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
        $h = date("H");

        if($h<11){
            //list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

            $end_time = strtotime(date("Y-m-d",$time));
            $start_time = $end_time-86400;
            //当日课时计算
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

            //考勤信息
            $date_list_old=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
            $date_arr=[];
            foreach($date_list_old as $k=>$val){
                $time1 = strtotime($k);
                $date_arr[$time1]["date"]=$time1;
            }
            $adminid_list = $task->t_manager_info->get_adminid_list_by_account_role(5);
            $ret_info=$task->t_admin_card_log->get_list( 1, $start_time,$end_time,-1,100000,5 );
            $data=[];
            foreach($adminid_list as $k=>$val){
                $date_list = $date_arr;
                foreach($ret_info["list"] as $item){
                    if($item["uid"]==$k){
                        $logtime=$item["logtime"];
                        $opt_date=strtotime(date("Y-m-d",$logtime));
                        $date_item= &$date_list[$opt_date];
                        if (!isset($date_item["start_logtime"])) {
                            $date_item["start_logtime"]=$logtime;
                            $date_item["end_logtime"]=$logtime;
                        }else{
                            if ($date_item["start_logtime"] > $logtime  ) {
                                $date_item["start_logtime"] = $logtime;
                            }
                            if ($date_item["end_logtime"] < $logtime  ) {
                                $date_item["end_logtime"] = $logtime;
                            }
                        }

                    }
                }
                $data[$k] = $date_list;

            }
            foreach($data as $key=>$p_item){
                $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($key);
                $teacherid = $teacher_info["teacherid"];
                if($teacherid>0){
                    foreach($p_item as $k=>$v){
                        $card_start_time = isset($v["start_logtime"])?$v["start_logtime"]:0;
                        $card_end_time = isset($v["end_logtime"])?$v["end_logtime"]:0;
                        $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$k,-1,$key);
                        $attendance_type = $task->t_fulltime_teacher_attendance_list->get_attendance_type($id);
                        $w = date("w",$k);
                        if($w>0 && $w<3 &&  $card_start_time>0 ){
                            $attendance_type=4;
                        }

                        if($id>0){
                            $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                                "card_start_time"  =>$card_start_time,
                                "card_end_time"   =>$card_end_time,
                                "attendance_type" =>$attendance_type,
                                "lesson_count"    =>@$list[$key]*100
                            ]);
                        }else{
                            $task->t_fulltime_teacher_attendance_list->row_insert([
                                "teacherid"  =>$teacherid,
                                "add_time"   =>$time,
                                "attendance_time"  =>$k,
                                "adminid"           =>$key,
                                "card_start_time"  =>$card_start_time,
                                "card_end_time"   =>$card_end_time,
                                "attendance_type" =>$attendance_type,
                                "lesson_count"    =>@$list[$key]*100
                            ]);
                        }
                    }
                }
            }
        }elseif($h>20){
            $day_time = strtotime(date("Y-m-d",$time));
            $w = date("w");

            //全职老师上班打卡延后时间/提前下班
            if($w !=1 && $w != 2){
                //再度校验当天课时是否满8课时
                $start_time = strtotime(date("Y-m-d",$time));
                $lesson_info = $task->t_lesson_info_b2->get_qz_tea_lesson_info($start_time,$time);
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
                foreach($list as $k=>$item){
                    if($item>=8){
                        $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);
                        $teacherid = $teacher_info["teacherid"];
                        $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$start_time,-1,$k);
                        if($id>0){
                            $attendance_type = $task->t_fulltime_teacher_attendance_list->get_attendance_type($id);
                            if(in_array($attendance_type,[0,2])){
                                $rt = $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                                    "teacherid"       => $teacherid,
                                    "add_time"        => $time,
                                    "attendance_type" => 1,
                                    "attendance_time" => $start_time,
                                    "day_num"         => 1,
                                    "adminid"         => $k,
                                    "lesson_count"    => $item*100
                                ]);
                            }
                        }else{
                            $task->t_fulltime_teacher_attendance_list->row_insert([
                                "teacherid"       => $teacherid,
                                "add_time"        => $time,
                                "attendance_type" => 1,
                                "attendance_time" => $start_time,
                                "day_num"         => 1,
                                "adminid"         => $k,
                                "lesson_count"    => $item*100
                            ]);
                        }
                    }
                }

                //全职老师上班打卡延后时间
                $begin_time = $day_time+9.5*3600;
                $lesson_start = strtotime(date("Y-m-d",$time)." 09:00:00");
                $list = $task->t_lesson_info_b2->get_delay_work_time_lesson_info($day_time,$lesson_start);
                foreach($list as $item){
                    $teacherid = $item["teacherid"];
                    if($item["lesson_type"]==2){
                        $lesson_end = $item["lesson_end"]+1200;
                    }else{
                        $lesson_end = $item["lesson_end"];
                    }
                    $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$day_time,-1,$item["uid"]);
                    $attendance_type = $task->t_fulltime_teacher_attendance_list->get_attendance_type($id);
                    if($id>0 && in_array($attendance_type,[0,2])){
                        $end = $task->get_last_lesson_end($teacherid,$lesson_end);
                        $delay_time = $end+5400;
                        if($delay_time>$begin_time){
                            $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                                "delay_work_time" =>$delay_time,
                                "attendance_type" =>2,
                            ]);
                        }

                    }elseif(empty($id)){
                        $end = $task->get_last_lesson_end($teacherid,$lesson_end);
                        $delay_time = $end+5400;
                        if($delay_time>$begin_time){
                            $task->t_fulltime_teacher_attendance_list->row_insert([
                                "teacherid"  =>$teacherid,
                                "add_time"   =>time(),
                                "attendance_type" =>2,
                                "attendance_time"  =>$day_time,
                                "delay_work_time"  =>$delay_time,
                                "adminid"          =>$item["uid"]
                            ]);

                        }
                    }
                }

                //全职老师提前下班
                $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
                $lesson_start = $lesson_end+1800;
                $lesson_list = $task->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
                foreach($lesson_list as $item){
                    $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($item["uid"]);
                    $teacherid = $teacher_info["teacherid"];
                    $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$day_time,-1,$item["uid"]);
                    $attendance_type = $task->t_fulltime_teacher_attendance_list->get_attendance_type($id);
                    if($id>0 && in_array($attendance_type,[0,2])){
                        $start = $task->get_first_lesson_start($teacherid,$item["lesson_start"]);
                        //$lesson_end = $item["lesson_start"]-5400;
                        // $start = $task->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$item["lesson_start"]);
                        $off_time = $start-5400;
                        $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                            "off_time"         =>$off_time,
                            "attendance_type" =>2,
                        ]);
                    }elseif(empty($id)){
                        $start = $task->get_first_lesson_start($teacherid,$item["lesson_start"]);
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
                $festival_day_str = date("Y-m-d H:i:s",$festival_info["begin_time"])." ~ ".date("Y-m-d 22:i:s",$festival_info["end_time"]); 
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
                    @$arr[$key]['fulltime_teacher_type'] = $teacher_info["fulltime_teacher_type"];
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
                        $holiday_hugh_time = json_encode(["start"=>$value["attendance_time"],"end"=>$value["holiday_end_time"]]);
                        for($i=1;$i<=$value['day_num'];$i++){
                            $attendance_time =$value["attendance_time"]+($i-1)*86400;
                            $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$attendance_time,-1,$key);
                            if($id>0){
                                $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                                    "attendance_type"  =>3,
                                    "attendance_time"  =>$attendance_time,
                                    "day_num"          =>$value['day_num'],
                                    "adminid"          =>$key,
                                    "lesson_count"     =>$value['lesson_count']*100,
                                    "holiday_hugh_time" =>$holiday_hugh_time,
                                ]);

                            }else{
                                $task->t_fulltime_teacher_attendance_list->row_insert([
                                    "teacherid"        =>$value['teacherid'],
                                    "add_time"         =>$time,
                                    "attendance_type"  =>3,
                                    "attendance_time"  =>$attendance_time,
                                    "day_num"          =>$value['day_num'],
                                    "adminid"          =>$key,
                                    "lesson_count"     =>$value['lesson_count']*100,
                                    "holiday_hugh_time" =>$holiday_hugh_time,
                                ]);
                            }
                        }
                    }
                }
                //wx
                foreach ($arr as $key => $value) {
                    /**
                     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                     * 标题课程 : 待办事项提醒
                     * {{first.DATA}}
                     * 待办主题：{{keyword1.DATA}}
                     * 待办内容：{{keyword2.DATA}}
                     * 日期：{{keyword3.DATA}}
                     * {{remark.DATA}}
                     */

                    $data=[];
                    $url = "";
                    $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                    $data['first']    =  $festival_info["name"]."延休统计";
                    $data['keyword1'] = "延休数据汇总";
                    $data['keyword2'] ="\n老师:".$value['realname'].
                                      "\n时间:".$festival_day_str.
                                      "\n累计上课课时:".$value['lesson_count'].
                                      "\n延休天数:".$value['day_num'].
                                      "\n延休日期:".$value['cross_time'];
;

                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = ""; 
                    // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                    $wx_openid = $task->t_teacher_info->get_wx_openid($value["teacherid"]);

                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);




                    // $task->t_manager_info->send_wx_todo_msg_by_adminid (
                    //     $key,
                    //     $festival_info["name"]."延休统计",
                    //     "延休数据汇总",
                    //     "\n老师:".$value['realname'].
                    //     "\n时间:".$festival_day_str.
                    //     "\n累计上课课时:".$value['lesson_count'].
                    //     "\n延休天数:".$value['day_num'].
                    //     "\n延休日期:".$value['cross_time'],'');
                }
                $namelist = $namelist_shanghai = $namelist_wuhan = '';
                $num = $num_shanghai = $num_wuhan= 0;
                foreach ($arr as $key => $value) {
                    if($value['day_num'] != 0){
                        $namelist .= $value['realname'];
                        $namelist .= ',';
                        ++$num;
                        if($value["fulltime_teacher_type"]==1){
                            $namelist_shanghai .= $value['realname'];
                            $namelist_shanghai .= ',';
                            ++$num_shanghai;

                        }elseif($value["fulltime_teacher_type"]==2){
                            $namelist_wuhan .= $value['realname'];
                            $namelist_wuhan .= ',';
                            ++$num_wuhan;

                        }
                    }
                }
                $namelist = trim($namelist,',');
                $namelist_wuhan = trim($namelist_wuhan,',');
                $namelist_shanghai = trim($namelist_shanghai,',');

                $adminid_festival = [72,480,1171,1453,1446];
                $festival_header  = $festival_info["name"]."延休统计";
                $festival_title   = "全职老师".$festival_info["name"]."延休安排情况如下";
                $festival_content = "如下".$num."位老师满足条件,具体名单如下:".$namelist;
                foreach($adminid_festival as $festival_v){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid($festival_v,$festival_header,$festival_title,$festival_content);
                }

                // $task->t_manager_info->send_wx_todo_msg_by_adminid (72, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //erick
                // $task->t_manager_info->send_wx_todo_msg_by_adminid (480, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //low-key

                //上海全职老师延休邮件发送
                $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">上海全职老师假期累计上课时间及延休安排</td></tr>';
                $table .= '<tr><td>假期名称</td><td colspan="3" align="center"><font color="red">'.$festival_info["name"].'</font></td></tr>';
                $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
                foreach ($arr as $key => $value) {
                    if($value['day_num'] != 0 && $value["fulltime_teacher_type"]==1){
                        $table .= '<tr>';
                        $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                        $table .= '</tr>';
                    }
                }
                $table .= "</table>";
                $content = "Dear all：<br>上海全职老师".$festival_info["name"]."延休安排情况如下<br/>";
                $content .= "数据见下表<br>";
                $content .= $table;
                $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优教育</div><div>";

                $send_email = [
                    "low-key@leoedu.com","erick@leoedu.com","sherry@leoedu.com","hejie@leoedu.com","hr@leoedu.com",
                    "jiangmin@leoedu.com","dengxin@leoedu.com","xiongyuanli@leoedu.com"
                ];

                \App\Helper\Email::SendMailJiaoXue($send_email,"上海全职老师".$festival_info["name"]."假期累计上课时间及延休安排",$content,true,1);

                //武汉全职老师延休邮件发送
                $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">武汉全职老师假期累计上课时间及延休安排</td></tr>';
                $table .= '<tr><td>假期名称</td><td colspan="3" align="center"><font color="red">'.$festival_info["name"].'</font></td></tr>';
                $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
                foreach ($arr as $key => $value) {
                    if($value['day_num'] != 0 && $value["fulltime_teacher_type"]==2){
                        $table .= '<tr>';
                        $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                        $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                        $table .= '</tr>';
                    }
                }
                $table .= "</table>";
                $content = "Dear all：<br>武汉全职老师".$festival_info["name"]."延休安排情况如下<br/>";
                $content .= "数据见下表<br>";
                $content .= $table;
                $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优教育</div><div>";

                $send_email = ["low-key@leoedu.com","erick@leoedu.com","sherry@leoedu.com","hejie@leoedu.com","limingyu@leoedu.com","hr@leoedu.com"];

                \App\Helper\Email::SendMailJiaoXue($send_email,  "武汉全职老师".$festival_info["name"]."假期累计上课时间及延休安排", $content, true, 1);


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
                                 
                        $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);                   
                        $teacherid = $teacher_info["teacherid"];
                        $attendance_day = $day_time+86400;
                        $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$attendance_day,-1,$k);

                        if($id<3){
                            $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"在家办公通知","明天课时满8课时可在家办公","老师您好,您明天的课时满8小时,可以在家办公","");
                            $realname = $task->t_teacher_info->get_realname($teacherid);
                            if($id>0){
                                $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
                                    "attendance_type" =>1,
                                    "attendance_time"  =>$attendance_day,
                                    "day_num"           =>1,
                                    "lesson_count"      =>$item*100
                                ]);

                            }else{
                                $task->t_fulltime_teacher_attendance_list->row_insert([
                                    "teacherid"  =>$teacherid,
                                    "add_time"   =>$time,
                                    "attendance_type" =>1,
                                    "attendance_time"  =>$attendance_day,
                                    "day_num"           =>1,
                                    "adminid"           =>$k,
                                    "lesson_count"      =>$item*100
                                ]);
 
                            }
                        
                            $name_list .= $realname.",";
                            $num++;

                        }

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
                $adminid_full = [349,480,986,1171,1453,1446];
                $full_header  = "在家办公通知";
                $full_title   = "明天在家办公老师名单";
                $full_content = "明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list;
                foreach($adminid_full as $f_val){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid($f_val,$full_header,$full_title,$full_content);
                }
            }
            if($num_research>0){
                $adminid_research = [349,72];
                $research_header  = "在家办公通知";
                $research_title   = "明天在家办公教研老师名单";
                $research_content = "明天有如下".$num_research."位老师满8课时,可在家办公,具体名单如下:".$name_list_research;
                foreach($adminid_research as $r_val){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid($r_val,$research_header,$research_title,$research_content);
                }
            }
        }


    }
}
