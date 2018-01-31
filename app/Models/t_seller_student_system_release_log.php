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

}











