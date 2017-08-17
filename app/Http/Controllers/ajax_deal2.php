<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ajax_deal2 extends Controller
{
    use CacheNick;
    use TeaPower;

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

    //JIM
    public function office_cmd_add () {
        $office_device_type = $this->get_in_e_office_device_type();
        $device_opt_type    = $this->get_in_e_device_opt_type();
        $device_id    = $this->get_in_int_val("device_id");
        \App\Helper\office_cmd::add_one($office_device_type,$device_id,$device_opt_type);
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

            $this->t_manager_info->send_wx_todo_msg( "李子璇","来自:$account" , "TMK 有效:$phone"  );
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
        $orderid   = $this->get_in_int_val("orderid");
        $row       = $this->t_order_info->field_get_list($orderid,"*");
        $type_1_lesson_count=$this->t_order_info->get_type1_lesson_count ($orderid)/100;
        $userid    = $row["userid"];
        $username           = $this->t_student_info->get_nick($userid);
        $grade               = $row["grade"];
        $lesson_count        = $row["lesson_total"] * $row["default_lesson_count"]/100;
        $price               = $row["price"]/100;
        $competition_flag = $row["competition_flag"];
        $one_lesson_count    = $row["lesson_weeks"] ;
        $per_lesson_interval = $row["lesson_duration"] ;
        $order_start_time    = $row["contract_starttime"];
        $order_end_time      = $row["contract_endtime"];
        if (($lesson_count) <=90 ) {
            $order_end_time =$order_start_time+365*86400;
        } else if (($lesson_count) <=270 ) {
            $order_end_time =$order_start_time+365*86400*2;
        } else  {
            $order_end_time =$order_start_time+365*86400*3;
        }

        if(!$one_lesson_count    ){ $one_lesson_count= 3; }
        if(!$per_lesson_interval ){ $per_lesson_interval = 40; }
        $now=time(NULL);

        $pdf_file_url=\App\Helper\Common::gen_order_pdf($orderid,$username,$grade,$competition_flag,$lesson_count,$price,$one_lesson_count,$per_lesson_interval,$order_start_time,$order_end_time,true, $now ,$type_1_lesson_count);
        \App\Helper\Utils::logger("pdf_file_url:$pdf_file_url");

        $pdf_file_url=\App\Helper\Common::gen_order_pdf($orderid,$username,$grade,$competition_flag,$lesson_count,$price,$one_lesson_count,$per_lesson_interval,$order_start_time,$order_end_time,false,$now , $type_1_lesson_count);

        \App\Helper\Utils::logger("pdf_file_url:$pdf_file_url");
        $this->t_order_info->field_update_list($orderid,[
            "pdf_url" =>$pdf_file_url
        ]);
        return $this->output_succ(["pdf_file_url" => $pdf_file_url] );
    }

    /**
     * @author    sam
     * @function  更新学生考试成绩信息
     */
    public function score_edit(){
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
        $create_adminid   =  $this->get_account_id();
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
            'status'        =>   $status
         ];

        $ret = $this->t_student_score_info->field_update_list($id,$data);
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
        $score            = $this->get_in_int_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
        $semester         = $this->get_in_int_val("semester");
        $total_score      = $this->get_in_int_val("total_score");
        $grade            = $this->get_in_int_val("grade");
        $grade_rank       = $this->get_in_str_val("grade_rank");


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
    *@author   sam
    *@function 创建学生和家长账号
    *@path     authority/manager_list
    */
    public function register_student_parent_account()
    {
        $account = $this->get_in_str_val("account");
        $phone   = $this->get_in_int_val("phone");
        $ret=[];
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
                $ret['success'] =  "注册家长账号成功";
                $this->t_parent_child->set_student_parent($ret_parent,$ret_student);
            }
        }else if($ret_student == 0 && $ret_parent == 0){
            $ret_student = $this->t_student_info->register($phone,md5("123456"),0,101,0,$account,"后台");
            $ret_parent    = $this->t_parent_info->register($phone,md5("123456"),0,0,$account);
            if($ret_student && $ret_parent){
                $ret['success'] =  "注册学生账号和家长账号成功";
                $this->t_parent_child->set_student_parent($ret_parent,$ret_student);
            }
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
        $this->t_config_date->set_config_value($config_date_type,$opt_time,$value);

        return $this->output_succ();
    }

}
