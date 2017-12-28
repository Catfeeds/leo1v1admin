<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\report;

class week_report extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:week_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '周报数据统计';

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
     * @desn:每周三调用生成上一个周二到本周二的数据
     * @return mixed
     */
    public function handle()
    {
        $time_now = date('l');
        // if($time_now == 'Wednesday'){
            $start_time = strtotime('- 2 Tuesday');
            $end_time = strtotime('+ 7 day',$start_time);
            $data_arr = $this->get_week_of_monthly_report($start_time, $end_time, $data_arr=[]);
            $id = $this->task->t_week_of_monthly_report->get_id_by_time_type($start_time,$report_type=1);
            if($data_arr){//添加统计数据
                if($id)
                    $this->task->t_week_of_monthly_report->job_row_update($id,$start_time,$report_type=1,$data_arr);
                else
                    $this->task->t_week_of_monthly_report->job_row_insert($start_time,$report_type=1,$data_arr);
            }
            echo 'week report ok!';

        // }else echo '统计时间出错!';

    }
    //@desn:获取周月报统计数据
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    //@param:$data_arr 初始data_arr 数组
    public function get_week_of_monthly_report($start_time,$end_time,$data_arr=[]){
        //历史数据
        $example_info = $this->task->t_test_lesson_subject->get_example_info($start_time,$end_time);
        if(@$example_info['called_num'] > 0){
            $example_info['valid_rate'] = number_format(@$example_info['valid_example_num']/@$example_info['called_num']*100, 2);
            $example_info['invalid_rate'] =number_format(@$example_info['invalid_example_num']/@$example_info['called_num']*100, 2);
            $example_info['not_through_rate'] =number_format(@$example_info['not_through_num']/@$example_info['called_num']*100, 2);
            $example_info['high_num_rate'] = number_format(@$example_info['high_num']/@$example_info['example_num']*100, 2);
            $example_info['middle_num_rate'] = number_format(@$example_info['middle_num']/@$example_info['example_num']*100, 2);
            $example_info['primary_num_rate'] = number_format(@$example_info['primary_num']/@$example_info['example_num']*100, 2);
        }
        //获取公开课次数
        $public_class_num = $this->task->t_lesson_info->get_public_class_num($start_time,$end_time);
        //微信运营信息
        $wx_example_num = $this->task->t_seller_student_new->get_wx_example_num($start_time,$end_time);
        $wx_order_info = $this->task->t_order_info->get_wx_order_info($start_time,$end_time);
        //获取所有公众号渠道
        $all_public_number_origin_list = $this->task->t_origin_key->get_all_public_number_origin();
        $all_public_number_origin_arr = array_column($all_public_number_origin_list, 'value');
        //公众号信息
        $pn_example_num = 0;
        $pn_order_num = 0;
        $pn_order_money = 0;
        $public_number_example_info = $this->task->t_seller_student_new->get_public_number_example_info($start_time,$end_time);
        $public_number_order_info = $this->task->t_order_info->get_public_number_order_info($start_time,$end_time);
        foreach($all_public_number_origin_arr as $item){
            $pn_example_num += @$public_number_example_info[$item]['public_number_num'];
            $pn_order_num += @$public_number_order_info[$item]['pn_order_count'];
            $pn_order_money += @$public_number_order_info[$item]['pn_order_all_money'];
        }
        //将统计数据放进数组里
        $data_arr['all_example_info'] = $example_info;
        $data_arr['public_class_num'] = $public_class_num;
        $data_arr['wx_example_num'] = $wx_example_num;
        $data_arr['wx_order_info'] = $wx_order_info;
        $data_arr['pn_example_num'] = $pn_example_num;
        $data_arr['pn_order_num'] = $pn_order_num;
        $data_arr['pn_order_money'] = $pn_order_money;
        return $data_arr;
    }

}
