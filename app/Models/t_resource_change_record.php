<?php
namespace App\Models;
use App\Enums as E;
class t_resource_change_record extends \App\Models\Zgen\z_t_resource_change_record
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_teacherid_by_file_id_reload($file_id){
    	$sql = "select teacherid from db_weiyi.t_resource_change_record where file_id = $file_id and action ='申请-更换修改重传负责人' order by id desc limit 1";
    	return $this->main_get_value($sql);
    }

    public function get_teacherid_by_file_id_kpi($file_id){
    	$sql = "select teacherid from db_weiyi.t_resource_change_record where file_id = $file_id and action ='申请-更换讲义统计负责人' order by id desc limit 1";
    	return $this->main_get_value($sql);
    }
}