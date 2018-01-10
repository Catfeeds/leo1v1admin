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
        

        /*
        $time = [
            ['start_time' => 1506787200,'end_time' => 1509465600], //10
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
        ];
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $ret_info = $task->t_student_score_info->get_info_by_month($start_time,$end_time);
            foreach ($ret_info as $kkey => &$vvalue) {
                $vvalue['subject'] = E\Esubject::get_desc($vvalue['subject']);
                $vvalue['grade']   = E\Egrade::get_desc($vvalue['grade']);
                $vvalue['phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['phone_location']);
            }
            

            $res = array();
            foreach($ret_info as $item) {
                $flag = false;
                foreach ($res as $key => $value) {
                    if($item['subject'] == $value['subject'] && $item['grade'] == $value['grade'] 
                        && $item['phone_location'] == $value['phone_location']) {
                        $flag = true;
                        $value['num'] = $value['num'] + $item['num'];
                        break;
                    }
                    else {
                        $flag = false;
                    }
                }
                if(!$flag){
                    $res[] = $item;
                }  
            }
            //var_dump(date("Y-m-d",$start_time), date("Y-m-d",$end_time));
            $month = date("Y-m",$start_time);
            $file_name = $month.'sam_subject_grade_phone_location';
            $arr_title = ['科目',"年级","省份","数量"];
            $arr_data  = ['subject','grade','phone_location','num'];
            //$ret_file_name = \App\Helper\Utils::download_txt($file_name,$res,$arr_title,$arr_data);
        }
        */

        $time = [
            ['start_time' => 1506787200,'end_time' => 1509465600], //10
            ['start_time' => 1509465600,'end_time' => 1512057600], //11
            ['start_time' => 1512057600,'end_time' => 1514736000], //12
        ];
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $ret_info = $task->t_student_score_info->get_info_by_month_b2($start_time,$end_time);
            foreach ($ret_info as $kkey => &$vvalue) {
                $vvalue['teacher']     = $task->cache_get_teacher_nick($vvalue["teacherid"]);
                $vvalue['grade_start'] = E\Egrade_range::get_desc($vvalue['grade_start']);
                $vvalue['grade_end']   = E\Egrade_range::get_desc($vvalue['grade_end']);
                $vvalue['grade_range'] = $vvalue['grade_start'].'~'.$vvalue['grade_end']; 
                $vvalue['phone_location'] = \App\Helper\Utils::phone_location_to_province($vvalue['phone_location']);
            }
            //dd($ret_info);
            //var_dump(date("Y-m-d",$start_time), date("Y-m-d",$end_time));
            $month = date("Y-m",$start_time);
            $file_name = "sam-".$month;
            $arr_title = ['老师姓名',"年级段","省份"];
            $arr_data  = ['teacher','grade_range','phone_location'];
            $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        }
        
    }
}
