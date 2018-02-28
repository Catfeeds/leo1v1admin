<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;
use App\Jobs\deal_wx_pic;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
require_once app_path("/Libs/Qiniu/functions.php");
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;
use OSS\Core\OssException;
class wjx_receive_api extends Controller
{
    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();

        if(!$this->check_ip_frequent() ){
            echo $this->output_err("当前操作过于频繁！");
            exit;
        }
    }

    //获取毫秒数
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    //检查该ip取数据的频繁次数,限制1秒取1次
    private function check_ip_frequent(){
        $redis= $this->get_redis();
    
        $oldTime = $redis->get($_SERVER["REMOTE_ADDR"]);
        $time = $this->getMillisecond();        
        
        if( $time - $oldTime <= 1000 ){
            return false;
        }else{
            $redis->set($_SERVER["REMOTE_ADDR"],$time);
        }

        return true;
    }

    //获取学生id和试卷id
    public function get_answers(){
        $grade = E\Egrade::$desc_map;
        $subject = E\Esubject::$desc_map;
        $params= $this->get_in_str_val("sojumpparm");
        $param_arr = explode("-", $params);
        if(count($param_arr) > 2){
            $paper_id = $param_arr[0];
            $user_id = $param_arr[1];
            $phone = $param_arr[2];
            $this->t_student_test_answer->row_insert([
                "paper_id"    =>$paper_id,
                "userid"      =>$user_id,
                "phone"       =>$phone,
            ]); 
        }
        print_r($param_arr);
        return "恭喜你啊！答完了";
    }

    //将学生的答案录入并且给出分数
    public function give_scores(){
        $data = file_get_contents("php://input");
        //$data =  '{"activity": "20980136","timetaken":"528","submittime":"2016-08-23 10:01:59", "q1":"A","q2": "B","q3":"A","q4":"C","q5":"A","q6":"C","q7":"B","q8":"A","q9":"C","q10":"D" }'; 
        \App\Helper\Utils::logger("学生的提交数据: $data");
        if($data){
            $answers = json_decode($data,true);
            $paper_id = $answers['activity'];
            $time_token = $answers['timetaken'];
            $submittime = $answers['submittime'];

            //查找该试卷的答案
            $paper = $this->t_student_test_paper->get_paper($paper_id);
            //学生每道题目的答案
            $content = [];
            //试卷标准答案
            $correct = [];
            //学生的每道题的得分情况
            $scores = [];
            //每个维度的得分情况
            $dimension_scores = [];
            //根据该维度提供建议
            $dimension_suggest = [];
  
            foreach($answers as $k => $v){
                if( !in_array($k, ["activity","timetaken","submittime"]) ){
                    $question_no = substr($k,1);
                    $content[$question_no] = $v;
                }
            }
            //dd($paper); 
            if($paper){
                //正确的答案
                $correct = json_decode($paper["answer"],true);
                //维度与题目的绑定
                $question_bind = json_decode($paper['question_bind'],true);
                foreach( $content as $no => $v){
                    //查看该题目所绑定的维度
                    $cur_dimension = 0;
                    if(is_array($question_bind)){
                        foreach( $question_bind as $di => $ques){
                            if( in_array($no,$ques)){
                                $cur_dimension = $di;
                                continue;
                            }
                        }
                    }
                   
                    if( (string)$v == (string)@$correct[$no][1] ){
                        $scores[$no] = (int)$correct[$no][2];
                    }else{
                        $scores[$no] = 0;
                    }
                    
                    if($cur_dimension != 0){
                        if(!array_key_exists($cur_dimension, $dimension_scores)){
                            $dimension_scores[$cur_dimension] = 0;
                        }
                        $dimension_scores[$cur_dimension] += $scores[$no];
                    }
                }

                //维度建议
                $suggestion = json_decode($paper['suggestion'],true);

                //根据每个维度的得分情况给出建议
                if($dimension_scores){
                    foreach( $dimension_scores as $di => $score){
                        //该维度的所有建议
                        $all_suggest = @$suggestion[$di];
                        if($all_suggest){
                            foreach( $all_suggest as $range => $sug ){
                                $sco_range = explode('-', $range);
                                $sco_min = $sco_range[0];
                                $sco_max = $sco_range[1];
                                if( $score >= $sco_min && $score <= $sco_max){
                                    $dimension_suggest[$di] = $sug;
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
            
            $last_answer = $this->t_student_test_answer->get_last_answer();
            if($last_answer){
                //dd($last_answer);
                $data = [
                    //"phone"   => @$last_answer['phone'],
                    //"userid"   => @$last_answer['userid'],
                    //"paper_id " => $paper_id ,
                    "time_token" => $time_token,
                    "submittime" => strtotime($submittime),
                    "student_answers" => json_encode($content),
                    "student_scores"  => json_encode($scores),
                    "dimension_scores"  => json_encode($dimension_scores),
                    "dimension_suggest"  => json_encode($dimension_suggest),
                ];
                $ret = $this->t_student_test_answer->field_update_list($last_answer['id'],$data);
            }
            dd("success");
        }
    }
    
}