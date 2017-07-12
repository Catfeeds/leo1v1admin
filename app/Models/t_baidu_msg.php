<?php
namespace App\Models;
class t_baidu_msg extends \App\Models\Zgen\z_t_baidu_msg
{
	public function __construct()
	{
		parent::__construct();
	}

    public function baidu_push_msg($userid,$content,$value,$message_type,$push_num,$use_push_flag=0){
        return $this->row_insert([
            'userid'        => $userid,
            'content'       => $content,
            'push_num'      => $push_num,
            'value'         => $value,
            'message_type'  => $message_type,
            'date'          => time(null),
            'use_push_flag' => $use_push_flag,
        ]);
    }

    public function change_lesson_start_message_status($lessonid){
        $sql = $this->gen_sql_new("update %s set status=0"
                                  ." where value='%s'"
                                  ." and push_num in (1,106,301)"
                                  ." and status>0"
                                  ,self::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_update($sql);
    }

    public function delete_open_msg($msgid,$userid){
        $sql = $this->gen_sql_new("update %s set status=2 where content=%s and userid=%u"
                                  ,self::DB_TABLE_NAME
                                  ,$msgid
                                  ,$userid
        );
        return $this->main_update($sql);
    }

    public function get_stu_detail_message_list($page_num,$start_time,$end_time,$userid,$message_type){
        $where_arr = [
            ["userid=%u",$userid,0],
            ["message_type=%u",$message_type,0],
            ["date>%u",$start_time,0],
            ["date<%u",$end_time,0],
        ];
        $sql = $this->gen_sql_new("select messageid,date,content,value,push_num,message_type,userid"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

}