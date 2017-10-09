<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Libs;
use \App\Helper\Config;
use Illuminate\Support\Facades\Redis ;

class test_code extends Controller
{

    use CacheNick;
    use TeaPower;
    var $br;
    var $red;
    var $blue;
    var $div;
    var $teacherid;

    var $level_simulate_count_key = "level_simulate_count";
    var $all_money_count_key      = "all_money_count";
    var $has_month_key            = "has_month";
    var $teacher_ref_rate_key     = "teacher_ref_rate";
    var $month_money_key          = "month_money";
    var $lesson_total_key         = "lesson_total";

    var $already_lesson_count_key          = "already_lesson_count_month";
    var $already_lesson_count_simulate_key = "already_lesson_count_simulate_month";
    var $money_month_key                   = "money_month";
    var $teacher_money_type_month_key      = "teacher_money_type_month";

    public function __construct(){
        $this->switch_tongji_database();
        $this->br="<br>";
        $this->red="<div color=\"red\">";
        $this->blue="<div color=\"blue\">";
        $this->div="</div>";
        if(\App\Helper\Utils::check_env_is_release()){
            $this->teacherid="50728";
        }else{
            $this->teacherid="60024";
        }
    }

    public function get_b_txt($file_name="b"){
        $info = file_get_contents("/tmp/".$file_name.".txt");
        $arr  = explode("\n",$info);
        return $arr;
    }

    public function get_success_lesson(){
        $day  = $this->get_in_int_val("day",30);
        $type = $this->get_in_int_val("type",2);

        $begin_time = $this->get_begin_time($type,$day);
        $list = $this->t_test_lesson_subject_sub_list->get_teacher_trial_success_list($begin_time,$type);
        dd($list);
    }

    public function get_begin_time($type,$day){
        $begin_time = strtotime("-$day day",time());
        if($type==2){
            $check_time = strtotime("2016-12-1");
        }elseif($type==3){
            $check_time = strtotime("2017-4-1");
        }

        if($begin_time<$check_time){
            $begin_time = $check_time;
        }
        return $begin_time;
    }

    /**
     * 切换工作室渠道的老师工资版本
     adrian
     */
    public function change_studio_tea_list(){
        $reference = $this->get_in_str_val("reference");
        if($reference==""){
            return $this->output_err("手机号不能为空!");
        }

        $tea_list = $this->t_teacher_info->get_teacher_list_by_reference($reference);
        foreach($tea_list as $val){
            if($val['trial_lecture_is_pass']>0 && $val['train_through_new']>0){
                echo $val['teacherid'];
                echo "<br>";
            }
            // $this->t_teacher_info->field_update_list($val['teacherid'],[
            //     "teacher_money_type" => E\Eteacher_money_type::V_6,
            //     "teacher_ref_type"   => E\Eteacher_ref_type::V_0,
            // ]);
        }
    }

    /**
     * 添加老师工资分类
     */
    public function add_money_type(){
        $file    = "/tmp/bb.txt";
        $content = file_get_contents($file); //读取文件中的内容
        $info    = explode("\n",$content);
        $br      = "<br>";
        $red     = "<div style=\"color:red\">";
        $div     = "</div>".$br;
        \App\Helper\Utils::debug_to_html( $info );

        /**
         * 0 teacher_money_type 1 level 2 grade 3 money 4 type
         */
        foreach($info as $val){
            $arr=explode("|",$val);
            if(is_array($arr)){
                if($arr[0]!=""){
                    $check_flag=$this->t_teacher_money_type->check_is_exists($arr[0],$arr[1],$arr[2]);
                    if(!$check_flag){
                        $this->t_teacher_money_type->row_insert([
                            "teacher_money_type" => $arr[0],
                            "level"              => $arr[1],
                            "grade"              => $arr[2],
                            "money"              => $arr[3],
                            "type"               => $arr[4],
                        ]);
                        echo $red;
                        echo "teacher_money_type:".$arr[0]." level:".$arr[1]." grade:".$arr[2]." money:".$arr[3]." type:".$arr[4];
                        echo $div;
                    }
                }
            }
        }
    }

    /**
     * 新版本升级推送
     */
    public function test_template(){
        $teacherid=$this->get_in_int_val("teacherid");
        if($teacherid==0){
            echo "teacherid is 0";
            exit;
        }

        // $tea_list['wx_openid'] = $this->t_teacher_info->get_wx_openid($teacherid);
        // $time     = strtotime("2017-4-21");
        $tea_list = $this->t_teacher_info->get_tea_wx_openid_list();
        foreach($tea_list as $val){
            $user_agent = json_decode($val['user_agent'],true);
            $os         = substr($user_agent["device_model"],0,4);
            $version    = $user_agent["version"];
            // if($os=="Win" || $os=="Mac"){
            //     if($version != "3.1.0"){
            //         $push_list[] = $val;
            //     }
            // }
            if($os=="iPad"){
                if($version != "5.0.4"){
                    $push_list[] = $val;
                }
            }
        }

        echo "<pre>";
        var_dump($push_list);
        echo "</pre>";
        exit;

        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data['first']    = "您有新的客户端版本等待更新";
        $data['keyword1'] = "【iPad老师端 5.0.4版本】";
        $data['keyword2'] = "\n-增加课堂中一键pdf上传功能";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "为了保证授课正常进行，请您参考【帮助中心】的【使用手册】教程尽快更新。";
        $url              = "http://admin.yb1v1.com/article_wx/leo_teacher_ipad";

        // \App\Helper\Utils::send_teacher_msg_for_wx($tea_list['wx_openid'] ,$template_id,$data,$url);
        // $job = new \App\Jobs\SendTeacherWx($tea_list,$template_id,$data,$url);
        // dispatch($job);
    }

    /**
     * 设置工作室助理的老师类型
     */
    public function set_teacher_type(){
        $list = $this->t_teacher_info->get_teacher_type_list();
        \App\Helper\Utils::debug_to_html(  );
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_type"=>22
            ]);
        }
    }


    public function get_lecture_list(){
        header("Content-type: text/html; charset=utf-8"); 
        $phone = $this->get_in_str_val("phone");
        if($phone==""){
            return $this->output_err("手机号为空！");
        }

        $teacher_info = $this->t_teacher_lecture_appointment_info->get_lecture_appointment_info($phone);
        if(empty($teacher_info)){
            return $this->output_err("您此手机号还未报名！请先报名才可录制试讲！");
        }
        $subject_list   = [];
        $grade_list     = [];
        $lecture_status = [];
        $ret_list       = [];
        $subject       = $teacher_info['subject_ex'];
        $grade         = $teacher_info['grade_ex'];
        $trans_subject = $teacher_info['trans_subject_ex'];
        $trans_grade   = $teacher_info['trans_grade_ex'];
        $grade_list[$subject] = E\Egrade::get_specify_select([100,200,300]);
        if(isset($trans_subject) && $trans_subject>0){
            $subject_list = E\Esubject::get_specify_select([$subject,$trans_subject]);
            $grade_list[$trans_subject] = $grade_list[$subject];
        }else{
            $subject_list = E\Esubject::$desc_map;
        }

        $ret_list = $this->get_teacher_lecture_list($teacher_info);
        $lecture_list = $this->t_teacher_lecture_info->get_lecture_list($phone);

        if(is_array($lecture_list) && !empty($lecture_list)){
            foreach($lecture_list as $l_val){
                $l_grade   = substr($l_val['grade'],0,1)."00";
                $l_subject = $l_val['subject'];
                $ret_key   = $l_subject."_".$l_grade;
                if(in_array($l_val['status'],[1,2])){
                    if(isset($grade_list[$l_subject])){
                        $grade_list[$l_subject] = array_diff_key($grade_list[$l_subject],[$l_grade]);
                    }
                }
                $ret_list[$ret_key]['status'] = $l_val['status'];
            }
        }

    }

    public function get_teacher_lecture_list($teacher_info){
        $ret_list = [];
        extract($teacher_info);
        $teacher_grade[$subject_ex]       = explode(",",$grade_ex);
        $teacher_grade[$trans_subject_ex] = explode(",",$trans_grade_ex);
        $identity = E\Eidentity::get_desc($teacher_type);
        foreach($teacher_grade as $s_key=>$s_val){
            $s_str = E\Esubject::get_desc($s_key);
            if($s_key>0){
                foreach($s_val as $g_val){
                    $g_str = E\Egrade::get_desc($g_val);
                    $data  = [
                        "title"        => $g_str.$s_str."试讲课",
                        "subject"      => $s_str,
                        "grade"        => $g_str,
                        "tea_nick"     => $name,
                        "identity_str" => $identity,
                        "status"       => -1,
                    ];
                    $ret_key = $s_key."_".$g_val;
                    $ret_list[$ret_key] = $data;
                }
            }
        }
        return $ret_list;
    }

    public function get_teacher_lesson_count(){
        $this->switch_tongji_database();
        $list = $this->t_lesson_info_b2->get_teacher_lesson_count();

        foreach($list as $val){
            $teacher_money_type = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $level              = E\Elevel::get_desc($val['level']);
            echo $val['nick']."|".$val['phone']."|".$teacher_money_type."|".$level."|".$val['lesson_all_count'];
            echo "<br>";
        }
    }

    public function subject_teacher(){
        $list = $this->t_lesson_info_b2->get_subject_teacher();
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "subject"       => $val['subject'],
                "grade_part_ex" => substr($val['grade'],0,1),
            ]);
            echo $val['grade'];
            echo "<br>";
            echo $val['subject'];
            echo "<br>";
            echo $val['phone'];
            exit;
        }
    }

    public function get_data(){
        //6月份新签合同的课时，原始价格，折扣价格，赠送课时，年级
        $start = strtotime("2017-6-1");
        $end   = strtotime("2017-7-1");

        $list=$this->t_order_info->get_new_order($start,$end);
        foreach($list as $val){
            $grade_str = E\Egrade::get_desc($val['grade']);
            echo $val['nick']."|".$grade_str."|".($val['lesson_total']/100)."|".($val['price']/100)."|".($val['discount_price']/100)
                             ."|".($val['free_lesson_total']/100);
            echo "<br>";
        }
    }

    public function get_not_through_user(){
        $start_date = $this->get_in_str_val("start_date","2017-6-17");
        $start_time = strtotime($start_date);
        $end_time   = time();
        $list = $this->t_train_lesson_user->get_not_through_user($start_time,$end_time);
        
        foreach($list as $val){
            echo $val['nick']."|".$val['phone']."|".$val['score']."|".date("Y-m-d H:i",$val['create_time']);
            echo "<br>";
        }
    }

    public function get_pingtai(){
        $num                = $this->get_in_int_val("num");
        $teacher_money_type = $this->get_in_str_val("teacher_money_type","5");
        $level              = $this->get_in_str_val("level","");

        $start_date = strtotime("2017-4-1");
        $start_time = strtotime("+$num month",$start_date);
        $end_time   = strtotime("+1 month",$start_time);
        $this->t_lesson_info_b2->switch_tongji_database();
        $list = $this->t_lesson_info_b2->get_lesson_total_list($start_time,$end_time,$teacher_money_type,$level);
        echo "姓名|工资类型|等级|创建时间|转化率|常规课时|试听课时|总课时|学生数";
        echo "<br>";

        foreach($list as $val){
            $ref_str            = E\Eteacher_ref_type::get_desc($val['teacher_ref_type']);
            $level_str          = E\Elevel::get_desc($val['level']);
            $teacher_money_type_str = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $create_time        = date("Y-m-d",$val['create_time']);
            $trial_lesson_total = $val['trial_lesson_total']/100;
            $lesson_total       = $val['lesson_total']/100;
            $all_total          = $trial_lesson_total+$lesson_total;
            $stu_num            = $this->t_lesson_info_b2->get_stu_num($val['teacherid'],$start_time,$end_time);
            echo $val['nick']."|".$teacher_money_type_str."|".$level_str."|".$create_time
                             ."|".$val['test_transfor_per']."|".$lesson_total."|".$trial_lesson_total
                             ."|".$all_total."|".$stu_num;
            echo "<br>";
        }
    }

    public function get_stu_num(){
        $start = strtotime("2017-4-1");
        $end   = strtotime("2017-7-1");

        foreach($info as $val){
            $lesson_list = $this->t_lesson_info_b2->get_lesson_total();
            $teacherid = $this->t_teacher_info->get_teacherid_by_name($val);
            if($teacherid>0){
                $stu_num=$this->t_lesson_info_b2->get_stu_num($val,$start,$end);
                echo $val."|".$stu_num;
                echo "<br>";
            }
        }
    }


    public function get_wx_openid(){
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data = [
            "first"    => "老师您好，您的银行卡还未绑定。",
            "keyword1" => "银行卡绑定提醒",
            "keyword2" => "\n请您尽快绑定银行卡信息，上月的薪资将于本月10日左右发出，如果今天18:00前还未绑定，将和本月工资一起算在下月10号发放，请知悉",
            "keyword3" => date("Y-m-d H:i:s",time()),
            "remark" => "点击详情绑定银行卡",
        ];
        $url = "http://wx-teacher-web.leo1v1.com/bank_info.html";

        $arr = $this->get_b_txt();

        foreach($arr as $val){
            if($val!=""){
                $info = $this->t_teacher_info->get_teacher_info_by_phone($val);
                if(!empty($info) && $info['bankcard']==""){
                    echo $info['phone']."|".$info['realname']."|".$info['bankcard'];
                    echo "<br>";
                }
            }
        }
    }

    public function add_new_teacher(){
        $arr = $this->get_b_txt();
        $lesson_start = strtotime("2017-7-12 21:00");
        $lesson_end   = strtotime("2017-7-12 23:59");
        $grade        = 101;
        $subject      = 11;

        foreach($arr as $val){
            if($val!=""){
                $t_v = explode("|",$val);
                $phone = $t_v[2];
                $tea_info  = $this->t_teacher_info->get_teacher_info_by_phone($phone);
                $teacherid = $tea_info['teacherid'];
                $this->t_teacher_info->field_update_list($teacherid,[
                    "wx_use_flag"=>1,
                ]);
                // $user_info = $this->t_student_info->get_student_info_by_phone($t_v[2]);
                $this->cache_get_assistant_nick($assistantid);
            }
        }
    }

    public function reset_teacher_ref_type(){
        $list = $this->t_teacher_info->get_admin_teacher_list();
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_ref_type" => 41
            ]);
        }
    }

    public function test_lesson_total(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $this->switch_tongji_database();
        $list = $this->t_lesson_info_b2->test_lesson_total($start_time,$end_time,0);
        echo count($list);
        echo "<br>";
        $full_start  = strtotime("-1 month",$start_time);
        $full_end    = strtotime("-1 month",$end_time);
        $full_list = $this->t_lesson_info_b2->test_lesson_total($full_start,$full_end,3);
        echo count($full_list);
    }
    public function get_create_teacher(){
        $num = $this->get_in_int_val("num",0);
        $start_time = strtotime("2017-4-1");
        $end_time = strtotime("2017-7-1");

        $list = $this->t_teacher_info->get_trial_teacher_month($start_time,$end_time);
        echo "姓名|手机|入职日期|试听课时|成功数|常规课时|转化率";
        echo "<br>";

        foreach($list as &$val){
            $create_start = $val['create_time'];
            $create_end   = $val['create_time']+30*86400;
            $create_date = date("Y-m-d",$val['create_time']);
            $success = $this->t_lesson_info->get_teacher_test_person_num_list( $create_start,$create_end,-1,-1,[$val['teacherid']]);
            \App\Helper\Utils::check_isset_data($success[$val['teacherid']]['have_order'],0);
            $succ_trial = $success[$val['teacherid']]['have_order'];
            $trial_count  = $val['trial_count']/100;
            $normal_count = $val['normal_count']/100;
            if($trial_count==0){
                $succ_per="0%";
            }else{
                $succ_per = (round($succ_trial/$trial_count,2)*100)."%";
            }
            echo $val['nick']."|".$val['phone']."|".$create_date."|".$trial_count."|".$succ_trial."|".$normal_count."|".$succ_per;
            echo "<br>";
        }
    }

    /**
     * 获取学生第一次签约时间，科目明细
     */
    public function get_stu_order_list(){
        $start_time = strtotime("2016-5-1");
        $end_time   = strtotime("2017-6-30");
        $list       = $this->t_order_info->get_stu_order_list($start_time,$end_time);
        $stu_list   = [];
        foreach($list as $val){
            $userid     = $val['userid'];
            $phone      = $val['phone'];
            $realname   = $val['realname']!=""?$val['realname']:$val['nick'];
            $grade      = E\Egrade::get_desc($val['grade']);
            $first_time = date("Y-m-d H:i",$val['first_time']);
            $lesson_total = $val['lesson_total'];
            $subject    = E\Esubject::get_desc($val['subject']);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['phone'],$phone,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['realname'],$realname,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['grade'],$grade,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['first_time'],$first_time,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['subject'],$subject,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['lesson_total'],$lesson_total);
            if(isset($stu_list[$userid]['subject']) && !strstr($stu_list[$userid]['subject'],$subject)){
                $stu_list[$userid]['subject'] .= (",".$subject);
            }
        }
        echo "姓名|手机|首次签约时间|年级|总课时数|科目";
        echo "<br>";
        foreach($stu_list as $s_val){
            echo $s_val['realname']."|".$s_val['phone']."|".$s_val['first_time']."|".$s_val['grade']."|".($s_val['lesson_total']/100)
                                   ."|".$s_val['subject'];
            echo "<br>";
        }
    }

    /**
     * 今日头条渠道数据
     */
    public function get_jr() {
        $page_info  = $this->get_in_page_info();
        $start_time = strtotime("2017-5-1");
        $end_time   = time();
        $opt_type_str = $this->get_in_str_val("opt_type_str","all_count");
        $key1 = $this->get_in_str_val("key1","今日头条");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );


        $ret_info= $this->t_seller_student_new->get_origon_list( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            echo $item['phone'];
            echo "<br>";
        }
    }

    /**
     * 重置田克平
     */
    public function get_tkp(){
        $lesson_start=strtotime("2017-8-1");
        $tea_list = $this->t_teacher_info->get_tkp();
        $this->switch_tongji_database(false);
        foreach($tea_list as $tea_val){
            $teacherid=$tea_val['teacherid'];
            $this->t_teacher_info->field_update_list($teacherid,[
                "teacher_money_type"=>4
            ]);
            $this->t_lesson_info_b2->reset_lesson_teacher_money_type($teacherid,$lesson_start);
        }
    }

    public function test_email(){
        $email = "wg392567893@163.com";
        $ret= \App\Helper\Common::send_paper_mail_new($email,"测试邮件","is test email");
        dd($ret);
    }

    public function reset_test_appointment(){
        if(\App\Helper\Utils::check_env_is_test()){
            $phone     = "99900020001";
            $id=$this->t_teacher_lecture_appointment_info->get_id_by_phone($phone);
        }else{
            $phone=$this->get_in_str_val("phone");
            if($phone==""){
                return $this->output_err("请输入手机号");
            }
            $info = $this->t_teacher_lecture_appointment_info->get_simple_info($phone);
            $id = $info['id'];
        }

        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "subject_ex"       => "1",
            "grade_ex"         => "100",
            "trans_grade_ex"   => "",
            "trans_subject_ex" => "",
            "grade_1v1"        => "",
            "trans_grade_1v1"  => "",
        ]);

        $teacherid= $this->t_teacher_info->get_teacherid_by_phone($phone);
        $lessonid_list=$this->t_lesson_info_b2->get_lessonid_list_by_userid($teacherid);
        if(is_array($lessonid_list) && !empty($lessonid_list)){
            foreach($lessonid_list as $val){
                $this->t_lesson_info->row_delete($val['lessonid']);
            }
        }
        $id=$this->t_teacher_lecture_info->get_id_list_by_phone($phone);
        if(is_array($id) && !empty($id)){
            foreach($id as $val_l){
                $this->t_teacher_lecture_info->row_delete($val_l['id']);
            }
        }

    }

    public function add_teacher_money_type_new(){
        $arr = $this->get_b_txt();
        // 0 teacher_money_type | 1 level | 2 k1-k5 money |3 k6-k8 money |4 k9 money |5 k10-k11 money |6 k12 money |7 type

        foreach($arr as $val){
            if($val!=""){
                $add_arr = explode("|",$val);
                if(count($add_arr)==8){
                    $teacher_money_type = $add_arr[0];
                    $level              = $add_arr[1];
                    $type               = $add_arr[7];

                    $update_arr['teacher_money_type'] = $teacher_money_type;
                    $update_arr['level']              = $level;
                    $update_arr['type']               = $type;

                    $grade = 101;
                    for($i = 1;$i<=5;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[2]);
                    }

                    for($i=1;$i<=3;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[3]);
                    }

                    $this->add_teacher_money_type($update_arr,$grade,$add_arr[4]);

                    for($i=1;$i<=2;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[5]);
                    }

                    $this->add_teacher_money_type($update_arr,$grade,$add_arr[6]);
                }
            }
        }
    }

    public function add_teacher_money_type($update_arr,&$grade,$money){
        $check_flag = $this->t_teacher_money_type->check_is_exists($update_arr['teacher_money_type'],$update_arr['level'],$grade);
        if(!$check_flag && ($grade>=101 || $grade<=303)){
            $update_arr['grade']=$grade;
            $update_arr['money']=$money;
            $this->t_teacher_money_type->row_insert($update_arr);
        }
        $grade = \App\Helper\Utils::get_next_grade($grade);
    }

    public function set_simulate_info(){
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",4);
        $level              = $this->get_in_int_val("level",1);
        $level_simulate     = $this->get_in_int_val("level_simulate",1);

        $new_A = [
            "胡旭",
            "丁媛媛",
            "默建宾",
            "吉妍瑾",
            "李志伟",
            "邢平",
            "章一维",
            "付文",
            "董超",
            "马娇",
            "朱凯",
            "唐梦迪",
            "吴迎雪",
            "尹向烟",
            "李伟",
            "于艳",
            "张鑫",
            "王月",
            "李睿超",
            "卢雪瑞",
            "徐雪丽",
            "宁斯同",
            "李泽",
            "褚雪莲",
            "翁老师",
            "张习席",
            "周钦钦",
            "罗佳玲",
            "董昊",
            "桂莱樱",
            "任志琴",
            "刘学文",
            "毛好",
            "边水青",
            "张志敏",
            "毛志元",
            "赵凌云",
        ];

        $new_A_plus = [
            "张子琦",
            "矣子沁",
            "惠吉",
            "郭政一",
            "张礼海",
            "于梁梁",
            "张和清",
            "胡兴福",
            "汪小民",
            "杜琼",
            "成实",
            "孟德龙",
            "徐宁",
            "凌艺",
            "熊慧格",
            "孔春莉",
            "韩嘉亮",
            "黄志玲",
            "谢楠静",
            "卓媛容",
            "万里",
            "沙维娜",
            "田娇",
            "刘淼",
            "黄嵩婕",
            "王皓",
        ];

        $new_B_plus = [
            "魏晓晓",
            "许磊",
            "张杰-Johnny",
            "鞠东篱",
            "高歌",
            "闫佳",
            "税雄",
            "李凤喜",
            "韩涵（韩文君）",
        ];

        $new_B = [
            "陈桂琼",
            "陈懿",
            "房彩虹",
        ];

        $new_T = [
            "潘笑文",
            "刘平",
            "赵力红",
        ];
        //在职C->新版B
        // $this->set_simulate_info_by_list(0,0,1);
        //在职B->新版B+
        // $this->set_simulate_info_by_list(0,1,2);
        //高校C->新版B
        // $this->set_simulate_info_by_list(1,0,1);
        //外聘C->新版B
        // $this->set_simulate_info_by_list(2,0,1);
        //固定C->新版B
        // $this->set_simulate_info_by_list(3,0,1);
        $this->set_simulate_info_by_list($new_T,0,11);
        $this->set_simulate_info_by_list($new_B,0,1);
        $this->set_simulate_info_by_list($new_B_plus,0,2);
        $this->set_simulate_info_by_list($new_A,0,3);
        $this->set_simulate_info_by_list($new_A_plus,0,4);
    }

    public function set_simulate_info_by_list($list,$level,$level_simulate){
        if(!is_array($list)){
            $this->t_teacher_info->set_simulate_info($list,$level,$level_simulate);
        }else{
            foreach($list as $val){
                $count = $this->t_teacher_info->check_count_by_realname($val);
                if($count>1){
                    echo $this->red;
                    echo "more name:";
                    echo $val;
                    echo "more name end";
                    echo $this->div;
                }else{
                    $teacher_info = $this->t_teacher_info->get_teacher_info_by_realname_for_level_simulate($val,$level_simulate);
                    if(!empty($teacher_info)){
                        if($teacher_info['level_simulate'] != $level_simulate){
                            $ret = $this->t_teacher_info->field_update_list($teacher_info['teacherid'],[
                                "level_simulate" => $level_simulate
                            ]);
                            if($ret){
                                echo $this->blue;
                                echo $val;
                                echo "<br>";
                                echo "succ";
                                echo $this->div;
                            }else{
                                echo $this->red;
                                echo $val;
                                echo "<br>";
                                echo "fail";
                                echo $this->div;
                            }
                        }
                    }
                }
            }
        }
    }



    public function add_rule_type(){
        $rule_type = [
            1=>[
                0     => 0,
                1500  => 3,
                4500  => 6,
                7500  => 13,
                13500 => 16,
                18000 => 19
            ],2=>[
                0     => 0,
                15000 => 3,
                22500 => 7
            ],3=>[
                0     => 0,
                15000 => 4,
                22500 => 6
            ],4=>[
                0     => 0,
                1500  => 3,
                4500  => 6,
                10500 => 9,
                16500 => 12,
                22500 => 15,
                28500 => 18,
            ],5=>[
                0     => 0,
                1000  => 5,
                6000  => 10,
                12000 => 20,
            ],6=>[
                0     => 0,
                1000  => 4,
                4000  => 7,
                10000 => 10,
                16000 => 15,
                24000 => 20,
                33000 => 30,
            ]
        ];
        $reward_count_type = 1;
        foreach($rule_type as $key => $val){
            foreach($val as $k => $v){
                $check_flag = $this->t_teacher_reward_rule_list->check_reward_rule_is_exists($reward_count_type,$key,$k);
                if(!$check_flag){
                    $this->t_teacher_reward_rule_list->row_insert([
                        "reward_count_type" => $reward_count_type,
                        "rule_type"         => $key,
                        "num"               => $k,
                        "money"             => $v*100,
                    ]);
                }
            }
        }
    }

    /**
     * 刷新招师库李的年级和科目信息
     */
    public function refresh(){
        $subject_map = E\Esubject::$desc_map;
        $grade_map = E\Egrade::$desc_map;
        $list = $this->t_teacher_lecture_appointment_info->get_refresh_list();

        $num  = 0;
        foreach($list as $val){
            $num++;
            echo $val['id']."|".$val['phone']."|".$val['grade_ex']."|".$val['subject_ex'];

            // $grade_ex   = $this->check_str($grade_map,$val['grade_ex']);
            // $subject_ex = $this->check_str($subject_map,$val['subject_ex']);

            $grade_ex = $this->get_grade_ex_by_range($val['grade_start']);
            echo "|".$grade_ex;
            // if($grade_ex=="" && $subject_ex!=""){
            //     $grade_ex="100";
            // }
            // if($subject_ex=="" && $grade_ex!=""){
            //     $subject_ex="2";
            // }

            if($grade_ex!=""){
                // $this->t_teacher_lecture_appointment_info->field_update_list($val['id'],[
                //      "grade_ex" => $grade_ex,
                //      "subject_ex" => $subject_ex,
                // ]);

                echo "|update";
            }
            echo "<br>";
        }
    }

    public function check_str($map,$str){
        $ret = "";
        if($str!=""){
            foreach($map as $key=>$val){
                // $check_val = mb_substr($val,0,1,"utf-8");
                if(strstr($str,$val)){
                    $ret = $key;
                    break;
                }
            }
        }
        return $ret;
    }

    public function get_grade_ex_by_range($grade_range){
        switch($grade_range){
        case 1:case 2:
            $grade_ex="100";
            break;
        case 3:case 4:
            $grade_ex="200";
            break;
        case 5:case 6:
            $grade_ex="300";
            break;
        default:
            $grade_ex="";
            break;
        }
        return $grade_ex;
    }

    /**
     * 获取时间内的老师奖励薪资
     */
    public function get_reward_list(){
        $start = strtotime("2017-8-1");
        $end   = strtotime("2017-9-1");
        $list  = $this->t_teacher_money_list->get_reward_list($start,$end,5);
        foreach($list as $val){
            echo $val['teacherid']."|".$val['realname']."|".$val['phone']
                                  ."|银行卡:".$val['bankcard']."|".$val['bank_account']."|".$val['bank_type']
                                  ."|".($val['money_total']/100);
            echo "<br>";
        }
    }

    public function get_trial_teacher(){
        $num = $this->get_in_int_val("num");
        $start = strtotime("+$num month",strtotime("2017-7-1"));
        $end   = strtotime("+1 month",$start);
        $list = $this->t_lesson_info_b3->get_trial_teacher_list($start,$end);

        echo "姓名|手机|试听课次|成功课次|第一科目|第一科目年级|第二科目|第二科目年级";
        echo "<br>";
        foreach($list as $val){
            if($val['subject']>0){
                $subject_str = E\Esubject::get_desc($val['subject']);
                if($val['grade_start']>0){
                    $grade_str = E\Egrade_range::get_desc($val['grade_start'])."-".E\Egrade_range::get_desc($val['grade_end']);
                }else{
                    $grade_str = E\Egrade_part_ex::get_desc($val['grade_part_ex']);
                }
            }else{
                $subject_str = "无";
                $grade_str   = "无";
            }
            if($val['second_subject']){
                $second_subject_str = E\Esubject::get_desc($val['second_subject']);
                if($val['second_grade_start']>0){
                    $second_grade_str = E\Egrade_range::get_desc($val['second_grade_start'])."-".E\Egrade_range::get_desc($val['second_grade_end']);
                }else{
                    $second_grade_str = E\Egrade_part_ex::get_desc($val['second_grade']);
                }
            }else{
                $second_subject_str = "无";
                $second_grade_str   = "无";
            }


            $second_grade_str = E\Egrade_part_ex::get_desc($val['second_grade']);
            echo $val['realname']."|".$val['phone']."|".$val['lesson_num']."|".$val['succ_num']."|".$subject_str."|".$grade_str."|".$second_subject_str."|".$second_grade_str;
            echo "<br>";
        }
    }

    /**
     * 切换老师后,更新课程信息
     adrian
     */
    public function reset_teacher_info(){
        $time  = strtotime("2017-9-26");
        $arr = $this->t_lesson_info_b3->get_need_reset_list($time);
        dd($arr);
        foreach($arr as $val){
            if($val['new_teacher_money_type']!=$val['old_teacher_money_type'] || $val['new_level']!=$val['old_level']){
                echo $val['lessonid']."|new_teacher_money_type:".$val["new_teacher_money_type"]
                                     ."|old_teacher_money_type:".$val['old_teacher_money_type']
                                     ."|new_level:".$val['new_level']
                                     ."|old_level:".$val['old_level'];
                echo "<br>";
                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "teacher_money_type" => $val['new_teacher_money_type'],
                    "level"              => $val['new_level'],
                ]);
            }
        }
    }

    /**
     * 切换老师到第四版
     */
    public function reset_teacher_money_type(){
        // $list  = $this->t_teacher_info->get_need_reset_money_type_list();
        $list  =$this->t_teacher_info->get_no_lesson_teacher_list();
        dd($list);
        /**
         * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
         * 标题课程 : 等级升级通知
         * {{first.DATA}}
         * 用户昵称：{{keyword1.DATA}}
         * 最新等级：{{keyword2.DATA}}
         * 生效时间：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
        $data['keyword3'] = "21:00";
        $data['remark']   = "感谢您长期以来对理优平台的辛劳付出与长久陪伴！";
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                // "teacher_money_type" => $val['teacher_money_type_simulate'],
                // "level"              => $val['level_simulate'],
                "teacher_money_type" => 6,
                "level"              => 1,
            ]);

            if($val['wx_openid']!=""){
                $level_str = E\Enew_level::v2s(1);
                $data['first'] = "恭喜您，您等级已经调整为".$level_str;
                $data['keyword1'] = mb_substr($val['realname'],0,1)."老师";
                $data['keyword2'] = $level_str;
                \App\Helper\Utils::send_teacher_msg_for_wx($val['wx_openid'],$template_id,$data);
            }
        }
    }



    public function send_level_up(){
        $teacherid = $this->get_in_int_val("teacherid");
        $val = $this->t_teacher_info->get_teacher_info($teacherid);
        if($val['teacher_money_type']!=6){
            // $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
            // $data['keyword3'] = "20:00";
            // $data['remark']   = "感谢您长期以来对理优平台的辛劳付出与长久陪伴！";
            // $level_str = E\Enew_level::v2s($val['level_simulate']);
            // $data['first'] = "恭喜您，您等级已经调整为".$level_str;
            // $data['keyword1'] = mb_substr($val['realname'],0,1)."老师";
            // $data['keyword2'] = $level_str;

            // if($val['wx_openid']!=""){
            //     \App\Helper\Utils::send_teacher_msg_for_wx($val['wx_openid'],$template_id,$data);
            // }
        }
    }



    /**
     * 重置试讲通过,但科目未设置的老师科目和年级
     */
    public function reset_teacher_subject_info(){
        $list = $this->t_teacher_info->reset_teacher_subject_info();
        dd($list);
        foreach($list as $val){
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($val['grade']);
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "subject"     => $val['subject'],
                "grade_start" => $grade_range['gradde_start'],
                "grade_end"   => $grade_range['gradde_end'],
            ]);
        }
    }

    public function reset_already_lesson_count(){
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->already_lesson_count_key);
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->already_lesson_count_simulate_key);
    }

    public function teacher_lesson_total(){
        $teacherid_str = "53534,71729,50278,70048,76127,52106,51386,50275,51505,55334,53306,60128,58775,57559,62557,59961,51588,74044,88522,69469,50658,107415,84255,50277,58812,72328,54170,71743,50274,58813,85647,69376,74408,91056,69858,108292,59844,57701,87501,51528,69625,107333,54085,54034,102775,85095,84489,59196,59275,59487,58832,103363,98051,78734,102580,59163,50295,50448,59272,50449,66976,70137,76280,108968,69386,60081,79219,102261,70953,57909,85622,52068,81327,59432,106718,52033,106814,58774,87361,104514,90733,60554,107285,74223,92799,70047,81328,106679,68713,73019,107876,107887,87684,93660,60680,59197,60678,76382,104531,75661,78039,67377,93608,86273,74933,69774,61404,60544,92113,101463,147462,60388,56844,81957,54088,77089,95754,97340,74653,60375,59484,74420,76145,98036,95695,70463,102278,63515,57910,75855,106783,69318,102780,91894,57339,59519,107886,107337,58777,104615,85405,106751,74214,59958,102756,64501,69412,107775,102966,75065,65313,109389,86798,86105,69905,96491,145500,87209,105960,102155,81288,70127,72865,83840,66994,141160,104563,66987,86799,92940,107326,72123,77353,76760,70769,74092,74109,101476,77347,109445,75921,107087,60698,105018,75929,107000,92123,93253,109801,108341,102777,76763,77088,90732,77093,108965,90724,81165,67359,70323,60697,104617,50450,82043,59276,58833,80565,77558,74564,108819,77113,74095,74750,75720,59281,74775,75277,230147,139272,139081,224461,125512,141109,145845,216696,159994,236577,214412,108827,171736,233934,181897,170345,127483,165795,234565,133746,134271,170298,215516,130474,116808,147636,263869,249750,172038,244935,249833,187348,117072,147713,247704,216929,274974,176345,234492,223058,150010,130453,254321,244298,146384,224347,109666,147455,170161,113715,161841,253135,176720,226848,110433,134524,161878,251842,145487,131377,216636,158533,250574,224647,181112,117187,161315,150206,220900,268304,245081,151230,170356,277816,161467,272869,167226,203370,226411,221971,134954,181635,167624,158531,146712,141115,134979,139867,113714,202041,127480,149738,146390,264165,249831,240301,224475,146813,134542,170591,259876,135265,233916,127548,210480,151466,158577,135178,281447,146832,170156,188116,265615,260485,202092,196162,225965,158698,172088,146875,156624,134406,138561,254139,188853,151226,226431,109543,230096,135045,244057,196680,147674,160819,218000,216621,224667,134436,229619,147625,160765,156613,146818,127237,151153,285720,226406,110223,242580,172003,196159,246047,130378,176412,230996,219763,253428,173920,252482,249886,217328,160845,125681,203305,107472,248679,263180,224455,266958,226389,161966,234842,110342,250360,250328,127543,269366,158317,112445,134273,114690,116825,151167,260233,223832,170636,146817,72499,157964,146878,249822,135411,107061,158584,134430,271924,135559,167237,217326,129223,167234,240459,214143,252444,280842,127584,226380,125723,233106,146761,127544,260216,236240,249764,146340,245499,240439,223056,187504,184281,240184,125507,165772,134609,135377,126872,226816,249128,223055,196185,183726,139863,110292,134527,246810,176722,251908,265861,225244,192736,228647,202094,222678,161746,225088,288495,275383,240293,107862,230976,135405,265297,139864,172046,174820,170352,285830,216623,134989,172070,242744,157870,265445,170647,265970,139813,221138,240300,202146,86796,267274,161980,170445,244283,146876,243672,151145,129905,216787,216673,131339,238034,241573,113555,170060,135188,234598,158166,125676,205339,242249,240324,147703,113730,226811,227014,129373,196815,135576,172024,139866,218172,253713,196200,224641,177742,247562,240119,232521,236411,135010,167427,132122,146877,134499,134961,57865,231143,172062,243705,196028,234643,117063,250395,147641,147454,74519,217355,222976,263807,235366,236773,203859,147648,252070,190975,176718,112427,138961,114349,256659,160847,225972,228168,255806,234801,158169,242747,226447,187251,196719,161547,125779,226990,125549,157508,75144,209759,264585,226388,232737,114607,233040,225383,230737,244966,225961,160843,127582,267307,171997,116818,182902,146768,225463,130493,145495,226129,139861,171999,234984,115523,202851,127539,202738,139288,245060,202104,112423,225420,202109,151232,94290,135007,127224,112633,151423,110486,134431,147680,154796,151231,126927,155453,125834,172004,155565,167329,154890,114639,131354,151149,130466,134650,155143,125572,134419,226032,107064,155420,127417,155218,116826,126903,158381,160841,138229,158919,151308,170061,112669,135229,127450";

        $tea_arr = explode(",",$teacherid_str);
        $month   = $this->get_in_int_val("month",6);

        $start_time = strtotime("2017-$month");
        $end_time   = strtotime("+1 month",$start_time);
        $list       = $this->t_lesson_info->get_teacher_lesson_count_total($start_time,$end_time,$tea_arr,1);
        echo "teacherid|姓名|工资类型|课耗|学生数";
        echo "<br>";
        foreach($list as $val){
            $teacherid = $val['teacherid'];
            $tea_nick = $this->cache_get_teacher_nick($val['teacherid']);
            $teacher_money_type_str = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $lesson_total = $val['lesson_total']/100;
            $stu_num=$val['stu_num'];
            echo "$teacherid|$tea_nick|$teacher_money_type_str|$lesson_total|$stu_num";
            echo "<br>";
        }
    }

    /**
     * 老师所带学生数
     */
    public function get_tea_stu_num(){
        $month_start = $this->get_in_int_val("month_start");
        $month_end   = $this->get_in_int_val("month_end");
        $teacherid   = $this->get_in_int_val("teacherid");
        if($teacherid==0){
            return $this->output_err("id不能为0");
        }

        $start_time = strtotime("2017-$month_start");
        $end_time   = strtotime("+1 month",strtotime("2017-$month_end"));
        $name = $this->t_teacher_info->get_realname($teacherid);
        $list = $this->t_lesson_info_b3->get_tea_stu_num_list($start_time,$end_time,$teacherid);
        $not_num=0;
        $num=0;
        foreach($list as $l_val){
            $num++;
            if($l_val['type']==1){
                $not_num++;
            }
        }
        echo "姓名|结课学员|所带学生";
        echo "<br>";
        echo "$name|".$not_num."|".$num;
        echo "<br>";
    }

    public function get_teacher_list_for_total_info(){
        $month_start = $this->get_in_int_val("month_start",1);
        $month_end   = $this->get_in_int_val("month_end",2);

        // $not_month_start = $this->get_in_int_val("not_month_start",3);
        // $not_month_end = $this->get_in_int_val("not_month_end",4);

        $start_time = strtotime("2017-$month_start");
        $end_time   = strtotime("2017-$month_end");
        // $not_start_time = strtotime("2017-$not_month_start");
        // $not_end_time   = strtotime("2017-$not_month_end");
        // $end_time   = strtotime("+1 month",$start_time);
        // if($month_start>$month_end || $not_start_time>$not_end_time){
        //     return $this->output_err("时间出错!结束不能小于开始!");
        // }

        $list = $this->t_lesson_info_b3->get_teacher_list_for_total_info($start_time,$end_time);
        echo "姓名|手机|常规学生数|试听课次|成功课次|科目";
        echo "<br>";
        foreach($list as $val){
            $subject_str = "";
            if($val['stu_subject']!=""){
                $subject_arr = explode(",",$val['stu_subject']);
                foreach($subject_arr as $sub_val){
                    if($subject_str==""){
                        $subject_str = E\Esubject::get_desc($sub_val);
                    }else{
                        $subject_str .= ",".E\Esubject::get_desc($sub_val);
                    }
                }
            }
            echo $val['realname']."|".$val['phone']."|".$val['normal_stu_num']
                                 ."|".$val["trial_num"]."|".$val['succ_num']."|".$subject_str;
            echo "<br>";
        }
    }

    public function get_month_list(){
        // $list = $this->t_teacher_switch_money_type_list->get_teacher_switch_list(-1,-1,0,-1,-1,0,0);
        // foreach($list as $val){
        //     echo $val['teacherid']."|".$val['realname'];
        //     echo "<br>";
        // }
        // exit;
        $arr = $this->get_b_txt();
        $month_start = $this->get_in_int_val("month_start",1);
        $month_end   = $this->get_in_int_val("month_end",2);

        $start_time = strtotime("2017-$month_start");
        $end_time = strtotime("+1 month",$start_time);
        // $end_time   = strtotime("2017-$month_end");
        $month_str = $month_start."月";
        echo "$month_str"
            // ."|试听课时|常规课时|"
            ;
        echo "<br>";
        foreach($arr as $val){
            if($val!=""){
                $tea_info = explode("|",$val);
                $teacherid = $tea_info[0];
                $name= $tea_info[1];
                $info = $this->t_lesson_info_b3->get_tea_lesson_total($start_time,$end_time,$teacherid);
                echo $info['lesson_total']/100;
                // ."|".$info['trial_lesson_total']."|".$info['normal_lesson_total']
                echo "<br>";
            }
        }
    }



}
