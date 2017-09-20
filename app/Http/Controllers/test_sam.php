<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    
    public function lesson_list()
    {
      
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }

    private function get_work_url($work_value)
    {
        switch($work_value['work_status']) {
        case 1:
            return $work_value['issue_url'];
        case 2:
            return $work_value['finish_url'];
        case 3:
            return $work_value['check_url'];
        case 4:
            return $work_value['tea_research_url'];
        case 5:
            return $work_value['ass_research_url'];
        default:
            return '';
        }
    }



    public function manager_list()
    {
    }
    public function test(){
        
    }



     public function  tt(){

        //$t = 1507464000;
        $time = time();
        //$time = $t;
        $day_time = strtotime(date("Y-m-d",$time));
        $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
        $lesson_start = $lesson_end+1800;

        $lesson_list = $this->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);


        $day_time = date("Y-m-d H:i:s",$day_time);
        $lesson_end = date("Y-m-d H:i:s",$lesson_end);
        $lesson_start = date("Y-m-d H:i:s",$lesson_start);

        //
        $time = 1507464000;
        $date_time = date("Y-m-d",$time);       
        if($date_time == "2017-10-08"){
            //deal 2017-10-08 22:00:00

        }
       
    }        
}