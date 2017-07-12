<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class revisit extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }


    public function get_revisit_info()
    {
        $userid = $this->get_in_userid();
        $phone = $this->get_in_phone_ex();
        if ($userid) { //得到phone
            $phone_row=$this->t_phone_to_user->get_phone_role_by_userid($userid);
            if ($phone_row) {
                $phone=trim($phone_row["phone"]);
                if (preg_match("/[0-9]*/",$phone,$matches)) {
                    $phone= $matches[0] ;
                }
            }
        }else{
            $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        }
        $ret_db_userid=[];
        if ($userid)  {
            $ret_db_userid = $this->t_revisit_info->get_all_revisit_ex($userid);
        }
        $ret_db_phone=[];
        if ($phone) {

            $revisit_show_all_flag = $this->t_lesson_info-> get_book_user_lesson_count($userid)>0;
            //TODO
            //$revisit_show_all_flag =true;
            $ret_db_phone=$this->t_book_revisit->get_book_revisit_list($phone ,$revisit_show_all_flag  );
        }
        $ret_db=array_merge($ret_db_phone,$ret_db_userid);
        usort($ret_db,function($a,$b){
            if ($a["revisit_time"]==$b["revisit_time"]) return 0;
            return ($a["revisit_time"]<$b["revisit_time"])?1:-1;

        });

        foreach($ret_db as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time");
            $item['revisit_type']  = E\Erevisit_type::get_desc($item['revisit_type']);
        }

        $revisit_num = count($ret_db);
        array_unshift($ret_db,[
            "revisit_type"  => "TMK 备注",
            "operator_note" => $this->t_seller_student_new->get_tmk_desc($userid),
        ]);
        //$ret_db
        return outputJson(array('ret' => 0, 'info' => "success", 'revisit_num' => $revisit_num,
                    'revisit_list' => $ret_db));

    }

    public function get_revisit_info_new()
    {
        $userid = $this->get_in_userid();      
        $ret_db = $this->t_revisit_info->get_all_revisit_limit_list($userid);             
        foreach($ret_db as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time");
            $item['revisit_type']  = E\Erevisit_type::get_desc($item['revisit_type']);
            E\Eset_boolean::set_item_value_str($item,"operation_satisfy_flag");
            E\Eset_boolean::set_item_value_str($item,"school_work_change_flag");
            E\Etea_content_satisfy_flag::set_item_value_str($item,"tea_content_satisfy_flag");
            E\Eschool_work_change_type::set_item_value_str($item,"school_work_change_type");
            E\Eschool_score_change_flag::set_item_value_str($item,"school_score_change_flag");
            E\Eoperation_satisfy_type::set_item_value_str($item,"operation_satisfy_type");
            E\Etea_content_satisfy_type::set_item_value_str($item,"tea_content_satisfy_type");
            E\Echild_class_performance_flag::set_item_value_str($item,"child_class_performance_flag");
            E\Echild_class_performance_type::set_item_value_str($item,"child_class_performance_type");
            E\Eis_warning_flag::set_item_value_str($item,"is_warning_flag");

        }

        return outputJson(array('ret' => 0, 'info' => "success", 'revisit_list' => $ret_db));

    }


    public function add_revisit_record()
    {
        $userid         = intval($this->get_in_int_val('userid',-1));
        $revisit_type   = $this->get_in_int_val('revisit_type',0);
        $revisit_person = trim($this->get_in_str_val('revisit_person'));
        $revisit_time = $this->get_in_str_val('revisit_time');
        $operator_note  = trim($this->get_in_str_val('operator_note'));
        $operation_satisfy_flag =$this->get_in_int_val("operation_satisfy_flag",0);
        $operation_satisfy_type =$this->get_in_int_val("operation_satisfy_type",0);
        $record_tea_class_flag =$this->get_in_int_val("record_tea_class_flag",0);
        $tea_content_satisfy_flag =$this->get_in_int_val("tea_content_satisfy_flag",0);
        $tea_content_satisfy_type=$this->get_in_int_val("tea_content_satisfy_type",0);
        $operation_satisfy_info= trim($this->get_in_str_val("operation_satisfy_info",""));
        $child_performance = trim($this->get_in_str_val("child_performance",""));
        $tea_content_satisfy_info= trim($this->get_in_str_val("tea_content_satisfy_info",""));
        $other_parent_info= trim($this->get_in_str_val("other_parent_info",""));
        $other_warning_info= trim($this->get_in_str_val("other_warning_info",""));
        $child_class_performance_flag =$this->get_in_int_val("child_class_performance_flag",0);
        $child_class_performance_type =$this->get_in_int_val("child_class_performance_type",0);
        $child_class_performance_info = trim($this->get_in_str_val("child_class_performance_info",""));
        $school_score_change_flag  =$this->get_in_int_val("school_score_change_flag",0);
        $school_score_change_info = trim($this->get_in_str_val("school_score_change_info",""));
        $school_work_change_flag  =$this->get_in_int_val("school_work_change_flag",0);
        $school_work_change_type =$this->get_in_int_val("school_work_change_type",0);
        $school_work_change_info = trim($this->get_in_str_val("school_work_change_info",""));
        if($operation_satisfy_flag>1 || $child_class_performance_flag>2 || $school_score_change_flag>1 || $school_work_change_flag==1 || $tea_content_satisfy_flag>2){
            $is_warning_flag=1;
        }else{
            $is_warning_flag=0;
        }



        $acc = $this->get_account();

        if(empty($revisit_time)){
            $revisit_time = time();
        }else{
            $revisit_time = strtotime($revisit_time);
        }

        $ret_stu      = $this->t_student_info->get_student_simple_info($userid);
        if(count($ret_stu) == 0){
            return  $this->output_err( "系统出错");
        }


        if ( $this->t_revisit_info->check_add_existed($userid,$revisit_time) ) {
            return  $this->output_succ();
        }

        $ret_add = $this->t_revisit_info->add_revisit_record($userid, $revisit_time, $ret_stu['nick'], $revisit_person,
                                                             $acc, $operator_note, $revisit_type,null, $operation_satisfy_flag,$operation_satisfy_type,$record_tea_class_flag,$tea_content_satisfy_flag,$tea_content_satisfy_type,$operation_satisfy_info,$child_performance,$tea_content_satisfy_info,$other_parent_info,$other_warning_info,$child_class_performance_flag,$child_class_performance_info,$child_class_performance_type,$school_work_change_flag,$school_score_change_flag,$school_work_change_info,$school_work_change_type,$school_score_change_info,$is_warning_flag);
        //get_week_start_time
        $week_info=\App\Helper\Utils::get_week_range(time(NULL),1);
        $week_start_time=$week_info["sdate"];

        //E\Erevisit_type
        $max_revisit_time = $this->t_revisit_info->get_max_revisit_time($userid);

        if ( $revisit_type==0 ||  $revisit_type==2 ){
            $set_arr=[];
            if($revisit_time >= $max_revisit_time){
                $set_arr["ass_revisit_last_week_time"]= $revisit_time ;
            }
            if ( $revisit_type==2  ) {
                $set_arr["ass_revisit_last_month_time"]=  $week_start_time;
            }
            if (count ($set_arr) >0)   {
                $this->t_student_info->field_update_list($userid, $set_arr);
            }
        }

        return  $this->output_succ();

    }


}