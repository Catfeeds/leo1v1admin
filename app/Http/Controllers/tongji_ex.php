<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

use Illuminate\Support\Facades\Input ;
class tongji_ex extends Controller
{
    use  CacheNick;
    public function __construct() {
        parent::__construct();
        $this->switch_tongji_database();
    }

    public function top_list() {

        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $tongji_type=$this->get_in_int_val("tongji_type",1, E\Etongji_type::class);
        $page_num=$this->get_in_page_num();

        $ret_info=$this->t_tongji_seller_top_info->get_list($page_num,$tongji_type,$start_time);
        foreach($ret_info["list"] as &$item) {
            $this->cache_set_item_account_nick($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function call_count() {
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $sys_invaild_flag= $this->get_in_e_boolean(-1,"sys_invaild_flag");
        $this->switch_tongji_database();
        $ret_info=$this->t_seller_student_new->get_call_info( $start_time, $end_time, $sys_invaild_flag  );
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function test_lesson_order_detail_list() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $cur_require_adminid= $this->get_in_int_val("cur_require_adminid", -1 );
        $teacherid= $this->get_in_int_val("teacherid", -1);
        $origin_ex= $this->get_in_str_val("origin_ex", "");
        $page_info= $this->get_in_page_info();

        $ret_info=$this->t_test_lesson_subject_require->test_lesson_order_detail_list($page_info,$start_time,$end_time,$cur_require_adminid,$origin_ex,$teacherid);
        foreach ($ret_info["list"] as &$item ){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_order_info() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $cur_require_adminid= $this->get_in_int_val("cur_require_adminid", -1 );
        $teacherid= $this->get_in_int_val("teacherid", -1);
        $origin_ex= $this->get_in_str_val("origin_ex", "");

        $group_by_field= $this->get_in_str_val("group_by_field", "cur_require_adminid");
        $ret_info=$this->t_test_lesson_subject_require->tongji_test_lesson_order($group_by_field,$start_time,$end_time,$cur_require_adminid,$origin_ex,$teacherid);

        $all_item=["title"=> "全部" ];

        foreach($ret_info["list"]  as &$item ) {
            $item["percent"] = intval( $item["order_count"]/ $item["test_lesson_count"] *10000)/100;
            $item["order_money"]/=100;

            $field_value=$item["field_name"];
            switch ( $group_by_field ) {
            case "teacherid" :
                $title=$this->cache_get_teacher_nick($field_value);
                break;
            case "cur_require_adminid" :
                $title=$this->cache_get_account_nick($field_value);
                break;
            default:
                $title=$field_value;
                break;
            }
            $item["title"]= $title;
        }


        return $this->pageView(__METHOD__,$ret_info);
    }

    public function user_login() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $ret_info=$this->t_user_login_log->get_login_tongji($start_time,$end_time);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function user_login_list() {
        $page_info= $this->get_in_page_info();
        list($start_time,$end_time) = $this->get_in_date_range_month(0 );
        $ip                         = trim( $this->get_in_str_val("ip"));
        $userid                     = $this->get_in_userid(-1);
        $ret_info                   = $this->t_user_login_log->get_login_list($page_info, $start_time,$end_time, $userid ,$ip );
        foreach($ret_info["list"]  as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"login_time");
        }
        /*
        "userid" => "21312"
                 "login_time" => "12412412"
                 "nick" => "2131wo"
                 "ip" => "1231"
        */
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function sys_error_list() {
        list($start_time, $end_time)= $this->get_in_date_range(0,0,0,[],0,0,true);
        $report_error_from_type=$this->get_in_el_report_error_from_type();
        $report_error_from_type=$this->get_in_el_report_error_type();

    }
    public function show_sys_error_info() {
        $id=$this->get_in_id();
        $item= $this->t_sys_error_info->field_get_list($id,"*");
        \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        E\Ereport_error_from_type::set_item_value_str($item);
        E\Ereport_error_type::set_item_value_str($item);
        return $this->pageView(__METHOD__, null, ["report_info"=> $item]);
    }

    public function get_lesson_user_ip_same_info(){
        list($start_time,$end_time) = $this->get_in_date_range_month(0 );
        $match_type = $this->get_in_int_val("match_type",0);
        $company_ip_list=[
             '123.57.153.80',
             '123.57.153.95',
             '116.226.191.120',
             '101.81.224.61',
             '121.43.230.95',
             '116.226.191.6',
             '222.64.63.129'
        ];
        $str ="";
        foreach($company_ip_list as $val){
            $str  .="'".$val."',";
        }
        $ip_str = "(".trim($str,",").")";
        $ret_info = $this->t_user_login_log->get_pay_stu_ip_list($start_time,$end_time,$match_type,$ip_str);

        $list=[];
        foreach($ret_info as $val){
            $k = $val["userid"]."-".$val["ip"];
            @$list[$k]["userid"]=$val["userid"];
            @$list[$k]["nick"]=$val["phone"];
            @$list[$k]["ip"]=$val["ip"];
            @$list[$k]["grade"]=$val["grade"];
            @$list[$k]["same_name_list"] .=$val["s2_phone"].",";
        }
        foreach($list as &$item){
            $item["same_name_list"] = trim($item["same_name_list"],",");
            E\Egrade::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list)  );

    }

    public function seller_student_detail(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_seller_student_new->get_master_detail_list($start_time,$end_time,$page_info);
        foreach($ret_info['list'] as &$item){
            E\Eorigin_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Eseller_student_status::set_item_value_color_str($item);
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            $cc_adminid = $item['admin_revisiterid']>0?$item['admin_revisiterid']:$item['competition_call_adminid'];
            $item['cc_nick'] = $this->cache_get_account_nick($cc_adminid);
            $item['first_called_cc'] = $this->cache_get_account_nick($item['first_called_cc']);
            $item['first_get_cc'] = $this->cache_get_account_nick($item['first_get_cc']);
            $item['key0'] = $this->cache_get_origin_key0($item['origin']);
            $item['test_lesson_flag'] = \App\Helper\Common::get_set_boolean_color_str($item['test_lesson_flag']>0?1:2);
            $item["suc_test_flag"] = \App\Helper\Common::get_set_boolean_color_str($item["test_lesson_count"]>0?1:2);
            $item['order_flag'] = \App\Helper\Common::get_set_boolean_color_str($item["orderid"]>0?1:2);
            $item['price'] = $item['price']/100;
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function seller_student_distribution(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_seller_edit_log->get_seller_distribution_list($start_time,$end_time,$page_info);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"first_revisit_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"first_contact_time");
            $item['price'] = $item['price']/100;
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function market_seller_student(){
        $this->check_and_switch_tongji_domain();
        $ret = $this->t_seller_student_origin->get_item_list();
        $ret_info = [];
        foreach($ret as $info){
            $userid = $info['userid'];
            $ret_info[$userid]['phone'] = isset($ret_info[$userid]['phone'])?$ret_info[$userid]['phone']:$info['phone'];
            $ret_info[$userid]['origin'] = isset($ret_info[$userid]['origin'])?$ret_info[$userid]['origin'].','.$info['origin']:$info['origin'];
            $ret_info[$userid]['add_time'] = isset($ret_info[$userid]['add_time'])?$ret_info[$userid]['add_time']:date('Y-m-d H:i:s',$info['add_time']);
            $ret_info[$userid]['is_exist'] = isset($ret_info[$userid]['is_exist'])?$ret_info[$userid]['is_exist']:($info['is_exist_count']>0?'是':'否');
        }
        $num = 0;

        echo '<table border="1" width="600" align="center">';
        echo '<caption><h1>12月进入例子渠道</h1></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th>编号</th><th>号码</th><th>渠道</th><th>进入日期</th><th>是否重复</th>';
        echo '</tr>';
        foreach($ret_info as $item){
            $num++;
            echo '<tr>';
            echo '<td>'.$num.'</td>';
            echo '<td>'.$item['phone'].'</td>';
            echo '<td>'.$item['origin'].'</td>';
            echo '<td>'.$item['add_time'].'</td>';
            echo '<td>'.$item['is_exist'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function market_seller_student_repeat(){
        $this->check_and_switch_tongji_domain();
        $ret = $this->t_seller_student_origin->get_item_exist_list();
        $ret_info = [];
        foreach($ret as $info){
            $userid = $info['userid'];
            $order_time = $info['order_time']>0?date('Y-m-d H:i:s',$info['order_time']):'';
            $price = $info['price']>0?$info['price']/100:'';
            $lessonid = $info['lessonid'];
            $lesson_start = $info['lesson_start'];
            $lesson_end = $info['lesson_end'];
            $lesson_del_flag = $info['lesson_del_flag'];
            $confirm_flag = $info['confirm_flag'];
            $lesson_user_online_status = $info['lesson_user_online_status'];
            $sys_operator = $info['adminid']>0?$this->cache_get_account_nick($info['adminid']):'';

            $ret_info[$userid]['phone'] = isset($ret_info[$userid]['phone'])?$ret_info[$userid]['phone']:$info['phone'];
            $ret_info[$userid]['origin'] = isset($ret_info[$userid]['origin'])?$ret_info[$userid]['origin']:$info['origin'];
            $ret_info[$userid]['add_time'] = isset($ret_info[$userid]['add_time'])?$ret_info[$userid]['add_time']:date('Y-m-d H:i:s',$info['add_time']);
            $ret_info[$userid]['is_exist_count'] = isset($ret_info[$userid]['is_exist_count'])?$ret_info[$userid]['is_exist_count']:$info['is_exist_count'];
            $ret_info[$userid]['orderid'] = isset($ret_info[$userid]['orderid'])?$ret_info[$userid]['orderid']:$info['orderid'];
            $ret_info[$userid]['order_time'] = isset($ret_info[$userid]['order_time'])?$ret_info[$userid]['order_time']:$order_time;
            $ret_info[$userid]['price'] = isset($ret_info[$userid]['price'])?$ret_info[$userid]['price']:$price;
            if($lessonid>0){
                $ret_info[$userid]['lessonid'][$lessonid] = [
                    'lesson_start'=>$lesson_start,
                    'lesson_end'=>$lesson_end,
                    'lesson_del_flag'=>$lesson_del_flag,
                    'confirm_flag'=>$confirm_flag,
                    'lesson_user_online_status'=>$lesson_user_online_status,
                    'sys_operator'=>$sys_operator,
                ];
            }
        }
        $num = 0;

        echo '<table border="1" width="600" align="center">';
        echo '<caption><h1>12月重复进入例子</h1></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th>编号</th><th>号码</th><th>渠道</th><th>进入时间</th><th>重复进入次数</th><th>试听情况</th><th>签单</th><th>签单时间</th>';
        echo '</tr>';
        foreach($ret_info as $userid=>$item){
            $num++;
            echo '<tr>';
            echo '<td>'.$num.'</td>';
            echo '<td>'.$item['phone'].'</td>';
            echo '<td>'.$item['origin'].'</td>';
            echo '<td>'.$item['add_time'].'</td>';
            echo '<td>'.$item['is_exist_count'].'</td>';
            echo '<td>';
            if(isset($item['lessonid'])){
                foreach($item['lessonid'] as $lessonid=>$info){
                    echo 'lessonid:'.$lessonid.',';
                    echo '试听时间:'.date('Y-m-d H:i:s',$info['lesson_start']).',';
                    echo '是否取消:'.($info['lesson_del_flag']==1?'是':'否').',';
                    echo '是否成功:'.(($info['confirm_flag']<2 && $info['lesson_user_online_status']==1 && $info['lesson_del_flag']==0)?'是':'否').',';
                    echo '试听申请cc:'.$info['sys_operator'];
                    echo "\n";
                }
            }else{
                echo '无试听';
            }
            echo '</td>';
            echo '<td>'.$item['price'].'</td>';
            echo '<td>'.$item['order_time'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function market_january_seller_student(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time,$end_cc_60,$end_c_60,$end_cc_40,$end_c_40) = [strtotime('2018-01-01'),strtotime('2018-02-01'),0,0,0,0];
        $count = $this->t_seller_student_new->get_item_january_count($start_time,$end_time);
        $called_count = $this->t_seller_student_new->get_item_january_called_count($start_time,$end_time);
        $no_called_count = $this->t_seller_student_new->get_item_january_no_called_count($start_time,$end_time);
        $ret = $this->t_seller_student_new->get_item_january_list($start_time,$end_time);
        $ret_info = [];
        foreach($ret as $info){
            $userid = $info['userid'];
            $duration = $info['duration'];
            $end_reason = $info['end_reason'];
            if($duration>0 &&  $duration<40){
                if($end_reason == 0){
                    $ret_info[$userid]['end_40'] = isset($ret_info[$userid]['end_40'])?$ret_info[$userid]['end_40']:0;
                }elseif($end_reason == 1){
                    $ret_info[$userid]['end_40'] = isset($ret_info[$userid]['end_40'])?$ret_info[$userid]['end_40']:1;
                }
            }
            if($duration>0 &&  $duration<60){
                if($end_reason == 0){
                    $ret_info[$userid]['end_60'] = isset($ret_info[$userid]['end_60'])?$ret_info[$userid]['end_60']:0;
                }elseif($end_reason == 1){
                    $ret_info[$userid]['end_60'] = isset($ret_info[$userid]['end_60'])?$ret_info[$userid]['end_60']:1;
                }
            }
        }
        foreach($ret_info as $item){
            if(isset($item['end_40'])){
                if($item['end_40'] == 0){
                    $end_cc_40 += 1;
                }elseif($item['end_40'] == 1){
                    $end_c_40 += 1;
                }
            }
            if(isset($item['end_60'])){
                if($item['end_60'] == 0){
                    $end_cc_60 += 1;
                }elseif($item['end_60'] == 1){
                    $end_c_60 += 1;
                }
            }
        }

        echo '<table border="1" width="600" align="center">';
        echo '<caption><h1>1月进入例子</h1></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th>进入例子量</th><th>拨通量</th><th>拨通40s内客户挂机量</th><th>拨通40s内销售挂机量</th><th>拨通60s内客户挂机量</th><th>拨通60s内销售挂机量</th><th>未拨通例子量</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>'.$count.'</td>';
        echo '<td>'.$called_count.'</td>';
        echo '<td>'.$end_cc_40.'</td>';
        echo '<td>'.$end_c_40.'</td>';
        echo '<td>'.$end_cc_60.'</td>';
        echo '<td>'.$end_c_60.'</td>';
        echo '<td>'.$no_called_count.'</td>';
        echo '</tr>';
        echo '</table>';
    }

    public function market_january_student_detail(){
        $this->check_and_switch_tongji_domain();
        list($ret_info,$userid_arr,$num,$start_time,$end_time) = [[],[],0,$this->get_in_str_val('start_time','2018-01-01'),$this->get_in_str_val('end_time','2018-01-05')];
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $ret = $this->t_seller_student_new->get_item_january_detail_list($start_time,$end_time);
        foreach($ret as $item){
            $userid = $item['userid'];
            if($item['start_time']>0){
                if($item['is_called_phone'] == 0){
                    $ret_info[$userid]['list'][] = $item;
                    $ret_info[$userid]['add_time'] = date('Y-m-d H:i:s',$item['add_time']);
                }else{
                    if($item['duration']<60){
                        $ret_info[$userid]['list'][] = $item;
                        $ret_info[$userid]['add_time'] = date('Y-m-d H:i:s',$item['add_time']);
                    }
                }
            }
        }
        foreach($ret_info as &$item){
            $call_count = count($item['list']);
            $no_called_count = 0;
            $called_count=0;
            $end_cc_count=0;
            $end_c_count=0;
            $first_called_cc='';
            $first_called_time=0;
            $end_first_called='';
            $first_called_time_long=0;
            $second_called_cc='';
            $second_called_time=0;
            $end_second_called='';
            $second_called_time_long=0;
            $tian_call_count = 0;
            $tian_called_count = 0;
            foreach($item['list'] as $info){
                if($info['uid']<10000){
                    $tian_call_count++;
                }
                if($info['is_called_phone']==0){
                    $no_called_count++;
                }elseif($info['is_called_phone']==1){
                    $called_count++;
                    if($info['uid']<10000){
                        $tian_called_count++;
                    }
                }
                if($info['end_reason']==0 && $info['uid']<10000){
                    $end_cc_count++;
                }elseif($info['end_reason']==1  && $info['uid']<10000){
                    $end_c_count++;
                }
                if($first_called_time == 0){
                    if($info['is_called_phone']==1){
                        $first_called_time = $info['start_time'];
                        $first_called_cc = $this->cache_get_account_nick($info['adminid']);
                        $end_first_called = $info['uid']<10000?($info['end_reason']==0?'销售':'客户'):'';
                        $first_called_time_long=$info['duration'];
                    }
                }else{
                    if($info['start_time']<$first_called_time){
                        if($info['is_called_phone']==1){
                            $first_called_time = $info['start_time'];
                            $first_called_cc = $this->cache_get_account_nick($info['adminid']);
                            $end_first_called = $info['uid']<10000?($info['end_reason']==0?'销售':'客户'):'';
                            $first_called_time_long=$info['duration'];
                        }
                    }
                }
            }
            if($first_called_time>0 && count($item['list'])>1){
                foreach($item['list'] as $info){
                    if($info['start_time']>$first_called_time && $info['is_called_phone']==1){
                        $second_called_cc = $this->cache_get_account_nick($info['adminid']);
                        $end_second_called = $info['uid']<10000?($info['end_reason']==0?'销售':'客户'):'';
                        $second_called_time_long=$info['duration'];
                        break;
                    }
                }
            }
            $item['call_count'] = $call_count;
            $item['no_called_count'] = $no_called_count;
            $item['called_count'] = $called_count;
            $item['end_cc_count'] = $end_cc_count;
            $item['end_c_count'] = $end_c_count;
            $item['first_called_cc'] = $first_called_cc;
            $item['end_first_called'] = $end_first_called;
            $item['first_called_time_long'] = $first_called_time_long;
            $item['second_called_cc'] = $second_called_cc;
            $item['second_called_time_long'] = $second_called_time_long;
            $item['end_second_called'] = $end_second_called;
            $item['tian_call_count'] = $tian_call_count;
            $item['tian_called_count'] = $tian_called_count;
        }
        echo '<table border="1" width="600" align="center">';
        echo '<caption><h1>1月'.date('d',$start_time).'日-'.date('d',$end_time).'日例子明细</h1></caption>';
        echo '<tr bgcolor="#dddddd">';
        echo '<th>编号</th><th>未拨通例子</th><th>拨打次数</th><th>未拨通次数</th><th>拨通次数</th><th>天润拨打次数</th><th>天润拨通次数</th><th>天润cc挂断次数</th><th>天润客户挂断次数</th><th>首次拨通cc</th><th>天润首次拨通挂断人</th><th>首次拨通通话时长/s</th><th>第二次拨通cc</th><th>天润第二次拨通挂断人</th><th>第二次拨通通话时长/s</th><th>例子进入时间</th>';
        echo '</tr>';
        foreach($ret_info as $userid=>$item){
            echo '<tr>';
            echo '<td>'.$num++.'</td>';
            echo '<td>'.$userid.'</td>';
            echo '<td>'.$item['call_count'].'</td>';
            echo '<td>'.$item['no_called_count'].'</td>';
            echo '<td>'.$item['called_count'].'</td>';
            echo '<td>'.$item['tian_call_count'].'</td>';
            echo '<td>'.$item['tian_called_count'].'</td>';
            echo '<td>'.$item['end_cc_count'].'</td>';
            echo '<td>'.$item['end_c_count'].'</td>';
            echo '<td>'.$item['first_called_cc'].'</td>';
            echo '<td>'.$item['end_first_called'].'</td>';
            echo '<td>'.$item['first_called_time_long'].'</td>';
            echo '<td>'.$item['second_called_cc'].'</td>';
            echo '<td>'.$item['end_second_called'].'</td>';
            echo '<td>'.$item['second_called_time_long'].'</td>';
            echo '<td>'.$item['add_time'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function actual_call_threshold(){
        $ret = [];
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $ret_info = $this->t_seller_edit_log->get_threshold_list($start_time, $end_time);
        foreach($ret_info as $key=>$item){
            if($item['type'] == 6){
                $ret[$key]['time'] = date('H:i',$item['create_time']);
                $ret[$key]['threshold'] = $item['new'];
            }elseif($item['type'] == 4){
                $threshold_max = $item['new'];
            }elseif($item['type'] == 5){
                $threshold_min = $item['new'];
            }
        }
        foreach($ret as &$item){
            $item['threshold_min'] = $threshold_min;
            $item['threshold_max'] = $threshold_max;
        }

        $list_info = [];
        $list = $this->t_seller_get_new_log->get_all_list($start_time,$end_time);
        foreach($list as $item){
            $list_info[$item['userid']]['userid'] = isset($list_info[$item['userid']]['userid'])?$list_info[$item['userid']]['userid']:$item['userid'];
            $list_info[$item['userid']]['list'][$item['adminid']] = isset($list_info[$item['userid']]['list'][$item['adminid']])?$list_info[$item['userid']]['list'][$item['adminid']]:$item;
            $list_info[$item['userid']]['add_time'] = isset($list_info[$item['userid']]['add_time'])?$list_info[$item['userid']]['add_time']:date('Y-m-d H:i:s',$item['add_time']);
        }
        $num = 0;
        foreach($list_info as $userid=>$item){
            $num++;
            $list_info[$userid]['num'] = $num;
            foreach($item['list'] as $adminid=>$info){
                $list_info[$userid]['list'][$adminid]['account'] = $this->cache_get_account_nick($adminid);
                $list_info[$userid]['list'][$adminid]['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
                $list_info[$userid]['list'][$adminid]['cc_end'] = $info['cc_end']==1?'客户':'销售';
            }
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list_info),['data_ex_list'=>$ret]);
    }
}
