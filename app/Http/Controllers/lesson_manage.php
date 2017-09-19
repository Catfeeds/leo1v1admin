<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class lesson_manage extends Controller
{
    public function stu_status_count()
    {
        $start_date   = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-86400*30 ));
        $end_date     = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)));
        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;
        $page_num     = $this->get_in_page_num();

        $startday = date("Ymd",$start_date_s);
        $endday   = date("Ymd",$end_date_s);

        $ret_info = $this->t_tongji->get_tongji_info($startday,$endday,$page_num);
        foreach($ret_info["list"] as &$item) {
            $item["new_course_count"]=intval($item["new_course_count"]);
            $item["old_course_count"]=intval($item["old_course_count"]);
        }

        return $this->Pageview(__METHOD__,$ret_info);
    }


    public function get_lesson_info() {
        $lessonid     = $this->get_in_lessonid();
        $row_data     = $this->t_lesson_info->field_get_list($lessonid,"*");
        $lesson_count = $this->t_course_order->get_default_lesson_count($row_data['courseid']);
        return $this->output_succ( ["data"=>$row_data,"lesson_count"=>$lesson_count]);

    }


}