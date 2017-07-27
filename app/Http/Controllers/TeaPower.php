<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;

trait  TeaPower {
    public function research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count=0,$lesson_start=0,$lesson_type=-1){
        $admin_info   = $this->t_manager_info->get_account_role_by_teacherid($teacherid);

        $account_role = $admin_info["account_role"];
        $date_week    = \App\Helper\Utils::get_week_range($lesson_start,1);
        $day          = intval(($lesson_start-$date_week["sdate"])/86400)+1;

        $normal_stu_num    = $this->t_course_order->get_tea_stu_num($teacherid);
        $normal_stu_list   = $this->t_course_order->get_tea_userid_detail_list($teacherid);
        $day_start         = strtotime(date("Y-m-d",$lesson_start));
        $day_end           = strtotime(date("Y-m-d",$lesson_start+86400));
        $lesson_count_ex   = ($this->t_lesson_info->get_lesson_count_all_by_teacherid_new($teacherid,$day_start,$day_end))/100;
        $lesson_count_week = ($this->t_lesson_info->get_lesson_count_all_by_teacherid_new($teacherid,$date_week["sdate"],$date_week["edate"]))/100;
        $saturday_lesson_num = $this->t_teacher_info->get_saturday_lesson_num($teacherid);
        $week_lesson_count   = $this->t_teacher_info->get_week_lesson_count($teacherid);
        $week_left = $saturday_lesson_num-$lesson_count_ex;
        $h         = date("H",$lesson_start);
        $tea_arr   = [107884,53289,78733,59896,55161,164508,190394,176999,240348];
        $day_arr   = ["2017-04-02","2017-04-03","2017-04-04","2017-05-01","2017-05-29","2017-05-30","2017-05-28"];
        $lesson_start_date = date("Y-m-d",$lesson_start);
        if($account_role==4 && !in_array($lesson_start_date,$day_arr)){
            if($admin_info["uid"] != 325){
                /*if($lesson_type==2){
                   $month_start = strtotime(date("Y-m-01",$lesson_start));
                   $month_end = strtotime(date("Y-m-01",$month_start+35*86400));
                   $test_num = $this->t_lesson_info->get_test_lesson_num_by_teacherid($month_start,$month_end,$teacherid,$normal_stu_list);
                   if(($normal_stu_num+$test_num)>=7){
                        return $this->output_err("该教研老师学生达上限,不能接试听课!");
                    }
                    }*/
                if(($lesson_count_week+$lesson_count)>$week_lesson_count){
                    return $this->output_err(
                        "教研老师每周只能带".$week_lesson_count."课时,该老师该周已有".$lesson_count_week."课时!"
                    );
                }

                if($day==6 && !in_array($teacherid,$tea_arr)){
                    if(!empty($lesson_count)){
                        if($week_left < $lesson_count){
                            return $this->output_err("该教研老师周六剩余可排课时为".$week_left);
                        }
                    }
                }elseif($day>=2 && $day <=5  && !in_array($teacherid,$tea_arr)){
                    if(!empty($lesson_start)){
                        if($h <18){
                            return $this->output_err("教研老师周二至周五只能18点以后排课");
                        }
                    }
                }
            }
        }elseif($account_role==5 && !in_array($teacherid,$tea_arr)){
            $create_time = $this->t_teacher_info->field_get_value($teacherid,"train_through_new_time");
            if(!empty($lesson_start)){
                if(($create_time+14*86400)>$lesson_start){
                    $is_freeze = $this->t_teacher_info->get_is_freeze($teacherid);
                    if($is_freeze==1){
                        return $this->output_err("该老师目前冻结排课");
                    }
                    $test_lesson_num = $this->t_lesson_info->get_limit_type_teacher_lesson_num(
                        $teacherid,$date_week["sdate"],$date_week["edate"]
                    );
                    $limit_type = $this->t_teacher_info->get_limit_plan_lesson_type($teacherid);
                    if($limit_type !=0 && $limit_type<=$test_lesson_num){
                        return $this->output_err("该全职老师新入职两周内一周限课".$limit_type."节,当前已排".$test_lesson_num."节");
                    }
                }
            }
        }
    }

    public function add_teacher_label($sshd_good,$sshd_bad,$ktfw_good,$ktfw_bad,$skgf_good,$skgf_bad,$jsfg_good,$jsfg_bad,$teacherid,$label_origin,$lessonid=0,$subject=0,$lessonid_list=""){
        $sshd_good=\App\Helper\Utils::json_decode_as_array($sshd_good, true);
        $sshd_bad=\App\Helper\Utils::json_decode_as_array($sshd_bad, true);
        $sshd =  array_merge($sshd_good, $sshd_bad);
        $sshd_str = json_encode($sshd);
        $ktfw_good=\App\Helper\Utils::json_decode_as_array($ktfw_good, true);
        $ktfw_bad=\App\Helper\Utils::json_decode_as_array($ktfw_bad, true);
        $ktfw =  array_merge($ktfw_good, $ktfw_bad);
        $ktfw_str = json_encode($ktfw);
        $skgf_good=\App\Helper\Utils::json_decode_as_array($skgf_good, true);
        $skgf_bad=\App\Helper\Utils::json_decode_as_array($skgf_bad, true);
        $skgf =  array_merge($skgf_good, $skgf_bad);
        $skgf_str = json_encode($skgf);
        $jsfg_good=\App\Helper\Utils::json_decode_as_array($jsfg_good, true);
        $jsfg_bad=\App\Helper\Utils::json_decode_as_array($jsfg_bad, true);
        $jsfg =  array_merge($jsfg_good, $jsfg_bad);
        $jsfg_str = json_encode($jsfg);

        $ret = $this->t_teacher_label->row_insert([
            "teacherid"         =>$teacherid,
            "label_origin"      =>$label_origin,
            "add_time"          =>time(),
            "subject"           =>$subject,
            "interaction"       =>$sshd_str,
            "class_atmos"       =>$ktfw_str,
            "tea_standard"      =>$skgf_str,
            "tea_style"         =>$jsfg_str,
            "lessonid"          =>$lessonid,
            "lesson_list"       =>$lessonid_list
        ]);
        return $ret;
    }

    public function get_teacher_label($tea_arr){
        $teacher_label_list = $this->t_teacher_label->get_info_by_teacherid(-1,$tea_arr);
        $arr = [];
        foreach($teacher_label_list as $item){
            $teacherid = $item["teacherid"];
            $interaction = json_decode($item["interaction"],true);
            if(!empty($interaction)){
                foreach($interaction as $v){
                    @$arr[$teacherid]["interaction"][$v]["num"]++;
                    @$arr[$teacherid]["interaction"][$v]["name"] =E\Etea_label_interact_type::get_desc($v);
                    ;
                }
            }
            $class_atmos = json_decode($item["class_atmos"],true);
            if(!empty($class_atmos)){
                foreach($class_atmos as $v){
                    @$arr[$teacherid]["class_atmos"][$v]["num"]++;
                    @$arr[$teacherid]["class_atmos"][$v]["name"] =E\Etea_label_atmos_type::get_desc($v);
                }
            }

            $tea_standard = json_decode($item["tea_standard"],true);
            if(!empty($tea_standard)){
                foreach($tea_standard as $v){
                    @$arr[$teacherid]["tea_standard"][$v]["num"]++;
                    @$arr[$teacherid]["tea_standard"][$v]["name"] =E\Etea_label_norm_type::get_desc($v);
                }
            }

            $tea_style = json_decode($item["tea_style"],true);
            if(!empty($tea_style)){
                foreach($tea_style as $v){
                    @$arr[$teacherid]["tea_style"][$v]["num"]++;
                    @$arr[$teacherid]["tea_style"][$v]["name"] =E\Etea_label_style_type::get_desc($v);
                }
            }

        }
        $str=[];
        foreach($arr as $k=>$label){
            foreach($label as $item){
                foreach($item as $v){
                    @$str[$k] .= $v["name"]."<br>";
                }
            }
        }
        return $str;

    }

    //可删除，没有在用
    public function get_account_id_by_subject_and_grade($subject,$grade){
        if($subject==3){
            if($grade >=100 && $grade<200){
                return 372;
            }else{
                return 329;
            }
        }else if($subject==2){
            if($grade >=100 && $grade<200){
                return 683;
            }elseif($grade >=200 && $grade<300){
                return 481;
            }else{
                return 310;
            }

        }else if($subject==1){
            if($grade >=100 && $grade<200){
                return 404;
            }else{
                return 379;
            }

        }else if($subject==5){
            return 793;
        }else if($subject==4){
            return 770;
        }else{
            return 478;
        }
    }

    public function get_account_id_by_subject_and_grade_new($subject,$grade){
        // $ret = $this->t_admin_main_group_name->get_all_memeber_list(4,"小学科");
        $grade = substr($grade,0,1);
        $grade_list = [1=>[1,4],2=>[2,4,5],3=>[3,5,7]];
        $grade_arr = $grade_list[$grade];

        if($subject==3 || $subject==1){
            $list = $this->t_admin_main_group_name->get_all_memeber_list(4,"文综组");
            foreach($list as $item){
                if(in_array($item["grade_part_ex"],$grade_arr) && $subject ==$item["subject"]){
                    return $item["adminid"];
                }
            }
        }else if($subject==2){
            $list = $this->t_admin_main_group_name->get_all_memeber_list(4,"数学组");
            foreach($list as $item){
                if(in_array($item["grade_part_ex"],$grade_arr)){
                    return $item["adminid"];
                }
            }

        }else{
            $adminid= $this->t_admin_group_name->get_master_adminid_by_subject($subject);
            //$list = $this->t_admin_main_group_name->get_all_memeber_list(4,"小学科");
            return $adminid;
        }
    }

    public function get_tea_adminid_by_subject($subject){
        if(in_array($subject,[1,3])){
             $master_adminid = $this->t_admin_main_group_name->get_maste_admin_name(4,"文综组");
        }elseif($subject==2){
            $master_adminid = $this->t_admin_main_group_name->get_maste_admin_name(4,"数学组");
        }else{
            $master_adminid = $this->t_admin_main_group_name->get_maste_admin_name(4,"小学科");
        }
        if($subject==0){
            return 349;
        }else{
            return $master_adminid["master_adminid"];
        }
    }

    public function get_account_leader_adminid($account_id){

        if($account_id==372){
            return 329;
        }else if($account_id==683 || $account_id==481){
            return 310;
        }else if($account_id==404){
            return 379;
        }else{
            return 1;
        }
    }


    public function get_tea_subject_and_grade_by_adminid($adminid){
        if($adminid==486 || $adminid==754){
            $adminid=478;
        }
        $arr_group    = ["小学科"=>[4,5,6,7,8,9,10],"数学组"=>[2],"文综组"=>[1,3],"物理组"=>[5]];
        $account_role = $this->t_manager_info->get_account_role($adminid);
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list($account_role);
        if(in_array($adminid,$master_adminid)){
            $group_name  = $this->t_admin_main_group_name->get_group_name_by_master_adminid($adminid);
            $subject = $arr_group[$group_name];
            $grade=-1;
        }elseif($account_info["subject"]>0){
            $subject = [$account_info["subject"]];
            $grade_part_ex= $account_info["grade_part_ex"];
            if($grade_part_ex==1){
                $grade=[101,102,103];
            }else if($grade_part_ex==2){
                $grade=[201,202,203];
            }else if($grade_part_ex==3){
                $grade=[301,302,303];
            }elseif($grade_part_ex==4){
                $grade=[101,102,103,201,202,203];
            }elseif($grade_part_ex==5){
                $grade=[201,202,203,301,302,303];
            }else{
                $grade=-1;
            }

        }else{
            $subject=-1;
            $grade=-1;
        }
        return $arr=["subject"=>$subject,"grade"=>$grade];

    }

    public function get_tea_subject_and_grade_by_adminid_new($adminid){
        $account_role = $this->t_manager_info->get_account_role($adminid);
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list($account_role);
        if(in_array($adminid,[486,754,478,793])){
            $subject = [4,5,6,7,8,9,10];
            $grade=-1;
        }elseif($account_info["subject"]>0){
            $subject = [$account_info["subject"]];
            $grade_part_ex= $account_info["grade_part_ex"];
            if($grade_part_ex==1){
                $grade=[101,102,103];
            }else if($grade_part_ex==2){
                $grade=[201,202,203];
            }else if($grade_part_ex==3){
                $grade=[301,302,303];
            }elseif($grade_part_ex==4){
                $grade=[101,102,103,201,202,203];
            }elseif($grade_part_ex==5){
                $grade=[201,202,203,301,302,303];
            }else{
                $grade=-1;
            }

        }else{
            $subject=-1;
            $grade=-1;
        }
        return $arr=["subject"=>$subject,"grade"=>$grade];

    }

    public function get_tea_subject_and_right_by_adminid($adminid){
        if($adminid==349){
            $adminid=349;
        }
        $arr_group    = ["小学科"=>"(4,5,6,7,8,9,10)","数学组"=>"(2)","文综组"=>"(1,3)","物理组"=>"(5)"];
        $account_role = $this->t_manager_info->get_account_role($adminid);
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if($account_role==4){
            $qz_flag        = 0;
            $tea_right      = 1;
            if($adminid==793){
                $tea_subject="(5)";
            }elseif($adminid==770){
                $tea_subject="(4)";
            }else{
                $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list($account_role);
                if(in_array($adminid,$master_adminid)){
                    $group_name  = $this->t_admin_main_group_name->get_group_name_by_master_adminid($adminid);
                    $tea_subject = $arr_group[$group_name];
                }elseif($account_info["subject"]>0){
                    $tea_subject = "(".$account_info["subject"].")";
                }else{
                    $tea_subject="";
                }
            }
        }else if($account_role==5){
            $qz_flag     = 1;
            if($adminid==480){
                 $tea_right = 1;
            }else{
                $tea_right   = 0;
            }
            $tea_subject = "";
        }else{
            if(in_array($adminid,["72","349","60","186","68","790"])){
                $tea_right=1;
            }else{
                $tea_right=0;
            }
            $qz_flag     = 0;
            $tea_subject = "";
        }

        $acc = $this->t_manager_info->get_account($adminid);
        if($acc=="jim"){
            $tea_right=1;
        }
        $list = ["tea_subject"=>$tea_subject,"tea_right"=>$tea_right,"qz_flag"=>$qz_flag];
        return $list;
    }

    public function get_admin_tea_subject_and_arr($account_id){
        //$account_id = $this->get_account_id();
        if($account_id==349){
            $account_id=349;
        }
        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($account_id);
        //dd($account_info);
        if($account_id==72 || $account_id==349){
            $tea_subject=0;
        }elseif($account_info["account_role"]==5){
            $tea_subject=-3;
        }else if($account_info["account_role"]==4){
            $arr_group=["小学科"=>"-2","数学组"=>2,"文综组"=>-5,"物理组"=>5];
            $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list(4);
            if(in_array($account_id,$master_adminid)){
                $group_name= $this->t_admin_main_group_name->get_group_name_by_master_adminid($account_id);
                $tea_subject=$arr_group[$group_name];
            }elseif($account_info["subject"]>0){
                $tea_subject = $account_info["subject"];
            }else{
                $tea_subject=100;
            }
        }else{
            $tea_subject=100;
        }

        $subject_grade_arr=[["subject"=>"3","grade"=>200,"realname"=>"初中英语"],["subject"=>"1","grade"=>200,"realname"=>"初中语文"],["subject"=>"2","grade"=>300,"realname"=>"高中数学"],["subject"=>"-2","grade"=>"-1","realname"=>"综合学科"],["subject"=>"3","grade"=>100,"realname"=>"小学英语"],["subject"=>"1","grade"=>100,"realname"=>"小学语文"],["subject"=>"2","grade"=>200,"realname"=>"初中数学"],["subject"=>"3","grade"=>300,"realname"=>"高中英语"],["subject"=>"1","grade"=>300,"realname"=>"高中语文"],["subject"=>"2","grade"=>100,"realname"=>"小学数学"],["subject"=>"4","grade"=>"200","realname"=>"初中化学"],["subject"=>"4","grade"=>"300","realname"=>"高中化学"],["subject"=>"5","grade"=>"200","realname"=>"初中物理"],["subject"=>"5","grade"=>"300","realname"=>"高中物理"],["subject"=>"6","grade"=>"-1","realname"=>"生物"],["subject"=>"7","grade"=>"-1","realname"=>"政治"],["subject"=>"8","grade"=>"-1","realname"=>"历史"],["subject"=>"9","grade"=>"-1","realname"=>"地理"],["subject"=>"10","grade"=>"-1","realname"=>"科学"],["subject"=>"-1","grade"=>"100","realname"=>"小学"],["subject"=>"-1","grade"=>"200","realname"=>"初中"],["subject"=>"-1","grade"=>"300","realname"=>"高中"],["subject"=>"-1","grade"=>"101","realname"=>"小一"],["subject"=>"-1","grade"=>"102","realname"=>"小二"],["subject"=>"-1","grade"=>"103","realname"=>"小三"],["subject"=>"-1","grade"=>"104","realname"=>"小四"],["subject"=>"-1","grade"=>"105","realname"=>"小五"],["subject"=>"-1","grade"=>"106","realname"=>"小六"],["subject"=>"-1","grade"=>"201","realname"=>"初一"],["subject"=>"-1","grade"=>"202","realname"=>"初二"],["subject"=>"-1","grade"=>"203","realname"=>"初三"],["subject"=>"-1","grade"=>"301","realname"=>"高一"],["subject"=>"-1","grade"=>"302","realname"=>"高二"],["subject"=>"-1","grade"=>"303","realname"=>"高三"]];

        foreach($subject_grade_arr as $s=>$v){
            if($tea_subject==-3){
                if($v["subject"]!=$account_info["subject"]){
                    unset($subject_grade_arr[$s]);
                }
            }elseif($tea_subject==-2){
                if($v["subject"] !=-2 && in_array($v["subject"],[1,2,3])){
                    unset($subject_grade_arr[$s]);
                }
            }elseif($tea_subject==-5){
                if(!in_array($v["subject"],[1,3])){
                    unset($subject_grade_arr[$s]);
                }

            }elseif($tea_subject!=0 && $v["subject"]!=$tea_subject){
                unset($subject_grade_arr[$s]);
            }
        }

        if($account_info["account_role"]==4){
            $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list(4);
            if(in_array($account_id,$master_adminid)){
                $master_flag=1;
            }else{
                 $master_flag=0;
            }
        }else{
            $master_flag=1;
        }
        $list=["tea_subject"=>$tea_subject,"subject_grade_arr"=>$subject_grade_arr,"master_flag"=>$master_flag];
        return $list;

    }

    public function get_admin_group_subject_list($subject){
        if($subject==1 || $subject==3){
            $group_name = "文综组";
        }elseif($subject==2){
            $group_name = "数学组";
        }else{
            $group_name = "小学科";
        }
        $list = $this->t_admin_main_group_name->get_maste_admin_name(4,$group_name);
        $arr=[$list["master_adminid"]=>$list["account"]];
        //展会东 化学(临时)
        if($subject==4){
            $arr=["770"=>"展慧东"];
        }elseif($subject==5){
            $arr=["793"=>"seth"];
        }elseif($subject==2){
            $arr["754"]="sun";
        }
        return $arr;

    }

    public function get_accept_adminid_list($account_id){
        $master_adminid = $this->t_admin_main_group_name->get_master_adminid_list(4);
        if($account_id==349){
            $account_id=349;
        }
        $adminid_list =$this->t_admin_main_group_name->get_adminid_list_by_master_adminid(-1,4);
        if(in_array($account_id,["72","349","448","99","486"])){
            $accept_adminid_list=[];
        }else if(in_array($account_id,$master_adminid)){
           $accept_adminid_list = $this->t_admin_main_group_name->get_adminid_list_by_master_adminid($account_id,4);
        }else if(in_array($account_id,$adminid_list)){
            $accept_adminid_list=[$account_id];
        }else{
            $accept_adminid_list=[1];
        }
        return $accept_adminid_list;
    }

    public function get_not_grade_new($grade_range,$not_grade,$freeze_flag=true){
        $grade_list=[0=>[0],1=>[101,102,103],2=>[104,105,106],3=>[201,202],4=>[203],5=>[301,302],6=>[303]];
        $grade_arr= $grade_list[$grade_range];
        $not_grade_arr=explode(",",$not_grade);
        if($freeze_flag){
            foreach($grade_arr as $val){
                if(!in_array($val,$not_grade_arr)){
                    $not_grade_arr[]=$val;
                }
            }
        }else{
            foreach($not_grade_arr as  $k=>$val){
                if(in_array($val,$grade_arr)){
                    unset($not_grade_arr[$k]);
                }
            }
        }
        $res= trim(implode(",",$not_grade_arr),",");
        return $res;
    }

    public function get_detail_grade($grade_range){
        $grade_list=[0=>[0],1=>[101,102,103],2=>[104,105,106],3=>[201,202],4=>[203],5=>[301,302],6=>[303]];
        $grade_arr= $grade_list[$grade_range];
        $str="";
        foreach($grade_arr as $item){
            $str .= E\Egrade::get_desc($item)."、";
        }
        return trim($str,"、");

    }

    public function get_grade_range_new($grade){
        switch($grade){
        case 101:case 102:case 103:
            $grade_range=1;
            break;
        case 104:case 105:case 106:
            $grade_range=2;
            break;
        case 201:case 202:
            $grade_range=3;
            break;
        case 203:
            $grade_range=4;
            break;
        case 301:case 302:
            $grade_range=5;
            break;
        case 303:
            $grade_range=6;
            break;
        default:
            $grade_range=0;
        }
        return $grade_range;
    }

    public function get_teacher_grade_freeze_limit_info($teacherid,$lesson_start,$grade,$require_id){
        //周时间计算
        $date_week = \App\Helper\Utils::get_week_range($lesson_start,1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];

        //检查老师一周排课功能是否冻结
        $week_freeze_info = $this->t_teacher_info->field_get_list($teacherid,"is_week_freeze,week_freeze_time,lesson_hold_flag,is_test_user");

        $is_test = $week_freeze_info["is_test_user"];
        // $is_test=0;
        if($week_freeze_info["is_week_freeze"]==1 && $is_test==0){
            if($week_freeze_info["week_freeze_time"]>=($lstart-7*86400) && $week_freeze_info["week_freeze_time"]<($lend-7*86400)){
                return $this->output_err("该老师被限制排课,下一周开始可以排课!");
            }
        }

        //老师需满足培训通过的条件才能排试听课
        $teacher_type_train_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,train_through_new");
        if( $teacher_type_train_info["train_through_new"]==0 && $is_test==0){
            return $this->output_err("该老师培训未通过,暂不能排试听课!");
        }

        //教研老师带6个常规课学生以后不能接试听课
        $check = $this->research_fulltime_teacher_lesson_plan_limit($teacherid,-1,1,$lesson_start,2);
        if($check){
            return $check;
        }

        //老师科目/年级限制,包含冻结年级
        $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item          = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,userid");
        $tt_item['grade'] = $grade;
        $teacher_subject  = $this->t_teacher_info->field_get_list(
            $teacherid,"subject,second_subject,third_subject,grade_part_ex,second_grade,third_grade,grade_start,grade_end,not_grade,not_grade_limit,limit_plan_lesson_type"
        );
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];

        if($subject==$teacher_subject["subject"]){
            if($teacher_subject['grade_start']==0){
                $check_subject = $this->check_teacher_subject_and_grade_new(
                   $subject,$grade,$teacher_subject["subject"],$teacher_subject["second_subject"],$teacher_subject["third_subject"],
                   $teacher_subject["grade_part_ex"],$teacher_subject["second_grade"],$teacher_subject["third_grade"],$is_test,
                   $teacher_subject["not_grade"]
                );
            }else{
                $check_subject = $this->check_teacher_grade_range_new($tt_item,$teacher_subject);
            }
        }else{
            $check_subject = $this->check_teacher_subject_and_grade_new(
                $subject,$grade,$teacher_subject["subject"],$teacher_subject["second_subject"],$teacher_subject["third_subject"],
                $teacher_subject["grade_part_ex"],$teacher_subject["second_grade"],$teacher_subject["third_grade"],$is_test,
                $teacher_subject["not_grade"]
            );
        }

        if($check_subject != 1){
            return $check_subject;
        }

        //系统限课
        $test_lesson_num = $this->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
        if($test_lesson_num >= $teacher_subject["limit_plan_lesson_type"]
           && $is_test==0
           && $teacher_subject["limit_plan_lesson_type"] !=0
        ){
            return $this->output_err(
                "该老师排课受限制,一周限排".$teacher_subject["limit_plan_lesson_type"]."节,"
                ."当周该老师已排".$test_lesson_num."节,不能继续排课!"
            );
        }

        //新入职老师当周限排6节课,其他老师每周限排8节课,一天限排4节课
        $limit_num_info  = $this->t_teacher_info->field_get_list($teacherid,"limit_day_lesson_num,limit_week_lesson_num");
        $ret             = $this->t_lesson_info->check_teacher_have_test_lesson_pre_week($teacherid,$lstart);
        $test_lesson_num = $this->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
        if($ret ==1){
            if($test_lesson_num >=$limit_num_info["limit_week_lesson_num"] && $is_test==0){
                return $this->output_err(
                    "试听课一周限排".$limit_num_info["limit_week_lesson_num"]."节!目前老师已排".$test_lesson_num."节."
                );
            }
        }else{
            if($test_lesson_num >=6 && $is_test==0){
                return $this->output_err(
                    "新入职老师,试听课一周限排6节!目前老师已排".$test_lesson_num."节!目前老师已排".$test_lesson_num."节."
                );
            }
        }

        $day_st    = date("Y-m-d",$lesson_start);
        $day_start = strtotime($day_st);
        $day_end   = strtotime($day_st." 23:59:59");
        $test_lesson_num_day = $this->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$day_start,$day_end);
        if($test_lesson_num_day>=$limit_num_info["limit_day_lesson_num"] && $is_test==0){
            return $this->output_err(
                "试听课一天限排".$limit_num_info["limit_day_lesson_num"]."节!目前老师已排".$test_lesson_num_day."节"
            );
        }

    }

    public function check_teacher_subject_and_grade_new(
        $subject,$grade,$first_subject,$second_subject,$third_subject,
        $grade_part_ex,$second_grade,$third_grade,$is_test,$not_grade
    ){
        if($is_test ==0){
            if($subject != $first_subject && $subject != $second_subject && $subject != $third_subject){
                return $this->output_err(
                    "请安排与老师科目相符合的课程!"
                );
            }

            if($subject==$first_subject){
                if($grade==106){
                    if($grade_part_ex !=1 && $grade_part_ex!=6 && $grade_part_ex!=4 ){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }elseif($grade==203){
                    if($grade_part_ex !=2 && $grade_part_ex!=5 && $grade_part_ex!=4 && $grade_part_ex!=7 ){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }elseif($grade>=100 && $grade <200){
                    if($grade_part_ex !=1 && $grade_part_ex!=4 ){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($grade_part_ex !=2 && $grade_part_ex !=4 && $grade_part_ex !=5 && $grade_part_ex!=6){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($grade_part_ex !=3 && $grade_part_ex !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }

                }
            }else if($subject==$second_subject){
                if($grade>=100 && $grade <200){
                    if($second_grade !=1 && $second_grade !=4){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($second_grade !=2 && $second_grade !=4 && $second_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($second_grade !=3 && $second_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }
            }else if($subject==$third_subject){
                if($grade>=100 && $grade <200){
                    if($third_grade !=1 && $third_grade !=4){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if($third_grade !=2 && $third_grade !=4 && $third_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }else if($grade>=300 ){
                    if($third_grade !=3 && $third_grade !=5){
                        return $this->output_err(
                            "请安排与老师年级段相符合的课程!"
                        );
                    }
                }
            }

            //冻结年级
            $not_grade_arr       = explode(",",$not_grade);
            if(in_array($grade,$not_grade_arr) && $subject==$first_subject){
                return $this->output_err("该老师对应年级段已被冻结!");
            }


            return 1;
        }else{
            return 1;
        }
    }

    public function check_teacher_grade_range_new($stu_info,$tea_info){
        $stu_grade_range = $this->get_grade_range_new($stu_info['grade']);
        $not_grade       = explode(",",$tea_info['not_grade']);
        $grade_start     = $tea_info['grade_start'];
        $grade_end       = $tea_info['grade_end'];

        if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end){
            return $this->output_err("学生年级与老师年级范围不匹配!");
        }
        if($stu_info['subject']!=$tea_info['subject']){
            return $this->output_err("学生科目与老师科目不匹配!");
        }
        if(in_array($stu_info['grade'],$not_grade)){
            return $this->output_err("该老师对应年级段已被冻结!");
        }

        return 1;
    }


    public function get_seller_limit_require_info($teacherid,$lesson_start,$grade,$subject,$account_role,$master_adminid,$is_green_flag){
        $list = $this->t_teacher_info->field_get_list($teacherid,"subject,second_subject,third_subject,grade_part_ex,second_grade,third_grade,grade_start,grade_end,not_grade");
        //科目限制
        if(!in_array($subject,[$list["subject"],$list["second_subject"],$list["third_grade"]])){
            return $this->output_err("学生科目与老师科目不符,不能做特殊申请!");
        }

        //年级限制
        if($list["grade_start"]>0){
            $stu_grade_range = $this->get_grade_range_new($grade);
            $grade_start     = $list['grade_start'];
            $grade_end       = $list['grade_end'];

            if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end){
                return $this->output_err("学生年级与老师年级范围不匹配,不能做限课特殊申请");
            }
        }else{
            if($subject==$list["subject"]){
                if($grade==106){
                    if(!in_array($list["grade_part_ex"],[1,4,6])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }elseif($grade==203){
                    if(!in_array($list["grade_part_ex"],[2,4,5,7])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }

                }elseif($grade>=100 && $grade <200){
                    if(!in_array($list["grade_part_ex"],[1,4])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if(!in_array($list["grade_part_ex"],[2,4,5,6])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=300 ){
                    if(!in_array($list["grade_part_ex"],[3,5])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }

                }
            }else if($subject==$list["second_subject"]){
                if($grade>=100 && $grade <200){
                    if(!in_array($list["second_grade"],[1,4])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if(!in_array($list["second_grade"],[2,4,5])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=300 ){
                    if(!in_array($list["second_grade"],[3,5])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }
            }else if($subject==$list["third_grade"]){
                if($grade>=100 && $grade <200){
                    if(!in_array($list["third_grade"],[1,4])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=200 && $grade <300){
                    if(!in_array($list["third_grade"],[2,4,5])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }else if($grade>=300 ){
                    if(!in_array($list["third_grade"],[3,5])){
                        return $this->output_err(
                            "老师年级段不相符,不能做特殊申请!"
                        );
                    }
                }
            }

        }

        //冻结排课

        $not_grade_arr= explode(",",$list["not_grade"]);
        if(in_array($grade,$not_grade_arr) && $subject==$list["subject"]){
            return $this->output_err("该老师对应年级段已被冻结!不可以做限课特殊申请");
        }

        $date_week = \App\Helper\Utils::get_week_range($lesson_start,1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];

        $test_lesson_num = $this->t_lesson_info->get_limit_type_teacher_lesson_num($teacherid,$lstart,$lend);
        if($test_lesson_num >=10){
            return $this->output_err("该老师该周已有10课时课!不可以做限课特殊申请!");
        }

        //申请数量限制
        $require_month=["05"=>"2000","06"=>"35000","07"=>"6500","08"=>"7000","09"=>"7500","10"=>"8000","11"=>"8500","12"=>"9000"];
        $m = date("m",time());
        $start_time = strtotime(date("Y-m-01",time()));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
        $limit_num = 1000;
        if($account_role==2 && $is_green_flag){
            if($master_adminid==287){
                $limit_num= ceil($require_month[$m]*0.027);
            }elseif($master_adminid==416){
                $limit_num= ceil($require_month[$m]*0.009);
            }elseif($master_adminid==364){
                 $limit_num= ceil($require_month[$m]*0.009);
            }

        }else{
            $limit_num= ceil($require_month[$m]*0.016);
        }

        $num = $this->t_test_lesson_subject_require->get_month_limit_require_num($master_adminid,$start_time,$end_time);
        if($num >= $limit_num){
            return $this->output_err("本月特殊限课已达上限".$limit_num."次");
        }


    }


    //常规课表不能连排三节
    public function regular_course_set_check($teacherid,$week,$start_time,$end_time,$old_start_time){
        $ymd = date("Y-m-d",time());
        $start_time = strtotime($ymd." ".$start_time);
        $dinner_time = strtotime($ymd." 18:00:00");
        if($start_time < $dinner_time){
            $end_time = strtotime($ymd." ".$end_time);
            $list = $this->t_week_regular_course->get_week_regular_course_info($teacherid,$week,$old_start_time);
            foreach($list as $k=>$val){
                $start = $val["start_time"];
                $end = $val["end_time"];
                $arr = explode("-",$start);
                $start = @$arr[1];
                $wstart = strtotime($ymd." ".$start);
                $wend = strtotime($ymd." ".$end);

                if($start_time >= $wend && $start_time <($wend +60)){
                    $res = $list;
                    unset($res[$k]);
                    foreach($res as $item){
                        $s = $item["start_time"];
                        $e = $item["end_time"];
                        $arr = explode("-",$s);
                        $s = @$arr[1];
                        $ws = strtotime($ymd." ".$s);
                        $we = strtotime($ymd." ".$e);
                        if($wstart >= $we && $wstart <($we +60)){
                            return $this->output_err(
                                "不能排三节连续的常规课"
                            );
                        }
                        if($ws >= $end_time && $ws <($end_time +60)){
                            return $this->output_err(
                                "不能排三节连续的常规课"
                            );
                        }

                    }

                }

                if($wstart >= $end_time && $wstart <($end_time +60)){
                    $res = $list;
                    unset($res[$k]);
                    foreach($res as $item){
                        $s = $item["start_time"];
                        $e = $item["end_time"];
                        $arr = explode("-",$s);
                        $s = @$arr[1];
                        $ws = strtotime($ymd." ".$s);
                        $we = strtotime($ymd." ".$e);
                        if($start_time >= $we && $start_time <($we +60)){
                            return $this->output_err(
                                "不能排三节连续的常规课"
                            );
                        }
                        if($ws >= $wend && $ws <($wend +60)){
                            return $this->output_err(
                                "不能排三节连续的常规课"
                            );
                        }

                    }

                }


            }

        }

    }

    public function get_teacher_all_grade($teacherid){
        $grade_info = $this->t_teacher_info->field_get_list($teacherid,"grade_part_ex,grade_start,grade_end");
        $grade_part_ex = $grade_info["grade_part_ex"];
        $grade_start = $grade_info["grade_start"];
        $grade_end = $grade_info["grade_end"];
        $not_grade = "";
        if($grade_start>0){
            for($i=$grade_start;$i<=$grade_end;$i++){
                if($i==1){
                    $not_grade .= "101,102,103,";
                }elseif($i==2){
                    $not_grade .= "104,105,106,";
                }elseif($i==3){
                    $not_grade .= "201,202,";
                }elseif($i==4){
                    $not_grade .= "203,";
                }elseif($i==5){
                    $not_grade .= "301,302,";
                }elseif($i==6){
                    $not_grade .= "303,";
                }

            }
        }else{
            if($grade_part_ex==1){
                $not_grade = "101,102,103,104,105,106";
            }elseif($grade_part_ex==2){
                $not_grade = "201,202,203";
            }elseif($grade_part_ex==3){
                $not_grade = "301,302,303";
            }elseif($grade_part_ex==4){
                $not_grade = "101,102,103,104,105,106,201,202,203";
            }elseif($grade_part_ex==5){
                $not_grade = "201,202,203,301,302,303";
            }elseif($grade_part_ex==6){
                $not_grade = "106,201,202,203";
            }elseif($grade_part_ex==7){
                $not_grade = "203,301,302,303";
            }
        }
        $not_grade = trim($not_grade,",");
        return $not_grade;
    }


    public function get_first_lesson_start($teacherid,$lesson_start){
        $lesson_end = $lesson_start-5400;
        $start = $this->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$lesson_start);
        if($start>0){
            return $this->get_first_lesson_start($teacherid,$start);
        }else{
            return $lesson_start;
        }
    }

    public function course_set_new_ex( $require_id, $teacherid,  $lesson_start, $grade,$adminid , $account ) {
        $lesson_end   = $lesson_start+2400;
        $orderid      = 1;

        $db_lessonid = $this->t_test_lesson_subject_require->get_current_lessonid($require_id);
        if ($db_lessonid){
            return $this->output_err("已经排课过了!,可以换老师&时间");
        }
        if ($teacherid<=0 || $lesson_end<=0 || $lesson_start<=0 ) {
            return $this->output_err("请填写完整!");
        }
        if($lesson_start < time()){
            return $this->output_err("课程开始时间过早!");
        }

        //老师年级科目限制
        $rr = $this->get_teacher_grade_freeze_limit_info($teacherid,$lesson_start,$grade,$require_id);
        if($rr){
            return $rr;
        }

        $test_lesson_subject_id=$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,grade,userid");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];

        $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
        //检查时间是否冲突
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret_row2=$this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        $courseid     = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
        $lessonid     = $this->t_lesson_info->add_lesson(
            $courseid,0,$userid,0,2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,100,$teacher_info["teacher_money_type"],$teacher_info["level"]
        );
        $this->t_homework_info->add(
            $courseid,0,$userid,$lessonid,$grade,$subject,$teacherid
        );
        $this->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
            "grade"  => $grade,
        ]);
        $this->t_test_lesson_subject_sub_list->row_insert([
            "lessonid"           => $lessonid,
            "require_id"         => $require_id,
            "set_lesson_adminid" =>  $adminid,
            "set_lesson_time"    => time(NULL) ,
        ]);
        $this->t_test_lesson_subject_require->field_update_list($require_id,[
            'current_lessonid'      => $lessonid,
            'accept_flag'           => E\Eset_boolean::V_1 ,
            'accept_time'           => time(NULL),
            'jw_test_lesson_status' => 1,
            'grab_status'           => 2,
        ]);
        $this->t_test_lesson_subject_require->set_test_lesson_status(
            $require_id,E\Eseller_student_status::V_210,$account);

        $this->t_lesson_info->reset_lesson_list($courseid);
        $this->t_seller_student_new->field_update_list($userid,[
            "global_tq_called_flag" => 2,
            "tq_called_flag"        => 2,
        ]);

        $require_info = $this->t_test_lesson_subject_require->field_get_list($require_id,"test_lesson_subject_id,accept_adminid");
        $this->t_test_lesson_subject->field_update_list($require_info["test_lesson_subject_id"],[
            "history_accept_adminid" => $require_info["accept_adminid"]
        ]);


        if (\App\Helper\Utils::check_env_is_release()){
            $require_adminid  = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
            $userid           = $this->t_test_lesson_subject->get_userid($test_lesson_subject_id);
            $phone            = $this->t_seller_student_new->get_phone($userid);
            $nick             = $this->t_student_info ->get_nick($userid);
            $teacher_nick     = $this->cache_get_teacher_nick($teacherid);
            $require_phone    = $this->t_manager_info->get_phone($require_adminid);
            $stu_request_info = $this->t_test_lesson_subject->get_stu_request($lessonid);
            $demand           = $stu_request_info['stu_request_test_lesson_demand'];

            $lesson_time_str    = \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $require_admin_nick = $this->cache_get_account_nick($require_adminid);
            $this->t_manager_info->send_wx_todo_msg(
                $require_admin_nick,"来自:".$this->get_account()
                ,"排课[$phone][$nick] 老师[$teacher_nick] 上课时间[$lesson_time_str]","","");

            $parentid = $this->t_student_info->get_parentid($userid);
            $this->t_parent_info->send_wx_todo_msg($parentid,"课程反馈","您的试听课已预约成功!", "上课时间[$lesson_time_str]","http://wx-parent.leo1v1.com/wx_parent/index", "点击查看详情" );


            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $wx_openid = $this->t_teacher_info->get_wx_openid($teacherid);
            if($wx_openid!=""){
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
                $data['keyword1'] = "备课通知";
                $data['keyword2'] = "\n上课时间：$lesson_time_str "
                                  ."\n教务电话：$require_phone"
                                  ."\n试听需求：$demand"
                                  ."\n1、请及时确认试听需求并备课"
                                  ."\n2、请尽快上传教师讲义、学生讲义（用于学生预习）和作业"
                                  ."\n3、老师可提前15分钟进入课堂进行上课准备";
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "http://www.leo1v1.com/login/teacher";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }
        }

        return $this->output_succ();
    }


    /**
     * teacher_info可以添加的老师信息如下
     * @param string phone 老师账号
     * @param string acc   添加人
     * @param int wx_use_flag 能否使用微信功能 0 不能 1 能
     * @param int trial_lecture_is_pass 老师试讲是否通过 0 未通过 1 通过
     * @param int train_through_new 是否通过培训 0 不是 1 是
     * @param int teacher_money_type 老师工资类型
     * @param int level 老师工资等级
     * @param int subject 老师第一科目
     * @param int grade 老师第一科目年级
     * @param string tea_nick 老师昵称
     * @param string realname 老师真实姓名
     * @param string phone_spare 老师额外的联系方式
     * @param string not_grade 老师禁止的排课年级
     * @param int identity 老师身份
     * @param int teahcer_type 老师类型
     * @param int teacher_ref_type 老师推荐人类型
     * @param int is_test_user 是否是测试账号 0 不是 1 是
     * @param int use_easy_pass 是否用123456作为老师账号密码 0 不是 1 是
     * @param int send_sms_flag 是否发送老师账号短信 0 不 1 是
     * @param string base_intro 老师简介 
     * @param int grade_start 老师年级开始范围
     * @param int grade_end 老师年级结束范围
     * @param string email 邮箱
     * @param string school 学校
     * @param int transfer_teacherid 转移自此teacherid
     * @param int transfer_time 转移时间
     * @param string interview_access 老师面试评价
     */
    public function add_teacher_common($teacher_info){
        $phone = $teacher_info['phone'];
        if($phone==""){
            return "老师手机不能为空！";
        }
        $check_flag = $this->t_teacher_info->check_teacher_phone($phone);
        if($check_flag){
            return "该手机号已存在！";
        }

        \App\Helper\Utils::set_default_value($acc,$teacher_info,"","acc");
        \App\Helper\Utils::set_default_value($wx_use_flag,$teacher_info,0,"wx_use_flag");
        \App\Helper\Utils::set_default_value($trial_lecture_is_pass,$teacher_info,0,"trial_lecture_is_pass");
        \App\Helper\Utils::set_default_value($train_through_new,$teacher_info,0,"train_through_new");
        \App\Helper\Utils::set_default_value($teacher_money_type,$teacher_info,4,"teacher_money_type");
        \App\Helper\Utils::set_default_value($level,$teacher_info,0,"level");
        \App\Helper\Utils::set_default_value($grade,$teacher_info,0,"grade");
        \App\Helper\Utils::set_default_value($subject,$teacher_info,0,"subject");
        \App\Helper\Utils::set_default_value($tea_nick,$teacher_info,$phone,"tea_nick");
        \App\Helper\Utils::set_default_value($realname,$teacher_info,$tea_nick,"realname");
        \App\Helper\Utils::set_default_value($phone_spare,$teacher_info,$phone,"phone_spare");
        \App\Helper\Utils::set_default_value($not_grade,$teacher_info,"","not_grade");
        \App\Helper\Utils::set_default_value($identity,$teacher_info,0,"identity");
        \App\Helper\Utils::set_default_value($teacher_type,$teacher_info,0,"teacher_type");
        \App\Helper\Utils::set_default_value($teacher_ref_type,$teacher_info,0,"teacher_ref_type");
        \App\Helper\Utils::set_default_value($is_test_user,$teacher_info,0,"is_test_user");
        \App\Helper\Utils::set_default_value($use_easy_pass,$teacher_info,0,"use_easy_pass");
        \App\Helper\Utils::set_default_value($send_sms_flag,$teacher_info,1,"send_sms_flag");
        \App\Helper\Utils::set_default_value($base_intro,$teacher_info,"","base_intro");
        \App\Helper\Utils::set_default_value($grade_start,$teacher_info,0,"grade_start");
        \App\Helper\Utils::set_default_value($grade_end,$teacher_info,0,"grade_end");
        \App\Helper\Utils::set_default_value($email,$teacher_info,"","email");
        \App\Helper\Utils::set_default_value($school,$teacher_info,"","school");
        \App\Helper\Utils::set_default_value($transfer_teacherid,$teacher_info,0,"transfer_teacherid");
        \App\Helper\Utils::set_default_value($transfer_time,$teacher_info,0,"transfer_time");
        \App\Helper\Utils::set_default_value($interview_access,$teacher_info,"","interview_access");
        $train_through_new_time = $train_through_new==1?time():0;

        $adminid = $this->t_manager_info->get_id_by_phone($phone);
        if($adminid>0){
            if($tea_nick==$phone){
                $tea_nick = $this->t_manager_info->get_name($adminid);
                $realname = $tea_nick;
            }
            $teacher_type     = E\Eteacher_type::V_41;
            $teacher_ref_type = E\Eteacher_ref_type::V_41;
        }else{
            $reference      = $this->t_teacher_lecture_appointment_info->get_reference_by_phone($phone);
            $reference_info = $this->t_teacher_info->get_teacher_info_by_phone($reference);
            if(isset($reference_info['teacher_type']) && $reference_info['teacher_type']>20){
                if($reference_info['teacher_type']>30){
                    $teacher_ref_type = $reference_info['teacher_ref_type'];
                }elseif(in_array($reference_info['teacher_type'],[21,22]) && in_array($teacher_ref_type,[1,2,3,4,5])){
                    $teacher_ref_type = $reference_info['teacher_ref_type'];
                    $teacher_money_type = E\Eteacher_money_type::V_5;
                }
            }
        }

        if(substr($phone,0,3)=="999"){
            $is_test_user=1;
        }

        if($use_easy_pass){
            $passwd = "123456";
        }else{
            srand(microtime(true)*1000);
            $passwd = (int)$phone+rand(9999999999,99999999999);
            $passwd = substr($passwd,0,6);
        }

        $passwd_md5 = md5($passwd);
        $this->t_user_info->start_transaction();
        $this->t_user_info->row_insert([
            "passwd" => $passwd_md5,
        ]);
        $teacherid = $this->t_user_info->get_last_insertid();
        if (!$teacherid) {
            $this->t_user_info->rollback();
            return "老师账号生成失败！请重试！";
        }
        $ret = $this->t_phone_to_user->add($phone,E\Erole::V_TEACHER,$teacherid) ;
        if (!$ret)  {
            $this->t_user_info->rollback();
            return false;
        }
        $this->t_teacher_info->add_teacher_info_to_ejabberd($teacherid,$passwd_md5);

        if($grade_start!=0 && $grade_end!=0){
            $grade_range = ["grade_start"=>$grade_start,"grade_end"=>$grade_end];
        }else{
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($grade);
        }
        $ret = $this->t_teacher_info->row_insert([
            "teacherid"              => $teacherid,
            "nick"                   => $tea_nick,
            "realname"               => $realname,
            "phone"                  => $phone,
            "phone_spare"            => $phone_spare,
            "teacher_money_type"     => $teacher_money_type,
            "level"                  => $level,
            "subject"                => $subject,
            "grade_part_ex"          => $grade,
            "grade_start"            => $grade_range['grade_start'],
            "grade_end"              => $grade_range['grade_end'],
            "not_grade"              => $not_grade,
            "create_time"            => time(),
            "trial_lecture_is_pass"  => $trial_lecture_is_pass, 
            "train_through_new"      => $train_through_new, 
            "train_through_new_time" => $train_through_new_time, 
            "wx_use_flag"            => $wx_use_flag, 
            "identity"               => $identity, 
            "teacher_type"           => $teacher_type, 
            "teacher_ref_type"       => $teacher_ref_type, 
            "add_acc"                => $acc, 
            "is_test_user"           => $is_test_user, 
            "base_intro"             => $base_intro, 
            "email"                  => $email, 
            "school"                 => $school, 
            "transfer_teacherid"     => $transfer_teacherid, 
            "transfer_time"          => $transfer_time, 
            "interview_access"       => $interview_access, 
        ]);

        if(!$ret){
            $this->t_user_info->rollback();
            return false;
        }else{
            $this->t_user_info->commit();
        }

        if($send_sms_flag==1){
            /**
             * 模板名称 : 老师注册通知
             * 模板ID   : SMS_55565027
             * 模板内容 : ${name}老师您好，您已经成功注册理优教育平台，您的帐号是您的手机号，密码是：${passwd}，
             请用此帐号绑定“理优1对1老师帮”公众号，参加培训通过后即可成为理优正式授课老师。
            */
            $sign_name = \App\Helper\Utils::get_sms_sign_name();
            $arr = [
                "name"   => $tea_nick,
                "passwd" => $passwd,
            ];
            \App\Helper\Utils::sms_common($phone,55565027,$arr,0,$sign_name);
        }
        $ret = $this->t_teacher_freetime_for_week->row_insert([
            "teacherid" => $teacherid,
        ]);

        return (int)$teacherid;
    }

    /**
     * @param teacher_info arr 原有老师信息 使用t_teacher_info 中的 get_teacher_info_by_phone 获取
     * @param check_info arr 待检测更新的年级和科目信息
     */
    public function set_teacher_grade($teacher_info,$check_info){
        $grade_range = \App\Helper\Utils::change_grade_to_grade_range($check_info["grade"]);
        $check_info["grade_start"] = $grade_range["grade_start"];
        $check_info["grade_end"]   = $grade_range["grade_end"];
        if($check_info['subject']==$teacher_info['subject'] || $teacher_info['subject']<=0){
            $ret = $this->update_teacher_grade_range($check_info,$teacher_info,1);
        }elseif($check_info['subject']==$teacher_info["second_subject"] || $teacher_info['second_subject']<=0){
            $ret = $this->update_teacher_grade_range($check_info,$teacher_info,2);
        }
        return $ret;
    }

    /**
     * @param info  待更新的老师年级范围信息
     * @param phone 老师手机号
     */
    public function update_teacher_grade_range($info,$teacher_info,$type){
        $not_grade_arr = [];
        if($type==1){
            $not_grade   = "not_grade";
            $grade_start = "grade_start";
            $grade_end   = "grade_end";
            $subject     = "subject";
        }else{
            $not_grade   = "second_not_grade";
            $grade_start = "second_grade_start";
            $grade_end   = "second_grade_end";
            $subject     = "second_subject";
        }

        if(isset($info["not_grade"]) && $info["not_grade"] != ""){
            $not_grade_arr[] = $info['not_grade'];
        }
        if($teacher_info[$not_grade]!=""){
            $not_grade_arr[] = $teacher_info[$not_grade];
        }

        if($teacher_info[$grade_start]==0 || $teacher_info[$grade_end]==0){
            $l_grade_start = $info['grade_start'];
            $r_grade_end   = $info['grade_end'];
        }else{
            if($info['grade_end']>$teacher_info[$grade_start]){
                $l_grade_start = $teacher_info[$grade_start];
                $l_grade_end   = $teacher_info[$grade_end];
                $r_grade_start = $info['grade_start'];
                $r_grade_end   = $info['grade_end'];
            }else{
                $l_grade_start = $info['grade_start'];
                $l_grade_end   = $info['grade_end'];
                $r_grade_start = $teacher_info[$grade_start];
                $r_grade_end   = $teacher_info[$grade_end];
            }

            $grade_range = [
                1 => "101,102,103",
                2 => "104,105,106",
                3 => "201,202",
                4 => "203",
                5 => "301,302",
                6 => "303",
            ];
            if($r_grade_start-$l_grade_end>1){
                for($i=$r_grade_start+1;$i<$l_grade_end;$i++){
                    $not_grade_arr[] = $grade_range[$i];
                }
            }
        }

        $not_grade_str="";
        if(!empty($not_grade_arr)){
            $not_grade_str = implode(",",$not_grade_arr);
        }
        $update_arr = [
            $grade_start => $l_grade_start,
            $grade_end   => $r_grade_end,
            $not_grade   => $not_grade_str,
        ];
        if($teacher_info[$subject]==0){
            $update_arr[$subject]=$info['subject'];
        }
        $arr_num = count($update_arr);
        if(count(array_intersect_assoc($teacher_info,$update_arr)) != $arr_num){
            $ret = $this->t_teacher_info->field_update_list($teacher_info['teacherid'],$update_arr);
        }else{
            $ret = true;
        }
        return $ret;
    }

    public function check_teacher_lecture_is_pass($teacher_info){
        $update_arr = [];
        $appointment_info = $this->t_teacher_lecture_appointment_info->get_simple_info($teacher_info['phone']);
        if($teacher_info['nick']==$teacher_info['phone']){
            $update_arr["nick"]     = $appointment_info['name'];
            $update_arr["realname"] = $appointment_info['name'];
        }
        if(in_array($teacher_info['teacher_type'],[32])){
            $update_arr['teacher_type']=0;
        }
        if($teacher_info['level']==0){
            $update_arr['level']=1;
        }
        if($teacher_info['trial_lecture_is_pass']==0){
            $update_arr['trial_lecture_is_pass']=1;
        }
        if($teacher_info['wx_use_flag']==0){
            $update_arr['wx_use_flag']=1;
        }
        if($teacher_info['identity']==0){
            $update_arr['identity'] = $appointment_info['teacher_type'];
        }
        if(!empty($update_arr)){
            $ret = $this->t_teacher_info->field_update_list($teacher_info['teacherid'],$update_arr);
            if($ret){
                return false;
            }
        }
        return true;
    }

    public function change_https_to_http_new(&$url){
        $length = strlen($url);
        if(substr($url,0,5)=="https"){
            $url = "http".substr($url,5,$length);
        }
    }

    public function send_lecture_sms_new($teacher_info,$status){
        $teacher_re_submit_num = $this->t_teacher_lecture_info->get_teacher_re_submit_num($teacher_info['id']);
        if(!isset($teacher_info['phone']) || $teacher_re_submit_num>0){
            return false;
        }

        if($status==1){
            /**
             * 老师试讲通过2-14
             * SMS_46865086
             * 面试结果通知：${name}老师您好，恭喜您已经成功通过试讲，试讲反馈情况是：${reason}。
             每周我们都会组织新入职老师的在线培训，帮助各位老师熟悉软件使用，提高教学技能。
             请您准时参加培训，培训通过后我们会及时给您安排试听课。
            */
            $sms_id = 46865086;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试通过";
        }elseif($status==2){
            /**
             * 模板名称 : 老师试讲未通过2-14
             * 模板ID   : SMS_46745131
             * 模板内容 : 面试结果通知：${name}老师您好，我们详细回看了试讲视频，很抱歉您没有通过面试审核。
             理优教育致力于打造高水平的教学服务团队，期待将来您能加入理优教学团队，如对面试结果有疑问请联系招聘老师。
            */
            $sms_id = 46745131;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试淘汰";
        }elseif($status==3){
            /**
             * 模板名称 : 老师试讲可重申2-14
             * 模板ID   : SMS_46670149
             * 模板内容 : 面试结果通知：${name}老师您好，我们详细回看了试讲视频，很抱歉您没有通过面试审核。
             但您的潜力很大，我们给予您二次试讲机会。您的试讲反馈情况是：${reason}。
             理优教育致力于打造高水平的教学服务团队，期待您能通过下次面试，加油！如对面试结果有疑问请联系招聘老师。
            */
            $sms_id = 46670149;
            $arr    = [
                "name"   => $teacher_info['nick'],
                "reason" => $teacher_info['reason'],
            ];
            $info_str = "面试重审";
        }
        \App\Helper\Utils::sms_common($teacher_info['phone'],$sms_id,$arr);

        $admin_arr = [
            492 => "zoe",
            513 => "abby",
            790 => "ivy",
        ];
        $header_msg  = "老师".$info_str."通知";
        $from_user   = "理优面试组";
        $admin_url   = "http://admin.yb1v1.com/human_resource/teacher_lecture_list/?phone=".$teacher_info["phone"];
        $subject_str = E\Esubject::get_desc($teacher_info['subject']);

        foreach($admin_arr as $id => $name){
            $msg_info = $name."老师你好,".$subject_str."学科老师".$teacher_info['nick'].$info_str
                                  .",建议如下:".$teacher_info['reason'];
            $this->t_manager_info->send_wx_todo_msg_by_adminid($id,$from_user,$header_msg,$msg_info,$admin_url);
        }
    }

    /**
     * @param phone 被推荐的老师电话
     */
    public function add_reference_reward($phone){
        $teacher_info        = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        $reference           = $this->t_teacher_lecture_appointment_info->get_reference_by_phone($phone);
        $reference_teacherid = $this->t_teacher_info->get_teacherid_by_phone($reference);
        $reference_count     = $this->t_teacher_lecture_appointment_info->get_reference_count($reference);

        $check_flag=$this->t_teacher_money_list->check_is_exists($teacher_info['teacherid'],6);
        if(!$check_flag){
            $reference_reward = \App\Helper\Utils::get_reference_money($teacher_info['identity'],$reference_count);
            $this->t_teacher_info->row_insert([
                "teacherid"  => $reference_teacherid,
                "type"       => 6,
                "add_time"   => time(),
                "money"      => $reward,
                "money_info" => $teacher_info['teacherid'],
                "acc"        => session("acc"),
            ]);
        }
    }

    public function get_fulltime_teacher_test_lesson_score($teacherid,$start_time,$end_time){
        $d = ($end_time - $start_time)/86400;
        $qz_tea_arr = [$teacherid];
        $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$end_time);
        $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$end_time);
        $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$end_time);
        $cc_lesson_num =  isset($qz_tea_list[$teacherid])?$qz_tea_list[$teacherid]["all_lesson"]:0;
        $cc_order_num =  isset($qz_tea_list[$teacherid])?$qz_tea_list[$teacherid]["order_num"]:0;
        $kk_lesson_num =  isset($qz_tea_list_kk[$teacherid])?$qz_tea_list_kk[$teacherid]["all_lesson"]:0;
        $kk_order_num =  isset($qz_tea_list_kk[$teacherid])?$qz_tea_list_kk[$teacherid]["order_num"]:0;
        $hls_lesson_num =  isset($qz_tea_list_hls[$teacherid])?$qz_tea_list_hls[$teacherid]["all_lesson"]:0;
        $hls_order_num =  isset($qz_tea_list_hls[$teacherid])?$qz_tea_list_hls[$teacherid]["order_num"]:0;
        $cc_per = !empty($cc_lesson_num)?round($cc_order_num/$cc_lesson_num*100,2):0;
        $kk_per = !empty($kk_lesson_num)?round($kk_order_num/$kk_lesson_num*100,2):0;
        $hls_per = !empty($hls_lesson_num)?round($hls_order_num/$hls_lesson_num*100,2):0;
        $lesson_all = $cc_lesson_num+$kk_lesson_num+$hls_lesson_num;
        $order_all = $cc_order_num+$kk_order_num+$hls_order_num;
        $all_per = !empty($lesson_all)?round($order_all/$lesson_all*100,2):0;
        $lesson_per = $lesson_all/$d*100;
        if( $lesson_per>100){
            $lesson_per=100;
        }
        $cc_score = round($cc_per*0.5,2);
        $kk_score = round($kk_per*0.1,2);
        $hls_score= round($hls_per*0.1,2);
        $lesson_score = round($lesson_per*0.1,2);
        $all_score = round($all_per*0.2,2);

        $score =  $cc_score+ $kk_score + $hls_score+$lesson_score+$all_score;
        return $score;
    }

    public function set_teacher_lecture_is_pass($teacher_info){
        
    }

    public function get_ass_refund_score($start_time,$end_time){
        $list = $this->t_order_refund->get_ass_refund_info_new($start_time,$end_time);
        $arr=[];
        foreach($list as $val){
            $ss = $val["orderid"]."-".$val["apply_time"];
            @$arr[$val["uid"]][$ss][$val["value"]]=$val["score"]; 
        }

        $refund_score = [];
        foreach($arr as $uu=>$item){
            foreach($item as $v){
                $all=0;$ass=0;
                foreach($v as $k=>$s){
                    if($k=="助教部"){
                        $ass = $s;
                    }
                    $all +=$s;
                }
                
                @$refund_score[$uu] +=10*$ass/$all;
            }
        }
        return $refund_score;
    }

    public function get_tea_refund_info($start_time,$end_time,$tea_arr){
        $list = $this->t_order_refund->get_tea_refund_info_new($start_time,$end_time,$tea_arr);
        $arr=[];
        foreach($list as $val){
            if($val["value"]=="教学部" && $val["score"]>0){
                @$arr[$val["teacherid"]]++;
            }
        }

        return $arr;
    }

    public function get_ass_leader_account_id($adminid){
        if($adminid==503){
            $adminid = 297;
        }elseif($adminid==512){
            $adminid =702;
        }elseif($adminid==349){
            $adminid=297;
        }
        return $adminid;
    }

    /**
     * @param info 中需要teacher_type,teacher_money_type,level,old_level
     */
    public function teacher_level_up_html($info){
        $name          = $info['nick'];
        $level_str     = \App\Helper\Utils::get_teacher_level_str($info);
        $info['level'] = $info['old_level'];
        $level_old_str = \App\Helper\Utils::get_teacher_level_str($info);

        if($level_str=="中级教师"){
            $level_eng="Intermediate Teacher";
        }elseif($level_str=="高级教师"){
            $level_eng="Senior Teacher";
        }elseif($level_str=="金牌教师"){
            $level_eng="Golden Teacher";
        }else{
            $level_eng=" ";
        }

        $star_html = "<img src='http://leowww.oss-cn-shanghai.aliyuncs.com/image/pic_star.png'>";
        $date_begin = date("m月d日0时",time());
        $date       = date("Y年m月d日",time());

        $html="
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf8'>
        <meta name='viewport' content='width=device-width, initial-scale=0.8, maximum-scale=1,user-scalable=true'>
        <style>
         *{margin:0 auto;padding:0 auto;}
         body{opacity:100%;color:#666;font-family:'黑体';}
         html{font-size:10px;}
         .color333{color:#333;}
         .fl{float:left;}
         .fr{float:right;}
         .cl{clear:both;}
         .tl{text-align:left;}
         .tr{text-align:right;}
         .size12{font-size:2.4rem;}
         .size14{font-size:2.8rem;}
         .size18{font-size:3.6rem;}
         .size20{font-size:4rem;}
         .size24{font-size:4.8rem;}
         .size28{font-size:5.6rem;}
         .size36{font-size:7.2rem;}
         .color_red{color:red;}
         .t2em{text-indent:2em;}
         .content{width:700px;}
         .title{margin:6rem 0 2rem;letter-spacing:1rem;}
         .border{border:0.2rem solid #e8665e;border-radius:2rem;margin:4rem 0 2rem;padding:1.2rem 2.2rem 0.8rem 2rem;}
         .tea_name{font-weight:bold;}
         .tea_level{font-weight:bold;}
         .img_position{position:relative;z-index:0;width:100%;}
         .img_level{position:relative;z-index:1;height:0;top:263px;}
         .img_level_eng{position:relative;z-index:1;height:0;top:335px;}
         .img_star{position:relative;z-index:1;height:0;top:390px;}
         .img_name{position:relative;z-index:1;height:0;top:535px;font-family:'Helvetica','方正舒体','华文行楷','隶书';}
         @media screen and (max-width: 720px) {
             .size12{font-size:1.5rem;}
             .size14{font-size:1.75rem;}
             .size18{font-size:2.25rem;}
             .size20{font-size:2.5rem;}
             .size24{font-size:3rem;}
             .size28{font-size:3.5rem;}
             .size36{font-size:4.5rem;}
             .content{width:400px;}
             .img_level{top:140px;}
             .img_level_eng{top:185px;}
             .img_star{top:213px;}
             .img_star img{width:30px;}
             .img_name{top:285px;}
         }
        </style>
    </head>
    <body>
        <div style='width:100%' align='center'>
            <div class='content size14'>
                <div class='title size24'>理优教育</div>
                <div >感谢您一路对我们的支持与信任</div>
                <div class='border tl'>
                    尊敬的<span class='tea_name size18'>".$name."</span>老师，您好！
                    <div class='t2em'>
                        鉴于您在上一季度的教学过程中，工作态度认真负责，教学方法灵活高效，并在学生和家长群体中赢得了广泛好评，
                        达到晋升考核标准（
                        <span class='color_red'>课时量</span>、
                        <span class='color_red'>转化率</span>和
                        <span class='color_red'>教学质量</span>
                        三个考核维度的评分俱皆达标），且无一起有效教学事故类退费或投诉。
                    </div>
                    <div class='t2em'>
                        故公司经研究决定：将您由".$level_old_str."晋升为
                        <span class='tea_level'>".$level_str."</span>。
                        此晋升将于".$date_begin."起即行生效。
                    </div>
                    <div style='text-align:center'>
                        <div class='img_level size24'>
                            <div>".$level_str."</div>
                        </div>
                        <div class='img_level_eng'>
                            <div>".$level_eng."</div>
                        </div>
                        <div class='img_star'>
                            <div>".$star_html."                            </div>
                        </div>
                        <div class='img_name size20'>
                            <div>庄老师</div>
                        </div>
                    </div>
                    <img class='img_position' src='http://leowww.oss-cn-shanghai.aliyuncs.com/image/pic_certificate.png'/>

                    感谢您对公司所做出的积极贡献， 希望您在以后的教学过程中再接再厉、超越自我、不忘初心、不负重托！<br>
                    特此通知!<br>
                    <div class='fr tr'>
                        理优教学管理事业部<br>
                        ".$date."
                    </div>
                    <div class='cl'></div>
                </div>
            </div>
        </div>
    </body>
</html>
";
        return $html;
    }

}