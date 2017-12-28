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
    public function job_row_update($id,$start_time,$report_type=1,$data_arr){
        if($data_arr){
            $this->field_update_list($id, [
                'add_time' => $start_time,
                'report_type' => $report_type,
                'example_num' => @$data_arr['all_example_info']['example_num'],
                'valid_example_num' => @$data_arr['all_example_info']['valid_example_num'],
                'called_num' => @$data_arr['all_example_info']['called_num'],
                'valid_rate' => @$data_arr['all_example_info']['valid_rate'],
                'invalid_example_num' => @$data_arr['all_example_info']['invalid_example_num'],
                'invalid_rate' => @$data_arr['all_example_info']['invalid_rate'],
                'not_through_num' => @$data_arr['all_example_info']['not_through_num'],
                'not_through_rate' => @$data_arr['all_example_info']['not_through_rate'],
                'high_num' => @$data_arr['all_example_info']['high_num'],
                'high_num_rate' => @$data_arr['all_example_info']['high_num_rate'],
                'middle_num' => @$data_arr['all_example_info']['middle_num'],
                'middle_num_rate' => @$data_arr['all_example_info']['middle_num_rate'],
                'primary_num' => @$data_arr['all_example_info']['primary_num'],
                'primary_num_rate' => @$data_arr['all_example_info']['primary_num_rate'],
                'wx_example_num' => @$data_arr['wx_example_num'],
                'wx_order_count' => @$data_arr['wx_order_info']['wx_order_count'],
                'wx_order_all_money' => @$data_arr['wx_order_info']['wx_order_all_money'],
                'pn_example_num' => @$data_arr['pn_example_num'],
                'pn_order_num' => @$data_arr['pn_order_num'],
                'pn_order_money' => @$data_arr['pn_order_money'],
                'public_class_num' => @$data_arr['public_class_num']
            ]);
        }
    }
    //@desn: 记录统计数据
    //@param:$start_time 统计开始时间
    //@param:$report_type 报告类型1：周报2：月报
    //@param:$data_arr 计算好的统计数据 
    public function job_row_insert($start_time,$report_type=1,$data_arr=[]){
        if($data_arr){
            $this->row_insert([
                'add_time' => $start_time,
                'report_type' => $report_type,
                'example_num' => @$data_arr['all_example_info']['example_num'],
                'valid_example_num' => @$data_arr['all_example_info']['valid_example_num'],
                'called_num' => @$data_arr['all_example_info']['called_num'],
                'valid_rate' => @$data_arr['all_example_info']['valid_rate'],
                'invalid_example_num' => @$data_arr['all_example_info']['invalid_example_num'],
                'invalid_rate' => @$data_arr['all_example_info']['invalid_rate'],
                'not_through_num' => @$data_arr['all_example_info']['not_through_num'],
                'not_through_rate' => @$data_arr['all_example_info']['not_through_rate'],
                'high_num' => @$data_arr['all_example_info']['high_num'],
                'high_num_rate' => @$data_arr['all_example_info']['high_num_rate'],
                'middle_num' => @$data_arr['all_example_info']['middle_num'],
                'middle_num_rate' => @$data_arr['all_example_info']['middle_num_rate'],
                'primary_num' => @$data_arr['all_example_info']['primary_num'],
                'primary_num_rate' => @$data_arr['all_example_info']['primary_num_rate'],
                'wx_example_num' => @$data_arr['wx_example_num'],
                'wx_order_count' => @$data_arr['wx_order_info']['wx_order_count'],
                'wx_order_all_money' => @$data_arr['wx_order_info']['wx_order_all_money'],
                'pn_example_num' => @$data_arr['pn_example_num'],
                'pn_order_num' => @$data_arr['pn_order_num'],
                'pn_order_money' => @$data_arr['pn_order_money'],
                'public_class_num' => @$data_arr['public_class_num']
            ]);
        }
    }
    //@desn:获取该类型、该时间是否已存在记录
    //@param:$start_time 时间
    //@param:$report_type 1 周报类型 2 月报类型
    public function get_id_by_time_type($start_time,$report_type=1){
        $where_arr = [
            ['add_time = %u',$start_time],
            ['report_type = %u',$report_type]
        ];
        $sql = $this->gen_sql_new('select id from %s where %s', self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }
    //@desn:获取存档报告
    //@param:$report_type 1:周报 2：月报
    public function get_example_info($report_type=1,$new_date){
        $where_arr = [
            ['add_time = %u',$new_date],
            ['report_type = %u',$report_type]
        ];
        $sql = $this->gen_sql_new(
            'select * from %s where %s', self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_row($sql);
    }

}











