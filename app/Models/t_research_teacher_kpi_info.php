<?php
namespace App\Models;
use \App\Enums as E;
class t_research_teacher_kpi_info extends \App\Models\Zgen\z_t_research_teacher_kpi_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_month_kpi_info($type_flag,$start_time,$subject_type=0){
        $where_arr=[
            ["type_flag=%u",$type_flag,-1],
            ["month=%u",$start_time,-1]
        ];
        if($type_flag==1){
            $where_arr[]="kid <>20000";
        }elseif($type_flag==2 && $subject_type==0){
            $where_arr[]="kid in (21,22,24)";
        }else{
            $where_arr[]="kid in (1,2,3,4,5,23)";
        }
        $sql= $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_month_info($type_flag,$start_time){
        $where_arr=[
            ["type_flag=%u",$type_flag,-1],
            ["month=%u",$start_time,-1],
            "kid <> 24"
        ];
        $sql= $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["kid"];
        });
    }

    public function get_all_info_by_time($time){
        $where_arr=[
            ["month<%u",$time,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }

}











