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
    //测试通知

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
        $lessonid   = $this->get_in_int_val("lessonid");
        $month      = $this->get_in_int_val("month",1);
        $month_str  = "2017-".$month;
        $month_time = strtotime($month_str);
        $month_time = \App\Helper\Utils::get_month_range($month_time,true);


        $jianzhi_reward = ($this->t_teacher_money_list->get_reward_total($month_time['sdate'],$month_time['edate'],0,0,0))/100;
        $full_reward = ($this->t_teacher_money_list->get_reward_total($month_time['sdate'],$month_time['edate'],0,0,1))/100;
        echo $jianzhi_reward;
        echo "<br>";
        echo $full_reward;
        echo "<br>";
        exit;
        $list = $this->t_lesson_all_money_list->get_lesson_all_money_list($month_time['sdate'],$month_time['edate'],$lessonid);

        echo "课程id|用户id|学生|科目|年级|课程类型|课时不对|课程表课时|课时|付费课时|赠送课时|课时收入|老师课时费|老师课时奖励|是否为全职老师|课程确认|课程扣款";
        echo "<br>";
        $show_list = [];
        foreach($list as $val){
            $lessonid     = $val['lessonid'];
            $lesson_count = $val['lesson_count'];
            //课时收入
            $val['lesson_price'] = $lesson_count*$val['per_price']/10000;
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

        $money_total=[];
        foreach($show_list as $s_val){
            $stu_nick    = $s_val['stu_nick'];
            $lessonid    = $s_val['lessonid'];
            $userid      = $s_val['userid'];
            $subject     = E\Esubject::get_desc($s_val['subject']);
            $grade       = E\Egrade::get_desc($s_val['grade']);
            $lesson_type = $s_val['lesson_type'];
            if($lesson_type==2){
                $lesson_type_str = "试听";
            }else{
                $lesson_type_str = "常规";
            }
            $lesson_count = ($s_val['free_lesson_count']+$s_val['normal_lesson_count'])/100;
            $l_lesson_count = $s_val['l_lesson_count']/100;
            if($lesson_count!=$l_lesson_count){
                $error_lesson_count=1;
            }else{
                $error_lesson_count=0;
            }
            $normal_lesson_count        = $s_val['normal_lesson_count']/100;
            $free_lesson_count          = $s_val['free_lesson_count']/100;
            $lesson_price               = $s_val['lesson_price'];
            $teacher_base_money         = $s_val['teacher_base_money']/100;
            $teacher_lesson_count_money = $s_val['teacher_lesson_count_money']/100;
            $teacher_lesson_cost        = $s_val['teacher_lesson_cost']/100;
            $teacher_money_type         = $s_val['teacher_money_type'];
            $teacher_type               = $s_val['teacher_type'];
            $teacherid                  = $s_val['teacherid'];
            $check_is_full = \App\Helper\Utils::check_teacher_is_full($teacher_money_type, $teacher_type, $teacherid);
            $confirm_flag = $s_val['confirm_flag'];
            $confirm_flag_str = E\Econfirm_flag::get_desc($confirm_flag);
            $check_is_full = $check_is_full?1:0;
            if($error_lesson_count==0){
                $error_lesson_count_str = "课时正确";
            }else{
                $error_lesson_count_str = "课时错误";
            }

            echo $lessonid."|".$userid."|".$stu_nick."|".$subject."|".$grade."|".$lesson_type_str
                          ."|".$error_lesson_count_str."|".$l_lesson_count
                          ."|".$lesson_count."|".$normal_lesson_count."|".$free_lesson_count."|".$lesson_price
                          ."|".$teacher_base_money."|".$teacher_lesson_count_money."|".$check_is_full."|".$confirm_flag_str
                          ."|".$teacher_lesson_cost;
            echo "<br>";

            if($teacher_lesson_cost>=100){
                $teacher_base_money = 0;
                $teacher_lesson_count_money= 0;
            }elseif($confirm_flag!=2){
            }else{
                $teacher_base_money = 0;
                $teacher_lesson_count_money= 0;
                $teacher_lesson_cost = 0;
            }
            $teacher_all_money = $teacher_base_money+$teacher_lesson_count_money-$teacher_lesson_cost;

            $money_detail = &$money_total[$check_is_full];
            //课耗收入
            \App\Helper\Utils::check_isset_data($money_detail["lesson_price"],$lesson_price);
            //付费课耗数
            \App\Helper\Utils::check_isset_data($money_detail["normal_lesson_count"],$normal_lesson_count);
            //赠送课耗数
            \App\Helper\Utils::check_isset_data($money_detail["free_lesson_count"],$free_lesson_count);
            //老师课时费收入
            \App\Helper\Utils::check_isset_data($money_detail["teacher_all_money"],$teacher_all_money);
            //课时费收入
            \App\Helper\Utils::check_isset_data($money_detail["teacher_normal_money"],0,0);
            //试听收入
            \App\Helper\Utils::check_isset_data($money_detail["teacher_trial_money"],0,0);
            if($lesson_type==2){
                //老师试听课收入
                \App\Helper\Utils::check_isset_data($money_detail["teacher_trial_money"],$teacher_base_money);
            }else{
                //老师1对1课时费收入
                \App\Helper\Utils::check_isset_data($money_detail["teacher_normal_money"],$teacher_base_money);
            }
            //老师课时奖励
            \App\Helper\Utils::check_isset_data($money_detail["teacher_lesson_count_money"],$teacher_lesson_count_money);
        }

        $jianzhi_reward = ($this->t_teacher_money_list->get_reward_total($month_time['sdate'],$month_time['edate'],0,0,0))/100;
        $full_reward = ($this->t_teacher_money_list->get_reward_total($month_time['sdate'],$month_time['edate'],0,0,1))/100;
        echo "<br>";
        echo "兼职课耗收入|兼职付费课耗数|兼职赠送课耗|兼职老师成本-课时费收入|兼职1对1课时收入|兼职试听课收入|兼职课时奖励|兼职额外奖励";
        echo "|全职课耗收入|全职付费课耗数|全职赠送课耗|全职老师成本-课时费收入|全职1对1课时收入|全职试听课收入|全职课时奖励|全职额外奖励";
        echo "<br>";
        echo $money_total[0]['lesson_price']."|".$money_total[0]['normal_lesson_count']."|".$money_total[0]['free_lesson_count']
                                            ."|".$money_total[0]['teacher_all_money']."|".$money_total[0]['teacher_normal_money']
                                            ."|".$money_total[0]['teacher_trial_money']."|".$money_total[0]['teacher_lesson_count_money']
                                            ."|".$jianzhi_reward."|";
        echo $money_total[1]['lesson_price']."|".$money_total[1]['normal_lesson_count']."|".$money_total[1]['free_lesson_count']
                                            ."|".$money_total[1]['teacher_all_money']."|".$money_total[1]['teacher_normal_money']
                                            ."|".$money_total[1]['teacher_trial_money']."|".$money_total[1]['teacher_lesson_count_money']
                                            ."|".$full_reward;
        echo "<br>";
        return $this->output_succ();
    }

    public function test_free_time(){ // 协议编号 :1010
        $teacherid = 60024;
        $ret_str = $this->t_teacher_freetime_for_week->get_vacant_arr($teacherid);
        $ret_arr = json_decode($ret_str,true);
        if(!empty($ret_arr)){
            foreach($ret_arr as $i=>$item_ret){
                foreach($format_arr as &$item_format){
                    if($item_ret==' '){
                        unset($ret_arr[$i]);
                    }else{
                        if($item_format == @$item_ret['0']){
                            unset($ret_arr[$i]);
                        }
                    }
                }
            }
        }
    }

    public function add_train_lesson(){
        $this->t_teacher_record_list->row_insert([
            "teacherid"      => 510403,
            "type"           => E\Erecord_type::V_1,
            "add_time"       => time()+1000,
            "train_lessonid" => 537758,
            "lesson_style"   => E\Elesson_style::V_5
        ]);
    }

    public function test_money($year,$month){
        $this->switch_tongji_database();
        $date_str = $year."-".$month;
        $start    = strtotime($date_str);
        $end      = strtotime("+1 month",$start);

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages(-1,$start,$end,-1,"current",1);
        $check_num  = [];
        $money_list = [];
        $tea_lesson_count = [];
        if(!empty($lesson_list)){
            foreach($lesson_list as $key => &$val){
                $teacherid    = $val['teacherid'];
                $lesson_count = $val['confirm_flag']!=2?($val['lesson_count']/100):0;
                $lesson_type  = $val['lesson_type'];
                $grade        = \App\Helper\Utils::change_grade_to_grade_part($val['grade']);
                if($lesson_type==E\Econtract_type::V_2){
                    continue;
                }

                if(!isset($tea_lesson_count[$teacherid])){
                    $last_lesson_count = $this->get_last_lesson_count_info($start,$end,$teacherid);
                    $tea_lesson_count[$teacherid] = $last_lesson_count;
                }else{
                    $last_lesson_count = $tea_lesson_count[$teacherid];
                }

                $val['money']       = $this->get_teacher_base_money($teacherid,$val);
                $val['lesson_base'] = $val['money']*$lesson_count;
                $reward = $this->get_lesson_reward_money(
                    $last_lesson_count,$val['already_lesson_count'],$val['teacher_money_type'],$val['teacher_type'],$val['type']
                );
                $val['lesson_reward'] = $reward*$lesson_count;

                $this->get_lesson_cost_info($val,$check_num[$teacherid]);
                //老师收入,课时成本
                $teacher_money = ($val['lesson_base']+$val['lesson_reward']-$val['lesson_cost']);
                /**
                 * 课时收入：当月内，产生课时消耗得到的收入，以实际收入为准；
                 * 付费课时数：当月内实际消耗的课时数，以实际扣除学生的课时数为准；
                 */
                $lesson_price      = 0;
                $lesson_pay_count  = 0;
                $lesson_free_count = 0;
                if(in_array($val['confirm_flag'],[0,1,3]) && $val['deduct_change_class']==0){
                    $lesson_price = $val['lesson_price'];
                    if($lesson_price>0){
                        $lesson_pay_count = $lesson_count;
                    }else{
                        $lesson_free_count = $lesson_count;
                    }
                }

                //赠送课时数
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_price'], $lesson_price);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['teacher_money'], $teacher_money);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_pay_count'], $lesson_pay_count);
                \App\Helper\Utils::check_isset_data($money_list[$grade]['lesson_free_count'], $lesson_free_count);
            }
        }
        return $money_list;
    }



}
