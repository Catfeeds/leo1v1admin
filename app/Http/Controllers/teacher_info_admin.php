<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class teacher_info_admin extends Controller
{
    use CacheNick;

    static $page_self_view_data=[];
    var $teacherid;

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($request, $next)
        {
            $this->teacherid=$this->get_in_teacherid();
            static::$page_self_view_data["_teacherid"]= $this->teacherid;
            static::$page_self_view_data["_teacher_nick"]= $this->cache_get_teacher_nick($this->teacherid);

            return $next($request);
        });
    }

    public function index(){
        $teacherid = $this->teacherid;
        $tea_info  = $this->t_teacher_info->get_teacher_info_all($teacherid);

        $tea_info['gender_str'] = @E\Egender::get_desc( $tea_info['gender']);
        $tea_info['textbook_type_str'] = @E\Etextbook_type::get_desc( $tea_info['textbook_type']);
        $tea_info['grade_part_ex_str'] = empty($tea_info['grade_part_ex'])?"":@E\Egrade_part_ex::get_desc( $tea_info['grade_part_ex']);
        $tea_info['subject_str'] = empty($tea_info['subject'])?"":@E\Esubject::get_desc( $tea_info['subject']);
        $tea_info['putonghua_is_correctly_str'] = @E\Eputonghua_is_correctly::get_desc( $tea_info['putonghua_is_correctly']);
        if(@$tea_info['birth']){
            $tea_info['birth_str'] = substr(@$tea_info['birth'],0,4)
                ."-".substr(@$tea_info['birth'],4,2)
                ."-".substr(@$tea_info['birth'],6,2);
        }else{
            $tea_info['birth_str']="";
        }
        $tea_info['grade_str'] = @$tea_info['grade_start']>0?(@E\Egrade_range::get_desc( $tea_info['grade_start'])."~".@E\Egrade_range::get_desc( $tea_info['grade_end'])):"未设置";
        $tea_info['identity_str'] = @E\Eidentity::get_desc( $tea_info['identity']);
        $tea_info['level_str'] = @E\Enew_level::get_simple_desc( $tea_info['level']);


        $tea_info['phone'] = \App\Helper\Utils::get_teacher_contact_way($tea_info); 

        $arr = explode(",",@$tea_info['quiz_analyse']);
        $tea_info['quiz_analyse'] = $arr[0];
        if(!empty($tea_info['create_time'])){
            $tea_info['create_time'] = date('Y-m-d ',$tea_info['create_time']);
        }else{
            $tea_info['create_time'] = "";
        }
        if(!empty($tea_info['train_through_new_time'])){
            $tea_info['train_through_new_time'] = date('Y-m-d',$tea_info['train_through_new_time']);
        }else{
            $tea_info['train_through_new_time'] = "";
        }
        //  $tags_list = json_decode(@$tea_info["teacher_tags"],true);

        $arr_text= explode(",",@$tea_info["teacher_textbook"]);
        foreach($arr_text as $vall){
            @$tea_info["textbook"] .=  E\Eregion_version::get_desc ($vall).",";
        }
        @$tea_info["textbook"] = trim($tea_info["textbook"],",");

        if(empty(@$tea_info["teacher_tags"])){
            $tags_list=[];
        }else{
            $tag= json_decode($tea_info["teacher_tags"],true);
            $tags_list=[];
            if(is_array($tag)){
                foreach($tag as $d=>$t){
                    $tags_list[]= $d."  ".$t;
                }
            }
        }



        $adminid      = $this->get_account_id();
        $account_role = $this->t_manager_info->get_account_role($adminid);

        return $this->pageView(__METHOD__,null,[
            "tea_info"     => $tea_info,
            "account_role" => $account_role,
            "adminid"      => $adminid,
            "tags_list"      => $tags_list,
            "acc"          => $this->get_account()
        ]);
    }

    public function get_teacher_info_for_js(){
        $teacherid = $this->get_in_teacherid();
        $tea_info  = $this->t_teacher_info->get_teacher_info_all($teacherid);
        $arr_text= explode(",",@$tea_info["teacher_textbook"]);
        foreach($arr_text as $vall){
            @$tea_info["textbook"] .=  E\Eregion_version::get_desc ($vall).",";
        }
        @$tea_info["textbook"] = trim($tea_info["textbook"],",");

        return $this->output_succ(["data"=>$tea_info]);
    }

    public function get_tea_arr_list(){
        $tea_all_list = $this->t_teacher_info->get_teacher_all_info_list();
        $tea_arr= [];
        foreach($tea_all_list as $item){
            $tea_arr[$item['teacherid']] = $item['nick'];
        }
        $tea_list = json_encode($tea_arr);
        return $tea_list;
    }
    public function get_free_time_js()
    {
        $teacherid     = $this->teacherid;
        $free_time_new = $this->t_teacher_freetime_for_week->get_free_time_new($teacherid);
        $date_week     = \App\Helper\Utils::get_week_range(time(),1);
        $start_time    = $date_week["sdate"];
        $end_time      = $start_time + 21*86400;
        $test_lesson_list = $this->t_lesson_info->get_teacher_week_test_lesson_info($teacherid,$start_time,$end_time);
        foreach($test_lesson_list as &$val){
            $lesson_start = $val["lesson_start"];
            $date_week_test   = \App\Helper\Utils::get_week_range($lesson_start,1);
            $val["week"] = intval(( $lesson_start-$date_week_test["sdate"])/86400)+1;
            $val["start"] = date("H",$lesson_start);
            $val["end"] = date("H",$val["lesson_end"]);
        }
        $common_lesson_config= $this->t_week_regular_course->get_lesson_info($teacherid,-1);
        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stime=$date["sdate"];
        foreach ( $common_lesson_config as &$item ) {
            $start_time=$item["start_time"];
            $end_time=$item["end_time"];

            $arr=explode("-",$start_time);
            $item["week"]=$arr[0];
            $item["lesson_start"]=substr(@$arr[1],0,2);
            $item["lesson_end"]=substr(@$item["end_time"],0,2);

            //得到周几的开始时间
            // $day_start=$stime + ($week-1)*86400;
            /*$item["start_time_ex"] = strtotime(date("Y-m-d", $day_start)." $start_time")*1000;
            $item["end_time_ex"]   = strtotime(date("Y-m-d", $day_start)." $end_time")*1000;
            $item["nick"]          = $this->cache_get_student_nick($item["userid"]);
            $item["teacher"]       = $this->cache_get_teacher_nick($item["teacherid"]);*/
        }

        //dd($common_lesson_config);
        return  $this->output_succ( [ "data" =>$free_time_new,"lesson"=>$common_lesson_config,"test_lesson"=>$test_lesson_list ] );
    }

    public function free_time()
    {
        $teacherid = $this->teacherid;
        $ret_info  = \App\Helper\Utils::list_to_page_info([]);

        $now      = time(NULL);
        $week_arr = \App\Helper\Utils::get_week_range( $now ,1);
        $week_start_time = $week_arr["sdate"];

        return $this->pageView(__METHOD__, $ret_info , [
            "week_start_time" => $week_start_time
        ]);
    }

    public function update_free_time(){
        $teacherid = $this->teacherid;
        $free_time = $this->get_in_str_val("free_time");

        $this->t_teacher_freetime_for_week->field_update_list($teacherid,[
            "free_time_new" => $free_time,
        ]);
        return $this->output_succ();
    }

    public function set_teacher_info(){
        $teacherid              = $this->get_in_int_val("teacherid");
        $nick                   = $this->get_in_str_val("nick");
        $realname               = $this->get_in_str_val("realname");
        $gender                 = $this->get_in_int_val("gender");
        $birth                  = $this->get_in_str_val("birth");
        $work_year              = $this->get_in_int_val("work_year");
        $phone_spare            = $this->get_in_int_val("phone_spare");
        $putonghua_is_correctly = $this->get_in_int_val("putonghua_is_correctly");
        $email                  = $this->get_in_str_val("email");
        $advantage              = $this->get_in_str_val("advantage");
        $base_intro             = $this->get_in_str_val("base_intro");
        $dialect_notes          = $this->get_in_str_val("dialect_notes");
        $teaching_achievement   = $this->get_in_str_val("teaching_achievement");
        $parent_student_evaluate= $this->get_in_str_val("parent_student_evaluate");
        $qq_info                = $this->get_in_str_val("qq_info");
        $age                    = $this->get_in_int_val("age");
        $identity               = $this->get_in_int_val("identity");
        $teacher_textbook       = $this->get_in_str_val("teacher_textbook");

        if(!empty($email)){
            if(preg_match('/^[1-9]\d{4,10}$/',$email)){
                $email = $email."@qq.com";
            }else{
                $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
                if ( !preg_match( $pattern, $email )){
                   return outputJson(array(
                        'ret' => -1,
                        'info' => "邮箱格式有误 ",
                    ));
                }
            }
        }

        $ret = \App\Helper\Utils::check_phone($phone_spare);
        if(!$ret){
            return $this->output_err("手机号错误!");
        }

        if(!preg_match("/^\d*$/",$qq_info)){
            return outputJson(array(
                'ret' => -1,
                'info' => "请输入有效的ＱＱ号码 ",
            ));
        }

        if(!empty($birth)){
            $birth = substr($birth,0,4).''.substr($birth,5,2).''.substr($birth,8,2);
        }

        $this->t_teacher_info->field_update_list($teacherid,[
            "nick"                   => $nick,
            "email"                  => $email,
            "phone_spare"            => $phone_spare,
            "gender"                 => $gender,
            "birth"                  => $birth,
            "work_year"              => $work_year,
            "putonghua_is_correctly" => $putonghua_is_correctly,
            "dialect_notes"          => $dialect_notes,
            "parent_student_evaluate"=> $parent_student_evaluate,
            "teaching_achievement"   => $teaching_achievement,
            "age"                    => $age,
            "teacher_textbook"       => $teacher_textbook,
            "qq_info"                => $qq_info,
            "identity"               => $identity,
        ]);

        return $this->output_succ();
    }

    public function set_teacher_face(){
        $teacherid = $this->get_in_int_val("teacherid");
        $face = $this->get_in_str_val("face");
        $domain = config('admin')['qiniu']['public']['url'];
        $face = $domain.'/'.$face;
        $this->t_teacher_info->field_update_list($teacherid,[
            "face" => $face,
        ]);
        // dd();
        return $this->output_succ();

    }

    public function set_teacher_quiz_analyse(){
        $teacherid = $this->get_in_int_val("teacherid");
        $quiz_analyse = $this->get_in_str_val("quiz_analyse");
        $domain = config('admin')['qiniu']['public']['url'];
        $quiz_analyse = $domain.'/'.$quiz_analyse;
        $this->t_teacher_info->field_update_list($teacherid,[
            "quiz_analyse" => $quiz_analyse,
        ]);
        return $this->output_succ();

    }

    public function set_teacher_jianli(){
        $teacherid = $this->get_in_int_val("teacherid");
        $jianli = $this->get_in_str_val("jianli");
        $domain = config('admin')['qiniu']['public']['url'];
        $jianli = $domain.'/'.$jianli;
        $this->t_teacher_info->field_update_list($teacherid,[
            "jianli" => $jianli,
        ]);
        return $this->output_succ();

    }



    public function common_time() {
        $teacherid = $this->get_in_teacherid();

        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function common_time_new() {
        $teacherid = $this->get_in_teacherid();

        $date=\App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time=$date["sdate"];
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function avoid(){
        dd("xx");
    }
    public function get_lesson_list (){
        $teacherid   = $this->get_in_teacherid();
        $start_date  = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL) ));
        $end_date    = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400*7 ));
        $lesson_type = $this->get_in_el_contract_type(-1, "lesson_type");
        $page_num    = $this->get_in_page_num();
        $lessonid    = $this->get_in_lessonid(-1);

        #list($start_time,$end_time) = $this->get_in_date_range(-7,0);
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;

        $lesson_type_in_str="";
        switch ($lesson_type){
        case  E\Econtract_type::V_0:
            $lesson_type_in_str="0,1,2,3";
            break;
        case  E\Econtract_type::V_2 :
            $lesson_type_in_str="2";
            break;
        case  E\Econtract_type::V_1001 :
            $lesson_type_in_str="1001,1002,1003";
            break;
        case  E\Econtract_type::V_3001 :
            $lesson_type_in_str="3001";
            break;
        default :
            break;
        }
        $get_flag_color_func= function($v) {
            if ($v)  {
                $color="green";
            }else{
                $color="red";
            }
            $desc=E\Eboolean::get_desc($v);
            return "<font color=$color>$desc<font>";
        };

        $ret_info=$this->t_lesson_info_b2->get_teacher_lesson_list_new(  $page_num,$teacherid,$start_time,$end_time,$lesson_type,$lessonid);

        foreach($ret_info["list"] as &$item){
            $item["lesson_time"]     = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            E\Econtract_type::set_item_value_str($item,"lesson_type");

            $item["lesson_num_str"]  = "第".$item["lesson_num"]."次课";
            if($item["lesson_type"]<1000){
                $item["lesson_course_name"] = $this->cache_get_student_nick($item["userid"]);
            }elseif($item["lesson_type"]<3000){
                $item["lesson_course_name"]='';
            }else{
                $item["lesson_course_name"]= \App\Helper\Utils::fmt_lesson_name($item["grade"],$item["subject"],$item["lesson_num"]);
            }
            $lessonid    = $item["lessonid"];
            $lesson_type = $item['lesson_type'];

            $item['textbook']='';
            $item['tea_comment_str']="<font color=red>-<font>";
            if ($lesson_type<1000) {
                if($lesson_type==2){
                    $item['textbook']=E\Eregion_version::get_desc( $item['editionid']);
                    $tea_comment=$this->t_seller_student_info->get_lesson_content($item['lessonid']);
                }else{
                    $tea_comment=$this->t_lesson_info->get_stu_performance($item['lessonid']);
                }
                $item['tea_comment']=$tea_comment==''?0:1;
                $item['tea_comment_str']=$get_flag_color_func($item['tea_comment']);
            }elseif($lesson_type>=3000 && $lesson_type<4000){
                $ret_homework=$this->t_small_lesson_info->get_pdf_homework($item['lessonid']);
                if ($ret_homework) {
                    $item['homework_status'] = $ret_homework['work_status'];
                    $item['issue_url'] = $ret_homework['issue_url'];
                    $item['pdf_question_count'] = $ret_homework['pdf_question_count'];
                }
            }
            $item["pdf_status_str"]= $get_flag_color_func( $item["tea_status"])."/"
                                   . $get_flag_color_func( $item["stu_status"])."/"
                                   . $get_flag_color_func( $item["homework_status"]);
            $item["price"] /=100;
            $item["pay_flag_str"] ="";
            $item["pay_flag"] =-2;
            $item["pay_info"] ="";
            if ( $item["lesson_type"] ==2 ) {
                if($item["price"])  {
                    $item["pay_flag"]=1;
                    $item["pay_info"]="金额:". $item["price"] ;
                }else if ( $item["test_lesson_order_fail_flag"] ) {
                    $item["pay_flag"]=2;
                    $item["pay_info"]="说明:". E\Etest_lesson_order_fail_flag::get_desc($item["test_lesson_order_fail_flag"] )  ."|".$item["test_lesson_order_fail_desc"] ;
                }else{
                    $item["pay_flag"]=0;
                }
                $item["pay_flag_str"]=  \App\Helper\Common::get_set_boolean_color_str($item["pay_flag"] );
            }

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function lesson_list () {
        $teacherid = $this->teacherid;
        $ret_info=\App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info,['teacherid'=>$teacherid]);

    }
    public function get_lesson_time_js() {
        $teacherid = $this->get_in_teacherid();
        $timestamp = $this->get_in_int_val("timestamp");
        $type      = $this->get_in_int_val("type",0);

        if ($timestamp == 0) {
            $timestamp = time();
        }

        if($type==0) { //
            $ret_week=\App\Helper\Utils::get_week_range($timestamp,1);
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
        }else{
            $ret_week=\App\Helper\Utils::get_month_range($timestamp) ;
            $start_time=$ret_week["sdate"];
            $end_time=$ret_week["edate"];
        }

        $lesson_list=$this->t_lesson_info->get_teacher_lesson_info($teacherid,$start_time,$end_time);
        foreach($lesson_list as &$item) {
            $nick=$this->cache_get_student_nick($item["userid"]);
            $item["month_title"]= $nick  ;
            $item["week_title"]=  "学生:$nick";
        }
        return $this->output_succ(["lesson_list"=>$lesson_list]);
    }

    public function get_user_info()
    {
        $userid= $this->get_in_int_val("userid");
        $phone=$this->t_student_info->get_phone($userid);
        $row=$this->t_seller_student_info->field_get_list($phone, "*");
        return outputjson_success(["data"=> $row  ]);
    }

    public function teacher_assess(){
        $teacherid     = $this->teacherid;
        $page_num    = $this->get_in_page_num();
        $ret_info = $this->t_teacher_assess->get_assess_info($teacherid,$page_num);
        foreach($ret_info["list"] as &$item){
            $item['assess_nick'] = $this->cache_get_account_nick($item['assess_adminid']);
            $item['assess_res_str'] = E\Eassess_res::get_desc($item["assess_res"]) ;
            $item['assess_time_str'] = date("Y-m-d H:i:s",$item["assess_time"]) ;
        }
        $assess_num = $this->t_teacher_info->field_get_value($teacherid,"assess_num");
        return $this->pageView(__METHOD__,$ret_info,['assess_num'=>$assess_num]);
    }

    public function assess_del(){
        $teacherid= $this->get_in_int_val("teacherid");
        $assess_time= $this->get_in_int_val("assess_time");
        $ret = $this->t_teacher_assess->row_delete_2($teacherid,$assess_time);
        $assess_num = $this->t_teacher_info->field_get_value($teacherid,"assess_num")-1;
        if($ret){
            $this->t_teacher_info->field_update_list($teacherid,["assess_num"=>$assess_num]);
        }

        return $this->output_succ();
    }
    public function add_teacher_assess(){
        $teacherid= $this->get_in_int_val("teacherid");
        $assess_res= $this->get_in_int_val("assess_res");
        $content= trim($this->get_in_str_val("content"));
        $advise_reason= trim($this->get_in_str_val("advise_reason"));
        $assess_time= time();
        $assess_adminid=$this->get_account_id();
        $ret = $this->t_teacher_assess->row_insert([
            "teacherid"  => $teacherid ,
            "assess_adminid"  => $assess_adminid,
            "content"=>$content,
            "assess_time"=>$assess_time,
            "advise_reason"=>$advise_reason,
            "assess_res"=>$assess_res
        ]);
        $assess_num = $this->t_teacher_info->field_get_value($teacherid,"assess_num")+1;
        if($ret){
            $this->t_teacher_info->field_update_list($teacherid,["assess_num"=>$assess_num]);
        }

        return $this->output_succ();

    }

    public function update_teacher_assess(){
        $teacherid= $this->get_in_int_val("teacherid");
        $assess_res= $this->get_in_int_val("assess_res");
        $content= trim($this->get_in_str_val("content"));
        $advise_reason= trim($this->get_in_str_val("advise_reason"));
        $assess_time= $this->get_in_int_val("assess_time");
        $ret = $this->t_teacher_assess->field_update_list_2($teacherid,$assess_time,[
            "content"=>$content,
            "advise_reason"=>$advise_reason,
            "assess_res"=>$assess_res
        ]);

        return $this->output_succ();

    }


    public function add_meeting_info(){
        $create_time = $this->get_in_str_val("create_time");
        $theme = $this->get_in_str_val("theme");
        $summary = $this->get_in_str_val("summary");
        $address = $this->get_in_str_val("address");
        $moderator = $this->get_in_str_val("moderator");
        if(empty($create_time)){
            return outputJson(array('ret' => -1, 'info' => '时间不能为空'));
        }else{
            $create_time = strtotime($create_time);
        }
        $this->t_teacher_meeting_info->row_insert([
            "create_time"=>$create_time,
            "theme"=>$theme,
            "summary"=>$summary,
            "address"=>$address,
            "moderator"=>$moderator
        ]);
        $ret_tea = $this->t_teacher_info->get_teacher_all_info_list();
        foreach($ret_tea as $item){
            $this->t_teacher_meeting_join_info->row_insert([
                "create_time"=>$create_time,
                "teacherid"=>$item['teacherid'],
            ]);

        }
        return $this->output_succ();
    }

    public function update_meeting_info(){
        $create_time = $this->get_in_str_val("create_time");
        $theme = $this->get_in_str_val("theme");
        $summary = $this->get_in_str_val("summary");
        $address = $this->get_in_str_val("address");
        $moderator = $this->get_in_str_val("moderator");
        $id = $this->get_in_int_val("id");
        if(empty($create_time)){
            return outputJson(array('ret' => -1, 'info' => '时间不能为空'));
        }else{
            $create_time = strtotime($create_time);
        }
        $this->t_teacher_meeting_info->field_update_list($id,[
            "create_time"=>$create_time,
            "theme"=>$theme,
            "summary"=>$summary,
            "address"=>$address,
            "moderator"=>$moderator
        ]);
        return $this->output_succ();
    }


    public function teacher_meeting_info_del(){
        $id = $this->get_in_int_val("id");
        $this->t_teacher_meeting_info->row_delete($id);
        return $this->output_succ();
    }
    public function set_teacher_meeting_info(){
        $id = $this->get_in_int_val("id");
        $teacher_join_info = $this->get_in_str_val("teacher_join_info");

        $this->t_teacher_meeting_info->field_update_list($id,['teacher_join_info'=>$teacher_join_info]);

        return $this->output_succ();
    }
    public function set_teacher_join_info(){
        $join_info = $this->get_in_int_val("join_info");
        $create_time = $this->get_in_str_val("create_time");
        $teacherid_list = json_decode($this->get_in_str_val("teacherid_list"));
        foreach($teacherid_list as $item){
            $this->t_teacher_meeting_join_info->field_update_list_2($item,$create_time,['join_info'=>$join_info]);
        }
        return $this->output_succ();
    }

    public function set_teacher_join_info_once(){
        $join_info = $this->get_in_int_val("join_info");
        $create_time = $this->get_in_str_val("create_time");
        $teacherid = $this->get_in_int_val("teacherid");
        $this->t_teacher_meeting_join_info->field_update_list_2($teacherid,$create_time,['join_info'=>$join_info]);
        return $this->output_succ();
    }

    public function get_all_teacher_info_new(){
        $gender     = $this->get_in_int_val('gender',-1);
        $nick_phone = trim($this->get_in_str_val('nick_phone',""));

        $page_num     = $this->get_in_page_num();
        $ret_info = $this->t_teacher_info->get_all_teacher_info_new($gender,$nick_phone,$page_num);
        foreach($ret_info['list'] as &$item){
            $item['gender_str'] = E\Egender::get_desc($item['gender']);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return outputjson_success(array('data' => $ret_info));
    }

    public function  file_store()   {
        $teacherid = $this->get_in_int_val("teacherid");
        $dir= $this->get_in_str_val("dir");
        //$teacherid=10001;
        if (!$dir) {
            $dir= "/";
        }


        $store=new \App\FileStore\file_store_tea();
        $ret_list=$store->list_dir($teacherid, $dir);
        foreach ( $ret_list  as &$item  ) {
            if (!$item["is_dir"]) {
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            }
            $item["abs_path"] =  $dir .$item["file_name"];
            $item["file_size"]= \App\Helper\Common::size_str(@$item["file_size"] );
        }

        array_unshift( $ret_list, [ "is_dir" => true,
                                    "file_name" => "返回上级目录" ,
                                    "abs_path" => dirname($dir),
                                    "file_size" =>"",
                                    "create_time" =>"",
        ] );


        return $this->pageView(
            __METHOD__,
            \App\Helper\Utils::list_to_page_info($ret_list) ,["cur_dir"=>$dir] );

    }

    public function file_store_add_dir()  {
        $teacherid = $this->get_in_int_val("teacherid");
        $dir= $this->get_in_str_val("dir");
        $dir_name = trim($this->get_in_str_val("dir_name"));
        $obj_dir=$dir.$dir_name;

        \App\Helper\Utils::logger("obj_dir:$obj_dir");
        $store=new \App\FileStore\file_store_tea();
        $store->add_dir($teacherid,$obj_dir);
        \App\Helper\Utils::logger("ok ..");

        return $this->output_succ();
    }
    public function get_upload_token() {
        $store=new \App\FileStore\file_store_tea();
        $dir = $this->get_in_str_val("dir");
        $teacherid = $this->get_in_int_val("teacherid");

        $pre_dir=$store->get_dir($teacherid,$dir );
        $token=$store->get_upload_token();
        return $this->output_succ(["upload_token"=> $token, "pre_dir" => $pre_dir ]);

    }
    public function get_download_url() {
        $file_path = $this->get_in_str_val("file_path");
        $teacherid = $this->get_in_int_val("teacherid");

        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $file_path = $store->get_file_path($teacherid,$file_path);
        $authUrl = $auth->privateDownloadUrl("http://file-store.leo1v1.com/". $file_path );
        return $this->output_succ(["url" => $authUrl]);
    }
    public function get_share_link() {
        $teacherid= $this->get_in_teacherid();
        $share_path = $this->get_in_str_val("share_path");

        $now=time();
        $create_time=$now;
        $end_time=$create_time+86400*10;
        $arr=[
            "teacherid" => $teacherid,
            "share_path" => $share_path,
            "create_time" => $create_time, 
            "end_time" => $end_time ,
            "md5_sum" => substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)
        ];
        $key= "xcwen142857xcwAB";
        $sign= \App\Helper\Common::encrypt( json_encode($arr),$key);

        return $this->output_succ(["sign"=> $sign] );
        //echo urlencode($sign)."<br/>";

        //echo \App\Helper\Common::decrypt($sign,$key);

    }

    public function file_store_rename() {
        $teacherid= $this->get_in_teacherid();
        $old_path= $this->get_in_str_val("old_path");
        $new_name= $this->get_in_str_val("new_name");
        $store=new \App\FileStore\file_store_tea();
        $store->rename_file($teacherid,$old_path,$new_name);
        return $this->output_succ();
    }

    public function update_teacher_subject_info(){
        $teacherid          = $this->get_in_int_val("teacherid");
        $subject            = $this->get_in_int_val("subject");
        $grade_start        = $this->get_in_int_val("grade_start");
        $grade_end          = $this->get_in_int_val("grade_end");
        $second_subject     = $this->get_in_int_val("second_subject");
        $second_grade_start = $this->get_in_int_val("second_grade_start");
        $second_grade_end   = $this->get_in_int_val("second_grade_end");

        // $old_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $old_arr=$this->t_teacher_info->field_get_list($teacherid,"subject,grade_start,grade_end,second_subject,second_grade_start,second_grade_end");
        $now_arr=[
            "subject"            => $subject,
            "grade_start"        => $grade_start,
            "grade_end"          => $grade_end,
            "second_subject"     => $second_subject,
            "second_grade_start" => $second_grade_start,
            "second_grade_end"   => $second_grade_end,
        ];
        $record_info=json_encode(["old"=> $old_arr,"new"=> $now_arr]);


        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "subject"            => $subject,
            "grade_start"        => $grade_start,
            "grade_end"          => $grade_end,
            "second_subject"     => $second_subject,
            "second_grade_start" => $second_grade_start,
            "second_grade_end"   => $second_grade_end,
        ]);

        if(!$ret){
            return $this->output_err("更新失败或无需更新！");
        }else{
            //增加修改记录
            $this->t_teacher_record_list->row_insert([
                "teacherid" => $teacherid,
                "type"      => 17,
                "record_info"=> $record_info,
                "acc"        => $this->get_account(),
                "add_time"   => time()
            ]);
        }
        return $this->output_succ();
    }

}
