<?php
namespace App\Models;
use \App\Enums as E;
class t_user_log extends \App\Models\Zgen\z_t_user_log
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($page_info, $start_time, $end_time) {
        $whereArr = [
            ["add_time>%u", $start_time, 0],
            ["add_time<%u", $end_time, 0],
        ];

        $sql = $this->gen_sql_new("select id,add_time,userid,adminid,msg from %s where %s order by add_time desc",
                                  self::DB_TABLE_NAME,
                                  $whereArr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function add_data($msg,$userid=0) {
        return $this->row_insert([
            'userid'   => $userid,
            'adminid'  => session('adminid'),
            'msg'      => $msg,
            'add_time' => time()
        ]);
    }

    public function add_data_new($msg,$userid=0,$type=0) {
        return $this->row_insert([
            'userid'        => $userid,
            'adminid'       => session('adminid'),
            'msg'           => $msg,
            'user_log_type' => $type,
            'add_time'      => time()
        ]);
    }

    /**
     * 添加后台用户的操作记录
     */
    public function add_user_log($userid,$msg,$user_log_type){
        if(isset($_SERVER['HTTP_REFERER'])){
            $operate_referer = substr($_SERVER['HTTP_REFERER'],0,1000);
        }else{
            $operate_referer = "非浏览器操作";
        }
        if(isset($_SERVER['REQUEST_URI'])){
            $operate_request = substr($_SERVER['REQUEST_URI'],0,1000);
        }else{
            $operate_request = "没有请求地址";
        }
        $add_time  = time();
        $ret = $this->row_insert([
            'userid'          => $userid,
            'adminid'         => session('adminid'),
            'msg'             => $msg,
            'user_log_type'   => $user_log_type,
            'add_time'        => $add_time,
            "operate_referer" => $operate_referer,
            "operate_request" => $operate_request,
        ]);
        return $ret;
    }

    public function add_teacher_reward_log($teacherid,$msg=""){
        if($msg==""){
            $msg = "修改老师额外金额";
        }elseif(is_array($msg)){
            $msg = json_encode($msg);
        }
        $ret = $this->add_user_log($teacherid,$msg,E\Euser_log_type::V_200);
        return $ret;
    }


}











