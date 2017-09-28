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
        $time = time();
        //$time = 1507464000; //2017/10/8 20:0:0      
        $date_time = date("Y-m-d",$time);       
        if($date_time == "2017-09-28"){
          //deal 2017-10-08 22:00:00
          $start_time = 1504195200;//2017/10/1 0:0:0
          $end_time   = 1506787200;//2017/10/8 22:0:0

          $lesson_info = $this->t_lesson_info_b2->get_qz_tea_lesson_info_b2($start_time,$end_time);
          $list=[];
          foreach($lesson_info as $val){
              if($val["lesson_type"]==1100 && $val["train_type"]==5){
                  @$list[$val["uid"]] += 0.8;
              }elseif($val["lesson_type"]==2){
                  @$list[$val["uid"]] += 1.5;
              }else{
                  @$list[$val["uid"]] += $val["lesson_count"]/100;
              }
          }
          $arr = [];
          foreach ($list as $key => $value) {
              $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($key);
              $teacherid = $teacher_info["teacherid"];
              $realname = $this->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['teacherid'] = $teacherid;
              @$arr[$key]['realname']  = $this->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['lesson_count'] = $value;
              @$arr[$key]['day_num'] = floor($value/10.5);
              @$arr[$key]['attendance_time'] = 1507478400;//2017/10/9 0:0:0
              if($arr[$key]['day_num'] == 0){
                @$arr[$key]['cross_time'] = "";
              }else{
                @$arr[$key]['cross_time'] = "10.9-".date('m-d',1507478400+$arr[$key]['day_num']*86400);
              }
              
          }
          //wx
          foreach ($arr as $key => $value) {
              $this->t_manager_info->send_wx_todo_msg_by_adminid (
                944,
                "国庆延休统计",
                "延休数据汇总",
                "\n老师:".$value['realname'].
                "\n时间:2017-10-1 0:0:0 ~ 2017-10-8 22:0:0".
                "\n累计上课课时:".$value['lesson_count'].
                "\n延休天数:".$value['day_num'].
                "\n延休日期:".$value['cross_time'],'');
          }
        $namelist = '';
        $num = 0;
        foreach ($arr as $key => $value) {
            if($value['day_num'] != 0){
                $namelist .= $value['realname'];
                $namelist .= ',';
                ++$num;
            }
        }
        $namelist = trim($namelist,',');

        $this->t_manager_info->send_wx_todo_msg_by_adminid (944,"国庆延休统计","全职老师国庆延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); 
          
        }  
    }
}
