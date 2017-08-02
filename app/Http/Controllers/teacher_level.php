<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class teacher_level extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_teacher_level_quarter_info(){
        $this->switch_tongji_database();
        $sum_field_list = [
            "total_score"
        ];
        $order_field_arr = array_merge(["realname"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr,"realname desc ");

        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");

        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",1);
        $list = $this->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        $tea_list=[];
        foreach($list as $val){
            $tea_list[] = $val["teacherid"];
        }
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_teacher_info->get_teacher_level_info($page_info,$tea_list,$start_time);
        $tea_arr=[];
        foreach($ret_info["list"] as $val){
            $tea_arr[]=$val["teacherid"];
        }
        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        $tea_refund_info =$this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        foreach($ret_info["list"] as &$item){
            E\Elevel::set_item_value_str($item,"level");
            $item["level_after"] = $item["level"]+1;
            E\Elevel::set_item_value_str($item,"level_after");
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($item);

            $teacherid = $item["teacherid"];
            $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
            $item["lesson_count_score"] = $this->get_score_by_lesson_count($item["lesson_count"]);
            $item["cc_test_num"] = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            $item["cc_order_num"] = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_per"] = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
            $item["cc_order_score"] = $this->get_cc_order_score($item["cc_order_num"],$item["cc_order_per"]);
            $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
            $item["other_order_score"] = $this->get_other_order_score($item["other_order_num"],$item["other_order_per"]);
            $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"] = !empty($item["record_num"])?ceil($item["record_score_avg"]*0.2):12;
            $item["is_refund"] = (isset($tea_refund_info[$teacherid]) && $tea_refund_info[$teacherid]>0)?1:0;
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
            $item["total_score"] = $item["lesson_count_score"]+$item["cc_order_score"]+ $item["other_order_score"]+$item["record_final_score"];
            $item["hand_flag"]=0;
            
        }
        $hand_info = $this->t_teacher_advance_list->get_hand_add_list($start_time,1);
        foreach($hand_info as &$h){
            $h["realname"] = $this->t_teacher_info->get_realname($h["teacherid"]);
            $h["level"]  = $this->t_teacher_info->get_level($h["teacherid"]);
            $h["level_str"] =E\Elevel::get_desc($h["level"]);
            $h["level_after_str"] =E\Elevel::get_desc($h["level_after"]);
            $h["is_refund_str"] = $h["is_refund"]==1?"<font color='red'>有</font>":"无";
            \App\Helper\Utils::unixtime2date_for_item($h,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($h,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($h);

            array_unshift($ret_info["list"],$h);
        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        $erick =[];
        $erick["teacherid"]=50158;
        $erick["realname"]="刘辉";
        $erick["level"]  = $this->t_teacher_info->get_level($erick["teacherid"]);
        $erick["level_after"] =  $erick["level"]+1;
        $erick["level_str"] =E\Elevel::get_desc($erick["level"]);
        $erick["level_after_str"] =E\Elevel::get_desc($erick["level_after"]);
        $erick["lesson_count"] =$erick["lesson_count_score"]=$erick["cc_test_num"]=$erick["cc_order_num"]= $erick["cc_order_per"]= $erick["cc_order_score"]=$erick["other_test_num"] =$erick["other_order_num"]= $erick["other_order_per"]=$erick["other_order_score"]= $erick["record_num"]= $erick["record_score"]= $erick["record_score_avg"]= $erick["record_final_score"]= $erick["total_score"]=0;
        $erick["is_refund_str"]="无";
        $erick["is_refund"]=0;
        $erick["hand_flag"]=0;

        array_unshift($ret_info["list"],$erick);
        return $this->pageView(__METHOD__,$ret_info);

        //dd($ret_info);

    }

    public function add_teacher_advance_info(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $teacherid = $this->get_in_int_val("teacherid");
        $total_score = $this->get_in_int_val("total_score");
        $level = $this->t_teacher_info->get_level($teacherid);
        $level_after = $level+1;
        $this->t_teacher_advance_list->row_insert([
            "start_time" =>$start_time,
            "teacherid"  =>$teacherid,
            "level_before" =>$level,
            "level_after"  =>$level_after,
            "total_score"  =>$total_score,
            "hand_flag"    =>1
        ]);
        return $this->output_succ();

    }
    public function get_other_order_score($num,$per){
        if($num<=0){
            return 5;
        }elseif($per <60){
            return 4;
        }elseif($per >=60 && $per <70){
            return 5;
        }elseif($per >=70 && $per <80){
            return 6;
        }elseif($per >=80 && $per <90){
            return 7;
        }elseif($per >=90 ){
            return 8;
        }


    }

    public function get_cc_order_score($num,$per){
        if($num<=0){
            return 7;
        }elseif($per <15){
            return 6;
        }elseif($per >=15 && $per <20){
            return 7;
        }elseif($per >=20 && $per <25){
            return 8;
        }elseif($per >=25 && $per <30){
            return 9;
        }elseif($per >=30 && $per <35){
            return 10;
        }elseif($per >=35 && $per <40){
            return 11;
        }elseif($per >=40){
            return 12;
        }


    }
    
    public function get_score_by_lesson_count($lesson_count){
        if($lesson_count >=60 && $lesson_count <70){
            return 51;
        }elseif($lesson_count >=70 && $lesson_count <80){
            return 52;
        }elseif($lesson_count >=80 && $lesson_count <90){
            return 53;
        }elseif($lesson_count >=90 && $lesson_count <100){
            return 54;
        }elseif($lesson_count >=100 && $lesson_count <110){
            return 55;
        }elseif($lesson_count >=110 && $lesson_count <120){
            return 56;
        }elseif($lesson_count >=120 && $lesson_count <130){
            return 57;
        }elseif($lesson_count >=130 && $lesson_count <140){
            return 58;
        }elseif($lesson_count >=140 && $lesson_count <150){
            return 59;
        }elseif($lesson_count>=150){
            return 60;
        }else{
            return 0;
        }


    }

    public function update_teacher_advance_info_hand(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $realname = $this->get_in_str_val("realname");
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        // $realname ="胡玉梅";
        $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
        $lesson_total = $this->t_teacher_info->get_teacher_lesson_total_realname($teacher_money_type,$start_time,$end_time,$realname);
        $tea_arr=[];$lesson_count=0;
        foreach($lesson_total as $val){
            $tea_arr[]=$val["teacherid"];
            $lesson_count +=$val["lesson_count"];
        }
        $lesson_count = round($lesson_count/300,1);
        $lesson_count_score = $this->get_score_by_lesson_count($lesson_count);

        $test_person_num= $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        

        $kk_test_person_num= $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num= $this->t_lesson_info->get_change_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);

        $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        $tea_refund_info =$this->get_tea_refund_info($start_time,$end_time,$tea_arr);
        $cc_test_num=$cc_order_num=0;
        foreach($test_person_num as $val){
            $cc_test_num +=$val["person_num"];
            $cc_order_num +=$val["have_order"];
        }
        
        $cc_order_per= !empty($cc_test_num)?round($cc_order_num/$cc_test_num*100,2):0;
        $cc_order_score = $this->get_cc_order_score($cc_order_num,$cc_order_per);

        $other_test_num = $other_order_num=0;
        foreach($kk_test_person_num as $val){
            $other_test_num +=$val["kk_num"];
            $other_order_num +=$val["kk_order"];

        }
        foreach($change_test_person_num as $val){
            $other_test_num +=$val["change_num"];
            $other_order_num +=$val["change_order"];
        }
      
        $other_order_per = !empty($other_test_num)?round($other_order_num/$other_test_num*100,2):0;
        $other_order_score = $this->get_other_order_score($other_order_num,$other_order_per);

        $record_num = $record_score=0;
        foreach($teacher_record_score as $val){
            $record_num +=$val["num"];
            $record_score +=$val["score"];
        }
        $record_score_avg = !empty($record_num)?round($record_score/$record_num,1):0;
        $record_final_score = !empty($record_num)?ceil($record_score_avg*0.2):12;
        $is_refund = 0;
        if(!empty($tea_refund_info)){
            $is_refund=1;
        }
        $total_score = $lesson_count_score+$cc_order_score+ $other_order_score+$record_final_score;
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "lesson_count"=>$lesson_count,
            "lesson_count_score"=>$lesson_count_score,
            "cc_test_num"=>$cc_test_num,
            "cc_order_num" =>$cc_order_num,
            "cc_order_per" =>$cc_order_per,
            "cc_order_score" =>$cc_order_score,
            "other_test_num"=>$other_test_num,
            "other_order_num" =>$other_order_num,
            "other_order_per" =>$other_order_per,
            "other_order_score" =>$other_order_score,
            "record_final_score"=>$record_final_score,
            "record_score_avg" =>$record_score_avg,
            "record_num"     =>$record_num,
            "is_refund"      =>$is_refund,
            "total_score"    =>$total_score
        ]);
        return $this->output_succ();
                   
    }
    
    public function set_teacher_advance_require(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $level_before = $this->get_in_int_val("level_before");
        $level_after = $this->get_in_int_val("level_after");
        $lesson_count = $this->get_in_int_val("lesson_count");
        $lesson_count_score  = $this->get_in_int_val("lesson_count_score");
        $cc_test_num = $this->get_in_int_val("cc_test_num");
        $cc_order_num = $this->get_in_int_val("cc_order_num");
        $cc_order_per = $this->get_in_str_val("cc_order_per");
        $cc_order_score = $this->get_in_int_val("cc_order_score");
        $other_test_num = $this->get_in_int_val("other_test_num");
        $other_order_num = $this->get_in_int_val("other_order_num");
        $other_order_per = $this->get_in_str_val("other_order_per");
        $other_order_score = $this->get_in_int_val("other_order_score");
        $record_num = $this->get_in_int_val("record_num");
        $record_score_avg = $this->get_in_str_val("record_score_avg");
        $record_final_score  = $this->get_in_int_val("record_final_score");
        $is_refund  = $this->get_in_int_val("is_refund");
        $total_score = $this->get_in_int_val("total_score");
        $hand_flag = $this->get_in_int_val("hand_flag");
        $golden_flag = $this->get_in_int_val("golden_flag");
        if($golden_flag==1){
            $level_after=3;
        }
        if($hand_flag==0){
            $this->t_teacher_advance_list->row_insert([
                "start_time" =>$start_time,
                "teacherid"  =>$teacherid,
                "level_before"=>$level_before,
                "level_after" =>$level_after,
                "lesson_count"=>$lesson_count,
                "lesson_count_score"=>$lesson_count_score,
                "cc_test_num"=>$cc_test_num,
                "cc_order_num" =>$cc_order_num,
                "cc_order_per" =>$cc_order_per,
                "cc_order_score" =>$cc_order_score,
                "other_test_num"=>$other_test_num,
                "other_order_num" =>$other_order_num,
                "other_order_per" =>$other_order_per,
                "other_order_score" =>$other_order_score,
                "record_final_score"=>$record_final_score,
                "record_score_avg" =>$record_score_avg,
                "record_num"     =>$record_num,
                "is_refund"      =>$is_refund,
                "total_score"    =>$total_score,
                "require_time"   =>time(),
                "require_adminid"=>$this->get_account_id()
            ]);
        }else{
            $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                "require_time"   =>time(),
                "require_adminid"=>$this->get_account_id()
            ]);
        }
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"兼职老师晋升申请","兼职老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.yb1v1.com/teacher_level/get_teacher_advance_info?start_time=".$start_time."&teacherid=".$teacherid);
        $this->t_manager_info->send_wx_todo_msg_by_adminid (72,"兼职老师晋升申请","兼职老师晋升申请待处理",$realname."老师的晋升申请已提交,请尽快审核","http://admin.yb1v1.com/teacher_level/get_teacher_advance_info?start_time=".$start_time."&teacherid=".$teacherid);


        return $this->output_succ();
    }

    public function set_teacher_advance_require_master(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        $level_after = $this->get_in_int_val("level_after");
        $accept_flag = $this->get_in_int_val("accept_flag");
        $accept_info = trim($this->get_in_str_val("accept_info"));
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "accept_flag"  =>$accept_flag,
            "accept_time"  =>time(),
            "accept_adminid" =>$this->get_account_id(),
            "accept_info"    =>$accept_info
        ]);
        $realname  = $this->t_teacher_info->get_realname($teacherid);
        if($accept_flag==1){
            $old_level = $this->t_teacher_info->get_level($teacherid);
            $this->t_teacher_info->field_update_list($teacherid,["level"=>$level_after]);
            // $level_degree = E\Elevel::v2s($level_after);
            $info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,teacher_type,nick");
            $info["level"] = $level_after;
            $info["old_level"] = $old_level;
 
            $level_degree    = \App\Helper\Utils::get_teacher_level_str($info);

            $score = $this->t_teacher_advance_list->get_total_score($start_time,$teacherid);
            
            //已排課程工資等級更改
            $level_start = strtotime("2017-08-01");
            $teacher_money_type = $this->t_teacher_info->get_teacher_money_type($teacherid);
            $this->t_lesson_info->set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level_after,$level_start);

            
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
            $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜您获得了晋升";
                $data['keyword1'] = $realname;
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "晋升分数:".$score
                                  ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";
                $url = "http://admin.yb1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                // $url = "";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }

            //邮件推送
            $html = $this->teacher_level_up_html($info);
            $email = $this->t_teacher_info->get_email($teacherid);
            //  $email = "jack@leoedu.com";
            if($email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,"【理优1对1】老师晋升通知",$html
                ));

 
            }

           
            //微信通知教研
            $subject = $this->t_teacher_info->get_subject($teacherid);
            $master_adminid = $this->get_tea_adminid_by_subject($subject);
            $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($master_adminid);
            $jy_teacherid = $teacher_info["teacherid"];
            $wx_openid = $this->t_teacher_info->get_wx_openid($jy_teacherid);
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜".$realname."获得了晋升";
                $data['keyword1'] = $realname;
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                // $url = "";
                $url = "http://admin.yb1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }

 
        }elseif($accept_flag==2){
            //微信通知師資管理
            /**
             * 模板ID   : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
             * 标题课程 : 评估结果通知
             * {{first.DATA}}
             * 评估内容：{{keyword1.DATA}}
             * 评估结果：{{keyword2.DATA}}
             * 时间：{{keyword3.DATA}}
             * {{remark.DATA}}
             */

            
            $wx_openid = $this->t_teacher_info->get_wx_openid(130462);
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                $data['first']    = "晋升申请驳回";
                $data['keyword1'] = $realname."未能通过晋升申请";
                $data['keyword2'] = $accept_info;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }


            
 
        }
        return $this->output_succ();

    }
    public function get_teacher_advance_info(){
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        $start_time = $this->get_in_int_val("start_time",$start_time);
        $this->set_in_value("quarter_start",$start_time);
        $quarter_start = $this->get_in_int_val("quarter_start");
        $teacher_money_type       = $this->get_in_int_val("teacher_money_type",-1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $accept_flag       = $this->get_in_int_val("accept_flag",-1);

        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_teacher_advance_list->get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,$accept_flag);
        foreach($ret_info["list"] as &$item){
            E\Elevel::set_item_value_str($item,"level_before");
            E\Elevel::set_item_value_str($item,"level_after");
            \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            E\Eaccept_flag::set_item_value_str($item);
            $item["is_refund_str"] = $item["is_refund"]==1?"<font color='red'>有</font>":"无";
 
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_teacher_refund_detail_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $start_time = $this->get_in_int_val("start_time");
        // $teacherid = 109666;
        $tea_arr = [$teacherid];
        // $start_time = strtotime("2017-04-01");
        $end_time = strtotime(date("Y-m-01",$start_time+110*86400));
        $list = $this->t_order_refund->get_tea_refund_info_new($start_time,$end_time,$tea_arr);
        $arr =[];
        foreach($list as $val){
            $ss = $val["nick"]."-".$val["apply_time"];
            @$arr[$ss][$val["value"]]=$val["score"]; 
        }
        $data=[];
        foreach($arr as $k=>$item){
            $all=0;$ass=0;
            foreach($item as $kk=>$v){
                if($kk=="教学部"){
                    $ass = $v;
                }
                $all +=$v;
                
            }
            if($all>0 && $ass >0){
                @$data[$k]["per"]=round(100*$ass/$all,2);
                $info = explode("-",$k);
                $data[$k]["nick"] = @$info[0];
                $data[$k]["apply_time"] = @$info[1];
                $data[$k]["apply_time_str"]=!empty($data[$k]["apply_time"])?date("Y-m-d H:i:s",$data[$k]["apply_time"]):"";
            }
           

        }
        return $this->output_succ(["data"=>$data]);

                

    }


    public function get_teacher_test_first_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $teacherid=53289;
        $lesson_type=2;
        $data= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,$lesson_type,0);
        /*  $data["first"]["num"] = "第一次课";
        $data["five"] = $this->t_lesson_info_b2->get_lesson_row_info($teacherid,$lesson_type,4);
        $data["five"]["num"] = "第五次课";*/
        /* foreach($data as &$item){
             E\Esubject::set_item_value_str($item,"subject");
             $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
             $item["nick"] = $this->t_student_info->get_nick($item["userid"]);
             }*/
        if($empty($data)){
            
        }else{
            return $this->output_succ(["data"=>$data]);
        }
    }

    public function teacher_lesson_record_info(){
        $this->switch_tongji_database();
        $page_info = $this->get_in_page_info();
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $ret_info = $this->t_teacher_info->get_tea_have_test_lesson($page_info,$teacherid);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade_part_ex::set_item_value_str($item,"grade_part_ex");
            E\Egrade_range::set_item_value_str($item,"grade_start");
            E\Egrade_range::set_item_value_str($item,"grade_end");
  
        }
        return $this->pageView(__METHOD__,$ret_info);
    }





}