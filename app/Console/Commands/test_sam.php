<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_sam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_sam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test_sam';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        echo "姓名|电话";
        echo PHP_EOL;
        echo "1123213123|aklfjalksdfj";


        exit;
        */
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        

        /*
        $ret_info = $task->t_student_score_info->get_grade_by_info_b();
        foreach ($ret_info as $key => &$value) {
            # code...
            $value['grade']   = E\Egrade::get_desc($value['grade']);
            $value['phone_location'] = \App\Helper\Utils::phone_location_to_province($value['phone_location']);
        }
        $result = array();  
        foreach($ret_info as $val){  
            $key = $val['grade'].'_'.$val['phone_location'];  
            if(!isset($result[$key])){  
                $result[$key] = $val;  
            }else{  
                $result[$key]['num'] += $val['num'];  
            }  
        }  
        $ret = array_values($result);
        $file_name = 'sam_little';
        $arr_title = ['年级',"省份","数量"];
        $arr_data  = ['grade','phone_location','num'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret,$arr_title,$arr_data);
        dd($ret_file_name);

        dd($ret);
        */

        /*
        $ret_info1 = $task->t_student_score_info->get_total_student_b1();
        $ret_info2 = $task->t_student_score_info->get_total_student_b2();
        $ret_info3 = $task->t_student_score_info->get_total_student_b3();
        $ret_info4 = $task->t_student_score_info->get_total_student_b4();
        $ret_info5 = $task->t_student_score_info->get_total_student_b5();
        $ret_info6 = $task->t_student_score_info->get_total_student_b6();
        dd($ret_info1,$ret_info2,$ret_info3,$ret_info4,$ret_info5,$ret_info6);
        */
        

        
        // $time = [
        //     ['start_time' => 1506787200,'end_time' => 1509465600], //10
        //     ['start_time' => 1509465600,'end_time' => 1512057600], //11
        //     ['start_time' => 1512057600,'end_time' => 1514736000], //12
        // ];
        // foreach ($time as $key => $value) {
        //     $start_time = $value['start_time'];
        //     $end_time   = $value['end_time'];
        //     $ret_info = $task->t_student_score_info->get_info_by_month($start_time,$end_time);
        //     foreach ($ret_info as $kkey => &$vvalue) {
        //         $vvalue['subject'] = E\Esubject::get_desc($vvalue['subject']);
        //         $vvalue['grade']   = E\Egrade::get_desc($vvalue['grade']);
        //         $vvalue['phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['phone_location']);
        //     }
            

        //     $res = array();
        //     foreach($ret_info as $item) {
        //         $flag = false;
        //         foreach ($res as $key => $value) {
        //             if($item['subject'] == $value['subject'] && $item['grade'] == $value['grade'] 
        //                 && $item['phone_location'] == $value['phone_location']) {
        //                 $flag = true;
        //                 $value['num'] = $value['num'] + $item['num'];
        //                 break;
        //             }
        //             else {
        //                 $flag = false;
        //             }
        //         }
        //         if(!$flag){
        //             $res[] = $item;
        //         }  
        //     }
        //     //var_dump(date("Y-m-d",$start_time), date("Y-m-d",$end_time));
        //     $month = date("Y-m",$start_time);
        //     $file_name = $month.'sam_subject_grade_phone_location';
        //     $arr_title = ['科目',"年级","省份","数量"];
        //     $arr_data  = ['subject','grade','phone_location','num'];
        //     //$ret_file_name = \App\Helper\Utils::download_txt($file_name,$res,$arr_title,$arr_data);
        // }
        /*
        $time = [
            ['start_time' => 1506787200,'end_time' => 1509465600], //10
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
        ];
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $ret_info = $task->t_student_score_info->get_info_by_month_b2($start_time,$end_time);
            echo date("Y-m",$start_time).PHP_EOL;
            foreach ($ret_info as $kkey => &$vvalue) {
                $vvalue['teacher']     = $task->cache_get_teacher_nick($vvalue["teacherid"]);
                $vvalue['grade_start'] = E\Egrade_range::get_desc($vvalue['grade_start']);
                $vvalue['grade_end']   = E\Egrade_range::get_desc($vvalue['grade_end']);
                $vvalue['grade_range'] = $vvalue['grade_start'].'~'.$vvalue['grade_end']; 
                $vvalue['phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['phone_location']);
                echo $task->cache_get_teacher_nick($vvalue["teacherid"]).' '.$vvalue['grade_start'].'~'.$vvalue['grade_end'].' '.$vvalue['phone_location'].' '.E\Esubject::get_desc($vvalue['subject']).' '.E\Esubject::get_desc($vvalue['second_subject']).PHP_EOL;
            }
            
            //dd($ret_info);
            //var_dump(date("Y-m-d",$start_time), date("Y-m-d",$end_time));
            // $month = date("Y-m",$start_time);
            // $file_name = "sam-".$month;
            // $arr_title = ['老师姓名',"年级段","省份"];
            // $arr_data  = ['teacher','grade_range','phone_location'];
            // $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        }
        exit;
        */
        /*
        $time = [
            ['start_time' => 1506787200,'end_time' => 1509465600], //10
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
        ];
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $ret_info = $task->t_student_score_info->get_info_by_month_b3($start_time,$end_time);
            foreach ($ret_info as $kkey => &$vvalue) {
                $vvalue['subject']= E\Esubject::get_desc($vvalue['subject']);
                $vvalue['grade']  = E\Egrade::get_desc($vvalue['grade']);
                $vvalue['phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['phone_location']);

                $vvalue['origin'] = E\Eaccount_role::get_desc($vvalue['require_admin_type']);


                $vvalue['grade_start'] = E\Egrade_range::get_desc($vvalue['grade_start']);
                $vvalue['grade_end']   = E\Egrade_range::get_desc($vvalue['grade_end']);
                $vvalue['grade_range'] = $vvalue['grade_start'].'~'.$vvalue['grade_end']; 
                $vvalue['teacher_phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['teacher_phone_location']);
            }
            //dd($ret_info);
            //var_dump(date("Y-m-d",$start_time), date("Y-m-d",$end_time));
            $month = date("Y-m",$start_time);
            $file_name = "sam123-".$month;
            $arr_title = ['学生姓名',"学科","年级","省份","来源","老师姓名","年级段","老师省份"];
            $arr_data  = ['nick','grade','subject','phone_location','origin','teacher_name',"grade_range","teacher_phone_location"];
            $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        }
        */
        /*
        $phone_arr = [];
        $file_name = "a";
        if(\App\Helper\Utils::check_env_is_local()){
            $path = "/home/sam/".$file_name.".txt";
        }else{
            $path = "/home/ybai/".$file_name.".txt";
        }
        $fp = fopen($path,"r");
        while(!feof($fp)){
            //$content = fread($handle, 8080);
            $buffer  = fgets($fp, 4096);
            $buffer  = trim($buffer);
            $ret = $task->t_phone_info->main_insert($buffer);
            print($ret)+'<br/>';
        }
        dd("finish");                                                                        

        */  
        // $ret_info = $task->t_student_score_info->get_all_student_phone_and_id();

        // foreach ($ret_info as $key => $value) {
        //     $userid = $value['userid'];
        //     $phone  = intval(trim($value['phone']));
        //     $num = substr($phone, 0,7);
        //     $ret = $task->t_student_score_info->get_province_info($num);
        //     if($ret){
        //         $province = $ret['province'];
        //         $city     = $ret['city'];
        //     }else{
        //         $province = "其它";
        //         $city     = "其它";
        //     }
        //     $task->t_student_info->field_update_list($userid,[
        //         "phone_province" =>$province,
        //         "phone_city" =>$city,
        //     ]);
        //     echo "$userid $province  $city.fin\n";
        // }   
        /*
        $ret_info = $task->t_student_score_info->get_grade_by_info_1();
        foreach ($ret_info as $key => &$value) {
            $value['grade']   = E\Egrade::get_desc($value['grade']);
            $value['address'] = $value['phone_city'];
        }
        $file_name = 'sam_1';
        $arr_title = ['年级',"城市","数量"];
        $arr_data  = ['grade','phone_city','num'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        dd($ret_file_name);
        */

        /*
        $ret_info = $task->t_student_score_info->get_b3();
        foreach ($ret_info as $key => &$value) {
            $value['grade']   = E\Egrade::get_desc($value['grade']);
            //$value['seller_student_status'] = E\Eseller_student_status::get_desc($value['seller_student_status']);
            $value['test_lesson_order_fail_flag'] = E\Etest_lesson_order_fail_flag::get_desc($value['test_lesson_order_fail_flag']);
        }
        $file_name = 'sam_2_3';
        $arr_title = ['年级',"分类","数量"];
        $arr_data  = ['grade','test_lesson_order_fail_flag','num'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        */
        /*
        $ret_info = $task->t_student_score_info->get_b4();
        $ret = [];
        foreach ($ret_info as $key => &$value) {
            $value['grade']   = E\Egrade::get_desc($value['grade']);
            $time = time() - $value['max_time'];
            if($time > 31535000){
                $value['max_time'] = 0;
            }elseif($time > 15552000){
                $value['max_time'] = 1;
            }elseif($time > 7776000){
                $value['max_time'] = 2;
            }elseif($time > 2592000 && $time < 5184000){
                $value['max_time'] = 3;
            }elseif($time < 2592000){
                $value['max_time'] = 4;
            }else{
                $value['max_time'] = -1;
            }
        }
        foreach ($ret_info as $key => $value) {
            $ret[$value['grade']][$value['max_time']] = isset($ret[$value['grade']][$value['max_time']]) ? $ret[$value['grade']][$value['max_time']] + 1 : 1;
        }

        dd($ret);
        $file_name = 'sam_3';
        $arr_title = ['年级',"分类","数量"];
        $arr_data  = ['grade','1','num'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        */
        /*
    select s.userid, q.is_called_phone
from db_weiyi.t_student_info s
left join db_weiyi_admin.t_tq_call_info q on s.phone =q.phone 
where s.is_test_user = 0 and q.is_called_phone =1 

        */
        /*
        
        $time = [
            ['start_time' => 1506787200,'end_time' => 1509465600], //10
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
            ['start_time' => 1514736000,'end_time' => 1517414400], //1
        ];

        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            //$ret = $task->t_student_score_info->get_xx($start_time,$end_time);
            $ret = $task->t_student_score_info->get_zz($start_time,$end_time);
            echo date("Y-m",$start_time)."-".$ret['total']."- ".$ret['one_total'].'- '.$ret['two_total'].'- '.$ret['three_total']."\n";
        }
        
        */

        /*

        $ret_info = $task->t_student_score_info->get_all_teacher_phone_and_id();
        foreach ($ret_info as $key => $value) {
            $userid = $value['teacherid'];
            $phone  = intval(trim($value['phone']));
            $num = substr($phone, 0,7);
            $ret = $task->t_student_score_info->get_province_info($num);
            if($ret){
                $province = $ret['province'];
                $city     = $ret['city'];
            }else{
                $province = "其它";
                $city     = "其它";
            }
            $task->t_teacher_info->field_update_list($userid,[
                "phone_province" =>$province,
                "phone_city" =>$city,
            ]);
            echo "$userid $province  $city.fin\n";
        }   
        */
        /*
        $ret_info = $task->t_student_score_info->get_all_infoxxx();
        $file_name = 'sam_0119-1';
        $arr_title = ['ID',"省份","城市"];
        $arr_data  = ['nick','phone_province','phone_city'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);

        */
        /*
        $ret_info = $task->t_student_score_info->get_all_infoxxx2();
        $file_name = 'sam_0119-2';
        $arr_title = ['ID',"省份","城市"];
        $arr_data  = ['nick','phone_province','phone_city'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        dd($ret_file_name);


        $time = [
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
            ['start_time' => 1514736000,'end_time' => 1517414400], //1
        ];

        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            //$ret = $task->t_student_score_info->get_xx($start_time,$end_time);
            $ret      = $task->t_student_score_info->get_abcd($start_time,$end_time);
            $ret_info = $task->t_student_score_info->get_ae($start_time,$end_time);
            foreach(@$ret as $kkey => &$kvalue) {
                $kvalue['test'] = 0;
                foreach(@$ret_info as $vkey => $vvalue) {
                    if($kvalue['phone_province'] == $vvalue['phone_province'] &&
                       $kvalue['phone_city']     == $vvalue['phone_city']
                    ){
                        $kvalue['test'] = $vvalue['total'];
                    }
                }   
            }
            $month = date("Y-m",$start_time);
            $file_name = 'sam_0124-'.$month;
            $arr_title = ["省份","城市","1",""];
            $arr_data  = ['phone_province','phone_city',"total","test"];

            $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret,$arr_title,$arr_data);
            
            
        }
        */ 
        // $ret = $task->t_student_score_info->get_num();
        // foreach ($ret as $key => &$value) {
        //     $value["adminid_nick"]= $value["adminid"]>0?$task->cache_get_account_nick($value["adminid"]):'';
        //     $value["uid_nick"]= $task->cache_get_account_nick($value["uid"]);
        //     $value['adminid_account_role'] = E\Eaccount_role::get_desc($value['account_role']);
        //     $value['uid_account_role']     = E\Eaccount_role::get_desc($value['uid_account_role']);

        //     $value['create_time_str'] = \App\Helper\Utils::unixtime2date($value['create_time']);
        // }
        // $file_name = 'sam_0208-';
        // $arr_title = ["用户ID","最后一次分配例子的时间","分配人（操作人)","分配人account","被分配人","被分配人account"];
        // $arr_data  = ['userid','create_time_str',"adminid_nick","adminid_account_role","uid_nick","uid_account_role"];
        // $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret,$arr_title,$arr_data);


        // $ret = $task->t_student_score_info->get_num();
        // foreach ($ret as $key => $value) {
        //     $adminid = $value['adminid'];
        //     $file_id = $value['file_id'];
        //     $kpi_adminid = $value['kpi_adminid'];
        //     $reload_adminid = $value['reload_adminid'];

        //     if($reload_adminid == 0){
        //         $task->t_resource_file->field_update_list($file_id,[
        //             "reload_adminid" =>$adminid,
        //         ]);
        //     }
        //     if($kpi_adminid == 0){
        //         $task->t_resource_file->field_update_list($file_id,[
        //             "kpi_adminid" =>$adminid,
        //         ]);
        //     }

        $ret = $task->t_student_score_info->get_num_t2();
        $ret2 = $task->t_student_score_info->get_num_t3();
        $arr  = [];

        foreach ($ret2 as $key => $value) {
            $arr[$value['userid']] = 1;
        }
        foreach ($ret as $key => $value) {
            if($value['time'] >= 1517414400){
                $arr[$value['userid']] = 0;
            }
        }
        $arr_info = [];
        foreach ($ret as $key => $value) {
            if( isset($arr[$value['userid']]) && @$arr[$value['userid']] > 0 && $value['time'] < 1517414400){
                $arr_info[$value['userid']][$value['teacherid']] = 1;
            }
        }

        $test = [];
        foreach ($arr_info as $key => $value) {
            $data['userid'] = $key;
            $data['username'] = $task->t_student_info->get_nick($key);
            foreach ($value as $kkey => $kvalue) {
                $data['teacherid'] = $kkey;
                $data['teacher_name'] = $task->t_teacher_info->get_nick($kkey);
            }

            $test[] = $data;
        }
        $file_name = 'sam_0301';
        $arr_title = ["学生ID","学生姓名","老师ID","老师姓名"];
        $arr_data  = ['userid','username',"teacherid","teacher_name"];

        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$test,$arr_title,$arr_data);
        dd($test);



        
    }     
}
