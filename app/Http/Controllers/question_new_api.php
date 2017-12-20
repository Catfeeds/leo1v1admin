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
class question_new_api extends Controller
{
    use CacheNick;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
        if (! $this->get_agent_id()){
            // echo $this->output_err("未登录");
            // exit;
        }
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
        $question_type   = $this->get_in_int_val('question_type',-1);
        $question_resource_type   = $this->get_in_int_val('question_resource_type',-1);
        $difficult   = $this->get_in_int_val('difficult',-1);
        $page_num    = $this->get_in_int_val('page_num',1);
        $questions = $this->t_question->question_get($knowledge_id,$question_type,$question_resource_type,$difficult,$page_num);
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

    //根据题目获取对应的解题、解析、答案
    public function get_answers(){
        $question_id   = $this->get_in_int_val('question_id');
        $where_arr = [
            ["question_id=%d" , $question_id ],
        ];
        $ret = $this->t_answer->answer_list($where_arr);

        $i = 0;
        $type = 1;
        if($ret){
            foreach( $ret as &$item ){
                $item['difficult_str'] = E\Equestion_difficult_new::get_desc($item['difficult']);
                $item['answer_type_str'] = E\Eanswer_type::get_desc($item['answer_type']);

                if( $type == $item['answer_type']){
                    $i++;
                    $item['step_str'] = E\Eanswer_type::get_desc($type).$i;
                }else{
                    $type = $item['answer_type'];
                    $i = 1;
                    $item['step_str'] = E\Eanswer_type::get_desc($type).$i;
                }

                //取出题目对应的知识点
                $item['know_str'] = '';
                $know_arr = $this->t_question_knowledge->answer_know_get($item['answer_id']);
                if($know_arr){
                    $item['know_str'] = $know_arr;
                }
            }
        }

        return $this->output_succ(["list" => $ret]);

    }


}