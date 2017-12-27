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
require_once  app_path("/Libs/Qiniu/functions.php");
//require(app_path("/Libs/OSS/autoload.php"));
//use OSS\OssClient;
//use OSS\Core\OssException;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;
use OSS\Core\OssException;
//use Illuminate\Support\Facades\Redis;
class question_new_api extends Controller
{
    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();

        if(!$this->check_ip_frequent() ){
            echo $this->output_err("当前操作过于频繁！");
            exit;
        }

        if (! $this->get_agent_id()){
            // echo $this->output_err("未登录");
            // exit;
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

    public function get_agent_id(){
        $agent_id= $this->get_in_int_val("_agent_id")?$this->get_in_int_val("_agent_id"):session("agent_id");
        return $agent_id;
    }

    //获取年级和科目
    public function get_grade_and_subject(){
        $grade = E\Egrade::$desc_map;
        $subject = E\Esubject::$desc_map;
        return $this->output_succ(['grade' => $grade,'subject' => $subject]);
    }

    //获取所有教材名字和教材id、科目名字和科目id
    public function get_textbook_subject(){
        $subject = $this->get_in_int_val('subject',-1);
        $list = $this->t_textbook->textbook_list($subject, 1);
        if($list){
            foreach( $list as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            }
        }

        return $this->output_succ(["list" => $list]);
    }

    //获取教材年级对应的知识点
    public function get_textbook_knowledge(){
        $textbook_id   = $this->get_in_int_val('textbook_id');
        $subject   = $this->get_in_int_val('subject');
        $grade   = $this->get_in_int_val('grade');
        $list = $this->t_textbook_knowledge->textbook_knowledge_get($textbook_id,$grade,$subject);
        
        return $this->output_succ(["list" => $list]);
    }

    //根据该科目获取该科目所有的题型和题型来源和难度等级
    public function get_question_type_and_resource(){
        $subject = $this->get_in_int_val('subject');
        $question_type = $this->t_question_type->question_type_list($subject,1);
        if($question_type){
            foreach( $question_type as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            }
        }
        $difficult = E\Equestion_difficult_new::$desc_map;
        $question_resource_type = E\Equestion_resource_type::$desc_map;

        return $this->output_succ(["question_type" => $question_type,"difficult" => $difficult,"question_resource_type" => $question_resource_type]);
    }

    //根据知识点、题型、来源、难度 获取对应的题目
    public function get_questions(){
        $knowledge_id   = $this->get_in_int_val('knowledge_id');
        $knowledge_str = '(';
        if($knowledge_id){
            //获取该知识点的子级id
            $knowledge_str .= $this->get_tree($knowledge_id);
            $knowledge_str = substr($knowledge_str, 0, -1).')';
        }
        //dd($knowledge_str);
        $question_type   = $this->get_in_int_val('question_type',-1);
        $question_resource_type   = $this->get_in_int_val('question_resource_type',-1);
        $difficult   = $this->get_in_int_val('difficult',-1);
        $page_num    = $this->get_in_int_val('page_num',1);
        $questions = $this->t_question->question_get($knowledge_str,$question_type,$question_resource_type,$difficult,$page_num);
        //dd($questions);
        if($questions){
            foreach( $questions['list'] as &$qu){
                $qu['subject_str'] = E\Esubject::get_desc($qu['subject']);
                $qu['difficult_str'] = E\Equestion_difficult_new::get_desc($qu['difficult']);
                $qu['question_resource_type_str'] = E\Equestion_resource_type::get_desc($qu['question_resource_type']);
                //$qu = ksort($qu);
            }
        }

        return $this->output_succ(["list" => $questions]);
    }

    private function get_tree($pid){  
        $know_str = "$pid,";                                //每次都声明一个新数组用来放子元素
        $children = $this->t_knowledge_level->get_by_father_id($pid);
        if($children){
            foreach($children as $v){  
                $know_str .= $this->get_tree($v['knowledge_id']); //递归获取子记录                   
            }
        }
        
        return $know_str;                                  //返回新数组  
    }


    //根据题目获取对应的解题、解析、答案
    public function get_answers(){
        $question_id   = $this->get_in_int_val('question_id');
        $where_arr = [
            ["question_id=%d" , $question_id ],
        ];
        $ret = $this->t_answer->answer_list($where_arr);
       
        $i = 0;
        $answer_type = 1;
        $answer_no = 0;
        $answer_arr = [];
        if($ret){
            foreach( $ret as &$item ){
                $item['difficult_str'] = E\Equestion_difficult_new::get_desc($item['difficult']);

                if( $answer_type == $item['answer_type']){
                    $i++;
                }else{
                    $answer_type = $item['answer_type'];
                    $i = 1;
                }

                $item['step_str'] = $item['answer_type_name'].$i;

                if( $answer_no != $item['answer_no'] ){
                    $answer_no = $item['answer_no'];
                }

                //取出题目对应的知识点
                $item['know_str'] = '';
                $know_arr = $this->t_question_knowledge->answer_know_get($item['step_id']);
                if($know_arr){
                    $item['know_str'] = $know_arr;
                }

                $answer_arr[$answer_no][] = $item;
            }
        }

        return $this->output_succ(["list" => $answer_arr]);

    }

    public function save_answers(){
        $data   = $this->get_in_str_val('data');
        if(empty($data)){
            return $this->output_err('不能传空值');
        }
        //dd($data);
        $arr = json_decode($data,true);
        //dd($arr);
        if(empty($arr) || !is_array($arr)){
            return $this->output_err('请传json格式的数组');
        }
        //$arr = $arr[0];
        $insertCheck = [
            "sid"  => 0,
            "tid" => 0,
            "qid" => 0,
            "rid" => 0,
            "scores" => 0,
            "time"   => 0,
        ];
        
        $check1 = array_diff_key($insertCheck,$arr);
        $check2 = array_diff_key($arr,$insertCheck);
        //dd($check1);
        if(!empty($check1)){
            $lackkey = implode(',',array_keys($check1));
            $msg = "sid,tid,qid,rid,scores,time都是数组必须具备的值,你遗漏了".$lackkey;
            return $this->output_err($msg);
        }
        if(!empty($check2)){
            $morekey = implode(',',array_keys($check2));
            $msg = "数组中只需传sid,tid,qid,rid,scores,time你多传了".$morekey;
            return $this->output_err($msg);
        }
        
        $scores = $arr['scores'];
        if(count($arr) == count($arr, 1)){
            return $this->output_succ('分数scores是个二维数组不是一维数组');
        }

        //所要保存的条数
        $saveItem = count($scores);
        //已经保存的条数
        $haveSave = 0;
        //保存未成功的条数
        $lackItem = "以下未保存成功:第";
        foreach($scores as $k => $item){
            $k += 1;
            $item = [
                'student_id'=>$arr['sid'],
                'teacher_id'=>$arr['tid'],
                'question_id'=>$arr['qid'],
                'room_id'=>$arr['rid'],
                'time'=>$arr['time'],
                'score'=>$item['score'],
                'step_id'=>$item['step_id'],
                'create_time' => time(),
            ];
            $ret = $this->t_student_answer->row_insert($item);
            if($ret){
                $haveSave ++;
            }else{
                $lackItem .= $k.",";
            }
        }
        $lackItem = substr($lackItem, 0, -1);
        if( $haveSave < $saveItem ){
            return $this->output_err($lackItem);
        }else{
            return $this->output_succ();
        }
    }

    public function get_recommend(){
        $room_id = $this->get_in_int_val('rid');
        if( !$room_id ){
            return $this->output_err("请传房间id");
        }
        
        $count = $this->t_student_answer->get_answer_count($room_id);
        if(!$count || $count['count'] == 0){
            return $this->output_err("学生还没做题,无法推荐");
        }

        //学生做过的题目
        $have_done = [];

        //查看每个题目的答案得分情况
        $answer_scores = $this->t_student_answer->get_answer_scores($room_id);

        //每个知识点对应步骤解题的得分情况
        $result = []; 

        if( $answer_scores ){
            foreach( $answer_scores as $sc){
                if(!in_array($sc['question_id'], $have_done)){
                    $have_done = $sc['question_id'];
                }

                //解题步骤对应的知识点
                $solve_arr = [];
                if($sc['know_str']){
                    $solve_arr = explode(',', $sc['know_str']);        
                }

                $answer_arr = [];
                //答题步骤对应的知识点
                if( empty( $sc['know_str']) ){
                    //查找该题目对应的知识点
                    $answer_arr = $this->t_question->question_know_get($sc['question_id']);
                }

                //每个步骤的得分情况
                if( $sc['full_score'] > 0 and $sc['score'] >= 0){
                    //该步骤的分数情况
                    $score =  sprintf("%.2f", $sc['score']/$sc['full_score']);

                    //解题步骤对应的知识点得分
                    if($solve_arr){
                        foreach( $solve_arr as $v ){
                            $result[$v][$sc['step_id']] = $score;
                        }
        
                    }

                    //答题步骤对应的知识点得分
                    if($answer_arr){
                        foreach( $answer_arr as $k){
                            $result[$k['knowledge_id']][$sc['step_id']] = $score;
                        }
                    }
                    
                }

            }
        }
        $question_str = '';
        if($have_done){
            $question_str = "(".implode(",", $have_done).")";
        }
        $knowledge_str = "";
        $know_arr = [];
        if($result and count($result) != count($result, 1) ){
            $wrong = rsort($wrong);
            foreach( $result as $k => $v){
                $full = count($v);
                $each_whole = 0;
                foreach($v as $item){
                    $each_whole += $item;
                }
                $know_arr[$k] = sprintf("%.2f", $each_whole/$full);
            }
            $know_arr = sort($know_arr);        
            $knowledge_str .= "(".implode(",", $know_arr);
        }
        
        $questions = $this->get_questions_by_kid($knowledge_str,$question_str);
        return $this->output_succ(["list" => $questions]);
    }

    private function get_questions_by_kid($knowledge_str,$question_str){
        $questions = $this->t_question->question_get_by_id($knowledge_str,$question_str);
        //dd($questions);
        if($questions){
            foreach( $questions['list'] as &$qu){
                $qu['subject_str'] = E\Esubject::get_desc($qu['subject']);
                $qu['difficult_str'] = E\Equestion_difficult_new::get_desc($qu['difficult']);
                $qu['question_resource_type_str'] = E\Equestion_resource_type::get_desc($qu['question_resource_type']);
                //$qu = ksort($qu);
            }
        }
        return $questions;
    }

}