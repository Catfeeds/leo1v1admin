<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_student_system_release_log extends \App\Models\Zgen\z_t_seller_student_system_release_log
{
	public function __construct()
	{
		parent::__construct();
	}
    //@desn:添加释放日志
    //@param:$admin_revisiterid 拨打cc id
    //@param:$userid 用户id
    //@param:$phone 用户电话
    //@param:$release_reason_flag  释放分类标识
    public function add_log($admin_revisiterid,$userid,$phone,$release_reason_flag){
        $time = time(NULL);
        $this->row_insert([
            'adminid' => $admin_revisiterid,
            'userid' => $userid,
            'phone' => $phone,
            'release_time' => $release_time,
            'release_reason_flag' => $release_reason_flag
        ]);
    }
    //@desn:展示释放日志信息
    //@param:$page_info 分页信息
    //@param:$start_time,$end_time 开始时间 结束时间
    //@param:$adminid 销售id
    public function get_list($page_info,$start_time,$end_time,$adminid,$userid){
        $where_arr=[
            ["srl.userid=%d", $userid, -1 ],
            ["srl.adminid=%d", $adminid, -1 ],
        ];
        $this->where_arr_add_time_range($where_arr, 'release_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            'select srl.adminid,srl.userid,si.nick,srl.phone,srl.release_time,srl.release_reason_flag,mi.account '.
            'from %s srl '.
            'join %s mi on srl.adminid=mi.uid '.
            'join %s si using(userid) '.
            'where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}











