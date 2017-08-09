<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail;
class test_boby extends Controller
{
    use CacheNick;
    public function p_list(){
        $page_info= $this->get_in_page_info();
        $nick_phone= $this->get_in_str_val("nick_phone");
        $account_role= $this->get_in_el_account_role();

        $ret_info=$this->t_manager_info->get_list_test($page_info,$nick_phone);
        return $this->pageView( __METHOD__,$ret_info);
    }
    public function  ss() {

        $page_info= $this->get_in_page_info();
        $nick_phone= $this->get_in_str_val("nick_phone");
        $account_role= $this->get_in_el_account_role();
        $ret_info=$this->t_manager_info->get_list_test($page_info,$nick_phone,$account_role);
        foreach ($ret_info['list'] as &$item ) {
            E\Eaccount_role::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"uid", "unick");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }
        return $this->pageView( __METHOD__,$ret_info);

    }

    public function sf() {
        $page_info = $this->get_in_page_info();
        $nick_phone = $this->get_in_str_val("nick_phone");
        $account_role = $this->get_in_el_account_role();
        $ret_info = $this->t_manager_info->get_list_test($page_info,$nick_phone);
        return $this->pageView( __METHOD__, $ret_info);
    }
    public function st() {
        // list($start_time,$end_time) = $this->get_in_date_range(-365, 0 );
        // $page_info = $this->get_in_page_info();
        // $orderid = $this->get_in_str_val("orderid");
        // $account_role = $this->get_in_el_account_role();
        // $this->get_in_int_val("account_role"); //没什么作用?
        // $ret_info = $this->t_manager_info->get_list_test($page_info, $nick_phone, $account_role, $start_time, $end_time);
        $idstr = $this->get_in_str_val("idstr");
        $ret_info = $this->t_manager_info->get_tea_sub_list_by_orderid($idstr);
        // dd($ret_info);
        $s = '<table border=1><tr><td>id</td><td>ss';
        $id =  0;
        foreach( $ret_info as &$item ) {
            E\Esubject::set_item_value_str($item);
            if ($id == $item['orderid']) {
                $s = $s.",{$item['nick']}/{$item['subject_str']}";
            } else {
                $s = $s."</td></tr><tr><td>{$item['orderid']}</td><td>{$item['nick']}/{$item['subject_str']}";
            }
            $id = $item['orderid'];
        }
        $s = $s."</td></tr></table>";
        return $s;
        dd($ret_info);
        return $this->pageView( __METHOD__, $ret_info);
    }
    public function test_one(){
        $phone    = $this->get_in_phone();
        $origin   = $this->get_in_str_val("origin");
        $grade    = $this->get_in_grade();
        $subject  = $this->get_in_subject();
        $tmk_flag = $this->get_in_int_val("tmk_flag", 0);
        $sql = 'select * from t_teacher_info where teacherid=123';
        if (strlen($phone )!=11) {
            return $this->output_err("电话号码长度不对");
        }

        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        if ($this->t_test_lesson_subject->check_subject($userid,$subject))  {
            return $this->output_err("已经有了这个科目的例子了,不能增加");
        }

        $userid=$this->t_seller_student_new->book_free_lesson_new("",$phone,$grade,$origin,$subject,0);
        if ($tmk_flag){
            \App\Helper\Utils::logger("SET TMK INFO");

            $this->t_seller_student_new->field_update_list($userid,[
                "tmk_assign_time"  => time(NULL) ,
                "tmk_adminid"  => $this->get_account_id(),
                "tmk_join_time"  => time(NULL),
                "tmk_student_status"  => 0,
            ]);
            $account=$this->get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $phone,
                "操作者: 状态:  TMK新增例子  :$account ",
                "system"
            );
        }
        return $this->output_succ();

   }

    public function for_add_news() {
        $title = $this->get_in_str_val("title");
        $type = $this->get_in_int_val("type");
        $des = $this->get_in_str_val("des");
        $pic = $this->get_in_str_val("pic");
        $new_link = $this->get_in_str_val("new_link");
        $adminid = session("adminid");
        $create_time = time();
        $ret_info = $this->t_yxyx_wxnews_info->add_news($title, $des,$pic,$new_link,$adminid,$type);
        dd($ret_info);
    }

    //七月份
    public function get_id_info(){
        $start_time = strtotime('2017-07-01');
        $end_time  = strtotime('2017-08-01');
        $ret_info  = $this->t_order_info->get_order_group_by_id($start_time, $end_time);
        $iparr = [];
        foreach ($ret_info as $v) {
            $iparr[] = $v['ip'];
        }
        // $list = $this->t_user_login_log->get_per_phone_by_ip($iparr);
        $s = '<table border=1><tr><th>ip</th><th>电话</th></tr>';
        foreach ($ret_info as $v) {
            $s = $s."<tr><td>{$v['ip']}</td><td>{$v['phone']}</td></tr>";
        }
        $s = $s.'</table>';
        return $s;
        dd($ret_info);
    }

}
