<?php
namespace App\Models;
class t_admin_group extends \App\Models\Zgen\z_t_admin_group
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_info_list($groupid,$adminid,$page_num)
    {
        $where_arr = [
            ["groupid=%u", $groupid,-1],
            ["adminid=%u", $adminid,-1],
        ];

        $sql = $this->gen_sql("select * from %s where %s ",
                              self::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function add_member($groupid,$adminid)
    {
        return $this->row_insert([
            self::C_groupid => $groupid,
            self::C_adminid => $adminid,

        ]);
    }

    public function get_member_sim($adminid)
    {
        $sql = $this->gen_sql("select adminid,groupid from %s where adminid = %u ",
                              self::DB_TABLE_NAME,
                              $adminid
        );

        return $this->main_get_row($sql);
    }

    public function get_adminid()
    {
        $sql = $this->gen_sql("select adminid from %s ",
                              self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }


    public function edit_member($groupid,$adminid,$old_adminid)
    {
        $sql = $this->gen_sql("update %s set adminid = %u,groupid = %u where adminid = %u ",
                              self::DB_TABLE_NAME,
                              $adminid,
                              $groupid,
                              $old_adminid
        );

        return $this->main_update($sql);
    }



    public function delete_member_info($adminid)
    {
        $sql = $this->gen_sql("delete from %s where adminid = %u ",
                              self::DB_TABLE_NAME,
                              $adminid
        );

        return $this->main_update($sql);
    }

    public function get_admin_group_by_group_id($groupid)
    {
        $sql = $this->gen_sql("select adminid, account, name  from %s a ,%s m where a.adminid=m.uid and  groupid = %u  ",
                              self::DB_TABLE_NAME,
                              t_manager_info::DB_TABLE_NAME,
                              $groupid
        );
        return $this->main_get_list($sql);
    }

    public function get_admin_list_by_gorupid($groupid)
    {
        $sql = $this->gen_sql("select id, account  from %s t_g, %s t_a where t_g.adminid= t_a.id and  groupid = %u ",
                              self::DB_TABLE_NAME,
                              t_admin_users::DB_TABLE_NAME,
                              $groupid
        );
        return $this->main_get_list($sql);
    }

    public function get_group_id($adminid){
        $sql = $this->gen_sql("select groupid from %s where adminid=%d ",
                              self::DB_TABLE_NAME,
                              $adminid
        );
        return $this->main_get_value($sql);


    }

    public function get_group_id_by_aid($aid){
        $sql = $this->gen_sql_new(" select n.master_adminid ,g.groupid".
                                  " from %s g left join %s n on n.groupid = g.groupid".
                                  " left join %s a on g.adminid = m.uid".
                                  " left join %s m on a.phone = m.phone".
                                  " where a.assistantid = $aid",
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_group_id_by_aid2($aid) {
        $sql = "select g.groupid from db_weiyi_admin.t_manager_info m left join t_assistant_info a on a.phone=m.phone left join db_weiyi_admin.t_admin_group_user g on m.uid=g.adminid where a.assistantid = $aid";
        return $this->main_get_value($sql);
    }
}
