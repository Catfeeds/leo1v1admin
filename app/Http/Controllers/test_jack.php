<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_jack  extends Controller
{
    use CacheNick;
    use TeaPower;
    public function test_kk(){
        $file = fopen("/home/ybai/1.csv","r");
        $goods_list=[];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            if($i>=2 && $i<24){
                $goods_list[] = $data; 
            }
            $i++;
        }
        foreach($goods_list as &$item){
            foreach($item as $k=>&$val){
                
                if(in_array($k,[1,2,5,6])){
                    $arr = explode(",",$val);
                    $str="";
                    foreach($arr as $t){
                        $str .= $t;
                    }
                    $str = $str *100;
                    $item[$k] = $str;
                }elseif($k==0){
                    $arr = explode("年",$val);
                    $arr_2 = $arr[1];
                    $arr_3 = explode("月",$arr_2);

                    $year = $arr[0];
                    $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
                    $date = $year."-".$month."-01";
                    $item[$k]=strtotime($date);
                }
            }
            
        }

        // foreach($goods_list as $p_item){
        //     $this->t_admin_corporate_income_list->row_insert([
        //         "month"  =>$p_item[0],
        //         "new_order_money"=>$p_item[1],
        //         "renew_order_money"=>$p_item[2],
        //         "new_order_stu"=>$p_item[3],
        //         "renew_order_stu"=>$p_item[4],
        //         "new_signature_price"=>$p_item[5],
        //         "renew_signature_price"=>$p_item[6],
        //     ]);
        // }
        fclose($file); 
        
    }

    public function test_tt(){
        $file = fopen("/home/ybai/2.csv","r");
        $goods_list=[];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            if($i>=1){
                $goods_list[] = $data;
            }
            $i++;
        }
        foreach($goods_list as &$val){
            foreach($val as $k=>&$v){
                if(in_array($k,[0,2])){
                    $arr= explode("\n",$v);
                    if($k==0){
                        $nick = trim($arr[0]);
                        $phone = trim(@$arr[1]);
                        $val[$k]=$nick;
                        $val[100] = $phone;

                    }else{
                        $str ="";
                        foreach($arr as $r){
                            $str .= trim($r).",";
                        }
                        $val[$k] = trim($str,",");
                    }
                }
            }
        }
        foreach($goods_list as $p_item){
            $this->t_admin_refund_order_list->row_insert([
                "nick"   =>$p_item[0],
                "phone"   =>$p_item[100],
                "grade"   =>$p_item[1],
                "order_custom"   =>$p_item[2],
                "sys_operator"   =>$p_item[3],
                "order_time"   =>strtotime($p_item[4]),
                "contract_type"   =>$p_item[5],
                "lesson_total"   =>$p_item[6]*100,
                "refund_lesson_count"   =>$p_item[7]*100,
                "order_cost_price"   =>$p_item[8]*100,
                "order_price"   =>$p_item[9]*100,
                "refund_price"   =>$p_item[10]*100,
                "is_invoice"   =>$p_item[11],
                "invoice"   =>$p_item[12],
                "payment_account_id"   =>$p_item[13],
                "refund_info"   =>$p_item[14],
                "save_info"   =>$p_item[15],
                "apply_account"   =>$p_item[17],
                "apply_time"   =>strtotime($p_item[16]),
                "approve_status"   =>$p_item[18],
                "approve_time"   =>$p_item[19]=="无"?0:strtotime($p_item[19]),
                "refund_status"   =>$p_item[20],
                "period_flag"   =>$p_item[21],
                "assistant_name"   =>$p_item[22],
                "subject"   =>$p_item[23],
                "teacher_realname"   =>$p_item[24],
                "connection_state"   =>$p_item[25],
                "lifting_state"   =>$p_item[26],
                "learning_attitude"   =>$p_item[27],
                "order_three_month_flag"   =>$p_item[28],
                "assistant_one_level_cause"   =>$p_item[29],
                "assistant_two_level_cause"   =>$p_item[30],
                "assistant_three_level_cause"   =>$p_item[31],
                "assistant_deduction_value"   =>$p_item[32],
                "assistant_cause_analysis"   =>$p_item[33],
                "registrar_one_level_cause"   =>$p_item[34],
                "registrar_two_level_cause"   =>$p_item[35],
                "registrar_three_level_cause"   =>$p_item[36],
                "registrar_deduction_value"   =>$p_item[37],
                "registrar_cause_analysis"   =>$p_item[38],
                "teacher_manage_one_level_cause"   =>$p_item[39],
                "teacher_manage_two_level_cause"   =>$p_item[40],
                "teacher_manage_three_level_cause"   =>$p_item[41],
                "teacher_manage_deduction_value"   =>$p_item[42],
                "teacher_manage_cause_analysis"   =>$p_item[43],
                "dvai_one_level_cause"   =>$p_item[44],
                "dvai_two_level_cause"   =>$p_item[45],
                "dvai_three_level_cause"   =>$p_item[46],
                "dvai_deduction_value"   =>$p_item[47],
                "dvai_cause_analysis"   =>$p_item[48],
                "product_one_level_cause"   =>$p_item[49],
                "product_two_level_cause"   =>$p_item[50],
                "product_three_level_cause"   =>$p_item[51],
                "product_deduction_value"   =>$p_item[52],
                "product_cause_analysis"   =>$p_item[53],
                "advisory_one_level_cause"   =>$p_item[54],
                "advisory_two_level_cause"   =>$p_item[55],
                "advisory_three_level_cause"   =>$p_item[56],
                "advisory_deduction_value"   =>$p_item[57],
                "advisory_cause_analysis"   =>$p_item[58],
                "customer_changes_one_level_cause"   =>$p_item[59],
                "customer_changes_two_level_cause"   =>$p_item[60],
                "customer_changes_three_level_cause"   =>$p_item[61],
                "customer_changes_deduction_value"   =>$p_item[62],
                "customer_changes_cause_analysis"   =>$p_item[63],
                "teacher_one_level_cause"   =>$p_item[64],
                "teacher_two_level_cause"   =>$p_item[65],
                "teacher_three_level_cause"   =>$p_item[66],
                "teacher_deduction_value"   =>$p_item[67],
                "teacher_cause_analysis"   =>$p_item[68],
                "subject_one_level_cause"   =>$p_item[69],
                "subject_two_level_cause"   =>$p_item[70],
                "subject_three_level_cause"   =>$p_item[71],
                "subject_deduction_value"   =>$p_item[72],
                "subject_cause_analysis"   =>$p_item[73],
                "other_cause"   =>$p_item[74],
                "quality_control_global_analysis"   =>$p_item[75],
                "later_countermeasure"   =>$p_item[76],
                "assistant_cause_rate"   =>$p_item[77],
                "registrar_cause_rate"   =>$p_item[78],
                "teacher_manage_cause_rate"   =>$p_item[79],
                "dvai_cause_rate"   =>$p_item[80],
                "product_cause_rate"   =>$p_item[81],
                "advisory_cause_rate"   =>$p_item[82],
                "customer_changes_cause_rate"   =>$p_item[83],
                "teacher_cause_rate"   =>$p_item[84],
                "subject_cause_rate"   =>$p_item[85],
               
            ]);
        }
        fclose($file); 
        
    }

    public function test_yy(){
        $file = fopen("/home/ybai/3.csv","r");
        $goods_list=[];
        $first_list = [];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
            if($i==0){
                $first_list = $data;
            }
            $i++;
        }
        $num = count($first_list);
        $list=[];
        $j=1;
        foreach($goods_list as $val){
            foreach($val as $k=>$v){
                $list[$k][$j]=$v; 
            }
            $j++;
        }

        foreach($list as &$item){
            $arr = explode("年",$item[1]);
            $arr_2 = $arr[1];
            $arr_3 = explode("月",$arr_2);

            $year = $arr[0];
            $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
            $date = $year."-".$month."-01";
            $item[1]=strtotime($date);
            $this->t_admin_student_month_info->row_insert([
                "month" =>$item[1],
                "begin_stock" =>$item[2],
                "increase_num" =>$item[3],
                "end_num" =>$item[4],
                "refund_num" =>$item[5],
                "end_stock" =>$item[6],
                "no_lesson_num" =>$item[7],
                "end_read_num" =>$item[8],
                "three_end_num" =>$item[9],
                "expiration_renew_num" =>$item[10],
                "early_renew_num" =>$item[11],
                "end_renew_num" =>$item[12],
                "actual_renew_rate" =>$item[13],
                "actual_renew_rate_three" =>$item[14],
            ]);

        }
        // dd($list);

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function test_xx(){
        $file = fopen("/home/ybai/4.csv","r");
        $goods_list=[];
        $first_list = [];
        $i=0; 
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
            if($i==0){
                $first_list = $data;
            }
            $i++;
        }
        $num = count($first_list);
        $list=[];
        $j=1;
        foreach($goods_list as $val){
            foreach($val as $k=>$v){
                $list[$k][$j]=$v; 
            }
            $j++;
        }
        foreach($list as &$item){
            $arr = explode("年",$item[1]);
            $arr_2 = $arr[1];
            $arr_3 = explode("月",$arr_2);

            $year = $arr[0];
            $month = $arr_3[0]>=10?$arr_3[0]:"0".$arr_3[0];
            $date = $year."-".$month."-01";
            $item[1]=strtotime($date);
            $this->t_admin_student_month_info->field_update_list($item[1],[
                "test_chinese_num" =>$item[2],
                "test_math_num" =>$item[3],
                "test_english_num" =>$item[4],
                "test_minor_subject_num" =>$item[5],
                "test_all_subject_num" =>$item[6],
                "increase_chinese_num" =>$item[7],
                "increase_math_num" =>$item[8],
                "increase_english_num" =>$item[9],
                "increase_minor_subject_num" =>$item[10],
                "increase_all_subject_num" =>$item[11],
                "increase_test_rate" =>$item[12],
                "read_chinese_num" =>$item[13],
                "read_math_num" =>$item[14],
                "read_english_num" =>$item[15],
                "read_minor_subject_num" =>$item[16],
                "read_all_subject_num" =>$item[17],
            ]);

        }

        dd($list);

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function test_zz(){
        $file = fopen("/home/ybai/5.csv","r");
        $goods_list=[];
        $first_list = [];
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            // print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
        }

        foreach($goods_list as &$val){
            $arr = explode("月",$val[0]);
            $month = $arr[0]>=10?$arr[0]:"0".$arr[0];

            $date = "2017-".$month."-01";
            $val[0]=strtotime($date);
            $this->t_order_student_month_list->row_insert([
                "month" =>$val[0],
                "origin" =>$val[1],
                "leads_num" =>$val[2],
                "test_num" =>$val[3],
                "test_transfor_per" =>$val[4],
                "order_transfor_per" =>$val[6],
                "order_stu_num" =>$val[5],
            ]);



        }
        dd($goods_list);

        // print_r($goods_list);
        fclose($file); 
        
    }

    public function test_period(){
        $start_time = strtotime("2017-09-01");
        $end_time = strtotime("2017-10-01");
        $contract_type = $this->get_in_int_val("contract_type",0);
        $order_info = $this->t_order_info_finance->get_order_info($start_time,$end_time,$contract_type);
        // $order_info_t = $this->t_order_info_finance->get_order_tongji_info($start_time,$end_time,$contract_type);
        $arr=[];
        $money=0;
        foreach($order_info as $val){
            if($val["price"]>1360000 && $val["price"]<2210000){
                $money +=$val["price"];
                if(!isset($arr[$val["userid"]])){
                    $arr[$val["userid"]]=$val["userid"];
                }

                // $val["order_time"] = strtotime("+1 months",$val["order_time"]);
                // $val["parent_order_id"] = 3000;

                if(count($arr) >= 59){
                    break;
                }
 
            }
        }
        // dd([$order_info,$order_info_t]);
        dd([$arr,$money]);
        // $time = time()-7*86400;
        dd(date("w",time()));
        $day_time = strtotime("2017-10-09");
        $check_holiday = $this->t_fulltime_teacher_attendance_list->check_is_in_holiday(231463,$day_time);
        dd($check_holiday);
        //节假日延休        
        $festival_info = $this->t_festival_info->get_festival_info_by_end_time($day_time);
        if($festival_info){
            $attendance_day = $day_time+86400;
            $lesson_info = $this->t_lesson_info_b2->get_qz_tea_lesson_info_b2($festival_info["begin_time"],$attendance_day);
            $list=[];
            foreach($lesson_info as $val){
                if($val["lesson_type"]==1100 && $val["train_type"]==5){
                    @$list[$val["uid"]] += 0.8;
                }elseif($val["lesson_type"]==2){
                    @$list[$val["uid"]] += 1.5;
                }else{
                    @$list[$val["uid"]] += $val["lesson_count"]/100;
                }
            }
            $arr = [];
            foreach ($list as $key => $value) {
                $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($key);
                $teacherid = $teacher_info["teacherid"];
                $realname = $this->t_teacher_info->get_realname($teacherid);
                @$arr[$key]['teacherid'] = $teacherid;
                @$arr[$key]['realname']  = $this->t_teacher_info->get_realname($teacherid);
                @$arr[$key]['lesson_count'] = $value;
                @$arr[$key]['day_num'] = floor($value/10.5);
                @$arr[$key]['attendance_time'] = $attendance_day;
                @$arr[$key]['holiday_end_time'] = $attendance_day+($arr[$key]['day_num']-1)*86400;
                if($arr[$key]['day_num'] == 0){
                    @$arr[$key]['cross_time'] = "";
                }else{
                    @$arr[$key]['cross_time'] = date('m.d',$attendance_day)."-".date('m.d',$arr[$key]['holiday_end_time']);
                }
              
            }
            //insert data
            // foreach ($arr as $key => $value) {
            //     if($value['day_num']>=1){
            //         $task->t_fulltime_teacher_attendance_list->row_insert([
            //             "teacherid"        =>$value['teacherid'],
            //             "add_time"         =>$time,
            //             "attendance_type"  =>3,
            //             "attendance_time"  =>$value["attendance_time"],
            //             "day_num"          =>$value['day_num'],
            //             "adminid"          =>$key,
            //             "lesson_count"     =>$value['lesson_count']*100,
            //             "holiday_end_time" =>$value["holiday_end_time"],
            //         ]);
            //     } 
            // }
            //wx
            foreach ($arr as $key => $value) {
                $this->t_manager_info->send_wx_todo_msg_by_adminid (
                    349,
                    $festival_info["name"]."延休统计",
                    "延休数据汇总",
                    "\n老师:".$value['realname'].
                    "\n时间:2017-10-1 0:0:0 ~ 2017-10-8 22:0:0".
                    "\n累计上课课时:".$value['lesson_count'].
                    "\n延休天数:".$value['day_num'].
                    "\n延休日期:".$value['cross_time'],'');
            }
            $namelist = '';
            $num = 0;
            foreach ($arr as $key => $value) {
                if($value['day_num'] != 0){
                    $namelist .= $value['realname'];
                    $namelist .= ',';
                    ++$num;
                }
            }
            $namelist = trim($namelist,',');
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //erick
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349, $festival_info["name"]."延休统计","全职老师".$festival_info["name"]."延休安排情况如下","如下".$num."位老师满足条件,具体名单如下:".$namelist,""); //low-key

            //email
            $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="4">全职老师假期累计上课时间及延休安排</td></tr>';
            $table .= '<tr><td>假期名称</td><td colspan="3" align="center"><font color="red">'.$festival_info["name"].'</font></td></tr>';
            $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
            foreach ($arr as $key => $value) {
                if($value['day_num'] != 0){
                    $table .= '<tr>';
                    $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                    $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                    $table .= '</tr>';
                }
            }
            $table .= "</table>";
            $content = "Dear all：<br>全职老师".$festival_info["name"]."延休安排情况如下<br/>";
            $content .= "数据见下表<br>";
            $content .= $table;
            $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优教育</div><div>";
            // $email_arr = ["low-key@leoedu.com",
            //               "erick@leoedu.com",
            //               "hejie@leoedu.com",
            //               "sherry@leoedu.com",
            //               "cindy@leoedu.com",
            //               "limingyu@leoedu.com"];
            $email_arr = ["jack@leoedu.com"];

            foreach($email_arr as $email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,
                    "全职老师".$festival_info["name"]."假期累计上课时间及延休安排",
                    $content
                ));  
            }

        }
        dd(1111);



       
        $lesson_end = $this->get_in_str_val("lesson_end","2017-11-23 09:00:00");
        $lesson_end = strtotime($lesson_end);
        $day_time = strtotime(date("Y-m-d",$lesson_end));
        $begin_time = $day_time+9.5*3600;
        $list = $this->t_lesson_info_b2->get_delay_work_time_lesson_info($day_time,$lesson_end);
        $i=0;
        foreach($list as $item){
            $teacherid = $item["teacherid"];
            if($item["lesson_type"]==2){
                $lesson_end = $item["lesson_end"]+1200;
            }else{
                $lesson_end = $item["lesson_end"];
            }
            echo $i."<br>";
            $i++;
            $id = $this->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
            $attendance_type = $this->t_fulltime_teacher_attendance_list->get_attendance_type($id);
            if($id>0 && $attendance_type==2){
                $end = $this->get_last_lesson_end($teacherid,$lesson_end);
                $delay_time = $end+5400;
                if($delay_time>$begin_time){
                    $this->t_fulltime_teacher_attendance_list->field_update_list($id,[
                        "delay_work_time" =>$delay_time,
                    ]);
                }
                echo $delay_time."111<br>";
            }elseif(empty($id)){
                $end = $this->get_last_lesson_end($teacherid,$lesson_end);
                $delay_time = $end+5400;
                if($delay_time>$begin_time){
                    $this->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>time(),
                        "attendance_type" =>2,
                        "attendance_time"  =>$day_time,
                        "delay_work_time"         =>$delay_time,
                        "adminid"          =>$item["uid"]
                    ]);

                }
                echo $delay_time."222<br>";


            }
 
        }
        dd($list);
        $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
        $lesson_start = $lesson_end+1800;
        $lesson_list = $this->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
        dd($lesson_list);
        dd(111);

       


    }






   
}

