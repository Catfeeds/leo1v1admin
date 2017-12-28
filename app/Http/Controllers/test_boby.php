<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Hash;

include(app_path("Wx/Yxyx/lanewechat_yxyx.php"));

use Illuminate\Support\Facades\Mail;
class test_boby extends Controller
{
    use CacheNick;

    public function __construct(){
//      $this->switch_tongji_database();
    }

    public function send_msg_to_tea_wx(){
        return 1;
        //boby oJ_4fxDrbnuMZnQ6HmPIjmUdRxVM
        $tea_list = [[
        'wx_openid' => 'oJ_4fxDrbnuMZnQ6HmPIjmUdRxVM',
        'grade_start' => 1,
        'subject' => 1,
        'grade_part_ex' =>0
        ]];
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */

        foreach ($tea_list as $item) {
            $html = $this->get_new_qq_group_html($item['grade_start'],$item['grade_part_ex'],$item['subject']);

            $wx_openid = $item['wx_openid'];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = "老师您好，为了给大家提供更优质的服务，现对教研、排课及答疑群按学段和科目进行分类重建，旧群已作废。";
            $data['keyword1'] = "加入相关QQ群";
            $data['keyword2'] = $html;
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "";
            // $url = "http://www.leo1v1.com/login/teacher";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        return 'ok';
    }

    public function test_wx(){
        $info['teacherid'] = 438630;

        $info['wx_openid'] = 'oJ_4fxDrbnuMZnQ6HmPIjmUdRxVM';
        // \App\Helper\WxSendMsg::template_tea_simulation_tip($wx);
        // \App\Helper\WxSendMsg::template_tea_simulation_tip($wx,false);
        // \App\Helper\WxSendMsg::template_tea_offer_tip($info, '五星教师');
        \App\Helper\WxSendMsg::template_tea_first_test_tip($info['wx_openid'], 1);
        \App\Helper\WxSendMsg::template_tea_first_test_tip($info['wx_openid'], 2);
        \App\Helper\WxSendMsg::template_tea_first_test_tip($info['wx_openid'], 3);
    }

    public function table_start($th_arr){
        $s = '<table border=1><tr>';
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

    public function get_b_txt($file_name="b"){
       $info = file_get_contents("/home/boby/".$file_name.".txt");
       $arr  = explode("\n",$info);
       return $arr;
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
        $start_time = strtotime('2017-07-01');
        $end_time   = strtotime('2017-08-01');
        $ret_info   = $this->t_order_info->get_order_group_by_id($start_time, $end_time);
        $list       = $this->t_order_info->get_order_group_by_id(1, time());

        // $list                  = $this->t_order_info->get_phont_by_ip();
        // dd($list);
        $newarr = [];
        foreach ($list as $v){
            if ( @!$newarr[$v['ip']] ){
                $newarr[$v['ip']] = $v['phone'];
            } else {
                $newarr[$v['ip']] = $newarr[$v['ip']].';'.$v['phone'];
            }
        }
        $s  = '<table border=1><tr><th>ip</th><th>电话</th><th>电话N</th></tr>';
        foreach ($ret_info as $v) {
            $s   = $s."<tr><td>{$v['ip']}</td><td>{$v['phone']}</td><td>";
            $new = str_replace($v['phone'], '', $newarr[$v['ip']]);
            $s   = $s."{$new}</td></tr>";
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
                    $s = $s.'<tr><td>'.$subject.'</td>' .'<td>'.$k.'</td>' .'<td>'.$v.'</td>' .'</tr>';
                }
            }

        }

        $s = $s.'</table>';
        return $s;

    }

    //添加给老师添加公开课学生
    public function add_stu_to_tea_open_lesson(){
        $start_time = strtotime('2017-09-01');
        $end_time = strtotime('2017-10-01');
        $userid_list = $this->t_order_info->get_userid_by_pay_time($start_time, $end_time);

        // $teacherid = "(180795)";
        // $start_time = strtotime('2017-09-01');
        // $end_time = strtotime('2017-10-01');

        // $lessonid_list = [374979,374980,374096,374097,374098,374080,374081,374082,374083,374084,374085,374086,378713,378714];
        $lessonid_list = [374980=>300,374096=>300,374097=>300,374098=>300,374081=>200,374082=>100,374083=>100,374084=>100,374085=>100,374086=>100,378713=>200,378714=>200];
        $jiaoyu_lessonid_list = [318460,318461,371543,371544,371545];
        // $lessonid_list = [378713,378714];//10-24

        // $lessonid_list = [374979,374980,374096,374097,374098,374080,374081,374082,374083,374084,374085,374086,318460,318461,371543,371544,371545,378713,378714];
        // foreach($lessonid_list as $lessonid){
        //     $courseid = $this->t_lesson_info->get_courseid($lessonid);

        //     $this->t_course_order->field_update_list($courseid,[
        //         "packageid"=>0
        //     ]);
        // }
        // return 1;
        // $lessonid_list = $this->t_lesson_info_b2->get_lessonid_by_teacherid($start_time, $end_time, $teacherid);
         // foreach ($lessonid_list as $k=>$v) {
         //     $this->t_open_lesson_user->delete_open_lesson_by_lessonid( $k );
         // }
         // echo 'ok';
         // exit;

        // $g100 = [];
        // $g200 = [];
        // $g300 = [];
        // foreach ($lessonid_list as $v){
        //     if ($v['grade'] < 200) {
        //         $g100[] = $v['lessonid'];
        //     } else if ($v['grade'] < 300) {
        //         $g200[] = $v['lessonid'];
        //     }else {
        //         $g300[] = $v['lessonid'];
        //     }
        // }
        $userid_xiao = [];
        $userid_chu = [];
        $userid_gao = [];
        foreach ($userid_list as $item) {
            if ($item['grade'] > 0) {
                if ($item['grade'] < 200 ) {
                    array_push($userid_xiao,$item);
                } else if ($item['grade'] < 300 ) {
                    array_push($userid_chu,$item);
                } else if ($item['grade'] < 400 )  {
                    array_push($userid_gao,$item);
                }
            }

            // foreach($lessonid_list as $lessonid){
                // $this->t_open_lesson_user->add_open_class_user($lessonid, $item['userid']);
            // }
        }
        // dd($userid_xiao);

        // foreach($lessonid_list as $k=>$v){
        //     if ($v == 100){
        //         $job=(new \App\Jobs\add_lesson_grade_user($userid_xiao, $k))->delay(10);
        //         dispatch($job);
        //     } else if ($v == 200) {
        //         $job=(new \App\Jobs\add_lesson_grade_user($userid_chu, $k))->delay(10);
        //         dispatch($job);
        //     } else {
                $job=(new \App\Jobs\add_lesson_grade_user($userid_gao, $k))->delay(10);
        //         dispatch($job);
        //    }
        // }

        foreach($jiaoyu_lessonid_list as $v){
            $job=(new \App\Jobs\add_lesson_grade_user($userid_list, $v))->delay(10);
            dispatch($job);
        }
        return 'ok';
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

    //@desn:根据当前价格更新赞个数
    public function update_all_praise_count(){
        $cur_ratio = Config::get_current_ratio();
        $ret_info = $this->t_gift_info->get_gift_id_price();

        foreach($ret_info as $item){
            if($item['cost_price']> 0){
                $praise = ceil($item['cost_price']/100*$cur_ratio);
                $this->t_gift_info->field_update_list($item['giftid'],[
                    'current_praise' => $praise,
                ]);
            }
        }
        echo 'ok';
    }

    public function get_info_by_time(){
        $this->switch_tongji_database();
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
                'create_time' => $time,
            ]);
        }
    }

    public function get_revisit_call_info_new(){
        $day = $this->get_in_int_val('day',1);
        $start_time = strtotime( "2017-10-".$day );
        $time = $start_time;
        $end_time   = $start_time+86400;
        //1,先查询当天已近记录的call_phone_id
        $id_str_list = $this->t_revisit_call_count->get_call_phone_id_str($start_time,$end_time);
        $uid_phoneid = [];
        foreach ($id_str_list as $item) {
            if (is_array($item)) {
                $uid_phoneid[$item['uid']] = $item['phoneids'];
            }
        }
        //2,然后查询助教的学情回访    每分钟自动查询
        $ret_info = $this->t_revisit_info->get_revisit_type0_per_minute($start_time, $end_time);

        //3,有学情回访后，在获取当日的其他回访信息
        $th_arr = ['uid','userid','学情回访时间','电话时间（其他回访）','call_phone_id'];
        $s = $this->table_start($th_arr);
        foreach($ret_info as $item) {
            if (is_array($item)){
                $uid = $item['uid'];
                $userid = $item['userid'];
                $revisit_time1 = $item['revisit_time1'];
                $id_str   = @$uid_phoneid[$uid] ? $uid_phoneid[$uid] : 1;
                $ret_list = $this->t_revisit_info->get_revisit_type6_per_minute($start_time, $revisit_time1, $uid, $userid, $id_str);

                foreach($ret_list as $val) {
                    if (is_array($val)){

                        $t1 = date('Y-m-d H:i:s',$item['revisit_time1']);
                        $t2 = date('Y-m-d H:i:s',$val['revisit_time2']);
                        $s = $this->tr_add($s,$uid,$userid,$item['revisit_time1']."<br>".$t1,$val['revisit_time2']."<br>".$t2,$val['call_phone_id']);

                    }
                }
            }
        }

        return $s;
    }

    public function update_revisit_call_info_new(){
        $day = $this->get_in_int_val('day',1);
        $start_time = strtotime( "2017-10-".$day );
        $time = $start_time;
        $end_time   = $start_time+86400;
        //1,先查询当天已近记录的call_phone_id
        $id_str_list = $this->t_revisit_call_count->get_call_phone_id_str($start_time,$end_time);
        $uid_phoneid = [];
        foreach ($id_str_list as $item) {
            if (is_array($item)) {
                $uid_phoneid[$item['uid']] = $item['phoneids'];
            }
        }
        //2,然后查询助教的学情回访    每分钟自动查询
        $ret_info = $this->t_revisit_info->get_revisit_type0_per_minute($start_time, $end_time);

        //3,有学情回访后，在获取当日的其他回访信息
        foreach($ret_info as $item) {
            if (is_array($item)){
                $uid = $item['uid'];
                $userid = $item['userid'];
                $revisit_time1 = $item['revisit_time1'];
                $id_str   = @$uid_phoneid[$uid] ? $uid_phoneid[$uid] : 1;
                $ret_list = $this->t_revisit_info->get_revisit_type6_per_minute($start_time, $revisit_time1, $uid, $userid, $id_str);

                foreach($ret_list as $val) {
                    if (is_array($val)){
                        $this->t_revisit_call_count->row_insert([
                            'uid'           => $uid,
                            'userid'        => $userid,
                            'revisit_time1' => $item['revisit_time1'],
                            'revisit_time2' => $val['revisit_time2'],
                            'call_phone_id' => $val['call_phone_id'],
                            'create_time'   => $time,
                        ]);
                    }
                }
            }
        }

        return 'ok';
    }

    //9.1-10.18期间上过非试听课的所有学生统计
    public function get_stu_num(){
        $sql = "select l.subject,l.userid,l.grade  from db_weiyi.t_lesson_info l force index(lesson_start) left join t_student_info s on s.userid=l.userid where l.lesson_start >=1504195200 and l.lesson_start<1508342400 and l.lesson_user_online_status <>2  and l.lesson_type<>2  and s.is_test_user=0";

        $ret_info = $this->t_grab_lesson_link_info->get_info_test($sql);
        $subject_arr = [1 =>[], 2 =>[], 3 =>[], 4 =>[], 5 =>[], 6 =>[], 7 =>[], 8 =>[], 9 =>[], 10 =>[], 11 =>[]];
        $grade_arr = [101 =>[], 102 =>[], 103 =>[], 104 =>[], 105 =>[], 106 =>[], 201 =>[], 202 =>[], 203 =>[], 301 =>[], 302 =>[], 303 =>[]];
        $sub = array_keys($subject_arr);
        $grade = array_keys($grade_arr);
        $new = [];
        foreach ($grade as $g) {
            $new[$g] = $subject_arr;
        }

        $ret_info = $this->t_grab_lesson_link_info->get_info_test($sql);
        foreach ($ret_info as $item){
            $g = $item['grade'];
            $s = $item['subject'];
            $u = $item['userid'];
            if ( !in_array($u, $new[$g][$s]) ){
                array_push($new[$g][$s], $u );
            }

            if ( !in_array($u, $subject_arr[$s]) ){
                array_push($subject_arr[$s], $u );
            }

            if ( !in_array($u, $grade_arr[$g]) ){
                array_push($grade_arr[$g], $u );
            }
        }

        $th_arr = ['年级','科目','人数'];
        $s = $this->table_start($th_arr);
        foreach($new as $k=>$v){
            foreach ($v as $g=>$n){
                $s= $this->tr_add($s, $k,$g,count($n));
            }
        }
        $s = $this->table_end($s);
        echo $s;

        $th_arr = ['年级','人数'];
        $s = $this->table_start($th_arr);
        foreach($grade_arr as $k=>$v){
            $s= $this->tr_add($s,$k,count($v));
        }
        $s = $this->table_end($s);
        echo $s;

        $th_arr = ['科目','人数'];
        $s = $this->table_start($th_arr);
        foreach($subject_arr as $k=>$v){
            $s= $this->tr_add($s,$k,count($v));
        }
        $s = $this->table_end($s);
        return $s;

    }

    public function test_job(){
        //给老师发送微信推送
        // dispatch( new \App\Jobs\send_wx_to_teacher());
    }

    public function update_data(){
        return 1;
        $day = $this->get_in_str_val('day','2015-01-01');
        $start_time = strtotime( $day );
        $end_time   = strtotime( '+1 month',$start_time );
        $prev_start = strtotime( '-1 month',$start_time );
        $cur_month = [];
        $prev_month = [];
        //本月初付费学员数,订单数
        $all_pay = $this->t_student_info->get_student_list_for_finance_count();
        $cur_month['pay_stu_num'] = $all_pay['userid_count'];
        $cur_month['pay_order_num'] = $all_pay['orderid_count'];

        $user_order_list = $this->t_order_info->get_order_user_list_by_month($start_time);
        $new_user = [];//上月新签

        foreach ( $user_order_list as $item ) {
            if ($item['order_time'] >= $prev_start ){
                $new_user[] = $item['userid'];
                if (!$item['start_time'] && $item['assistantid'] > 0) {//新签订单,未排课,已分配助教
                    @$prev_month['has_ass_num']++;
                } else if (!$item['start_time'] && !$item['assistantid']) {//新签订单,未排课,未分配助教
                    @$prev_month['no_ass_num']++;
                }
            }

        }
        $this->table_start();

        $new_user = array_unique($new_user);
        $prev_month['new_pay_stu_num'] = count($new_user);

        //上月退费名单
        $refund_info = $this->t_order_refund->get_refund_userid_by_month($prev_start,$start_time);
        $prev_month['refund_stu_num'] = $refund_info['userid_count'];
        $prev_month['refund_order_num'] = $refund_info['orderid_count'];
        //上月正常结课学生
        $ret_num = $this->t_student_info->get_user_list_by_lesson_count_new($prev_start,$start_time);
        $prev_month['normal_over_num'] = $ret_num;

        //上月 在读,停课,休学,假期数
        $ret_info = $this->t_student_info->get_student_count_archive();

        foreach($ret_info as $item) {
            if($item['type'] == 0) {
                @$prev_month['study_num']++;
            } else if ($item['type'] == 2) {
                @$prev_month['stop_num']++;
            } else if ($item['type'] == 3) {
                @$prev_month['drop_out_num']++;
            } else if ($item['type'] == 4) {
                @$prev_month['vacation_num']++;
            }
        }

        //上月月续费学员
        $renow_list = $this->t_order_info->get_renow_user_by_month($prev_start,$start_time);
        $renow_user = [];
        foreach ($renow_list as $item) {
            $renow_user[] = $item['userid'];
        }
        //上月预警学员
        $warning_list = $this->t_ass_weekly_info->get_warning_user_by_month($prev_start);
        $warning_renow_num = 0;

        foreach ($warning_list as $item){
            $new = json_decode($item['warning_student_list'], true);
            if(is_array($new)){
                foreach($new as $v) {
                    if( strlen($v)>0){
                        if( in_array($v ,$renow_user) ){
                            $warning_renow_num++;
                        }
                    }
                }
            }
        }
        $prev_month['warning_renow_stu_num']    = $warning_renow_num;
        $prev_month['no_warning_renow_stu_num'] = count($renow_user) - $warning_renow_num;

        //本月预警学员
        $warning_list = $this->t_ass_weekly_info->get_warning_user_by_month($start_time);
        $warning_stu_num = 0;
        foreach ($warning_list as $item){
            $new = json_decode($item['warning_student_list'], true);
            if(is_array($new)){
                foreach($new as $v) {
                    if( strlen($v)>0){
                        $warning_stu_num++;
                    }
                }
            }
        }

        $cur_month['warning_stu_num']    = $warning_stu_num;
        //上月课耗和上月课耗收入
        $lesson_money = $this->t_lesson_info_b3->get_lesson_count_money_info_by_month($prev_start,$start_time);
        $prev_month['lesson_count']       = $lesson_money['lesson_count'];
        $prev_month['lesson_count_money'] = $lesson_money['lesson_count_money'];
        $prev_month['lesson_stu_num']     = $lesson_money['lesson_stu_num'];

        $cur_month['create_time'] = $start_time;

        $id = $this->t_month_student_count->get_id_by_create_time($prev_start);
        $this->t_month_student_count->field_update_list($id,$prev_month);
        $this->t_month_student_count->row_insert($cur_month);

        return 'ok';
    }

    //获取所有试听课排课时间,科目,年级
    public function get_test_lesson_info(){
        $sql = " select set_lesson_time,l.subject,l.grade  from t_test_lesson_subject_sub_list tl left join t_lesson_info l on l.lessonid=tl.lessonid where l.lesson_del_flag=0 and l.lesson_user_online_status <>2";

        $th_arr = ['年级','科目','人数'];
        $s = $this->table_start($th_arr);
        $ret_info = $this->t_grab_lesson_link_info->get_info_test($sql);
        $zu = [];
        foreach ($ret_info as $item){
            $sub = E\Esubject::get_desc($item['subject']);
            $gra = E\Egrade::get_desc($item['grade']);
            $tim = \App\Helper\Utils::unixtime2date($item["set_lesson_time"]);
            $b = $this->fenzu( $item['set_lesson_time'] );
            @$zu[$b]++;
            $s= $this->tr_add($s, $gra,$sub,$tim);
        }

        $s = $this->table_end($s);
        echo $s;
        $all =0;
        foreach($zu as $k=>$v){
            $all = $all+$v;
            echo $k,'--',$v,'<br>';
        }
        echo $all;

    }

    public function fenzu($time){
        $ling = strtotime(date('Y-m-d',$time));
        $a = $time - $ling;
        $b = floor($a / 1800);
        return $b;
    }

    public function add_user_to_lesson(){
        $old_lessonid = '371545';
        $new_lessonid = '398579';
        $userid = $this->t_open_lesson_user->get_all_user($old_lessonid);
        dd($userid);
        foreach($userid as $v){
            $this->t_open_lesson_user->add_open_class_user($new_lessonid,$v['userid']);
        }
    }

    public function get_tea_free_info(){
        $time = strtotime('2017-08-01');
        $end = strtotime('2017-11-01');
        // $sql = " select t.subject,t.grade,t.teacherid  from t_teacher_info t left join t_lesson_info l on l.teacherid=t.teacherid where t.trial_lecture_is_pass=1 and t.is_quit=0 and t.train_through_new_time<$time and t.is_test_user=0 and l.lessonid is null and l.lesson_start>$time and l.lesson_start<$end group by t.teacherid";

        $sql1 = 'select t.subject,t.grade,t.teacherid  from t_teacher_info t  where t.trial_lecture_is_pass=1  and t.train_through_new_time<1501516800 and t.is_test_user=0 ';
        $sql2 = 'select teacherid  from  t_lesson_info l  where  l.lesson_start>1501516800 and l.lesson_start<1509465600  group by teacherid';
        $th_arr = ['年级科目','人数'];
        $s = $this->table_start($th_arr);
        $ret_info1 = $this->t_grab_lesson_link_info->get_info_test($sql1);
        $ret_info2 = $this->t_grab_lesson_link_info->get_info_test($sql2);
        $n=[];
        foreach ($ret_info2 as $k) {
            $n[] = $k['teacherid'];
        }
        $zu = [];
        foreach ($ret_info1 as $item){
            if (!in_array($item['teacherid'], $n)){
                $sub = E\Esubject::get_desc($item['subject']);
                $gra = E\Egrade::get_desc($item['grade']);
                $key = $gra.$sub;
                @$zu[$key]++;
            }
        }

        foreach($zu as $k=>$v){
            $s= $this->tr_add($s, $k,$v);
        }

        $s = $this->table_end($s);
        return $s;
    }

    public function test_img(){
        $wx_openid = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $phone = 18898881852;
        $agent = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $agent['wx_openid'] = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $agent['phone'] = 18898881852;
        $agent['id'] = 45;
        //$phone = uniqid();
        $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";

        $request = ['fromusername'=>'tset'];

        // \App\Helper\Utils::wx_make_and_send_img($bg_url,$qr_code_url,$request,$agent,1);
        $task=new \App\Jobs\make_and_send_wx_img($wx_openid,$bg_url,$qr_code_url,$request,$agent);
        $task->handle();

    }

    public function update_adminid_yxyx(){

        return 1;
        //刷张龙的优学优享例子
        $sql = 'select ss.phone,t.userid,t. test_lesson_subject_id ,t.require_adminid,ss.admin_revisiterid,auto_allot_adminid,ss.add_time   from t_seller_student_new ss left join t_test_lesson_subject t on t.userid=ss.userid where auto_allot_adminid>0 and auto_allot_adminid !=384 and ss.add_time>=1510416000 and ss.add_time<1510502400 order by add_time limit 15';
        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);
        foreach ($ret as $v ) {
            if ($v['require_adminid'] == 0) {
                $this->t_seller_student_new->auto_allot_yxyx_userid(412, '张植源', $v['userid'], '系统',$v['phone']);
                $this->t_test_lesson_subject->field_update_list($v['test_lesson_subject_id'],['require_adminid' => 412]);
            }
        }
    }

    public function update_adminid_yxyx2(){
        return 1;
        $sql = 'select ss.phone,t.userid,t. test_lesson_subject_id ,t.require_adminid,ss.admin_revisiterid,auto_allot_adminid,ss.add_time   from t_seller_student_new ss left join t_test_lesson_subject t on t.userid=ss.userid where auto_allot_adminid>0 and auto_allot_adminid =795 order by add_time ';
        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);
        foreach ($ret as $v ) {
            if ($v['require_adminid'] == 0) {
                $this->t_seller_student_new->auto_allot_yxyx_userid(759, '邵少鹏', $v['userid'], '系统',$v['phone']);
            }
        }
    }

    public function get_some_user_info(){
        $this->switch_tongji_database();
        $start = strtotime('2017-8-1');
        $end = strtotime('2017-9-1');

        $sql = "select ss.add_time,s.phone,s.nick,count(distinct tq.uid) cc,s.origin, "
             ." count(distinct if( tq.is_called_phone=1,tq.uid,0)) ok_phone,"
             ." min( if( tq.is_called_phone=1,tq.uid,0) ) flag"
             ." from db_weiyi.t_seller_student_new ss "
             ." left join db_weiyi_admin.t_tq_call_info tq on tq.phone=ss.phone "
             ." left join db_weiyi.t_student_info s on s.userid=ss.userid "
             ." left join t_test_lesson_subject tl on tl.userid=ss.userid "
             ." left join t_test_lesson_subject_require ts on ts.test_lesson_subject_id=tl.test_lesson_subject_id "
             ." left join t_test_lesson_subject_sub_list tss on tss.require_id=ts.require_id "
             ." left join t_lesson_info l on l.lessonid=tss.lessonid "
             ." where ss.add_time>=$start and ss.add_time<$end and s.is_test_user=0 and l.lesson_user_online_status<>1"
             ." group by s.phone having cc<=2";

        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);
        $th_arr = ['手机','姓名','进入日期','联系次数（ｃｃ人数）','渠道','未拨通人数'];
        $s = $this->table_start($th_arr);

        foreach($ret as $v){
            if( $v['flag']==0 ) {//说明有未打通的
                $num = $v['cc']-$v['ok_phone']+1;
            }else {
                $num = $v['cc']-$v['ok_phone'];
            }
            $s= $this->tr_add($s, $v['phone'], $v['nick'],date('Y-m-d',$v['add_time']), $v['cc'], $v['origin'],$num);

        }
        $s = $this->table_end($s);

        return $s;

    }
    //1-7
    public function get_xiaoxue_lesson_info(){
        //统计小学上课课堂时间段星期分布

        $this->switch_tongji_database();
        $start = strtotime('2017-9-1');
        $end = strtotime('2017-10-1');

        $sql = "select lesson_start,lesson_type from t_lesson_info l left join t_student_info s on s.userid=l.userid where l.grade<200 and lesson_start>=$start and lesson_start<$end and s.is_test_user=0 and l.lesson_del_flag=0 and l.lesson_type in (0,2,3)";
        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);
        $week = [
            'free'=>[0=>[],1=>[], 2=>[], 3=>[], 4=>[], 5=>[], 6=>[]],
            'no'=>[0=>[],1=>[], 2=>[], 3=>[], 4=>[], 5=>[], 6=>[]],
        ];
        foreach($ret as $v){
            if($v['lesson_type'] == 2){
                $a='free';
            }else {
                $a='no';
            }
            $w = date('w',$v['lesson_start']);
            $h = $this->fenzu( $v['lesson_start'] );
            @$week[$a][$w][$h] += 1;
        }

        $free = [];
        foreach ($week['free'] as $key=>$v){
            foreach ($v as $h=>$val){

                $n = $h%2;
                $z = intval(floor($h/2));
                if($h%2 == 0){
                    $t = $z.':'.'00-'.$z.':30';
                } else {
                    $t = $z.':'.'30-'.($z+1).':00';
                }
                @$free[$key][$t] = $val;
            }

        }
        $no = [];
        foreach ($week['no'] as $key=>$v){
            foreach ($v as $h=>$val){

                $n = $h%2;
                $z = intval(floor($h/2));
                if($h%2 == 0){
                    $t = $z.':'.'00-'.$z.':30';
                } else {
                    $t = $z.':'.'30-'.($z+1).':00';
                }
                @$no[$key][$t] = $val;
            }

        }
        echo '<pre>';
        print_r($free);
        print_r($no);

        exit;
    }

    public function get_xiaoxue_user_lesson_info(){
        //统计小学生个人 上课时间段星期分布

        $this->switch_tongji_database();
        $start = strtotime('2017-9-1');
        $end   = strtotime('2017-10-1');

        $sql   = "select s.nick,l.userid,group_concat(lesson_start) l_time from t_lesson_info l left join t_student_info s on s.userid=l.userid where l.grade<200 and lesson_start>=$start and lesson_start<$end and s.is_test_user=0 and l.lesson_del_flag=0 and l.lesson_type in (0,3) group by l.userid";
        $ret   = $this->t_grab_lesson_link_info->get_info_test($sql);
        // dd($ret);
        foreach($ret as &$v){
            $week = [0=>[],1=>[], 2=>[], 3=>[], 4=>[], 5=>[], 6=>[]];

            $lesson_arr = explode(',',$v['l_time']);
            foreach($lesson_arr as $val){

                $w = date('w',$val);
                $h = $this->fenzu( $val);
                @$week[$w][$h] += 1;
            }
            $v['week'] = $week;

        }

        $time_range = range(0,47);
        foreach ($time_range as &$h){

            $n = $h%2;
            $z = intval(floor($h/2));
            if($h%2 == 0){
                $h = $z.':'.'00-'.$z.':30';
            } else {
                $h = $z.':'.'30-'.($z+1).':00';
            }

        }

        // dd($ret);
        return $this->pageView( __METHOD__,[],[
            'ret' => $ret,
            'time' => $time_range
        ]);

    }

    public function hash_check(){

        $str1 = $this->get_in_str_val('str1');
        $str2 = $this->get_in_str_val('str2');
        $ret = Hash::check($str1, $str2);
        dd($ret);

    }

    public function add_resource_type(){
        $r_arr = [1,2,3,4,5,6,7,9];//资源类型
        // $s_arr = E\Esubject::$desc_map;
        $s_arr = [1,2,3,4,5,6,7,8,9,10,11];//科目
        $g_arr = [101,102,103,104,105,106,201,202,203,301,302,303];
        $o_arr = [201,202,203,301,302,303];
        foreach($r_arr as $r){
            foreach($s_arr as $s){
                if($r == 7){//要年级段,不要年级
                    if($s<4){//语数外
                        $grade = [100,200,300];
                    } else {//没有小学
                        $grade = [200,300];
                    }

                } else {
                    if($s<4){//语数外
                        $grade = $g_arr;
                    } else {//没有小学
                        $grade = $o_arr;
                    }

                }
                if($r != 6){
                    foreach($grade as $g){
                        $this->t_resource_agree_info->row_insert([
                            'resource_type' => $r,
                            'subject'       => $s,
                            'grade'         => $g,
                        ]);
                    }
                } else {
                    $year = [2015,2016,2017];
                    foreach($grade as $g){

                        foreach($year as $y){
                            // $shen = $this->get_shen_shi();
                            // foreach($shen as $sh => $city){
                            //     foreach($city as $c){
                            $this->t_resource_agree_info->row_insert([
                                'resource_type' => $r,
                                'subject'       => $s,
                                'grade'         => $g,
                                'tag_one'       => $y,
                                // 'tag_two'    => $sh,
                                // 'tag_three'  => $c,
                            ]);
                            //     }
                            // }
                        }
                    }
                }

            }
        }
    }

    public function add_city(){
        $city = [[110000,110100],[120000,120100],[120000,120200],[130000,130100],[130000,130200],[130000,130300],[130000,130400],[130000,130500],[130000,130600],[130000,130700],[130000,130800],[130000,130900],[130000,131000],[130000,131100],[140000,140100],[140000,140200],[140000,140300],[140000,140400],[140000,140500],[140000,140600],[140000,140700],[140000,140800],[140000,140900],[140000,141000],[140000,141100],[150000,150100],[150000,150200],[150000,150300],[150000,150400],[150000,150500],[150000,150600],[150000,150700],[150000,150800],[150000,150900],[150000,152200],[150000,152500],[150000,152900],[210000,210100],[210000,210200],[210000,210300],[210000,210400],[210000,210500],[210000,210600],[210000,210700],[210000,210800],[210000,210900],[210000,211000],[210000,211100],[210000,211200],[210000,211300],[210000,211400],[220000,220100],[220000,220200],[220000,220300],[220000,220400],[220000,220500],[220000,220600],[220000,220700],[220000,220800],[220000,222400],[230000,230100],[230000,230200],[230000,230300],[230000,230400],[230000,230500],[230000,230600],[230000,230700],[230000,230800],[230000,230900],[230000,231000],[230000,231100],[230000,231200],[230000,232700],[310000,310100],[310000,310200],[320000,320100],[320000,320200],[320000,320300],[320000,320400],[320000,320500],[320000,320600],[320000,320700],[320000,320800],[320000,320900],[320000,321000],[320000,321100],[320000,321200],[320000,321300],[330000,330100],[330000,330200],[330000,330300],[330000,330400],[330000,330500],[330000,330600],[330000,330700],[330000,330800],[330000,330900],[330000,331000],[330000,331100],[340000,340100],[340000,340200],[340000,340300],[340000,340400],[340000,340500],[340000,340600],[340000,340700],[340000,340800],[340000,341000],[340000,341100],[340000,341200],[340000,341300],[340000,341500],[340000,341600],[340000,341700],[340000,341800],[350000,350100],[350000,350200],[350000,350300],[350000,350400],[350000,350500],[350000,350600],[350000,350700],[350000,350800],[350000,350900],[360000,360100],[360000,360200],[360000,360300],[360000,360400],[360000,360500],[360000,360600],[360000,360700],[360000,360800],[360000,360900],[360000,361000],[360000,361100],[370000,370100],[370000,370200],[370000,370300],[370000,370400],[370000,370500],[370000,370600],[370000,370700],[370000,370800],[370000,370900],[370000,371000],[370000,371100],[370000,371200],[370000,371300],[370000,371400],[370000,371500],[370000,371600],[370000,371700],[410000,410100],[410000,410200],[410000,410300],[410000,410400],[410000,410500],[410000,410600],[410000,410700],[410000,410800],[410000,410900],[410000,411000],[410000,411100],[410000,411200],[410000,411300],[410000,411400],[410000,411500],[410000,411600],[410000,411700],[410000,419001],[420000,420100],[420000,420200],[420000,420300],[420000,420500],[420000,420600],[420000,420700],[420000,420800],[420000,420900],[420000,421000],[420000,421100],[420000,421200],[420000,421300],[420000,422800],[420000,429004],[420000,429005],[420000,429006],[420000,429021],[430000,430100],[430000,430200],[430000,430300],[430000,430400],[430000,430500],[430000,430600],[430000,430700],[430000,430800],[430000,430900],[430000,431000],[430000,431100],[430000,431200],[430000,431300],[430000,433100],[440000,440100],[440000,440200],[440000,440300],[440000,440400],[440000,440500],[440000,440600],[440000,440700],[440000,440800],[440000,440900],[440000,441200],[440000,441300],[440000,441400],[440000,441500],[440000,441600],[440000,441700],[440000,441800],[440000,441900],[440000,442000],[440000,445100],[440000,445200],[440000,445300],[450000,450100],[450000,450200],[450000,450300],[450000,450400],[450000,450500],[450000,450600],[450000,450700],[450000,450800],[450000,450900],[450000,451000],[450000,451100],[450000,451200],[450000,451300],[450000,451400],[460000,460100],[460000,460200],[460000,460300],[460000,460400],[460000,469001],[460000,469002],[460000,469005],[460000,469006],[460000,469007],[460000,469021],[460000,469022],[460000,469023],[460000,469024],[460000,469025],[460000,469026],[460000,469027],[460000,469028],[460000,469029],[460000,469030],[500000,500100],[500000,500200],[510000,510100],[510000,510300],[510000,510400],[510000,510500],[510000,510600],[510000,510700],[510000,510800],[510000,510900],[510000,511000],[510000,511100],[510000,511300],[510000,511400],[510000,511500],[510000,511600],[510000,511700],[510000,511800],[510000,511900],[510000,512000],[510000,513200],[510000,513300],[510000,513400],[520000,520100],[520000,520200],[520000,520300],[520000,520400],[520000,520500],[520000,520600],[520000,522300],[520000,522600],[520000,522700],[530000,530100],[530000,530300],[530000,530400],[530000,530500],[530000,530600],[530000,530700],[530000,530800],[530000,530900],[530000,532300],[530000,532500],[530000,532600],[530000,532800],[530000,532900],[530000,533100],[530000,533300],[530000,533400],[540000,540100],[540000,540200],[540000,540300],[540000,540400],[540000,542200],[540000,542400],[540000,542500],[610000,610100],[610000,610200],[610000,610300],[610000,610400],[610000,610500],[610000,610600],[610000,610700],[610000,610800],[610000,610900],[610000,611000],[620000,620100],[620000,620200],[620000,620300],[620000,620400],[620000,620500],[620000,620600],[620000,620700],[620000,620800],[620000,620900],[620000,621000],[620000,621100],[620000,621200],[620000,622900],[620000,623000],[630000,630100],[630000,630200],[630000,632200],[630000,632300],[630000,632500],[630000,632600],[630000,632700],[630000,632800],[640000,640100],[640000,640200],[640000,640300],[640000,640400],[640000,640500],[650000,650100],[650000,650200],[650000,650400],[650000,652200],[650000,652300],[650000,652700],[650000,652800],[650000,652900],[650000,653000],[650000,653100],[650000,653200],[650000,654000],[650000,654200],[650000,654300],[650000,659001],[650000,659002],[650000,659003],[650000,659004],[650000,659005],[650000,659006],[650000,659007],[650000,659008],[810000,810001],[810000,810002],[810000,810003],[810000,810004],[810000,810005],[810000,810006],[810000,810007],[810000,810008],[810000,810009],[810000,810010],[810000,810011],[810000,810012],[810000,810013],[810000,810014],[810000,810015],[810000,810016],[810000,810017],[810000,810018],[820000,820001],[820000,820002],[820000,820003],[820000,820004],[820000,820005],[820000,820006],[820000,820007],[820000,820008],];
        // dd($city);
        $o_arr = [201,202,203,301,302,303];
        $g_arr = [101,102,103,104,105,106,201,202,203,301,302,303];
        $s = $this->get_in_int_val('s');
        if($s<4){//语数外
            $grade = $g_arr;
        } else {//没有小学
            $grade = $o_arr;
        }
        $year = [2015,2016,2017];
        $a = 1;
        foreach($grade as $g){
            foreach($year as $y){
                foreach($city as $c){
                    // $job=(new \App\Jobs\add_resource_agree($s, $g, $y, $c[0], $c[1]))->delay(10);
                    // dispatch($job);
                    $this->t_resource_agree_info->row_insert([
                        'resource_type' => 6,
                        'subject'       => $s,
                        'grade'         => $g,
                        'tag_one'       => $y,
                        'tag_two'       => $c[0],
                        'tag_three'     => $c[1],
                    ]);

                }
            }
        }
    }

    public function refund_list(){
        set_time_limit(0);
        // $this->switch_tongji_database();
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range_month(0,0, [
            0 => array( "apply_time", "申请时间"),
            1 => array("flow_status_time","审批时间"),
            2 => array("qc_deal_time","定责时间"),
        ]);

        $adminid       = $this->get_account_id();
        $refund_type   = $this->get_in_int_val('refund_type',-1);
        $userid        = $this->get_in_int_val('userid',-1);
        $is_test_user  = $this->get_in_int_val('is_test_user',0);
        $page_num      = $this->get_in_page_num();
        $refund_userid = $this->get_in_int_val("refund_userid", -1);
        $qc_flag = $this->get_in_int_val("qc_flag", 1);

        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right        = $this->get_seller_adminid_and_right();
        $acc                  = $this->get_account();

        $ret_info = $this->t_order_refund->get_order_refund_list_nopage(
            $opt_date_str,$refund_type,$userid,$start_time,$end_time, $is_test_user,$refund_userid,$require_adminid_list
        );
        $refund_info = [];
        // $th_arr = ['签约时间','退费申请时间','原因分析','科目','老师一级原因','老师二级原因','老师三级原因','责任鉴定 | 老师','责任鉴定 | 科目'];
        // $s = $this->table_start($th_arr);
        // echo $s;
        echo '<table border=1>';
        $a =1;
        foreach($ret_info as  $k_f => &$item){
            $item['deal_nick'] = $this->cache_get_account_nick($item['qc_adminid']);
            \App\Helper\Utils::unixtime2date_for_item($item,"qc_deal_time");

            // $item['ass_nick'] = $this->cache_get_assistant_nick($item['assistantid']);
            // $item['tea_nick'] = $this->cache_get_teacher_nick($item['teacher_id']);
            $item['subject_str'] = E\Esubject::get_desc($item['subject']);

            $item["is_staged_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["is_staged_flag"]);
            // $item['user_nick']         = $this->cache_get_student_nick($item['userid']);
            // $item['refund_user']       = $this->cache_get_account_nick($item['refund_userid']);
            // $item['lesson_total']      = $item['lesson_total']/100;
            // $item['should_refund']     = $item['should_refund']/100;
            // $item['price']             = $item['price']/100;
            // $item['real_refund']       = $item['real_refund']/100;
            // $item['discount_price']    = $item['discount_price']/100;
            $item['apply_time_str']    = date("Y-m-d H:i",$item['apply_time']);
            $item['refund_status_str'] = $item['refund_status']?'已打款':'未付款';

            \App\Helper\Common::set_item_enum_flow_status($item);
            E\Econtract_type::set_item_value_str($item,"contract_type");
            E\Eboolean::set_item_value_str($item,"need_receipt");
            E\Egrade::set_item_value_str($item);

            E\Eqc_advances_status::set_item_value_str($item);
            E\Eqc_contact_status::set_item_value_str($item);
            E\Eqc_voluntarily_status::set_item_value_str($item);

            \App\Helper\Utils::unixtime2date_for_item($item,"flow_status_time");
            $item['order_time_str'] = date('Y-m-d H:i:s',$item['order_time']);

            if($qc_flag==0){
                continue;
            }
            //以下不处理

            $refund_qc_list = $this->t_order_refund->get_refund_analysis($item['apply_time'], $item['orderid']);
            if($refund_qc_list['qc_other_reason']
               || $refund_qc_list['qc_analysia']
               || $refund_qc_list['qc_reply']
            ){
                $item['flow_status_str'] = '<font style="color:#a70192;">QC已审核</font>';
            }

            $pass_time = $item['apply_time']-$item['order_time'];
            if($pass_time >= (90*24*3600)){ // 下单是否超过3个月
                $item['is_pass'] = '<font style="color:#ff0000;">是</font>';
            }else{
                $item['is_pass'] = '<font style="color:#2bec2b;">否</font>';
            }

            //处理 投诉分析 [QC-文斌]
            $arr = $this->get_refund_analysis_info($item['orderid'],$item['apply_time']);
            // $item['qc_other_reason'] = trim($arr['qc_anaysis']['qc_other_reason']);
            // $item['qc_analysia']     = trim($arr['qc_anaysis']['qc_analysia']);
            // $item['qc_reply']        = trim($arr['qc_anaysis']['qc_reply']);
            // $item['duty']            = $arr['duty'];
            // E\Eboolean::set_item_value_str($item, "duty");

            /**
             * @demand 获取孩子[首次上课时间] [末次上课时间]
             */
            $lesson_time_arr = $this->t_lesson_info_b3->get_extreme_lesson_time($item['userid']);

            $item['max_time_str'] = @$lesson_time_arr['max_time']?@unixtime2date($lesson_time_arr['max_time']):'无';
            $item['min_time_str'] = @$lesson_time_arr['min_time']?@unixtime2date($lesson_time_arr['min_time']):'无';

            foreach($arr['key1_value'] as $kkk=>&$v1){
                // if(in_array($kkk, [2,7,8])){
                    $key1_name = @$v1['value'].'一级原因';
                    $key2_name = @$v1['value'].'二级原因';
                    $key3_name = @$v1['value'].'三级原因';
                    $reason_name    = @$v1['value'].'reason';
                    $dep_score_name = @$v1['value'].'dep_score';

                    $item["$key1_name"] = '';
                    $item["$key2_name"] = '';
                    $item["$key3_name"] = '';
                    $item["$reason_name"]     = "";
                    $item["$dep_score_name"]  = "";

                    foreach($arr['list'] as $v2){
                        if($v2['key1_str'] == $v1['value']){
                            if(isset($v1["$key1_name"])){
                                $item["$key1_name"] = @$item["$key1_name"].'/'.$v2['key2_str'];
                                $item["$key2_name"] = @$item["$key2_name"].'/'.$v2['key3_str'];
                                $item["$key3_name"] = @$item["$key3_name"].'/'.$v2['key4_str'];
                                $item["$reason_name"]     = @$item["$reason_name"].'/'.$v2['reason'];
                                $item["$dep_score_name"]  = @$item["$dep_score_name"].'/'.$v2['score'];
                            }else{
                                $item["$key1_name"] = @$v2['key2_str'];
                                $item["$key2_name"] = @$v2['key3_str'];
                                $item["$key3_name"] = @$v2['key4_str'];
                                $item["$reason_name"]     = @$v2['reason'];
                                $item["$dep_score_name"]  = @$v2['score'];
                            }
                        }
                    }
                    $score_name   = $v1['value'].'扣分值';
                    $percent_name = $v1['value'].'责任值';
                    $item["$score_name"]   = @$v1['score'];
                    $item["$percent_name"] = @$v1['responsibility_percent'];
                // }
            }
            // if($a == 1){
            //     $a++;
            //     $ks = array_keys($item);
            //     echo '<tr>';
            //     foreach($ks as $kv){
            //         echo '<td>',$kv,'</td>';
            //     }
            //     echo '</tr>';

            // }

            echo '<tr>';
            foreach($item as $iv){
                echo '<td>',$iv,'</td>';
            }
            echo '</tr>';
            unset($ret_info[$k_f]);
        }

        return 'ok';
        // $s = $this->table_end($s);

        // return $s;

    }

    public function  get_refund_analysis_info($orderid,$apply_time){
        $list = $this->t_refund_analysis->get_list($orderid,$apply_time);
        foreach ($list as $key =>&$item) {
            $keys       = $this->t_order_refund_confirm_config->get_refundid_by_configid($item['configid']);
            $ret        = @$this->t_order_refund_confirm_config->get_refund_str_by_keys($keys);
            $list[$key] = @array_merge($item,$ret);
        }

        // dd($list);
        //以上处理原因填写
        /**
         * 规则: 如果教学部的责任为0 则 老师|科目的责任也为0 [QC-文斌]
         * 责任占比=部门分值/总分
         * 部门分值=（部门问题1分值+。。。。。。+部门问题N分值）/N
         * 总分=部门1分值+。。。+部门N分值
         */

        $total_score = 0;
        $key1_value  = $this->t_order_refund_confirm_config->get_all_key1_value();
        $is_teaching_flag = true;
        $duty = 0;

        foreach($key1_value as $k1=>&$v1){
            $num = 0;
            $score = 0;

            foreach($list as $i2=>&$v2){
                $v2['department'] = $this->t_order_refund_confirm_config->get_department_name_by_configid($v2['configid']);

                if($v2['score'] >0 && $v2['department'] == '教学部'){
                    $is_teaching_flag = false;
                }

                /**
                 * @demand 老师管理或教学部出现责任划分时，该部分自动引用之老师和科目选择的字段，若无责任则默认空值
                 **/
                if(($v2['score'] >0 && $v2['department'] == '教学部') || ($v2['score']>0 && $v2['department'] == '老师管理') ){
                    $duty = 1;
                }

                if($v2['department'] == $v1['value']){
                    $num++;
                    $score += $v2['score'];
                }
            }

            if($num>0){
                $v1['score'] = $score/$num;
                $total_score += ($score/$num);
            }
        }

        foreach($key1_value as &$v3){
            if($is_teaching_flag && ($v3['value'] == '老师' || $v3['value']=='科目') ){
                if(isset($v3['score'])){
                    $total_score-=$v3['score'];
                    $v3['score'] = 0;
                }
            }
        }

        foreach($key1_value as &$v4){
            if($total_score>0){
                if(isset($v4['score'])){
                    $v4['responsibility_percent'] = number_format(($v4['score']/$total_score)*100,2).'%';
                }else{
                    $v4['responsibility_percent'] = '0%';
                }
            }
        }

        $arr['qc_anaysis'] = $this->t_order_refund->get_qc_anaysis_by_orderid_apply($orderid, $apply_time);
        $arr['key1_value'] = $key1_value;
        $arr['list']       = $list;
        $arr['duty']       = $duty;
        return $arr;

        $this->table_start();
    }

    public function get_order(){
        $sql = "select o.order_time,m.account,m.become_member_time,o.price,o.check_money_time from db_weiyi.t_order_info o left join db_weiyi_admin.t_manager_info m on m.account=o.sys_operator where o.order_time>=1504195200 and o.order_time<1514736000 and m.del_flag=0 and m.account_role=2 and o.contract_status>0 and o.contract_type =0";
        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);

        $th_arr = ['cc','入职时间','金额','下单时间','财务确认时间'];
        $s = $this->table_start($th_arr);

        foreach($ret as $v){
            \App\Helper\Utils::unixtime2date_for_item($v, 'become_member_time');
            \App\Helper\Utils::unixtime2date_for_item($v, 'order_time');
            \App\Helper\Utils::unixtime2date_for_item($v, 'check_money_time');
            $s= $this->tr_add($s, $v['account'], $v['become_member_time'],$v['price']/100, $v['order_time'],$v['check_money_time']);

        }
        $s = $this->table_end($s);

        return $s;

    }

}
