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
            '_publish_version'    => 20180224134439,
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

    public function get_paper(){
        $paper_id  = trim($this->get_in_int_val('paper_id'));
        $paper = $this->t_student_test_paper->get_paper($paper_id);
      
        if($paper){
            return $this->output_succ(["paper"=>$paper,'status'=>200]);
        }else{
            return $this->output_succ(['status'=>201]);
        }
    }
}
