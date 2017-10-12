<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

use Illuminate\Support\Facades\Mail;
class test_boby extends Controller
{
    use CacheNick;

    public function __construct(){
      // $this->switch_tongji_database();
    }
    public function table_start($th_arr){
        $s   = '<table border=1><tr>';
        foreach ($th_arr as $v) {
            $s = $s."<th>{$v}</th>";
        }
        return $s.'</tr>';
    }

    public function tr_add($table_start){
        $arr = func_get_args();
        $s   = $table_start.'<tr>';
        foreach($arr as $k => $v){
            if($k) {
                $s = $s."<td>{$v}</td>";
            }
        }
        return $s.'</tr>';
    }

    public function table_end($s){
        return $s.'</table>';
    }

    public function test(){
        $th_arr = ['姓名','电话','年龄'];
        $str = $this->table_start($th_arr);
        $str = $this->tr_add($str, 'sdfa',13213,45);
        $str = $this->tr_add($str, 'sdfsf',353513,15);
        $str = $this->tr_add($str, 'aaasf',111113,15);
        $str = $this->tr_add($str, 'asddasf',5511113,15);
        $str = $this->tr_add($str, 'a350sf',22113,15);
        $str = $this->table_end($str);
        return $str;
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
        $tt =  $this->get_in_int_val("account_role"); //没什么作用?
        $ret_info = $this->t_manager_info->get_list_test($page_info, $nick_phone, $account_role, $start_time, $end_time);
        foreach ($ret_info['list'] as &$item ) {
            E\Eaccount_role::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"uid", "unick");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }

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

        $th_arr = ['电话','uid','拨打时间','是否接通','拨打者','状态'];
        $s = $this->table_start($th_arr);
        foreach ($ret_info as $item) {
            $s = $this->tr_add($s, $item['phone'], $item['uid'], date('Y-m-d H:i:s',$item['start_time']), $item['is_called_phone'], $item['account'], $item['seller_student_status']);
        }
        $s = $this->table_end($s);
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
    //公开课6.7.8月，报名数，年级，科目
    public function get_open_lesson_info(){
        $start_time = strtotime('2017-06-01');
        $end_time = strtotime('2017-09-01');
        $ret_info = $this->t_lesson_info_b2->get_open_lesson_info($start_time, $end_time);

        $th_arr = ['科目','人数','上课人数','时间','年级','公开课id'];
        $s = $this->table_start($th_arr);
        foreach ($ret_info as &$item) {
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            $s = $this->tr_add($s,$item['subject_str'], $item['num'], $item['cur_num'], $item['lesson_start'],$item['grade_str'], $item['lessonid']);
        }
        $s = $this->table_end($s);
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
        $th_arr = ['orderid','签单人','渠道' ,'金额' ,'电话' ,'城市' ,'下单时间' ,'拨打者' ,'角色' ,'拨打时间' ,'是否打通(0:否；1：是)'];
        $s = $this->table_start($th_arr);

        foreach ($ret_info as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            $ret = $this->t_tq_call_info->get_acc_role($item['phone']);
            foreach( $ret as &$val ) {
                \App\Helper\Utils::unixtime2date_for_item($val,"start_time");
                $this->cache_set_item_account_nick($val);
                E\Eaccount_role::set_item_value_str($val,"admin_role");
                $s = $this->tr_add($s,$item["orderid"], $item["sys_operator"],$item["origin"],$item["price"],$item["phone"],$item["phone_location"],$item["order_time"],$val["admin_nick"],$val["admin_role_str"],$val["start_time"],$val["is_called_phone"]);
            }

        }

        $s = $this->table_end($s);
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
        $th_arr = ['id','名字','电话','科目','上课时间'];
        $s = $this->table_start($th_arr);
        foreach ($ret as &$item) {
            E\Esubject::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            $s = $this->tr_add($s,$item["userid"],$item["nick"],$item["phone"],$item["subject_str"],$item["lesson_start"]);
        }

        $s = $this->table_end($s);
        return $s;

    }

    public function get_role(){
        return false;
        $arr = $this->get_b_txt();
        $th_arr = ['电话','老师','员工','学生','其他'];
        $s = $this->table_start($th_arr);
        $admin = [];
        foreach ($arr as $k) {
            $sql = "select teacherid from db_weiyi.t_teacher_info where phone='{$k}'";
            $ret = $this->t_teacher_info->is_teacher($sql);
            if ($ret) {
                $s = $this->tr_add($s,$k,'老师','','','');
            }else {

                $sql = "select create_time from db_weiyi_admin.t_manager_info where phone='{$k}'";
                $ret = $this->t_teacher_info->is_teacher($sql);
                if ($ret) {
                    $s = $this->tr_add($s,$k,'','员工','','');
                }else {

                    $sql = "select userid from db_weiyi.t_student_info where phone='{$k}'";
                    $ret = $this->t_teacher_info->is_teacher($sql);
                    if ($ret) {
                        $s = $this->tr_add($s,$k,'','','学生','');
                    }else {
                        $s = $this->tr_add($s,$k,'','','','其他');
                    }
                }
            }
        }

        $s = $this->table_end($s);
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
            $start_time = strtotime("2017-$month");
            $end_time = strtotime("+1 month",$start_time);
            $v = $this->t_lesson_info_b3->get_test_succ_count($start_time,$end_time);
            echo $month."|".'小学'."|".$v['min']."|".$v['min_succ'];
            echo "<br>";
            echo $month."|".'初中'."|".$v['mid']."|".$v['mid_succ'];
            echo "<br>";
            echo $month."|".'高中'."|".$v['heigh']."|".$v['heigh_succ'];
            echo "<br>";
        }
    }

    public function get_tea_succ_count(){
        echo '<table border=1> <tr><td>月</td><td>老师</td><td>科目</td><td>年级</td><td>试听课数</td><td>成功数</td><td>常规课耗</td></tr>';
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
                if($s >=0) {
                    $gstr = $gstr.','. E\Egrade::$desc_map[$s];
                }
            }

            $kehao = $this->t_lesson_info_b3->get_tea_count($v['teacherid'],$start_time,$end_time);
            echo '<tr><td>'.$month.'</td><td>'
                           .$nick.'</td><td>'
                           .$sstr .'</td><td>'
                           .$gstr.'</td><td>'
                           .$v["trial_num"].'</td><td>'
                           .$v["trial_succ"].'</td><td>'
                           .$kehao/100 .'</td></tr>';

        }
    }

    public function update_all_price(){
        $cur_ratio = Config::get_current_ratio();
        $ret_info = $this->t_gift_info->get_gift_id_praise();

        foreach($ret_info as $item){
            $price = $item['current_praise'] * 100 / $cur_ratio;
            $this->t_gift_info->update_all_price( $item['giftid'], $price );
        }
        echo 'ok';
    }

    public function get_info_by_time(){
        $start = $this->get_in_str_val('start',0);
        $end = $this->get_in_str_val('end',0);
        $start_time = strtotime($start);
        $end_time = strtotime($end);

        if($start !=0 ) {

            $sql = " select ss.phone,if(max(tq.start_time) >0,1,0) as call_flag,max(tq.is_called_phone) as call_succ,p.wx_openid,if(max(tsl.lessonid) >0,1,0) as test_flag,max(if (l.lesson_user_online_status=1,1,0)) as test_succ,o.orderid  from t_seller_student_new ss left join db_weiyi_admin.t_tq_call_info tq on tq.phone = ss.phone and tq.admin_role=2 left join t_parent_child pc on pc.userid=ss.userid left join t_parent_info p on p.parentid=pc.parentid left join t_test_lesson_subject tl on tl.userid=ss.userid  left join t_test_lesson_subject_require tr on tr.test_lesson_subject_id =tl.test_lesson_subject_id  left join t_test_lesson_subject_sub_list tsl on tsl.require_id = tr.require_id   left join t_lesson_info l on l.lessonid=tsl.lessonid  left join t_order_info o on o.userid=ss.userid and o.contract_type=0 and o.pay_time>0 and o.contract_status >0  where ss.add_time >= $start_time and ss.add_time < $end_time group by ss.phone";
            $ret_info = $this->t_grab_lesson_link_info->get_info_test($sql);
            $th_arr = ['电话','ｃｃ是否拨打(1是，０，否)','是否打通(0,否；１，是)','是否绑定微信（为空则未绑定）','是否排试听课(0否1是)','试听是否成功（１成功，别的都不成功）','是否签单(有数字就是签单)'];
            $s = $this->table_start($th_arr);

            foreach ($ret_info as $item ) {

                $s = $this->tr_add($s,$item['phone'],$item['call_flag'],$item['call_succ'],$item['wx_openid'],$item['test_flag'], $item['test_succ'], $item['orderid']);
            }

            $s = $this->table_end($s);
            return $s;
        } else {
            return '在浏览器地址栏后面添加"?start=2016-1-1&end=2016-2-1"';
        }

    }

    public function get_ass_stu_num(){
        $ret_info = $this->t_manager_info->get_uid_stu_num();
        $time = time();
        foreach( $ret_info as $item ){
            $this->t_revisit_assess_info->row_insert([
                'uid'     => $item['uid'],
                'stu_num' => $item['stu_num'],
                'revisit_num' => 0,
                'call_count'  => 0,
                'create_time' => $time,
            ]);
        }
    }


}
