<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;
use \App\Enums as E;

class send_tea_record_by_email_week extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_tea_record_by_email_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "每周一下午6点发送课程质量反馈报告";

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
        $task       = new \App\Console\Tasks\TaskController();
        $start_time_ave = time()-30*86400;
        $res = $task->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num =0;$arr=0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }
        $day_num = ceil($arr/$num/86400);
        $start_time=time()-67*86400;
        $end_time = time()-$day_num*86400;
        $teacherid_list = $task->t_lesson_info->get_have_ten_test_lesson_teacher_list_new($start_time,-1,"",$end_time);
        $ret_info=[];
        $un_num =0;
        foreach($teacherid_list as $item){
            $teacherid = $item["teacherid"];
            $test_lesson_num = $item["suc_count"]>=20?20: $item["suc_count"];
            $ret = $task->t_lesson_info->get_test_lesson_order_info_by_teacherid($teacherid,$start_time,$end_time,$test_lesson_num);
            $subject_str = E\Esubject::get_desc($item["subject"]);
            $identity_str = E\Eidentity::get_desc($item["identity"]);
            $realname = $item["realname"];
            $interview_access = $item["interview_access"];
            $level_str =  E\Elevel::get_desc($item["level"]);
            $start = date("Y-m-d",$ret[9]["lesson_start"]);
            $end = date("m-d",$end_time);
            $day = ceil((time()-$item["create_time"])/86400);
            $ss=0;
            foreach($ret as $v){
                if($v["orderid"]>0){
                    $ss++;
                }
            }
            if(($ss/$test_lesson_num) < 0.05){
                $un_num++;
            }

        }

        $time=time()-7*86400;
        $freeze_num = $task->t_teacher_info->get_freeze_teacher_num($time);

        $end = date("m.d",time());
        $start = date("m.d",$time);
        $aa = "<!DOCTYPE html>  <html>  <head> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body> <table border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr><td >老师</td><td >科目</td><td >入职时间</td><td >反馈情况</td><td width=\"400px\">监课情况</td><td width=\"400px\">建议</td><td >监课人</td><td>教学打分</td></tr>";
        //$lend = time();
        $date_week_pre = \App\Helper\Utils::get_week_range(time()-7*86400,1);
        $lstart = strtotime(date("Y-m-d",$date_week_pre["sdate"] + 4*86400)." 19:00:00");
        $lend = strtotime(date("Y-m-d",$date_week_pre["edate"] + 2*86400)." 09:00:00");       
        $lessonid_list = $task->t_test_lesson_subject_sub_list->get_test_lessonid_list_by_set_time($lstart,$lend);
        $new_teacher = [];
        foreach($lessonid_list as $vvv){
            $teacherid = $vvv["teacherid"];
            $lessonid = $vvv["lessonid"];
            $ret = $task->t_lesson_info->check_teacher_have_test_lesson($teacherid,$lessonid,$lstart);
            if($ret != 1){
                if(!isset( $new_teacher[$teacherid])){
                    $new_teacher[$teacherid] = $teacherid;
                }
            }
        }
        $new_tea_num = count($new_teacher);
        $left_new= $new_teacher;
        $ret = $task->t_teacher_record_list->get_teacher_record_list_time_new($time);
        $new = 0;$all=0;
        $arr=[];
        foreach($ret as &$item){
            E\Esubject::set_item_value_str($item,"subject"); 
            $record_lesson_list = $item["record_lesson_list"];
            $teacherid = $item["teacherid"];
            if(in_array($teacherid,$new_teacher)){
                $item["fkqk"] = "新入职老师";
                $new++;
                unset($left_new[$teacherid]);
            }else{
                $item["fkqk"] = "非新入职老师";
            }
            $all++;
            foreach($item as $k=>$v){
                if(preg_match("/score/", $k) && $k != "record_score"){
                    @$arr[$k] +=$v;
                }
            }
            
            if(empty($record_lesson_list)){
                $str = "";
            }else{
                $lessonid=json_decode($record_lesson_list,true);
                $str = "";
                foreach($lessonid as $ss){
                    $ret = $task->t_lesson_info->get_lesson_info_stu($ss);
                    $lesson_start = date("Y.m.d H:i:s",$ret["lesson_start"]);
                    $str .= $lesson_start." ".$ret["nick"].";";
                }
                $str = trim($str,";")."\n";

            }
            

            $aa .= "<tr><td>".$item["nick"]."</td><td>".$item["subject_str"]."</td><td>".date("Y-m-d",$item["create_time"])."</td><td>".$item["fkqk"]."</td><td>".$str.$item["record_monitor_class"]."</td><td>".$item["record_info"]."</td><td>".$item["acc"]."</td><td>".$item["record_score"]."</td></tr>";
        }
        /* $trr = ["tea_process_design_score"=>10,"tea_method_score"=>5,"answer_question_cre_score"=>10,"courseware_flag_score"=>5,"lesson_preparation_content_score"=>10,"courseware_quality_score"=>10,"class_atm_score"=>10,"teacher_blackboard_writing_score"=>10,"tea_rhythm_score"=>10,"knw_point_score"=>5,"dif_point_score"=>5,"content_fam_degree_score"=>5,"language_performance_score"=>10,"tea_attitude_score"=>25,"tea_concentration_score"=>25,"tea_accident_score"=>50,"tea_operation_score"=>50,"class_abnormality_score"=>30,"tea_environment_score"=>20];
        $strr = ["tea_process_design_score"=>"教学过程设计","tea_method_score"=>"讲题方法思路","answer_question_cre_score"=>"题目解答","courseware_flag_score"=>"有无课件","lesson_preparation_content_score"=>"备课内容与试听需求匹配度","courseware_quality_score"=>"课件质量","class_atm_score"=>"课堂氛围","teacher_blackboard_writing_score"=>"板书书写","tea_rhythm_score"=>"课程节奏","knw_point_score"=>"知识点讲解","dif_point_score"=>"重难点把握","content_fam_degree_score"=>"课本内容熟悉程度","language_performance_score"=>"语言表达和组织能力","tea_attitude_score"=>"教学态度","tea_concentration_score"=>"教学专注度","tea_accident_score"=>"教学事故","tea_operation_score"=>"软件操作","class_abnormality_score"=>"课程异常情况处理","tea_environment_score"=>"周边环境"];
        foreach($trr as $k=>$v){
            $arr[$k] = $arr[$k]/$v;
        }
        asort($arr);
        $arr = array_slice($arr,0, 2);
        $res = [];$i=0;
        foreach($arr as $kk=>$vv){
            $res[$i] = $strr[$k].",平均得分".$vv."/".$trr[$k];
            $i++;
            }*/
        $bb = "</table></body></html>";
        $qq = $aa.$bb;
        $start_date = date("Y-m-d H:i:s",$time);
        $end_date = date("Y-m-d H:i:s",time());
        //  dd($qq);
        $email_arr = ["fly@leoedu.cn","leowang@leoedu.com","chenhongji@leoedu.com","xixi@leoedu.cn","michael@leoedu.cn","jim@leoedu.cn","Louis@leoedu.com","erick@leoedu.cn","nick@leoedu.cn","coco@leoedu.cn","melody@leoedu.cn","cocozhang@leoedu.cn","jw@leoedu.com","wander@leoedu.cn","Jack@leoedu.cn","atom@leoedu.com","gonghaotian@leoedu.com"];
         foreach($email_arr as $email){
             dispatch( new \App\Jobs\SendEmailNew(
                 $email,"教学质量反馈报告(".$start."-".$end.")","Dear all：<br>本周教学监课情况如下<br>1.本周超过10次以上未转化的老师共".$un_num."位,其中".$freeze_num."位已进行冻结排课操作<br>2.本周监课数量".$all."节,其中新入职老师监课数量".$new."节<br>简要情况见下表<br>".$qq."<br>详情请点击:<a href=\"http://admin.yb1v1.com/human_resource/teacher_record_detail_list_new/?teacherid=-1&subject=-1&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date."\" target=\"_blank\">(上周教学质量反馈报告)</a><br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优监课组</div><div>"
             ));
  
         }
        /*dispatch( new \App\Jobs\SendEmailNew(
            "erick@leoedu.cn","教学质量反馈报告(".$start."-".$end.")","Dear all：<br>本周教学监课情况如下<br>1.本周超过10次以上未转化的老师共".$un_num."位,其中".$freeze_num."位已进行冻结排课操作<br>2.本周监课数量".$all."节,其中新入职老师监课数量".$new."节<br>简要情况见下表<br>".$qq."<br>详情请点击:<a href=\"http://admin.yb1v1.com/human_resource/teacher_record_detail_list/?teacherid=-1&subject=-1&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date."\" target=\"_blank\">(上周教学质量反馈报告)</a><br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优监课组</div><div>"
            ));*/

        dispatch( new \App\Jobs\SendEmailNew(
            [0=>"Jack@leoedu.cn",1=>"429710405@qq.com",2=>"jhp0416@163.com"],"教学质量反馈报告(".$start."-".$end.")","Dear all：<br>本周教学监课情况如下<br>1.本周超过10次以上未转化的老师共".$un_num."位,其中".$freeze_num."位已进行冻结排课操作<br>2.本周监课数量".$all."节,其中新入职老师监课数量".$new."节<br>简要情况见下表<br>".$qq."<br>详情请点击:<a href=\"http://admin.yb1v1.com/human_resource/teacher_record_detail_list_new/?teacherid=-1&subject=-1&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date."\" target=\"_blank\">(上周教学质量反馈报告)</a><br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优监课组</div><div>"
        ));

        $admin_arr1=[349,72,448,74,303,188,60,325,329,379,478,310];
        // $admin_arr1=[349];
        foreach($admin_arr1 as $qq){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($qq,"理优监课组","教学质量反馈报告(".$start."-".$end.")","本周教学监课情况如下:
1.本周超过10次以上未转化的老师共".$un_num."位,其中".$freeze_num."位已进行冻结排课操作
2.本周监课数量".$all."节,其中新入职老师监课数量".$new."节","http://admin.yb1v1.com/human_resource/teacher_record_detail_list_new/?teacherid=-1&subject=-1&date_type=null&opt_date_type=0&start_time=".$start_date."&end_time=".$end_date);

        }
       
        $left_name="";$left_list=[];
        foreach($left_new as $vv){
            $tea_info= $task->t_teacher_info->field_get_list($vv,"subject,realname");
            $subject = $tea_info["subject"];
            @$left_list[$subject] .= $tea_info["realname"].",";
        }
        foreach($left_list as $k=>$tt){
            $subject_str = E\Esubject::get_desc($k);
            $tt = trim($tt,",");
            $left_name .= $subject_str."科目:".$tt.";";
        }
        $left_name =  trim($left_name,";");
        $left_num = count($left_new);
        $oo = $new_tea_num-$left_num;
        $admin_arr2=[349,72,448];
        //$admin_arr2=[349];
        foreach($admin_arr2 as $vv){
           
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($vv,"理优监课组","新老师反馈情况","上周新入职老师总共需反馈".$new_tea_num."位,总共反馈了".$oo."位,未反馈名单如下"
                                                                .":".$left_name,"");
        }
        $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"理优监课组","新老师反馈情况","上周新入职老师总共需反馈".$new_tea_num."位,总共反馈了".$oo."位,未反馈名单如下"
                                                            .":".$left_name,"");
        $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","新老师反馈情况","上周新入职老师总共需反馈".$new_tea_num."位,总共反馈了".$oo."位,未反馈名单如下"
                                                            .":".$left_name,"");

    }
}
