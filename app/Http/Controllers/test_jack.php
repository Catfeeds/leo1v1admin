<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_jack  extends Controller
{
    use CacheNick;
    use TeaPower;

    public function test_ass(){
        $str = ["old"=>303,"new"=>401];
        $str = json_encode( $str);
        $ret=$this->t_flow->add_flow(
            1,$this->get_account_id(),"",1234,$str,0
        );
        dd($ret);

        $qiniu_file_name=\App\Helper\Utils::qiniu_upload("/home/ybai/period_order_001.pdf");

        //$ret=\App\Helper\Utils::exec_cmd("rm -rf /tmp/$base_file_name.*");
        return Config::get_qiniu_public_url()."/". $qiniu_file_name;

        $teacherid = 453296;
        $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        // $lesson_info   = $this->t_lesson_info->get_lesson_info($lessonid);
        //新版,发送入职前在线签订入职协议
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
        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data['first']    = $teacher_info["nick"]."老师您好，恭喜您通过模拟试听课考核，为了保护您的利益，请您签订《理优平台兼职老师入职协议》，之后您将正式成为理优平台兼职老师。";
        $data['keyword1'] = "签订入职协议";
        $data['keyword2'] = "签订《理优平台老师兼职协议》 ";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "点击此链接，签订入职协议";
        $url = "http://wx-teacher.leo1v1.com/wx_teacher_web/agreement";
        $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
        if($wx_openid){
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }
        dd(111);

        $orderNo="1798399505210";
        $channel = $this->t_orderid_orderno_list->get_channel($orderNo);
        if(empty($channel)){
            $channel="baidu";
        }
        dd($channel);

        $old_list = $this->t_child_order_info->field_get_list($orderid,"pay_status,pay_time,channel");
        if($old_list["pay_status"]==1 && $old_list["pay_time"]>0 && in_array($old_list["channel"],["baidu","baidu_app"])){
            return $this->output_succ(["status"=>0,"msg"=>"success"]);
        }
        $parentid= $this->t_student_info->get_parentid($userid);
        $parent_name = $this->t_parent_info->get_nick($parentid);
        $this->t_child_order_info->field_update_list($orderid,[
            "pay_status"  =>1,
            "pay_time"    =>time(),
            "channel"     =>$channel,
            "from_orderno"=>$orderNo,
            "period_num"  =>$period_new,
            "parent_name" =>$parent_name
        ]);

        $info = $this->t_company_wx_department->get_all_list();
        $users = $this->t_company_wx_users->get_all_list_for_manager(323);
        $department_list=[];
        foreach($users as $val){
            $department = $val["department"];
            $department_list = $this->get_all_department_name($info,$department,$department_list);

        }
        dd([$info,$users,$department_list]);
 
        $list1= $this->t_flow->field_get_list(120,"*");

        $list2 = $this->t_qingjia->field_get_list(42 ,"*");
        $ret =  $this->t_flow_config->get_next_node(E\Eflow_type::V_QINGJIA,0, $list1, $list2 , 99 );
        $node_map=\App\Flow\flow::get_flow_class_node_map (E\Eflow_type::V_QINGJIA);
        $adminid =59;
        $groupid=$this->t_admin_group_user->get_groupid_value($adminid);
        $item1=$this->t_admin_group_name->field_get_list($groupid, "master_adminid,up_groupid");
        $up_groupid=$item1["up_groupid"];
        $master_adminid2=$this->t_admin_main_group_name->get_master_adminid($up_groupid);
        dd($master_adminid2);




        dd($node_map);
        $list = $this->t_fulltime_teacher_attendance_list->get_list_by_attendance_type(3);
        foreach($list as $val){
            $str = $val["holiday_hugh_time"];
            if($str){
                $arr = json_decode($str,true);
                $arr["lesson_count"] = $val["lesson_count"];
            }else{
                $arr=[
                    "start" => $val["attendance_time"],
                    "end" => ($val["attendance_time"]+86400*($val["day_num"]-1)),
                    "lesson_count" => $val["lesson_count"]
                ];
              
            }
            $res = json_encode($arr);
            $this->t_fulltime_teacher_attendance_list->field_update_list($val["id"],[
                "holiday_hugh_time" =>  $res
            ]);
        }
        $list2 = $this->t_fulltime_teacher_attendance_list->get_list_by_attendance_type(3);

        dd($list2);
        $adminid = $this->get_account_id();
        $arr=[
            ["tag_name"=>"幽默风趣","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"生动活泼","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"鼓励激发","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"耐心绅士","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"口语标准","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"经验丰富","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"普通话标准","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"熟悉考纲","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"激昂热情","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"生动活泼","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"鼓励激发","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"经验丰富","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"丰富有趣","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"游戏相关","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"图片精美","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"嘻嘻哈哈","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"兴趣培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"习惯培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"信心建立","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"学习方法技巧","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"文学素养培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],

        ];
        foreach($arr as $var){
            $this->t_tag_library->row_insert($var);
 
        }
               // return $this->output_succ();

        // $this->t_student_info->reset_lesson_count(440915);
        dd(1111);

        $lessonid = $this->get_in_int_val('lessonid',549731);

        $homework_situation = array_flip(E\Ehomework_situation::$desc_map);
        $content_grasp = array_flip(E\Econtent_grasp::$desc_map);
        $lesson_interact = array_flip(E\Elesson_interact::$desc_map);

        $ret = $this->t_lesson_info_b2->get_lesson_stu_performance($lessonid);
        if($ret['stu_performance']!=''){
            $ret_info = json_decode($ret['stu_performance'],true);
            $ret_info['homework_situation'] = $homework_situation[$ret_info['homework_situation']];
            $ret_info['content_grasp']      = $content_grasp[$ret_info['content_grasp']];
            $ret_info['lesson_interact']    = $lesson_interact[$ret_info['lesson_interact']];

            if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                foreach($ret_info['point_note_list'] as $key => $val){
                    $ret_info['point_name'][$key]     = $val['point_name'];
                    $ret_info['point_stu_desc'][$key] = $val['point_stu_desc'];
                }
            }else{
                $ret_info['point_name']=explode("|",$ret['lesson_intro']);
            }
        }else{
            $ret_info=explode("|",$ret['lesson_intro']);
        }
        dd($ret_info);

        dd(111);


        $master_adminid_arr = $this->t_admin_main_group_name->get_seller_master_adminid_by_campus_id(-1);
        //  $sub_assign_adminid_1 = $this->t_admin_main_group_name->get_major_master_adminid($sub_assign_adminid_1);
        $ret=[];
        foreach($master_adminid_arr as $val){
            $adminid = $val["master_adminid"];
            
            if($adminid>0){
                $list = $this->t_seller_student_new->get_stu_info_master_leader($adminid);
                $sub_assign_adminid_1 = $this->t_admin_main_group_name->get_major_master_adminid($adminid);
                // foreach($list as $item){
                //     $nick = $item["nick"];
                //     $phone = $item["phone"];
                //     $userid = $item["userid"];
                //     $this->t_seller_student_new->field_update_list($userid,[
                //         "sub_assign_adminid_1"  =>$sub_assign_adminid_1,
                //         "sub_assign_adminid_2"  =>$sub_assign_adminid_1,
                //         "admin_revisiterid"     =>$sub_assign_adminid_1,
                //         "admin_assign_time"     =>time()
                //     ]);

                //     $this->t_manager_info->send_wx_todo_msg_by_adminid($sub_assign_adminid_1,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");
                //     $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"转介绍","学生[$nick][$phone]","总监:".$sub_assign_adminid_1."类型2","/seller_student_new/seller_student_list_all?userid=$userid");

                //     $name = $this->t_manager_info->get_account($sub_assign_adminid_1);
                //     $this->t_book_revisit->add_book_revisit(
                //         $phone,
                //         "操作者: $account ,分配给销售总监".$name,
                //         "system"
                //     );
 
                // }
                $ret[$adminid] = $list;
                $s2 = $this->t_admin_main_group_name->get_major_master_adminid(-1,$val["groupid"]);
                echo $s1."<br>";
                echo $s2."<br>";

            }
        }
        dd($ret);
        $su = $this->t_admin_main_group_name->get_major_master_adminid(416);
        dd($su);

        dd(md5("leo15179518621580092561911v1"));
      
        $tt= $this->t_teacher_info->get_prize(240314);
        dd(json_decode($tt,true));




        $teacher_money_type=6;
        $start_time = strtotime("2018-01-01");
        $ret_info = $this->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type,351371);
        dd($ret_info);

        $list = $this->t_teacher_advance_list->get_no_deal_withhold_info($start_time,$teacher_money_type);
        dd($list);

        $job = new \App\Jobs\SendAdvanceTeacherWxEmail($start_time,$teacher_money_type,2);
        $tt = dispatch($job);
        dd($tt);

        $ret_info = $this->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type);
        dd($ret_info);
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2017-02-01");
        $adminid = 1416;
        $kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time,$adminid);
        dd($kk_suc);


        $job = new \App\Jobs\SendAdvanceTeacherWxEmail(1506787200,6);
        dispatch($job);
        dd(111);

        $list = $this->t_teacher_advance_list->get_all_accept_no_send_list(1506787200,6);
        dd($list);

        // $time = time();
        // $h = date("H");

     
        //list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        // $end_time = strtotime(date("Y-m-d",$time));
        // $start_time = $end_time-86400;
        // $date_list_old=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        // $date_arr=[];
        // foreach($date_list_old as $k=>$val){
        //     $time = strtotime($k);
        //     $date_arr[$time]["date"]=$time;
        // }
        // dd($time);

        // //全职老师上班打卡延后时间
        // $begin_time = $day_time+9.5*3600;
        // $lesson_start = strtotime(date("Y-m-d",$time)." 09:00:00");
        // $list = $task->t_lesson_info_b2->get_delay_work_time_lesson_info($day_time,$lesson_start);
        // foreach($list as $item){
        //     $teacherid = $item["teacherid"];
        //     if($item["lesson_type"]==2){
        //         $lesson_end = $item["lesson_end"]+1200;
        //     }else{
        //         $lesson_end = $item["lesson_end"];
        //     }
        //     $id = $task->t_fulltime_teacher_attendance_list->check_is_exist(-1,$day_time,-1,$item["uid"]);
        //     $attendance_type = $task->t_fulltime_teacher_attendance_list->get_attendance_type($id);
        //     if($id>0 && in_array($attendance_type,[0,2])){
        //         $end = $task->get_last_lesson_end($teacherid,$lesson_end);
        //         $delay_time = $end+5400;
        //         if($delay_time>$begin_time){
        //             $task->t_fulltime_teacher_attendance_list->field_update_list($id,[
        //                 "delay_work_time" =>$delay_time,
        //                 "attendance_type" =>2,
        //             ]);
        //         }

        //     }elseif(empty($id)){
        //         $end = $task->get_last_lesson_end($teacherid,$lesson_end);
        //         $delay_time = $end+5400;
        //         if($delay_time>$begin_time){
        //             $task->t_fulltime_teacher_attendance_list->row_insert([
        //                 "teacherid"  =>$teacherid,
        //                 "add_time"   =>time(),
        //                 "attendance_type" =>2,
        //                 "attendance_time"  =>$day_time,
        //                 "delay_work_time"  =>$delay_time,
        //                 "adminid"          =>$item["uid"]
        //             ]);

        //         }
        //     }
 
                 
        // }



        //全职老师提前下班
        $time = strtotime("2018-01-26 11:00:00");
        $day_time = strtotime(date("Y-m-d",$time));
        $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
        $lesson_start = $lesson_end+1800;
        $lesson_list = $this->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
        foreach($lesson_list as $item){
            $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($item["uid"]);
            $teacherid = $teacher_info["teacherid"];
            $id = $this->t_fulltime_teacher_attendance_list->check_is_exist(-1,$day_time,-1,$item["uid"]);
           
            $attendance_type = $this->t_fulltime_teacher_attendance_list->get_attendance_type($id);
            if($id>0 && in_array($attendance_type,[0,2])){
                $start = $this->get_first_lesson_start($teacherid,$item["lesson_start"]);
                //$lesson_end = $item["lesson_start"]-5400;
                // $start = $this->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$item["lesson_start"]);
                $off_time = $start-5400;
                if($teacherid==99504){
                    $this->t_fulltime_teacher_attendance_list->field_update_list($id,[
                        "off_time"         =>$off_time,
                        "attendance_type" =>2,
                    ]);

                }
                // $this->t_fulltime_teacher_attendance_list->field_update_list($id,[
                //     "off_time"         =>$off_time,
                //     "attendance_type" =>2,
                // ]);
            }elseif(empty($id)){
                $start = $this->get_first_lesson_start($teacherid,$item["lesson_start"]);
                $off_time = $start-5400;
                if($teacherid==99504){
                    dd($off_time);
                }
                // $this->t_fulltime_teacher_attendance_list->row_insert([
                //     "teacherid"  =>$teacherid,
                //     "add_time"   =>$time,
                //     "attendance_type" =>2,
                //     "attendance_time"  =>$day_time,
                //     "off_time"         =>$off_time,
                //     "adminid"          =>$item["uid"]
                // ]);

            }
 
        }
        dd(111);


        $week_limit_time_info = $this->t_teacher_info->get_week_limit_time_info(62735);
        $lesson_start = strtotime("2018-01-27 14:00");
        $lesson_end = strtotime("2018-01-27 16:00");
        $date_week    = \App\Helper\Utils::get_week_range($lesson_start,1);

        $res = $this->check_research_teacher_limit_time($lesson_start,$lesson_end,$week_limit_time_info,$date_week);
        if($res){
            return $res;
        }
        dd(111);

        $admin_info   = $this->t_manager_info->get_research_teacher_list_new(4);
        $tt=[
            ["week_num"=>2,"week_name"=>"周二","start"=>"09:00","end"=>"18:00"],
            ["week_num"=>3,"week_name"=>"周三","start"=>"09:00","end"=>"18:00"],
            ["week_num"=>4,"week_name"=>"周四","start"=>"09:00","end"=>"18:00"],
            ["week_num"=>5,"week_name"=>"周五","start"=>"09:00","end"=>"18:00"],
        ];
        $str = json_encode($tt);

        foreach($admin_info as $v){
            $create_time = $v["create_time"];
            if($create_time<strtotime("2017-10-25")){              
                $this->t_teacher_info->field_update_list($v["teacherid"],[
                    "week_limit_time_info" =>$str 
                ]);

            }else{
                if($v["teacherid"]==428558){
                    $tt=[
                        ["week_num"=>2,"week_name"=>"周二","start"=>"09:00","end"=>"18:00"],
                        ["week_num"=>3,"week_name"=>"周三","start"=>"09:00","end"=>"16:00"],
                        ["week_num"=>4,"week_name"=>"周四","start"=>"09:00","end"=>"18:00"],
                        ["week_num"=>5,"week_name"=>"周五","start"=>"09:00","end"=>"18:00"],
                    ];
                    $str = json_encode($tt);

                }
                $this->t_teacher_info->field_update_list($v["teacherid"],[
                    "week_limit_time_info" =>$str ,
                    "week_lesson_count"   =>8
                ]);

            }
        }

        dd($admin_info);


        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2018-01-01");
        $order_num = $this->t_order_info->get_all_renew_stu_list_by_order($start_time,$end_time);
        $end_stu = $this->t_student_info->get_end_stu_list_str($start_time,$end_time);
        $list =[];
        foreach($end_stu as $k=>$val){
            if(!isset($order_num[$k])){
                $list[$k]=$val;
            }
        }
        $ass=[];
        foreach($order_num as $k=>$val){
            @$ass[$val["assistantid"]]["renew_num"] ++;
            $ass[$val["assistantid"]]["name"] = $val["nick"];
            $ass[$val["assistantid"]]["id"] = $val["assistantid"];
        }
        foreach($list as $k=>$val){
            @$ass[$val["assistantid"]]["end_num"] ++;
            $ass[$val["assistantid"]]["name"] = $val["nick"];
            $ass[$val["assistantid"]]["id"] = $val["assistantid"];
        }
        $str = json_encode( $ass);
        $task->t_teacher_info->field_update_list(240314,[
            "prize" => $str
        ]);



        dd($ass);
        $lessonid = 2404;
        $page_info = $this->get_in_page_info();
        $login_list = $this->t_lesson_info_b3->get_classroom_situation_info($page_info,-1,0,0,-1,-1,1,$lessonid);
        $login = @$login_list["list"][0];
        dd($login);

        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2018-01-21");
        $cc_list        = $task->t_lesson_info->get_teacher_test_person_num_by_all( $start_time,$end_time,-1,-1,[],2,false);
        $cr_list        = $task->t_lesson_info->get_teacher_test_person_num_by_all( $start_time,$end_time,-1,-1,[],1,false);
        $data=[];
        $data["cc_lesson_num"] =  $cc_list["lesson_num"];
        $data["cc_person_num"] =  $cc_list["person_num"];
        $data["cc_order_num"] =  $cc_list["have_order"];
        $data["cc_per"]  = round($data["cc_order_num"]/$data["cc_person_num"]*100,2);
        $data["cr_lesson_num"] =  $cr_list["lesson_num"];
        $data["cr_person_num"] =  $cr_list["person_num"];
        $data["cr_order_num"] =  $cr_list["have_order"];
        $data["cr_per"]  = round($data["cr_order_num"]/$data["cr_person_num"]*100,2);
        dd($data);


        //微信通知老师
        /**
         * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
         * 标题课程 : 等级升级通知
         * {{first.DATA}}
         * 用户昵称：{{keyword1.DATA}}
         * 最新等级：{{keyword2.DATA}}
         * 生效时间：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        // $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
        $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
        if($wx_openid){
            $data=[];
            $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
            $data['first']    = "恭喜jack老师,您已经成功晋级到了三星级";
            $data['keyword1'] = "jack";
            $data['keyword2'] = "三星级";
            $data['keyword3'] = date("Y-m-01 00:00",time());
            /* $data['remark']   = "晋升分数:".$score
               ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";*/
            $data['remark']   = "希望老师在今后的教学中继续努力,再创佳绩";

            $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=13817759346";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }
        dd(111);

        \App\Helper\Net::send_sms_taobao(13817759346,111, 10671029,[
            "code"  => 1,
            "index" => 2,
        ],1);
        dd(111);


        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2018-01-01");
        // $tt = $this->t_teacher_info->get_prize(240314);
        // dd(json_decode($tt,true));
        $ret_info = $this->t_teacher_lecture_appointment_info->get_tongji_data($start_time,$end_time);
        dd($ret_info);
        $json_data=file_get_contents( "http://10.31.92.162/account/login/phone=13817759346&role=1&passwd=befe7ecb6a1aab4ad80332b34ef782d8"  );
        dd($json_data);

        // $registered_student_arr=[1,2,3,4];
        // $read_student_arr =[2,3];
        // $registered_student_arr = array_diff($registered_student_arr, $read_student_arr);//获得去除在读学员的数组
        // dd($registered_student_arr);
        // $phone = "136212987151";
        // //短信黑名单(不发送)
        // $sms_phone_refund_list=["13621298715"];

        // if ($phone && !in_array($phone,$sms_phone_refund_list)) {
        //     dd(111);
        // }else{
        //     dd(222);
        // }
        $ret_info = $this->t_month_ass_student_info->get_ass_month_info(1512057500);

        //续费/新签合同数据
        $start_time = strtotime("2017-12-01");
        $end_time = strtotime("2018-01-01");
        $ass_order_info = $this->t_order_info->get_assistant_performance_order_info($start_time,$end_time);
        $order_money_list = $this->t_order_info->get_ass_self_order_period_money($start_time,$end_time);
        $renew_list=$new_list=[];
        foreach($ass_order_info as $val){
            $contract_type = $val["contract_type"];
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if($contract_type==0){
                $new_list[$orderid]["uid"] = $uid;
                $new_list[$orderid]["userid"] = $userid;
                $new_list[$orderid]["price"] = $price;
                $new_list[$orderid]["orderid"] = $orderid;
                @$new_list[$orderid]["real_refund"] += $real_refund;
            }elseif($contract_type==3){
                $renew_list[$orderid]["uid"] = $uid;
                $renew_list[$orderid]["userid"] = $userid;
                $renew_list[$orderid]["price"] = $price;
                $renew_list[$orderid]["orderid"] = $orderid;
                @$renew_list[$orderid]["real_refund"] += $real_refund;
            }
        }
        $ass_renew_info = $ass_new_info=[];
        foreach($renew_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            $price = @$order_money_list[$orderid]["reset_money"];
            if(!$price){
                $price = $val["price"]; 
            }
            if(!isset($ass_renew_info[$uid]["user_list"][$userid])){
                $ass_renew_info[$uid]["user_list"][$userid]=$userid;
                @$ass_renew_info[$uid]["num"] +=1;
            }
            @$ass_renew_info[$uid]["money"] += $price-$real_refund;

        }
        foreach($new_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            $price = @$order_money_list[$orderid]["reset_money"];
            if(!$price){
                $price = $val["price"]; 
            }

            if(!isset($ass_new_info[$uid]["user_list"][$userid])){
                $ass_new_info[$uid]["user_list"][$userid]=$userid;
                @$ass_new_info[$uid]["num"] +=1;
            }
            @$ass_new_info[$uid]["money"] += $price-$real_refund;

        }

        foreach($ret_info as $k=>$val){
            $performance_cr_renew_num  = @$ass_renew_info[$k]["num"];
            $performance_cr_renew_money  = @$ass_renew_info[$k]["money"];
            $performance_cr_new_num  = @$ass_new_info[$k]["num"];
            $performance_cr_new_money  = @$ass_new_info[$k]["money"];
            $this->t_month_ass_student_info->get_field_update_arr($k,1512057500,1,[               
                "performance_cr_renew_num"    =>$performance_cr_renew_num,
                "performance_cr_renew_money"  =>$performance_cr_renew_money,
                "performance_cr_new_num"      =>$performance_cr_new_num,
                "performance_cr_new_money"    =>$performance_cr_new_money,
            ]);

        }
        $ret_info = $this->t_month_ass_student_info->get_ass_month_info(1512057500);
        dd($ret_info);




        //获取销售转介绍合同信息
        $cc_order_list = $this->t_order_info->get_seller_tran_order_info($start_time,$end_time);
        $new_tran_list=[];
        foreach($cc_order_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            $new_tran_list[$orderid]["uid"] = $uid;
            $new_tran_list[$orderid]["userid"] = $userid;
            $new_tran_list[$orderid]["price"] = $price;
            $new_tran_list[$orderid]["orderid"] = $orderid;
            @$new_tran_list[$orderid]["real_refund"] += $real_refund;
            
        }
        $ass_tran_info =[];
        foreach($new_tran_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_tran_info[$uid]["user_list"][$userid])){
                $ass_tran_info[$uid]["user_list"][$userid]=$userid;
                @$ass_tran_info[$uid]["num"] +=1;
            }
            @$ass_tran_info[$uid]["money"] += $price-$real_refund;

        }

        
        //销售月拆解
        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;

        //每周助教在册学生数量获取
        $registered_student_num=[];
        for($i=0;$i<$n;$i++){
            $week = $first_week+$i*7*86400;
            $week_edate = $week+7*86400;
            $week_info = $this->t_ass_weekly_info->get_all_info($week);
            foreach($week_info as $val){
                @$registered_student_num[$val["adminid"]] +=@$week_info[$val["adminid"]]["registered_student_num"];
            } 
        }

        $ret = $this->t_month_ass_student_info->get_ass_month_info($start_time);
        $last_month = strtotime("-1 month",$start_time);
        $last_ass_month = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        foreach($ret as $k=>$item){
            /*课时消耗达成率*/
            $registered_student_list = @$last_ass_month[$k]["registered_student_list"];
            if($registered_student_list){
                $registered_student_arr = json_decode($registered_student_list,true);
                $last_stu_num = count($registered_student_arr);//月初在册人员数
                $last_lesson_total = $this->t_week_regular_course->get_lesson_count_all($registered_student_arr);//月初周总课时消耗数
                $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;  //预估月课时消耗总量
            }else{
                $registered_student_arr=[];      
                $estimate_month_lesson_count =100;
            }

            //平均学员数(销售周)
            $seller_stu_num = @$registered_student_num[$k]/$n;


            //得到单位学员
            //$seller_stu_num = $item["seller_week_stu_num"];
            $seller_lesson_count = $item["seller_month_lesson_count"];
            // $estimate_month_lesson_count = $item["estimate_month_lesson_count"];
            if(empty($seller_stu_num)){
                $lesson_count_finish_per=0;
            }else{
                $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
            }

            //算出kpi中课时消耗达成率的情况
            if($lesson_count_finish_per>=70){
                $kpi_lesson_count_finish_per = 0.4;
            }else{
                $kpi_lesson_count_finish_per=0;
            }

            $item["kpi_lesson_count_finish_per"]=$kpi_lesson_count_finish_per;

            /*课程消耗奖金*/
            if($lesson_count_finish_per>=120){
                $lesson_count_finish_reword=$seller_lesson_count*1.2;
            }elseif($lesson_count_finish_per>=100 ){
                $lesson_count_finish_reword=$seller_lesson_count*1;
            }elseif($lesson_count_finish_per>=75 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.8;
            }elseif($lesson_count_finish_per>=50 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.5;
            }else{
                $lesson_count_finish_reword=0;
            }

            $item["lesson_count_finish_reword"]=$lesson_count_finish_reword;

            $performance_cc_tran_num = @$ass_tran_info[$k]["num"];
            $performance_cc_tran_money = @$ass_tran_info[$k]["money"];
            $performance_cr_renew_num  = @$ass_renew_info[$k]["num"];
            $performance_cr_renew_money  = @$ass_renew_info[$k]["money"];
            $performance_cr_new_num  = @$ass_new_info[$k]["num"];
            $performance_cr_new_money  = @$ass_new_info[$k]["money"];

            $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,[
                "kpi_lesson_count_finish_per" =>$kpi_lesson_count_finish_per*100,
                "estimate_month_lesson_count" =>$estimate_month_lesson_count,
                "seller_month_lesson_count"   =>$seller_lesson_count,
                "seller_week_stu_num"         =>$seller_stu_num,
                "performance_cc_tran_num"     =>$performance_cc_tran_num,
                "performance_cc_tran_money"   =>$performance_cc_tran_money,
                "performance_cc_renew_num"    =>$performance_cc_renew_num,
                "performance_cc_renew_money"  =>$performance_cc_renew_money,
                "performance_cc_new_num"      =>$performance_cc_new_num,
                "performance_cc_new_money"    =>$performance_cc_new_money,
            ]);

 
        }




        $ret = $this->t_month_ass_student_info->get_ass_month_info($start_time);

        dd($ret);
        //续费金额 分期按80%计算,按新方法获取
        $ass_renw_money = $this->t_manager_info->get_ass_renw_money_new($start_time,$end_time);

        //cc签单助教转介绍数据
        $cc_tran_order = $this->t_manager_info->get_cc_tran_origin_order_info($start_time,$end_time);


    }
    public function test_main(){
        // $pdf_file = "/home/ybai/no_order_show_parent_unique.pdf";
        // $qiniu_file_name=\App\Helper\Utils::qiniu_upload($pdf_file);

        // //$ret=\App\Helper\Utils::exec_cmd("rm -rf /tmp/$base_file_name.*");
        // $pdf_file_url= \App\Helper\Config::get_qiniu_public_url()."/". $qiniu_file_name;

        $pdf_file_url=\App\Helper\Common::gen_order_pdf_empty();

        // dd($pdf_file_url);
        $time=strtotime("2017-12-01");
        $list = $this->t_month_ass_student_info->get_ass_month_info($time);
        foreach($list as &$val){
            $val["month"] = $val["month"]-100;
            unset($val["assistantid"]);
            $this->t_month_ass_student_info->row_insert($val);
        }
        $time=strtotime("2017-12-01")-100;
        $list = $this->t_month_ass_student_info->get_ass_month_info($time);
        dd($list);

        $registered_userid_list = $this->t_student_info->get_read_student_ass_info(-2);//在册学员名单
        $time=strtotime("2017-11-27");
        for($i=0;$i<=6;$i++){
            $start_time = $time+$i*7*86400;
            $week_info = $this->t_ass_weekly_info->get_all_info($start_time);
            foreach($week_info as $val){
                $k = $val["adminid"];
                $list = @$registered_userid_list[$k];
                if($list){
                    $arr = json_decode($list,true);
                    $num = count($arr);
                }else{
                    $num=0;
                    
                }
                $this->t_ass_weekly_info->field_update_list($val["id"],[
                    "registered_student_list" => $list,
                    "registered_student_num"  => $num
                ]);
            }
        }
        dd($registered_userid_list);

        $userid  = 62938;
        $regular_lesson_list = $this->t_lesson_info_b3->get_stu_first_lesson_time_by_subject($userid);
        dd($regular_lesson_list);

        $this->switch_tongji_database();
        $this->check_and_switch_tongji_domain();
        $start_time = strtotime("2017-01-01");
        $end_time = time();
        $list  = $this->t_lesson_info_b3->get_teacher_student_first_subject_info($start_time,$end_time);
        dd($list);

    }

    public function get_user_list(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");

        #输入参数
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $userid=$this->get_in_userid(-1);
        $grade=$this->get_in_el_grade();
        $gender=$this->get_in_el_gender();
        $query_text=$this->get_in_query_text();

        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str,  $grade );

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }

        return $this->pageOutJson(__METHOD__, $ret_info,[
            "message" =>  "cur usrid:".$userid,
        ]);
    }

    public function get_user_list1(){
        $this->set_in_value("grade", 101);
        //
        $this->html_hide_list_add([ "grade","opt_grade", "input_grade" ]);
        return $this->get_user_list();
    }


    public function test_kk(){
        $file = fopen("/home/ybai/111.csv","r");
        // $file = fopen("/home/jack/111.csv","r");
        $goods_list=[];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            if($i>=24 && $i<26){
                $goods_list[] = $data; 
            }
            $i++;
        }
        foreach($goods_list as &$item){
            foreach($item as $k=>&$val){
                
                if(in_array($k,[1,2,5,6])){
                    $arr = explode(",",$val);
                    $str="";
                    foreach($arr as $t){
                        $str .= $t;
                    }
                    $str = $str *100;
                    $item[$k] = $str;
                }elseif($k==0){
                    $arr = explode("年",$val);
                    $arr_2 = $arr[1];
                    $arr_3 = explode("月",$arr_2);

                    $year = $arr[0];
                    $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
                    $date = $year."-".$month."-01";
                    $item[$k]=strtotime($date);
                }
            }
            
        }
        // dd($goods_list);

        foreach($goods_list as $p_item){
            $this->t_admin_corporate_income_list->row_insert([
                "month"  =>$p_item[0],
                "new_order_money"=>$p_item[1],
                "renew_order_money"=>$p_item[2],
                "new_order_stu"=>$p_item[3],
                "renew_order_stu"=>$p_item[4],
                "new_signature_price"=>$p_item[5],
                "renew_signature_price"=>$p_item[6],
            ]);
        }
        fclose($file); 
        
    }

    public function test_tt(){
        // $file = fopen("/home/jack/222.csv","r");
        $file = fopen("/home/ybai/333.csv","r");
        $goods_list=[];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            if($i>=1){
                $goods_list[] = $data;
            }
            $i++;
        }
        foreach($goods_list as &$val){
            foreach($val as $k=>&$v){
                if(in_array($k,[0,2])){
                    $arr= explode("\n",$v);
                    if($k==0){
                        $nick = trim($arr[0]);
                        $phone = trim(@$arr[1]);
                        $val[$k]=$nick;
                        $val[100] = $phone;

                    }else{
                        $str ="";
                        foreach($arr as $r){
                            $str .= trim($r).",";
                        }
                        $val[$k] = trim($str,",");
                    }
                }
            }
        }
        foreach($goods_list as $p_item){
            $this->t_admin_refund_order_list->row_insert([
                "nick"   =>$p_item[0],
                "phone"   =>$p_item[100],
                "grade"   =>$p_item[1],
                "order_custom"   =>$p_item[2],
                "sys_operator"   =>$p_item[3],
                "order_time"   =>strtotime($p_item[4]),
                "contract_type"   =>$p_item[5],
                "lesson_total"   =>$p_item[6]*100,
                "refund_lesson_count"   =>$p_item[7]*100,
                "order_cost_price"   =>$p_item[8]*100,
                "order_price"   =>$p_item[9]*100,
                "refund_price"   =>$p_item[10]*100,
                "is_invoice"   =>$p_item[11],
                "invoice"   =>$p_item[12],
                "payment_account_id"   =>$p_item[13],
                "refund_info"   =>$p_item[14],
                "save_info"   =>$p_item[15],
                "apply_account"   =>$p_item[17],
                "apply_time"   =>strtotime($p_item[16]),
                "approve_status"   =>$p_item[18],
                "approve_time"   =>$p_item[19]=="无"?0:strtotime($p_item[19]),
                "refund_status"   =>$p_item[20],
                "period_flag"   =>$p_item[21],
                "assistant_name"   =>$p_item[22],
                "subject"   =>$p_item[23],
                "teacher_realname"   =>$p_item[24],
                "connection_state"   =>$p_item[28],
                "lifting_state"   =>$p_item[29],
                "learning_attitude"   =>$p_item[30],
                "order_three_month_flag"   =>$p_item[31],
                "assistant_one_level_cause"   =>$p_item[32],
                "assistant_two_level_cause"   =>$p_item[33],
                "assistant_three_level_cause"   =>$p_item[34],
                "assistant_deduction_value"   =>$p_item[35],
                "assistant_cause_analysis"   =>$p_item[36],
                "registrar_one_level_cause"   =>$p_item[37],
                "registrar_two_level_cause"   =>$p_item[38],
                "registrar_three_level_cause"   =>$p_item[39],
                "registrar_deduction_value"   =>$p_item[40],
                "registrar_cause_analysis"   =>$p_item[41],
                "teacher_manage_one_level_cause"   =>$p_item[42],
                "teacher_manage_two_level_cause"   =>$p_item[43],
                "teacher_manage_three_level_cause"   =>$p_item[44],
                "teacher_manage_deduction_value"   =>$p_item[45],
                "teacher_manage_cause_analysis"   =>$p_item[46],
                "dvai_one_level_cause"   =>$p_item[47],
                "dvai_two_level_cause"   =>$p_item[48],
                "dvai_three_level_cause"   =>$p_item[49],
                "dvai_deduction_value"   =>$p_item[50],
                "dvai_cause_analysis"   =>$p_item[51],
                "product_one_level_cause"   =>$p_item[52],
                "product_two_level_cause"   =>$p_item[53],
                "product_three_level_cause"   =>$p_item[54],
                "product_deduction_value"   =>$p_item[55],
                "product_cause_analysis"   =>$p_item[56],
                "advisory_one_level_cause"   =>$p_item[57],
                "advisory_two_level_cause"   =>$p_item[58],
                "advisory_three_level_cause"   =>$p_item[59],
                "advisory_deduction_value"   =>$p_item[60],
                "advisory_cause_analysis"   =>$p_item[61],
                "customer_changes_one_level_cause"   =>$p_item[62],
                "customer_changes_two_level_cause"   =>$p_item[63],
                "customer_changes_three_level_cause"   =>$p_item[64],
                "customer_changes_deduction_value"   =>$p_item[65],
                "customer_changes_cause_analysis"   =>$p_item[66],
                // "teacher_one_level_cause"   =>1111111,
                // "teacher_two_level_cause"   =>$p_item[65],
                // "teacher_three_level_cause"   =>$p_item[66],
                // "teacher_deduction_value"   =>$p_item[67],
                // "teacher_cause_analysis"   =>$p_item[68],
                // "subject_one_level_cause"   =>$p_item[69],
                // "subject_two_level_cause"   =>$p_item[70],
                // "subject_three_level_cause"   =>$p_item[71],
                // "subject_deduction_value"   =>$p_item[72],
                // "subject_cause_analysis"   =>$p_item[73],
                "other_cause"   =>$p_item[67],
                "quality_control_global_analysis"   =>$p_item[68],
                "later_countermeasure"   =>$p_item[69],
                "assistant_cause_rate"   =>$p_item[70],
                "registrar_cause_rate"   =>$p_item[71],
                "teacher_manage_cause_rate"   =>$p_item[72],
                "dvai_cause_rate"   =>$p_item[73],
                "product_cause_rate"   =>$p_item[74],
                "advisory_cause_rate"   =>$p_item[75],
                "customer_changes_cause_rate"   =>$p_item[76],
                "teacher_cause_rate"   =>$p_item[77],
                "subject_cause_rate"   =>$p_item[78],
               
            ]);
        }
        fclose($file); 
        
    }

    public function test_yy(){
        $file = fopen("/home/ybai/444.csv","r");
        // $file = fopen("/home/jack/444.csv","r");
        $goods_list=[];
        $first_list = [];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
            if($i==0){
                $first_list = $data;
            }
            $i++;
        }
        $num = count($first_list);
        $list=[];
        $j=1;
        foreach($goods_list as $val){
            foreach($val as $k=>$v){
                $list[$k][$j]=$v; 
            }
            $j++;
        }
        foreach($list as $kk=>&$item){
                
            
                $arr = explode("年",$item[1]);
                $arr_2 = $arr[1];
                $arr_3 = explode("月",$arr_2);

                $year = $arr[0];
                $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
                $date = $year."-".$month."-01";
                $item[1]=strtotime($date);
                if( $item[1] >= strtotime("2017-11-01")){
                
            
                    $this->t_admin_student_month_info->row_insert([
                        "month" =>$item[1],
                        "begin_stock" =>$item[2],
                        "increase_num" =>$item[3],
                        "end_num" =>$item[4],
                        "refund_num" =>$item[5],
                        "end_stock" =>$item[6],
                        "no_lesson_num" =>$item[7],
                        "end_read_num" =>$item[8],
                        "three_end_num" =>$item[11],
                        "expiration_renew_num" =>$item[12],
                        "early_renew_num" =>$item[13],
                        "end_renew_num" =>$item[14],
                        "actual_renew_rate" =>$item[15],
                        "actual_renew_rate_three" =>$item[16],
                    ]);
                }

        }
        // dd($list);

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function test_xx(){
        $file = fopen("/home/ybai/555.csv","r");
        // $file = fopen("/home/jack/555.csv","r");
        $goods_list=[];
        $first_list = [];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
            if($i==0){
                $first_list = $data;
            }
            $i++;
        }
        $num = count($first_list);
        $list=[];
        $j=1;
        foreach($goods_list as $val){
            foreach($val as $k=>$v){
                $list[$k][$j]=$v; 
            }
            $j++;
        }
        foreach($list as &$item){
            $arr = explode("年",$item[1]);
            $arr_2 = $arr[1];
            $arr_3 = explode("月",$arr_2);

            $year = $arr[0];
            $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
            $date = $year."-".$month."-01";
            $item[1]=strtotime($date);
            if( $item[1] >= strtotime("2017-11-01")){

                $this->t_admin_student_month_info->field_update_list($item[1],[
                    "test_chinese_num" =>$item[2],
                    "test_math_num" =>$item[3],
                    "test_english_num" =>$item[4],
                    "test_minor_subject_num" =>$item[5],
                    "test_all_subject_num" =>$item[6],
                    "increase_chinese_num" =>$item[7],
                    "increase_math_num" =>$item[8],
                    "increase_english_num" =>$item[9],
                    "increase_minor_subject_num" =>$item[10],
                    "increase_all_subject_num" =>$item[11],
                    "increase_test_rate" =>$item[12],
                    "read_chinese_num" =>$item[13],
                    "read_math_num" =>$item[14],
                    "read_english_num" =>$item[15],
                    "read_minor_subject_num" =>$item[16],
                    "read_all_subject_num" =>$item[17],
                ]);
            }

        }

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function test_zz(){
        $file = fopen("/home/ybai/666.csv","r");
        // $file = fopen("/home/jack/666.csv","r");
        $goods_list=[];
        $first_list = [];
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
        }

        foreach($goods_list as &$val){
            $arr = explode("月",$val[0]);
            $month = $arr[0]>=10?$arr[0]:"0".$arr[0];

            $date = "2017-".$month."-01";
            $val[0]=strtotime($date);
            $this->t_order_student_month_list->row_insert([
                "month" =>$val[0],
                "origin" =>$val[1],
                "leads_num" =>$val[2],
                "test_num" =>$val[3],
                "test_transfor_per" =>$val[4],
                "order_transfor_per" =>$val[6],
                "order_stu_num" =>$val[5],
            ]);



        }

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function get_parent_courseid($courseid,$num,$parentid){
        $course_list = $this->t_parent_info->get_baidu_class_info($parentid);
        if($course_list){
            $list=json_decode($course_list,true);
            if(isset($list[$num])){
                $course_arr = $list[$num];
                $i=0;
                foreach($course_arr as $val){
                    if($val==$courseid){
                        $i=1;
                    }
                }
                if($i==0){
                    @$list[$num][]=$courseid;
                }
            }else{
                @$list[$num][]=$courseid;
            }
        }else{
            $list=[];
            @$list[$num][]=$courseid;
        }
        $str = json_encode($list);
        return $str;

    }

    public function test_period(){
        // $day_time =  strtotime("2018-01-02");
        // $festival_info = $this->t_festival_info->get_festival_info_by_end_time($day_time);
        // $festival_day_str = date("Y-m-d H:i:s",$festival_info["begin_time"])." ~ ".date("Y-m-d 22:i:s",$festival_info["end_time"]);

        // $add_time = strtotime("2018-01-01");
        // $attendance_type = 3;
        // $arr = $this->t_fulltime_teacher_attendance_list->get_festaival_info( $add_time,$attendance_type);
        // foreach ($arr as $key => $value) {
        //     /**
        //      * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
        //      * 标题课程 : 待办事项提醒
        //      * {{first.DATA}}
        //      * 待办主题：{{keyword1.DATA}}
        //      * 待办内容：{{keyword2.DATA}}
        //      * 日期：{{keyword3.DATA}}
        //      * {{remark.DATA}}
        //      */

        //     $holiday_hugh_time_arr = json_decode($value["holiday_hugh_time"],true);
        //     $holiday_hugh_time_str = date("Y.m.d",@$holiday_hugh_time_arr["start"])."-".date("Y.m.d",@$holiday_hugh_time_arr["end"]);

        //     $lesson_count = $value["lesson_count"]/100;
        //     $data=[];
        //     $url = "";
        //     $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        //     $data['first']    =  $festival_info["name"]."延休统计";
        //     $data['keyword1'] = "延休数据汇总";
        //     $data['keyword2'] = "\n老师:".$value['realname'].
        //                         "\n时间:".$festival_day_str.
        //                         "\n累计上课课时:".$lesson_count.
        //                       "\n延休天数:".$value['day_num'].
        //                       "\n延休日期:".$holiday_hugh_time_str;

        //     $data['keyword3'] = date("Y-m-d H:i",time());
        //     $data['remark']   = ""; 
        //     // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
        //     $wx_openid = $value["wx_openid"];
        
        //     \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
 
        // }
        // dd($arr);

       


         $page_info= $this->get_in_page_info();
        $grade=$this->get_in_el_grade();
        $ret_info=$this->t_student_info->get_test_list($page_info, $grade );
        // $gender=$this->get_in_el_gender();
        $this->get_in_query_text();
        // list($start_time, $end_time)=$this->get_in_date_range_day(0);

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }
        $tt=  $this->last_in_values;
        dd($tt);

        return $this->pageOutJson(__METHOD__, $ret_info);

       //  $ret_info=[];
        return $this->pageOutJson(__METHOD__, $ret_info);

        $method = __METHOD__;
        if (preg_match("/([a-zA-Z0-9_]+)::([a-zA-Z0-9_]+)/",$method, $matches)  )  {
            // $this->view_ctrl=$matches[1];
            // $this->view_action=strtolower($matches[2]);
        }
        dd($matches);

        $orderNo = $this->get_in_str_val("ORDERID","701748525753");
        $posid   = $this->get_in_str_val("POSID","002171923");
        $branchid = $this->get_in_str_val("BRANCHID","310000000");
        $payment  = $this->get_in_str_val("PAYMENT","1.00");
        $curcode = $this->get_in_str_val("CURCODE","01");
        $remark1 = $this->get_in_str_val("REMARK1","");
        $remark2 = $this->get_in_str_val("REMARK2","");
        $success = $this->get_in_str_val("SUCCESS","N");
        $acc_type = $this->get_in_str_val("ACC_TYPE","30");
        $type = $this->get_in_str_val("TYPE","1");
        $referer = $this->get_in_str_val("REFERER","");
        $clientip = $this->get_in_str_val("CLIENTIP","116.226.191.6");
        $installnum = $this->get_in_str_val("INSTALLNUM","12");
        $errmsg = $this->get_in_str_val("ERRMSG");
        $sign = $this->get_in_str_val("SIGN","&CLIENTIP=116.226.191.6&INSTALLNUM=12&ERRMSG=&SIGN=5d00745445c4e3cc4dc99653bb2516cdac417701431e591088b5fdfddb984a116760e6156641ddd46cb6d434a6b5150aa4c37f7cf4732b2b94241ea926b0e1d4234b53f458d3ab2f80d6df3f6fc785450240105ace4b76dc6525191cbca54e1c09377b67cd6f42de89582e2987de1fd557368fa18dca273541f2d5a823ff30f6");
        $data = "POSID=".$posid."&BRANCHID=".$branchid."&ORDERID=".$orderNo."&PAYMENT=".$payment."&CURCODE=".$curcode."&REMARK1=".$remark1."&REMARK2=".$remark2."&ACC_TYPE=".$acc_type."&SUCCESS=".$success."&TYPE=".$type."&REFERER=".$referer."&CLIENTIP=".$clientip."&INSTALLNUM=".$installnum."&ERRMSG=".$errmsg;
        dd([1=>$sign,2=>$data]);
        // $data = "POSID=".$posid."&BRANCHID=".$branchid."&ORDERID=".$orderNo."&PAYMENT=".$payment."&CURCODE=".$curcode."&REMARK1=".$remark1."&REMARK2=".$remark2."&SUCCESS=".$success;

        if($posid=="002171923"){
            $cmd ='cd /home/ybai/bin/Cbb/ && java Main "'.$data.'" "'.$sign.'"'; 
        }elseif($posid=="002171916"){
            $cmd ='cd /home/ybai/bin/Cbb/ && java Other "'.$data.'" "'.$sign.'"'; 
        }
        // echo $cmd;
        //dd(11);
        // dd($cmd);
        $verifyResult = \App\Helper\Utils::exec_cmd($cmd);
        dd($verifyResult);

        $phone = 13720242210;
        $time = 1514313347;
        $role=1;
        dd(md5("leo15143216401381775934621v1"));
        $this->switch_tongji_database();
        $start_time = strtotime("2017-10-01");
        $end_time = strtotime("2018-01-01");
        $teacher_money_type=6;
        $list     = $this->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        dd($list);

        $teacher_money_type = $this->get_in_int_val("teacher_money_type",6);
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $page_info = $this->get_in_page_info();


        $start_time = strtotime("2017-10-01");
        $ret_info = $this->t_teacher_advance_list->get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,-1,-1,-1,0);
        dd($ret_info);

        dd(md5("@leo"));
        $orderid = $this->get_in_int_val("orderid");
        $old_list = $this->t_child_order_info->field_get_list($orderid,"pay_status,pay_time,channel");
        if($old_list["pay_status"]==1 && $old_list["pay_time"]>0 && $old_list["channel"]=="baidu"){
            return $this->output_succ(["status"=>0,"msg"=>"success"]);
        }
        dd(1111);

        $role=$this->get_in_int_val("role",1);
        $phone = $this->get_in_phone();
        $code_key = $phone."-".$role."-code";
        \App\Helper\Utils::logger("key:$code_key");


        
        $check_verify_code = session($code_key);
        dd($check_verify_code);

        $tt = $this->get_parent_courseid(1111,2,303);
        dd($tt);
        // $list = $this->get_baidu_money_charge_pay_info_test();
        dd(111);

        $key = "sms_phone_13817759346";
        $data = json_decode( \App\Helper\Common::redis_get($key) ,true);
        dd($data);

        $this->reset_parent_course_info(358650,1391851545550);
        dd(111);

        $list = $this->t_child_order_info->get_all_payed_prder_info();
        foreach($list as $val){
            $competition_flag = $val["competition_flag"];
            if($competition_flag==1){
                $courseid = "SHLEOZ3101006";
                $arr =[4=>[$courseid]];
                $coursename = "思维拓展在线课程";
            }elseif($val["grade"] >=100 && $val["grade"]<200){
                $courseid = "SHLEOZ3101001";
                $arr =[1=>[$courseid]];
                $coursename = "小学在线课程";
            }elseif($val["grade"] >=200 && $val["grade"]<300){
                $courseid = "SHLEOZ3101011";
                $arr =[2=>[$courseid]];
                $coursename = "初中在线课程";
            }elseif($val["grade"] >=300 && $val["grade"]<400){
                $courseid = "SHLEOZ3101016";
                $arr =[3=>[$courseid]];
                $coursename = "高中在线课程";
            }
            $str = json_encode($arr);
            dd($str);
            $this->t_parent_info->field_update_list($val["parentid"],[
                "baidu_class_info" => $str
            ]);

        }
        dd($list);

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $adminid= $this->get_in_int_val("adminid",480 );
        $date_list_old=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $date_arr=[];
        foreach($date_list_old as $k=>$val){
            $time = strtotime($k);
            $date_arr[$time]["date"]=$time;
        }
        $adminid_list = $this->t_manager_info->get_adminid_list_by_account_role(5);
        $ret_info=$this->t_admin_card_log->get_list( 1, $start_time,$end_time,-1,100000,5 );
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
        dd($data);
        $ret_info=$this->t_admin_card_log->get_list( 1, $start_time,$end_time,$adminid,100000,5 );
        $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $teacherid = @$teacher_info["teacherid"];

        foreach ($ret_info["list"] as $item ) {
            $logtime=$item["logtime"];
            $opt_date=date("Y-m-d",$logtime);
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
        dd(1111);

        // $start_time = strtotime("2017-11-15");
        // $list = $this->t_seller_student_new->get_ass_tran_stu_info_new($start_time,time());

        // dd($list);
        // $arr=[];
        // $order_info = $this->t_order_info->field_get_list(13868,"*");
        // unset($order_info["orderid"]);
        // $this->t_order_info_finance->row_insert($order_info);

      
        $start_time = strtotime("2016-12-01");
        $end_time = strtotime("2017-01-01");
        // $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        // $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        // foreach($teacher_arr_ex as $k=>$val){
        //     if(!isset($teacher_list_ex[$k])){
        //         $teacher_list_ex[$k]=$k;
        //     }
        // }

        // $all_tea_ex = count($teacher_list_ex);
        // dd($teacher_list_ex);
        // $train_all = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,-1);
        // $train_succ = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,1);
        // $arr[7]=["参加培训"=>$train_all,"通过培训"=>$train_succ];
        // $start_time = strtotime("2017-08-01");
        // $end_time = strtotime("2017-09-01");
        // $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        // $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        // foreach($teacher_arr_ex as $k=>$val){
        //     if(!isset($teacher_list_ex[$k])){
        //         $teacher_list_ex[$k]=$k;
        //     }
        // }

        // $all_tea_ex = count($teacher_list_ex);
        // $train_all = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,-1);
        // $train_succ = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,1);
        // $arr[8]=["参加培训"=>$train_all,"通过培训"=>$train_succ];
        // $start_time = strtotime("2017-09-01");
        // $end_time = strtotime("2017-10-01");
        // $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        // $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        // foreach($teacher_arr_ex as $k=>$val){
        //     if(!isset($teacher_list_ex[$k])){
        //         $teacher_list_ex[$k]=$k;
        //     }
        // }

        // $all_tea_ex = count($teacher_list_ex);
        // $train_all = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,-1);
        // $train_succ = $this->t_lesson_info_b2->get_all_train_num_new($start_time,$end_time,$teacher_list_ex,1);
        // $arr[9]=["参加培训"=>$train_all,"通过培训"=>$train_succ];
        // dd($arr);
        // dd(1111);


        // $list = $this->t_order_info_finance->get_add_info();
        // foreach($list as $val){
        //     $val["contract_starttime"] = strtotime("+1 months",$val["contract_starttime"]);
        //     $val["contract_endtime"] = strtotime("+1 months",$val["contract_endtime"]);
        //     $this->t_order_info_finance->field_update_list($val["orderid"],[
        //         "contract_starttime" => $val["contract_starttime"],
        //         "contract_endtime" => $val["contract_endtime"],
        //     ]);
        // }
        // dd(111);

        $contract_type = $this->get_in_int_val("contract_type",3);
        $order_info = $this->t_order_info_finance->get_order_info($start_time,$end_time,$contract_type);
        // $order_info_t = $this->t_order_info_finance->get_order_tongji_info($start_time,$end_time,$contract_type);
        $arr=[];
        $money=0;
        foreach($order_info as $val){

            if($val["price"]>400000 && $val["price"]<1630000 && in_array($val["orderid"],[13344,12948])){
                $money +=$val["price"];
                if(!isset($arr[$val["userid"]])){
                    $arr[$val["userid"]]=$val["userid"];
                }

                // $val["order_time"] = strtotime("+2 months",$val["order_time"]);
                // $val["pay_time"] = strtotime("+2 months",$val["pay_time"]);
                // if($val["app_time"]>0){
                //     $val["app_time"] = strtotime("+2 months",$val["app_time"]);
                // }
                // $val["check_money_time"] = strtotime("+2 months",$val["check_money_time"]);
                // $val["contract_starttime"] = strtotime("+2 months",$val["contract_starttime"]);
                // $val["contract_endtime"] = strtotime("+2 months",$val["contract_endtime"]);

                $val["order_time"] = strtotime("+1 months",$val["order_time"]);
                $val["pay_time"] = strtotime("+1 months",$val["pay_time"]);
                if($val["app_time"]>0){
                    $val["app_time"] = strtotime("+1 months",$val["app_time"]);
                }
                $val["check_money_time"] = strtotime("+1 months",$val["check_money_time"]);
                $val["contract_starttime"] = strtotime("+1 months",$val["contract_starttime"]);
                $val["contract_endtime"] = strtotime("+1 months",$val["contract_endtime"]);

                
                $val["parent_order_id"] = 3000;
                unset($val["orderid"]);
                $this->t_order_info_finance->row_insert($val);


                // $this->t_order_info_finance->field_update_list($val["orderid"],[
                //    "contract_type"=>100 
                // ]);

                if(count($arr) >= 2){
                    break;
                }
 
            }
        }
        // dd([$order_info,$order_info_t]);
        dd([$arr,$money]);
        // $time = time()-7*86400;
        dd(date("w",time()));
        $day_time = strtotime("2017-10-09");
        $check_holiday = $this->t_fulltime_teacher_attendance_list->check_is_in_holiday(231463,$day_time);
        dd($check_holiday);
        //节假日延休        
        $festival_info = $this->t_festival_info->get_festival_info_by_end_time($day_time);
        if($festival_info){
            $attendance_day = $day_time+86400;
            $lesson_info = $this->t_lesson_info_b2->get_qz_tea_lesson_info_b2($festival_info["begin_time"],$attendance_day);
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
                @$arr[$key]['attendance_time'] = $attendance_day;
                @$arr[$key]['holiday_end_time'] = $attendance_day+($arr[$key]['day_num']-1)*86400;
                if($arr[$key]['day_num'] == 0){
                    @$arr[$key]['cross_time'] = "";
                }else{
                    @$arr[$key]['cross_time'] = date('m.d',$attendance_day)."-".date('m.d',$arr[$key]['holiday_end_time']);
                }
              
            }
            //insert data
            // foreach ($arr as $key => $value) {
            //     if($value['day_num']>=1){
            //         $task->t_fulltime_teacher_attendance_list->row_insert([
            //             "teacherid"        =>$value['teacherid'],
            //             "add_time"         =>$time,
            //             "attendance_type"  =>3,
            //             "attendance_time"  =>$value["attendance_time"],
            //             "day_num"          =>$value['day_num'],
            //             "adminid"          =>$key,
            //             "lesson_count"     =>$value['lesson_count']*100,
            //             "holiday_end_time" =>$value["holiday_end_time"],
            //         ]);
            //     } 
            // }
            //wx
            foreach ($arr as $key => $value) {
                $this->t_manager_info->send_wx_todo_msg_by_adminid (
                    349,
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
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //erick
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //low-key

            //email
            $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">全职老师假期累计上课时间及延休安排</td></tr>';
            $table .= '<tr><td>假期名称</td><td colspan="3" align="center"><font color="red">'.$festival_info["name"].'</font></td></tr>';
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
            // $email_arr = ["low-key@leoedu.com",
            //               "erick@leoedu.com",
            //               "hejie@leoedu.com",
            //               "sherry@leoedu.com",
            //               "cindy@leoedu.com",
            //               "limingyu@leoedu.com"];
            $email_arr = ["jack@leoedu.com"];

            foreach($email_arr as $email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,
                    "全职老师".$festival_info["name"]."假期累计上课时间及延休安排",
                    $content
                ));  
            }

        }
        dd(1111);



       
        $lesson_end = $this->get_in_str_val("lesson_end","2017-11-23 09:00:00");
        $lesson_end = strtotime($lesson_end);
        $day_time = strtotime(date("Y-m-d",$lesson_end));
        $begin_time = $day_time+9.5*3600;
        $list = $this->t_lesson_info_b2->get_delay_work_time_lesson_info($day_time,$lesson_end);
        $i=0;
        foreach($list as $item){
            $teacherid = $item["teacherid"];
            if($item["lesson_type"]==2){
                $lesson_end = $item["lesson_end"]+1200;
            }else{
                $lesson_end = $item["lesson_end"];
            }
            echo $i."<br>";
            $i++;
            $id = $this->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
            $attendance_type = $this->t_fulltime_teacher_attendance_list->get_attendance_type($id);
            if($id>0 && $attendance_type==2){
                $end = $this->get_last_lesson_end($teacherid,$lesson_end);
                $delay_time = $end+5400;
                if($delay_time>$begin_time){
                    $this->t_fulltime_teacher_attendance_list->field_update_list($id,[
                        "delay_work_time" =>$delay_time,
                    ]);
                }
                echo $delay_time."111<br>";
            }elseif(empty($id)){
                $end = $this->get_last_lesson_end($teacherid,$lesson_end);
                $delay_time = $end+5400;
                if($delay_time>$begin_time){
                    $this->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>time(),
                        "attendance_type" =>2,
                        "attendance_time"  =>$day_time,
                        "delay_work_time"         =>$delay_time,
                        "adminid"          =>$item["uid"]
                    ]);

                }
                echo $delay_time."222<br>";


            }
 
        }
        dd($list);
        $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
        $lesson_start = $lesson_end+1800;
        $lesson_list = $this->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
        dd($lesson_list);
        dd(111);

       


    }

        //更新家长百度有钱花课程信息
    public function reset_parent_course_info($userid,$orderNo){
        $pp_info = $this->t_student_info->field_get_list($userid,"parentid,grade");
        $courseid = $this->t_orderid_orderno_list->get_courseid($orderNo);
        $grade=$pp_info["grade"];
        $parent_orderid = $this->t_orderid_orderno_list->get_parent_orderid($orderNo);
        $competition_flag = $this->t_order_info->get_competition_flag($parent_orderid);
        if($competition_flag==1){
            if(!$courseid){
                $courseid = "SHLEOZ3101006"; 
            }
            $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            if($course_list){
                $list=json_decode($course_list,true);
            }else{
                $list=[];
            }
            @$list[4][]=$courseid;
            $str = json_encode($list);
            
        }elseif($grade >=100 && $grade<200){
            if(!$courseid){
                $courseid = "SHLEOZ3101001"; 
            }
            $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            if($course_list){
                $list=json_decode($course_list,true);
            }else{
                $list=[];
            }
            @$list[1][]=$courseid;
            $str = json_encode($list);
        }elseif($grade >=200 && $grade<300){
            if(!$courseid){
                $courseid = "SHLEOZ3101012"; 
            }
            $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            if($course_list){
                $list=json_decode($course_list,true);
            }else{
                $list=[];
            }
            @$list[2][]=$courseid;
            $str = json_encode($list);
        }elseif($grade >=300 && $grade<400){
            if(!$courseid){
                $courseid = "SHLEOZ3101016"; 
            }
            $course_list = $this->t_parent_info->get_baidu_class_info($pp_info["parentid"]);
            if($course_list){
                $list=json_decode($course_list,true);
            }else{
                $list=[];
            }
            @$list[3][]=$courseid;
            $str = json_encode($list);
        }
        $this->t_parent_info->field_update_list($pp_info["parentid"],[
            "baidu_class_info" =>$str 
        ]);


        

    }


    public function test_wx(){
        // $admin_revisiterid= $this->t_order_info-> get_last_seller_by_userid(60001);
        // //$admin_revisiterid= $origin_assistantid;
        // dd($admin_revisiterid);

        // $this->t_flow_node->row_insert([
        //     "node_type"=>1,
        //     "flowid"   =>4713,
        //     "adminid"  =>1004,
        //     "add_time" =>time()
        // ]);
        // dd(1111);
        // $ret_info   = $this->t_flow_node->get_node_list(4713,"asc");
        // dd($ret_info);

        // /**
        //  * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
        //  * 标题课程 : 待办事项提醒
        //  * {{first.DATA}}
        //  * 待办主题：{{keyword1.DATA}}
        //  * 待办内容：{{keyword2.DATA}}
        //  * 日期：{{keyword3.DATA}}
        //  * {{remark.DATA}}
        //  */

        // $data=[];
        // $url = "";
        // $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";

        // $history_flag = $this->get_in_int_val("history",1);
        // if($history_flag==1){
        //     $data['first']    = "老师您好,为方便您尽快完成理优入职流程,特邀您观看【新师培训】回放视频";
        //     $data['keyword1'] = "新师培训";
        //     $data['keyword2'] = "参训方法:登录老师端-我的培训-新师培训-播放视频";
        //     $data['keyword3'] = date("Y-m-d H:i",time());
        //     $data['remark']   = "如有疑问,可在新师培训QQ群:315540732 咨询【师训】老师"; 
        // }elseif($history_flag==0){
        //     $data['first']    = "老师您好,为方便您尽快完成理优入职流程,特邀您参加在线【新师培训】";
        //     $data['keyword1'] = "新师培训";
        //     $data['keyword2'] = "参训方法:登录老师端-我的培训-新师培训-进入课堂(提前5分钟)";
        //     $data['keyword3'] = date("Y-m-d H:i",time());
        //     $data['remark']   = "如有疑问,可在新师培训QQ群:315540732 咨询【师训】老师"; 

        // }
        // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
        
        // \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        // dd(111);


        // $start_time = strtotime("2016-12-01");
        // for($i=1;$i<11;$i++){
        //     $start_time = strtotime("+1 months",$start_time);
        //     $end_time = strtotime("+1 months",$start_time);
        //     $top_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,1,2);//教务1000精排总体
        //     $top_jw_total["per"] = !empty($top_jw_total["person_num"])?round($top_jw_total["have_order"]/$top_jw_total["person_num"]*100,2):0;
        //     @$arr["精排"] +=$top_jw_total["have_order"];

        //     $green_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,2,2); //教务绿色通道总体
        //     $green_jw_total["per"] = !empty($green_jw_total["person_num"])?round($green_jw_total["have_order"]/$green_jw_total["person_num"]*100,2):0;
        //     @$arr["绿色"] +=$green_jw_total["have_order"];

        //     $normal_jw_total_grab = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,2,1); //教务普通排课总体(抢课)
        //     $normal_jw_total_grab["per"] = !empty($normal_jw_total_grab["person_num"])?round($normal_jw_total_grab["have_order"]/$normal_jw_total_grab["person_num"]*100,2):0;
        //     @$arr["抢课"] +=$normal_jw_total_grab["have_order"];
        //     $normal_jw_total = $this->t_lesson_info_b3->get_seller_test_lesson_tran_info( $start_time,$end_time,3,2,0); //教务普通排课总体(非抢课)
        //     $normal_jw_total["per"] = !empty($normal_jw_total["person_num"])?round($normal_jw_total["have_order"]/$normal_jw_total["person_num"]*100,2):0;
        //     @$arr["普通"] +=$normal_jw_total["have_order"];


        // }
        // dd($arr);
        // $end_time = strtotime("2017-11-01");
        // $lesson_money_list = $this->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
       

        // $lesson_money_all = $this->t_manager_info->get_assistant_lesson_money_info_all($start_time,$end_time);
        // $lesson_count_all = $this->t_manager_info->get_assistant_lesson_count_info_all($start_time,$end_time);
        // $lesson_price_avg = !empty($lesson_count_all)?$lesson_money_all/$lesson_count_all:0;

        // $ass_month = $this->t_month_ass_student_info->get_ass_month_info($start_time);
        // foreach($ass_month as $val){
        //     $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"];//课耗收入          
        //     $item["lesson_price_avg"] = (round(@$lesson_count_list[$k]["lesson_count"]*$lesson_price_avg/100,2))*100;
        //     $this->t_month_ass_student_info->get_field_update_arr($val["adminid"],$start_time,1,[
        //         "lesson_money"  =>$item["lesson_money"],
        //         "lesson_price_avg" =>$item["lesson_price_avg"]
        //     ]);


        // }
        // dd(11);



        // $url="http://api.clink.cn/interfaceAction/cdrObInterface!listCdrOb.action";

        // $this->t_manager_info-> get_tquin_uid_map();

        // $start_time= time()-900;
        // $end_time = time();
        // $post_arr=[
        //     "enterpriseId" => 3005131  ,
        //     "userName" => "admin" ,
        //     "pwd" =>md5(md5("Aa123456" )."seed1")  ,
        //     "seed" => "seed1",
        //     "startTime" => date("Y-m-d H:i:s", $start_time),
        //     "endTime" => date("Y-m-d H:i:s", $end_time),
        // ];

        // $limit_count =500;
        // $index_start=0;
        // $post_arr["start"]  = $index_start;
        // $post_arr["limit"]  = $limit_count;
        // $return_content= \App\Helper\Net::send_post_data($url, $post_arr );
        // $ret=json_decode($return_content, true  );
        // dd($ret);

        // $list = $this->t_teacher_info->get_all_teacher_tags();
        // foreach($list as $vall){
        //     $teacher_tags_list = json_decode($vall["teacher_tags"],true);
        //     // \App\Helper\Utils::logger("teacherid".$vall["teacherid"]);
        //     if(is_array($teacher_tags_list)){
                
        //     }else{
        //         \App\Helper\Utils::logger("teacherid".$vall["teacherid"]);

        //         $tag = trim($vall["teacher_tags"],",");
        //         if($tag){
        //             $arr2 = explode(",",$tag);
        //             $teacher_tags_list=[];
        //             foreach($arr2 as $val){
        //                 if($val=="循循善诱"){
        //                     $val="鼓励激发";
        //                 }elseif($val=="细致耐心"){
        //                     $val="耐心细致";
        //                 }elseif($val=="善于互动"){
        //                     $val="互动引导";
        //                 }elseif($val=="没有口音"){
        //                     $val="普通话标准";
        //                 }elseif($val=="考纲熟悉"){
        //                     $val="熟悉考纲";
        //                 }

        //                 $teacher_tags_list[$val]=1;
        //             }
        //             $str = json_encode($teacher_tags_list);
        //             $this->t_teacher_info->field_update_list($vall["teacherid"],[
        //                 "teacher_tags" =>$str
        //             ]);
 
        //         }else{
        //             $teacher_tags_list=[];
        //         }
                
        //     }
 
        // }
        // dd($list);
       
        // $list = $this->t_lesson_info_b3->get_lesson_info_by_teacherid_test(85081);
        // $i=2;
        // foreach($list as $val){
        //     $this->t_teacher_record_list->row_insert([
        //         "teacherid"      => $val["teacherid"],
        //         "type"           => 1,
        //         "train_lessonid" => $val["lessonid"],
        //         "lesson_time"    => $val["lesson_start"],
        //         "lesson_style"   => $i,
        //         "add_time"       => time()+$i*100,
        //         "userid"         => $val["userid"]
        //     ]);
        //     $i++;
 
        // }
        

        // $arr=[
        //     ["tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格"],
        //     ["tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力"],
        //     ["tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂氛围"],
        //     ["tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求"],
        //     ["tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养"] ,
        // ];
        // $list=[];
        // foreach( $arr as $val){
        //     $ret = $this->t_tag_library->get_tag_name_list($val["tag_l1_sort"],$val["tag_l2_sort"]);
        //     $rr=[];
        //     foreach($ret as $item){
        //         $rr[]=$item["tag_name"];
        //     }
        //     $list[$val["tag_l2_sort"]]=$rr;
        // }
        // dd($list);

        // $list = $this->t_tag_library->get_tag_name_list("教师相关","风格性格");
        // dd($list);

        $adminid = $this->get_account_id();
        $arr=[
            ["tag_name"=>"幽默风趣","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"生动活泼","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"鼓励激发","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"耐心绅士","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"口语标准","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"经验丰富","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"普通话标准","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"熟悉考纲","tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"激昂热情","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"生动活泼","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"鼓励激发","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"经验丰富","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"丰富有趣","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"游戏相关","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"图片精美","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"嘻嘻哈哈","tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"兴趣培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"习惯培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"信心建立","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"学习方法技巧","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],
            ["tag_name"=>"文学素养培养","tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养",'create_time' => time(NULL),'manager_id' => $adminid],

        ];
        foreach($arr as $var){
            $this->t_tag_library->row_insert($var);
 
        }
               // return $this->output_succ();

        // $this->t_student_info->reset_lesson_count(440915);
        dd(1111);
        $aa = E\Eorder_channel::s2v("alipay_pc_direct");
        $channel_name = E\Eorder_channel::get_desc($aa);
        dd($channel_name);

        $noti_account = $this->t_assistant_info->get_account_by_id(441550);
        $header_msg="测试";
        $msg="学生:" ;
        $url="/user_manage/ass_archive_ass";
        // $ret=$this->t_manager_info->send_wx_todo_msg($noti_account, $this->get_account() ,$header_msg,$msg ,$url);
       
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

        $data=[
            "first"    => "测试",
            "keyword1" => "测试",
            "keyword2" => "测试",
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => "测试",
        ];
        $url="";

        $wx     = new \App\Helper\Wx();
        $openid = $this->t_manager_info->get_wx_openid_by_account("巫叔敏");
        $ret = $wx->send_template_msg("orwGAs-t9gt9GrqKIPN0nBLZuMgg",$template_id,$data ,$url);

        if($ret) {
        }else{
            return $this->output_err("发送WX通知失败,请确认[$noti_account]有绑定微信");
        }

        dd($noti_account);
 
    }

    public function ajax_deal_jack(){
        $this->switch_tongji_database();
        $this->check_and_switch_tongji_domain();
        // $userid           = $this->get_in_int_val("userid");
        // $tea_name = $this->t_lesson_info_b3->get_last_class_tea_name($userid);
        // return $this->output_succ(["num1"=>$tea_name]);


        // $ass_list = $this->t_ass_stu_change_list->get_stu_ass_list($userid);
        // $ass_num=[];
        // foreach($ass_list as $val){
        //     $adminid = $val["adminid"];
        //     $adminid_old = $val["old_ass_adminid"];
        //     if($adminid>0 && !isset($ass_num[$adminid])){
        //         $ass_num[$adminid]=$adminid;
        //     }
        //     if($adminid_old>0 && !isset($ass_num[$adminid_old])){
        //         $ass_num[$adminid_old]=$adminid_old;
        //     }

        // }
        // $num1 = count($ass_num)>0?count($ass_num):1;
        // $tea_num_list = $this->t_lesson_info_b3->get_tea_num_by_subject($userid);
        // $num2=0;
        // foreach($tea_num_list as $v){
        //     if($num2<$v["num"]){
        //         $num2 = $v["num"];
        //     }
        // }
        // return $this->output_succ([
        //     "num1"=>$num1,
        //     "num2"=>$num2,
        // ]);


        
        
        $start           = $this->get_in_int_val("userid");
        $end = strtotime("+1 months",$start);
        $start = strtotime("2017-01-01");
        $end = strtotime("2018-01-01");

        $arr=["num1"=>0,"num2"=>1,"num3"=>2,"num4"=>3,"num5"=>4,"num6"=>11,"num"=>-1];
        $list=[];
        foreach($arr as $k=>$val){
            $ret=$this->t_lesson_info_b3->get_lesson_count_by_level($start,$end,$val);
            $key1 = $k."_tea";$key2=$k."_stu";$key3=$k."_lesson";
            $list[$key1] = @$ret["num"];
            $list[$key3] = (@$ret["num"]>0)?round($ret["lesson_count"]/$ret["num"]/100):0;
            $ret_detail=$this->t_lesson_info_b3->get_lesson_count_by_level_detail($start,$end,$val);
            $stu_num=0;
            foreach($ret_detail as $tt){
                $stu_num +=$tt["num"];

            }
            $list[$key2] = (@$ret["num"]>0)?round($stu_num/$ret["num"],1):0;
            

        }
        return $this->output_succ($list);
        $list1 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,0);
        $num1 = (isset($list1["num"]) && $list1["num"]>0)?round($list1["lesson_count"]/$list1["num"]):0;
        $list2 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,1);
        $num2 = (isset($list2["num"]) && $list2["num"]>0)?round($list2["lesson_count"]/$list2["num"]):0;
        $list3 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,2);
        $num3 = (isset($list3["num"]) && $list3["num"]>0)?round($list3["lesson_count"]/$list3["num"]):0;
        $list4 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,3);
        $num4 = (isset($list4["num"]) && $list4["num"]>0)?round($list4["lesson_count"]/$list4["num"]):0;
        $list5 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,4);
        $num5 = (isset($list5["num"]) && $list5["num"]>0)?round($list5["lesson_count"]/$list5["num"]):0;
        $list6 = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,11);
        $num6 = (isset($list6["num"]) && $list6["num"]>0)?round($list6["lesson_count"]/$list6["num"]):0;
        $list = $this->t_lesson_info_b3->get_lesson_count_by_level(-1,-1,-1);
        $num = (isset($list["num"]) && $list["num"]>0)?round($list["lesson_count"]/$list["num"]):0;
        return $this->output_succ([
            "num1"=>$num1/100,
            "num2"=>$num2/100,
            "num3"=>$num3/100,
            "num4"=>$num4/100,
            "num5"=>$num5/100,
            "num6"=>$num6/100,
            "num"=>$num/100,
        ]);

        $num = $this->t_test_lesson_subject_require->check_user_have_require($userid);
        $list=[];
        $list["num"] = $num==1?"是":"否";
        return $this->output_succ($list);




        // $teacherid             = $this->get_in_int_val("teacherid");
        $start_time            = $this->get_in_int_val("start_time");
        $end_time             = strtotime("+1 months",$start_time);
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2018-01-01");

        // $list = $this->t_lesson_info_b3->get_lesson_count_by_grade($start_time,$end_time);
        $small = $this->t_lesson_info_b3->get_stu_num_by_grade($start_time,$end_time,1);
        $middle = $this->t_lesson_info_b3->get_stu_num_by_grade($start_time,$end_time,2);
        $high = $this->t_lesson_info_b3->get_stu_num_by_grade($start_time,$end_time,3);
        $all =  $small+$middle+$high;
        $list=[];
        $list["small_grade"] = round($small/$all*100,2)."%";
        dd($list["small_grade"]);

        $list["middle_grade"] = round($middle/$all*100,2)."%";
        $list["high_grade"] =round($high/$all*100,2)."%";
        return $this->output_succ($list);

        $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
        $week_start = $date_week["sdate"]-14*86400;
        $week_end = $date_week["sdate"]+21*86400;
        $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5);
        $qz_tea_arr=[];
        foreach($ret_info as $yy=>$item){
            if($item["teacherid"] != 97313){
                $qz_tea_arr[] =$item["teacherid"];
            }else{
                unset($ret_info[$yy]);
            }
        }
        $list = $this->t_lesson_info_b2->get_tea_stu_num_list_detail($qz_tea_arr,$week_start,$week_end);
        $all_num = $one_num=$two_num = $three_num = $four_num = $five_num = $six_num = $other_num=0;
        $data=[];
        foreach($list as $val){
            @$data["all_num"]++;
            // $lesson_count = $val["lesson_all"]/500;
            // if($lesson_count<=1){
            //     @$data["one_num"]++;
            // }elseif($lesson_count<=2){
            //     @$data["two_num"]++;
            // }elseif($lesson_count<=3){
            //     @$data["three_num"]++;
            // }elseif($lesson_count<=4){
            //     @$data["four_num"]++;
            // }elseif($lesson_count<=5){
            //     @$data["five_num"]++;
            // }elseif($lesson_count<=6){
            //     @$data["six_num"]++;
            // }else{
            //     @$data["other_num"]++;
            // }

        }
        $list2 = $this->t_week_regular_course->get_tea_stu_num_list_detail($qz_tea_arr);
        foreach($list2 as $val){
            $lesson_count = $val["lesson_all"]/100;
            if($lesson_count==1){
                @$data["one_num"]++;
            }elseif($lesson_count==1.5){
                @$data["one_five_num"]++;
            }elseif($lesson_count==2){
                @$data["two_num"]++;
            }elseif($lesson_count==2.5){
                @$data["two_five_num"]++;
            }elseif($lesson_count==3){
                @$data["three_num"]++;
            }elseif($lesson_count==3.5){
                @$data["three_five_num"]++;
            }elseif($lesson_count==4){
                @$data["four_num"]++;
            }elseif($lesson_count==4.5){
                @$data["four_five_num"]++;
            }elseif($lesson_count==5){
                @$data["five_num"]++;
            }elseif($lesson_count==5.5){
                @$data["five_five_num"]++;
            }elseif($lesson_count==6){
                @$data["six_num"]++;
            }elseif($lesson_count==6.5){
                @$data["six_five_num"]++;
            }else{
                @$data["other_num"]++;
            }

        }


        $start_time = strtotime("2017-12-01");
        $end_time = strtotime("2018-01-01");

        $ret = $this->t_lesson_info_b3->get_teacher_lesson_info(-1,$start_time,$end_time,$qz_tea_arr);
        $stu_leave_num = $tea_leave_num=0;
        foreach($ret as $val){
            @$data["stu_leave_num"] +=$val["stu_leave_count"]/100;
            @$data["tea_leave_num"] +=$val["tea_leave_count"]/100;
        }
        return $this->output_succ(["data"=>$data]);
        dd($list);

    }

    public function get_reference_teacher_money_info(){
        //拉数据
        $this->check_and_switch_tongji_domain();
        // $start_time = strtotime("2018-02-01");
        // // $start_time = strtotime("2017-06-01");
        // $end_time = strtotime("2018-03-01");
        // $list = $this->t_student_info->get_stop_student_list($start_time,$end_time);
        // foreach($list as &$val){
        //     E\Estudent_type::set_item_value_str($val);
        //     E\Egrade::set_item_value_str($val);
        // }
        // return $this->pageView(__METHOD__,null,[
        //     "list"  =>$list
        // ]);

        // dd($list);

        // $list = $this->t_order_refund->get_order_refund_userid_by_apply_time($start_time,$end_time);
        $level = E\Enew_level::$simple_desc_map;
        $level[-1]="全部";
        // return $this->pageView(__METHOD__,null,[
        //     "list"  =>$list,
        //     "level" =>$level
        // ]);

        $list=[];
        $start_time = strtotime("2016-12-01");
        for($i=1;$i<=14;$i++){
            $first = strtotime(date("Y-m-01",strtotime("+".$i." months", $start_time)));
            // $next = strtotime(date("Y-m-01",strtotime("+1 months", $first)));
            $month = date("Y-m-d",$first);
            /* $order_money_info = $this->t_order_info->get_order_lesson_money_info($first,$next);
               $order_money_month = $this->t_order_info->get_order_lesson_money_use_info($first,$next);
               $list[$month]["stu_num"] = @$order_money_info["stu_num"];
               $list[$month]["all_price"] = @$order_money_info["all_price"];
               $list[$month]["lesson_count_all"] = @$order_money_info["lesson_count_all"];
               foreach($order_money_month as $val){
               $list[$month][$val["time"]]=($val["all_price"]/100)."/".($val["lesson_count_all"]/100);
               }*/
            $list[$month]["time"] = date("Y年m月",$first);
            $list[$month]["start"] = $first;


        }
        return $this->pageView(__METHOD__,null,[
            "list"  =>$list,
            "level" =>$level
        ]);


        $ret= $this->t_seller_student_new->get_new_thousand_stu();
        foreach($ret as &$val){          
            E\Egrade::set_item_value_str($val);
        }

        return $this->pageView(__METHOD__,null,[
            "list"  =>$ret
        ]);
        //学员停课预警
        // $list = $this->t_lesson_info_b3->get_stop_stu_lesson_info();
        // $data=[];
        // foreach($list as $val){
        //     $userid = $val["userid"];
        //     $subject = $val["subject"];
        //     $grade = $val["grade"];
        //     $data[$userid]["nick"] = $val["nick"]; 
        //     $data[$userid]["userid"] = $val["userid"];
        //     $data[$userid]["type"] =  E\Estudent_type::get_desc($val["type"]);
        //     if(!isset($data[$userid]["subject"][$subject])){
        //         $data[$userid]["subject"][$subject]=$subject;
        //         @$data[$userid]["subject_str"] .=E\Esubject::get_desc($subject).",";
        //     }
        //     if(!isset($data[$userid]["grade"][$grade])){
        //         $data[$userid]["grade"][$grade]=$grade;
        //         @$data[$userid]["grade_str"] .=E\Egrade::get_desc($grade).",";
        //     }

        // }
        // return $this->pageView(__METHOD__,null,[
        //     "list"  =>$data
        // ]);
        // dd($data);

        //课耗预警
        $start_time = strtotime("2018-01-01");
        $end_time = strtotime("2018-01-29");
        $list =$this->t_lesson_info_b3->get_same_stu_grade_subject_lesson_num_list($start_time,$end_time);
        $start = strtotime("2017-12-30");
        $data =$this->t_lesson_info_b3->get_same_stu_grade_subject_lesson_num_list($start,$end_time);
        $start_week = strtotime("2018-01-15");

        $week = $this->t_lesson_info_b3->get_same_stu_grade_subject_lesson_num_list( $start_week,$end_time);
        $arr1=$arr2=[];
        foreach($list as $val){
            $str = $val["subject"]."-".$val["grade"]."-".$val["userid"];
            $arr1[$str] = $val["num"];
        }
        foreach($week as $val){
            $str = $val["subject"]."-".$val["grade"]."-".$val["userid"];
            $arr2[$str] = $val["num"];
        }
        foreach($data as &$val){
            $str = $val["subject"]."-".$val["grade"]."-".$val["userid"];
            $val["four_week"] = @$arr1[$str];
            $val["two_week"] = @$arr2[$str];
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
        }
        $ret=[];
        foreach($data as $item){
            $userid = $item["userid"];
            $ret[$userid]["userid"]=$userid;
            $ret[$userid]["nick"]=$item["nick"];
            @$ret[$userid]["num"] +=$item["num"];
            @$ret[$userid]["four_week"] +=$item["four_week"];
            @$ret[$userid]["two_week"] +=$item["two_week"];
        }
        // dd($ret);
        return $this->pageView(__METHOD__,null,[
            "list"  =>$ret
        ]);





        //换老师
        $list = $this->t_lesson_info_b3->get_same_stu_grade_subject_num_list();
        // $data = json_encode($list);
        // $this->t_teacher_info->field_update_list(240314,[
        //     "prize"  => $data
        // ]);
        // dd($list);

        // $list = $this->t_teacher_info->get_prize(240314);
        // $list = json_decode($list,true);
        $data = [];
        foreach($list as $val){
            $str = $val["subject"]."-".$val["grade"]."-".$val["userid"];
            @$data[$str]++;
        }
        foreach($list as $k=>&$val){
            $str = $val["subject"]."-".$val["grade"]."-".$val["userid"];
            if(@$data[$str]>1){
                $val["change_num"]= @$data[$str]-1;
            }else{
                unset($list[$k]);
            }
           E\Esubject::set_item_value_str($val);
           E\Egrade::set_item_value_str($val);

        }
        return $this->pageView(__METHOD__,null,[
            "list"  =>$list
        ]);

        dd($list);
       
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2018-01-01");
        $order_num = $this->t_order_info->get_all_renew_stu_list_by_order($start_time,$end_time);
        $end_stu = $this->t_student_info->get_end_stu_list_str($start_time,$end_time);
        $list =[];
        foreach($end_stu as $k=>$val){
            if(!isset($order_num[$k])){
                $list[$k]=$val;
            }
        }
        $ass=[];
        foreach($order_num as $k=>$val){
            @$ass[$val["assistantid"]]["renew_num"] ++;
            $ass[$val["assistantid"]]["name"] = $val["nick"];
            $ass[$val["assistantid"]]["id"] = $val["assistantid"];
        }

        foreach($list as $k=>$val){
            @$ass[$val["assistantid"]]["end_num"] ++;
            $ass[$val["assistantid"]]["name"] = $val["nick"];
            $ass[$val["assistantid"]]["id"] = $val["assistantid"];
        }
        $renew_num_all = $end_num_all=$all=0;
        foreach($ass as &$val){
            $renew_num_all +=@$val["renew_num"];
            $end_num_all +=@$val["end_num"];
            $val["all"] = @$val["renew_num"]+@$val["end_num"];
            $val["per"] = $val["all"]==0?0:round(@$val["renew_num"]/$val["all"]*100,2);
        }
        $all = $renew_num_all+ $end_num_all;
        $per = $all==0?0:round($renew_num_all/$all*100,2);
        $total=[
            "id"  =>"全部",
            "name"=>"全部",
            "renew_num"=>$renew_num_all,
            "end_num" =>$end_num_all,
            "all"     =>$all,
            "per"     =>$per
        ];
        array_unshift($ass,$total);
        return $this->pageView(__METHOD__,null,[
            "list"  =>$ass
        ]);


        
        $str = json_encode( $ass);
        $task->t_teacher_info->field_update_list(240314,[
            "prize" => $str
        ]);


        // $list= $this->t_teacher_lecture_appointment_info->get_id_list_by_adminid(513,1);
        // $i=0;
        // foreach($list as $item){
        //     if($i<2087){
        //         $tt = 955;
        //     }else{
        //         $tt =1000;
        //     }
        //     $this->t_teacher_lecture_appointment_info->field_update_list($item["id"],[
        //         "accept_adminid" =>$tt,
        //         "accept_time"  =>1513051920
        //     ]);
        //     $i++;
        // }
        // dd($list);
        // $type= $this->get_in_int_val("type",2);
        // $ret = $this->t_cr_week_month_info->get_all_info_by_type_and_time($type);
        // foreach($ret as $val){
        //     $end_time = $val["create_time"];
        //     if($type==1){
        //         $start_time = strtotime("-1 months",$end_time);
        //     }elseif($type==2){
        //         $start_time = $end_time-7*86400;
        //     }

        //     $lesson_plan    = $this->t_lesson_info->get_total_lesson($start_time,$end_time); //实际有效课时/排课量         
        //     $arr=[];
        //     $arr['lesson_plan']    = $lesson_plan['total_plan']; //计划排课数量
        //     $arr['student_arrive'] = $lesson_plan['student_arrive']; //学生有效课程数量 
        //     if($arr['lesson_plan']){
        //         $arr['student_arrive_per'] = round(100*$arr['student_arrive']/$arr['lesson_plan'],2); //B10-学生到课率
        //     }else{
        //         $arr['student_arrive_per'] = 0;
        //     }       
        //     $insert_data = [          
        //         "student_arrive"          => $arr['student_arrive'],   //学生到课数量
        //         "lesson_plan"             => $arr['lesson_plan'],      //排课数量
        //         "student_arrive_per"      => intval($arr['student_arrive_per']*100),//B10-学生到课率         
        //     ];
        //     $this->t_cr_week_month_info->field_update_list($val["id"],$insert_data);


        // }
        // dd($ret);

        $list=[];
        $start_time = strtotime("2016-12-01");
        for($i=1;$i<=12;$i++){
            $first = strtotime(date("Y-m-01",strtotime("+".$i." months", $start_time)));
            // $next = strtotime(date("Y-m-01",strtotime("+1 months", $first)));
            $month = date("Y-m-d",$first);
            /* $order_money_info = $this->t_order_info->get_order_lesson_money_info($first,$next);
               $order_money_month = $this->t_order_info->get_order_lesson_money_use_info($first,$next);
               $list[$month]["stu_num"] = @$order_money_info["stu_num"];
               $list[$month]["all_price"] = @$order_money_info["all_price"];
               $list[$month]["lesson_count_all"] = @$order_money_info["lesson_count_all"];
               foreach($order_money_month as $val){
               $list[$month][$val["time"]]=($val["all_price"]/100)."/".($val["lesson_count_all"]/100);
               }*/
            $list[$month]["time"] = date("Y年m月",$first);
            $list[$month]["start"] = $first;


        }

        // $start = $this->get_in_str_val("start","2017-01-01");
        // $end = $this->get_in_str_val("end","2017-02-01");
        // $start_time = strtotime($start);
        // $end_time = strtotime($end);
     

        // $list =  $this->t_teacher_info->get_all_train_throuth_teacher_list($start_time,$end_time);
        // foreach($list as &$item){
        //     E\Eidentity::set_item_value_str($item);
        // }

        // $this->switch_tongji_database();
        // $start_time = time()-5*86400;
        // $end_time = time();
        // $list = $this->t_lesson_info_b3->get_tea_info_by_subject($start_time,$end_time);

        // foreach($list as &$val){
        //     $subject = $val["subject"];
        //     $grade = $val["grade"];
        //     if($grade==1){
        //         $val["grade_str"]="小学";
        //     }elseif($grade==2){
        //         $val["grade_str"]="初中";
        //     }else{
        //         $val["grade_str"]="高中";
        //     }
        //     E\Esubject::set_item_value_str($val,"subject");
        //     $val["num"]=0;
            
        // }
       
        
        //  dd($list);
        // // $list = $this->t_teacher_info->get_teacher_lesson_info_by_money_type($start_time,$end_time);
        // $list = $this->t_teacher_info->get_data_to_teacher_flow(0,0,1);

        // foreach($list as &$item){           
        //     if($item["simul_test_lesson_pass_time"]>0){
        //         $item["time_str"]=date("Y-m-d H:i",$item["simul_test_lesson_pass_time"]);           
        //     }else{
        //         $item["time_str"]=date("Y-m-d H:i",$item["train_through_new_time"]);           
        //     }
        //     E\Esubject::set_item_value_str($item,"subject");

        // }
        // $list=[1];
        return $this->pageView(__METHOD__,null,[
            "list"  =>$list
        ]);

        // $first_month = strtotime("2016-01-01");
        // // $end_month = strtotime(date("Y-m-01",time()));
        // // $next_month = strtotime(date("Y-m-01",strtotime("+1 months", $first_month)));
        // $num = (date("Y",time())-2016)*12+date("m",time())-1+1;

        // // $order_money_info = $this->t_order_info->get_order_lesson_money_info($first_month,$next_month);
        // //  $order_money_info = $this->t_order_info->get_order_lesson_money_use_info($first_month,$next_month);
        // $list=[];
        // for($i=1;$i<=$num;$i++){
        //     $first = strtotime(date("Y-m-01",strtotime("+".($i-1)." months", $first_month)));
        //     $next = strtotime(date("Y-m-01",strtotime("+1 months", $first)));
        //     $month = date("Y-m-d",$first);
        //     /* $order_money_info = $this->t_order_info->get_order_lesson_money_info($first,$next);
        //        $order_money_month = $this->t_order_info->get_order_lesson_money_use_info($first,$next);
        //        $list[$month]["stu_num"] = @$order_money_info["stu_num"];
        //        $list[$month]["all_price"] = @$order_money_info["all_price"];
        //        $list[$month]["lesson_count_all"] = @$order_money_info["lesson_count_all"];
        //        foreach($order_money_month as $val){
        //        $list[$month][$val["time"]]=($val["all_price"]/100)."/".($val["lesson_count_all"]/100);
        //        }*/
        //     $list[$month]["month"] = date("Y年m月",$first);
        //     $list[$month]["month_start"] = $first;


        // }

        // return $this->pageView(__METHOD__,null,[
        //     "list"  =>$list ,
        //     "num"  =>count($list)
        // ]);


        // $start_time = strtotime("2017-10-01");
        // $end_time = strtotime("2017-11-01");
        // $grade = $this->get_in_int_val("grade",1);
        // $list = $this->t_lesson_info_b3->get_test_lesson_teacher_list($start_time,$end_time,$grade);
        // $list = $this->t_teacher_info->get_part_remarks(240314);
        // $arr= explode(",",$list);
        // $ret_info=[];
        // foreach($arr as  $val){
        //     $ret_info[]=["phone"=>$val];
        // }
        //$list = $this->t_teacher_info->get_teacher_lesson_info_by_money_type($start_time,$end_time);
        // $list = $this->t_teacher_info->get_teacher_openid_list_new();
        //$list["list"][]=["teacherid"=>240314,"realname"=>"hahah","wx_openid"=>1111];
        // dd($list);

        $arr=[];
        for($i=1;$i<=11;$i++){

            $time =strtotime("2016-12-01");
            $start_time=strtotime("+".$i." month",$time);
            $end_time = strtotime("+".($i+1)." month",$time);
            $date= date("m",$start_time);


            // $list = $this->t_lesson_info_b3->get_teacher_list_by_time_new($start_time,$end_time);
            // $lesson_count=0;$tea_arr=[];
            // foreach($list as $val){
            //     $lesson_count +=$val["lesson_total"];
            //     $tea_arr[$val["teacherid"]]=$val["teacherid"];
                
            // }
            // $tea_num = count($tea_arr);

            // $cc_num=$cc_order=$cr_num=$cr_order=0;
            // $cc_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,100,$tea_arr,2);
            // foreach($cc_list as $val){
            //     $cc_num +=$val["person_num"];
            //     $cc_order +=$val["have_order"];
            // }
            // $cc_per= $cc_num>0?round($cc_order/$cc_num*100,2):0;
            // $cr_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,100,$tea_arr,1);
            
            // foreach($cr_list as $val){
            //     $cr_num +=$val["person_num"];
            //     $cr_order +=$val["have_order"];
            // }
            // $cr_per= $cr_num>0?round($cr_order/$cr_num*100,2):0;


            $arr[$date]=[
                "start_time"=>$start_time,
                // "tea_num" =>$tea_num,
                // "lesson_count"=>$lesson_count,
                // "cc_per"=>$cc_per,
                // "cr_per"=>$cr_per
            ];
            
        }
        
        //  foreach($list["list"] as $k=>&$item){
        //      /* if($item['grade_start']>0){
        //          $item['grade_ex']     = E\Egrade_range::get_desc($item['grade_start'])
        //              ."-".E\Egrade_range::get_desc($item['grade_end']);
        //      }else{
        //          $item['grade_ex']     = E\Egrade_part_ex::get_desc($item['grade_part_ex']);
        //      }
        //      $item['subject_ex']   = E\Esubject::get_desc($item['subject']);*/
        //      if($item["teacher_money_type"]==6){
        //          $item["teacher_money_type_str"] = "第四版规则";
        //      }else{
        //          $item["teacher_money_type_str"] = "平台合作";
        //      }

        //  }
        return $this->pageView(__METHOD__,null,[
            "list"  =>$list
        ]);

        // return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));
        //  foreach($ret_info["list"] as &$item){
        /* if($item["train_through_new"]==1){
           $item["train_through_new_str"]="已入职";
           }else{
           $item["train_through_new_str"]="未入职";
           }
           if($item["train_through_new_time"]>0){*/
        //$item["train_through_new_time_str"]=date("Y-m-d H:i",$item["train_through_new_time"]);
        /* }else{
           $item["train_through_new_time_str"]="无";
           }
           E\Eidentity::set_item_value_str($item,"teacher_type");
           if($item['grade_start']>0){
           $item['grade_ex']     = E\Egrade_range::get_desc($item['grade_start'])
           ."-".E\Egrade_range::get_desc($item['grade_end']);
           $item['subject_ex']   = E\Esubject::get_desc($item['subject_ex']);
           }elseif(is_numeric($item['grade_ex'])){
           $item['grade_ex']     = E\Egrade_part_ex::get_desc($item['grade_ex']);
           }*/
        //  E\Eteacher_type::set_item_value_str($item,"teacher_type");
        // E\Eboolean::set_item_value_str($item,"need_test_lesson_flag");
        // E\Egender::set_item_value_str($item,"gender");
        /* E\Esubject::set_item_value_str($item,"subject");
           E\Elevel::set_item_value_str($item,"level");
           // E\Esubject::set_item_value_str($item,"second_subject");
           // E\Esubject::set_item_value_str($item,"third_subject");
           E\Eidentity::set_item_value_str($item);
           //E\Elevel::set_item_value_str($item,"level");
           E\Eteacher_money_type::set_item_value_str($item);
           // E\Eteacher_ref_type::set_item_value_str($item); //是否全职

           E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");

           E\Egrade_range::set_item_value_str($item,"grade_start");
           E\Egrade_range::set_item_value_str($item,"grade_end");*/



        // }
        // return $this->pageView(__METHOD__,$ret_info);

    }


    public function test_ws() {
        return $this->pageView(__METHOD__,[]);
    }

    public function test_hha(){
        // $url = \App\Helper\Config::get_monitor_new_url() .":8808/pay_notify?userid=10001&sub_orderid=88";
        // dd($url);
        file_get_contents("http://self.admin.leo1v1.com:8808/pay_notify?userid=10001&sub_orderid=88");

    }

    public function add_record(){
        $teacherid = $this->get_in_int_val("teacherid");
        $list = $this->t_lesson_info_b3->get_lesson_info_by_teacherid_test($teacherid);
        $i=1;
        foreach($list as $val){
            $this->t_teacher_record_list->row_insert([
                "teacherid"      => $val["teacherid"],
                "type"           => 1,
                "train_lessonid" => $val["lessonid"],
                "lesson_time"    => $val["lesson_start"],
                "lesson_style"   => $i,
                "add_time"       => time()+$i*100,
                "userid"         => $val["userid"]
            ]);
            $i++;
 
        }
        return  $this->output_succ();


    }

    public function add_record2(){
        $subject = $this->get_in_int_val("subject");
        $grade = $this->get_in_int_val("grade");
        $phone = $this->get_in_str_val("phone");
        $name = $this->get_in_str_val("name");      
     
        $this->t_teacher_lecture_info->row_insert([
            "phone"      => $phone,
            "nick"           => $name,
            "add_time" => time(),
            "subject"    => $subject,
            "grade"   => $grade,
            "is_test_flag"=>1
        ]);
           
        return  $this->output_succ();


    }

    public function test_sms(){
        \App\Helper\Net::
        send_sms_taobao(13661596957,0, 10671029,[
            "code"  => 1111,
            "index" => 3,
        ]);

    }

    //百度有钱花接口(移动端)
    public function send_baidu_money_charge_move_terminal(){       
        $orderid = $this->get_in_int_val("orderid",17820);


        //期待贷款额度(分单位)
        $money = $this->t_child_order_info->get_price($orderid);

        //分期期数
        $period = $this->t_child_order_info->get_period_num($orderid);
        //成交价格
        $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
        $dealmoney = $this->t_order_info->get_price($parent_orderid);
        //订单id
        $orderNo = $orderid.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

        $url = 'https://umoney.umoney.baidu.com/edu/openapi/post';
        // $url = 'http://rdtest.umoney.baidu.com/edu/openapi/post';

        $userid = $this->t_order_info->get_userid($parent_orderid);
        $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email,grade,parentid");
        $parent_name = $this->t_parent_info->get_nick($user_info["parentid"]);
        $competition_flag = $this->t_order_info->get_competition_flag($parent_orderid);
        $baidu_class_info = $this->t_parent_info->get_baidu_class_info($user_info["parentid"]);
        if($baidu_class_info){
            $class_list = json_decode($baidu_class_info,true);
        }else{
            $class_list = [];
        }
        $courseid="";
        if($competition_flag==1){
            $courseid_list=["SHLEOZ3101006","SHLEOZ3101007","SHLEOZ3101008","SHLEOZ3101009","SHLEOZ3101010"];
            foreach($courseid_list as $v){
                if(isset($class_list[4])){
                    $cl_list = $class_list[4];
                    $i=0;
                    foreach($cl_list as $p_item){
                        if($p_item==$v){
                            $i=1;
                        }
                    }
                    if($i==0){
                        $courseid = $v;
                        break;
                    }
                }else{
                    $courseid = $v;
                    break;
                }
            }
            // $courseid = "SHLEOZ3101006";
            $coursename = "思维拓展在线课程";
        }elseif($user_info["grade"] >=100 && $user_info["grade"]<200){
            $courseid_list=["SHLEOZ3101001","SHLEOZ3101002","SHLEOZ3101003","SHLEOZ3101004","SHLEOZ3101005"];
            foreach($courseid_list as $v){
                if(isset($class_list[1])){
                    $cl_list = $class_list[1];
                    $i=0;
                    foreach($cl_list as $p_item){
                        if($p_item==$v){
                            $i=1;
                        }
                    }
                    if($i==0){
                        $courseid = $v;
                        break;
                    }
                }else{
                    $courseid = $v;
                    break;
                }
            }

            //$courseid = "SHLEOZ3101001";
            $coursename = "小学在线课程";
        }elseif($user_info["grade"] >=200 && $user_info["grade"]<300){
            $courseid_list=["SHLEOZ3101011","SHLEOZ3101012","SHLEOZ3101013","SHLEOZ3101014","SHLEOZ3101015"];
            foreach($courseid_list as $v){
                if(isset($class_list[2])){
                    $cl_list = $class_list[2];
                    $i=0;
                    foreach($cl_list as $p_item){
                        if($p_item==$v){
                            $i=1;
                        }
                    }
                    if($i==0){
                        $courseid = $v;
                        break;
                    }
                }else{
                    $courseid = $v;
                    break;
                }
            }

            //  $courseid = "SHLEOZ3101012";
            $coursename = "初中在线课程";
        }elseif($user_info["grade"] >=300 && $user_info["grade"]<400){
            $courseid_list=["SHLEOZ3101016","SHLEOZ3101017","SHLEOZ3101018","SHLEOZ3101019","SHLEOZ3101020"];
            foreach($courseid_list as $v){
                if(isset($class_list[3])){
                    $cl_list = $class_list[3];
                    $i=0;
                    foreach($cl_list as $p_item){
                        if($p_item==$v){
                            $i=1;
                        }
                    }
                    if($i==0){
                        $courseid = $v;
                        break;
                    }
                }else{
                    $courseid = $v;
                    break;
                }
            }

            // $courseid = "SHLEOZ3101016";
            $coursename = "高中在线课程";
        }

        // if(empty($courseid)){
        //     return $this->output_err("您申请百度有钱花的次数已达上限");
        // }
        // $courseid = "HXSD0101003";
        // $coursename = "思维拓展在线课程";

        // RSA加密数据
        $endata = array(
            'username' => $parent_name,
            'mobile' => $user_info["phone"],
            'email' => $user_info["email"],
        );

        $rsaData = $this->enrsa($endata);


        $arrParams = array(
            'action' => 'sync_order_info',
            'tpl' => 'leoedu',// 分配的tpl
            'corpid' => 'leoedu',// 分配的corpid
            'orderid' => $orderNo,// 机构订单号
            'money' => $money,// 期望贷款额度（分单位）
            'dealmoney' => $dealmoney,// 成交价格（分单位）>= 期望额度+首付额度
            'period' => $period,// 期数
            'courseid' => $courseid,// 课程id（会分配）
            'coursename' => $coursename,// 课程名称
            'oauthid' => $userid,// 用户id 机构方提供
            'addrtype' =>1,
            'data' => $rsaData,
        );

        $strSecretKey = '9v4DvTxOz3';// 分配的key
        $arrParams['sign'] = $this->createBaseSign($arrParams, $strSecretKey);


        // 发送请求post(form)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($ret, true);
        // dd($result);

        // print_r($result);


        //返回信息成功后处理
        if($result["status"]==0){
            $this->t_orderid_orderno_list->row_insert([
                "order_no"  =>$orderNo,
                "orderid"   =>$orderid,
                "order_type"=>1,
                "parent_orderid"=>$parent_orderid,
                "courseid"  =>$courseid
            ]);
        }

        return outputjson_success( ["result"=>$result] );
    }
        /**
     * @param $data
     * @return string
     * rsa 加密(百度有钱花)
     */
    public function enrsa($data){
        $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';
        $pu_key = openssl_pkey_get_public($public_key);
        $str = json_encode($data);
        $encrypted = "";
        // 公钥加密  padding使用OPENSSL_PKCS1_PADDING这个
        if (openssl_public_encrypt($str, $encrypted, $pu_key, OPENSSL_PKCS1_PADDING)){
            $encrypted = base64_encode($encrypted);
        }
        return $encrypted;
    }


    /**
     * @param $param
     * @param string $strSecretKey
     * @return bool|string
     * 生成签名(百度有钱花)
     */
    public function createBaseSign($param, $strSecretKey){
        if (!is_array($param) || empty($param)){
            return false;
        }
        ksort($param);
        $concatStr = '';
        foreach ($param as $k=>$v) {
            $concatStr .= $k.'='.$v.'&';
        }
        $concatStr .= 'key='.$strSecretKey;
        return strtoupper(md5($concatStr));
    }

    // 兴业银行微信扫码支付
    public function get_xingye_wx_url(){
        ini_set('date.timezone','Asia/Shanghai');
        require_once  app_path("Libs/WxpayAPI/lib/init.php");
        // $input = new WxPayUnifiedOrder();
        $input= new \WxpayAPI\WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxpayAPI\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");

        if($input->GetTrade_type() == "NATIVE")
		{
			$result = \WxpayAPI\WxPayApi::unifiedOrder($input);
		}

        $url2 = $result["code_url"];
        dd($url2);



    }



   
}

