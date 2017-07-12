<?php
namespace App\Models;
class t_baidu_push_msg extends \App\Models\Zgen\z_t_baidu_push_msg
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_stu_message_list($page_num){
        $sql=$this->gen_sql("select * from %s where message_type in (1007,2010) order by messageid desc "
                            ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function add_baidu_push_msg($message,$push_type){
        $this->row_insert([
            self::C_message_content => $message,
            self::C_message_type    => $push_type,
        ]);
        $messageid = $this->get_last_insertid();
        return $messageid; 
    }
}
