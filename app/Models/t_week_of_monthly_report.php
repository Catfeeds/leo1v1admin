<?php
namespace App\Models;
use \App\Enums as E;
class t_week_of_monthly_report extends \App\Models\Zgen\z_t_week_of_monthly_report
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn: 记录统计数据
    //@param:$start_time 统计开始时间
    //@param:$report_type 报告类型1：周报2：月报
    //@param:$data_arr 计算好的统计数据 
    public function job_row_insert($start_time,$report_type=1,$data_arr=[]){
        $this->row_insert([
            'add_time' => $start_time,
            'report_type' => $report_type,
            'example_num' => $data_arr['all_example_info']['example_num'],
            'valid_example_num' => $data_arr['all_example_info']['example_num'],
            'called_num' => $data_arr['all_example_info']['called_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
            '' => $data_arr['all_example_info']['example_num'],
        ]);
    }

}











