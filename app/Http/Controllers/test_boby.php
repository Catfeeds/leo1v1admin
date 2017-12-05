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

    public function get_new_qq_group_html($grade_start,$grade_part_ex,$subject){
        // 528851744 原答疑1群，人数已满
        if ( $grade_start >= 5 ) {
            $grade = 300;
        } else if ($grade_start >= 3) {
            $grade = 200;
        } else if($grade_start > 0 ) {
            $grade = 100;
        }else if ($grade_part_ex == 1) {
            $grade = 100;
        }else if ($grade_part_ex == 2) {
            $grade = 200;
        }else if ($grade_part_ex == 3) {
            $grade = 300;
        }else{
            $grade = 100;
        }

        $qq_answer = [
            1  => ["答疑语文","126321887","用于薪资，软件等综合问题"],
            2  => ["答疑数学","29759286","用于薪资，软件等综合问题"],
            3  => ["答疑英语","451786901","用于薪资，软件等综合问题"],
            99 => ["答疑综合学科","513683916","用于薪资，软件等综合问题"],
        ];
        $qq_group  = [
            '100' => [
                1=>[
                    ["教研-小学语文","653665526","处理教学相关事务"],
                    ["排课-小学语文","387090573","用于抢课"]
                ],2=>[
                    ["教研-小学数学","644724773","处理教学相关事务"],
                    ["排课-小学数学","527321518","用于排课"],
                ],3=>[
                    ["教研-小学英语","653621142","处理教学相关事务"],
                    ["排课-小学英语","456074027","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ],
            ],
            '200' => [
                1=>[
                    ["教研-初中语文","623708298","处理教学相关事务"],
                    ["排课-初中语文","465023367","用于抢课"]
                ],2=>[
                    ["教研-初中数学","373652928","处理教学相关事务"],
                    ["排课-初中数学","665840444","用于排课"],
                ],3=>[
                    ["教研-初中英语","161287264","处理教学相关事务"],
                    ["排课-初中英语","463756557","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ]
            ],
            '300' => [
                1=>[
                    ["教研-高中语文","653689781","处理教学相关事务"],
                    ["排课-高中语文","573564364","用于抢课"]
                ],2=>[
                    ["教研-高中数学","644249518","处理教学相关事务"],
                    ["排课-高中数学","659192934","用于排课"],
                ],3=>[
                    ["教研-高中英语","456994484","处理教学相关事务"],
                    ["排课-高中英语","280781299","用于排课"],
                ],4=>[
                    ["教研-化学","652504426","处理教学相关事务"],
                    ["排课-化学","608323943","用于排课"],
                ],5=>[
                    ["教研-物理","652500552","处理教学相关事务"],
                    ["排课-物理","534509273","用于排课"],
                ],99=>[
                    ["教研-文理综合","652567225","处理教学相关事务"],
                    ["排课-文理综合","598180360","用于排课"],
                ]
            ],
        ];

        $html="";
        $list = @$qq_group[ $grade ][ $subject ] ? $qq_group[ $grade ][ $subject ] : $qq_group[ $grade ][99];
        $list[] = @$qq_answer[ $subject ] ? $qq_answer[ $subject ] : $qq_answer[99];
        // dd($list);
        foreach($list as $val){
            $html .= "<li>【LEO】".$val[0]."<br>群号：".$val[1]."<br>群介绍：".$val[2]."</li>";
        }
        return $html;
    }

    public function send_msg_to_tea_wx(){
        // return 1;
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
        //         $job=(new \App\Jobs\add_lesson_grade_user($userid_gao, $k))->delay(10);
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
        $end = strtotime('2017-10-1');

        $sql = "select s.nick,l.userid,group_concat(lesson_start) l_time from t_lesson_info l left join t_student_info s on s.userid=l.userid where l.grade<200 and lesson_start>=$start and lesson_start<$end and s.is_test_user=0 and l.lesson_del_flag=0 and l.lesson_type in (0,3) group by l.userid";
        $ret = $this->t_grab_lesson_link_info->get_info_test($sql);
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


    public function test_md5(){
        return $this->pageView( __METHOD__,[]);
    }

    public function hash_check(){

        $str1 = $this->get_in_str_val('str1');
        $str2 = $this->get_in_str_val('str2');
        $ret = Hash::check($str1, $str2);
        dd($ret);

    }
}
