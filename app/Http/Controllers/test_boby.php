<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail;
class test_boby extends Controller
{
    use CacheNick;

    public function __construct(){
      $this->switch_tongji_database();
    }

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
        list($start_time,$end_time) = $this->get_in_date_range(-365, 0 );
        $page_info = $this->get_in_page_info();
        $orderid = $this->get_in_str_val("orderid");
        $nick_phone = $this->get_in_int_val("nick_phone",'');
        $account_role = $this->get_in_el_account_role();
        $this->get_in_int_val("account_role"); //没什么作用?
        $ret_info = $this->t_manager_info->get_list_test($page_info, $nick_phone, $account_role, $start_time, $end_time);
        // $idstr = $this->get_in_str_val("idstr");
        // $ret_info = $this->t_manager_info->get_tea_sub_list_by_orderid($idstr);
        // // dd($ret_info);
        // $s = '<table border=1><tr><td>id</td><td>ss';
        // $id =  0;
        // foreach( $ret_info as &$item ) {
        //     E\Esubject::set_item_value_str($item);
        //     if ($id == $item['orderid']) {
        //         $s = $s.",{$item['nick']}/{$item['subject_str']}";
        //     } else {
        //         $s = $s."</td></tr><tr><td>{$item['orderid']}</td><td>{$item['nick']}/{$item['subject_str']}";
        //     }
        //     $id = $item['orderid'];
        // }
        // $s = $s."</td></tr></table>";
        // return $s;
        // dd($ret_info);
        return $this->pageView( __METHOD__, $ret_info);
    }

    public function ajax() {
        $phone = $this->get_in_str_val('phone');
        $arr = [1313,34645132,3546312,645413,6846312,64513268,635312686,31684,641,87,987,61,35,4,876,46416,49,8464,68,74964,6];
        $newArr = [];
        foreach($arr as $k) {
            if( strpos($k, $phone) !== false ) {
                $newArr[] = $k;
            }
        }
        return json_encode($newArr);
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

        $s = '<table border=1><tr><td>科目</td><td>人数</td><td>上课人数</td><td>时间</td><td>年级</td><td>公开课id</td></tr>';
        foreach ($ret_info as &$item) {
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            $s = $s."<tr><td>".$item['subject_str']."</td><td>".$item['num']."</td><td>{$item['cur_num']}</td><td>{$item['lesson_start']}</td><td>"
                .$item['grade_str']."</td><td>".$item['lessonid']."</td></tr>";
        }
        $s = $s.'</table>';
        return $s;
    }


    //7-8月份签单学生，电话，地址和与之相关的销售或者tmk信息
    public function get_acc_tmk_by_order(){
        return 'bey';
        $day = $this->get_in_str_val('day','today');
        $start_time = strtotime($day);
        $end_time = time();
        $ret_info = $this->t_order_info->get_order_stu_acc_info($start_time, $end_time);

        // dd($ret_info);
        $s = '<table border=1><tr>'
           .'<td>orderid</td>'
           .'<td>签单人</td>'
           .'<td>渠道</td>'
           .'<td>金额</td>'
           .'<td>电话</td>'
           .'<td>城市</td>'
           .'<td>下单时间</td>'
           .'<td>拨打者</td>'
           .'<td>角色</td>'
           .'<td>拨打时间</td>'
           .'<td>是否打通(0:否；1：是)</td>'
           .'</tr>';

        foreach ($ret_info as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            $ret = $this->t_tq_call_info->get_acc_role($item['phone']);
            foreach( $ret as &$val ) {
                \App\Helper\Utils::unixtime2date_for_item($val,"start_time");
                $this->cache_set_item_account_nick($val);
                E\Eaccount_role::set_item_value_str($val,"admin_role");
                // dd($item['orderid']);
                $s = $s.'<tr><td>'.$item["orderid"].'</td>'
                   .'<td>'.$item["sys_operator"].'</td>'
                   .'<td>'.$item["origin"].'</td>'
                   .'<td>'.$item["price"].'</td>'
                   .'<td>'.$item["phone"].'</td>'
                   .'<td>'.$item["phone_location"].'</td>'
                   .'<td>'.$item["order_time"].'</td>'
                   .'<td>'.$val["admin_nick"].'</td>'
                   .'<td>'.$val["admin_role_str"].'</td>'
                   .'<td>'.$val["start_time"].'</td>'
                   .'<td>'.$val["is_called_phone"].'</td>'
                   .'</tr>';
            }

        }

        $s = $s.'</table>';
        return $s;

    }

    //获取某个月在读学生，上课-堂数——人数
    public function get_lesson_student_by_month(){

        // return 'bey';
        $start_time = strtotime ( $this->get_in_str_val('start') );
        $end_time = strtotime ( $this->get_in_str_val('end') );

        $s = '<table border=1><tr>'
           .'<td>科目</td>'
           .'<td>课次</td>'
           .'<td>人数</td>'
           .'</tr>';
        for ($sub=0; $sub < 12; $sub++) {
            // $sub = 2;
            $ret_info = $this->t_lesson_info_b2->get_lesson_student_count_info($start_time, $end_time,$sub);
            if ($ret_info) {
                $list = [];
                $subject = '';
                // dd($ret_info);
                foreach ($ret_info as &$item) {
                    if ( !array_key_exists($item['lesson_nums'], $list) ){
                        $list[ $item['lesson_nums'] ] = 1;
                    } else {
                        $list[ $item['lesson_nums'] ] = $list[ $item['lesson_nums'] ] +1;
                    }
                    E\Esubject::set_item_value_str($item);
                    $subject = $item['subject_str'];
                }
                // dd($list);
                // dd($subject);

                foreach ($list as $k=>$v){
                    $s = $s.'<tr><td>'.$subject.'</td>'
                                    .'<td>'.$k.'</td>'
                                    .'<td>'.$v.'</td>'
                                    .'</tr>';
                }
            }

        }

        $s = $s.'</table>';
        return $s;

    }

    //添加给老师添加公开课学生
    public function add_stu_to_tea_open_lesson(){
        return 'bey';
        // $start_time = strtotime('2017-08-05');
        // $end_time = strtotime('2017-09-01');
        // $userid_list = $this->t_order_info->get_userid_by_pay_time($start_time, $end_time);

        // $teacherid = "(180795)";
        // $start_time = strtotime('2017-09-01');
        // $end_time = strtotime('2017-10-01');
        $lessonid_list = ['318458','318459','318460','318461','318462'];
        // $lessonid_list = $this->t_lesson_info_b2->get_lessonid_by_teacherid($start_time, $end_time, $teacherid);
        // foreach ($lessonid_list as $v) {
        //     $this->t_open_lesson_user->delete_open_lesson_by_lessonid( $v );
        // }
        echo 'ok';
        exit;
        $g100 = [];
        $g200 = [];
        $g300 = [];
        foreach ($lessonid_list as $v){
            if ($v['grade'] < 200) {
                $g100[] = $v['lessonid'];
            } else if ($v['grade'] < 300) {
                $g200[] = $v['lessonid'];
            }else {
                $g300[] = $v['lessonid'];
            }
        }
        foreach ($userid_list as $item) {
            if ($item['grade'] > 0) {
                if ($item['grade'] < 200 ) {
                    foreach ($g100 as $lessonid) {
                        $this->t_open_lesson_user->add_open_class_user($lessonid, $item['userid']);
                    }
                } else if ($item['grade'] < 300 ) {
                    foreach ($g200 as $lessonid) {
                        $this->t_open_lesson_user->add_open_class_user($lessonid, $item['userid']);
                    }
                } else {
                    foreach ($g300 as $lessonid) {
                        $this->t_open_lesson_user->add_open_class_user($lessonid, $item['userid']);
                    }
                }
            }
        }

        echo 'ok';
        exit;
    }

    public function get_teacher(){

        exit;
        $s = '<table border=1><tr>'
           .'<td>id</td>'
           .'<td>名字</td>'
           .'<td>tel</td>'
           .'<td>科目</td>'
           .'<td>上课时间</td>'
           .'</tr>';

        foreach ($ret as &$item) {
            E\Esubject::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
                $s = $s.'<tr><td>'.$item["userid"].'</td>'
                   .'<td>'.$item["nick"].'</td>'
                   .'<td>'.$item["phone"].'</td>'
                   .'<td>'.$item["subject_str"].'</td>'
                   .'<td>'.$item["lesson_start"].'</td>'
                   .'</tr>';
        }

        $s = $s.'</table>';
        return $s;

    }

    public function get_role(){
        return false;
        $arr = $this->get_b_txt();
        $s = '<table border=1><tr>'
           .'<td>电话</td>'
           .'<td>老师</td>'
           .'<td>员工</td>'
           .'<td>学生</td>'
           .'<td></td>'
           .'</tr>';
        $admin = [];
        foreach ($arr as $k) {
            $sql = "select teacherid from db_weiyi.t_teacher_info where phone='{$k}'";
            $ret = $this->t_teacher_info->is_teacher($sql);
            if ($ret) {
                $s = $s."<tr><td>{$k}</td><td>老师</td><td></td><td></td><td></td></tr>";
            }else {

                $sql = "select create_time from db_weiyi_admin.t_manager_info where phone='{$k}'";
                $ret = $this->t_teacher_info->is_teacher($sql);
                if ($ret) {
                    $s = $s."<tr><td>{$k}</td><td></td><td>员工</td><td></td><td></td></tr>";
                }else {

                    $sql = "select userid from db_weiyi.t_student_info where phone='{$k}'";
                    $ret = $this->t_teacher_info->is_teacher($sql);
                    if ($ret) {
                        $s = $s."<tr><td>{$k}</td><td></td><td></td><td>学生</td><td></td></tr>";
                    }else {
                        $s = $s."<tr><td>{$k}</td><td></td><td></td><td></td><td>其他</td></tr>";
                    }
                }
            }
        }



        $s = $s.'</table>';
        return $s;
    }

    public function ex_seven_days(){
        $sessionId   = session()->getId();
        $sessionName = session()->getName();
        setcookie($sessionId,$sessionName, time()+3600*24*7);//有效期7天
    }

    public function get_test_succ_count(){
        $arr = [5,6,7,8];
        echo "月份｜年级|课数|成功数";
        echo "<br>";

        foreach ($arr as $v) {
            $month = $v;
            // $month = $this->get_in_int_val("month",1);
            $start_time = strtotime("2017-$month");
            $end_time = strtotime("+1 month",$start_time);
            $list = $this->t_lesson_info_b3->get_test_succ_count($start_time,$end_time);
            $new = [];
            foreach ($list as $v) {
                if ($v['grade'] < 200) {
                    $new[1]['grade'] = '小学';
                    $new[1]['num'] = @$new[1]['num']+1;
                    $new[1]['succ'] = @$new[1]['succ'] + $v['succ'];
                } else if($v['grade'] < 300) {
                    $new[2]['grade'] = '初中';
                    $new[2]['num'] = @$new[2]['num']+1;
                    $new[2]['succ'] = @$new[2]['succ'] + $v['succ'];
                } else if ($v['grade'] <400){
                    $new[3]['grade'] = '高中';
                    $new[3]['num'] = @$new[3]['num']+1;
                    $new[3]['succ'] = @$new[3]['succ'] + $v['succ'];
                } else if ($v['grade'] <500){
                    $new[4]['grade'] = '大学';
                    $new[4]['num'] = @$new[4]['num']+1;
                    $new[4]['succ'] = @$new[4]['succ'] + $v['succ'];
                }

            }
            foreach($new as $v){
                echo $month."|".$v['grade']."|".$v['num']."|".$v['succ'];
                echo "<br>";
            }
        }
    }

    public function get_tea_succ_count(){
        // $arr = [5,6,7,8];
        // echo "月份｜老师｜科目|年级|试听课数|成功数|课耗";
        // echo "<br>";
        echo '<table border=1> <tr><td>月</td><td>老师</td><td>科目</td><td>年级</td><td>试听课数</td><td>成功数</td><td>常规课耗</td></tr>';
        // echo '<table border=1> <tr><td>月</td><td>老师</td><td>科目</td><td>年级</td><td>试听课数</td><td>成功数</td></tr>';
        $month      = $this->get_in_int_val("month",1);
        $start_time = strtotime("2017-$month");
        $end_time   = strtotime("+1 month",$start_time);
        $list       = $this->t_lesson_info_b3->get_tea_succ_count($start_time,$end_time);

        foreach ($list as $k=>$v){
            $nick = $this->cache_get_teacher_nick($v['teacherid']);
            $subject = explode(',',$v['group_concat(distinct(l.subject))']);
            $grade = explode(',',$v['group_concat(distinct(l.grade))']);
            $sstr = '';
            $gstr = '';
            foreach($subject as $s){
               $sstr = $sstr.','. E\Esubject::$desc_map[$s];
            }
            foreach($grade as $s){
                $gstr = $gstr.','. E\Egrade::$desc_map[$s];
            }

            $kehao = $this->t_lesson_info_b3->get_tea_count($v['teacherid'],$start_time,$end_time);
            echo '<tr><td>'.$month.'</td><td>'
                           .$nick.'</td><td>'
                           .$sstr .'</td><td>'
                           .$gstr.'</td><td>'
                           .$v["trial_num"].'</td><td>'
                           .$v["trial_succ"].'</td><td>'
                           .$kehao/100 .'</td></tr>';
                           // .$v["trial_succ"].'</td></tr>';

        }
    }


}
