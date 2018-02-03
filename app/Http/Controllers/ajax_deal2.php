<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;


class ajax_deal2 extends Controller
{
    use CacheNick;
    use TeaPower;
    public function  manger_info_set_info () {

        $adminid = $this->get_in_adminid();
        $groupid = $this->get_in_int_val("groupid");
        $email =trim($this->get_in_str_val("email"));
        $name=trim ($this->get_in_str_val("name"));
        $this->t_manager_info->field_update_list($adminid,[
            "email" =>$email,
            "name" =>$name,
        ]);
        $this->t_mail_group_user_list->field_update_list_2($groupid,$adminid,[
            "create_flag" =>0,
        ]);
        return $this->output_succ();

    }
    public function get_order_activity_list ( ) {
        $orderid= $this->get_in_int_val("orderid");
        $list = $this->t_order_activity_info->get_order_activity_list($orderid);
        return $this->output_succ([ "list"=> $list] );
    }


    public function sync_email() {
        $email=$this->get_in_str_val( "email" );
        $title= $this->get_in_str_val("title");
        $zmcmd= "zmprov ca $email 111111 displayName $title &>/dev/null ;".
              "zmprov ma $email displayName $title &>/dev/null; ".
              "zmprov adlm all@leoedu.com $email ";
        $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
        \App\Helper\Utils::exec_cmd($cmd);
        return $this->output_succ();
    }
    public function sync_group_email() {
        $groupid=$this->get_in_int_val("groupid");
        $group_info=$this->t_mail_group_name->field_get_list($groupid,"*");
        $title= $group_info["title"];
        $group_email= $group_info["email"];
        $ret_info=$this->t_mail_group_user_list->get_list (null, $groupid, -1);
        $list= $ret_info["list"];
        $email_list_str="";
        foreach ($list as $item) {
            $email_list_str.=" ".$item["email"] ;
        }

        $zmcmd=
              "zmprov ddl $group_email &>/dev/null ;".
              "zmprov cdl $group_email displayName '$title'  &>/dev/null ;".
              "zmprov adlm $group_email $email_list_str &>/dev/null ; "
              . "zmprov gdlm $group_email "
              ;
        $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
        $ret_str=\App\Helper\Utils::exec_cmd($cmd);
        $email_list=preg_split("/\n/", $ret_str);

        $this->t_mail_group_user_list->reset_all_create_flag ($groupid, $email_list );

        return $this->output_succ();
    }



    public function office_open_door () {
        $sn = $this->get_in_str_val("sn");
        $info=$this->t_kaoqin_machine->get_info_by_sn( $sn );
        if (!$info) {
            return $this->output_err("出错");
        }

        $machine_id= $info[ "machine_id"];
        $adminid=$this->get_account_id();
        if ( !in_array( $sn,[ "Q11163910015", "0"] ) ) {
            return $this->output_err("该门 未开启远程开关:<");
        }

        $check_value= $this->t_kaoqin_machine_adminid->field_get_list_2($machine_id, $adminid,"adminid");
        if (!$check_value) {
            return $this->output_err("你没有权限开此门 :<");
        }

        $this->t_kaoqin_machine->send_cmd_unlock($machine_id);
        return $this->output_succ();
    }
    //JIM
    public function office_cmd_add () {
        $office_device_type = $this->get_in_e_office_device_type();
        $device_opt_type    = $this->get_in_e_device_opt_type();


        $device_id    = $this->get_in_int_val("device_id");
        $value = $this->get_in_int_val("value");
        $device_sub_type=1;//海尔
        if (in_array ($device_id , [1,2,6,7,8,11,13,14] )) {
            $device_sub_type=2;//美的
        }
        \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type,$device_sub_type ,$value);
        return $this->output_succ();
    }

    public function gen_ass_from_account() {
        $adminid=$this->get_in_adminid();
        $admin_info= $this->t_manager_info->field_get_list($adminid,"phone,name,email");
        $phone=$admin_info["phone"];

        $passwd = "142857";
        $md5_passwd = md5(md5($passwd)."#Aaron");
        $this->t_user_info->row_insert([

            "passwd"  => md5($passwd)
        ]) ;
        $assid =  $this->t_user_info->get_last_insertid();
        if($assid === false){
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }

        //加入ejabberd 账号让助教可以进入课堂
        //$this->users->add_ejabberd_account($assid,md5($passwd));
        //加入ejabberd监控账号 以ad开头
        //$this->users->add_ejabberd_account("ad_" . $assid,md5($passwd));

        $ret_db = $this->t_phone_to_user->add_phone_to_ass($assid, $phone);
        if($ret_db === false){
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }


        $ret_db = $this->t_assistant_info->add_new_ass($admin_info["name"], 0, 0, 0, $phone, $admin_info["email"],
                                                       0, $assid, "");
        if($ret_db === false) {
            return outputJson(array('ret' => -1, 'info'  => '插入失败'));
        }
        return $this->output_succ();
    }


    public function set_email_passwd() {
        $email=$this->get_in_str_val( "email" );
        $zmcmd= "zmprov sp $email 111111 &>/dev/null ;";
        $cmd="sshpass -p\"yb142857\" ssh -2  -o \"StrictHostKeyChecking no\" -p22 -l\"zimbra\" 115.28.89.73   \"   $zmcmd \"";
        \App\Helper\Utils::exec_cmd($cmd);
        return $this->output_succ();
    }



    public function set_tmk_valid() {
        $userid=$this->get_in_userid();
        $tmk_student_status = $this->get_in_e_tmk_student_status();
        $tmk_student_status_old = $this->get_in_int_val('tmk_student_status_old');
        $tmk_desc          = $this->get_in_str_val("tmk_desc");


        if ($tmk_student_status == E\Etmk_student_status::V_3) {

            $opt_type=2;
            $userid_list=[$userid];
            $opt_adminid= $this->get_account_id();
            $phone=$this->t_seller_student_new->get_phone($userid);
            $account = $this-> get_account();

            $this->t_seller_student_new->set_admin_info(
                $opt_type, $userid_list,  $opt_adminid, $this->get_account_id() );



            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                " 状态: 新例子 设置 给 TMK [$account] ",
                "system"
            );


            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_desc"=>$tmk_desc,
            ]);

            $this->t_seller_student_new->set_admin_info(
                0, $userid_list,  0, $this->get_account_id() );

            $phone=$this->t_seller_student_new->get_phone($userid);

            // $this->t_manager_info->send_wx_todo_msg( "李子璇","来自:$account" , "TMK 有效:$phone"  );
            $account = $this-> get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                " 状态: TMK [$account] 有效 例子输出 ",
                "system"
            );
        }else{
            if($tmk_student_status != $tmk_student_status_old && $tmk_student_status=3){//tmk将例子更改为有效
                $adminid = $this->get_account_id();
                $this->t_seller_student_new->field_update_list($userid,[
                    "tmk_student_status"=>$tmk_student_status,
                    "tmk_desc"=>$tmk_desc,
                    "first_tmk_set_valid_admind"=>$adminid,
                    "first_tmk_set_valid_time"=>time(null),
                ]);
            }else{
                $this->t_seller_student_new->field_update_list($userid,[
                    "tmk_student_status"=>$tmk_student_status,
                    "tmk_desc"=>$tmk_desc,
                ]);
            }

        }

        return $this->output_succ();
    }

    public function gen_order_pdf(){
        $orderid             = $this->get_in_int_val("orderid");
        $parent_name         = $this->get_in_str_val("parent_name");
        $row                 = $this->t_order_info->field_get_list($orderid,"*");
        $type_1_lesson_count = $this->t_order_info->get_type1_lesson_count ($orderid)/100;
        $userid              = $row["userid"];
        $username            = $this->t_student_info->get_nick($userid);
        $phone               = $this->t_student_info->get_phone($userid);
        $grade               = $row["grade"];
        $lesson_count        = $row["lesson_total"] * $row["default_lesson_count"]/100;
        $price               = $row["price"]/100;
        $competition_flag    = $row["competition_flag"];
        $one_lesson_count    = $row["lesson_weeks"] ;
        $per_lesson_interval = $row["lesson_duration"] ;

        if($row['contract_starttime']>0){
            $order_start_time = $row["contract_starttime"];
        }else{
            $order_start_time = $row["order_time"];
        }
        $order_end_time      = \App\Helper\Utils::get_order_term_of_validity($order_start_time,$lesson_count);
        $contract_type       = $row["contract_type"];
        $contract_status     = $row["contract_status"];

        $this->t_student_info->field_update_list($userid,[
            "parent_name" => $parent_name
        ]);

        if ($contract_status ==0 )  {
            $username="   ";
            $phone="   ";
            $parent_name="   ";
        }

        /*
        if ($contract_status ==0 )  {
            return $this->output_err("未支付，不能生成合同");
        }
        */
        if (!in_array(  $contract_type , array(E\Econtract_type::V_0 ,E\Econtract_type::V_3 )  )) {
            return $this->output_err("不是1对１合同，不能生成合同");
        }

        if(!$one_lesson_count    ){ $one_lesson_count= 3; }
        if(!$per_lesson_interval ){ $per_lesson_interval = 40; }
        $now=time(NULL);

        $pdf_file_url=\App\Helper\Common::gen_order_pdf($orderid,$username,$grade,$competition_flag,$lesson_count,$price,$one_lesson_count,$per_lesson_interval,$order_start_time,$order_end_time,true, $now ,$type_1_lesson_count,$phone, $parent_name );
        \App\Helper\Utils::logger("pdf_file_url:$pdf_file_url");
        $pdf_file_url=\App\Helper\Common::gen_order_pdf($orderid,$username,$grade,$competition_flag,$lesson_count,$price,$one_lesson_count,$per_lesson_interval,$order_start_time,$order_end_time,false,$now , $type_1_lesson_count ,$phone, $parent_name);

        \App\Helper\Utils::logger("pdf_file_url:$pdf_file_url");
        $this->t_order_info->field_update_list($orderid,[
            "pdf_url" =>$pdf_file_url
        ]);

        # 增加发送给家长
        $parentid = $this->t_parent_child->get_parentid_by_userid($userid);
        $test_arr = ['335719','321232'];
        if($pdf_file_url && in_array($parentid,$test_arr) ){
            $cc_row = $this->t_manager_info->get_phone_by_account($row['sys_operator']);
            $cc_phone = $cc_row['phone'];
            $template_id = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
            $data = [
                "keyword1" => "请查看合同",
                "keyword2" => $parent_name."家长: \n 您的购课合同已生成,请注意点击查看 \n 联系老师: ".$row['sys_operator']."老师 \n 联系电话: ".$cc_phone,
                "keyword3" => date("Y年m月d日 H:i:s")
            ];
            $parentOpenid = $this->t_parent_info->getWxOpenidByStuId($useid);
            $pdf_url = "http://admin.leo1v1.com/common_new/redirectForPdf?url=".$pdf_file_url."&orderid=".$orderid;

            \App\Helper\Utils::send_wx_to_parent($parentOpenid,$template_id,$data,$pdf_url);

        }
        return $this->output_succ(["pdf_file_url" => $pdf_file_url] );
    }

    /**
     * @author    sam
     * @function  更新学生考试成绩信息
     */
    public function score_edit(){
        $userid           = $this->get_in_int_val("userid");
        $id               = $this->get_in_int_val("id");
        $subject          = $this->get_in_int_val("subject");
        $stu_score_type   = $this->get_in_int_val("stu_score_type");
        $score            = $this->get_in_int_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");

        $semester         = $this->get_in_int_val("semester");
        $total_score      = $this->get_in_int_val("total_score");
        $grade            = $this->get_in_int_val("grade");
        $grade_rank       = $this->get_in_str_val("grade_rank");
        $status           = $this->get_in_int_val("status");
        $create_adminid   = $this->get_account_id();
        $create_time      = $this->get_in_str_val("create_time");
        $create_time = strtotime($create_time);
        $rank_arr = explode("/",$grade_rank);
        $rank_now = $rank_arr[0];
        $grade_rank_last = $this->t_student_score_info->get_last_grade_rank_b1($subject,$userid,$create_time);

        if( $score> $total_score){
            return $this->output_err("成绩输入有误!");
        }
        if($grade_rank_last  && $rank_now != ''){
            $grade_rank_last = $grade_rank_last[0]["grade_rank"];
            $rank_last_arr = explode("/",$grade_rank_last);
            $rank_last = $rank_last_arr[0];
            if($rank_last - $rank_now >= 0){
                $rank_up = $rank_last-$rank_now;
                $rank_down = '';
            }else{
                $rank_up = '';
                $rank_down = $rank_now - $rank_last;
            }
        }else{
            $rank_up = '';
            $rank_down = '';
        }
        $data = [
            'id'            =>   $id,
            'create_adminid'=>   $create_adminid,
            'subject'       =>   $subject,
            'stu_score_type'=>   $stu_score_type,
            'score'         =>   $score,
            'rank'          =>   $rank,
            'file_url'      =>   $file_url,
            'semester'      =>   $semester,
            'total_score'   =>   $total_score,
            'grade'         =>   $grade,
            'grade_rank'    =>   $grade_rank,
            'status'        =>   $status,
            "rank_up"       =>   $rank_up,
            "rank_down"     =>   $rank_down,
         ];

        $ret = $this->t_student_score_info->field_update_list($id,$data);//更新当前字段值

        $grade_rank_next = $this->t_student_score_info->get_last_grade_rank_b2($subject,$userid,$create_time);
        if($grade_rank_next  && $rank_now != ''){

            $grade_rank_next_arr = $grade_rank_next[0]["grade_rank"];
            $rank_next_arr = explode("/",$grade_rank_next_arr);
            $rank_next = $rank_next_arr[0];
            if($rank_next - $rank_now >= 0){
                $rank_down = $rank_next-$rank_now;
                $rank_up = '';
            }else{
                $rank_down = '';
                $rank_up = $rank_now - $rank_next;
            }
            $data = [
                'id'            =>   $grade_rank_next[0]['id'],
                'create_adminid'=>   $grade_rank_next[0]['create_adminid'],
                'subject'       =>   $grade_rank_next[0]['subject'],
                'stu_score_type'=>   $grade_rank_next[0]['stu_score_type'],
                'score'         =>   $grade_rank_next[0]['score'],
                'rank'          =>   $grade_rank_next[0]['rank'],
                'file_url'      =>   $grade_rank_next[0]['file_url'],
                'semester'      =>   $grade_rank_next[0]['semester'],
                'total_score'   =>   $grade_rank_next[0]['total_score'],
                'grade'         =>   $grade_rank_next[0]['grade'],
                'grade_rank'    =>   $grade_rank_next[0]['grade_rank'],
                'status'        =>   $grade_rank_next[0]['status'],
                "rank_up"       =>   $rank_up,
                "rank_down"     =>   $rank_down,
            ];

            $ret = $this->t_student_score_info->field_update_list($grade_rank_next[0]['id'],$data);//houmian
        }else{

        }

        //dd($ret);

        return $this->output_succ();
    }

    /**
     *@author    sam
     *@function  新增学生考试成绩信息
     *
     */
    public function score_add_new(){
        $userid           = $this->get_in_int_val("userid");
        $create_time      = time();
        $create_adminid   = $this->get_account_id();
        $subject          = $this->get_in_int_val("subject");
        $stu_score_type   = $this->get_in_int_val("stu_score_type");
        $stu_score_time   = strtotime($this->get_in_str_val("stu_score_time"));
        $score            = $this->get_in_str_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
        $semester         = $this->get_in_int_val("semester");
        $total_score      = $this->get_in_int_val("total_score");
        $grade            = $this->get_in_int_val("grade");
        $grade_rank       = $this->get_in_str_val("grade_rank");
        $school_ex       = $this->get_in_str_val("school_ex");
        $rank_arr = explode("/",$grade_rank);
        $rank_now = $rank_arr[0];
        $grade_rank_last = $this->t_student_score_info->get_last_grade_rank($subject,$userid);
        if( $score > $total_score){
            return $this->output_err("成绩输入有误!");
        }


        if($grade_rank_last  && $rank_now != ''){
            $grade_rank_last = $grade_rank_last[0]["grade_rank"];
            $rank_last_arr = explode("/",$grade_rank_last);
            $rank_last = $rank_last_arr[0];
            if($rank_last - $rank_now >= 0){
                $rank_up = $rank_last-$rank_now;
                $rank_down = '';
            }else{
                $rank_up = '';
                $rank_down = $rank_now - $rank_last;
            }
        }else{
            $rank_up = '';
            $rank_down = '';
        }

        $score = $score*10;
        if($file_url){
            $paper_upload_time=time();
        }else{
            $paper_upload_time=0;
        }
        $ret_info = $this->t_student_score_info->row_insert([
            "userid"                => $userid,
            "create_time"           => $create_time,
            "create_adminid"        => $create_adminid,
            "subject"               => $subject,
            "stu_score_type"        => $stu_score_type,
            "stu_score_time"        => $stu_score_time,
            "score"                 => $score,
            "rank"                  => $rank,
            "file_url"              => $file_url,
            "semester"              => $semester,
            "total_score"           => $total_score,
            "grade"                 => $grade,
            "grade_rank"            => $grade_rank,
            "rank_up"               => $rank_up,
            "rank_down"             => $rank_down,
            "paper_upload_time"     => $paper_upload_time,
            "school_ex"             => $school_ex
        ],false,false,true);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 删除学生考试成绩信息
     *@path     stu_manage/score_list
     */
    public function score_del(){
        $id = $this->get_in_int_val('id');
        $this->t_student_score_info->row_delete($id);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 取消添加考试成绩
     *@path
     */
    public function score_cancel(){
        $id = $this->get_in_int_val('id');
        $reason = $this->get_in_str_val('reason');
        $data = [
            'reason' => $reason,
            'status' => 1,
        ];
        $ret = $this->t_student_score_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     * @author   sam
     * @function 创建学生和家长账号
     * @path     authority/manager_list
     */
    public function register_student_parent_account()
    {
        $account = $this->get_in_str_val("account");
        $phone   = $this->get_in_int_val("phone");
        $ret     = [];

        $ret_student = $this->t_student_info->get_userid_by_phone($phone);
        $ret_parent  = $this->t_parent_info->get_parentid_by_phone_b1($phone);

        if($ret_student != 0 && $ret_parent != 0){
            $ret['success'] =  "此手机号已经注册学生账号和家长账号";
        }else if($ret_student == 0 && $ret_parent != 0){
            $ret_student = $this->t_student_info->register($phone,md5("123456"),0,101,0,$account,"后台");
            if($ret_student){
                $ret['success'] =  "注册学生账号成功";
                $this->t_parent_child->set_student_parent($ret_parent,$ret_student);
            }
        }else if($ret_student != 0 && $ret_parent == 0){
            $ret_parent    = $this->t_parent_info->register($phone,md5("123456"),0,0,$account);
            if($ret_parent){
                $ret['success'] = "注册家长账号成功";
                $this->t_parent_child->set_student_parent($ret_parent,$ret_student);
            }
        }else if($ret_student == 0 && $ret_parent == 0){
            $ret_student = $this->t_student_info->register($phone,md5("123456"),0,101,0,$account,"后台");
            $ret_parent  = $this->t_parent_info->register($phone,md5("123456"),0,0,$account);
            if($ret_student && $ret_parent){
                $ret['success'] =  "注册学生账号和家长账号成功";
                $this->t_parent_child->set_student_parent($ret_parent,$ret_student);
            }
        }

        $ret_teacher = $this->t_teacher_info->check_teacher_phone($phone);
        if(!$ret_teacher){
            $teacher_info['phone']         = $phone;
            $teacher_info['tea_nick']      = $account;
            $teacher_info['send_sms_flag'] = 0;
            $teacher_info['wx_use_flag']   = 0;
            $teacher_info['is_test_user']  = 1;
            $this->add_teacher_common($teacher_info);
        }

        return $this->output_succ($ret);
    }

    /**
     *@author   sam
     *@function 增加老师取消课程记录
     *@path     seller_student_new2/test_lesson_list
     */
    public function add_cancel_lesson_four_hour_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        $type = 13;
        $record_info = $this->get_in_str_val("record_info");
        $add_time = time();
        $acc = $this->get_account();
        $this->t_teacher_record_list->row_insert([
            "teacherid"    =>$teacherid,
            "type"         =>$type,
            "record_info"  =>$record_info,
            "add_time"     =>$add_time,
            "acc"          =>$acc,
        ]);
        return $this->output_succ();
    }
    /**
     *@author   sam
     *@function 老师取消课程记录
     *@path     human_resource/index
     */
    public function get_teacher_cancel_lesson_list(){
        $teacherid = $this->get_in_int_val("teacherid");
        $data = $this->t_teacher_record_list->get_teacher_record_list($teacherid,13);
        return $this->output_succ(["data"=>$data]);
    }
    /**
     *@author   sam
     *@function 添加意向用户记录
     *@path     customer_service/intended_user_info
     */
    public function add_intended_user_info(){
        $phone           = $this->get_in_str_val('phone');
        $child_realname  = $this->get_in_str_val('child_realname');
        $parent_realname = $this->get_in_str_val('parent_realname');
        $relation_ship   = $this->get_in_int_val('relation_ship');
        $region          = $this->get_in_str_val('region');
        $grade           = $this->get_in_int_val('grade');
        $free_subject    = $this->get_in_int_val('free_subject');
        $region_version  = $this->get_in_int_val('region_version');
        $notes           = $this->get_in_str_val('notes');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();
        $ret = $this->t_student_info->get_student_info_by_phone($phone);
        if($ret){
            return $this->output_err('此账号已经注册');
        }
        $ret_info = $this->t_cs_intended_user_info->get_intended_info_by_phone($phone);
        if($ret_info){
            return $this->output_err("此账号信息已经输入,请勿重复输入");
        }
        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        if ($userid && $this->t_seller_student_new->get_phone($userid)) {

            $admin_nick=$this->cache_get_account_nick(
                $this->t_seller_student_new->get_admin_revisiterid($userid)
            );
            return $this->output_err("系统中已有这个人的账号了,销售负责人:$admin_nick");
            /*
              if ($this->t_test_lesson_subject->check_subject($userid,$subject))  {
              return $this->output_err("已经有了这个科目的例子了,不能增加");
              }
            */
        }

        //dd($ret);
        $this->t_cs_intended_user_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'child_realname'           => $child_realname,
            'parent_realname'          => $parent_realname,
            'relation_ship'            => $relation_ship,
            'region'                   => $region,
            'grade'                    => $grade,
            'free_subject'             => $free_subject,
            'region_version'           => $region_version,
            'notes'                    => $notes,
         ]);
        $this->add_tran_stu($phone,$free_subject,$this->get_account_id(),$grade,$child_realname,2,$region_version,$notes);

        return $this->output_succ();
    }

    public function reset_intended_user_info(){
        $phone           = $this->get_in_str_val('phone');
        $child_realname  = $this->get_in_str_val('child_realname');
        $grade           = $this->get_in_int_val('grade');
        $free_subject    = $this->get_in_int_val('free_subject');
        $region_version  = $this->get_in_int_val('region_version');
        $notes           = $this->get_in_str_val('notes');

        $ret = $this->t_student_info->get_student_info_by_phone($phone);
        if(!$ret){
             $this->add_tran_stu($phone,$free_subject,$this->get_account_id(),$grade,$child_realname,2,$region_version,$notes);
        }else{
            $userid = $ret["userid"];
            $this->t_student_info->field_update_list($userid,[
                "editionid"=>$region_version
            ]);
            $this->t_seller_student_new->field_update_list($userid,[
                "user_desc"           => $notes,
            ]);


        }
        return $this->output_succ();

    }

    /**
     *@author   sam
     *@function 删除意向用户记录
     *@path     customer_service/intended_user_info
     */
    public function del_intended_user_info(){
        $id = $this->get_in_int_val('id');
        $this->t_cs_intended_user_info->row_delete($id);
        return $this->output_succ();
    }

    /**
     *@author    sam
     *@function  修改意向用户记录
     *@path      customer_service/intended_user_info
     */
    public function edit_intended_user_info(){

        $id              = $this->get_in_int_val("id");
        $phone           = $this->get_in_str_val('phone');
        $child_realname  = $this->get_in_str_val('child_realname');
        $parent_realname = $this->get_in_str_val('parent_realname');
        $relation_ship   = $this->get_in_int_val('relation_ship');
        $region          = $this->get_in_str_val('region');
        $grade           = $this->get_in_int_val('grade');
        $free_subject    = $this->get_in_int_val('free_subject');
        $region_version  = $this->get_in_int_val('region_version');
        $notes           = $this->get_in_str_val('notes');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();
        $data = [
            'id'                       => $id,
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'child_realname'           => $child_realname,
            'parent_realname'          => $parent_realname,
            'relation_ship'            => $relation_ship,
            'region'                   => $region,
            'grade'                    => $grade,
            'free_subject'             => $free_subject,
            'region_version'           => $region_version,
            'notes'                    => $notes,
         ];

        $ret = $this->t_cs_intended_user_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 添加用户投诉信息
     *@path     customer_service/complaint_info
     */
    public function add_complaint_user_info(){
        $phone           = $this->get_in_str_val('phone');
        $username        = $this->get_in_str_val('username');
        $complaint_user_type = $this->get_in_int_val('complaint_user_type');
        $content         = $this->get_in_str_val('content');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();

        $this->t_cs_complaint_user_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'username'                 => $username,
            'complaint_user_type'      => $complaint_user_type,
            'content'                  => $content,
         ]);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 删除用户投诉信息
     *@path     customer_service/complaint_info
     */
    public function del_complaint_user_info(){
        $id = $this->get_in_int_val('id');
        $this->t_cs_complaint_user_info->row_delete($id);
        return $this->output_succ();
    }

    /**
     *@author    sam
     *@function  修改意向用户记录
     *@path      customer_service/complaint_info
     */
    public function edit_complaint_user_info(){

        $id              = $this->get_in_int_val("id");
        $phone           = $this->get_in_str_val('phone');
        $username        = $this->get_in_str_val('username');
        $complaint_user_type = $this->get_in_int_val('complaint_user_type');
        $content         = $this->get_in_str_val('content');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();
        $data = [
            'id'                       => $id,
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'username'                 => $username,
            'complaint_user_type'      => $complaint_user_type,
            'content'                  => $content,
         ];

        $ret = $this->t_cs_complaint_user_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 添加用户建议信息
     *@path     customer_service/proposal_info
     */
    public function add_proposal_info(){
        $phone           = $this->get_in_str_val('phone');
        $username        = $this->get_in_str_val('username');
        $complaint_user_type = $this->get_in_int_val('complaint_user_type');
        $content         = $this->get_in_str_val('content');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();

        $this->t_cs_proposal_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'username'                 => $username,
            'complaint_user_type'      => $complaint_user_type,
            'content'                  => $content,
         ]);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 删除用户建议信息
     *@path     customer_service/proposal_info
     */
    public function del_proposal_info(){
        $id = $this->get_in_int_val('id');
        $this->t_cs_proposal_info->row_delete($id);
        return $this->output_succ();
    }

    /**
     *@author    sam
     *@function  修改用户建议记录
     *@path      customer_service/complaint_info
     */
    public function edit_proposal_info(){

        $id              = $this->get_in_int_val("id");
        $phone           = $this->get_in_str_val('phone');
        $username        = $this->get_in_str_val('username');
        $complaint_user_type = $this->get_in_int_val('complaint_user_type');
        $content         = $this->get_in_str_val('content');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();
        $data = [
            'id'                       => $id,
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'phone'                    => $phone,
            'username'                 => $username,
            'complaint_user_type'      => $complaint_user_type,
            'content'                  => $content,
         ];

        $ret = $this->t_cs_proposal_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    public function show_student_single_subject()
    {
        $teacherid = $this->get_in_int_val("teacherid");
        $subject = $this->get_in_int_val("subject");
        $studentid   = $this->get_in_int_val("studentid");
        $start_time  = strtotime($this->get_in_str_val("start_time"));
        $end_time    = strtotime($this->get_in_str_val("end_time"))+86400;
        $ret_info = $this->t_lesson_info->get_student_single_subject($start_time,$end_time,$teacherid,$subject,$studentid);

        foreach ($ret_info as &$item) {
            # code...
            $this->cache_set_item_teacher_nick($item,"teacherid","teacher_nick");
            $this->cache_set_item_assistant_nick($item,"assistantid","assistant_nick");
            $item['count'] = $item['count']/100;
            $item['lesson_start']    = \App\Helper\Utils::unixtime2date($item['lesson_start'] );
            $item['lesson_end']      = \App\Helper\Utils::unixtime2date($item['lesson_end'] );
        }
        return $this->output_succ(['data'=>$ret_info]);
        # code...
    }
    public function get_admin_work_status(){
        $account_role = $this->get_in_int_val("account_role",-1);
        $list = $this->t_manager_info->get_admin_work_status_info($account_role);
        foreach($list as &$val){
            $val["admin_work_status_str"]=$val["admin_work_status"]==1?"工作":"休息";
        }
        return $this->output_succ(["data"=>$list]);
    }
    public function set_admin_work_status(){
        $status = $this->get_in_int_val("status");
        $adminid = $this->get_in_int_val("adminid");
        $this->t_manager_info->field_update_list($adminid,[
            "admin_work_status" =>$status
        ]);

        // 添加操作日志
        $this->t_user_log->add_data("设置招师工作状态");
        return $this->output_succ();
    }
    public function config_date_set () {
        $config_date_type     = $this->get_in_int_val("config_date_type");
        $config_date_sub_type = $this->get_in_int_val("config_date_sub_type");
        $opt_time=$this->get_in_unixtime_from_str("opt_time");
        $value                = $this->get_in_int_val("value");
        if ( in_array($config_date_type , array(
            E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY )) ) {
            $opt_time = strtotime(date("Y-m-01", $opt_time) );
        }

        if ( $config_date_type== E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY  ) {
            if (!$this->check_account_in_arr(["jim","yueyue"]))  {
                return $this->output_err("没有权限");
            }
        }

        $this->t_config_date->set_config_value($config_date_type,$opt_time,$value);

        return $this->output_succ();
    }
    //修改助教特殊申请配额
    public function teach_assistant_config_date_set () {
        $config_date_type     = $this->get_in_int_val("config_date_type");
        $config_date_sub_type = $this->get_in_int_val("config_date_sub_type");
        $opt_time=$this->get_in_unixtime_from_str("opt_time");
        $value                = $this->get_in_int_val("value");
        if ( in_array($config_date_type , array(
            E\Econfig_date_type::V_MONTH_MARKET_TEACH_ASSISTANT_DIFF_MONEY )) ) {
            $opt_time = strtotime(date("Y-m-01", $opt_time) );
        }

        if ( $config_date_type== E\Econfig_date_type::V_MONTH_MARKET_TEACH_ASSISTANT_DIFF_MONEY  ) {
            if (!$this->check_account_in_arr(["jim","yueyue"]))  {
                return $this->output_err("没有权限");
            }
        }

        $this->t_config_date->set_config_value($config_date_type,$opt_time,$value);

        return $this->output_succ();
    }
    public function set_teacher_train_through_info(){
        $phone           = $this->get_in_str_val('phone');
        $adminid = $this->get_in_int_val("adminid");
        $create_time = $this->t_manager_info->get_create_time($adminid);
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $this->t_teacher_info->field_update_list($teacherid,[
            "train_through_new"   =>1,
            "train_through_new_time"=>$create_time
        ]);

        // $new_train_flag = $this->t_teacher_info->get_new_train_flag($teacherid);
        //$lessonid = $this->t_lesson_info_b3->get_first_new_train_lessonid();
        /* if($new_train_flag==0 && $lessonid >0){

           }*/
        return $this->output_succ();
    }

    public function get_teacherid_by_phone(){
        $phone           = $this->get_in_str_val('phone');
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);

        if(empty($teacherid)){
           return $this->output_err("没有老师帐号");
        }else{
            $data = $this->t_teacher_info->field_get_list($teacherid,"teacherid,level,teacher_money_type");
           return $this->output_succ(["data"=>$data]);
        }
    }



    //获取试听课学生试卷
    public function get_stu_test_paper(){
        $lessonid = $this->get_in_int_val("lessonid");
        $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        $test_lesson_subject_id =$this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
        $stu_test_paper  = $this->t_test_lesson_subject->get_stu_test_paper($test_lesson_subject_id);
        if(empty($stu_test_paper)){
             return $this->output_err("没有试卷");
        }

        \App\Helper\Utils::logger("url:$stu_test_paper");

        // $stu_test_paper = "f59b6c7e660afbd216fd6ca5613ffdf81489146606255.jpg";
        $url = \App\Helper\Utils::gen_download_url($stu_test_paper);
        \App\Helper\Utils::logger("url2:$url");
        // dd($url);
        return $this->output_succ(["data"=>$url]);
    }
    public function  agent_reset_info () {
        $id=$this->get_in_id();
        $this->t_agent->reset_user_info($id);
        return $this->output_succ();
    }


    //获取老师入职后第一周第二周试听课信息
    public function get_teacher_week_test_lesson_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $train_through_new_time = $this->get_in_int_val("train_through_new_time");
        $first_start = strtotime("2017-04-01");
        $first_end = strtotime("2017-05-01");
        $second_end = strtotime("2017-06-01");
        $third_end = strtotime("2017-07-01");
        $fourth_end = strtotime("2017-08-01");
        $qz_tea_arr = [];
        $qz_tea_arr[] = $teacherid;
        $first_info  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$first_start,$first_end);
        $second_info  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$first_end,$second_end);
        $third_info  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$second_end,$third_end);
        $fourth_info  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$third_end,$fourth_end);
        $data=[];
        $data["first_lesson_num"] = isset($first_info[$teacherid]["all_lesson"])?$first_info[$teacherid]["all_lesson"]:0;
        $data["first_order_num"] = isset($first_info[$teacherid]["order_num"])?$first_info[$teacherid]["order_num"]:0;
        $data["second_lesson_num"] = isset($second_info[$teacherid]["all_lesson"])?$second_info[$teacherid]["all_lesson"]:0;
        $data["second_order_num"] = isset($second_info[$teacherid]["order_num"])?$second_info[$teacherid]["order_num"]:0;
        $data["first_per"] = !empty($data["first_lesson_num"])?round($data["first_order_num"]/$data["first_lesson_num"]*100,2):0;
        $data["second_per"] = !empty($data["second_lesson_num"])?round($data["second_order_num"]/$data["second_lesson_num"]*100,2):0;
        $data["third_lesson_num"] = isset($third_info[$teacherid]["all_lesson"])?$third_info[$teacherid]["all_lesson"]:0;
        $data["third_order_num"] = isset($third_info[$teacherid]["order_num"])?$third_info[$teacherid]["order_num"]:0;
        $data["third_per"] = !empty($data["third_lesson_num"])?round($data["third_order_num"]/$data["third_lesson_num"]*100,2):0;

        $data["fourth_lesson_num"] = isset($fourth_info[$teacherid]["all_lesson"])?$fourth_info[$teacherid]["all_lesson"]:0;
        $data["fourth_order_num"] = isset($fourth_info[$teacherid]["order_num"])?$fourth_info[$teacherid]["order_num"]:0;
        $data["fourth_per"] = !empty($data["fourth_lesson_num"])?round($data["fourth_order_num"]/$data["fourth_lesson_num"]*100,2):0;


        return $this->output_succ(["data"=>$data]);
    }
    public function set_admin_menu_config () {
        $menu_config= $this->get_in_str_val("menu_config");
        $adminid=$this->get_account_id();
        $this->t_manager_info->field_update_list($adminid,[
            "menu_config" =>  $menu_config
        ]);
        sleep(2);
        (new  login() )->reset_power($this->get_account());
        return $this->output_succ();
    }
    public function get_admin_member_config() {
        $adminid=$this->get_account_id();
        $row= $this->t_manager_info->field_get_list($adminid,"menu_config");
        return $this->output_succ([
            "menu_config"=> $row["menu_config"]
        ]);

    }
    public function upload_origin_xlsx() {
        $adminid= $this->get_account_id();

        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            (new common_new()) ->upload_from_xls_data( $realPath);

            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            $now=time(NULL);
            $title_info=array_shift($arr);
            if ($title_info[0] != "key0" ) {
                return $this->output_err("文件格式不对!");
            }
            $origin_arr=[];
            $key0="";
            $key1="";
            $key2="";
            $key3="";
            $key4="";
            $value="";
            $fix="";
            foreach ($arr as $index => $item) {
                $key0= trim($item[0])? trim($item[0]): $key0 ;
                $key1= trim($item[1])? trim($item[1]): $key1 ;
                $key2= trim($item[2])? trim($item[2]): $key2 ;
                $key3= trim($item[3])? trim($item[3]): $key3 ;
                $key4= trim($item[4])? trim($item[4]): $key4 ;
                $value= trim($item[5]);
                $fix= trim($item[6])? trim($item[6]): $fix;

                $fix_value=$fix.$value;
                $origin_arr[]=$fix_value;
                $this->t_origin_key->row_insert([
                    "create_time" => $now,
                    "key0" => $key0,
                    "key1" => $key1,
                    "key2" => $key2,
                    "key3" => $key3,
                    "key4" => $key4,
                    "value" => $fix_value ,
                ],true);

                /*
                0 => array:7 [
                    0 => "key1"
                    1 => "key2"
                    2 => "key3"
                    3 => "key4"
                    4 => "value"
                    5 => "fix"
                    6 => null
                ]
                */
            }
            \App\Helper\Common::redis_set_json("ORIGIN_UPLOAD_$adminid",$origin_arr);


            return $this->output_succ() ;
        } else {
            return $this->output_err("没有文件") ;
        }
    }
    public function download_cur_origin_info() {
        $adminid= $this->get_account_id();
        $origin_arr=\App\Helper\Common::redis_get_json("ORIGIN_UPLOAD_$adminid");
        $list= [];
        foreach ($origin_arr as $value ) {
            $list[]=["value"=>$value, "urlencode" => urlencode($value)  ];
        }
        $this->out_xls( \App\Helper\Utils::list_to_page_info($list));

    }

    public function check_add_test_lesson() {
        $userid       = $this->get_in_userid();
        $adminid      = $this->get_account_id();
        $account_role = $this->get_account_role();
        $seller_level = $this->t_manager_info->get_seller_level($adminid);

        $user_test_lesson_list= $this->t_lesson_info_b2->get_test_lesson_count_by_userid($userid,0, -1 );
        $user_test_lesson_count = count( $user_test_lesson_list) ;
        if ($user_test_lesson_count>5 && $account_role !=12) {
            return $this->output_err("已经 $user_test_lesson_count 次试听了，超过5次，不可试听");
        }

        $test_lesson_list = $this->t_seller_student_new->get_test_lesson_list($adminid);

        $test_lesson_count = count( $test_lesson_list);
        $seller_hold_test_lesson_user_count_config= \App\Helper\Config::get_config("seller_hold_test_lesson_user_count");
        $cur_test_lesson_count_max= @$seller_hold_test_lesson_user_count_config[$seller_level];
        \App\Helper\Utils::logger("cur_require_count:$test_lesson_count,   cur_require_count_max: $cur_test_lesson_count_max" );

        if ($test_lesson_count > $cur_test_lesson_count_max  && $account_role!=12) {
            return $this->output_err(
                "目前当前已试听数 $test_lesson_count,　超过 $cur_test_lesson_count_max ,不可申请, 请将无效的试听用户回流公海，才能提交 新试听申请 ",
                ["flag" => "goto_test_lesson_list"]);
        }
        return $this->output_succ();
    }

    public function seller_test_lesson_list( ) {
        $this->get_account_id();
    }

    public function get_rcrai_login_info() {
        $adminid= $this->get_account_id();
        $ret_str=file_get_contents("http://api.rcrai.com/leoedu/staff/job_number/$adminid");
        $ret_arr=\App\Helper\Utils::json_decode_as_array($ret_str,true);
        return $this->output_succ(["data" => $ret_arr ]);
    }

    public function get_lesson_stu_tea_time(){
        $lessonid = $this->get_in_int_val("lessonid");
        $info = $this->t_lesson_info->field_get_list($lessonid,"userid,teacherid,lesson_end");
        $user_in = $this->t_lesson_opt_log->get_time_by_lesson($lessonid,$info["userid"],1);
        $user_out = $this->t_lesson_opt_log->get_time_by_lesson($lessonid,$info["userid"],2);
        $stu_time = 0;
        foreach($user_in as $k=>$val){
            if(isset($user_out[$k])){
                $stu_time +=$user_out[$k]["opt_time"]-$val["opt_time"];
            }else{
                 $stu_time +=$info["lesson_end"]-$val["opt_time"];
            }
        }

        $teacher_in = $this->t_lesson_opt_log->get_time_by_lesson($lessonid,$info["teacherid"],1);
        $teacher_out = $this->t_lesson_opt_log->get_time_by_lesson($lessonid,$info["teacherid"],2);
        $tea_time = 0;
        foreach($teacher_in as $k=>$val){
            if(isset($teacher_out[$k])){
                $tea_time +=$teacher_out[$k]["opt_time"]-$val["opt_time"];
            }else{
                $tea_time +=$info["lesson_end"]-$val["opt_time"];
            }
        }
        $data["stu_time"] = $stu_time;
        $data["tea_time"] = $tea_time;
        return $this->output_succ(["data" => $data ]);
    }

    //修改学生信息
    public function change_stu_info()
    {
        $userid    = $this->get_in_int_val("studentid");
        $stu_nick     = mb_substr($this->get_in_str_val("stu_nick"), 0, 10, 'utf-8');
        $address      = $this->get_in_str_val("address");
        $school       = $this->get_in_str_val("school");
        $editionid    = $this->get_in_int_val("editionid");
        $region       = $this->get_in_str_val("region");
        $gender         = $this->get_in_int_val("sexy");
        $birth        = $this->get_in_int_val("birth");
        $stu_email    = trim($this->get_in_str_val("stu_email"));
        $realname = $this->get_in_str_val("realname");
        $province       = $this->get_in_int_val("province");
        $city = $this->get_in_str_val("city");
        $area = $this->get_in_str_val("area");
        if(!empty($stu_email)){
            if(preg_match('/^[1-9]\d{4,10}$/',$stu_email)){
                $stu_email = $stu_email."@qq.com";
            }else{
                $pattern = "/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/i";
                if ( !preg_match( $pattern, $stu_email )){
                   return $this->output_err("邮箱格式错误!");
                }
            }

        }
        // TODO check user login and the

        /* $ret_auth = $this->manage_model->check_permission($this->account, CHANGE_STU_INFO);
        if(!$ret_auth)
        outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));*/

        $this->t_student_info->field_update_list($userid,[
            "nick"    =>$stu_nick,
            "address" =>$address,
            "school"  =>$school,
            "editionid"=>$editionid,
            "gender"   =>$gender,
            "birth"    =>$birth,
            "stu_email"=>$stu_email,
            "realname" =>$realname,
        ]);

        // 添加操作日志
        $this->t_user_log->add_data("修改个人资料", $userid);
        if($region){
            $this->t_student_info->field_update_list($userid,[
                "region"   =>$region,
                "province" =>$province,
                "city"     =>$city,
                "area"     =>$area
            ]);

        }
        return $this->output_succ();
    }

    //获取学生科目教材列表
    public function get_subject_textbook_list(){
        $userid    = $this->get_in_int_val("userid");
        $list = $this->t_student_subject_list->get_info_by_userid($userid);
        foreach($list  as &$item){
            $item["editionid_str"] =  E\Eregion_version::get_desc ($item["editionid"]);
            $item["subject_str"] =  E\Esubject::get_desc ($item["subject"]);
        }
        return $this->output_succ(["data"=>$list]);
    }

    //新增学生科目教材
    public function set_user_subject_textbook(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        $editionid   = $this->get_in_int_val("editionid");
        if($subject<=0){
            return $this->output_err("请选择科目");
        }
        $exist_flag = $this->t_student_subject_list->field_get_value_2($userid,$subject,"userid");
        if($exist_flag){
            return $this->output_err("该科目已存在");
        }
        $this->t_student_subject_list->row_insert([
            "userid"  => $userid,
            "subject" => $subject,
            "editionid" =>$editionid
        ]);

        $list = $this->t_student_subject_list->get_info_by_userid($userid);
        $subject_ex="";
        foreach($list as $item){
             $subject_ex .= E\Esubject::get_desc ($item["subject"]).",";
        }
        $this->t_student_info->field_update_list($userid,[
            "subject_ex"  =>trim($subject_ex,",")
        ]);

        // 添加操作日志
        $this->t_user_log->add_data('增加科目'.E\Esubject::get_desc($subject).',增加教材,教材id:'.$editionid, $userid);
        return $this->output_succ();
    }

    //删除学生科目
    public function delete_user_subject_textbook(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        $editionid =$this->t_student_subject_list->get_editionid($userid,$subject);
        $this->t_student_subject_list->row_delete_2($userid,$subject);
        $list = $this->t_student_subject_list->get_info_by_userid($userid);
        $subject_ex="";
        foreach($list as $item){
            $subject_ex .= E\Esubject::get_desc ($item["subject"]).",";
        }
        $this->t_student_info->field_update_list($userid,[
            "subject_ex"  =>trim($subject_ex,",")
        ]);

        // 添加操作日志
        $this->t_user_log->add_data("删除教材,教材id:".$editionid, $userid);
        return $this->output_succ();


    }

    //修改学生科目教材
    public function update_user_subject_textbook(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        $editionid   = $this->get_in_int_val("editionid");

        $this->t_student_subject_list->field_update_list_2($userid,$subject,[
            "editionid" =>$editionid
        ]);

        // 添加操作日志
        $this->t_user_log->add_data("修改教材,教材id:".$editionid, $userid);

        return $this->output_succ();
    }


    //根据学生科目获取教材
    public function get_editionid(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        $editionid =$this->t_student_subject_list->get_editionid($userid,$subject);
        return $this->output_succ(["editionid"=>$editionid]);
    }

    //配置教材版本
    public function set_teacher_textbook(){
        $id = $this-> get_in_int_val('id');
        $textbook_list = \App\Helper\Utils::json_decode_as_int_array( $this->get_in_str_val("textbook_list"));
        $teacher_textbook = implode(",",$textbook_list);
        $this->t_location_subject_grade_textbook_info->field_update_list($id,[
            "teacher_textbook"    => $teacher_textbook
        ]);

        return $this->output_succ();
    }


    public function get_stu_nick_info(){
        $userid    = $this->get_in_int_val("userid",166241);
        $stu_info = $this->t_student_info->field_get_list($userid,"nick,grade,phone");
        $grade =  E\Egrade::get_desc ($stu_info["grade"]);
        $location = \App\Helper\Common::get_phone_location($stu_info["phone"]);
        $location = substr($location,0,-6);
        $tea_info = $this->t_lesson_info_b3->get_teacher_identity($userid);
        $str="";
        if(!empty($tea_info)){
            foreach($tea_info as $val){
                $str .= E\Eidentity::get_desc ($val["identity"]).",";
            }
        }
        $data=["nick"=>$stu_info["nick"],"location"=>$location,"grade"=>$grade,"identity"=>$str];


        return $this->output_succ(["data"=>$data]);

    }
    public function get_order_desc_html_str() {
        $str=$this->get_in_str_val("str");
        $arr=\App\Helper\Utils::json_decode_as_array($str,true);
        $tr_str="";

        $row_count=0;
        if ( is_array($arr)){
            foreach($arr as $item ) {
                $succ_flag= $item["succ_flag"];
                if ($succ_flag==1) {
                    $succ_str="<font color=\"green\">匹配</font>";
                } else if ($succ_flag==2) {
                    $succ_str="<font color=\"blue\">手动不启用</font>";
                }else{
                    $succ_str="<font color=\"red\">未匹配</font>";
                }

                if (@$item["can_period_flag"]) {
                    $period_str="<font color=\"red\">分期</font>";
                }else{
                    $period_str="<font color=\"green\">全款</font>";
                }

                $need_spec_require_flag= $item["need_spec_require_flag"];
                $need_spec_require_flag_str="";
                if ( $need_spec_require_flag ) {
                    $need_spec_require_flag_str="<font color=\"red\">需要特殊申请</font>";
                }else{

                }

                if(isset ($item["title"] )) { //旧版
                    $tr_str.= " <tr><td> <font color=\"blue\"> ". $item["title"]. "</font> <td>".$succ_str."<td>".$item["desc"]. "<td> <font color=\"red\"> ". $item["price"]."  </font> <td> </tr> ";

                }else{
                    $order_activity_config = $item["order_activity_type"];
                    $order_activity_type = $item["order_activity_type"];

                    if($item["order_activity_type"] == 0){
                        $order_activity_config = 1;
                    }
                    $ret = $this->t_order_activity_config->get_by_id($order_activity_config);

                    $title = '';
                    if($ret){
                        $title = $ret['title'];
                    }

                    $tr_str.= " <tr  class=\"table-row\" data-order_activity_type=\"$order_activity_type\" data-succ_flag=\"$succ_flag\" data-need_spec_require_flag=\"$need_spec_require_flag\" ><td> <font color=\"blue\"> <a href=\"/seller_student_new2/show_order_activity_info?order_activity_type={$order_activity_type}\" target=\"_blank\"> ". $title. "</font> </a> <td>".$succ_str."<td>".$item["activity_desc"]
                           . "<td> <font color=\"red\"> ". $item["cur_price"]."  </font> "
                           . "<td> <font color=\"red\"> ". $item["cur_present_lesson_count"]."  </font> "
                           . "<td> <font color=\"red\"> ". @$item["change_value"]."  </font> "
                           . "<td> <font color=\"red\"> ". @$item["off_money"]."  </font> "
                           . "<td> <font color=\"red\"> ". $need_spec_require_flag_str ."  </font> "
                           . "<td>  ". $period_str
                           . " </tr> ";
                }
            }
            $row_count= count( $arr);
        }
        $html_str="<table class=\"table table-bordered table-striped\" > <tr class=\"table-header\"> <th>项目 <th> 匹配与否 <th>说明 <th>  计算后的价格  <th>  计算后的赠送课时 <th> 修改值 <th> 现金价值 <th> 特殊申请 <th>  启用分期  </tr>  $tr_str </table>";
        return $this->output_succ(["html_str" => $html_str, "row_count" =>$row_count ] );
    }

    public function add_textbook_one(){
        $teacher_textbook = $this->get_in_str_val("teacher_textbook");
        $grade = $this->get_in_int_val('grade');
        $subject = $this->get_in_int_val('subject');
        $province = trim($this->get_in_str_val('province'));
        $city = trim($this->get_in_str_val('city'));
        $educational_system = trim($this->get_in_str_val('educational_system'));
        $is_exist = $this->t_location_subject_grade_textbook_info->check_is_exist($province,$city,$grade,$subject);
        if($is_exist > 0 ){
            return $this->output_err("已有相同地区科目信息!");
        }
        $this->t_location_subject_grade_textbook_info->row_insert([
            "province"  =>$province,
            "city"      =>$city,
            "subject"   =>$subject,
            "grade"     =>$grade,
            "teacher_textbook"=>$teacher_textbook,
            "educational_system"=>$educational_system
        ]);
        return $this->output_succ();
    }
    public function  fulltime_teacher_data_with_type () {
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $type= $this->get_in_str_val("type");
        if ($type=="apply_num") {
            $value = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_count($start_time,$end_time); //成功注册人数
        }else if($type=="arrive_num"){
            $arrive_num  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);//
            $video_num   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);//视频试讲人数
            $value = $arrive_num + $video_num;
        }else if($type=="arrive_through"){
            $arrive_through_num = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
            $video_through_num  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);
            $value = $arrive_through_num + $video_through_num;
        }else if($type=="second_through"){
            $value  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);
        }else if($type=="enter_num"){
            $value = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time);
        }else if($type=="arrive_num_per"){
            $start_time = 1498838400;
            $apply_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_total($start_time,$end_time); //累计注册人数

            $arrive_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);//累计面试人数
            $video_num_total   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);
            $arrive_total = $arrive_num_total + $video_num_total;
            if($apply_total>0){
                $value = round(100*$arrive_total/$apply_total,2);
                $value .= '%('.$arrive_total.'/'.$apply_total.')';
            }else{
                $value = '0%';
            }
        }else if($type=="arrive_through_per"){
            $start_time = 1498838400;
            $arrive_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);
            $video_num_total   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);

            $video_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);//视频试讲通过人数
            $arrive_through_num_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
            $arrive_total = $arrive_num_total +  $video_num_total;
            $arrive_through = $video_through_num_total + $arrive_through_num_total;
            if($arrive_total>0){
                $value = round(100*$arrive_through/$arrive_total,2);
                $value .= '%('.$arrive_through.'/'.$arrive_total.')';
            }else{
                $value = '0%';
            }

        }else if($type=="second_through_per"){
            $start_time = 1498838400;
            $video_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);//视频试讲通过人数
            $arrive_through_num_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
            $arrive_through = $video_through_num_total + $arrive_through_num_total;


            $second_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);

            if($arrive_through>0){
                $value = round(100* $second_through_num_total/$arrive_through,2);
                $value .= '%('. $second_through_num_total.'/'.$arrive_through.')';
            }else{
                $value = '0%';
            }

        }else if($type=="enter_num_per"){
            $start_time = 1498838400;
            $second_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);
            $enter_num_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time); //入职人数
            if($second_through_num_total >0){
                $value = round(100* $enter_num_total/$second_through_num_total ,2);
                $value .= '%('. $enter_num_total.'/'.$second_through_num_total .')';
            }else{
                $value = '0%';
            }
        }elseif($type=="leave_num"){
            $value = $this->t_manager_info->get_admin_leave_num($start_time,$end_time);
        }elseif($type=="leave_per"){
            $start_time = 1498838400;
            $leave_num = $this->t_manager_info->get_admin_leave_num($start_time,$end_time);
            $enter_num = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time);
            $value = $enter_num>0?round($leave_num/$enter_num*100,2):0;
            // $value .= "%";
            $value .= '%('. $leave_num.'/'.$enter_num.')';


        }

        return $this->output_succ(["value"=>$value]);

    }

    public function  fulltime_teacher_count_data_with_type () {
        $this->switch_tongji_database();
        // $this->check_and_switch_tongji_domain();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $type= $this->get_in_str_val("type");
        $lesson_end_time = $this->get_test_lesson_end_time($end_time);
        if ($type=="fulltime_teacher_count") {
            $value = $this->t_manager_info->get_fulltime_teacher_num($end_time);//全职老师总人数
        }else if($type=="fulltime_teacher_student"){
            $fulltime_lesson_count = $this->t_lesson_info_b3->get_teacher_list($start_time,$end_time,1);//统计全职老师总人数/课时
            $value =$fulltime_lesson_count["stu_num"]; //全职老师所带学生总数

        }else if($type=="fulltime_teacher_pro"){
            $fulltime_teacher_count = $this->t_manager_info->get_fulltime_teacher_num($end_time);
            $ret_platform_teacher_lesson_count = $this->t_lesson_info_b3->get_teacher_list($start_time,$end_time);//统计平台老师总人数/课时
            $ret['platform_teacher_count'] = $ret_platform_teacher_lesson_count["tea_num"];//统计平台老师总人数
            if($ret['platform_teacher_count']){
                $ret['fulltime_teacher_pro'] = round($fulltime_teacher_count*100/$ret['platform_teacher_count'],2);
                $value = $ret['fulltime_teacher_pro'].'%('.$fulltime_teacher_count.'/'.$ret['platform_teacher_count'].')';
            }else{
                $value='0%';
            }

        }else if($type=="fulltime_teacher_student_pro"){
            $fulltime_lesson_count = $this->t_lesson_info_b3->get_teacher_list($start_time,$end_time,1);//统计全职老师总人数/课时
            $fulltime_teacher_student =$fulltime_lesson_count["stu_num"]; //全职老师所带学生总数
            $platform_teacher_student_list= $this->t_student_info->get_total_student_num($type);//统计平台学生数
            $platform_teacher_student = $platform_teacher_student_list[0]['platform_teacher_student'];
            $fulltime_teacher_student_pro = round($fulltime_teacher_student*100/$platform_teacher_student,2);
            $value = $fulltime_teacher_student_pro.'%('.$fulltime_teacher_student.'/'.$platform_teacher_student.')';

        }else if($type=="fulltime_teacher_lesson_count"){
            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }
            $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
            $value=0;
            foreach($lesson_count as $val){
                $value +=$val["lesson_all"]/100;
            }
        }else if($type=="fulltime_teacher_cc_per"){

            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }

            $list = $ret_info;
            $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time, $lesson_end_time);
            $tran_avg= $lesson_avg=[];
            foreach($ret_info as &$item){
                $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
                $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;

                @$tran_avg["cc_lesson_num"] +=$item["cc_lesson_num"];
                @$tran_avg["cc_order_num"] +=$item["cc_order_num"];
            }

            $tran_avg["cc_per"] = !empty($tran_avg["cc_lesson_num"])?round($tran_avg["cc_order_num"]/$tran_avg["cc_lesson_num"]*100,2):0;
            if( $tran_avg["cc_per"]){

                $value = $tran_avg["cc_per"].'%('.$tran_avg["cc_order_num"].'/'.$tran_avg["cc_lesson_num"].')';
            }else{
                $value="0%";
            }


        }else if($type=="part_teacher_lesson_count"){
            $ret_platform_teacher_lesson_count = $this->t_lesson_info_b3->get_teacher_list($start_time,$end_time);//统计平台老师总人数/课时
            $ret['platform_teacher_lesson_count'] = round($ret_platform_teacher_lesson_count["lesson_count"]/100);//全职老师完成的课耗总数
            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }
            $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
            $value=0;
            foreach($lesson_count as $val){
                $value +=$val["lesson_all"]/100;
            }

            $value = $ret['platform_teacher_lesson_count']-$value;

        }else if($type=="part_teacher_cc_per"){
            $test_person_num_total= $this->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$lesson_end_time);
            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }

            $list = $ret_info;
            $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time, $lesson_end_time);
            $tran_avg= $lesson_avg=[];
            foreach($ret_info as &$item){
                $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
                $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;

                @$tran_avg["cc_lesson_num"] +=$item["cc_lesson_num"];
                @$tran_avg["cc_order_num"] +=$item["cc_order_num"];
            }
            $part_lesson = @$test_person_num_total['person_num']-@$tran_avg["cc_lesson_num"];
            $part_order = @$test_person_num_total['have_order']-@$tran_avg["cc_order_num"];
            $part_per = !empty($part_lesson)?round( $part_order/$part_lesson*100,2):0;
            if($part_per){

                $value = $part_per.'%('.$part_order.'/'.$part_lesson.')';
            }else{
                $value="0%";
            }



        }else if($type=="fulltime_teacher_lesson_count_per"){
            $ret_platform_teacher_lesson_count = $this->t_lesson_info_b3->get_teacher_list($start_time,$end_time);//统计平台老师总人数/课时
            $ret['platform_teacher_lesson_count'] = round($ret_platform_teacher_lesson_count["lesson_count"]/100);//全职老师完成的课耗总数
            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,-1);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }
            $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
            $full=0;
            foreach($lesson_count as $val){
                $full +=$val["lesson_all"]/100;
            }

            $full_per = !empty( $ret['platform_teacher_lesson_count'])?round( $full/$ret['platform_teacher_lesson_count']*100,2):0;
            if($full_per){

                $value = $full_per.'%('.$full.'/'.$ret['platform_teacher_lesson_count'].')';
            }else{
                $value="0%";
            }



        }elseif($type=="platform_teacher_cc_per"){
            $test_person_num_total= $this->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$lesson_end_time);
            $part_lesson = @$test_person_num_total['person_num'];
            $part_order = @$test_person_num_total['have_order'];
            $part_per = !empty($part_lesson)?round( $part_order/$part_lesson*100,2):0;
            if($part_per){

                $value = $part_per.'%('.$part_order.'/'.$part_lesson.')';
            }else{
                $value="0%";
            }
        }elseif($type=="fulltime_normal_stu_num"){
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

            $normal_stu_num = $this->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
            foreach($normal_stu_num as $val){
                @$ret['fulltime_normal_stu_num'] +=$val["num"];
            }
            $value = @$ret['fulltime_normal_stu_num'];


        }elseif($type=="fulltime_normal_stu_pro"){
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

            $normal_stu_num = $this->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
            foreach($normal_stu_num as $val){
                @$ret['fulltime_normal_stu_num'] +=$val["num"];
            }
            $normal_stu_num_all = $this->t_lesson_info_b2->get_tea_stu_num_list([],$week_start,$week_end,false);
            foreach($normal_stu_num_all as $val){
                @$ret['platform_normal_stu_num'] +=$val["num"];
            }
            $ret['fulltime_normal_stu_pro'] =  @$ret['platform_normal_stu_num']>0?round(100*@$ret['fulltime_normal_stu_num']/@$ret['platform_normal_stu_num'],2):0;
            if(@$ret['fulltime_normal_stu_pro']){

                $value = @$ret['fulltime_normal_stu_pro'].'%('.@$ret['fulltime_normal_stu_num'].'/'.@$ret['platform_normal_stu_num'].')';
            }else{
                $value="0%";
            }


        }

        return $this->output_succ(["value"=>$value]);

    }


    public function  set_lesson_current_server() {
        $lessonid=$this->get_in_lessonid();
        $courseid=$this->t_lesson_info->get_courseid($lessonid);
        $current_server= $this->get_in_str_val("current_server");
        $this->t_course_order->field_update_list(
            $courseid,
            ["current_server" => $current_server]
        );

        $this->t_lesson_info->field_update_list($lessonid,[
            "xmpp_server_name" =>  $current_server,
        ]);
        return $this->output_succ();

    }

    public function email_group_add()  {
        $title=trim($this->get_in_str_val("title"));
        $email=trim($this->get_in_str_val("email"));
        $this->t_mail_group_name->row_insert([
            "title" => $title,
            "email" => $email,
        ]);
        return $this->output_succ();
    }

    public function email_group_edit()  {
        $groupid= $this->get_in_int_val("groupid");
        $title=trim($this->get_in_str_val("title"));
        $this->t_mail_group_name->field_update_list($groupid,[
            "title" => $title,
        ]);
        return $this->output_succ();
    }

    /**
     * 在邮箱组内添加新成员
     */
    public function  email_group_user_add () {
        $groupid = $this->get_in_int_val("groupid");
        $adminid = $this->get_in_adminid();

        $check_flag = $this->t_mail_group_user_list->check_is_exists($groupid,$adminid);
        if($check_flag){
            return $this->output_err("此用户已在此用户组!");
        }

        $ret = $this->t_mail_group_user_list->row_insert([
            "groupid" => $groupid,
            "adminid" => $adminid,
        ]);
        if(!$ret){
            return $this->output_err("添加失败!请重试!");
        }

        return $this->output_succ();
    }

    public function  email_group_user_del () {
        $groupid= $this->get_in_int_val("groupid");
        $adminid = $this->get_in_adminid();
        $this->t_mail_group_user_list->row_delete_2($groupid,$adminid);
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 添加老师培训信息
     *@path     tea_manage/teacher_train_list
     */
    public function add_train_info(){
        $teacherid       = $this->get_in_int_val('teacherid');
        $subject         = $this->get_in_int_val('subject');
        $train_type      = $this->get_in_int_val('train_type');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();

        $this->t_teacher_train_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'train_type'               => $train_type,
            'subject'                  => $subject,
            'teacherid'                => $teacherid,
            'status'                   => 1,
         ]);
        return $this->output_succ();
    }

     /**
     *@author   sam
     *@function 删除老师培训信息
     *@path     tea_manage/teacher_train_list
     */
    public function del_train_info(){
        $id = $this->get_in_int_val('id');
        $this->t_teacher_train_info->field_update_list($id,[
            "status" => 4,
        ]);
        return $this->output_succ();
    }

    /**
     *@author    sam
     *@function  修改意向用户记录
     *@path      customer_service/complaint_info
     */
    public function edit_train_info(){

        $id              = $this->get_in_int_val("id");
        $subject         = $this->get_in_int_val('subject');
        $train_type      = $this->get_in_int_val('train_type');
        $status          = $this->get_in_int_val('status');
        $create_adminid  = $this->get_account_id();
        $create_time     = time();
        $through_time    = time();
        $data = [
            'id'                       => $id,
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'train_type'               => $train_type,
            'subject'                  => $subject,
            'status'                   => $status,
         ];

        if($status == 3){
            $data['through_time'] = $through_time;
        }
        $ret = $this->t_teacher_train_info->field_update_list($id,$data);
        return $this->output_succ();
    }
        /**
     *@author    sam
     *@function  更改培训状态
     *@path      teacher_info/get_train_list
     */
    public function change_train_status(){

        $id              = $this->get_in_int_val("id");
        $status          = $this->get_in_int_val('status');
        $data = [
            'status'                   => $status,
         ];
        if($status == 3){
            $data['through_time'] = time();
        }
        $ret = $this->t_teacher_train_info->field_update_list($id,$data);
        return $this->output_succ();
    }


    public function xmpp_server_add() {
        $ip=trim($this->get_in_str_val("ip"));
        $server_name=trim($this->get_in_str_val("server_name"));
        $server_desc=$this->get_in_str_val("server_desc");
        $xmpp_port= $this->get_in_int_val("xmpp_port") ;
        $webrtc_port= $this->get_in_int_val("webrtc_port") ;
        $websocket_port= $this->get_in_int_val("websocket_port") ;
        $weights= $this->get_in_int_val("weights") ;

        $this->t_xmpp_server_config->row_insert([
            "ip" => $ip,
            "server_desc" => $server_desc,
            "server_name" => $server_name,
            "xmpp_port"  => $xmpp_port,
            "webrtc_port"  => $webrtc_port,
            "websocket_port"  => $websocket_port,
            "weights"  => $weights,
        ]);
        return $this->output_succ();
    }

    public function xmpp_server_set() {
        $id=$this->get_in_id();

        $weights= $this->get_in_int_val("weights") ;
        $this->t_xmpp_server_config->field_update_list($id,[
            "weights"  => $weights,
        ]);
        return $this->output_succ();
    }
    public function xmpp_server_del() {
        $id=$this->get_in_id();
        $this->t_xmpp_server_config->row_delete($id);
        return $this->output_succ();

    }

    //获取老师所带学习超过三个月的学生
    public function get_three_month_stu_num(){
        $teacherid             = $this->get_in_int_val("teacherid");
        $start_time = strtotime("2017-01-01");
        $end_time = strtotime("2017-12-01");
        $list = $this->t_lesson_info_b3->get_teacher_lesson_info($teacherid,$start_time,$end_time);
        $data = @$list[0];
        return $this->output_succ($data);

        $ret_info = $this->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time,$teacher_money_type,$show_all_flag );


        $num = $this->t_lesson_info_b3->get_tea_num_by_subject_grade($start_time,$end_time,$subject,$grade);
        return $this->output_succ([
            "num" =>$num
        ]);


        $end_time = strtotime("+1 months",$start_time);
        $list =$this->t_lesson_info_b3->get_tea_lesson_total($start_time,$end_time,0,0);
        $lesson_count = @$list["lesson_total"];

        $test_person_num_total= $this->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$end_time,-1,-1,-1,-1,-1,"",-1,-1,-1,-1,-1);
        //

        $total_arr=[];
        $total_arr["test_person_num"] =  $test_person_num_total["person_num"];//
        $total_arr["have_order"] = $test_person_num_total["have_order"];//
        //


        $total_arr["order_num_per"] = !empty($total_arr["test_person_num"])?round($total_arr["have_order"]/$total_arr["test_person_num"],4)*100:0;
        return $this->output_succ([
            "lesson_count" =>$lesson_count/100,
            "cc_per"=>$total_arr["order_num_per"]."%"
        ]);



        $normal= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,-2,0,-1,1);
        if(@$normal["lesson_start"]>0){
            $last_time =date("Y-m-d H:i",$normal["lesson_start"]);
        }else{
            $last_time="无";
        }
        $data= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,2,0,-1);
        $normal= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,-2,0,-1);
        $time = $this->t_teacher_flow->get_simul_test_lesson_pass_time($teacherid );
        if(empty($time)){
            $time = $this->t_teacher_info->get_train_through_new_time($teacherid);
        }
        if(@$data["lesson_start"]>0){
            $first_test =date("Y-m-d H:i",$data["lesson_start"]);
        }else{
            $first_test="无";
        }
        if(@$normal["lesson_start"]>0){
            $first_normal =date("Y-m-d H:i",$normal["lesson_start"]);
        }else{
            $first_normal="无";
        }

        return $this->output_succ([
            "last_time" =>@$last_time,
            "first_normal"=>$first_normal,
            "first_test"  =>$first_test
        ]);



        $data= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,2,0,-1);
        $normal= $this->t_lesson_info_b2->get_lesson_row_info($teacherid,-2,0,-1);
        $time = $this->t_teacher_flow->get_simul_test_lesson_pass_time($teacherid );
        if(empty($time)){
            $time = $this->t_teacher_info->get_train_through_new_time($teacherid);
        }
        if(@$data["lesson_start"]>0){
            $first_test =date("Y-m-d H:i",$data["lesson_start"]);
        }else{
            $first_test="无";
        }
        if(@$normal["lesson_start"]>0){
            $first_normal =date("Y-m-d H:i",$normal["lesson_start"]);
        }else{
            $first_normal="无";
        }
        $tea_arr=[$teacherid];
        $cc_num=$cc_order=$cr_num=$cr_order=0;
        $cc_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $time,time(),-1,-1,$tea_arr,2);
        foreach($cc_list as $val){
            $cc_num +=$val["person_num"];
            $cc_order +=$val["have_order"];
        }
        $cc_per= $cc_num>0?round($cc_order/$cc_num*100,2):0;
        $cr_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $time,time(),-1,-1,$tea_arr,1);

        foreach($cr_list as $val){
            $cr_num +=$val["person_num"];
            $cr_order +=$val["have_order"];
        }
        $cr_per= $cr_num>0?round($cr_order/$cr_num*100,2):0;



        return $this->output_succ([
            "first_test" =>@$first_test,
            "first_normal"=>@$first_normal,
            "cc_per"   =>$cc_per,
            "cr_per"   =>$cr_per,
        ]);


        // $date= date("m",$start_time);


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

        // return $this->output_succ([
        //     "tea_num" =>$tea_num,
        //     "lesson_count"=>$lesson_count/100,
        //     "cc_per"=>$cc_per,
        //     "cr_per"=>$cr_per

        //     // "realname" =>$tea_info["realname"],
        //     // // "have_order"   =>$cc_list["have_order"],
        //     // "tea_phone"   =>$tea_info["phone"],
        // ]);

        // $phone            = $this->get_in_str_val("phone","13958068506");
        // $tea_info = $this->t_lesson_info_b3->get_tea_info_by_stu_phone($phone);
        // return $this->output_succ([
        //                     "tea_num" =>$tea_num,
        //         "lesson_count"=>$lesson_count,
        //         "cc_per"=>$cc_per,
        //         "cr_per"=>$cr_per

        //     // "realname" =>$tea_info["realname"],
        //     // // "have_order"   =>$cc_list["have_order"],
        //     // "tea_phone"   =>$tea_info["phone"],
        // ]);

        // dd($tea_info);





        // $teacherid             = $this->get_in_int_val("teacherid",50272);
        // $start_time = strtotime("2017-10-01");
        // $end_time = strtotime("2017-11-01");
        // $tea_arr =[$teacherid];
        // $cc_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,100,$tea_arr,2);
        // if(!empty($cc_list)){
        //     $cc_list = $cc_list[$teacherid];
        //     // $cc_per = !empty($cc_list["person_num"])?round($cc_list["have_order"]/$cc_list["person_num"]*100,2):0;
        // }
        // $order_num = $this->t_teacher_money_list->get_order_num_by_time($teacherid,100,$start_time,$end_time);
        // return $this->output_succ([
        //     "person_num" =>$cc_list["person_num"],
        //     // "have_order"   =>$cc_list["have_order"],
        //     "have_order"   =>$order_num ,
        // ]);


        // $start_time = strtotime($this->get_in_str_val("start_time"));
        // $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        // $list=[];
        // $normal_lesson_num = $this->t_lesson_info_b3->get_lesson_num_by_teacherid($teacherid,$start_time,$end_time,-2);
        // $test_lesson_num = $this->t_lesson_info_b3->get_lesson_num_by_teacherid($teacherid,$start_time,$end_time,2);
        // $tea_arr =[$teacherid];
        // $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        // if(!empty($teacher_record_score)){
        //     $score_list = $teacher_record_score[$teacherid];
        //     $score = !empty($score_list["num"])?round($score_list["score"]/$score_list["num"],2):0;
        // }else{
        //     $score =0;
        // }

        // $one_score = $this->t_teacher_record_list->get_teacher_first_interview_score_info($teacherid);
        // $phone = $this->t_teacher_info->get_phone($teacherid);
        // $video_score = $this->t_teacher_lecture_info->get_teacher_first_interview_score_info($phone);
        // if(!empty($one_score) && !empty($video_score)){
        //     $time = $one_score["add_time"]-$video_score["confirm_time"];
        //     if($time<=0){
        //         $inter_score = $one_score["teacher_lecture_score"];
        //     }else{
        //          $inter_score = $video_score["teacher_lecture_score"];
        //     }
        // }elseif(!empty($one_score) && empty($video_score)){
        //     $inter_score = $one_score["teacher_lecture_score"];
        // }elseif(empty($one_score) && !empty($video_score)){
        //      $inter_score = $video_score["teacher_lecture_score"];
        // }else{
        //     $inter_score=0;
        // }
        // return $this->output_succ([
        //     "normal_lesson_num" =>$normal_lesson_num,
        //     "test_lesson_num"   =>$test_lesson_num,
        //     "record_score"      =>$score,
        //     "inter_score"       =>$inter_score
        // ]);


        // $list=[];
        // $first             = $this->get_in_int_val("teacherid",50272);
        // // $first = strtotime("2016-06-01");
        // // $first = strtotime(date("Y-m-01",strtotime("+".($i-1)." months", $first_month)));
        // $next = strtotime(date("Y-m-01",strtotime("+1 months", $first)));
        // $month = date("Y-m-d",$first);
        // $order_money_info = $this->t_order_info->get_order_lesson_money_info($first,$next);
        // $order_money_month = $this->t_order_info->get_order_lesson_money_use_info($first,$next);
        // $list["stu_num"] = @$order_money_info["stu_num"];
        // $list["all_price"] = @$order_money_info["all_price"];
        // $list["lesson_count_all"] = @$order_money_info["lesson_count_all"];
        // foreach($order_money_month as $val){
        //     $list[$val["time"]]=($val["all_price"]/100)."/".($val["lesson_count_all"]/100);
        // }
        // return $this->output_succ(["data"=>$list]);

        // /*$start_time = time()-90*86400;
        // $end_time = time();

        // /*$list = $this->t_lesson_info_b3->get_teacher_stu_three_month_list($teacherid);
        // $num=0;
        // foreach($list as $v){
        //     $userid = $v["userid"];
        //     $min = $this->t_lesson_info_b3->get_first_regular_lesson_time($teacherid,$userid);
        //     $max = $this->t_lesson_info_b3->get_last_regular_lesson_time($teacherid,$userid);
        //     if(($max - $min) >= 90*86400){
        //         $num++;
        //     }
        //     }*/
        // $start_time = strtotime("2017-07-01");
        // $end_time = strtotime("2017-10-01");
        // $tea_arr =[$teacherid];
        // $cc_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr,2);
        // if(!empty($cc_list)){
        //     $cc_list = $cc_list[$teacherid];
        //     $cc_per = !empty($cc_list["person_num"])?round($cc_list["have_order"]/$cc_list["person_num"]*100,2):0;
        // }else{
        //     $cc_per =0;
        // }
        // $cr_list        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr,1);
        // if(!empty($cr_list)){
        //     $cr_list = $cr_list[$teacherid];
        //     $cr_per = !empty($cr_list["person_num"])?round($cr_list["have_order"]/$cr_list["person_num"]*100,2):0;
        // }else{
        //     $cr_per =0;
        // }
        // $start_time = strtotime("2017-07-01");
        // $end_time = strtotime("2017-10-01");

        // $tea_arr =[$teacherid];
        // $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr,1);
        // if(!empty($teacher_record_score)){
        //     $score_list = $teacher_record_score[$teacherid];
        //     $score = !empty($score_list["num"])?round($score_list["score"]/$score_list["num"],2):0;
        // }else{
        //     $score =0;
        // }

        // //  $level_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        // //  $level = \App\Helper\Utils::get_teacher_letter_level($level_info["teacher_money_type"],$level_info["level"]);

        // return $this->output_succ(["score"=>$score,"cc_per"=>$cc_per,"cr_per"=>$cr_per]);


        //return $this->output_succ();

    }

    //更改家长姓名
    public function update_parent_name(){
        $userid              = $this->get_in_int_val("userid");
        $parent_name              = $this->get_in_str_val("parent_name");
        $parentid =$this->t_student_info->get_parentid($userid);
        $this->t_parent_info->field_update_list($parentid,[
           "nick"    =>$parent_name
        ]);
        $this->t_student_info->field_update_list($userid,[
           "parent_name" =>$parent_name
        ]);
        return $this->output_succ();

    }

    //百度分期还款明细(实时)
    public function get_baidu_period_detail_info(){
        $orderid              = $this->get_in_int_val("orderid",177);
        $list = $this->get_baidu_money_charge_pay_info($orderid);
        if(!$list){
            return $this->output_err('无数据!');
        }
        if($list["status"]>0){
           return $this->output_err($list["msg"]);
        }
        $data = $list["data"];
        foreach($data as &$item){
            if($item["bStatus"]==48){
                $item["bStatus_str"] = "已还款";
            }elseif($item["bStatus"]==80){
                $item["bStatus_str"] = "未还但未到期";
            }elseif($item["bStatus"]==112){
                $item["bStatus_str"] = "未还款";
            }elseif($item["bStatus"]==144){
                $item["bStatus_str"] = "未还并逾期";
            }
            \App\Helper\Utils::unixtime2date_for_item($item, "paidTime","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item, "dueDate","_str","Y-m-d");

        }
        return $this->output_succ(["data"=>$data]);
    }

    //百度分期还款明细
    public function get_baidu_period_detail_info_new(){
        $orderid              = $this->get_in_int_val("orderid",177);
        $data = $this->t_period_repay_list->get_order_repay_info($orderid);
        if(!$data){
            return $this->output_err('无数据!');
        }
        foreach($data as &$item){
            if($item["b_status"]==48){
                $item["b_status_str"] = "已还款";
            }elseif($item["b_status"]==80){
                $item["b_status_str"] = "未还但未到期";
            }elseif($item["b_status"]==112){
                $item["b_status_str"] = "未还款";
            }elseif($item["b_status"]==144){
                $item["b_status_str"] = "未还并逾期";
            }
            \App\Helper\Utils::unixtime2date_for_item($item, "paid_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item, "due_date","_str","Y-m-d");
            E\Erepay_status::set_item_value_str($item);

        }
        return $this->output_succ(["data"=>$data]);
    }


    //精排试听详情获取
    public function get_seller_top_lesson_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        $adminid = $this->get_in_int_val("adminid",-1);
        $list = $this->t_test_lesson_subject_require->get_seller_top_lesson_list($start_time,$end_time,$adminid);


        foreach($list as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eseller_student_status::set_item_value_str($item,"test_lesson_student_status");
            if(empty($item["teacher_dimension"])){
                $tea_in = $this->t_teacher_info->field_get_list($item["teacherid"],"test_transfor_per,identity,month_stu_num");
                $record_score = $this->t_teacher_record_list->get_teacher_first_record_score($item["teacherid"]);
                if($tea_in["test_transfor_per"]>=20){
                    $teacher_dimension="维度A";
                }elseif($tea_in["test_transfor_per"]>=10 && $tea_in["test_transfor_per"]<20){
                    $teacher_dimension="维度B";
                }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score>=60 && $record_score<=90){
                    $teacher_dimension="维度C";
                }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score<=90){
                    $teacher_dimension="维度C候选";
                }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score>=60 && $record_score<=90){
                    $teacher_dimension="维度D";
                }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score<=90){
                    $teacher_dimension="维度D候选";
                }else{
                    $teacher_dimension="其他";
                }
                $this->t_test_lesson_subject_sub_list->field_update_list($item["lessonid"],[
                   "teacher_dimension" =>$teacher_dimension
                ]);

            }
        }
        return $this->output_succ(["data"=> $list]);
    }

    public function get_suc_order_lesson_info(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");

        $adminid = $this->get_in_int_val("adminid",-1);
        $list = $this->t_test_lesson_subject_require->get_jw_order_lesson_info($start_time,$end_time,$adminid);


        foreach($list as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eseller_student_status::set_item_value_str($item,"test_lesson_student_status");
            if(empty($item["teacher_dimension"])){
                $tea_in = $this->t_teacher_info->field_get_list($item["teacherid"],"test_transfor_per,identity,month_stu_num");
                $record_score = $this->t_teacher_record_list->get_teacher_first_record_score($item["teacherid"]);
                if($tea_in["test_transfor_per"]>=20){
                    $teacher_dimension="维度A";
                }elseif($tea_in["test_transfor_per"]>=10 && $tea_in["test_transfor_per"]<20){
                    $teacher_dimension="维度B";
                }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score>=60 && $record_score<=90){
                    $teacher_dimension="维度C";
                }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=4 && $record_score<=90){
                    $teacher_dimension="维度C候选";
                }elseif($tea_in["test_transfor_per"]<10 && in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score>=60 && $record_score<=90){
                    $teacher_dimension="维度D";
                }elseif($tea_in["test_transfor_per"]<10 && !in_array($tea_in["identity"],[5,6]) && $tea_in["month_stu_num"]>=1 && $tea_in["month_stu_num"]<=3 && $record_score<=90){
                    $teacher_dimension="维度D候选";
                }else{
                    $teacher_dimension="其他";
                }
                $this->t_test_lesson_subject_sub_list->field_update_list($item["lessonid"],[
                    "teacher_dimension" =>$teacher_dimension
                ]);

            }
        }
        return $this->output_succ(["data"=> $list]);

    }

    public function push_share_knowledge(){
        $account_role = $this->get_account_role();
        $adminid = $this->get_account_id();
        $url = "http://admin.leo1v1.com/";
        $wx_openid = $this->t_manager_info->get_wx_openid($adminid);
        if(!$wx_openid){
            //return $this->output_err("请绑定微信");
        }
        $ret = $this->t_manager_info->send_wx_todo_msg_by_adminid (
                1101,
                "分析知识库",
                "",
                "",
                $url);
        return $this->output_succ(['data'=>"推送成功"]);
    }
    public function web_page_info_add () {
        $title= trim($this->get_in_str_val("title"));
        $url= trim($this->get_in_str_val("url"));
        $this->t_web_page_info->row_insert([
            "url" =>$url,
            "title" =>$title,
            "add_time" => time(NULL),
            "add_adminid" =>  $this->get_account_id(),
        ]);
        return $this->output_succ();
    }

    public function web_page_info_edit () {
        $title= trim($this->get_in_str_val("title"));
        $web_page_id= $this->get_in_int_val("web_page_id");
        $del_flag= $this->get_in_int_val("del_flag");
        $this->t_web_page_info->field_update_list($web_page_id,[
            "title"    => $title,
            "del_flag" => $del_flag,
        ]);
        return $this->output_succ();
    }
    public  function web_page_info_send_admin ()   {
        $web_page_id= $this->get_in_int_val("web_page_id");
        $userid_list_str= $this->get_in_str_val("userid_list");
        $userid_list=\App\Helper\Utils::json_decode_as_int_array($userid_list_str);
        if ( count($userid_list) ==0 ) {
            return $this->output_err("还没选择例子");
        }
        $web_page_info= $this->t_web_page_info->field_get_list($web_page_id,"*");
        $url=$web_page_info["url"];
        $title=$web_page_info["title"];
        $send_url="";


        foreach($userid_list as $adminid ) {
            if (preg_match("/\?/", $url ) ){
                $send_url="$url&web_page_id=$web_page_id&from_adminid=$adminid";
            }else {
                $send_url="$url?web_page_id=$web_page_id&from_adminid=$adminid";
            }

            $this->t_manager_info->send_wx_todo_msg_by_adminid(
                $adminid,
                "系统推送 分享",
                "点击分享",
                "分享:$title",
                $send_url,
                "点击进入 分享到朋友圈 "
            );
        }

        return $this->output_succ();

    }

    public  function web_page_info_send_admin_new ()   {
        $adminid         = $this->get_in_int_val("adminid");
        $web_page_id_str = $this->get_in_str_val("web_page_id_str");

        if ( $adminid ==0 ) {
            return $this->output_err("还没选择例子");
        }
        if ( strlen($web_page_id_str) ==0 ) {
            return $this->output_err("信息有误，发送失败！");
        }

        $web_list = $this->t_web_page_info->get_web_info($web_page_id_str);

        foreach($web_list as $web_page_info ) {

            $url      = $web_page_info["url"];
            $title    = $web_page_info["title"];
            $send_url = "";
            if (preg_match("/\?/", $url ) ){
                $send_url = "$url&web_page_id={$web_page_info['web_page_id']}&from_adminid=$adminid";
            }else {
                $send_url = "$url?web_page_id={$web_page_info['web_page_id']}&from_adminid=$adminid";
            }

            $this->t_manager_info->send_wx_todo_msg_by_adminid(
                $adminid,
                "系统推送 分享",
                "点击分享",
                "分享:$title",
                $send_url,
                "点击进入 分享到朋友圈 "
            );
        }

        return $this->output_succ();

    }


    public function delete_permission_by_uid(){
        $adminid= $this->get_in_int_val("adminid");
        $this->test_jack_new($adminid);
        return $this->output_succ();
    }

    public function change_permission_by_uid_new(){
        $adminid= $this->get_in_int_val("adminid");
        $info = $this->t_manager_info->field_get_list($adminid,"permission,permission_backup");
        $this->t_manager_info->field_update_list($adminid,[
            "permission"  =>$info["permission_backup"],
            "permission_backup"=>$info["permission"]
        ]);
        return $this->output_succ();
    }

    public function get_ass_revisit_info_detail(){
        $userid= $this->get_in_int_val("userid");
        $start_time= $this->get_in_str_val("start_time");
        $account= $this->get_in_str_val("account");
        $month_start = strtotime($start_time);
        $month_end = strtotime(date("Y-m-01",$month_start+40*86400));
        $cur_start = $month_start+15*86400;
        $cur_end =  $month_end;
        $last_start = $month_start;
        $last_end =  $month_start+15*86400;
        $ass_assign_time= $this->t_student_info->get_ass_assign_time($userid);
        if($ass_assign_time < ($cur_start-86400)){
            $first_need=1;
            $second_need=1;
        }elseif($ass_assign_time>=($cur_start-86400) && $ass_assign_time<($cur_end-86400)){
            $first_need=0;
            $second_need=1;
        }else{
            $first_need=0;
            $second_need=0;
        }
        $second_real = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$cur_start,$cur_end,$account);
        $first_real = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$last_start,$last_end,$account);

        return $this->output_succ([
            "first_need"=>$first_need==1?"是":"否",
            "second_need"=>$second_need==1?"是":"否",
            "second_real"=>$second_real==1?"是":"否",
            "first_real"=>$first_real==1?"是":"否",
        ]);
        // $cur_time_str  = date("m.d",$cur_start)."-".date("m.d",$cur_end-300);
        // $last_time_str = date("m.d",$last_start)."-".date("m.d",$last_end-300);

    }


    //获取审核用标签
    public function get_teacher_tag_info(){

        $list = $this->get_teacher_tag_list();

        return $this->output_succ(["data"=>$list]);
    }

    //教务设置老师标签
    public function set_teacher_tag_info(){
        $teacherid                        = $this->get_in_int_val("teacherid",0);
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");
        $tea_tag_arr=[
            "style_character"=>$style_character,
            "professional_ability"=>$professional_ability,
            "classroom_atmosphere"=>$classroom_atmosphere,
            "courseware_requirements"=>$courseware_requirements,
            "diathesis_cultivation"=>$diathesis_cultivation,
        ];
        $set_flag=2;
        $this->set_teacher_label_new($teacherid,0,"",$tea_tag_arr,1000,$set_flag);
        return $this->output_succ();



    }


    public function get_tq_info(){
        $adminid=$this->get_in_adminid();
        $phone = $this->get_in_int_val('phone',-1);
        $ret_info = $this->t_tq_call_info->get_time_by_phone_adminid($adminid,$phone);
        $time = time()-2*3600;
        if($ret_info['is_called_phone'] == 1 &&  $ret_info['duration'] >= 60  && $ret_info['end_time'] > $time ){
            return 1;
        }else{
            return 0;
        }
    }

    public function check_phone_status(){
        $phone = $this->get_in_int_val('phone',-1);
        $ret_info = $this->t_seller_student_new->get_last_revisit_time_by_phone($phone);
        $time = time();
        if($time - $ret_info > 86400){
            return 1;
        }else{
            return 0;
        }
    }

    public function get_require_info_by_id(){
        $require_id = $this->get_in_int_val('require_id',-1);
        $data = $this->t_test_lesson_subject_require->get_require_list_by_requireid($require_id);
        $data["gender_str"] = E\Egender::get_desc(@$data["gender"]);
        $data["subject_str"] = E\Esubject::get_desc(@$data["subject"]);
        $data["grade_str"] = E\Egrade::get_desc(@$data["grade"]);
        $data["intention_level_str"] = E\Eintention_level::get_desc(@$data["intention_level"]);
        $data["quotation_reaction_str"] = E\Equotation_reaction::get_desc(@$data["quotation_reaction"]);
        $data["require_time"] = @$data["curl_stu_request_test_lesson_time"]?date("Y-m-d H:i",$data["curl_stu_request_test_lesson_time"]):"无";
        return $this->output_succ(["data"=>$data]);



    }


    public function get_attendance_lesson_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $time = $this->get_in_int_val("time");
        $flag = $this->get_in_int_val("flag");
        if($flag==1){
            $start_time = $time;
            $end_time = $time+86400;
        }elseif($flag==2){
            $end_time = $time;
            $day_time = $end_time-86400;
            $festival_info = $this->t_festival_info->get_festival_info_by_end_time($day_time);
            $start_time = @$festival_info["begin_time"];
        }
        if(empty($start_time)){
            return $this->output_err("无数据!");
        }

        $lesson_info = $this->t_lesson_info_b2->get_qz_tea_lesson_info($time,$time+86400,$teacherid);
        foreach($lesson_info as &$item){
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
            $item["lesson_end_str"] = date("Y-m-d H:i:s",$item["lesson_end"]);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

            if($item["lesson_type"]==2){
                $item["lesson_count"] = 1.5;
                $item["lesson_type_str"]="试听";
            }elseif(in_array($item["lesson_type"],[0,1,3])){
                $item["lesson_count"]= $item["lesson_count"]/100;
                $item["lesson_type_str"]="常规";
            }else{
                $item["lesson_count"]= $item["lesson_count"]/100;
                $item["lesson_type_str"]="其他";

            }

        }
        return $this->output_succ(["data"=>$lesson_info]);


    }

    public function set_teacher_identity(){
        $teacherid = $this->get_in_int_val("teacherid");
        $identity = $this->get_in_int_val("identity");
        $realname = $this->get_in_str_val("realname");
        $wx_openid = $this->get_in_str_val("wx_openid");
        $this->t_teacher_info->field_update_list($teacherid,[
            "identity" =>$identity,
            "realname" =>$realname,
            "wx_openid"=>$wx_openid
        ]);
        return $this->output_succ();

    }

    //检查转介绍人是否存在竞赛合同
    public function check_origin_user_order_type(){
        $orderid = $this->get_in_int_val("orderid");
        $userid = $this->t_order_info->get_userid($orderid);
        $origin_userid=$this->t_student_info->get_origin_userid($userid);
        if(!$origin_userid){
            return $this->output_err("没有找到对应的转介绍人");
        }
        $competition_flag = $this->t_order_info->check_is_have_competition_order($origin_userid);
        return $this->output_succ(["flag"=>$competition_flag]);
    }

    //检查转介绍负责人以及转介绍人类型
    public function check_origin_assistantid_info(){
        $origin_userid = $this->get_in_int_val("origin_userid");
        $origin_assistantid = $this->get_in_int_val("origin_assistantid");
        $account_role = $this->t_manager_info->get_account_role($origin_assistantid);

        //原CC
        $admin_revisiterid= $this->t_order_info-> get_last_seller_by_userid($origin_userid);
        $cc_flag = 0;
        if($admin_revisiterid>0){
            $del_flag = $this->t_manager_info->get_del_flag($admin_revisiterid);
            if($del_flag==0){
                $cc_flag=1;
            }
        }
        return $this->output_succ([
            "account_role"=>$account_role,
            "cc_flag"     =>$cc_flag
        ]);




    }

    public function get_assistant_warning_info(){
        $ass_adminid = $this->get_account_id();
        $now = time();
        $three = $now - 86400*7;
        $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count($ass_adminid, $three,-1);
        $warning_type_num = [
            'warning_type_one' =>0,
            'warning_type_two' =>0,
            'warning_type_three' =>0
        ];
        foreach($warning_count as $item){
            \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        }

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        $start_time    = strtotime($this->get_in_str_val("start_time"));
        $end_time      = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        // dd($opt_date_type);
        if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;

        }else{
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $cur_end = strtotime(date('Y-m-01',$cur_start+40*86400));
        }
        $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count($ass_adminid, -1, $cur_start, $cur_end);
        $warning_type_num['warning_type_three'] = $three_count;


        //月回访信息
        $month_list = $this->t_revisit_assess_info->get_month_assess_info_by_uid($ass_adminid, $cur_start, $cur_end);
        $month_info = @$month_list[0];
        $month_info["call_num"]= \App\Helper\Common::get_time_format_minute(@$month_info["call_num"]);
        //当天回访信息
        $start_time = strtotime( "today" );
        $end_time   = strtotime("tomorrow");
        $today_info = $this->t_manager_info->get_today_assess_info_by_uid($ass_adminid, $start_time, $end_time);
        $call_num   = $this->t_revisit_call_count->get_today_call_count($ass_adminid, $start_time, $end_time);
        $today_info["call_num"]= \App\Helper\Common::get_time_format_minute($call_num);
        $today_info['goal'] = ceil(@$today_info['stu_num']/10);
        return $this->output_succ([
            "warning"      => @$warning_type_num,
            "month_info"   => @$month_info,
            "today_info"   => @$today_info,
        ]);

    }

    public function get_child_orderid_list(){
        $parent_orderid = $this->get_in_int_val("parent_orderid");
        $target_type = $this->get_in_int_val("target_type",1);
        if($target_type==1){
            $list = $this->t_child_order_info-> get_all_child_order_info($parent_orderid);
        }elseif($target_type==2){
            $userid = $this->t_order_info->get_userid($parent_orderid);
            $list = $this->t_child_order_info->get_other_child_order_list($parent_orderid,$userid);

        }
        foreach($list as &$item){
            if($item["child_order_type"]==0){
                $item["child_order_type_str"]="默认";
            }elseif($item["child_order_type"]==1){
                $item["child_order_type_str"]="首付款";
            }elseif($item["child_order_type"]==2){
                $item["child_order_type_str"]="分期";
            }elseif($item["child_order_type"]==3){
                $item["child_order_type_str"]="其他";
            }

            if($item["pay_status"]==0){
                $item["pay_status_str"]="未付款";
            }elseif($item["pay_status"]==1){
                $item["pay_status_str"]="已付款";
            }

            if($item["child_order_type"]==2){
                $item["period_num_info"] = $item["period_num"]."期";
            }else{
                $item["period_num_info"] ="";
            }

            $userid = $this->t_order_info->get_userid($item["parent_orderid"]);
            $parentid= $this->t_student_info->get_parentid($userid);
            $parent_name = $this->t_parent_info->get_nick($parentid);
            if(empty($item["parent_name"])){
                $item["parent_name"] = $parent_name;
            }
            \App\Helper\Utils::unixtime2date_for_item($item, "pay_time","_str");



        }
        return $this->output_succ(["data"=>$list]);

    }

    public function set_child_orderid_transfer(){
        $child_orderid_list = $this->get_in_str_val("child_orderid_list");
        $target_orderid_list = $this->get_in_str_val("target_orderid_list");
        $arr=[];
        $target_arr = explode(',',$target_orderid_list);
        $target_price =$target_orderid=0;
        $target_orderid_list = [];
        foreach($target_arr as $val){
            $ret = $this->t_child_order_info->field_get_list($val,"parent_orderid,price");
            $parent_orderid = $ret["parent_orderid"];
            if(!isset($arr[$parent_orderid])){
                $arr[$parent_orderid] = $parent_orderid;
            }
            $target_price += $ret["price"];
            $target_orderid = $parent_orderid;
            $target_orderid_list[]=$val;
        }
        if(count($arr)>1){
            return $this->output_err("目标合同不能出自两个以上的父合同");
        }

        $origin_orderid = $origin_price=0;
        $origin_arr= explode(',',$child_orderid_list);
        $origin_orderid_list = [];
        foreach($origin_arr as $val){
            $ret = $this->t_child_order_info->field_get_list($val,"parent_orderid,price");
            $parent_orderid = $ret["parent_orderid"];

            $origin_price += $ret["price"];
            $origin_orderid = $parent_orderid;
            $origin_orderid_list[]=$val;
        }
        if($target_price != $origin_price){
            return $this->output_err("价格需要一致!");
        }
        foreach($target_arr as $val){
            $this->t_child_order_info->field_update_list($val,[
                "parent_orderid" => $origin_orderid
            ]);
        }
        foreach($origin_arr as $val){
            $this->t_child_order_info->field_update_list($val,[
                "parent_orderid" => $target_orderid
            ]);
        }

        //记录日志
        $userid =$this->t_order_info->get_userid($target_orderid);
        $data =[
            "来源合同id" =>$origin_orderid_list,
            "目标合同id" =>$target_orderid_list,
            "操作人"     =>$this->get_account_id().":".$this->get_account()
        ];

        $this->t_student_log->row_insert([
            "userid"     => $userid,
            "log_time"   => time(),
            "type"       => 1,     //类型1,合同修改记录
            "msg"        => json_encode($data)
        ]);
        return $this->output_succ();




    }


    //重置寒假/暑假常规课表
    public function reset_winter_summer_regular_course(){
        $reset_type = $this->get_in_int_val("reset_type");//1,寒假课表重置;2,暑假课表重置
        if($reset_type==1){
            $this->t_winter_week_regular_course->delete_all_info();
            $this->t_winter_week_regular_course->get_detail_info();

        }elseif($reset_type==2){
            $this->t_summer_week_regular_course->delete_all_info();
            $this->t_summer_week_regular_course->get_detail_info();
        }
        return $this->output_succ();
    }


    //计算助教某个学生一个月回访得分
    public function get_ass_stu_month_revisit_data(){
        $userid = $this->get_in_int_val("userid");
        $adminid = $this->get_in_int_val("adminid");
        $type_flag = $this->get_in_int_val("type_flag");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime("+1 months",$start_time);
        $account = $this->t_manager_info->get_account($adminid);
        $month_half = $start_time+15*86400;
        $revisit_value=0;
        $deduct_list=[];

        //检查本月是否上过课
        $month_lesson_flag = $this->t_lesson_info_b3->check_have_lesson_stu($userid,$start_time,$end_time);


        //先计算是否第一次课学员
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        $list = @$ass_month[$adminid];
        $first_lesson_stu_list = $list["first_lesson_stu_list"];
        if($first_lesson_stu_list){
            $first_lesson_stu_arr = json_decode($first_lesson_stu_list,true);
            foreach($first_lesson_stu_arr as $val){
                $first_userid = $val["userid"];
                $lesson_start = $val["lesson_start"];
                if($userid==$val["userid"]){
                    $revisit_end = $lesson_start+86400;

                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$lesson_start,$revisit_end,$account,5);
                    if($revisit_num <=0){
                        $revisit_value +=1;
                        $deduct_list[]=[
                            "deduct_type"=>"第一次课",
                            "subject"    => E\Esubject::get_desc(@$val["subject"]),
                            "time"       => date("Y-m-d H:i",$lesson_start),
                        ];
                    }

                }
            }
        }

        // if($type_flag==1){
        //     $regular_lesson_list = $this->t_lesson_info_b3->get_stu_first_lesson_time_by_subject($userid);
        //     $assign_time = $this->t_student_info->get_ass_assign_time($userid);
        //     $first_lesson_time = @$regular_lesson_list[0]["lesson_start"];
        //     foreach($regular_lesson_list as $t_item){
        //         if($t_item["lesson_start"]>=$start_time && $t_item["lesson_start"]<=$end_time && $t_item["lesson_start"]>$assign_time){
        //             $revisit_end = $t_item["lesson_start"]+86400;

        //             $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$t_item["lesson_start"],$revisit_end,$account,5);
        //             if($revisit_num <=0){
        //                 $revisit_value +=1;
        //             }


        //         }
        //         if($t_item["lesson_start"]<$first_lesson_time){
        //             $first_lesson_time = $t_item["lesson_start"];
        //         }


        //     }



        //     if($first_lesson_time>0 && $first_lesson_time<$month_half){
        //         if($assign_time < $month_half){
        //             $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$start_time,$end_time,$account,-2);
        //             if($revisit_num <2){
        //                 $revisit_value +=1;
        //             }
        //         }elseif($assign_time>=$month_half && $assign_time <$end_time){
        //             $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$month_half,$end_time,$account,-2);
        //             if($revisit_num <1){
        //                 $revisit_value +=1;
        //             }

        //         }
        //     }elseif($first_lesson_time>0 && $first_lesson_time>=$month_half &&  $first_lesson_time<=$end_time){
        //         $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$month_half,$end_time,$account,-2);
        //         if($revisit_num <1){
        //             $revisit_value +=1;
        //         }

        //     }

        // }else
        if($type_flag==2){
            $history_list = $this->t_ass_stu_change_list->get_ass_history_list($adminid,$start_time,$end_time,$userid);

            foreach($history_list as $val){
                $add_time = $val["add_time"];
                if($add_time<$month_half){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$month_half,$account,-2);
                    if($revisit_num <1){
                        $revisit_value +=1;
                        $deduct_list[]=[
                            "deduct_type"=>"常规回访扣分",
                            "subject"    => "",
                            "time"       => "",
                        ];

                    }

                }else{
                    $assign_time = $val["assign_ass_time"];
                    if($assign_time <$month_half){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$end_time,$account,-2);
                        if($month_lesson_flag==1){

                            if($revisit_num <2){
                                $revisit_value +=1*(2-$revisit_num);
                                $deduct_list[]=[
                                    "deduct_type"=>"常规回访扣分",
                                    "subject"    => "",
                                    "time"       => "",
                                ];

                            }
                        }else{
                            if($revisit_num <1){
                                $revisit_value +=1;
                                $deduct_list[]=[
                                    "deduct_type"=>"常规回访扣分",
                                    "subject"    => "",
                                    "time"       => "",
                                ];

                            }

                        }

                    }else{
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_value +=1;
                            $deduct_list[]=[
                                "deduct_type"=>"常规回访扣分",
                                "subject"    => "",
                                "time"       => "",
                            ];

                        }

                    }
                }
                break;


            }

        }elseif($type_flag==3 || $type_flag==1){
            //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
            $first_regular_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($userid);
            $assign_time = $this->t_student_info->get_ass_assign_time($userid);

            if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
                if($assign_time < $month_half){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$start_time,$end_time,$account,-2);
                    if($type_flag==1 && $month_lesson_flag==1){

                        if($revisit_num <2){
                            $revisit_value +=1*(2-$revisit_num);
                            $deduct_list[]=[
                                "deduct_type"=>"常规回访扣分",
                                "subject"    => "",
                                "time"       => "",
                            ];

                        }
                    }else{
                        if($revisit_num <1){
                            $revisit_value +=1;
                            $deduct_list[]=[
                                "deduct_type"=>"常规回访扣分",
                                "subject"    => "",
                                "time"       => "",
                            ];

                        }

                    }
                }elseif($assign_time>=$month_half && $assign_time <$end_time){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$month_half,$end_time,$account,-2);
                    if($revisit_num <1){
                        $revisit_value +=1;
                        $deduct_list[]=[
                            "deduct_type"=>"常规回访扣分",
                            "subject"    => "",
                            "time"       => "",
                        ];

                    }

                }
            }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){
                $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($userid,$month_half,$end_time,$account,-2);
                if($revisit_num <1){
                    $revisit_value +=1;
                    $deduct_list[]=[
                        "deduct_type"=>"常规回访扣分",
                        "subject"    => "",
                        "time"       => "",
                    ];

                }

            }

        }
        return $this->output_succ(["revisit_value"=>$revisit_value,"deduct_list"=>$deduct_list]);


    }

    public function get_ass_performance_seller_week_stu_info(){
        $adminid = $this->get_in_int_val("adminid");
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime("+1 months",$start_time);
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
        $data=[];
        for($i=0;$i<$n;$i++){
            $week = $first_week+$i*7*86400;
            $week_edate = $week+7*86400;
            $week_info = $this->t_ass_weekly_info->get_all_info($week);
            $list = @$week_info[$adminid];
            if($list){
                $num = $list["registered_student_num"];
                $detail_list = $list["registered_student_list"];
                if($detail_list){
                    $arr = json_decode($detail_list,true);
                    $str="";
                    foreach($arr as $v){
                        @$str .= $this->cache_get_student_nick($v).",";
                    }
                    $str = trim(@$str,",");

                }else{
                    $str="";
                }
            }else{
                $num=0;
                $str="";
            }
            $data[]=[
                "time"      => date("Y.m.d", $week)."-".date("Y.m.d", $week_edate-100),
                "num"       => $num,
                "name_list" =>$str
            ];

        }
        return $this->output_succ(["data"=>$data]);



    }

    public function reset_train_lesson_record_info(){
        $id = $this->get_in_int_val("id");
        $record_info = $this->get_in_str_val("record_info");
        $old_record_info = $this->t_teacher_record_list->get_record_info($id);
        $old_free_time = $this->t_teacher_record_list->get_free_time($id);
        $old_arr = json_decode($old_free_time,true);

        $old_arr[]=[
            "old_record_info" =>$old_record_info,
            "new_record_info" =>$record_info,
            "change_time"     =>time(),
            "acc"             =>$this->get_account()
        ];
        $str = json_encode($old_arr);
        $this->t_teacher_record_list->field_update_list($id,[
            "record_info"  =>$record_info,
            "free_time"    =>$str
        ]);



        return $this->output_succ();
    }

    public function get_lesson_opt_detail_info(){
        $lessonid = $this->get_in_int_val("lessonid");
        $userid = $this->get_in_int_val("userid");
        $data = $this->t_lesson_opt_log->get_stu_log($lessonid,$userid);
        foreach($data as &$item){
            if($item["opt_type"]==1){
                $item["opt_type_str"]="登录";
            }elseif($item["opt_type"]==2){
                $item["opt_type_str"]="登出";
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"opt_time","_str");
        }
        return $this->output_succ(["data"=>$data]);

    }

    //模拟试听重审不通过判断
    public function set_no_pass_train_info(){
        $id = $this->get_in_int_val("id");
        $re_submit_list = $this->get_in_str_val("re_submit_list");
        $lecture_out_list = $this->get_in_str_val("lecture_out_list");
        $reason = trim($this->get_in_str_val("reason"));
        $re_submit_arr = !empty($re_submit_list)?json_decode($re_submit_list,true):[];
        $lecture_out_arr=!empty($lecture_out_list)?json_decode($lecture_out_list,true):[];
        $retrial_arr = array_merge($re_submit_arr,$lecture_out_arr);
        $retrial_info = json_encode($retrial_arr);
        $account = $this->get_account();
        $acc= $this->t_teacher_record_list->get_acc($id);
        $account = $this->get_account();
        if($acc != $account && $acc !=""){
            return $this->output_err("您没有权限审核,审核人为".$acc);
        }

        if(!empty($lecture_out_arr)){
            $status=2;
        }else{
            $status=3;
        }
        $this->t_teacher_record_list->field_update_list($id,[
            "record_info" =>$reason,
            "add_time"    =>time(),
            "free_time"   => $retrial_info, //临时存储
            "trial_train_status"=> $status,
            "acc"  =>$account
        ]);
        if( $status==2){

            $keyword2 = "未通过";
        }else{
            $keyword2 = "可重审";
        }
        $teacherid = $this->t_teacher_record_list->get_teacherid($id);
        $teacher_info  = $this->t_teacher_info->get_teacher_info($teacherid);
        // $teacher_info['wx_openid']= "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
        if($teacher_info['wx_openid']!=""){
            /**
             * 模板ID : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
             * 标题   : 评估结果通知
             * {{first.DATA}}
             * 评估内容：{{keyword1.DATA}}
             * 评估结果：{{keyword2.DATA}}
             * 时间：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
            $data['first']    = "老师您好，很抱歉您没有通过模拟试听，希望您再接再厉。";
            $data['keyword1'] = $reason;
            $data['keyword2'] = $keyword2;
            $data['keyword3'] = date("Y-m-d H:i:s");
            $data['remark'] = "请重新提交模拟试听时间，理优教育致力于打造高水平的教学服务团队，期待您能通过下次模拟试听，加油！";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$url);
            //\App\Helper\Utils::send_teacher_msg_for_wx("oJ_4fxLZ3twmoTAadSSXDGsKFNk8",$template_id,$data,$url);
        }
        $ret = $this->add_trial_train_lesson($teacher_info,1,2);

        return $this->output_succ();
    }

    public function update_advance_data_for_test(){
        $start_time   = $this->get_in_int_val("start_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $lesson_count  = $this->get_in_int_val("lesson_count");
        $stu_num  = $this->get_in_int_val("stu_num");
        $cc_order_num  = $this->get_in_int_val("cc_order_num");
        $cr_order_num  = $this->get_in_int_val("cr_order_num");
        $record_avg_score  = $this->get_in_str_val("record_avg_score");
        $level  = $this->get_in_int_val("level");
        $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
            "lesson_count"     => $lesson_count ,
            "cc_order_num"     => $cc_order_num,
            "other_order_num"  => $cr_order_num,
            "stu_num"          => $stu_num,
            "record_score_avg" => $record_avg_score,
            "level_before"            => $level
        ]);
        $this->t_teacher_info->field_update_list($teacherid,["level"=>$level]);
        return $this->output_succ();

    }

    //获取学生所有的信息(个人信息,上课信息等)
    public function get_student_deatil_lesson_info(){
        $userid    = $this->get_in_int_val("userid");
        $data= $this->t_student_info->field_get_list($userid,"face,nick,realname");
        $first_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($userid);
        if($first_lesson_time>0){
            $study_day = ceil((time()-$first_lesson_time)/86400);
        }else{
            $study_day=0;
        }
        $lesson_all = $this->t_lesson_info_b3->get_student_all_lesson_count_list($userid);
        $hour = @$lesson_all["lesson_count"]/100*1.5;
        $data["str1"] = "已经在理优学习了".$study_day."天，完成了".@$lesson_all["lesson_num"]."课次，".(@$lesson_all["lesson_count"]/100)."课时，总计学习了".$hour."小时";
        $data["str2"] = "学习课".@$lesson_all["subject_num"]."门课，".@$lesson_all["tea_num"]."位老师为你服务";

        $lesson_detail = $this->t_lesson_info_b3->get_student_all_lesson_info($userid,0,0);
        $cw_num=$pre_num=$tea_commit=$leave_num=$absence_num=$commit_num=$a_num=$b_num=$c_num=$d_num=$score_total=$check_num=$stu_praise=0;
        foreach($lesson_detail as $val){

            if(empty($val["tea_cw_upload_time"]) || $val["tea_cw_upload_time"]>$val["lesson_start"]){
            }else{
                $cw_num++;
                if($val["preview_status"]>0){
                    $pre_num++;
                }
            }
            if($val["stu_performance"]){
                $tea_commit++;
            }
            if($val["lesson_cancel_reason_type"]==11){
                $leave_num++;
            }elseif($val["lesson_cancel_reason_type"]==20){
                $absence_num++;
            }

            if($val["work_status"]>=2){
                $commit_num++;
            }

            if($val["work_status"]>=3){
                $score =$val["score"];
                $check_num++;
                if($score=="A"){
                    $score_total +=90;
                    $a_num++;
                }elseif($score=="B"){
                    $score_total +=80;
                    $b_num++;
                }elseif($score=="C"){
                    $score_total +=70;
                    $c_num++;
                }else{
                    $score_total +=50;
                    $d_num++;
                }

            }
            $stu_praise +=$val["stu_praise"];

        }
        $pre_rate = $cw_num==0?0:round($pre_num/$cw_num*100,2);
        $score_avg = $check_num==0?"0":($score_total/$check_num);
        if($score_avg>=86){
            $score_final = "A";
        }elseif($score_avg>=75){
            $score_final = "B";
        }elseif($score_avg>=60){
            $score_final = "C";
        }elseif($score_avg>0){
            $score_final = "D";
        }else{
            $score_final = "无";
        }
        $data["str3"] = "预习了".$pre_num."次，预习率为".$pre_rate."%，请假了".$leave_num."次，旷课了".$absence_num."次，获赞". $stu_praise."个，得到老师评价".$tea_commit."次";
        $data["str4"] = "提交了".$commit_num."次作业，获得成绩A".$a_num."次，B".$b_num."次，C".$c_num."次，D".$d_num."次，平均成绩为".$score_final;





        return $this->output_succ(["data"=>$data]);
    }


}