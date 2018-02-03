<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;

/**
 * @use \App\Http\Controllers\Controller
 */
trait TeaPower {
    public function research_fulltime_teacher_lesson_plan_limit($teacherid,$userid,$lesson_count=0,$lesson_start=0,$lesson_type=-1,$lesson_end=0){
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

        $tea_arr   = [107884,53289,78733,59896,55161,164508,190394,176999,240348,211290];
        $day_arr   = [
            "2017-04-02","2017-04-03","2017-04-04","2017-05-01","2017-05-29","2017-05-30","2017-05-28",
            "2017-10-01","2017-10-02","2017-10-03","2017-10-04","2017-10-05","2017-10-06","2017-10-07",
            "2017-10-08"
        ];

        $lesson_start_date = date("Y-m-d",$lesson_start);
        if(empty($lesson_end)){
            $lesson_end = $lesson_count*2400+$lesson_start;
        }
        if($account_role ==4 && !in_array($lesson_start_date,$day_arr)){
            $create_time = $this->t_manager_info->get_create_time($admin_info["uid"]);
            $week_limit_time_info = $this->t_teacher_info->get_week_limit_time_info($teacherid);
            if($create_time<strtotime("2017-10-25")){
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

                if($day==6){
                    if(!empty($lesson_count)){
                        if($week_left < $lesson_count){
                            return $this->output_err("该教研老师周六剩余可排课时为".$week_left);
                        }
                    }
                }
                $res = $this->check_research_teacher_limit_time($lesson_start,$lesson_end,$week_limit_time_info,$date_week);
                if($res){
                    return $res;
                }
                // elseif($day>=2 && $day <=5){
                //     if(!empty($lesson_start)){
                //         if($h <18){
                //             return $this->output_err("教研老师周二至周五只能18点以后排课");
                //         }
                //     }
                // }
            }else{
                //新教研老师规则改变(2017-10-25以后入职)
                //工作时间（周二至周六9:00~18:00）不安排授课
                // if($day>=2 && $day <=5){
                //     if(!empty($lesson_start)){

                //         $lesson_end = $lesson_count*2400+$lesson_start;
                //         $end_h = date("H",$lesson_end);
                //         if($day==3 && $teacherid==428558 && $h>=16){
                //         }else{
                //             if($h <18 && $end_h>=9 ){
                //                 return $this->output_err("教研老师周二至周五9点至18点不能排课");
                //             }
                //         }
                //     }

                // }
                $res = $this->check_research_teacher_limit_time($lesson_start,$lesson_end,$week_limit_time_info,$date_week);
                if($res){
                    return $res;
                }


                //非工作时间（周二至周六18:00以后及周日、周一）每周排课总量不超过6课时；
                if(($lesson_count_week+$lesson_count)>$week_lesson_count){
                    return $this->output_err(
                        "教研老师每周只能带".$week_lesson_count."课时,该老师该周已有".$lesson_count_week."课时!"
                    );
                }
            }
        }elseif($account_role==E\Eaccount_role::V_5 && !in_array($teacherid,$tea_arr)){
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
                    if($limit_type != E\Elimit_plan_lesson_type::V_0 && $limit_type<=$test_lesson_num){
                        return $this->output_err("该全职老师新入职两周内一周限课".$limit_type."节,当前已排".$test_lesson_num."节");
                    }
                }
            }
        }
    }

    public function check_research_teacher_limit_time($lesson_start,$lesson_end,$week_limit_time_info,$date_week){
        $end_h = date("H",$lesson_end);
        $h         = date("H",$lesson_start);
        $day          = intval(($lesson_start-$date_week["sdate"])/86400)+1;
        $day_time = date("Y-m-d",$lesson_start);
        $list = json_decode($week_limit_time_info,true);
        if($list){
            foreach($list as $val){
                if($day==$val["week_num"]){
                    $start= strtotime($day_time." ".$val["start"]);
                    $end= strtotime($day_time." ".$val["end"]);
                    if($lesson_start <$end && $lesson_end >=$start ){
                        return $this->output_err("该教研老师该时间段排课受限制");
                    }


                }
            }
        }

    }

    public function add_teacher_label(
        $sshd_good,$sshd_bad,$ktfw_good,$ktfw_bad,$skgf_good,$skgf_bad,$jsfg_good,$jsfg_bad,
        $teacherid,$label_origin,$lessonid=0,$subject=0,$lessonid_list=""
    ){
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

    public function set_teacher_label($teacherid,$lessonid,$lesson_list,$tea_label_type,$label_origin){
        $arr = json_decode($tea_label_type,true);
        if(!empty($arr)){


            $id = $this->t_teacher_label->check_label_exist($lessonid,$label_origin);
            if($id>0 && $lessonid>0){
                $this->t_teacher_label->field_update_list($id,[
                    "add_time" =>time(),
                    "label_origin"=>$label_origin,
                    "tea_label_type"=>$tea_label_type
                ]);
            }else{
                $this->t_teacher_label->row_insert([
                    "teacherid"=>$teacherid,
                    "add_time" =>time(),
                    "label_origin"=>$label_origin,
                    "lessonid"    =>$lessonid,
                    "lesson_list"=>$lesson_list,
                    "tea_label_type"=>$tea_label_type
                ]);
            }

            $list=[];
            foreach($arr as $v){
                $s =  E\Etea_label_type::get_desc($v);
                if($s=="循循善诱"){
                    $s="鼓励激发";
                }elseif($s=="细致耐心"){
                    $s="耐心细致";
                }elseif($s=="善于互动"){
                    $s="互动引导";
                }elseif($s=="没有口音"){
                    $s="普通话标准";
                }elseif($s=="考纲熟悉"){
                    $s="熟悉考纲";
                }

                $list[$s] = $s;
            }
            //dd($list);
            $teacher_tags = $this->t_teacher_info->get_teacher_tags($teacherid);
            $teacher_tags_list = json_decode($teacher_tags,true);
            if(is_array($teacher_tags_list)){

            }else{
                $tag = trim($teacher_tags,",");
                if($tag){
                    $arr2 = explode(",",$tag);
                    $teacher_tags_list=[];
                    foreach($arr2 as $val){
                        if($val=="循循善诱"){
                            $val="鼓励激发";
                        }elseif($val=="细致耐心"){
                            $val="耐心细致";
                        }elseif($val=="善于互动"){
                            $val="互动引导";
                        }elseif($val=="没有口音"){
                            $val="普通话标准";
                        }elseif($val=="考纲熟悉"){
                            $val="熟悉考纲";
                        }

                        $teacher_tags_list[$val]=1;
                    }

                }else{
                    $teacher_tags_list=[];
                }

            }

            foreach($list as $val){
                if(isset($teacher_tags_list[$val])){
                    $v = $teacher_tags_list[$val]+1;
                }else{
                    $v = 1;
                }
                $teacher_tags_list[$val]=$v;

            }
            // $teacher_tags = trim($teacher_tags,",");
            // $tags= explode(",",$teacher_tags);
            // $str ="";
            // if(empty($tags) || empty($teacher_tags)){
            //     foreach($list as $k){
            //         $str .= $k.",";
            //     }
            // }else{
            //     $tags_list=[];
            //     foreach($tags as $v){
            //         $tags_list[$v]=$v;
            //     }
            //     foreach($list as $k){
            //         if(!isset($tags_list[$k]) && !empty($k)){
            //             $tags[] = $k;
            //         }
            //     }
            //     $str = implode(",",$tags);
            //     $str .= ",";
            // }

            $str = json_encode($teacher_tags_list);
            $this->t_teacher_info->field_update_list($teacherid,[
                "teacher_tags"  =>$str
            ]);


        }
    }


    public function set_teacher_label_new($teacherid,$lessonid,$lesson_list,$tea_tag_arr,$label_origin,$set_flag=1,$per_flag=0){
        $tag_info = json_encode($tea_tag_arr);
        $style_character = json_decode(@$tea_tag_arr["style_character"],true);
        $professional_ability= json_decode(@$tea_tag_arr["professional_ability"],true);
        $classroom_atmosphere= json_decode(@$tea_tag_arr["classroom_atmosphere"],true);
        $courseware_requirements= json_decode(@$tea_tag_arr["courseware_requirements"],true);
        $diathesis_cultivation= json_decode(@$tea_tag_arr["diathesis_cultivation"],true);
        $teacher_tags_old = $this->t_teacher_info->get_teacher_tags($teacherid);
        $teacher_tags_list = json_decode($teacher_tags_old,true);
        $set_adminid = $this->get_account_id();

        if(!empty($tag_info)){
            if($set_flag==1){
                $id = $this->t_teacher_label->check_label_exist($lessonid,$label_origin,$set_adminid);
                if($id>0 && $lessonid>0){
                    $this->t_teacher_label->field_update_list($id,[
                        "add_time" =>time(),
                        "label_origin"=>$label_origin,
                        "tag_info"=>$tag_info
                    ]);
                }else{
                    $this->t_teacher_label->row_insert([
                        "teacherid"=>$teacherid,
                        "add_time" =>time(),
                        "label_origin"=>$label_origin,
                        "lessonid"    =>$lessonid,
                        "lesson_list"=>$lesson_list,
                        "tag_info"    =>$tag_info,
                        "set_adminid"   =>$this->get_account_id(),
                    ]);
                }
                if(is_array($teacher_tags_list)){

                }else{
                    $tag = trim($teacher_tags_old,",");
                    if($tag){
                        $arr = explode(",",$tag);
                        $teacher_tags_list=[];
                        foreach($arr as $val){
                            $teacher_tags_list[$val]=1;
                        }

                    }else{
                        $teacher_tags_list=[];
                    }
                }

                if($per_flag==1){
                    $per_num=0.1;
                }else{
                    $per_num=1;
                }
                foreach($tea_tag_arr as $item){
                    $ret= json_decode($item,true);
                    if(is_array($ret)){
                        foreach($ret as $val){
                            if(isset($teacher_tags_list[$val])){
                                $v = $teacher_tags_list[$val]+$per_num;
                            }else{
                                $v = $per_num;
                            }
                            $teacher_tags_list[$val]=$v;
                        }
                    }

                }

                $teacher_tags = json_encode($teacher_tags_list);
                $this->t_teacher_info->field_update_list($teacherid,[
                    "teacher_tags"  =>$teacher_tags
                ]);


            }elseif($set_flag==2){

                $id = $this->t_teacher_label->check_label_exist_teacherid($teacherid);


                if($id>0){
                    $old_tag = $this->t_teacher_label->get_tag_info($id);
                    $this->t_teacher_label->field_update_list($id,[
                        "add_time" =>time(),
                        "tag_info"=>$tag_info,
                        "set_adminid"   =>$this->get_account_id(),
                    ]);

                    $this->t_teacher_record_list->row_insert([
                        "add_time"  =>time(),
                        "teacherid"  =>$teacherid,
                        "type"      =>15,
                        "acc"       =>$this->get_account(),
                        "record_info"=>$tag_info,
                        "record_monitor_class"=>$old_tag
                    ]);
                    $old_tag_list = json_decode($old_tag,true);
                    foreach($old_tag_list as $item){
                        $ret= json_decode($item,true);
                        if(is_array($ret)){
                            foreach($ret as $val){
                                if(isset($teacher_tags_list[$val])){
                                    $v = $teacher_tags_list[$val]-1;
                                    if($v<=0){
                                        unset($teacher_tags_list[$val]);
                                    }else{
                                        $teacher_tags_list[$val]=$v;
                                    }

                                }
                            }
                        }

                    }


                    foreach($tea_tag_arr as $item){
                        $ret= json_decode($item,true);
                        if(is_array($ret)){
                            foreach($ret as $val){
                                if(isset($teacher_tags_list[$val])){
                                    $v = $teacher_tags_list[$val]+1;
                                }else{
                                    $v = 1;
                                }
                                $teacher_tags_list[$val]=$v;
                            }
                        }

                    }

                    $teacher_tags = json_encode($teacher_tags_list);

                }else{
                    $this->t_teacher_label->row_insert([
                        "teacherid"=>$teacherid,
                        "add_time" =>time(),
                        "label_origin"=>1000,
                        "tag_info"    =>$tag_info,
                        "set_adminid"   =>$this->get_account_id(),
                    ]);

                    if(is_array($teacher_tags_list)){

                    }else{
                        $tag = trim($teacher_tags_old,",");
                        if($tag){
                            $arr = explode(",",$tag);
                            $teacher_tags_list=[];
                            foreach($arr as $val){
                                $teacher_tags_list[$val]=1;
                            }

                        }else{
                            $teacher_tags_list=[];
                        }
                    }

                    foreach($tea_tag_arr as $item){
                        $ret= json_decode($item,true);
                        if(is_array($ret)){
                            foreach($ret as $val){
                                if(isset($teacher_tags_list[$val])){
                                    $v = $teacher_tags_list[$val]+1;
                                }else{
                                    $v = 1;
                                }
                                $teacher_tags_list[$val]=$v;
                            }
                        }

                    }

                    $teacher_tags = json_encode($teacher_tags_list);

                }

                $this->t_teacher_info->field_update_list($teacherid,[
                    "teacher_tags"  =>$teacher_tags
                ]);
            }
        }
    }


    public function get_teacher_label_new($tea_arr){
        $teacher_label_list = $this->t_teacher_label->get_info_by_teacherid(-1,$tea_arr);
        $arr = [];
        foreach($teacher_label_list as $item){
            $teacherid = $item["teacherid"];
            $tea_label_type = json_decode($item["tea_label_type"],true);
            if(!empty($tea_label_type)){
                foreach($tea_label_type as $v){
                    @$arr[$teacherid]["label"][$v]["num"]++;
                    @$arr[$teacherid]["label"][$v]["name"] =E\Etea_label_type::get_desc($v);
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
    // public function get_account_id_by_subject_and_grade($subject,$grade){
    //     if($subject==3){
    //         if($grade >=100 && $grade<200){
    //             return 372;
    //         }else{
    //             return 329;
    //         }
    //     }else if($subject==2){
    //         if($grade >=100 && $grade<200){
    //             return 683;
    //         }elseif($grade >=200 && $grade<300){
    //             return 481;
    //         }else{
    //             return 310;
    //         }

    //     }else if($subject==1){
    //         if($grade >=100 && $grade<200){
    //             return 404;
    //         }else{
    //             return 379;
    //         }

    //     }else if($subject==5){
    //         return 793;
    //     }else if($subject==4){
    //         return 770;
    //     }else{
    //         return 478;
    //     }
    // }

    public function get_account_id_by_subject_and_grade_new($subject,$grade){
        // $ret = $this->t_admin_main_group_name->get_all_memeber_list(4,"小学科");
        $grade = substr($grade,0,1);
        $grade_list = [1=>[1,4],2=>[2,4,5],3=>[3,5,7]];
        $grade_arr = @$grade_list[$grade];

        /* if($subject==3 || $subject==1){
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
            }*/
        //改为张科老师处理
        return 478;
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
            $subject = -1;
            $grade   = -1;
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
            }elseif($adminid==770 || $adminid==1271 ){
                $tea_subject="(4,6)";
            }elseif($adminid==895){
                $tea_subject="";
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

                if($adminid == 329){ // 千千 可以看所有科目
                    $tea_subject = "(1,2,3,4,5,6,7,8,9,10,11)";
                }
            }
        }else if($account_role==5){
            $qz_flag     = 1;
            //叶老师
            if($adminid==480){
                 $tea_right = 1;
            }else{
                $tea_right   = 0;
            }
            $tea_subject = "";
        }elseif($account_role==3){
            $qz_flag=0;
            $tea_right=0;
            $tea_subject = "";
            // if(in_array($adminid,[723,418,343])){
            //     $tea_subject = "(2)";
            // }elseif(in_array($adminid,[1238])){
            //     $tea_subject = "(1)";
            // }elseif(in_array($adminid,[436])){
            //     $tea_subject = "(1,4,5,6,7,8,9,10)";
            // }elseif(in_array($adminid,[434])){
            //     $tea_subject = "(3)";
            // }else{
            //     $tea_subject = "";
            // }
        }else{
            if(in_array($adminid,["72","349","60","186","68","790","448"]) || $account_role==9){
                $tea_right=1;
            }else{
                $tea_right=0;
            }
            $qz_flag     = 0;
            $tea_subject = "";
            // if($adminid==1444){
            //     $tea_subject ="(7,8,9,10)";
            // }
        }

        $acc = $this->t_manager_info->get_account($adminid);
        if($acc=="jim"){
            $tea_right=1;
            $tea_subject="";
            $qz_flag=0;
        }
        if($adminid==480 || $adminid==349){
            $tea_right=1;
            $tea_subject="";
            $qz_flag=0;
        }
        $list = ["tea_subject"=>$tea_subject,"tea_right"=>$tea_right,"qz_flag"=>$qz_flag];
        return $list;
    }

    public function get_admin_tea_subject_and_arr($account_id){
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
        if(in_array($account_id,["72","448","99","486","349","478"])){
            $accept_adminid_list=[];
        }else if(in_array($account_id,$master_adminid)){
           $accept_adminid_list = $this->t_admin_main_group_name->get_adminid_list_by_master_adminid($account_id,4);
        }else{
            $accept_adminid_list=[$account_id];
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
        //新版
        $check_subject = $this->check_test_lesson_grade_subject_new($require_id,$teacherid,$grade,$is_test);

        $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item          = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,userid");
        $tt_item['grade'] = $grade;
        $teacher_subject  = $this->t_teacher_info->field_get_list(
            $teacherid,"subject,second_subject,third_subject,grade_part_ex,second_grade,third_grade,grade_start,grade_end,not_grade,not_grade_limit,limit_plan_lesson_type"
        );
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];

        // if($subject==$teacher_subject["subject"]){
        //     if($teacher_subject['grade_start']==0){
        //         $check_subject = $this->check_teacher_subject_and_grade_new(
        //            $subject,$grade,$teacher_subject["subject"],$teacher_subject["second_subject"],$teacher_subject["third_subject"],
        //            $teacher_subject["grade_part_ex"],$teacher_subject["second_grade"],$teacher_subject["third_grade"],$is_test,
        //            $teacher_subject["not_grade"]
        //         );
        //     }else{
        //         $check_subject = $this->check_teacher_grade_range_new($tt_item,$teacher_subject,$is_test);
        //     }
        // }else{
        //     $check_subject = $this->check_teacher_subject_and_grade_new(
        //         $subject,$grade,$teacher_subject["subject"],$teacher_subject["second_subject"],$teacher_subject["third_subject"],
        //         $teacher_subject["grade_part_ex"],$teacher_subject["second_grade"],$teacher_subject["third_grade"],$is_test,
        //         $teacher_subject["not_grade"]
        //     );
        // }

        if($check_subject){
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

            $teacher_type= $this->t_teacher_info->get_teacher_type($teacherid);
            if($test_lesson_num >=6 && $is_test==0 && $teacher_type !=3){
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

    public function   check_test_lesson_grade_subject_new($require_id,$teacherid,$grade,$is_test){

        $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $tt_item          = $this->t_test_lesson_subject->field_get_list($test_lesson_subject_id,"subject,userid");
        $tea_info  = $this->t_teacher_info->field_get_list($teacherid,"*");
        $userid  = $tt_item["userid"];
        $subject = $tt_item["subject"];

        $stu_grade_range = $this->get_grade_range_new($grade);
        $not_grade       = explode(",",$tea_info['not_grade']);
        $grade_start     = $tea_info['grade_start'];
        $grade_end       = $tea_info['grade_end'];

        if($is_test==0){
            if(!in_array($subject,[$tea_info["subject"],$tea_info["second_subject"]])){
                return $this->output_err("学生科目与老师科目不匹配!");
            }

            if($subject==$tea_info["subject"]){
                if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end){
                    return $this->output_err("学生年级与老师年级范围不匹配!");
                }
                if(in_array($grade,$not_grade)){
                    return $this->output_err("该老师对应年级段已被冻结!");
                }

            }elseif($subject==$tea_info["second_subject"]){
                $not_grade_second       = explode(",",$tea_info['second_not_grade']);
                $grade_start_second     = $tea_info['second_grade_start'];
                $grade_end_second       = $tea_info['second_grade_end'];
                if($stu_grade_range<$grade_start_second || $stu_grade_range>$grade_end_second){
                    return $this->output_err("学生年级与老师年级范围不匹配!");
                }
                if(in_array($grade,$not_grade_second)){
                    return $this->output_err("该老师对应年级段已被冻结!");
                }


            }
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
                    if($grade_part_ex !=2 && $grade_part_ex!=5 && $grade_part_ex!=4 && $grade_part_ex!=7 && $grade_part_ex!=6 ){
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

            // return 1;
        }else{
            // return 1;
        }
    }

    public function check_teacher_grade_range_new($stu_info,$tea_info,$is_test=0){
        $stu_grade_range = $this->get_grade_range_new($stu_info['grade']);
        $not_grade       = explode(",",$tea_info['not_grade']);
        $grade_start     = $tea_info['grade_start'];
        $grade_end       = $tea_info['grade_end'];
        if($is_test==0){


            if($stu_grade_range<$grade_start || $stu_grade_range>$grade_end){
                return $this->output_err("学生年级与老师年级范围不匹配!");
            }
            if($stu_info['subject']!=$tea_info['subject']){
                return $this->output_err("学生科目与老师科目不匹配!");
            }
            if(in_array($stu_info['grade'],$not_grade)){
                return $this->output_err("该老师对应年级段已被冻结!");
            }
        }

        // return 1;
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
        $require_month=["01"=>"18500","02"=>"18500","03"=>"18500","04"=>"18500","05"=>"18500","06"=>"35000","07"=>"18500","08"=>"18500","09"=>"18500","10"=>"18500","11"=>"18500","12"=>"19000"];
        $m = date("m",time());
        $start_time = strtotime(date("Y-m-01",time()));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
        $limit_num = 150;
        if($account_role==2 && $is_green_flag){
            if($master_adminid==287){
                $limit_num= ceil($require_month[$m]*0.027);
            }elseif($master_adminid==416){
                $limit_num= ceil($require_month[$m]*0.009);
            }elseif($master_adminid==364){
                 $limit_num= ceil($require_month[$m]*0.009);
            }

        }else{
            $limit_num= ceil($require_month[$m]*0.026);
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
        $lesson_end = $lesson_start-3600;
        $start = $this->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$lesson_start);
        if($start>0){
            return $this->get_first_lesson_start($teacherid,$start);
        }else{
            return $lesson_start;
        }
    }

    public function get_last_lesson_end($teacherid,$lesson_end){
        $lesson_start = $lesson_end+3600;
        $end_info = $this->t_lesson_info_b2->check_off_time_lesson_end($teacherid,$lesson_end,$lesson_start);
        if(@$end_info["lesson_type"]==2){
            $end = @$end_info["lesson_end"]+1200;
        }else{
            $end = @$end_info["lesson_end"];
        }

        $stop_time = strtotime(date("Y-m-d",$lesson_end))+17*3600;
        if($end>0 && $end<$stop_time){
            return $this->get_last_lesson_end($teacherid,$end);
        }else{
            return $lesson_end;
        }
    }


    public function course_set_new_ex( $require_id, $teacherid,  $lesson_start, $grade,$adminid , $account ) {
        $lesson_end = $lesson_start+2400;
        $orderid    = 1;

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

        $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
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

        $ret_row2 = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
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
     * @param int use_easy_pass 老师账号密码类型 0 随机密码 1 123456 2 leo手机号后4位  目前统一使用类型2
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
            return "该手机号已存在";
        }

        if(!isset($teacher_info["teacher_money_type"])
           || (empty($teacher_info["teacher_money_type"]) && $teacher_info["teacher_money_type"] !=0)
        ){
            $default_teacher_money_type = E\Eteacher_money_type::V_6;
        }else{
            $default_teacher_money_type = $teacher_info["teacher_money_type"];
        }

        \App\Helper\Utils::set_default_value($acc,$teacher_info,"","acc");
        \App\Helper\Utils::set_default_value($wx_use_flag,$teacher_info,0,"wx_use_flag");
        \App\Helper\Utils::set_default_value($trial_lecture_is_pass,$teacher_info,0,"trial_lecture_is_pass");
        \App\Helper\Utils::set_default_value($train_through_new,$teacher_info,0,"train_through_new");
        \App\Helper\Utils::set_default_value($teacher_money_type,$teacher_info,$default_teacher_money_type,"teacher_money_type");
        \App\Helper\Utils::set_default_value($level,$teacher_info,E\Elevel::V_0,"level");
        \App\Helper\Utils::set_default_value($grade,$teacher_info,E\Egrade::V_0,"grade");
        \App\Helper\Utils::set_default_value($subject,$teacher_info,E\Esubject::V_0,"subject");
        \App\Helper\Utils::set_default_value($tea_nick,$teacher_info,$phone,"tea_nick");
        \App\Helper\Utils::set_default_value($realname,$teacher_info,$tea_nick,"realname");
        \App\Helper\Utils::set_default_value($phone_spare,$teacher_info,$phone,"phone_spare");
        \App\Helper\Utils::set_default_value($not_grade,$teacher_info,"","not_grade");
        \App\Helper\Utils::set_default_value($identity,$teacher_info,E\Eidentity::V_0,"identity");
        \App\Helper\Utils::set_default_value($teacher_type,$teacher_info,E\Eteacher_type::V_0,"teacher_type");
        \App\Helper\Utils::set_default_value($teacher_ref_type,$teacher_info,E\Eteacher_ref_type::V_0,"teacher_ref_type");
        \App\Helper\Utils::set_default_value($is_test_user,$teacher_info,0,"is_test_user");
        \App\Helper\Utils::set_default_value($use_easy_pass,$teacher_info,2,"use_easy_pass");
        \App\Helper\Utils::set_default_value($send_sms_flag,$teacher_info,1,"send_sms_flag");
        \App\Helper\Utils::set_default_value($base_intro,$teacher_info,"","base_intro");
        \App\Helper\Utils::set_default_value($grade_start,$teacher_info,E\Egrade_range::V_0,"grade_start");
        \App\Helper\Utils::set_default_value($grade_end,$teacher_info,E\Egrade_range::V_0,"grade_end");
        \App\Helper\Utils::set_default_value($email,$teacher_info,"","email");
        \App\Helper\Utils::set_default_value($school,$teacher_info,"","school");
        \App\Helper\Utils::set_default_value($transfer_teacherid,$teacher_info,0,"transfer_teacherid");
        \App\Helper\Utils::set_default_value($transfer_time,$teacher_info,0,"transfer_time");
        \App\Helper\Utils::set_default_value($interview_access,$teacher_info,"","interview_access");
        \App\Helper\Utils::set_default_value($week_limit_time_info,$teacher_info,"","week_limit_time_info");
        \App\Helper\Utils::set_default_value($week_lesson_count,$teacher_info,18,"week_lesson_count");


        $train_through_new_time = $train_through_new==1?time():0;

        $uid = $this->t_manager_info->get_id_by_phone($phone);
        if($uid>0){
            $del_flag = $this->t_manager_info->get_del_flag($uid);
            if($del_flag!=1){
                $tea_nick = $this->t_manager_info->get_name($uid);
                $realname = $tea_nick;
                $teacher_type     = $teacher_type==0?E\Eteacher_type::V_41:$teacher_type;
                $teacher_ref_type = $teacher_ref_type==0?E\Eteacher_ref_type::V_41:$teacher_ref_type;
            }
        }else{
            $reference      = $this->t_teacher_lecture_appointment_info->get_reference_by_phone($phone);
            $reference_info = $this->t_teacher_info->get_teacher_info_by_phone($reference);
            if(isset($reference_info['teacher_type']) && $reference_info['teacher_type']>20){
                $teacher_ref_type = $reference_info['teacher_ref_type'];
            }
        }

        //999开头的手机号均为测试账号
        if(substr($phone,0,3)=="999"){
            $is_test_user=1;
        }

        $passwd     = \App\Helper\Utils::get_common_passwd($phone,$use_easy_pass);
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
            "week_limit_time_info"   => $week_limit_time_info,
            "week_lesson_count"      => $week_lesson_count
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
     * 修改老师手机号
     * @param int teacherid 老师id
     * @param string phone  老师手机号
     * @return string 错误信息
     */
    public function change_teacher_phone($teacherid,$new_phone,$account=""){
        $role = E\Erole::V_TEACHER;
        $old_phone = $this->t_teacher_info->get_phone($teacherid);
        if($old_phone==$new_phone){
            return $this->output_err("更改的手机号与旧手机号相同!");
        }
        $check_phone = \App\Helper\Utils::check_phone($new_phone);
        if(!$check_phone){
            return $this->output_err("手机号不是11位!");
        }
        $check_flag = $this->t_phone_to_user->check_is_exist_by_phone_and_userid(-1,$new_phone,$role);
        if(!empty($check_flag)){
            return $this->output_err("该账号已存在!");
        }
        $update_tea_arr = [
            "phone"       => $new_phone,
            "phone_spare" => $new_phone,
        ];
        if(substr($new_phone,0,3)=="999"){
            $update_tea_arr["is_test_user"] = 1;
        }

        $this->t_phone_to_user->start_transaction();
        $update_ret = $this->t_phone_to_user->set_phone($new_phone,$role,$teacherid);
        if(!$update_ret){
            $this->t_phone_to_user->rollback();
            return $this->output_err("更新用户表出错！请重试！");
        }
        $tea_ret = $this->t_teacher_info->field_update_list($teacherid,$update_tea_arr);
        if(!$tea_ret){
            $this->t_phone_to_user->rollback();
            return $this->output_err("更新老师表出错！请重试！");
        }

        $this->t_phone_to_user->commit();
        \App\Helper\Utils::logger("update teacher phone success!teacherid:".$teacherid
                                  ." old phone:".$old_phone."new phone:".$new_phone);

        $record_info = "手机变更,由".$old_phone."变更为".$new_phone;
        if($account==""){
            $account = $this->get_account();
        }
        $this->t_teacher_record_list->row_insert([
            'teacherid'   => $teacherid,
            'type'        => E\Erecord_type::V_6,
            'record_info' => $record_info,
            'add_time'    => time(),
            'acc'         => $account,
        ]);
        return true;
    }

    /**
     * 通过手机号设置老师为离职状态
     * @param phone string 手机号
     * @param is_quit int 离职状态 0 未离职 1 已离职
     * @param quit_info string 离职信息
     */
    public function set_teacher_quit_status($phone,$is_quit=0,$quit_info=""){
        $adminid   = session("adminid");
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        if($teacherid>0){
            $old_is_quit = $this->t_teacher_info->get_is_quit($teacherid);
            if($old_is_quit != $is_quit){
                $ret = $this->t_teacher_info->field_update_list($teacherid,[
                    "is_quit"   => $is_quit,
                    "quit_time" => time(),
                    "quit_info" => $quit_info,
                    "quit_set_adminid" => $adminid,
                ]);
                if(!$ret){
                    return $this->output_err("老师离职状态变更失败!");
                }
            }
        }
        return $this->output_succ();
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
        if(in_array($teacher_info['teacher_type'],[E\Eteacher_type::V_32])){
            $update_arr['teacher_type']=E\Eteacher_type::V_0;
        }
        if($appointment_info['full_time']==1){
            $update_arr['teacher_type']=E\Eteacher_type::V_3;
        }
        if($teacher_info['trial_lecture_is_pass']==0){
            $update_arr['trial_lecture_is_pass']=1;
        }
        if($teacher_info['wx_use_flag']==0){
            $update_arr['wx_use_flag']=1;
        }
        if($teacher_info['identity']==E\Eidentity::V_0){
            $update_arr['identity']=$appointment_info['teacher_type'];
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
        $admin_url   = "http://admin.leo1v1.com/human_resource/teacher_lecture_list/?phone=".$teacher_info["phone"];
        $subject_str = E\Esubject::get_desc($teacher_info['subject']);

        foreach($admin_arr as $id => $name){
            $msg_info = $name."老师你好,".$subject_str."学科老师".$teacher_info['nick'].$info_str
                                  .",建议如下:".$teacher_info['reason'];
            $this->t_manager_info->send_wx_todo_msg_by_adminid($id,$from_user,$header_msg,$msg_info,$admin_url);
        }
    }

    public function get_fulltime_teacher_test_lesson_score($teacherid,$start_time,$end_time){
        $qz_tea_arr=[$teacherid];
        $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time,$end_time);

        $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time,$end_time);
        $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time,$end_time);
        $item=[];
        $item["teacherid"] = $teacherid;
        $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
        $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;
        $item["kk_lesson_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["all_lesson"]:0;
        $item["kk_order_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["order_num"]:0;
        $item["hls_lesson_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["all_lesson"]:0;
        $item["hls_order_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["order_num"]:0;
        $item["cc_per"] = !empty($item["cc_lesson_num"])?round($item["cc_order_num"]/$item["cc_lesson_num"]*100,2):0;
        $item["kk_per"] = !empty($item["kk_lesson_num"])?round($item["kk_order_num"]/$item["kk_lesson_num"]*100,2):0;
        $item["hls_per"] = !empty($item["hls_lesson_num"])?round($item["hls_order_num"]/$item["hls_lesson_num"]*100,2):0;
        $item["lesson_all"] = $item["cc_lesson_num"]+$item["kk_lesson_num"]+$item["hls_lesson_num"];
        $item["order_all"] = $item["cc_order_num"]+$item["kk_order_num"]+$item["hls_order_num"];
        $item["all_per"] = !empty($item["lesson_all"])?round($item["order_all"]/$item["lesson_all"]*100,2):0;
        $item["kk_hls_per"] =  !empty($item["kk_lesson_num"]+$item["hls_lesson_num"])?round(($item["kk_order_num"]+$item["hls_order_num"])/($item["kk_lesson_num"]+$item["hls_lesson_num"])*100,2):0;
        $item["cc_score"] = round($item["cc_per"]*0.75,2);
        $item["kk_hls_score"] = round($item["kk_hls_per"]*0.1,2);
        $item["all_score"] = round($item["all_per"]*0.15,2);
        if($item["cc_lesson_num"]>10){
            $cc_num=10;
        }else{
            $cc_num = $item["cc_lesson_num"];
        }
        $item["score"] =  round(($item["cc_score"]+ $item["kk_hls_score"] +$item["all_score"])*$cc_num/10,2);
        return $item["score"];

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
        /*if($adminid==503){
            $adminid = 297;
        }elseif($adminid==512){
            $adminid =702;
        }elseif($adminid==349){
            $adminid=297;
            }*/
        return $adminid;
    }

    /**
     * 老师晋升邮件
     * @param info 中需要teacher_type,teacher_money_type,level
     */
    public function teacher_level_up_html($info){
        $name      = $info['nick'];
        $level_str = \App\Helper\Utils::get_teacher_level_str($info);

        $star_num=0;
        if($level_str=="中级教师" ){
            $level_eng = "Intermediate Teacher";
            $star_num  = 2;
        }elseif($level_str=="高级教师"){
            $level_eng = "Senior Teacher";
            $star_num  = 3;
        }elseif($level_str=="金牌教师"){
            $level_eng="Golden Teacher";
            $star_num=4;
        }else{
            $level_eng=" ";
            if($info["teacher_money_type"]==E\Eteacher_money_type::V_6){
                $star_num = $info["level"]+1;
            }
            if($star_num<1){
                $star_num=1;
            }
        }

        $show_star = "<img src='http://leowww.oss-cn-shanghai.aliyuncs.com/image/pic_star.png'>";
        $star_html = $show_star;
        for($i=2;$i<=$star_num;$i++){
            $star_html.=$show_star;
        }
        // $date_begin = date("m月d日0时",time());
        $date_begin = date("m月1日0时",time());
        $date       = date("Y年m月d日",time());

        if($level_str=="中级教师" || $level_str =="二星级教师"){
            $header_html = "<div class='t2em'>
                                 恭喜您成功通过理优1对1模拟试讲，鉴于您在模拟试讲中态度认真负责，教学方法灵活高效，
                                 达到晋升标准。
                            </div>";
            $group_html = $this->get_new_qq_group_html($info['grade_start'],$info['grade_part_ex'],$info['subject']);
        }else{
            // 旧版说辞 start
            // 鉴于您在上一季度的教学过程中，工作态度认真负责，教学方法灵活高效，并在学生和家长群体中赢得了广泛好评，
            // 达到晋升考核标准（
            // <span class='color_red'>课时量</span>、
            // <span class='color_red'>转化率</span>和
            // <span class='color_red'>教学质量</span>
            // 三个考核维度的评分俱皆达标），且无一起有效教学事故类退费或投诉。
            // 旧版说辞 end

            $header_html = "<div class='t2em'>
                        鉴于您在上一季度的教学过程中，认真负责，积极进取，获得学生家长一致好评，在
                        <span class='color_red'>课时量</span>、
                        <span class='color_red'>学生数</span>、
                        <span class='color_red'>教学质量</span>
                        三个方面皆达到晋升标准。
                    </div>";
            $group_html = "";
        }

        $html = "
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

         .size12{font-size:24px;}
         .size14{font-size:28px;}
         .size18{font-size:36px;}
         .size20{font-size:40px;}
         .size24{font-size:48px;}
         .size28{font-size:56px;}
         .size36{font-size:72px;}
         .hl{line-height:42px;}

         .top-line{margin-top:24px;}
         .bottom-line{margin-bottom:24px;}
         .color_red{color:red;}
         .t2em{text-indent:2em;}
         .content{width:700px;}
         .title{margin:20px 0;}
         .border{border:2px solid #e8665e;border-radius:20px;margin:40px 0 20px;padding:12px 22px 8px 20px;}
         .tea_name{font-weight:bold;}
         .tea_level{font-weight:bold;}
         .img_position{position:relative;z-index:0;width:100%;}
         .img_level{position:relative;z-index:1;height:0;top:263px;}
         .img_level_eng{position:relative;z-index:1;height:0;top:335px;}
         .img_star{position:relative;z-index:1;height:0;top:390px;}
         .img_name{position:relative;z-index:1;height:0;top:535px;font-family:'Helvetica','方正舒体','华文行楷','隶书';}

         @media screen and (max-width: 720px) {
             .size12{font-size:15px;}
             .size14{font-size:17.5px;}
             .size18{font-size:22.5px;}
             .size20{font-size:25px;}
             .size24{font-size:30px;}
             .size28{font-size:35px;}
             .size36{font-size:45px;}
             .content{width:400px;}
             .img_level{top:140px;}
             .img_level_eng{top:185px;}
             .img_star{top:213px;}
             .img_star img{width:30px;}
             .img_name{top:285px;}
             .hl{line-height:26.25px;}
         }
        </style>
    </head>
    <body>
        <div style='width:100%' align='center'>
            <div class='content size14'>
                <div class='logo top-line' align='center'>
                    <img height='50px' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ff214d6936c8911f83b5ed28eba692481496717820241.png'/>
                </div>
                <div class='title size24'>理优教育</div>
                <div >感谢您一路对我们的支持与信任</div>
                <div class='border tl'>
                    尊敬的<span class='tea_name size18'>".$name."</span>老师，您好！
                        ".$header_html."
                    <div class='t2em'>
                        经公司研究决定：将您晋升为
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
                            <div>".$star_html."</div>
                        </div>
                        <div class='img_name size20'>
                            <div>".$name."</div>
                        </div>
                    </div>
                    <img class='img_position' src='http://leowww.oss-cn-shanghai.aliyuncs.com/image/pic_certificate.png'/>
                    ".$group_html."
                    <div >
                    感谢您对公司所做出的积极贡献，希望您在以后的教学过程中再接再厉、超越自我、不忘初心、不负重托！<br>
                    特此通知!<br>
                    </div>
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

    //学员结课,清空常规课表
    public function delete_teacher_regular_lesson($userid,$flag=0,$time=1,$teacherid=-1){
        //$userid = 60022 ;$teacherid= 60011;
        if($flag==1){
            $account="system";
            $time = time();
        }elseif($flag==0){
            $account = $this->get_account();
            $time = time();
        }
        $list1 = $this->t_week_regular_course->get_teacher_student_time($teacherid,$userid);
        $list2 = $this->t_summer_week_regular_course->get_teacher_student_time($teacherid,$userid);
        $list3 = $this->t_winter_week_regular_course->get_teacher_student_time($teacherid,$userid);
        $nick = $this->t_student_info->get_nick($userid);
        $arr_week = [1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];
        $list=[];
        foreach($list1 as $v){
            @$list[$v["teacherid"]][$v["start_time"]]["start_time"]= $v["start_time"];
            @$list[$v["teacherid"]][$v["start_time"]]["end_time"]= $v["end_time"];
            $this->t_week_regular_course->row_delete_2($v["teacherid"],$v["start_time"]);
        }
        foreach($list2 as $v){
            if(!isset($list[$v["teacherid"]][$v["start_time"]])){
                @$list[$v["teacherid"]][$v["start_time"]]["start_time"]= $v["start_time"];
                @$list[$v["teacherid"]][$v["start_time"]]["end_time"]= $v["end_time"];
            }
            $this->t_summer_week_regular_course->row_delete_2($v["teacherid"],$v["start_time"]);
        }
        foreach($list3 as $v){
            if(!isset($list[$v["teacherid"]][$v["start_time"]])){
                @$list[$v["teacherid"]][$v["start_time"]]["start_time"]= $v["start_time"];
                @$list[$v["teacherid"]][$v["start_time"]]["end_time"]= $v["end_time"];
            }
            $this->t_winter_week_regular_course->row_delete_2($v["teacherid"],$v["start_time"]);
        }

        if(!empty($list)){
            foreach($list as $k=>$item){
                \App\Helper\Utils::order_list( $item,"start_time", 1);

                $num_arr=[];
                $str ="";
                foreach($item as $val){
                    $arr=explode("-",$val["start_time"]);
                    $week=$arr[0];
                    $start_time=@$arr[1];
                    $week = $arr_week[$week];

                    if(isset($num_arr[$week])){
                        $num_arr[$week] .=  $start_time."-".$val["end_time"].",";
                    }else{
                        $num_arr[$week]="周".$week.":";
                        $num_arr[$week] .=  $start_time."-".$val["end_time"].",";
                    }

                }
                foreach($num_arr as $s){
                    $s    = trim($s,",");
                    $str .= $s.";";
                }
                $this->t_teacher_record_list->row_insert([
                    "teacherid"   => $k,
                    "type"        => E\Erecord_type::V_11,
                    "record_info" => $str,
                    "add_time"    => $time,
                    "acc"         => $account,
                    "current_acc" => $nick
                ]);
            }
        }
    }

    public function get_full_time_html($data){
        if(time()>strtotime("20107-8-15")){
            $passwd_str = "leo+手机后4位";
        }else{
            $passwd_str = "123456";
        }

        $name = $data['name'];
        $html = "
<html>
    <head>
        <meta charset='utf-8'>
        <style>
         .red{color:#ff3451;}
         .leo_blue{color:#0bceff;}
         body{font-size:24px;line-height:48px;color:#666;}
         .t20{margin-top:20px;}
         .underline{text-decoration:underline;}
         .download-pc-url{cursor:pointer;}
         li{list-style:none;}
         ul{padding-inline-start:0px !important;}
        </style>
    </head>
    <body>
        <div align='center'>
            <div style='width:800px;' align='left'>
                <div align='left'>尊敬的".$name."老师：</div>
                <div class='t20'>
                    感谢您对理优1对1的关注，您的报名申请已收到！<br/>
                    为了更好的评估您的教学能力，需要您尽快按照如下要求进行试讲。<br/>
                    【面试需知】<br/>
                    请下载好<span class='red'>理优老师客户端</span>并准备好<span class='red'>耳机和话筒</span>，
                    用<span class='red'>指定内容</span>在理优老师客户端进行试讲
                </div>
                <div>
                    <ul>
                        <li>
                            1、下载“理优老师端”<a class='leo_blue' href='http://www.leo1v1.com/common/download'>点击下载</a>
                            <br/>
                            （面试请务必使用电脑，暂不支持使用iPad和手机）
                        </li>
                        <li>
                            2、登陆客户端，进行1对1面试试讲<a class='leo_blue' href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
                        进入理优老师客户端预约时间，评审老师和面试老师同时进培训课堂进行面试，
                        面试通过后，进行新师培训，完成自测即可入职<br/>
                        <span class='red'>注意：若面试老师因个人原因需要调整1对1面试时间，请提前1天登陆理优老师端进行修改，以便招师老师安排其他面试，如未提前通知，将视为永久放弃面试机会。</span>
                        </li>
                        <li>
                            3、面试内容<br>
                            1)简单的自我介绍（英语科目请使用英语自我介绍）<br>
                            2)所授课程的PPT讲解<br>
                            <span class='red'>
                                面试账号：{本人报名手机号}<br>
                                密码：$passwd_str <br>
                                时间：请在1周内完成试讲（有特殊原因请及时联系招师老师）<br>
                            </span>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class='t20'>
                        【结果通知】
                    </div>
                    <img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/b6c31d01d41c9e1714958f9c56d01d8f1501149653620.png'/>
                </div>
                <div>
                    <div class='t20'>
                        【通关攻略】
                    </div>
                    <ul>
                        <li>1、确保相对安静的录制环境和稳定的网络环境</li>
                        <li>2、请上传讲义和板书，试讲要充分结合板书</li>
                        <li>3、注意跟学生的互动（模拟形成一种和学生1对1讲解互动的形式）</li>
                        <li>4、简历和PPT完善后需转成PDF格式才能上传</li>
                        <li>5、录制前请先充分准备，面试机会只有一次，要认真对待</li>
                    </ul>
                </div>
                <div class='red'>
                    （温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）
                </div>
                <div >
                    <div class='t20'>
                        【面试步骤】
                    </div>
                    1、备课→2、在线面试→3、复试→4、入职
                </div>
                <div>
                    <div class='t20'>
                        【关于理优】
                    </div>
                    理优1对1致力于为小初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）
                </div>
            </div>
    </body>
</html>
                    ";
        return $html;
    }

    public function set_full_time_teacher($teacherid){
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        if(!in_array($teacher_info['teacher_money_type'],[0,7])){
            $update_arr['teacher_money_type'] = 0;
        }
        if($teacher_info['level']!=0){
            $update_arr['level']=0;
        }
        /* if($teacher_info['train_through_new']==0){
            $update_arr['train_through_new']      = 1;
            $update_arr['train_through_new_time'] = time();
            }*/

        $ret = true;
        if(isset($update_arr) && is_array($update_arr) && !empty($update_arr)){
            $ret = $this->t_teacher_info->field_update_list($teacherid,$update_arr);
        }
        return $ret;
    }

    /**
     * 添加老师的模拟试听
     */
    public function add_trial_train_lesson($teacher_info,$flag=0,$trial_train_num=1){
        $grade    = \App\Helper\Utils::change_grade_end_to_grade($teacher_info);
        $courseid = $this->t_course_order->add_course_info_new(
            0,0,$grade,$teacher_info['subject'],0
            ,1100,1,0,0,0
            ,$teacher_info['teacherid']
        );
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,1,0,0,1100,$teacher_info['teacherid'],0
            ,0,0,$grade,$teacher_info['subject'],100
            ,$teacher_info['teacher_money_type'],$teacher_info['level'],0,2,0
            ,0,1,4
        );
        if($trial_train_num>1){
            $this->t_lesson_info->field_update_list($lessonid,[
               "trial_train_num" => $trial_train_num
            ]);
        }
        $this->t_homework_info->add(0,0,0,$lessonid,$grade,$teacher_info['subject'],$teacher_info['teacherid']);
        $this->t_teacher_record_list->row_insert([
            "teacherid"      => $teacher_info['teacherid'],
            "type"           => E\Erecord_type::V_1,
            "add_time"       => time()+1000,
            "train_lessonid" => $lessonid,
            "lesson_style"   => E\Elesson_style::V_5
        ]);
        if($flag==1){
            if(isset($teacher_info['wx_openid']) && !empty($teacher_info['wx_openid'])){
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
                $data['first']    = "请尽快登录老师后台完成模拟试听";
                $data['keyword1'] = "模拟试听";
                $data['keyword2'] = "尽快登录老师后台,选择模拟试听时间";
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "通过模拟试听即可获得晋升，理优教育致力于打造高水平的教学服务团队，期待您能通过审核，加油！";
                $url = "";

                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$url);
            }
        }elseif($flag==2){
            if(isset($teacher_info['wx_openid']) && !empty($teacher_info['wx_openid'])){
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
                $data['first']    = "请尽快登录老师后台完成模拟试听";
                $data['keyword1'] = "模拟试听";
                $data['keyword2'] = "老师您好,很抱歉您的授课视频因数据不完整导致无法成功上传,请老师重新录制课程,期待老师的课程";
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "通过模拟试听即可获得晋升，理优教育致力于打造高水平的教学服务团队，期待您能通过审核，加油！";
                $url = "";

                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$url);
            }
        }

        return true;
    }

    public function get_zs_accept_adminid($reference){
        if($reference=="99900020010"){
            $accept_adminid = 492;
        }elseif($reference=="99900020011"){
            $accept_adminid = 513;
        }elseif($reference=="99900020014"){
            $accept_adminid = 790;
        }elseif($reference=="99900020015"){
            $accept_adminid = 955;
        }elseif($reference=="99900020017"){
            $accept_adminid = 1000;
        }else{
            $accept_adminid = 0;
            $teacherid = $this->t_teacher_info->get_teacherid_by_phone($reference);
            $zs_id = $this->t_teacher_info->get_zs_id($teacherid);
            if($zs_id>0){
                $accept_adminid = $zs_id;
            }
        }
        return $accept_adminid;
    }

    public function get_zs_reference($accept_adminid){
        if($accept_adminid == 492){
            $reference="99900020010";
        }elseif($accept_adminid == 513){
            $reference="99900020011" ;
        }elseif($accept_adminid == 790){
            $reference="99900020014";
        }elseif($accept_adminid == 955){
            $reference="99900020015";
        }elseif($accept_adminid == 1000){
            $reference="99900020017";
        }else{
            $reference = 1;
        }
        return $reference;
    }

    /**
     * 老师培训通过后的处理操作
     * @param int teacherid
     */
    public function teacher_train_through_deal($teacherid){
        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "train_through_new_time" => time(),
        ]);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_info['level'] = 0;
        $this->send_offer_info($teacher_info);

        $reference_info = $this->t_teacher_info->get_reference_info_by_phone($teacher_info['phone']);
        if(isset($reference_info['teacherid']) && !empty($reference_info['teacherid'])){
            //各类渠道合作的平台总代理，助理不发伯乐奖
            if(!in_array($reference_info['teacher_type'],[E\Eteacher_type::V_21,E\Eteacher_type::V_22,E\Eteacher_type::V_31])){
                $this->add_reference_price($reference_info['teacherid'],$teacherid);
            }

        }
    }

    /**
     * 老师培训通过后的处理操作
     * @param int teacherid
     */
    public function teacher_train_through_deal_2018_1_25($teacherid,$train_through_new_time=0){
        if($train_through_new_time==0){
            $train_through_new_time = time();
        }
        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "train_through_new_time" => $train_through_new_time,
        ]);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_info['level'] = 0;
        $this->send_offer_info($teacher_info);

        $reference_info = $this->t_teacher_info->get_reference_info_by_phone($teacher_info['phone']);
        if(isset($reference_info['teacherid']) && !empty($reference_info['teacherid'])){
            $this->add_reference_price_2018_01_21($reference_info['teacherid'],$teacherid);
        }
    }
    /**
     * 发送入职邮件和入职微信推送
     * @param teacher_info 老师信息
     */
    public function send_offer_info($teacher_info){
        $today_date  = date("Y年m月d日",time());
        $level_str = \App\Helper\Utils::get_teacher_level_str($teacher_info);
        if(isset($teacher_info['email']) && !empty($teacher_info['email']) && strlen($teacher_info['email'])>3){
            $title = "上海理优教研室";
            $html  = $this->get_offer_html($teacher_info);
            $ret   = \App\Helper\Common::send_paper_mail($teacher_info['email'],$title,$html);
        }

        if(isset($teacher_info['wx_openid']) && !empty($teacher_info['wx_openid'])){
            /**
             * 模板ID : 1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II
             * 标题   : 入职邀请通知
             * {{first.DATA}}
             * 职位名称：{{keyword1.DATA}}
             * 公司名称：{{keyword2.DATA}}
             * 入职时间：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
            $data["first"]    = "老师您好，恭喜您已经通过了新师培训测评，等级为：一星级老师。请务必在七天内完成模拟试听课，通过审核后，即获得上课权限！";
            $data["keyword1"] = "教职老师";
            $data["keyword2"] = "上海理优教育";
            $data["keyword3"] = $today_date;
            $data["remark"]   = "";
            $offer_url        = "http://admin.leo1v1.com/common/show_offer_html?teacherid=".$teacher_info["teacherid"];
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
        }
    }

    public function get_offer_html($teacher_info){
        $name       = $teacher_info['nick'];
        $level_str = \App\Helper\Utils::get_teacher_level_str($teacher_info);
        $date_str  = \App\Helper\Utils::unixtime2date(time(),"Y.m.d");
        $html = "
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf8'>
        <meta name='viewport' content='width=device-width, initial-scale=0.8, maximum-scale=1,user-scalable=true'>
        <style>
         *{margin:0 auto;padding:0 auto;}
         body{opacity:100%;color:#666;}
         html{font-size:10px;}
         .color333{color:#333;}
         .fl{float:left;}
         .fr{float:right;}

         .top-line{margin-top:24px;}
         .tea_name{position:relative;z-index:1;top:321px;}
         .tea_level{position:relative;z-index:1;top:410px;}
         .date{position:relative;z-index:1;top:-215px;left:165px;}

         .todo{margin:20px 0 10px 0;}
         .todo li{margin:10px 0;}

         .about_us{margin:30px 0 0;}
         .us_title{margin:0 0 10px;}
         .ul_title{margin:10px 0 0;color:#333;font-size;28px;}

         .join-us{margin:40px 0;}
         .join-us-content{width:44%;}
         .middle-line{
             width:28%;
             height:4rem;
             background:url(http://7u2f5q.com2.z0.glb.qiniucdn.com/7854b16d86652ff547354f84b119d7a51496676904532.png) repeat-x;
             background-position:0 50%;
         }

         .size12{font-size:2.4rem;}
         .size14{font-size:2.8rem;}
         .size18{font-size:3.6rem;}
         .size20{font-size:4rem;}
         .size24{font-size:4.8rem;}
         .content{width:700px;}
         .img_position{position:relative;width:700px;}

         @media screen and (max-width: 720px) {
             .size12{font-size:1.5rem;}
             .size14{font-size:1.75rem;}
             .size18{font-size:2.25rem;}
             .size20{font-size:2.5rem;}
             .size24{font-size:3rem;}
             .content{width:400px;}
             .img_position{width:400px;}
             .tea_name{top:199px;}
             .tea_level{top:241px;}
             .date{top:-135px;left:90px;}
             .middle-line{height:2.5rem;}
         }
        </style>
    </head>
<body>
    <div style='width:100%' align='center'>
        <div class='content'>
            <div class='logo top-line' align='center'>
                <img height='50px' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ff214d6936c8911f83b5ed28eba692481496717820241.png'/>
            </div>
            <div>
                <div class='size24 top-line color:#333'>
                    您的加入,我们期待已久
                </div>
                <div class='size14' style='margin:20px 0 0'>
                    以下是您的理优教育兼职讲师入职通知
                    <br/>
                    请仔细阅读通知书下方待办事项
                </div>
            </div>
            <div>
                <div class='size12' style='line-height:24px'>
                    <name class='tea_name'>".$name."</name>
                    <br/>
                    <level class='tea_level'>老师等级:".$level_str."</level>
                    <img class='img_position' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ae57036b08deb686fc7d52b8463a075e1496669999943.png'>
                     <date class='date'>&nbsp;&nbsp;".$date_str."</date>
                </div>
            </div>
            <div class='todo size12' align='left'>
                <div class='size20 color333'>待办事项</div>
                <div class='ul_title size14 color333'>
                    -理优老师后台链接
                </div>
                <ul>
                    <li>
                        后台连接:<br>
                        http://www.leo1v1.com/login/teacher
                    </li>
                </ul>
            </div>
            <div class='about_us' align='left'>
                <div class='us_title size20 color333'>关于我们</div>
                <div class='size14' style='text-indent:2em'>理优1对1致力于为小初高学生提供专业、专注、有效的教学，帮助更多的家庭打破师资、时间、地域、费用的局限，
                    获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得
                    GGV数千万元A轮投资（GGV风投曾经投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）。
                </div>
                <div class='join-us'>
                    <div class='middle-line fl'></div>
                    <div class='join-us-content size14 color333 fl' align='center'>我们欢迎您的加入</div>
                    <div class='middle-line fr'></div>
                    <div style='clear:both'></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
";
        return $html;
    }

    public function get_new_qq_group_html($grade_start,$grade_part_ex,$subject){
        // 528851744 原答疑1群，人数已满
        if ( $grade_start >= 5 ) {
            $grade = 300;
        } else if ($grade_start >= 3) {
            $grade = 200;
        } else if($grade_start > 0 ) {
            $grade = 100;
        }else if ($grade_part_ex == 1) {
            $grade = 100;
        }else if ($grade_part_ex == 2) {
            $grade = 200;
        }else if ($grade_part_ex == 3) {
            $grade = 300;
        }else{
            $grade = 100;
        }

        $qq_answer = [
            1  => ["答疑语文","126321887","用于薪资，软件等综合问题"],
            2  => ["答疑数学","29759286","用于薪资，软件等综合问题"],
            3  => ["答疑英语","451786901","用于薪资，软件等综合问题"],
            99 => ["答疑综合学科","513683916","用于薪资，软件等综合问题"],
        ];
        $qq_group  = [
            '100' => [
                1=>[
                    ["教研-小学语文","653665526","处理教学相关事务"],
                    ["排课-小学语文","387090573","用于抢课"]
                ],2=>[
                    ["教研-小学数学","644724773","处理教学相关事务"],
                    ["排课-小学数学","527321518","用于排课"],
                ],3=>[
                    ["教研-小学英语","653621142","处理教学相关事务"],
                    ["排课-小学英语","456074027","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ],
            ],
            '200' => [
                1=>[
                    ["教研-初中语文","623708298","处理教学相关事务"],
                    ["排课-初中语文","465023367","用于抢课"]
                ],2=>[
                    ["教研-初中数学","373652928","处理教学相关事务"],
                    ["排课-初中数学","665840444","用于排课"],
                ],3=>[
                    ["教研-初中英语","161287264","处理教学相关事务"],
                    ["排课-初中英语","463756557","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ]
            ],
            '300' => [
                1=>[
                    ["教研-高中语文","653689781","处理教学相关事务"],
                    ["排课-高中语文","573564364","用于抢课"]
                ],2=>[
                    ["教研-高中数学","644249518","处理教学相关事务"],
                    ["排课-高中数学","659192934","用于排课"],
                ],3=>[
                    ["教研-高中英语","456994484","处理教学相关事务"],
                    ["排课-高中英语","280781299","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ]
            ],
        ];

        $list   = @$qq_group[ $grade ][ $subject ] ? $qq_group[ $grade ][ $subject ] : $qq_group[ $grade ][99];
        $list[] = @$qq_answer[ $subject ] ? $qq_answer[ $subject ] : $qq_answer[99];
        $html   = "<div>加入相关QQ群(请备注 科目-年级-姓名)";
        foreach($list as $val){
            $html .= "<div>[LEO]".$val[0]."<br>群号码：".$val[1]."<br>群介绍：".$val[2]."</div>";
        }
        $html .= "</div><br>";
        return $html;
    }


    public function add_tran_stu($phone,$subject,$origin_assistantid,$grade,$nick,$origin_userid=2,$region_version,$notes){
        $origin="转介绍";
        $has_pad=0;
        $userid=$this->t_seller_student_new->book_free_lesson_new(
            $nick,$phone,$grade,$origin,$subject,$has_pad);
        //处理
        $this->t_student_info->field_update_list($userid,[
            "originid"           => 1,
            "origin_assistantid" =>  $origin_assistantid,
            "origin_userid"      => $origin_userid,
            "reg_time" => time(NULL),
            "editionid"=>$region_version
        ]);
        $this->t_seller_student_new->field_update_list($userid,[
            "user_desc"           => $notes,
        ]);


        $account= $this->get_account();

        $origin_assistant_nick = $this->cache_get_account_nick($origin_assistantid);

        $origin_nick="客服";
        $this->t_book_revisit->add_book_revisit(
            $phone,
            "操作者: $account , 负责人: [$origin_assistant_nick] 转介绍  来自:[$origin_nick] ",
            "system"
        );

        //分配给原来的销售
        $admin_revisiterid= $this->t_order_info-> get_last_seller_by_userid($origin_userid);
        //$admin_revisiterid= $origin_assistantid;

        if ($admin_revisiterid) {
            $this->t_seller_student_new->set_admin_info(0,[$userid],$admin_revisiterid,$admin_revisiterid);
            $nick=$this->t_student_info->get_nick($userid);
            $this->t_manager_info->send_wx_todo_msg_by_adminid($admin_revisiterid,"转介绍","学生[$nick][$phone]","","/seller_student_new/seller_student_list_all?userid=$userid");
        }

    }

    public function get_admin_subject($adminid,$flag){
        $subject=-1;
        if($flag==1){
            if(in_array($adminid,[913])){
                $subject=1;
            }elseif(in_array($adminid,[892,754,683])){
                $subject=2;
            }elseif(in_array($adminid,[923])){
                $subject=-1;
            }elseif(in_array($adminid,[793])){
                $subject=-5;
            }else if(in_array($adminid,[770,1271])){
                $subject=12;
            }elseif(in_array($adminid,[478])){
                $subject=-1;
            }elseif(in_array($adminid,[895])){
                $subject=13;
            }elseif(in_array($adminid,[790])){
                $subject=14;
            }else{
                $subject=-1;
            }

        }elseif($flag==2){
            if(in_array($adminid,[379,404,868,849])){
                $subject=1;
            }elseif(in_array($adminid,[310,890,866])){
                $subject=2;
            }elseif(in_array($adminid,[372,329])){
                $subject=3;
            }elseif(in_array($adminid,[793])){
                $subject=-5;
            }else if(in_array($adminid,[770,1271])){
                $subject=12;
            }elseif(in_array($adminid,[478])){
                $subject=10;
            }elseif(in_array($adminid,[895])){
                $subject=13;
            }else{
                $subject=-1;
            }

        }
        return $subject;
    }

    // public function check_is_special_reference($phone){
    //     //田克平
    //     if($phone=="13387970861"){
    //         $check_flag=1;
    //     }else{
    //         $check_flag=0;
    //     }
    //     return $check_flag;
    // }

    // /**
    //  * 获取老师的高校生/在校老师的推荐数量
    //  * @param phone 推荐人手机号
    //  * @param identity 被推荐人身份
    //  */
    // public function get_teacher_reference_price($phone,$identity){
    //     $reference_type = \App\Config\teacher_rule::check_reference_type($identity);
    //     $check_flag     = $this->check_is_special_reference($phone);
    //     if($check_flag){
    //         $begin_time = 0;
    //     }else{
    //         $begin_date = \App\Helper\Config::get_config("teacher_ref_start_time");
    //         $begin_time = strtotime($begin_date);
    //     }

    //     $ref_num = $this->t_teacher_lecture_appointment_info->get_reference_num(
    //         $phone,$reference_type,$begin_time
    //     );
    //     $ref_price = \App\Helper\Utils::get_reference_money($identity,$ref_num);
    //     return $ref_price;
    // }

    /**
     * 获取老师上月累计课时
     * @param teacher_money_type 老师工资类型
     * @param teacherid  老师id
     * @param start_time 本月开始时间
     * @param end_time   本月结束时间
     * @return int       累计课时
     */
    public function get_already_lesson_count($start_time,$end_time,$teacherid,$teacher_money_type=0){
        $last_start_time = strtotime("-1 month",$start_time);
        $last_end_time   = strtotime("-1 month",$end_time);
        $already_lesson_count = $this->t_lesson_info->get_teacher_last_month_lesson_count(
            $teacherid,$last_start_time,$last_end_time,$teacher_money_type
        );
        return $already_lesson_count;
    }

    /**
     * 获取老师上个月的累计常规课时和累计常规+试听课时
     * @param int start_time 本月开始时间
     * @param int end_time   本月结束时间
     * @return array all_lesson_count 上月累计常规+试听课时  all_normal_count 上月累计常规课时
     */
    public function get_last_lesson_count_info($start_time,$end_time,$teacherid){
        $transfer_teacherid = $this->t_teacher_info->get_transfer_teacherid($teacherid);
        $last_lesson_count['all_lesson_count'] = $this->get_already_lesson_count(
            $start_time,$end_time,$teacherid,0
        );
        $last_lesson_count['all_normal_count'] = $this->get_already_lesson_count(
            $start_time,$end_time,$teacherid,E\Eteacher_money_type::V_6
        );
        if($transfer_teacherid>0){
            $old_all_lesson_count = $this->get_already_lesson_count($start_time,$end_time,$transfer_teacherid);
            $old_normal_lesson_count = $this->get_already_lesson_count(
                $start_time,$end_time,$transfer_teacherid,E\Eteacher_money_type::V_6
            );
            $last_lesson_count['all_lesson_count']+= $old_all_lesson_count;
            $last_lesson_count['all_normal_count']+= $old_normal_lesson_count;
        }
        return $last_lesson_count;
    }

    /**
     * 获取课程对应的累计课时
     * @param last_lesson_count array ['all_lesson_count'] 上月累计常规+试听课时
     *                                ['all_normal_count'] 上月累计常规课时
     * @param lesson_already_lesson_count t_lesson_info中课程上的累计课时
     * @param teacher_money_type t_lesson_info中课程上的老师工资类型
     * @param teacher_type t_teacher_info中老师类型
     * @return int 课程计算的累计课时
     */
    public function get_lesson_already_lesson_count(
        $last_lesson_count,$lesson_already_lesson_count,$teacher_money_type,$teacher_type
    ){
        $check_type = \App\Helper\Utils::check_teacher_money_type($teacher_money_type,$teacher_type);
        switch($check_type){
        case 1: case 3:
            $already_lesson_count = $lesson_already_lesson_count;
            break;
        case 2:
            $already_lesson_count = $last_lesson_count['all_lesson_count'];
            break;
        case 4:
            $already_lesson_count = $last_lesson_count['all_normal_count'];
            break;
        default:
            $already_lesson_count = 0;
            break;
        }
        return $already_lesson_count;
    }

    /**
     * 获取常规课程的课时奖励
     * @param last_lesson_count array key/all_lesson_count 上月累计常规+试听课时 key/all_normal_count 上月累计常规课时
     * @param lesson_already_lesson_count t_lesson_info 中课程上的累计课时
     * @param teacher_money_type t_lesson_info 中课程上的老师工资类型
     * @param teacher_type t_teacher_info 中老师类型
     * @param type 老师工资类型对应的课时奖励类型
     */
    public function get_lesson_reward_money(
        $last_lesson_count,$lesson_already_lesson_count,$teacher_money_type,$teacher_type,$reward_type
    ){
        $already_lesson_count = $this->get_lesson_already_lesson_count(
            $last_lesson_count,$lesson_already_lesson_count,$teacher_money_type,$teacher_type
        );
        $reward_money = \App\Helper\Utils::get_teacher_lesson_money($reward_type,$already_lesson_count);
        return $reward_money;
    }

    /**
     * 检测是否为公司全职老师,全职老师工资隔月发放
     * 叶,时,刁除外
     */
    public function check_full_time_teacher($teacherid,$teacher_type){
        if(!in_array($teacherid,[51094,99504,97313]) && $teacher_type==3){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取平台合作代理所需抽成百分比
     * @param time 检测时间之前
     * @param teacher_ref_type 老师所属的推荐渠道类别
     * @return
     */
    public function get_teacher_ref_rate($time,$teacher_ref_type,$teacher_money_type){
        $teacher_ref_rate = 0;
        if($teacher_money_type == E\Eteacher_money_type::V_5){
            if($teacher_ref_type==1){
                $teacher_ref_rate = \App\Helper\Config::get_config_2("teacher_ref_rate",$teacher_ref_type);
            }elseif($teacher_ref_type!=0){
                $teacher_ref_num  = $this->t_teacher_info->get_teacher_ref_num($time,$teacher_ref_type);
                $teacher_ref_rate = \App\Helper\Utils::get_teacher_ref_rate($teacher_ref_num);
            }
        }
        return $teacher_ref_rate;
    }

    /**
     * 获取时间段内老师额外奖金明细
     * @param int teacherid 老师id
     * @param int start_time 开始时间
     * @param int end_time 结束时间
     * @return array
     */
    public function get_teacher_reward_money_list($teacherid,$start_time,$end_time){
        $reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$start_time,$end_time);
        $reward_type_list = E\Ereward_type::$desc_map;
        $data = [];
        foreach($reward_type_list as $r_key => $r_val){
            $data[$r_key]['money'] = 0;
        }
        foreach($reward_list as $val){
            $reward_key   = $val['type'];
            $reward_money = $val['money']/100;
            $data[$reward_key]['money'] += $reward_money;
        }
        $data['list'] = $reward_list;

        return $data;
    }

    /**
     * 获取时间段内老师额外奖金明细
     * @param int teacherid 老师id
     * @param int start_time 开始时间
     * @param int end_time 结束时间
     * @return array
     */
    public function get_teacher_reward_money_list_new($teacherid,$start_time,$end_time){
        $reward_list      = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$start_time,$end_time);
        $reward_type_list = E\Ereward_type::$desc_map;
        $data = [];
        foreach($reward_list as $val){
            $reward_key   = $val['type'];
            $reward_money = $val['money']/100;
            \App\Helper\Utils::check_isset_data($data[$reward_key]['money'],$reward_money);
        }

        return $data;
    }

    /**
     * 获取模拟课时单价
     */
    public function get_simulate_price($lesson_total=0,$grade=101){
        if($grade<200){
            $grade = 101;
        }

        $price_config = \App\OrderPrice\order_price_20170701::$grade_price_config;

        $last_per_price = 0;
        foreach($price_config[$grade] as $key=>$val ){
            if($lesson_total>=$key){
                $last_per_price = $val;
            }else{
                break;
            }
        }

        return $last_per_price;
    }


    //获取质监/教研 空闲时间
    public function get_not_free_time_list($subject,$grade){
        $start_time = strtotime(date("Y-m-d 9:00",time()));
        $end_time   = strtotime("+1 week",strtotime(date("Y-m-d 20:00",time())));

        if($subject==0 || $grade==0){
            return $this->output_err("请选择正确的年级和科目");
        }
        $teacherid_str  = $this->get_check_teacherid_str($subject,$grade);
        $teacherid_list = $this->t_teacher_info->get_admin_teacher_list_new($subject,$grade);
        $lesson_list    = $this->t_lesson_info->get_not_free_lesson_list($start_time,$end_time,$teacherid_str);

        $free_list = [];
        if(is_array($lesson_list) && !empty($lesson_list)){
            $lesson_data = [];
            foreach($teacherid_list as $t_key=>$t_val){
                \App\Helper\Utils::check_isset_data($lesson_data[$t_key],[],0);
                $this->get_tea_rearch_time_block($lesson_data[$t_key],$t_val['account_role']);
                foreach($lesson_list as $l_val){
                    if($l_val['teacherid']==$t_key){
                        $this->change_time_to_range($lesson_data[$t_key],$l_val['lesson_start'],$l_val['lesson_end']);
                    }
                }
            }
            foreach($lesson_data as $l2_key=>$l2_val){
                if(empty($free_list)){
                    $free_list = $l2_val;
                }else{
                    $free_list = array_intersect($free_list,$l2_val);
                }
            }
            $free_list = array_values(array_unique($free_list));
        }

        $free_time_list = [];
        if(!empty($free_list)){
            foreach($free_list as $time_val){
                $time=explode("~",$time_val);
                $free_time_list[] = [
                    "lesson_start" => $time[0],
                    "lesson_end"   => $time[1],
                ];
            }
        }

        return $free_time_list;
    }

    public function get_check_teacherid_str($subject,$grade){
        $teacherid_list = $this->t_teacher_info->get_admin_teacher_list_new($subject,$grade);
        $teacherid_str  = \App\Helper\Utils::array_keys_to_string($teacherid_list,",");

        return $teacherid_str;
    }

    /**
     * 教研未来一周只有周二能排课
     * 质监只有周二到周六能排课
     * 中午12:30~13:00都不能排课
     * @param account_role 4 教研 9 质监
     */
    public function get_tea_rearch_time_block(&$lesson_data,$account_role){
        $start_date = date("Y-m-d 9:00",time());
        $half_start = date("Y-m-d 12:30",time());
        $half_end   = date("Y-m-d 13:00",time());
        $night_start= date("Y-m-d 18:00",time());
        $end_date   = date("Y-m-d 20:00",time());
        for($i=1;$i<=7;$i++){
            $start_day       = strtotime("+".$i." day",strtotime($start_date));
            $half_start_day  = strtotime("+".$i." day",strtotime($half_start));
            $half_end_day    = strtotime("+".$i." day",strtotime($half_end));
            $night_start_day = strtotime("+".$i." day",strtotime($night_start));
            $end_day         = strtotime("+".$i." day",strtotime($end_date));

            $week_day = date("w",$start_day);
            if($account_role==E\Eaccount_role::V_4){
                if($week_day != 2){
                    $this->change_time_to_range($lesson_data,$start_day,$end_day);
                }
            }elseif($account_role==E\Eaccount_role::V_9){
                if(in_array($week_day,[0,1])){
                    $this->change_time_to_range($lesson_data,$start_day,$end_day);
                }
            }
            $this->change_time_to_range($lesson_data,$half_start_day,$half_end_day);
            $this->change_time_to_range($lesson_data,$night_start_day,$end_day);
        }
    }

    public function change_time_to_range(&$lesson_data,$lesson_start,$lesson_end){
        for(;$lesson_start<=$lesson_end;){
            if($lesson_start==$lesson_end){
                break;
            }
            $time_range=$this->get_time_block($lesson_start);
            array_push($lesson_data,$time_range);
            $lesson_start += 1800;
        }
    }

    /**
     * 把时间戳转化成时间范围
     */
    public function get_time_block($time){
        $time_prefix      = date("Y-m-d H",$time);
        $time_prefix_now  = date("H",$time);
        $time_prefix_next = date("H",($time+3600));
        $time_minute      = date("i",$time);

        if($time_minute>=30){
            $time_range = $time_prefix.":30~".$time_prefix_next.":00";
        }else{
            $time_range = $time_prefix.":00~".$time_prefix_now.":30";
        }
        return $time_range;
    }


    public function get_train_lesson_teacherid($subject,$grade,$lesson_start){
        $lesson_end = $lesson_start+1800;
        $week_day = date("w",$lesson_start);
        $role_str = "9";
        $teacherid_list = $this->t_teacher_info->get_teacherid_by_role($role_str,$subject,$grade);
        $teacherid_str  = \App\Helper\Utils::array_keys_to_string($teacherid_list);

        $teacherid_has  = $this->t_lesson_info->get_teacherid_for_free_time_by_lessonid($lesson_start,$lesson_end,$teacherid_str);
        $teacherid_has_str = \App\Helper\Utils::array_keys_to_string($teacherid_has);

        $teacherid_free = str_replace($teacherid_has_str,"",$teacherid_str);
        $teacherid_free_arr = array_values(array_filter(explode(",",$teacherid_free)));
        return $teacherid_free_arr;
    }

    public function delete_train_lesson_before($lessonid,$subject,$grade,$teacherid){
        $list = $this->t_lesson_info_b2->get_train_lesson_before($lessonid,$subject,$grade,$teacherid);
        if(!empty($list)){
            foreach($list as $val){
                $this->t_lesson_info->field_update_list($val["lessonid"],[
                   "lesson_del_flag"  =>1
                ]);
            }
        }
    }

    /**
     * 老师报名邮件
     * @param name 老师姓名
     * @return String
     */
    public function get_email_html_new($name=""){
        $html = "
<html>
    <head>
        <meta charset='utf-8'>
        <style>
         .red{color:#ff3451;}
         .leo_blue{color:#0bceff;}
         .download_blue{color:rgb(0,0,238)}
         body{font-size:24px;line-height:48px;color:#666;}
         .t20{margin-top:20px;}
         .underline{text-decoration:underline;}
         .download-pc-url{cursor:pointer;}

        </style>
    </head>
    <body>
        <div align='center'>
            <div style='width:1000px;' align='left'>
                <div align='left'>尊敬的".$name."老师：</div>
                <div class='t20'>
                    感谢您对理优1对1的关注，您的报名申请已收到！
                    <br/>
                    请您尽快使用我们准备的试讲内容进行面试，祝您面试成功！
                    <br/>
                    <br/>
                    【轻松搞定面试】
                    <br/>
                    <br/>
                    一：理优老师客户端&nbsp;<a class='download_blue' href='http://www.leo1v1.com/common/download'>客户端下载</a>（面试必须使用电脑，并准备好耳机和话筒。以后正式上课可用iPad授课）<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class='red'>登录账号：注册报名的手机号</span><br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class='red'>登录密码：leo+手机号后四位</span><br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;下载简历模板，填写并上传在“理优老师客户端”&nbsp;<a class='download_blue' href='http://leowww.oss-cn-shanghai.aliyuncs.com/JianLi.docx'>简历模板下载</a><br>
                    <br/>
                    ​二：登陆客户端，选择试讲方式（试讲方式只能二选一，请老师选择适合自己的方式<span class='red'>↓↓↓</span>）<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1)录制试讲<a class='download_blue' href='http://file.leo1v1.com/index.php/s/JtvHJngJqowazxy'>试讲题目及视频教程←点击下载</a>（无需摄像头，录制只会录制软件界面和声音）<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;用指定试讲内容录制试讲视频，录制完成提交审核，五个工作日内将会收到审核结果<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class='red'>特点：提交前可反复回看并重新录制（提交后不可重新录制），回看满意后再提交</span><br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2)面试试讲<a class='download_blue' href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>试讲题目及视频教程←点击下载</a>（无需摄像头，录制只会录制软件界面和声音）<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;进入理优老师客户端预约时间，评审老师和面试老师同时进入培训课堂进行面试，用指定试讲内容进行一对一在线面试。<br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class='red'>特点：可以把面试官当您的学生进行互动。</span><br>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class='leo_blue'>目前物理、化学、政治、历史、地理、生物、科学七门学科不支持面试试讲，只能选择录制试讲。</span><br>
                    <br/>
                </div>
                <div>
                    <div class='t20'>
                        【通关攻略】
                    </div>
                    <ol>
                        <li>确保安静的环境和稳定的网络</li>
                        <li>试讲过程中必须要结合题目进行板书</li>
                        <li>网络课堂互动很重要，与学生进行模拟互动（假设电脑另一端坐着你的学生）</li>
                        <li>试讲内容PPT完善后需转成PDF格式才能上传</li>
                        <li>录制和面试试讲前请先充分准备，认真对待</li>
                    </ol>
                    <div class='red'>（温馨提示：讲题前先在页面画一笔，再开始讲解，有助于保持声音和画面同步）</div>
                </div>
                <div>
                    <div class='t20'>
                        【入职流程】
                    </div>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1、备课→2、试讲→3、培训→4、入职
                </div>
                <div >
                    <div class='t20'>
                        【面试结果通知】
                    </div>
                    <img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/b6c31d01d41c9e1714958f9c56d01d8f1501149653620.png'/><br>
                </div>
                <div >
                    <div class='t20'>
                        【联系我们】
                    </div>
                    <img  src='http://7u2f5q.com2.z0.glb.qiniucdn.com/cc88764ab0e165ab2c909ec0b3f3a0a11507778329047.png'/><br>
                    如有其它疑问，请联系教务老师 <span class='red'>QQ:1689916647</span>
                </div>
                <div>
                    <div class='t20'>
                        【岗位介绍】
                    </div>
                    名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）
                    <br/>
                    时薪：50-100RMB
                </div>
                <div>
                    <div class='t20'>
                        【关于理优】
                    </div>
                    理优1对1致力于为小初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）
                </div>
            </div>
    </body>
</html>
";
        return $html;
    }


    public function get_low_grade($grade){
        switch($grade){
        case 102:case 103:case 104:case 105:case 106:case 202:case 203:case 302:case 303:
            $grade_new=$grade-1;
            break;
        case 201:
            $grade_new=106;
            break;
        case 301:
            $grade_new=203;
            break;
        case 401:
            $grade_new=303;
            break;
        default:
            $grade_new=0;
        }
        return $grade_new;

    }

    public function  get_textbook_str($subject,$grade){
        $str="other";
        if($subject==1){
            if($grade==100){
                $str ="yw_primary";
            }elseif($grade==200){
                $str ="yw_middle";
            }elseif($grade==300){
                $str ="yw_senior";
            }

        }elseif($subject==2){
            if($grade==100){
                $str ="sx_primary";
            }elseif($grade==200){
                $str ="sx_middle";
            }elseif($grade==300){
                $str ="sx_senior";
            }

        }elseif($subject==3){
            if($grade==100){
                $str ="yy_primary";
            }elseif($grade==200){
                $str ="yy_middle";
            }elseif($grade==300){
                $str ="yy_senior";
            }

        }elseif($subject==4){
            if($grade==200){
                $str ="hx_middle";
            }elseif($grade==300){
                $str ="hx_senior";
            }

        }elseif($subject==5){
            if($grade==200){
                $str ="wl_middle";
            }elseif($grade==300){
                $str ="wl_senior";
            }
        }

        return $str;
    }

    /**
     * 重置老师的课程工资信息
     * teacher_money_type 和 level
     */
    public function reset_teacher_money_info($teacherid,$check_time=0){
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        if($check_time==0){
            $check_time = time();
        }
        $lesson_num = $this->t_lesson_info_b3->get_lesson_count_by_teacherid($teacherid,$check_time);
        if($lesson_num>0){
            $ret = $this->t_lesson_info_b3->reset_lesson_teacher_info(
                $teacherid,$teacher_info['teacher_money_type'],$teacher_info['level'],$check_time
            );
        }else{
            $ret = true;
        }
        return $ret;
    }

    public function get_test_lesson_comment_str($str,$flag=0){
        $arr  = json_decode($str,true);
        $data = "";
        if($flag==1){
            if(empty($arr)){
                $data="";
            }else{
                $data = "试听情况:".@$arr["stu_lesson_content"].";学习态度:".@$arr["stu_lesson_status"].";学习基础情况:".$arr["stu_study_status"].";学生优点:".$arr["stu_advantages"].";学生有待提高:".$arr["stu_disadvantages"].";培训计划:".$arr["stu_lesson_plan"].";教学方向:".$arr["stu_teaching_direction"].";意见、建议等:".$arr["stu_advice"];
            }
        }else{
            foreach($arr as $k=>$v){
                $data .= @$v["stu_tip"]." ".@$v["stu_info"].";";
            }
        }
        return $data;
    }

    //查询百度有钱花订单信息
    public function get_baidu_money_charge($orderid){
        $url = 'https://umoney.baidu.com/edu/openapi/post';
        //  $orderid = $this->get_in_int_val("orderid",516);

        $orderNo = $this->t_child_order_info->get_from_orderno($orderid);
        if(empty($orderNo)){
            $orderNo=123456789;
        }

        $arrParams = array(
            'action' => 'get_order_status',
            'tpl' => 'leoedu',// 分配的tpl
            'corpid' => 'leoedu',// 分配的corpid
            'orderid' => $orderNo,// 机构订单号
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
        return $result;

    }



    //查询百度有钱花订单还款信息
    public function get_baidu_money_charge_pay_info($orderid){
        $url = 'https://umoney.baidu.com/edu/openapi/post';
        //  $orderid = $this->get_in_int_val("orderid",516);

        $orderNo = $this->t_child_order_info->get_from_orderno($orderid);
        if(empty($orderNo)){
            $orderNo=726749100101;
        }

        $arrParams = array(
            'action' => 'get_order_info',
            'tpl' => 'leoedu',// 分配的tpl
            'corpid' => 'leoedu',// 分配的corpid
            'orderid' => $orderNo,// 机构订单号
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
        return $result;

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


    //老师晋升,获取前4个季度的列表
    public function get_four_season_list(){
        $list=[];
        //上季度
        $season = ceil((date('n'))/3)-1;
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $year = date("Y",$start_time);
        $m = date("m",$start_time);
        $md = date("m",$start_time+100*86400);
        $list[$start_time]=$year." ".$m."-".$md;

        //上上季度
        $season_pre = ceil((date('n'))/3)-2;
        $start_time_pre = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season_pre*3-3+1,1,date('Y'))));
        $year = date("Y",$start_time_pre);
        $m = date("m",$start_time_pre);
        $md = date("m",$start_time_pre+100*86400);
        $list[$start_time_pre]=$year." ".$m."-".$md;


        //上上上季度
        $season_se = ceil((date('n'))/3)-3;
        $start_time_se = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season_se*3-3+1,1,date('Y'))));
        $year = date("Y",$start_time_se);
        $m = date("m",$start_time_se);
        $md = date("m",$start_time_se+100*86400);
        $list[$start_time_se]=$year." ".$m."-".$md;


        //上上上上季度
        $season_le = ceil((date('n'))/3)-4;
        $start_time_le = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season_le*3-3+1,1,date('Y'))));
        $year = date("Y",$start_time_le);
        $m = date("m",$start_time_le);
        $md = date("m",$start_time_le+100*86400);
        $list[$start_time_le]=$year." ".$m."-".$md;

        return $list;
    }

    /**
     * 检测非测试老师是否成为正式老师
     */
    public function check_teacher_is_pass($teacherid){
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        if($teacher_info['is_test_user']==0){
            if($teacher_info['trial_lecture_is_pass']==0
               || $teacher_info['train_through_new']==0
               || $teacher_info['wx_use_flag']==0
            ){
                return false;
            }
        }
        return true;
    }


    //百度分期用户首月排课限制/非首次逾期还款排课限制
    public function check_is_period_first_month($userid,$lesson_count){
        $period_info = $this->t_child_order_info->get_period_info_by_userid($userid);

        //当期还款时间
        $d= date("d");
        if($d>15){
            $month_start = strtotime(date("Y-m-01",time()));
            $due_date = $month_start+14*86400;
        }else{
            $last_month = strtotime("-1 month",time());
            $month_start = strtotime(date("Y-m-01",$last_month));
            $due_date = $month_start+14*86400;
        }
        $no_first_overdue_flag=0;

        if($period_info){
            $data = $this->get_baidu_money_charge_pay_info($period_info["child_orderid"]);
            $pay_price=0;
            if($data && $data["status"]==0){
                $data = $data["data"];
                foreach($data as $val){
                    if($val["bStatus"]==48){
                        $pay_price +=$val["paidMoney"];
                    }
                    if($val["dueDate"]==$due_date && $val["period"]>1 && $val["bStatus"]==144){
                        $no_first_overdue_flag=1;
                    }

                }
            }
            $pay_price +=$period_info["price"]-$period_info["period_price"];
            $per_price = $period_info["discount_price"]/$period_info["default_lesson_count"]/$period_info["lesson_total"];
            $lesson_count_plan = floor($pay_price/$per_price/100+3*3);
            $order_lesson_left_pre = $this->t_order_info->get_order_lesson_left_pre($userid,$period_info["order_time"]);
            $flag = ((time()-$period_info["pay_time"])<30*86400)?1:0;


            if(empty($order_lesson_left_pre) && $flag){
                $day_start = strtotime(date("Y-m-d 05:00:00",time()));
                $day_end = $period_info["pay_time"]+30*86400;
                $lesson_use = $this->t_lesson_info_b3->get_lesson_count_sum($userid,$day_start,$day_end);
                $order_use =  $period_info["default_lesson_count"]*$period_info["lesson_total"]-$period_info["lesson_left"];
                $all_plan = ($lesson_use+$order_use)/100;
                $plan_flag = (($all_plan+$lesson_count/100)>$lesson_count_plan)?1:0;
                if($plan_flag){
                    return $this->output_err("分期用户,已超限,该时间段不能排课!");
                }

            }


            //非首次逾期还款排课限制

            //先确认是否为当期逾期未还款(非首次)用户
            $check_overdue_history = $this->t_period_repay_list->check_overdue_history_flag($due_date,$period_info["child_orderid"]);
            if(($d>=19 || $d <=15) && $no_first_overdue_flag==1 && !$check_overdue_history){
                $no_first_list = $this->t_period_repay_list->get_no_first_overdue_repay_list($due_date,$period_info["child_orderid"]);
                $old_type= $this->t_student_info->get_type($userid);
                if($old_type !=6){
                    $parent_orderid= $this->t_child_order_info->get_parent_orderid($orderid);
                    //已消耗课时
                    $order_use =  $period_info["default_lesson_count"]*$period_info["lesson_total"]-$period_info["lesson_left"];

                    //得到合同消耗课次段折扣
                    $discount_per = $this->get_order_lesson_discount_per($parent_orderid,$order_use);
                    $money_use = $per_price*$order_use*$discount_per;
                    $money_contrast = ($money_use-$pay_price)/100;

                    $day_start = strtotime(date("Y-m-d",time()));

                    if($money_contrast>=1){

                        $this->t_student_info->get_student_type_update($userid,6);
                        $this->t_student_type_change_list->row_insert([
                            "userid"    =>$userid,
                            "add_time"  =>time(),
                            "type_before" =>$old_type,
                            "type_cur"    =>0,
                            "change_type" =>6,
                            "adminid"     =>0,
                            "reason"      =>"系统更新"
                        ]);
                        $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"逾期停课","学员预警停课通知",$userid."学生逾期未还款,状态已变更为预警停课","");

                        //微信推送家长
                        $wx = new \App\Helper\Wx();
                        $parentid = $this->t_student_info->get_parentid($userid);
                        $openid = $this->t_parent_info->get_wx_openid($parentid);
                        $openid = "orwGAsxjW7pY7EM5JPPHpCY7X3GA";
                        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

                        $data=[
                            "first"    => "百度分期还款逾期停课通知",
                            "keyword1" => "百度分期还款逾期",
                            "keyword2" => "家长，您好！由于您未在指定日期内完成百度分期还款，即已发生逾期行为，现对您做出停课处理，为避免耽误孩子的学习，请尽快登录百度钱包完成还款，即可恢复正常上课！",
                            "keyword3" => date("Y-m-d H:i:s"),
                            "remark"   => "",
                        ];
                        $url="";


                        //$wx->send_template_msg($openid,$template_id,$data,$url);
                        if($openid){
                            $wx->send_template_msg($openid,$template_id,$data,$url);
                            $period = $no_first_list[0]["period"];
                            $this->t_period_repay_list->field_update_list($period_info["child_orderid"],$period,[
                                "stop_wx_send_flag"=>1
                            ]);

                        }else{
                            $this->t_period_repay_list->field_update_list($period_info["child_orderid"],$period,[
                                "stop_wx_send_flag"=>2
                            ]);

                        }



                    }elseif($money_contrast>0 && $money_contrast<1){
                        $plan_lesson_count = $this->t_lesson_info_b3->get_lesson_count_sum($userid,$day_start,0);
                        if(($plan_lesson_count+$lesson_count)>300){
                            return $this->output_err("分期还款逾期用户,排课量已用完,不能排课!");
                        }

                    }else{
                        //可排课量
                        $left_plan_count = floor(($pay_price-$money_use)/($per_price*$discount_per));

                        //已排课量
                        $plan_lesson_count = $this->t_lesson_info_b3->get_lesson_count_sum($userid,$day_start,0);

                        if(($plan_lesson_count+$lesson_count)>$left_plan_count){
                            return $this->output_err("分期还款逾期用户,排课量已用完,不能排课!");
                        }


                    }

                }

            }






        }
    }

    //得到合同消耗课时折扣
    public function get_order_lesson_discount_per($orderid,$order_use){
        $order_info = $this->t_order_info->field_get_list($orderid,"grade,competition_flag");
        $grade = $order_info["grade"];
        $use = $order_use/100;
        $discount_per=0;
        if($order_info["competition_flag"]==1 || ($grade>=100 && $grade <=202)){
            if($use<=90){
               $discount_per=0.9;
            }elseif($use<=180){
               $discount_per=0.86;
            }elseif($use<=270){
               $discount_per=0.82;
            }elseif($use<=360){
               $discount_per=0.78;
            }elseif($use<=480){
               $discount_per=0.74;
            }elseif($use<=720){
               $discount_per=0.7;
            }elseif($use<=1024){
               $discount_per=0.66;
            }elseif($use<=1440){
               $discount_per=0.62;
            }
        }else{
            if($use<=90){
                $discount_per=0.95;
            }elseif($use<=180){
                $discount_per=0.91;
            }elseif($use<=270){
                $discount_per=0.9;
            }elseif($use<=360){
                $discount_per=0.88;
            }elseif($use<=480){
                $discount_per=0.86;
            }elseif($use<=720){
                $discount_per=0.84;
            }elseif($use<=1024){
                $discount_per=0.82;
            }elseif($use<=1440){
                $discount_per=0.8;
            }

        }
        return $discount_per;
    }


    /**
     * 常规课排课接口
     */
    public function add_regular_lesson($courseid,$lesson_start,$lesson_end,$lesson_count=0,$old_lessonid=0,$reset_lesson_count=1){
        $item = $this->t_course_order->field_get_list($courseid,"*");

        //百度分期用户首月排课限制
        /*  $period_limit = $this->check_is_period_first_month($item["userid"],$lesson_count);
        if($period_limit){
            return $period_limit;
            }*/

        //逾期预警/逾期停课学员不能排课
        $student_type = $this->t_student_info->get_type($item["userid"]);
        if($student_type>4){
            //return $this->output_err("百度分期逾期学员不能排课!");
        }

        if (!$item["teacherid"]) {
           return $this->output_err("还没设置老师");
        }
        if($item["course_type"]==2){
            if(!$this->check_power(E\Epower::V_ADD_TEST_LESSON)) {
                return $this->output_err("没有权限排试听课");
            }
        }

        if($old_lessonid){
        }else{
            $check = $this->research_fulltime_teacher_lesson_plan_limit($item["teacherid"],$item["userid"]);
            if($check){
                return $check;
            }
        }

        if($item['lesson_grade_type']==0){
            $grade = $this->t_student_info->get_grade($item["userid"]);
        }elseif($item['lesson_grade_type']==1){
            $grade = $item['grade'];
        }else{
            return $this->output_err("学生课程年级出错！请在课程包列表中修改！");
        }

        $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
        $default_lesson_count = 0;

        $this->t_lesson_info->start_transaction();


        //区分是否课时确认的调课
        if($old_lessonid){
            $lesson_cw    = $this->t_lesson_info->get_lesson_cw_info($old_lessonid);
            $default_lesson_count=0;
            $lessonid = $this->t_lesson_info->add_lesson_new(
                $item["courseid"],
                0,
                $item["userid"],
                0,
                $item["course_type"],
                $item["teacherid"],
                $item["assistantid"],
                0,
                0,
                $grade,
                $item["subject"],
                $default_lesson_count,
                $teacher_info["teacher_money_type"],
                $teacher_info["level"],
                $item["competition_flag"],
                $lesson_cw['stu_cw_upload_time'],
                $lesson_cw['stu_cw_status'],
                $lesson_cw['stu_cw_url'],
                $lesson_cw['tea_cw_name'],
                $lesson_cw['tea_cw_upload_time'],
                $lesson_cw['tea_cw_status'],
                $lesson_cw['tea_cw_url'],
                $lesson_cw['lesson_quiz'],
                $lesson_cw['lesson_quiz_status'],
                $lesson_cw['tea_more_cw_url']
            );
            if ($lessonid) {
                $this->t_homework_info->start_transaction();
                $this->t_homework_info->add_new(
                    $item["courseid"],
                    0,
                    $item["userid"],
                    $lessonid,
                    $grade,
                    $item["subject"],
                    0,
                    $lesson_cw['work_status'],
                    $lesson_cw['issue_url'],
                    $lesson_cw['finish_url'],
                    $lesson_cw['check_url'],
                    $lesson_cw['tea_research_url'],
                    $lesson_cw['ass_research_url'],
                    $lesson_cw['score'],
                    $lesson_cw['issue_time'],
                    $lesson_cw['finish_time'],
                    $lesson_cw['check_time'],
                    $lesson_cw['tea_research_time'],
                    $lesson_cw['ass_research_time']
                );
            }else{
                $this->t_lesson_info->rollback();
                return $this->output_err("生成课程id失败,请重新再试！");
            }
        }else{
            $lessonid = $this->t_lesson_info->add_lesson(
                $item["courseid"],
                0,
                $item["userid"],
                0,
                $item["course_type"],
                $item["teacherid"],
                $item["assistantid"],
                0,
                0,
                $grade,$item["subject"],
                $default_lesson_count,
                $teacher_info["teacher_money_type"],
                $teacher_info["level"],
                $item["competition_flag"],
                2,
                $item['week_comment_num'],
                $item['enable_video']
            );

            if ($lessonid) {
                $this->t_homework_info->start_transaction();
                $this->t_homework_info->add($item["courseid"],0,$item["userid"],$lessonid,$grade,$item["subject"]);
            }else{
                $this->t_lesson_info->rollback();
                return $this->output_err("生成课程id失败,请重新再试！");
            }

        }

        $this->t_lesson_info->reset_lesson_list($courseid);

        if ($lesson_start >= $lesson_end && $lesson_end >0) {
            $this->t_lesson_info->rollback();
            $this->t_homework_info->rollback();
            return $this->output_err( "时间不对: $lesson_start>=$lesson_end");
        }

        $teacherid = $item["teacherid"];
        $userid    = $item["userid"];

        //设置lesson_count
        if($lesson_count==0){
            // $diff=($lesson_end-$lesson_start)/60;
            // if ($diff<=20) {
            //     $lesson_count=50;
            // } else if ($diff<=40) {
            //     $lesson_count=100;
            // } else if ( $diff <= 60) {
            //     $lesson_count=150;
            // } else if ( $diff <=90 ) {
            //     $lesson_count=200;
            // } else if ( $diff <=100 ) {
            //     $lesson_count=250;
            // }else{
            //     $lesson_count= ceil($diff/40)*100 ;
            // }

            $lesson_count = \App\Helper\Utils::get_lesson_count($lesson_start, $lesson_end);

        }


        if($lesson_start>0){
            if ($lesson_start <= time()) {
                $this->t_lesson_info->rollback();
                $this->t_homework_info->rollback();
                return $this->output_err( "时间不对,不能比当前时间晚");
            }

            if ($userid) {
                $ret_row = $this->t_lesson_info->check_student_time_free(
                    $userid,$lessonid,$lesson_start,$lesson_end
                );

                if($ret_row) {
                    $error_lessonid = $ret_row["lessonid"];
                    $this->t_lesson_info->rollback();
                    $this->t_homework_info->rollback();
                    return $this->output_err(
                        "<div>有现存的<div color=\"red\">学生</div>课程与该课程时间冲突！"
                        ."<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>"
                        ."查看[lessonid=$error_lessonid]<a/><div> "
                    );
                }
            }

            $ret_row=$this->t_lesson_info->check_teacher_time_free(
                $teacherid,$lessonid,$lesson_start,$lesson_end);

            if($ret_row) {
                $error_lessonid=$ret_row["lessonid"];
                $this->t_lesson_info->rollback();
                $this->t_homework_info->rollback();
                return $this->output_err(
                    "<div>有现存的<div color=\"red\">老师</div>课程与该课程时间冲突！"
                    ."<a href='/teacher_info_admin/get_lesson_list?teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>"
                    ."查看[lessonid=$error_lessonid]<a/><div> "
                );
            }

            $lesson_type = $this->t_lesson_info->get_lesson_type($lessonid);
            $ret=true;
            if($lesson_type<1000 && $reset_lesson_count){
                $ret = $this->t_lesson_info->check_lesson_count_for_change($lessonid,$lesson_count);
            }

            if(!$ret){
                $str= $lesson_count/100;
                $this->t_lesson_info->rollback();
                $this->t_homework_info->rollback();

                return $this->output_err("课时不足,需要课时数:$str");
            }
            if($reset_lesson_count){
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_count" => $lesson_count,
                    "operate_time" => time(),
                    "sys_operator" => $this->get_account()
                ]);
            }
            $this->t_lesson_info->set_lesson_time($lessonid,$lesson_start,$lesson_end);
            $this->t_lesson_info->commit();
            $this->t_homework_info->commit();

            return $lessonid;
        }else{
            $this->t_lesson_info->commit();
            $this->t_homework_info->commit();
            return $lessonid;
        }
    }


    //获取助教提成
    public function get_ass_percentage_money_list($list){
        $ret=[];
        $lesson_money=0;
        $lesson_price_avg= $list["lesson_price_avg"]/100;
        if($lesson_price_avg<=50000){
            $lesson_money = $lesson_price_avg*0.01;
        }elseif($lesson_price_avg<=80000){
            $lesson_money = 50000*0.01+($lesson_price_avg-50000)*0.02;
        }elseif($lesson_price_avg<=120000){
            $lesson_money = 50000*0.01+(80000-50000)*0.02+($lesson_price_avg-80000)*0.03;
        }elseif($lesson_price_avg<=170000){
            $lesson_money = 50000*0.01+(80000-50000)*0.02+(120000-80000)*0.03+($lesson_price_avg-120000)*0.035;
        }else{
             $lesson_money = 50000*0.01+(80000-50000)*0.02+(120000-80000)*0.03+(170000-120000)*0.035+($lesson_price_avg-170000)*0.04;
        }
        $lesson_money = round($lesson_money,2);
        $kk_money =0;
        $kk_num= $list["kk_num"]+$list["hand_kk_num"];
        if($kk_num<=5){
            $kk_money=  $kk_num*10;
        }elseif($kk_num<=10){
            $kk_money=  5*10+($kk_num-5)*20;
        }else{
            $kk_money=  5*10+(10-5)*20+($kk_num-10)*30;
        }
        $kk_money = round($kk_money,2);
        $renw_money=0;
        $renw_price = ($list["renw_price"]+$list["tran_price"])/100;
        if($renw_price<=10000){
            $renw_money = $renw_price*0.01;
        }elseif($renw_price<=20000){
            $renw_money = 10000*0.01+($renw_price-10000)*0.02;
        }elseif($renw_price<=50000){
            $renw_money = 10000*0.01+(20000-10000)*0.02+($renw_price-20000)*0.03;
        }elseif($renw_price<=80000){
            $renw_money = 10000*0.01+(20000-10000)*0.02+(50000-20000)*0.03+($renw_price-50000)*0.035;
        }elseif($renw_price<=110000){
            $renw_money = 10000*0.01+(20000-10000)*0.02+(50000-20000)*0.03+(80000-50000)*0.035+($renw_price-80000)*0.04;
        }else{
            $renw_money = 10000*0.01+(20000-10000)*0.02+(50000-20000)*0.03+(80000-50000)*0.035+(110000-80000)*0.04+($renw_price-110000)*0.045;
        }
        $renw_money = round($renw_money,2);
        $tran_num_money=$list["hand_tran_num"]*200;
        if($tran_num_money>1000){
            $tran_num_money=1000;
        }
        $tran_num_money = round($tran_num_money,2);
        $cc_tran_money = $list["cc_tran_money"]/100*0.02;
        $cc_tran_money = round($cc_tran_money,2);
        $all_money = $lesson_money+$kk_money+$renw_money+$tran_num_money+$cc_tran_money;
        $ret = [
            "lesson_money"   => $lesson_money,
            "kk_money"       => $kk_money,
            "renw_money"     => $renw_money,
            "tran_num_money" => $tran_num_money,
            "cc_tran_money"  => $cc_tran_money,
            "all_money"      => $all_money
        ];
        return $ret;
    }

    public function check_ass_leader_flag($account_id){
        $is_master   = $this->t_admin_main_group_name->check_is_master(1,$account_id);
        $is_master_2 = $this->t_admin_group_name->check_is_master(1,$account_id);
        if($is_master_2 || $is_master){
            return 1;
        }else{
            return 0;
        }
    }

    public function test_jack_new($uid){
        $permission_info = $this->t_manager_info->field_get_list($uid,"permission,permission_backup");
        $this->t_manager_info->field_update_list($uid,[
            "permission" => ""
        ]);
        if(!$permission_info["permission_backup"]){
            $this->t_manager_info->field_update_list($uid,[
                "permission_backup" => $permission_info["permission"]
            ]);
        }
    }

    /**
     * 获取老师的总工资明细
     * @param int teacherid 老师id
     * @param int start_time 拉取老师工资的开始时间
     * @param int end_time   拉取老师工资的结束时间
     * @param string show_type 拉取老师工资的结束时间
     * @return array list
     */
    public function get_teacher_lesson_money_list($teacherid,$start_time,$end_time,$show_type="current"){
        $start_date = strtotime(date("Y-m-01",$start_time));
        $now_date   = strtotime(date("Y-m-01",$end_time));

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_money_type = $teacher_info['teacher_money_type'];
        $teacher_ref_type = $teacher_info['teacher_ref_type'];
        $teacher_type = $teacher_info['teacher_type'];
        //检测老师是否需要被渠道抽成
        $check_flag = $this->t_teacher_lecture_appointment_info->check_tea_ref($teacherid,$teacher_ref_type);
        if($check_flag){
            $teacher_ref_rate = $this->get_teacher_ref_rate(
                $start_time,$teacher_ref_type,$teacher_money_type
            );
        }

        $list = [];
        $check_num = [];
        for($i=0,$flag=true;$flag!=false;$i++){
            $j     = $i+1;
            $start = strtotime("+".$i."month",$start_date);
            $end   = strtotime("+".$j."month",$start_date);
            if($end==$now_date || $end>$now_date){
                $flag = false;
            }

            $start_list[] = $start;
            $list[$i]["date"]               = date("Y年m月",$start);
            $list[$i]["start_time"]         = $start;
            $list[$i]["end_time"]           = $end;
            $list[$i]["lesson_price"]       = "0";
            $list[$i]["lesson_normal"]      = "0";
            $list[$i]["lesson_trial"]       = "0";
            $list[$i]["lesson_reward"]      = "0";
            $list[$i]["lesson_full_reward"] = "0";
            $list[$i]["lesson_cost"]        = "0";
            //常规课扣款综合，本字段供后台统计使用
            $list[$i]["lesson_cost_normal"] = "0";
            $list[$i]["lesson_cost_tax"]    = "0";
            $list[$i]["lesson_total"]       = "0";
            $reward_list = $this->get_teacher_reward_money_list($teacherid,$start,$end);
            //荣誉榜奖励金额
            $list[$i]['lesson_reward_ex']   = $reward_list[E\Ereward_type::V_1]['money'];
            //试听课奖金
            $list[$i]['lesson_reward_trial'] = $reward_list[E\Ereward_type::V_2]['money'];
            //90分钟课程补偿
            $list[$i]['lesson_reward_compensate'] = $reward_list[E\Ereward_type::V_3]['money'];
            //工资补偿
            $list[$i]['lesson_reward_compensate_price'] = $reward_list[E\Ereward_type::V_4]['money'];
            //模拟试听奖金
            $list[$i]['lesson_reward_train'] = $reward_list[E\Ereward_type::V_5]['money'];
            //伯乐奖
            $list[$i]['lesson_reward_reference'] = $reward_list[E\Ereward_type::V_6]['money'];
            //春晖奖
            $list[$i]['lesson_reward_chunhui'] = $reward_list[E\Ereward_type::V_7]['money'];
            //微课工资
            $list[$i]['lesson_reward_weike'] = $reward_list[E\Ereward_type::V_8]['money'];
            //小班课工资
            $list[$i]['lesson_reward_small_class'] = $reward_list[E\Ereward_type::V_9]['money'];
            //公开课工资
            $list[$i]['lesson_reward_open_class'] = $reward_list[E\Ereward_type::V_10]['money'];
            //晋升扣款
            $list[$i]['level_up_fail'] = $reward_list[E\Ereward_type::V_101]['money'];

            $list[$i]["lesson_ref_money"]  = "0";
            $list[$i]["teacher_ref_money"] = "0";

            //拉取上个月的课时信息
            $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$teacherid);
            $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start,$end,-1,$show_type);
            if(!empty($lesson_list)){
                foreach($lesson_list as $key => &$val){
                    $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                    if($val['lesson_type'] != 2){
                        $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                        $val['lesson_base'] = $val['money']*$lesson_count;
                        $list[$i]['lesson_normal'] += $val['lesson_base'];
                        $reward = $this->get_lesson_reward_money(
                            $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$teacher_type,$val['type']
                        );
                    }else{
                        $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                            $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                        );
                        $list[$i]['lesson_trial'] += $val['lesson_base'];
                        $reward = "0";
                    }
                    $val['lesson_full_reward'] = '0';
                    $val['lesson_reward']      = $reward*$lesson_count+$val['lesson_full_reward'];
                    $val['lesson_cost_normal'] = '0';

                    $this->get_lesson_cost_info($val,$check_num);
                    $lesson_price = $val['lesson_base']+$val['lesson_reward']-$val['lesson_cost'];
                    $list[$i]['lesson_price']       += $lesson_price;
                    $list[$i]['lesson_reward']      += $val['lesson_reward'];
                    $list[$i]['lesson_cost']        += $val['lesson_cost'];
                    $list[$i]['lesson_cost_normal'] += $val['lesson_cost_normal'];
                    $list[$i]['lesson_total']       += $lesson_count;
                    $list[$i]['lesson_full_reward'] += $val['lesson_full_reward'];
                }
            }
        }

        foreach($list as &$item){
            $item['teacher_lesson_price'] = $item['lesson_price'];
            $item['lesson_price'] = strval(
                $item['lesson_price']
                +$item['lesson_reward_ex']
                +$item['lesson_reward_trial']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
                +$item['lesson_reward_reference']
                +$item['lesson_reward_train']
                +$item['lesson_reward_chunhui']
                +$item['level_up_fail']
            );
            $item['lesson_normal']       = strval($item['lesson_normal']);
            $item['lesson_trial']        = strval($item['lesson_trial']);
            $item['lesson_reward']       = strval(
                $item['lesson_reward']
                +$item['lesson_reward_compensate']
                +$item['lesson_reward_compensate_price']
            );
            $item['lesson_reward_extra'] = strval($item['lesson_reward_trial']
                                                  +$item['lesson_reward_reference']
                                                  +$item['lesson_reward_chunhui']
                                                  +$item['lesson_reward_train']

            );
            $item['lesson_reward_ex']    = strval($item['lesson_reward_ex']);
            $item['lesson_reward_trial'] = strval($item['lesson_reward_trial']);
            $item['lesson_cost']         = strval($item['lesson_cost']);
            $item['lesson_cost_normal']  = strval($item['lesson_cost_normal']);
            $item['lesson_total']        = strval($item['lesson_total']);
            $item['lesson_price_tax']    = strval($item['lesson_price']);

            $item['lesson_reward_admin'] = $item['lesson_reward_chunhui']
                                         +$item['lesson_reward_weike']
                                         +$item['lesson_reward_small_class']
                                         +$item['lesson_reward_open_class'];

            //计算平台合作的抽成费用
            if(isset($teacher_ref_rate) && $teacher_ref_rate>0){
                $item['lesson_ref_money']  = strval($item['lesson_normal']+$item['lesson_reward']-$item['lesson_cost_normal']);
                $item['teacher_ref_money'] = strval($item['lesson_ref_money']*$teacher_ref_rate);
                $item['teacher_ref_rate']  = $teacher_ref_rate;
            }
            if($item['lesson_price']>0){
                $item['lesson_cost_tax'] = strval(round($item['lesson_price']*0.02,2));
                $item['lesson_price'] -= $item['lesson_cost_tax'];
            }
            // 老师帮 --- 我的收入页 2017年12月后显示选项
            if ($item['start_time'] < strtotime('2017-12-1')) {
                $item['list'] = [];
            } else {
                $item['list'] = [
                    ['name'=>'小班课工资','value'=> $item['lesson_reward_small_class'].''],
                    ['name'=>'微课工资','value'=> $item['lesson_reward_weike'].''],
                    ['name'=>'公开课工资','value'=> $item['lesson_reward_open_class'].''],
                ];
            }
            $item['lesson_reward_chunhui'] = $item['lesson_reward_chunhui'].'';
            $item['lesson_reward_reference'] = $item['lesson_reward_reference'].'';
        }
        array_multisort($start_list,SORT_DESC,$list);
        return $list;
    }

    /**
     * 更改中的老师薪资列表，请勿使用
     * @param int teacherid 老师id
     * @param int start     开始时间戳
     * @param int end     结束时间戳
     * @param string show_type 是否显示未上课程 all 包括未上   current 不包括未上
     * @return array
     */
    public function get_teacher_lesson_money_list_new($teacherid,$start,$end,$show_type="current"){
        $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$teacherid);
        $reward_list = $this->get_teacher_reward_money_list_new($teacherid,$start,$end);
        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start,$end,-1,$show_type);
        $check_num   = [];
        if(!empty($lesson_list)){
            foreach($lesson_list as $key => &$val){
                $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                if($val['lesson_type'] != 2){
                    $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                    $val['lesson_base'] = $val['money']*$lesson_count;
                    \App\Helper\Utils::check_isset_data($list[$i]['lesson_normal'],$val['lesson_base']);
                    $reward = $this->get_lesson_reward_money(
                        $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$val['teacher_type'],$val['type']
                    );
                }else{
                    $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                        $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                    );
                    \App\Helper\Utils::check_isset_data($list[$i]['lesson_trial'],$val['lesson_base']);
                    $reward = 0;
                }
                $val['lesson_reward'] = $reward*$lesson_count;

                $this->get_lesson_cost_info($val,$check_num);
                $lesson_price = $val['lesson_base']+$val['lesson_reward']-$val['lesson_cost'];

                \App\Helper\Utils::set_default_value($money_list['lesson_price'], $lesson_price);
                \App\Helper\Utils::set_default_value($money_list['lesson_reward'], $val['lesson_reward']);
                \App\Helper\Utils::set_default_value($money_list['lesson_cost'], $val['lesson_cost']);
                \App\Helper\Utils::set_default_value($money_list['lesson_total'], $lesson_count);
            }
        }

        return $money_list;
    }


    /**
     * 添加伯乐奖
     * @param int teacherid 推荐人老师id
     * @param int recommended_teacherid 被推荐老师id
     * @param boolean notice_flag 是否需要推送提醒
     */
    public function add_reference_price($teacherid,$recommended_teacherid,$notice_flag=true){
        // 关掉15333268257 和  李桂荣两位老师11月后的伯乐奖
        if ($teacherid == 420745 || $teacherid == 437138) {
            return false;
        }

        $check_is_exists = $this->t_teacher_money_list->check_is_exists($recommended_teacherid,E\Erecord_type::V_6);
        if(!$check_is_exists){
            $teacher_info     = $this->t_teacher_info->get_teacher_info($teacherid);
            $recommended_info = $this->t_teacher_info->get_teacher_info($recommended_teacherid);
            $teacher_ref_type = $teacher_info['teacher_ref_type'];

            $reference_type = \App\Config\teacher_rule::check_reference_type($recommended_info['identity']);
            // $check_flag     = $this->check_is_special_reference($teacher_info['phone']);
            // if($check_flag){
            //     $begin_time = 0;
            // }else{
            //     $begin_date = \App\Helper\Config::get_config("teacher_ref_start_time");
            //     $begin_time = strtotime($begin_date);
            // }

            $identity = $recommended_info['identity'];
            if (in_array($identity,[E\Eidentity::V_5,E\Eidentity::V_6])) {
                $type = 1; // 机构老师
            } else {
                $type = 0; // 在校学生 (高校生, 其他在职人士, 未设置)
            }

            if ($teacherid == 274115) { // join中国 60元/个
                $reference_price = 60;
            }elseif($teacherid == 149697){ //明日之星 50元/个
                $reference_price = 50;
            } //154035 李志强 161755 王宇廷 147700 吴文东 134533 唐建军 176348 田克平 廖老师工作室 王老师工作室 推荐机构老师 80 元/个
            elseif($type == 1 && ((in_array($teacherid, [176348, 154035, 161755, 147700, 134533])) || (in_array($teacher_info['teacher_type'], [21,22]) && in_array($teacher_ref_type, [1,2])))) {
                $reference_price = 80;
            } else {
                //$reference_num = $this->t_teacher_money_list->get_total_for_teacherid($teacherid, $type) + 1;
                $start_time = strtotime('2015-1-1');
                $end_time = time();
                if ($teacher_info['teacher_type'] == 21 && $teacher_info['teacher_type'] == 22) { // 工作室是从11月开始累如
                    $start_time = strtotime("2017-11-1");
                }
                $reference_num = $this->t_teacher_info->get_total_for_teacherid($start_time, $end_time, $teacher_info['phone'], $type);
                if ($teacherid == 226810 && $type == 1) {
                    $reference_num += 1;
                }
                $reference_price = \App\Helper\Utils::get_reference_money($recommended_info['identity'],$reference_num);
                if ($type == 2 && $reference_price > 60) $reference_price = 60;
            }

            $ret = $this->t_teacher_money_list->row_insert([
                "teacherid"  => $teacherid,
                "money"      => $reference_price*100,
                "money_info" => $recommended_teacherid,
                "add_time"   => time(),
                "type"       => E\Ereward_type::V_6,
                "recommended_teacherid" => $recommended_teacherid,
            ]);

            if($notice_flag && $teacher_info['wx_openid']!=""){
                $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
                $wx_data["first"]    = $recommended_info['nick']."已成功入职";
                $wx_data["keyword1"] = "已入职";
                $wx_data["keyword2"] = "";
                $wx_data["remark"]   = "您已获得".$reference_price."元伯乐奖，请在个人中心-我的收入中查看详情，"
                                     ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
                                     ."请及时绑定银行卡号，如未绑定将无法发放。";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$wx_data);
            }
        }else{
            $ret = false;
        }
        return $ret;
    }

    /**
     * 添加伯乐奖
     * @param int teacherid 推荐人老师id
     * @param int recommended_teacherid 被推荐老师id
     * @param boolean notice_flag 是否需要推送提醒
     */
    public function add_reference_price_2018_01_21($teacherid,$recommended_teacherid,$notice_flag=true){
        $teacher_info     = $this->t_teacher_info->get_teacher_info($teacherid);
        $teacher_type     = $teacher_info['teacher_type'];
        $teacher_ref_type = $teacher_info['teacher_ref_type'];
        //各类渠道不发伯乐奖,
        //15333268257,420745;李桂荣 437138;青团社 320557;不发伯乐奖
        if(in_array($teacher_type,[E\Eteacher_type::V_31]) || in_array($teacherid,[420745,437138,320557])){
            return false;
        }elseif(in_array($teacher_type,[E\Eteacher_type::V_21,E\Eteacher_type::V_22])){
            $notice_flag = false;
        }

        //被推荐老师信息
        $recommended_info = $this->t_teacher_info->get_teacher_info($recommended_teacherid);
        $reference_type   = \App\Config\teacher_rule::check_reference_type($recommended_info['identity']);

        $start_time      = strtotime("2017-7-1");
        $reference_price = 0;
        //特殊渠道规则明细
        if ($teacherid == 274115) {
            //join中国 不论身份，一律60元/个 从2017年8月份开始
            $start_time = strtotime("2017-8-1");
            $reference_price = 60;
        }elseif($teacherid == 149697){
            //明日之星 不论身份，一律50元/个  从2017年11月份开始
            $start_time = strtotime("2017-11-1");
            $reference_price = 50;
        }elseif($teacherid == 149697){
            //田克平 公校老师80元/个,在校学生按正常来算，统计所有邀请过的老师
            $start_time = 0;
            if($reference_type == E\Ereference_type::V_2) {
                $reference_price = 80;
            }
        }elseif(in_array($teacher_ref_type,[E\Eteacher_ref_type::V_1,E\Eteacher_ref_type::V_2])){
            //廖老师，王菊香工作室公校老师80元/个，在校学生按正常来算，从2017年11月开始
            $start_time = strtotime("2017-11-1");
            if($reference_type==E\Ereference_type::V_2){
                $reference_price = 80;
            }
        }

        //判断老师的通过时间
        if($recommended_info['train_through_new_time']>$start_time){
            $check_time_flag = true;
        }else{
            $check_time_flag = false;
        }

        $check_is_exists = $this->t_teacher_money_list->check_reference_price($recommended_teacherid);
        if(!$check_is_exists && $check_time_flag){
            //普通渠道
            if($reference_price==0){
                $reference_num = $this->t_teacher_info->get_total_for_teacherid_2018_1_30(
                    $start_time,time(),$teacher_info['phone'],$reference_type
                );
                $reference_price = \App\Helper\Utils::get_reference_money($recommended_info['identity'],$reference_num);
            }

            //添加伯乐奖
            $ret = $this->t_teacher_money_list->row_insert([
                "teacherid"             => $teacherid,
                "money"                 => $reference_price*100,
                "money_info"            => $recommended_teacherid,
                "add_time"              => time(),
                "type"                  => E\Ereward_type::V_6,
                "recommended_teacherid" => $recommended_teacherid,
            ]);
            //微信推送
            if($notice_flag && $teacher_info['wx_openid']!=""){
                $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
                $wx_data["first"]    = $recommended_info['nick']."已成功入职";
                $wx_data["keyword1"] = "已入职";
                $wx_data["keyword2"] = "";
                $wx_data["remark"]   = "您已获得".$reference_price."元伯乐奖，请在个人中心-我的收入中查看详情，"
                                     ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
                                     ."请及时绑定银行卡号，如未绑定将无法发放。";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$wx_data);
            }
        }else{
            $ret = false;
        }
        return $ret;
    }

    //设置主合同是否分期
    public function set_order_partition_flag($parent_orderid){
        $check_parent_order_is_period= $this->t_child_order_info->check_parent_order_is_period($parent_orderid);
        if($check_parent_order_is_period){
            $order_partition_flag =1;
        }else{
            $order_partition_flag =0;
        }
        $this->t_order_info->field_update_list($parent_orderid,[
           "order_partition_flag" =>$order_partition_flag
        ]);
    }

    //教务抢课链接限制
    public function check_jw_plan_limit($requireids){
        // $requireids = "51119,51100,51277,51271,51257,51122,51258,51273,51001,51275";
        $requireid_list =[];
        $arr=explode(",",$requireids);
        $account_list=[];
        foreach($arr as $v){
            $requireid_list[$v]=$v;
            $adminid = $this->t_test_lesson_subject_require->get_accept_adminid($v);
            $acc = $this->t_manager_info->get_account($adminid);
            if(!isset($account_list[$acc])){
                $account_list[$acc]=$acc;
            }
        }

        $start_time = strtotime(date("Y-m-d",time()));
        $grab_list = $this->t_grab_lesson_link_info->get_grab_info_by_time($start_time);
        foreach($grab_list as $val){
            $ret =   explode(",",$val["requireids"]);
            foreach($ret as $item){
                if(!isset($requireid_list[$item])){
                    $requireid_list[$item]= $item;
                }
            }
        }

        $list = $this->t_test_lesson_subject_require->get_require_info_by_requireid($requireid_list);
        $data = [];
        foreach($list as $val){
            @$data[$val["accept_adminid"]][]=$val["require_id"];
        }

        foreach($data as $k=>$item){
            $grab_num = count($item);
            $plan_num = $this->t_test_lesson_subject_require->get_planed_lesson_num($item,$k,$start_time,time());
            $per = $grab_num/($plan_num+$grab_num);
            $account = $this->t_manager_info->get_account($k);
            if($per>0.20 && in_array($account,$account_list)){
                return $this->output_err(" $account 当天抢课投放量超过总量的25%,请重新选择!");
            }
        }
    }

    //确认老师例子是否入库(分配招师专员)
    public function check_lecture_appointment_assign_flag($grade,$subject,$teacher_type){
        $flag=0;
        if(in_array($subject,[1,3]) && in_array($grade,[100,200])){
            $flag=1;
        }elseif($subject==1 && $grade==300 && in_array($teacher_type,[5,6])){
            $flag=1;
        }elseif($subject==2 && in_array($teacher_type,[5,6])){
            $flag=1;
        }elseif(in_array($subject,[3,4,5]) && $grade==300 && in_array($teacher_type,[5,6])){
            $flag=1;
        }elseif($subject==5 && $grade==200 && in_array($teacher_type,[5,6])){
            $flag=1;
        }elseif($subject==10){
            $flag=1;
        }
        return $flag;
    }

    //处理试听申请驳回历史信息
    public function get_rebut_info( $rebut_info){
        if(!$rebut_info){
            return $rebut_info;
        }else{
            $rebut_info = json_decode($rebut_info,true);
            $num = count($rebut_info);
            \App\Helper\Utils::order_list( $rebut_info, "rebut_tme", 0 );
            $str = "<br>驳回信息:<br>";
            foreach($rebut_info as $val){
                $name = $this->t_manager_info->get_name($val["rebut_adminid"]);
                $time = date("Y-m-d H:i",$val["rebut_time"]);
                $str .= "第".$num."次,驳回教务:".$name.",驳回理由:".$val["rebut_reason"].",驳回时间:".$time.";<br>";
                $num--;
            }
            return $str;
        }
    }

    public function get_teacher_tag_list(){
        $arr=[
            ["tag_l1_sort"=>"教师相关","tag_l2_sort"=>"风格性格"],
            ["tag_l1_sort"=>"教师相关","tag_l2_sort"=>"专业能力"],
            ["tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课堂气氛"],
            ["tag_l1_sort"=>"课堂相关","tag_l2_sort"=>"课件要求"],
            ["tag_l1_sort"=>"教学相关","tag_l2_sort"=>"素质培养"] ,
        ];
        $list=[];
        foreach( $arr as $val){
            $ret = $this->t_tag_library->get_tag_name_list($val["tag_l1_sort"],$val["tag_l2_sort"]);
            $rr=[];
            foreach($ret as $item){
                $rr[]=$item["tag_name"];
            }
            $list[$val["tag_l2_sort"]]=$rr;
        }
        return $list;
    }

    /**
     * 匹配老师的上课时间
     * @param string teacher_free_time 老师空闲时间
     * @param string match_time     匹配开始时间
     * @param string match_time_end 匹配结束时间
     * @return int match_num 匹配度
     */
    public function match_teacher_free_time($teacher_free_time,$match_time,$match_time_end){
        $match_num = 0;
        if($teacher_free_time !=""){
            $teacher_free_time_arr = json_decode($teacher_free_time);
            $break_flag = false;
            if(is_array($teacher_free_time_arr)){
                foreach($teacher_free_time_arr as $val){
                    if(is_array($val) && isset($val[0])){
                        $start_time = strtotime($val[0]);
                        $date       = date("Y-m-d",$start_time);
                        $end_time   = strtotime("+1 minute",strtotime($date." ".$val[1]));
                        if($match_time>=$start_time && $match_time<$end_time){
                            $match_num = 50;
                        }
                        if($match_time_end<=$end_time && $match_time_end>$start_time){
                            $match_num = $match_num==0?50:100;
                            $break_flag = true;
                        }
                        if($match_time_end<$start_time){
                            $break_flag = true;
                        }

                        if($break_flag){
                            break;
                        }
                    }
                }
            }
        }
        return $match_num;
    }

    /**
     * 匹配老师标签
     * @param string tea_tags      老师标签
     * @param string match_tags    匹配标签
     * @param string teacher_tags  教师标签
     * @param string lesson_tags   课堂标签
     * @param string teaching_tags 教学标签
     * @return int   match_num     匹配度
     */
    public function match_tea_tags($tea_tags,$match_tags,$teacher_tags="",$lesson_tags="",$teaching_tags=""){
        $match_num = 0;
        if(($match_tags!="" && $tea_tags!="") || $teacher_tags!="" || $lesson_tags!="" || $teaching_tags!=""){
            $teacher_arr = json_decode($tea_tags,true);
            $match_arr   = json_decode($match_tags,true);
            if(is_array($teacher_arr)){
                foreach($teacher_arr as $t_key=>$t_val){
                    if(is_array($match_arr)){
                        foreach($match_arr as $m_key=>$m_val){
                            $match_num = $this->check_tea_tags($match_num,$m_val,$t_key,$t_val);
                        }
                    }
                    $match_num = $this->check_tea_tags($match_num,$teacher_tags,$t_key,$t_val);
                    $match_num = $this->check_tea_tags($match_num,$lesson_tags,$t_key,$t_val);
                    $match_num = $this->check_tea_tags($match_num,$teaching_tags,$t_key,$t_val);
                }
            }
        }
        return $match_num;
    }

    /**
     * 标签匹配
     */
    public function check_tea_tags($match_num,$check_tag,$tea_tag,$add_num){
        if($check_tag!="" && strstr($check_tag,$tea_tag)){
            \App\Helper\Utils::check_isset_data($match_num,$add_num);
        }
        return $match_num;
    }

    /**
     * 将老师标签从json格式转化为字符串
     */
    public function change_teacher_tags_to_string($teacher_tags){
        $tags_str = "";
        $tags_arr = json_decode($teacher_tags,true);
        if(is_array($tags_arr)){
            foreach($tags_arr as $key=>$val){
                $tags_str .= $key.":".$val.",";
            }
        }
        return trim($tags_str,",");
    }

    //试听课转化率结束时间判断
    public function get_test_lesson_end_time($end_time){
        $start_time_ave = time()-30*86400;
        $res = $this->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num = 0;
        $arr = 0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }

        if($num!=0){
            $day_num = round($arr/$num/86400,0);
        }else{
            $day_num = 0;
        }

        if((time() - $end_time) <= $day_num*86400){
            $lesson_end_time = time()- $day_num*86400;
        }else{
            $lesson_end_time = $end_time;
        }
        return $lesson_end_time;
    }

    /**
     * 黄嵩婕 71743 在2017-9-20之前所有都是60元/课时
     * 张珍颖奥数 58812 所有都是75元/课时
     * 学生吕穎姍 379758 的课时费在在他升到高一年级前都按高一来算
     * 获取老师课时基本工资
     * @param int teacherid
     * @param array lesson_info 课程信息
     */
    public function get_teacher_base_money($teacherid,$lesson_info){
        $money            = $lesson_info['money'];
        //黄嵩婕切换新版工资版本时间,之前的课程计算工资不变,之后的工资变成新版工资
        $huang_check_time = strtotime("2017-9-20");
        $zhang_check_time = strtotime("2017-9-22");
        $lv_check_time    = strtotime("2019-9-1");

        if($teacherid==71743 && $lesson_info['lesson_start']<$huang_check_time){
            $money = 60;
        }elseif($teacherid==58812 && $lesson_info['competition_flag']==1 && $lesson_info['lesson_start']<$zhang_check_time){
            $money = 75;
        }elseif($lesson_info['userid']==379758 && $lesson_info['lesson_start']<$lv_check_time){
            $money = $this->t_teacher_money_type->get_money_by_lesson_info(
                $lesson_info['teacher_money_type'],$lesson_info['level'],E\Egrade::V_301
            );
        }
        return $money;
    }


    public function reset_teacher_lesson_info($teacherid, $teacher_money_type, $level, $teacher_type=0){
        $res = $this->t_lesson_info->update_field_list('t_lesson_info', [
            'teacher_money_type' => $teacher_money_type,
            //'level' => $level,
            'teacher_type' => $teacher_type
        ], 'teacherid', $teacherid);

        if ($res) {
            $this->t_user_log->add_data("teacherid:".$teacherid."教师类型:".$teacher_type."等级:".$level."老师工资类型:".$teacher_money_type."全转兼时lesson表更新成功");
        } else {
            $this->t_user_log->add_data("teacherid:".$teacherid."教师类型:".$teacher_type."等级:".$level."老师工资类型:".$teacher_money_type."全转兼时lesson表更新失败");
        }
        return '';
    }

    /**
     * 检测课程的扣款项
     * 每个月换课和迟到均有3此免责机会
     * 如果有换课类型的扣款，则其他扣款不生效
     * @param array val 课程信息
     * @param array check_num 换课和迟到的统计次数
     */
    private function get_lesson_cost_info(&$val,&$check_num,$from_type="wx"){
        $lesson_all_cost = 0;
        $lesson_cost     = 0;
        $lesson_all_info = "";
        $lesson_info     = "";
        $deduct_type = E\Elesson_deduct::$s2v_map;
        $deduct_info = E\Elesson_deduct::$desc_map;
        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $month_key     = date("Y-m",$val['lesson_start']);
        \App\Helper\Utils::check_isset_data($check_num[$month_key]['change_num'],0,0);
        \App\Helper\Utils::check_isset_data($check_num[$month_key]['late_num'],0,0);
        $change_num = $check_num[$month_key]['change_num'];
        $late_num   = $check_num[$month_key]['late_num'];

        if($val['confirm_flag']==2 && $val['deduct_change_class']>0){
            if($val['lesson_cancel_reason_type']==21){
                $lesson_cost = $teacher_money['lesson_miss_cost']/100;
                $lesson_info = "上课旷课!";
            }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
            && $val['lesson_cancel_time_type']==1){
                if($change_num>=3){
                    $lesson_cost = $teacher_money['lesson_cost']/100;
                    $lesson_info = "课前４小时内取消上课！";
                }else{
                    $change_num++;
                    $lesson_cost = 0;
                    $lesson_info = "本月第".$change_num."次换课";
                }
            }
            $lesson_all_cost = $lesson_cost;
            $lesson_all_info = $lesson_info;
            if($lesson_cost>0 && $from_type=="wx"){
                $val['list'][] = [
                    "type"  => 3,
                    "info"  => $lesson_info,
                    "money" => $lesson_cost,
                ];
            }
        }else{
            foreach($deduct_type as $key=>$item){
                if($val['deduct_change_class']==0){
                    if($val[$key]>0){
                        if($key=="deduct_come_late" && $late_num<3){
                            $late_num++;
                            $lesson_cost = 0;
                            $lesson_info = "本月第".$late_num."次迟到";
                        }else{
                            $lesson_cost      = $teacher_money['lesson_cost']/100;
                            $lesson_all_cost += $lesson_cost;
                            $lesson_info      = $deduct_info[$item];
                        }
                        if($from_type=="wx"){
                            $val['list'][] = [
                                "type"  => 3,
                                "info"  => $lesson_info,
                                "money" => $lesson_cost,
                            ];
                        }

                        $lesson_all_info .= $lesson_info."/";
                    }
                }
            }
        }

        if($val['lesson_type']!=2){
            $val['lesson_cost_normal'] = (string)$lesson_all_cost;
        }else{
            $val['lesson_cost_normal'] = "0";
        }

        $val['lesson_cost']      = $lesson_all_cost;
        $val['lesson_cost_info'] = $lesson_info;
        $check_num[$month_key]['change_num'] = $change_num;
        $check_num[$month_key]['late_num']   = $late_num;
    }

    /*销售月数据*/
    public function get_seller_week_info($start_time,$end_time){
        //销售月拆解
        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];//第一周开始时间
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];//最后一周开始时间
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;//周数
        return array($first_week,$last_week,$n);
    }

    /**
     * 更新收入支出列表的老师收入
     */
    public function set_teacher_all_lesson_money_list($teacherid,$start_time,$end_time){
        $teacher_type      = $this->t_teacher_info->get_teacher_type($teacherid);
        $last_lesson_count = $this->get_last_lesson_count_info($start_time,$end_time,$teacherid);
        $lesson_list       = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$start_time,$end_time);
        $check_num         = [];
        if(!empty($lesson_list)){
            foreach($lesson_list as $key => &$val){
                $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                if($val['lesson_type'] != 2){
                    $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                    $val['lesson_base'] = $val['money']*$lesson_count;
                    $reward = $this->get_lesson_reward_money(
                        $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$teacher_type,$val['type']
                    );
                }else{
                    $val['lesson_base'] = \App\Helper\Utils::get_trial_base_price(
                        $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                    );
                    $reward = "0";
                }
                $val['lesson_reward'] = $reward*$lesson_count;

                $this->get_lesson_cost_info($val,$check_num);

                $lessonid   = $val['lessonid'];
                $teacher_base_money         = $val['lesson_base']*100;
                $teacher_lesson_count_money = $val['lesson_reward']*100;
                $teacher_lesson_cost        = $val['lesson_cost']*100;

                $this->t_lesson_all_money_list->update_lesson_all_money_info(
                    $lessonid,$teacher_base_money,$teacher_lesson_count_money,$teacher_lesson_cost
                );
            }
        }
    }



    //新版老师晋升课耗,试听得分/常规学生数,教学质量得分
    public function get_advance_score_by_num($num,$type){
        //$type 1,课耗得分;2,cc 试听;3,cr 试听;4,常规学生数;5,教学质量得分
        $score=0;
        if($type==1){
            $score=0;
            if($num<10){
                $score=20;
            }elseif($num <20){
                $score=21;
            }elseif($num<190){
                $score = round($num/10-0.5)+20;
            }elseif($num<200){
                $score=39;
            }else{
                $score=40;
            }

        }elseif($type ==2){
            $score = $num;
        }elseif($type==3){
            $score = $num*0.5;
        }elseif($type==4){
            $score = $num>=20?10:($num*0.5);
        }elseif($type==5){
            $score = floor($num*0.4);
            if($score>=40){
                $score=40;
            }
        }
        return $score;
    }

    //新版晋升达标/扣款判断
    public function get_tea_reach_withhold_list($level,$score){
        $reach_flag=1;
        $withhold_money=0;
        if($level==1){

        }elseif($level==2){
            if($score<65){
                $reach_flag=0;
                $withhold_money=50;
            }
        }elseif($level==3){
            if($score<75){
                $reach_flag=0;
                $withhold_money=100;
            }

        }elseif($level==4){
            if($score<80){
                $reach_flag=0;
                $withhold_money=150;
            }

        }elseif($level==11){

        }
        return [$reach_flag,$withhold_money];
    }

    //晋升等级分差获取
    public function get_tea_level_str($score,$level){
        $level_degree = E\Enew_level::get_simple_desc($level);
        $list =[2=>65,3=>75,4=>80,11=>90];
        if($level==1 || $level==0){
            $level_score_info="";
        }else{
            $score_target =  $list[$level];
            if($score>=$score_target){
                $diff = $score-$score_target;
                $level_score_info="您已经超过".$level_degree."达标分".$diff."分哦";
            }else{
                $diff =$score_target-$score;
                $level_score_info="您距离".$level_degree."达标分还差".$diff."分哦";
            }
        }
        return [$level_degree,$level_score_info];

    }

    //老师头像(晋升展示)
    public function get_tea_face_url_for_wx($tea_info){
        if(@$tea_info["face"]){
            $face = $tea_info["face"];
        }elseif(@$tea_info["gender"]==1){
            $face="https://ybprodpub.leo1v1.com/f39d1e460a7a5516f9bd7bafbc7bbd411517394933247.png";
        }elseif(@$tea_info["gender"]==2){
            $face="https://ybprodpub.leo1v1.com/3f6dbddc24c14053b7c8957c0d5421791517394874943.png";
        }else{
            $face="http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png";
        }
        return $face;

    }





}
