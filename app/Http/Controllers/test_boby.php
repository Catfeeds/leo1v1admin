<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail;
class test_boby extends Controller
{
    use CacheNick;

    public function get_b_txt($file_name="b"){
        $info = file_get_contents("/home/boby/".$file_name.".txt");
        $arr  = explode("\n",$info);
        return $arr;
    }

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

    //七月份 同一ip的不同签单的家长电话
    public function get_id_info(){
        if ( !$this->get_in_str_val("boby")) {
                exit; 
            }
        $start_time = strtotime('2017-07-01');
        $end_time  = strtotime('2017-08-01');
        $ret_info  = $this->t_order_info->get_order_group_by_id($start_time, $end_time);
        $list  = $this->t_order_info->get_order_group_by_id(1, time());
        // $list = $this->t_order_info->get_phont_by_ip();
        // dd($list);
        $newarr = [];
        foreach ($list as $v){
            if ( @!$newarr[$v['ip']] ){
                $newarr[$v['ip']] = $v['phone'];
            } else {
                $newarr[$v['ip']] = $newarr[$v['ip']].';'.$v['phone'];
            }
        }
        $s = '<table border=1><tr><th>ip</th><th>电话</th><th>电话N</th></tr>';
        foreach ($ret_info as $v) {
            $s = $s."<tr><td>{$v['ip']}</td><td>{$v['phone']}</td><td>";
            $new = str_replace($v['phone'], '', $newarr[$v['ip']]);
            $s = $s."{$new}</td></tr>";
        }
        $s = $s.'</table>';
        return $s;
        dd($ret_info);
    }

    public function get_origin_rate(){
        $n = $this->get_in_int_val('yue');
        $start_time = strtotime("2017-0".$n."-01");
        $m = $n+3;
        $end_time = strtotime("2017-0".$m."-01");
        $ret_info = $this->t_student_info->get_stu_origin_rate($start_time, $end_time);
        // $this->cache_get_assistant_nick($id)
        $list = [1=>0,2=>0,3=>0,4=>0,7=>0,80=>0];
        foreach ($ret_info as $v) {
            $list[$v['count']] += 1;
        }
        // dd($list);
        echo '<pre>';
        var_dump($list);
        $list = [1=>0,2=>0,3=>0,4=>0,7=>0,80=>0];
        foreach ($ret_info as $v) {
            $list[$v['count']] = $list[$v['count']] +$v['succ'];
        }
        echo '<pre>';
        var_dump($list);

        dd($ret_info);
    }
    public function get_money_origin_rate(){
        $n = $this->get_in_int_val('yue');
        $start_time = strtotime("2017-0".$n."-01");
        $m = $n+1;
        $end_time = strtotime("2017-0".$m."-01");
        $ret_info = $this->t_student_info->get_stu_money_rate($start_time, $end_time);
        dd($ret_info);
    }

    public function get_info(){
        $sql =  'select distinct tq.phone,tq.uid,tq.start_time,tq.end_time,tq.is_called_phone, m.account,t.seller_student_status from db_weiyi.t_agent a left join db_weiyi.t_student_info s on s.userid = a.userid left join db_weiyi_admin.t_tq_call_info tq on a.phone=tq.phone left join db_weiyi_admin.t_manager_info m on tq.uid=m.tquin left join db_weiyi.t_seller_student_new n on n.phone= tq.phone left join db_weiyi.t_test_lesson_subject t on t.userid= s.userid where a.type=1 and tq.start_time>1501516800 and a.create_time>1501516800 order by a.phone ';
        $ret_info = $this->t_manager_info->get_some_info($sql);
        $s = '<table border=1><tr><td>电话</td><td>uid</td><td>拨打时间</td><td>是否接通</td><td>拨打者</td><td>状态</td></tr>';
        foreach ($ret_info as $item) {
            $s = $s."<tr><td>".$item['phone']."</td><td>".$item['uid']."</td><td>"
                .date('Y-m-d H:i:s',$item['start_time'])."</td><td>".$item['is_called_phone']."</td><td>"
                .$item['account']."</td><td>".$item['seller_student_status']."</td></tr>";
        }
        $s = $s.'</table>';
        return $s;
    }

    //通过订单号查询老师，科目
    public function get_teacher_subject_by_orderid(){
        // $arr = $this->get_b_txt();
        // $orderid_str = "(".join(",",$arr)."0)";
        exit;
        $ret_info = $this->t_order_info->get_teacherid_subject_by_orderid($orderid_str);

        $s = '<table border=1><tr><td>orderid</td><td>id-姓名/科目';
        $par =  '';
        foreach ($ret_info as &$item) {
            E\Esubject::set_item_value_str($item);
            if ($par != $item['orderid']) {
                $s = $s."</td></tr><tr><td>".$item['orderid']."</td><td>"
                      .$item['teacherid']."-".$item['nick']."-".$item['subject_str'];
            } else {
                $s = $s."/".$item['teacherid']."-".$item['nick']."-".$item['subject_str'];
            }
            $par = $item['orderid'];
        }
        $s = $s.'</td></tr></table>';
        return $s;

        dd($ret_info);
    }

    //刷新userid对应的电话拨打信息
    public function reset_flag(){
        // $arr=[];
        // foreach ($arr as $v) {
        //     $this->t_seller_student_new->reset_sys_invaild_flag($v);
        // }
        return 1;
    }

    //公开课6.7.8月，报名数，年级，科目
    public function get_open_lesson_info(){
        $start_time = strtotime('2017-06-01');
        $end_time = strtotime('2017-09-01');
        $ret_info = $this->t_lesson_info_b2->get_open_lesson_info($start_time, $end_time);

        $s = '<table border=1><tr><td>科目</td><td>人数</td><td>上课人数</td><td>年级</td><td>公开课id</td></tr>';
        foreach ($ret_info as &$item) {
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            $s = $s."<tr><td>".$item['subject_str']."</td><td>".$item['num']."</td><td>{$item['cur_num']}</td><td>"
                .$item['grade_str']."</td><td>".$item['lessonid']."</td></tr>";
        }
        $s = $s.'</table>';
        return $s;
    }

}
