<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_paper extends Controller
{

    function __construct( ) {
        parent::__construct();
    }
   
    public function input_paper() {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $subject      = $this->get_in_int_val('subject', -1);
        $grade        = $this->get_in_int_val('grade', -1);
        $paper_id     = trim($this->get_in_int_val('paper_id', -1) );
        $book         = $this->get_in_int_val("book",-1);
        $volume       = $this->get_in_int_val("volume",-1);
        $page_info    = $this->get_in_page_info();
        $ret_info = [];
        $ret_info = $this->t_student_test_paper->get_list($paper_id,$subject,$grade,$book,$volume,$start_time,$end_time,$page_info);
        if($ret_info){
            foreach($ret_info['list'] as &$item ){
                E\Egrade::set_item_field_list($item, [
                    "subject",
                    "grade"   
                ]);
                $item['volume_str'] = E\Eresource_volume::get_desc($item['volume']);
                $item['book_str'] = E\Eregion_version::get_desc($item['book']);
                $item["operator"] = $this->t_manager_info->get_name($item["adminid"]);
            }
        }
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180227134439,
        ]);
    } 

    public function save_paper_answer(){
        $subject      = $this->get_in_int_val('subject');
        $grade        = $this->get_in_int_val('grade');
        $paper_id     = trim($this->get_in_int_val('paper_id') );
        $paper_name   = $this->get_in_str_val("paper_name");
        $book         = $this->get_in_int_val("book");
        $volume       = $this->get_in_int_val("volume");
        $answer       = $this->get_in_str_val("answer");
        $dimension       = $this->get_in_str_val("dimension");
        $question_bind       = $this->get_in_str_val("question_bind");
        $suggestion       = $this->get_in_str_val("suggestion");
        $save_type   = $this->get_in_int_val('save_type');

        $adminid = $this->get_account_id();
        $data = [];
        if( $save_type == 1){
                    
            $data = [
                "paper_name" => $paper_name,
                "subject"    => $subject,
                "grade"      => $grade,
                "volume"     => $volume,
                "book"       => $book,
                "modify_time" => time(),
                "adminid"    => $adminid
            ];

            if(!empty($answer)){
                $new_answer = [];
                foreach( $answer as $v){
                    $new_answer[$v[0]] = [
                        $v[1],$v[2],$v[3]
                    ];
                }
                $data['answer'] = json_encode($new_answer);
            }

            $is_exist = $this->t_student_test_paper->check_paper_exist($paper_id);

            if($is_exist){
                //编辑
                $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
            }else{
                //添加
                $data["paper_id"] = $paper_id;
                $ret = $this->t_student_test_paper->row_insert($data);
            }
            return $this->output_succ('添加成功');
        }

        if( $save_type == 2 && !empty($dimension) ){
            $data = [
                "modify_time" => time(),
                "adminid"    => $adminid,
            ];

            $new_dimension = [];
            foreach( $dimension as $v){
                $new_dimension[$v[0]] = $v[1];
            }
            $data['dimension'] = json_encode($new_dimension);
        }

        if( !empty($data)){
            $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
            return $this->output_succ('添加成功');
        }

    }

    public function save_dimension_answer(){
        $paper_id     = trim($this->get_in_int_val('paper_id') );
        $dimension_id       = $this->get_in_int_val("dimension_id");
        $bind       = $this->get_in_str_val("bind");
        $paper = $this->t_student_test_paper->get_paper($paper_id);

        if($paper){
            $dimension = $paper['question_bind'];
            
            $new_dimension = json_encode([$dimension_id => $bind]);
            $data = ['question_bind'=>$new_dimension];
            if($dimension){
                $old_dimension = json_decode($dimension,true);
                $old_dimension[$dimension_id] = $bind;
                foreach( $old_dimension as $k => $v){
                    if($v == ""){
                        unset($old_dimension[$k]);
                    }
                }
                ksort($old_dimension);
                $data = ['question_bind'=>json_encode($old_dimension)];
            }
            if( !empty($data) ){
                $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
                return $this->output_succ(['status'=>200]);
            }
            return $this->output_succ(['status'=>201]);
        }else{
            return $this->output_err();
        }

    }

    public function save_suggestion(){
        $paper_id     = trim($this->get_in_int_val('paper_id') );
        $dimension_id       = $this->get_in_int_val("dimension_id");
        $score_min       = $this->get_in_int_val("score_min");
        $score_max       = $this->get_in_int_val("score_max");
        $suggestion       = trim($this->get_in_str_val("suggestion"));
        $paper = $this->t_student_test_paper->get_paper($paper_id);
        if($paper){
            $score_range = $score_min."-".$score_max;

            if($paper['suggestion']){
                $old_suggest = json_decode($paper['suggestion'],true);
                $old_suggest[$dimension_id][$score_range] = $suggestion;
                ksort($old_suggest);
                ksort($old_suggest[$dimension_id]);
                $data = ['suggestion'=>json_encode($old_suggest)];

            }else{
                $new_suggest[$dimension_id] = [ $score_range => $suggestion ];
                $data = ["suggestion" => json_encode($new_suggest)];
            }

            if( !empty($data) ){
                $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
                return $this->output_succ(['status'=>200]);
            }
            return $this->output_succ(['status'=>201]);

        }else{
            return $this->output_err();
        }
    }

    public function dele_suggestion(){
        $paper_id       = trim($this->get_in_int_val('paper_id') );
        $dimension_id   = $this->get_in_int_val("dimension_id");
        $score_range    = $this->get_in_str_val("score_range");
        $paper = $this->t_student_test_paper->get_paper($paper_id);
        if($paper){
            if($paper['suggestion']){
                $suggest = json_decode($paper['suggestion'],true);
                //$suggest[$dimension_id][$score_range] = "";
                unset($suggest[$dimension_id][$score_range]);
                //ksort($suggest);
                $data = ['suggestion'=>json_encode($suggest)];
                $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
                return $this->output_succ(['status'=>200]);
            }else{
                return $this->output_succ(['status'=>200,"info"=>"数据库未找到该条记录！"]); 
            }

        }else{
            return $this->output_err();
        }
    }

    public function get_paper(){
        $paper_id  = trim($this->get_in_int_val('paper_id'));
        $paper = $this->t_student_test_paper->get_paper($paper_id);
      
        if($paper){
            return $this->output_succ(["paper"=>$paper,'status'=>200]);
        }else{
            return $this->output_succ(['status'=>201]);
        }
    }

    public function dele_paper(){
        $paper_id  = trim($this->get_in_int_val('paper_id'));
        $dele_num = $this->t_student_test_paper->dele_paper($paper_id);
        if($dele_num){
            return $this->output_succ();
        }else{
            return $this->output_err();
        }
    }

    public function get_papers(){
        $subject  = $this->get_in_int_val('subject',-1);
        $grade  = $this->get_in_int_val('grade',-1);
        $book  = $this->get_in_int_val('book',-1);

        $page_num  = $this->get_in_page_num();
        $ret_info  = \App\Helper\Utils::list_to_page_info([]);

        $ret_info = $this->t_student_test_paper->get_papers($subject,$grade,$book,$page_num);
  
        if($ret_info){
            foreach($ret_info['list'] as &$item ){
                E\Egrade::set_item_field_list($item, [
                    "subject",
                    "grade"   
                ]);
                $item['volume_str'] = E\Eresource_volume::get_desc($item['volume']);
                $item['book_str'] = E\Eregion_version::get_desc($item['book']);
                $item["operator"] = $this->t_manager_info->get_name($item["adminid"]);
            }
        }

        return $this->output_ajax_table($ret_info, [
            "subject" => $subject,
            "grade" => $grade,
        ]);
    }

    //获取学生的分数
    public function get_student_scores(){
        $userid  = $this->get_in_int_val('userid',62721);
        $phone   = $this->get_in_int_val('phone',2147483647);
        $result  = ["status" => 201];
        $get_scores = $this->t_student_test_answer->get_scores($userid,$phone);
        //dd($get_scores);
        $ret = [];
        if($get_scores){          
            foreach( $get_scores as $v){
                $scores = [];
                $start_time = date("Y-m-d H:i", ($v['submittime'] - $v['time_token']));
                $subtime = date("Y-m-d H:i",$v['submittime']);
                $scores["name"] = $v['paper_id']."  ".$v['paper_name']."  ".$start_time;
                $scores["start_time"] = $start_time;
                $scores["subtime"] = $subtime;
                $scores["time_token"] = $v['time_token'];
                $scores["var"] = [];
                if($v['dimension_scores']){
                    $tr_show = [];
                    //每个维度的得分情况
                    $dimension_scores_arr = json_decode($v['dimension_scores'],true);
                    //根据每个维度的得分提供的建议
                    $dimension_suggest_arr = json_decode($v['dimension_suggest'],true);
                    //维度名称
                    $dimension = json_decode($v['dimension'],true);
                    //维度绑定的题目
                    $question_bind = json_decode($v['question_bind'],true);
                    //题目
                    $answer = json_decode($v['answer'],true);
                    //所有建议
                    $suggestion = json_decode($v['suggestion'],true);
                    foreach( $dimension_scores_arr as $di => $sco){
                        //该维度总分
                        $ques_arr = @$question_bind[$di];
                        $all_score = 0;
                        if($ques_arr){
                            foreach( $ques_arr as $q_no ){
                                $all_score += (int)$answer[$q_no][2];
                            }
                        }
                        $score_range_have = "";
                        //该分数落在哪个维度得分范围内
                        $score_range = @$suggestion[$di];
                        if($score_range){
                            foreach( $score_range as $s_range => $sug ){
                                if( $sug == $dimension_suggest_arr[$di]){
                                    $score_range_have = $s_range;
                                }
                            }
                        }
                        $tr_show[] = [ @$dimension[$di], $sco ,$all_score,$score_range_have,$dimension_suggest_arr[$di] ];                  
                    }

                    $scores["var"] = $tr_show;
                }

                $ret[] = $scores;
                $result['stauts'] = 200;
                $result['ret'] = $ret;
            }
        }
        //dd($ret);
        return $this->output_succ($result);

    }
}
