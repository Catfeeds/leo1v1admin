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
        $ret_info1 = $task->t_student_score_info->get_total_student_b1();
        $ret_info2 = $task->t_student_score_info->get_total_student_b2();
        $ret_info3 = $task->t_student_score_info->get_total_student_b3();
        $ret_info4 = $task->t_student_score_info->get_total_student_b4();
        $ret_info5 = $task->t_student_score_info->get_total_student_b5();
        $ret_info6 = $task->t_student_score_info->get_total_student_b6();
        dd($ret_info1,$ret_info2,$ret_info3,$ret_info4,$ret_info5,$ret_info6);
        */
        
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
            dd($res);
            print_r(array_values($res));

            $file_name = '10';
            $arr_title = ['科目',"年级","省份","数量"];
            $arr_data  = ['userid','origin_userid','is_money'];
            $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
            dd($ret_file_name);
        }
        
    }
}
