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
        $score            = $this->get_in_int_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
        $semester         = $this->get_in_int_val("semester");
        $total_score      = $this->get_in_int_val("total_score");
        $grade            = $this->get_in_int_val("grade");
        $grade_rank       = $this->get_in_str_val("grade_rank");
        $rank_arr = explode("/",$grade_rank);
        $rank_now = $rank_arr[0];
        $grade_rank_last = $this->t_student_score_info->get_last_grade_rank($subject,$userid);
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
        $ret_teacher = $this->t_teacher_info->check_teacher_phone($phone);
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

        if(!$ret_teacher){
            $teacher_info['phone']         = $phone;
            $teacher_info['tea_nick']      = $account;
            $teacher_info['send_sms_flag'] = 0;
            $teacher_info['wx_use_flag']   = 0;
            $teacher_info['use_easy_pass'] = 2;
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

    public function set_teacher_train_through_info(){
        $phone           = $this->get_in_str_val('phone');
        $adminid = $this->get_in_int_val("adminid");
        $create_time = $this->t_manager_info->get_create_time($adminid);
        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $this->t_teacher_info->field_update_list($teacherid,[
            "train_through_new"   =>1,
            "train_through_new_time"=>$create_time
        ]);
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
            if ($title_info[0] != "key1" ) {
                return $this->output_err("文件格式不对!");
            }
            $origin_arr=[];
            $key1="";
            $key2="";
            $key3="";
            $key4="";
            $value="";
            $fix="";
            foreach ($arr as $index => $item) {
                $key1= trim($item[0])? trim($item[0]): $key1 ;
                $key2= trim($item[1])? trim($item[1]): $key2 ;
                $key3= trim($item[2])? trim($item[2]): $key3 ;
                $key4= trim($item[3])? trim($item[3]): $key4 ;
                $value= trim($item[4]);
                $fix= trim($item[5])? trim($item[5]): $fix;

                $fix_value=$fix.$value;
                $origin_arr[]=$fix_value;
                $this->t_origin_key->row_insert([
                    "create_time" => $now,
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
        $userid = $this->get_in_userid();
        $adminid=$this->get_account_id();
        $seller_level=$this->t_manager_info->get_seller_level($adminid);


        $user_test_lesson_list= $this->t_lesson_info_b2->get_test_lesson_count_by_userid($userid,0, -1 );
        $user_test_lesson_count = count( $user_test_lesson_list) ;
        if ( $user_test_lesson_count >5 ) {
            return $this->output_err("已经 $user_test_lesson_count 次试听了，超过5次，不可试听");
        }

        $test_lesson_list= $this->t_seller_student_new->get_test_lesson_list($adminid);

        $test_lesson_count= count( $test_lesson_list);
        $seller_hold_test_lesson_user_count_config= \App\Helper\Config::get_config("seller_hold_test_lesson_user_count");
        $cur_test_lesson_count_max= @$seller_hold_test_lesson_user_count_config[$seller_level];

        \App\Helper\Utils::logger("cur_require_count:$test_lesson_count,   cur_require_count_max: $cur_test_lesson_count_max" );



        if ($test_lesson_count > $cur_test_lesson_count_max) {
            return $this->output_err(
                "目前当前已试听数 $test_lesson_count,　超过 $cur_test_lesson_count_max ,不可申请, 请将无效的试听用户回流公海，才能提交 新试听申请 ",
                ["flag" => "goto_test_lesson_list"]);
        }

        return $this->output_succ();
    }
    public function seller_test_lesson_list( ) {
        $this->get_account_id();

        //$this->t_seller_student_new->get_
    }
    public function test(){
        sleep(5);
        return  $this->output_succ();
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
        return $this->output_succ(); 
    }

    //删除学生科目教材
    public function delete_user_subject_textbook(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        
        $this->t_student_subject_list->row_delete_2($userid,$subject);
        return $this->output_succ(); 
    }



    //根据学生科目获取教材
    public function get_editionid(){
        $userid    = $this->get_in_int_val("userid");
        $subject    = $this->get_in_int_val("subject");
        $editionid =$this->t_student_subject_list->get_editionid($userid,$subject);
        return $this->output_succ(["editionid"=>$editionid]); 
    }
 

}
