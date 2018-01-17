<?php
namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Artisan;
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

    public function __construct(){
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

    /**
     * 获取文件拉取标示 0 不拉取 1 拉取
     */
    public function get_file_flag($file_name){
        $flag = 0;
        if(is_file($file_name)){
            $file_info = file_get_contents($file_name);
            if(empty($file_info) || $file_info==""){
                $flag = 1;
            }
        }else{
            $flag = 1;
        }
        return $flag;
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

    public function reset_test_appointment(){
        if(\App\Helper\Utils::check_env_is_test()){
            $phone     = $this->get_in_int_val("phone","99900020001");
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
        $this->t_teacher_info->field_update_list($teacherid,[
            "wx_use_flag"            => 0,
            "trial_lecture_is_pass"  => 0,
            "train_through_new"      => 0,
            "train_through_new_time" => 0,
        ]);
        $id=$this->t_teacher_lecture_info->get_id_list_by_phone($phone);
        if(is_array($id) && !empty($id)){
            foreach($id as $val_l){
                $this->t_teacher_lecture_info->row_delete($val_l['id']);
            }
        }

    }

    /**
     * 切换老师后,更新课程信息
     adrian
     */
    public function reset_teacher_info(){
        $time  = strtotime("2017-11-1");
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

    /**
     * 删除多余的模拟试听课程
     */
    public function delete_more_lesson(){
        $lesson_list = $this->t_lesson_info_b3->get_more_trial_lesson_list();
        foreach($lesson_list as $l_val){
            $this->t_lesson_info->row_delete($l_val['lessonid']);
        }
        echo "over";
        echo "<br>";
        $lesson_list = $this->t_lesson_info_b3->get_more_trial_lesson_list();
        if(!empty($lesson_list)){
            $this->delete_more_lesson();
        }
    }

    public function randFloat($min=0, $max=1){
        return $min + mt_rand()/mt_getrandmax() * ($max-$min);
    }

    public function test_shidai(){
        $login_url = $this->base_url."/user/login.html";

        $userId   = "";
        $password = "";
        $login_data = [
            "userId"   => $userId,
            "password" => $password,
        ];
        $ret = $this->send_post_data($login_url,$login_data);

        preg_match('/Set-Cookie:(.*);/iU',$ret,$str);

        echo "str is:";
        echo $this->br;
        var_dump($str);
        echo "ret is :";
        echo $this->br;
        var_dump($ret);
    }

    public function get_user_data(){
        $cookie = "acw_tc=AQAAAMD/1xZN7QwAPeBRZWlqFabRep+r; JSESSIONID=EE5ED08F7F71DB7B0135D364E94564E7; logined=y";
        $get_url  = $this->base_url."/vipRepay/getRepayList.html";
        $get_data = [
            "page" => 1,
            "rows" => 10,
        ];
        $ret = $this->send_post_data($get_url,$get_data,false,$cookie);
        \App\Helper\Utils::debug_to_html( $ret );
        dd($ret);
    }


    /**
     * @param string url 访问的地址
     * @param array data 所传参数
     * @param string cookie 所传cookie
     */
    function send_post_data($url, $data,$has_header=true,$cookie="")
    {
        $ch = curl_init();
        if ($ch === false) {
            return false;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        if($has_header){
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // wait to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//wait to execute
        if($cookie!=""){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function test_feedback(){
        $lessonid = 63275;
        $stu_performance = $this->t_lesson_info->get_stu_performance($lessonid);

        if($stu_performance!=''){
            $stu_performance = json_decode($stu_performance,true);
            $stu_comment = "";
            if(is_array($stu_performance['stu_comment'])){
                foreach($stu_performance['stu_comment'] as $stu_val){
                    if(isset($stu_val['stu_tip']) && isset($stu_val['stu_info'])){
                        $stu_comment .=$stu_val['stu_tip'].$stu_val['stu_info'].";";
                    }
                }
                $stu_comment = trim($stu_comment,";");
            }else{
                $stu_comment = $stu_performance['stu_comment'];
            }
            dd($stu_comment);
        }
    }

    public function test_sms(){
        $phone    = $this->get_in_int_val("phone","18790256265");

        $type = 15960017;
        $data = [
            "name"  => "测试",
            "wx_id" => "测试id",
            "phone" => "测试电话",
        ];
        \App\Helper\Utils::sms_common($phone, $type, $data);
    }

    public function show_lesson_all_money(){
        $month      = $this->get_in_int_val("month",1);
        $month_str  = "2017-".$month;
        $month_time = strtotime($month_str);
        $month_time = \App\Helper\Utils::get_month_range($month_time,true);

        $list = $this->t_lesson_all_money_list->get_lesson_all_money_list($month_time['sdate'],$month_time['edate']);
        echo "用户id|学生|科目|年级|课程类型|课时不对|课程表课时|课时|付费课时|赠送课时|课时收入|老师课时费|老师课时奖励|是否为全职老师|课程确认|课程扣款";
        $show_list = [];
        foreach($list as $val){
            $lessonid     = $val['lessonid'];
            $lesson_count = $val['lesson_count'];
            //课时收入
            $val['lesson_price'] = $lesson_count*$val['per_price'];
            //赠送课时
            $val['free_lesson_count']   = 0;
            //付费课时
            $val['normal_lesson_count'] = 0;

            if($val['per_price']==0){
                $val['free_lesson_count'] = $lesson_count;
            }else{
                $val['normal_lesson_count'] = $lesson_count;
            }

            if(isset($show_list[$lessonid])){
                $show_list[$lessonid]['free_lesson_count']   += $val['free_lesson_count'];
                $show_list[$lessonid]['normal_lesson_count'] += $val['normal_lesson_count'];
                $show_list[$lessonid]['lesson_price']        += $val['lesson_price'];
            }else{
                $show_list[$lessonid]=$val;
            }
        }

        foreach($show_list as $s_val){
            $stu_nick     = $s_val['stu_nick'];
            $subject      = E\Esubject::get_desc($s_val['subject']);
            $grade        = E\Esubject::get_desc($s_val['grade']);
            $lesson_type  = E\Econtract_type::get_desc($s_val['lesson_type']);
            $lesson_count = $s_val['free_lesson_count']+$s_val['normal_lesson_count'];
            $l_lesson_count = $s_val['l_lesson_count'];
            if($lesson_count!=$l_lesson_count){
                $error_lesson_count=1;
            }else{
                $error_lesson_count=0;
            }
            $lesson_price               = $s_val['lesson_price'];
            $teacher_base_money         = $s_val['teacher_base_money'];
            $teacher_lesson_count_money = $s_val['teacher_lesson_count_money'];
            $teacher_money_type         = $s_val['teacher_money_type'];
            $teacher_type               = $s_val['teacher_type'];
            $teacherid                  = $s_val['teacherid'];
            $check_is_full = \App\Helper\Utils::check_teacher_is_full($teacher_money_type, $teacher_type, $teacherid);
            $confirm_flag = $s_val['confirm_flag'];

            echo $userid."|".$stu_nick."|".$subject."|".$grade."|".$lesson_type."|".$error_lesson_count."|".$l_lesson_count
                        ."|".$lesson_count."|".$s_val['normal_lesson_count']."|".$s_val['free_lesson_count']."|".$lesson_price
                        ."|".$teacher_base_money."|".$teacher_lesson_count_money."|".$check_is_full."|".$confirm_flag
                        ."|".$s_val['teacher_lesson_cost'];
            echo "<br>";
        }
    }





}
