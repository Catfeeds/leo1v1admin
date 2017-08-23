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
        // dd($orderid_str);
        $orderid_str = "(20307,20286,20244,20227,20217,20078,19968,19981,19652,19622,19766,20331,20075,19953,19904,19783,19748,19630,19524,19574,20143,19908,19635,20099,20079,19888,19838,19813,19727,19715,19695,19694,19640,19600,19502,20048,19497,20209,20150,19870,19861,19773,19664,20263,19799,19791,19752,19733,19722,19702,19661,19631,19621,19616,19607,19568,19550,19549,19493,20038,20035,20033,19781,19772,19770,19767,19557,19527,20023,19720,19717,19555,20045,19918,19846,19845,19620,19938,19833,19735,19732,19699,19679,19672,19665,19641,19623,19611,19561,19542,19811,19592,20043,19999,19963,19937,19886,19867,19828,19816,19776,19712,19690,19658,19644,19609,19608,19599,19582,19564,19525,19662,19537,20355,20319,20293,20119,20076,20030,19864,19812,19808,19782,19759,19719,19707,19696,19678,19675,19670,19624,20072,20004,19841,19775,19769,19750,19729,19728,19701,19682,19673,19648,19642,19626,19588,19538,19535,19494,20279,19736,19541,19526,19511,19484,20311,20273,19879,19859,19849,19842,19834,19831,19825,19822,19818,19817,19815,19806,19795,19785,19778,19760,19756,19746,19741,19711,19706,19698,19692,19684,19683,19671,19656,19650,19647,19637,19634,19633,19597,19595,19594,19567,19536,19534,19522,19512,19509,19500,19496,19495,20333,20267,20243,20236,20142,20133,20053,20039,20034,20008,20000,19995,19961,19955,19943,19934,19923,19897,19896,19865,19852,19839,19837,19826,19801,19780,19762,19710,19614,19575,19490,20296,20295,20240,20239,20230,20214,20103,20093,20091,20054,20042,20018,20014,19989,19950,19939,19925,19914,19913,19901,19890,19655,19514,19510,20327,20317,20304,20290,20289,20283,20241,20219,20218,20210,20116,20112,20108,20106,20097,20070,20047,20032,20025,20002,20001,19996,19959,19957,19948,19947,19942,19941,19935,19931,19927,19924,19916,19910,19902,19900,19892,19883,19860,19835,19832,19792,19619,20361,20342,20337,20323,20302,20291,20374,20393,20391,20275,20261,20248,20247,20224,20216,20130,20129,20120,20102,20095,20085,20081,20071,20049,20036,20026,20024,20020,20019,20006,19979,19977,19970,19965,19962,19956,19946,19920,19885,19810,19589,19558,20343,20320,20284,20135,20128,20127,20122,20118,20113,20088,20082,19668,19539,20349,20335,20278,20126,20092,19823,19798,19721,19583,20359,20282,20272,20266,20255,20229,20123,20083,19805,19734,19610,19591,19545,19516,20345,20306,20041)";
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


}
