<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_channel_group extends \App\Models\Zgen\z_t_admin_channel_group
{
    public function __construct()
    {
        parent::__construct();
    }
    /*
     *@author sam
     *@function 获取group_id
     */
    public function get_group($group_id) {
        $where_arr=[
            ["ref_type= %u",$group_id,-1]
        ];
        $sql=$this->gen_sql_new("select ref_type,channel_id from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);

    }

    /*
     *@author sam
     *@function 获取group_id
     */
    public function get_group_id_list($channel_id) {
        $where_arr=[
            ["channel_id= %u",$channel_id,-1]
        ];
        $sql=$this->gen_sql_new("select ref_type  from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);

    }
    /*
     *@author sam
     *@function 获取channel_id
     */
    public function get_group_id_list_b1($channel_id) {
        $where_arr=[
            ["channel_id= %u",$channel_id,-1]
        ];
        $sql=$this->gen_sql_new("select group_id from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);

    }
    /*
     *@author sam
     *@function 获取group_id,group_name
     */

    public function get_all_group_id($page_num) {
        $sql = $this->gen_sql_new("select ref_type from %s ",
                                  self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_num);
    }
     /*
     *@author sam
     *@function 获取teacher_id,teacher_name
     */

    public function get_all_teacher($page_num) {
        $sql = $this->gen_sql_new("select teacherid,realname from %s ",
                                  t_teacher_info::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_num);
    }
}
