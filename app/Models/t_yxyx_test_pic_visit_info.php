<?php
namespace App\Models;
use \App\Enums as E;
class t_yxyx_test_pic_visit_info extends \App\Models\Zgen\z_t_yxyx_test_pic_visit_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add_visit_info($id, $wx_openid) {
        $res = $this->row_insert([
            "test_pic_info_id" => $id,
            "parentid"         => $id,//没有获取parentid,不需要的话，去掉表中该字段
            "wx_openid"        => $wx_openid,
            "flag"             => 1,
        ]);
    }
}











