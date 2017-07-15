<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ajax_deal2 extends Controller
{
    use CacheNick;

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
            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_student_status"=>$tmk_student_status,
                "tmk_desc"=>$tmk_desc,
            ]);

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


    //$todo_type= E\Etodo_type::V_SELLER_NEXT_CALL;
    //$from_key_int  = $userid;
    //$from_key2_int = $next_revisit_time;
    //            \App\Todo\todo_base::add($todo_type,$next_revisit_time ,$next_revisit_time+7200,$adminid,$from_key_int,$from_key2_int);

    /**
     *@author    sam
     *@function  更新学生考试成绩信息
     *
     */
    public function score_edit(){
        $id               = $this->get_in_int_val("id");
        $userid           = $this->get_in_int_val("userid");
        $subject          = $this->get_in_int_val("subject");
        $stu_score_type   = $this->get_in_int_val("stu_score_type");
        $stu_score_time   = strtotime($this->get_in_str_val("stu_score_time"));
        $score            = $this->get_in_int_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
        $create_adminid   =  $this->get_account_id();

        $id = $this->get_in_int_val('id');
        $data = [
            'subject'       =>   $subject,
            'stu_score_type'=>   $stu_score_type,
            'stu_score_time'=>   $stu_score_time,
            'score'         =>   $score,
            'rank'          =>   $rank,
            'file_url'      =>   $file_url,
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
        $subject          = $this->get_in_int_val("subject");
        $stu_score_type   = $this->get_in_int_val("stu_score_type");
        $stu_score_time   = strtotime($this->get_in_str_val("stu_score_time"));
        $score            = $this->get_in_int_val("score");
        $rank             = $this->get_in_str_val("rank");
        $file_url         = $this->get_in_str_val("file_url");
    $create_adminid   =  $this->get_account_id();

        $ret_info = $this->t_student_score_info->row_insert([
            "userid"                => $userid,
            "create_time"           => $create_time,
            "create_adminid"        => $create_adminid,
            "subject"               => $subject,
            "stu_score_type"        => $stu_score_type,
            "stu_score_time"        => $stu_score_time,
            "score"                 => $score,
            "rank"                  => $rank,
            "file_url"              => $file_url
        ],false,false,true); 
        return $this->output_succ();
    }

    /**
     *@author   sam
     *@function 删除学生考试成绩信息
     */
    public function score_del(){
        $id = $this->get_in_int_val('id');
        $this->t_student_score_info->row_delete($id);
        return $this->output_succ();
    }

    //测试login_log增加
    public function login_log_add(){
        $userid     = $this->get_in_int_val("userid");
        $login      = strtotime($this->get_in_int_val("login"));

        $nick       = $this->get_in_str_val("nick");

        $ip         = $this->get_in_int_val ("ip");
        $role       = $this->get_in_int_val ("role");
        $login_type = $this->get_in_int_val("login_type");
        $flag       = $this->get_in_int_val("flag");
        \App\Helper\Utils::logger("role:$role");

        if ($ip>100)  {
            return $this->output_err("ip出错");
        }
        if ($role>100)  {
            return $this->output_err("出错");
        }

        if ($login_type>100)  {
            return $this->output_err("登录方式出错");
        }

        if ($flag>100)  {
            return $this->output_err("方式出错");
        }


        $ret = $this->t_user_login_log->row_insert([
            "userid"       => $userid,
            "login_time"   => $login,
            "nick"         => $nick,
            "ip"           => $ip,
            "role"         => $role,
            "login_type"   => $login_type,
            "dymanic_flag" => $flag,
        ]);
<<<<<<< HEAD
         return $this->output_succ();
   }

    //测试删除login_id
    public function login_log_del(){
        $id = $this->get_in_id();
        //
        $res = $this->t_user_login_log->row_delete($id);
        if($res){
            return $this->output_succ();
        }else{
            return $this->output_err('login删除失败');
        }
     }

    //测试login_log修改
    public function set_login_log(){
        $id     = $this->get_in_int_val('id');
       
        $userid     = $this->get_in_int_val("userid");
        $login_time = strtotime($this->get_in_int_val("login_time"));

        $nick         = $this->get_in_str_val("nick");
        $ip           = $this->get_in_int_val("ip");
        $role       = $this->get_in_int_val("role");
        $login_type   = $this->get_in_int_val("login_type");
        $dymanic_flag = $this->get_in_int_val("dymanic_flag");

       
        if ($ip>100)  {
            return $this->output_err("ip出错");
        }
        if ($role>100)  {
            return $this->output_err("出错");
        }

        if ($login_type>100)  {
            return $this->output_err("登录方式出错");
        }

        if ($dymanic_flag>100)  {
            return $this->output_err("方式出错");
        }


        $arr = [];
        $arr['userid'] = $userid;
        $arr['login_time'] = $login_time;
        $arr['nick'] = $nick;
        $arr['ip'] = $ip;
        $arr['role'] = $role;
        $arr['login_type'] = $login_type;
        $arr['dymanic_flag'] = $dymanic_flag;
        $ret = $this->t_user_login_log->field_update_list($id,$arr);
        return $this->output_succ();
    }


=======
        return $this->output_succ();
   }
    public function query_sql_data(){
        $sql=$this->get_in_str_val("sql");
        //$db_name= 
>>>>>>> a5b0316527a9b63b390e9e90237b3a5d10100361

    }
}
