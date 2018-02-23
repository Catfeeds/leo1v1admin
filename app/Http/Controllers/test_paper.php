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
            '_publish_version'    => 20180223134439,
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
                $data['answer'] = json_encode($answer);
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
                "dimension" => json_encode($dimension)
            ];
        }

        if( $save_type == 3 && !empty($question_bind) ){
            $data = [
                "modify_time" => time(),
                "adminid"    => $adminid,
                "question_bind" => json_encode($question_bind)
            ];
        }

        if( $save_type == 4 && !empty($suggestion) ){
            $data = [
                "modify_time" => time(),
                "adminid"    => $adminid,
                "suggestion" => json_encode($suggestion)
            ];
        }

        if( !empty($data)){
            $ret = $this->t_student_test_paper->field_update_list($paper_id,$data);
            return $this->output_succ('添加成功');
        }

    }

    public function get_paper(){
        $paper_id  = trim($this->get_in_int_val('paper_id'));
        $paper = $this->t_student_test_paper->get_paper($paper_id);
      
        if($paper){
            return $this->output_succ(["paper"=>$paper,'stauts'=>200]);
        }else{
            return $this->output_succ(['stauts'=>201]);
        }
    }
}
