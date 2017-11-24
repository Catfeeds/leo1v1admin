<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class fulltime_teacher_kaoqin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_kaoqin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全职/教研老师考勤';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $time = time();
        $day_time = strtotime(date("Y-m-d",$time));
        
        $w = date("w");

        //全职老师提前下班
        if($w !=1 && $w != 2){
            $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
            $lesson_start = $lesson_end+1800;
            $lesson_list = $task->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
            foreach($lesson_list as $item){
                $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($item["uid"]);
                $teacherid = $teacher_info["teacherid"];
                $check_exist = $task->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
                if($check_exist != 1){
                    $start = $task->get_first_lesson_start($teacherid,$item["lesson_start"]);
                    //$lesson_end = $item["lesson_start"]-5400;
                    // $start = $task->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$item["lesson_start"]);
                    $off_time = $start-5400;
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>2,
                        "attendance_time"  =>$day_time,
                        "off_time"         =>$off_time,
                        "adminid"          =>$item["uid"]
                    ]);
                }
            }
        }


        //节假日延休        
        $festival_info = $task->t_festival_info->get_festival_info_by_end_time($day_time);
        if($festival_info){
            $attendance_day = $day_time+86400;
            $lesson_info = $task->t_lesson_info_b2->get_qz_tea_lesson_info_b2($festival_info["begin_time"],$attendance_day);
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
                $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($key);
                $teacherid = $teacher_info["teacherid"];
                $realname = $task->t_teacher_info->get_realname($teacherid);
                @$arr[$key]['teacherid'] = $teacherid;
                @$arr[$key]['realname']  = $task->t_teacher_info->get_realname($teacherid);
                @$arr[$key]['lesson_count'] = $value;
                @$arr[$key]['day_num'] = floor($value/10.5);
                @$arr[$key]['attendance_time'] = $attendance_day;
                @$arr[$key]['holiday_end_time'] = $attendance_day+($arr[$key]['day_num']-1)*86400;
                if($arr[$key]['day_num'] == 0){
                    @$arr[$key]['cross_time'] = "";
                }else{
                    @$arr[$key]['cross_time'] = date('m.d',$attendance_day)."-".date('m.d',$arr[$key]['holiday_end_time']);
                }
              
            }
            //insert data
            foreach ($arr as $key => $value) {
                if($value['day_num']>=1){
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"        =>$value['teacherid'],
                        "add_time"         =>$time,
                        "attendance_type"  =>3,
                        "attendance_time"  =>$value["attendance_time"],
                        "day_num"          =>$value['day_num'],
                        "adminid"          =>$key,
                        "lesson_count"     =>$value['lesson_count']*100,
                        "holiday_end_time" =>$value["holiday_end_time"],
                    ]);
                } 
            }
            //wx
            foreach ($arr as $key => $value) {
                $task->t_manager_info->send_wx_todo_msg_by_adminid (
                    $key,
                    $festival_info["name"]."延休统计",
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
            $task->t_manager_info->send_wx_todo_msg_by_adminid (72, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //erick
            $task->t_manager_info->send_wx_todo_msg_by_adminid (480, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //low-key

            //email
            $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">全职老师假期累计上课时间及延休安排</td></tr>';
            $table .= '<tr><td>假期名称</td><td><font color="red">'.$festival_info["name"].'</font></td><td></td><td></td></tr>';
            $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
            foreach ($arr as $key => $value) {
                if($value['day_num'] != 0){
                    $table .= '<tr>';
                    $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                    $table .= '</tr>';
                }
            }
            $table .= "</table>";
            $content = "Dear all：<br>全职老师".$festival_info["name"]."延休安排情况如下<br/>";
            $content .= "数据见下表<br>";
            $content .= $table;
            $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优教育</div><div>";
            $email_arr = ["low-key@leoedu.com",
                          "erick@leoedu.com",
                          "hejie@leoedu.com",
                          "sherry@leoedu.com",
                          "cindy@leoedu.com",
                          "limingyu@leoedu.com"];
            foreach($email_arr as $email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,
                    "全职老师".$festival_info["name"]."假期累计上课时间及延休安排",
                    $content
                ));  
            }

        }





        //第二天满8课时,在家办公(教研/全职老师)
        // if($w >=2){
        $start_time = strtotime(date("Y-m-d",$time))+86400;
        $end_time = $start_time + 86400;
        $lesson_info = $task->t_lesson_info_b2->get_qz_tea_lesson_info($start_time,$end_time);
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
        $name_list ="";
        $num=0;
        $name_list_research="";
        $num_research=0;
        foreach($list as $k=>$item){
            if($item>=8){
                $account_role = $task->t_manager_info->get_account_role($k);
                if($account_role==5 && $w >=2){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"在家办公通知","明天课时满8课时可在家办公","老师您好,您明天的课时满8小时,可以在家办公","");
              
                    $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);                   
                    $teacherid = $teacher_info["teacherid"];
                    $realname = $task->t_teacher_info->get_realname($teacherid);
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>1,
                        "attendance_time"  =>strtotime(date("Y-m-d",$time+86400)),
                        "day_num"           =>1,
                        "adminid"           =>$k,
                        "lesson_count"      =>$item*100
                    ]);
 
                    $name_list .= $realname.",";
                    $num++;
                }elseif($account_role==4){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"在家办公通知","明天课时满8课时可在家办公","老师您好,您明天的课时满8小时,可以在家办公","");
              
                    $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);                   
                    $teacherid = $teacher_info["teacherid"];
                    $realname = $task->t_teacher_info->get_realname($teacherid);
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>1,
                        "attendance_time"  =>strtotime(date("Y-m-d",$time+86400)),
                        "day_num"           =>1,
                        "adminid"           =>$k,
                        "lesson_count"      =>$item*100
                    ]);

                    $name_list_research .= $realname.",";
                    $num_research++;
                }
 
            }
        }
        $name_list = trim($name_list,",");
        $name_list_research = trim($name_list_research,",");
        if($num>0){
            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (480,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");                               
            $task->t_manager_info->send_wx_todo_msg_by_adminid (986,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");

        }
        if($num_research>0){
            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"在家办公通知","明天在家办公教研老师名单","明天有如下".$num_research."位老师满8课时,可在家办公,具体名单如下:".$name_list_research,"");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"在家办公通知","明天在家办公教研老师名单","明天有如下".$num_research."位老师满8课时,可在家办公,具体名单如下:".$name_list_research,"");                               

        }


        //ex once
        $time = time();
        //$time = 1507464000; //2017/10/8 20:0:0      
        $date_time = date("Y-m-d",$time);       
        if($date_time == "2017-10-08"){
          //deal 2017-10-08 22:00:00
          $start_time = 1506787200;//2017/10/1 0:0:0
          $end_time   = 1507471200;//2017/10/8 22:0:0

          $lesson_info = $task->t_lesson_info_b2->get_qz_tea_lesson_info_b2($start_time,$end_time);
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
              $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($key);
              $teacherid = $teacher_info["teacherid"];
              $realname = $task->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['teacherid'] = $teacherid;
              @$arr[$key]['realname']  = $task->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['lesson_count'] = $value;
              @$arr[$key]['day_num'] = floor($value/10.5);
              @$arr[$key]['attendance_time'] = 1507478400;//2017/10/9 0:0:0
              if($arr[$key]['day_num'] == 0){
                  @$arr[$key]['cross_time'] = "";
              }else{
                  @$arr[$key]['cross_time'] = "10.9-".date('m.d',1507478400+($arr[$key]['day_num']-1)*86400);
              }
              
          }
          //insert data
          foreach ($arr as $key => $value) {
            if($value['day_num']>=1){
              $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"        =>$value['teacherid'],
                        "add_time"         =>$time,
                        "attendance_type"  =>3,
                        "attendance_time"  =>1507478400,
                        "day_num"          =>$value['day_num'],
                        "adminid"          =>$key,
                        "lesson_count"     =>$value['lesson_count']*100,
                    ]);
              } 
          }
          //wx
          foreach ($arr as $key => $value) {
              $task->t_manager_info->send_wx_todo_msg_by_adminid (
                $key,
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
          $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"国庆延休统计","全职老师国庆延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //erick
          $task->t_manager_info->send_wx_todo_msg_by_adminid (480,"国庆延休统计","全职老师国庆延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //low-key

          //email
          $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">全职老师假期累计上课时间及延休安排</td></tr>';
          $table .= '<tr><td>假期名称</td><td><font color="red">国庆节</font></td><td></td><td></td></tr>';
          $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
          foreach ($arr as $key => $value) {
              if($value['day_num'] != 0){
                  $table .= '<tr>';
                  $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                  $table .= '</tr>';
              }
          }
          $table .= "</table>";
          $content = "Dear all：<br>全职老师国庆延休安排情况如下<br/>";
          $content .= "数据见下表<br>";
          $content .= $table;
          $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优教育</div><div>";
          $email_arr = ["low-key@leoedu.com",
                        "erick@leoedu.com",
                        "hejie@leoedu.com",
                        "sherry@leoedu.com",
                        "cindy@leoedu.com",
                        "limingyu@leoedu.com"];
          foreach($email_arr as $email){
             dispatch( new \App\Jobs\SendEmailNew(
                $email,
                "全职老师国庆假期累计上课时间及延休安排",
                $content
             ));  
          }
        }         
    }
}
