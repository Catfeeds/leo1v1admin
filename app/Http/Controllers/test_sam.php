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
    public function manager_list()
    {
    }
    public function test(){
        
    }



     public function  tt(){
        $ret = $this->t_course_order->get_course_list(1);
        foreach ($ret as $key => $value) {
            $ret[$key]["left_lesson_count"] = ($ret[$key]["assigned_lesson_count"]-$ret[$key]["finish_lesson_count"])/100;
        }
        foreach($ret as $key => $value){
            if($ret[$key]["left_lesson_count"] > 0){
                echo "<br>";
                echo $value['userid']."|".$value['courseid']."|".$value['left_lesson_count'];

                $ret_info = $this->t_course_order->update_course_status($ret[$key]['courseid']);
                if($ret_info){
                    echo "success";
                }
                echo "</br>";
            }
        }
    }
}
