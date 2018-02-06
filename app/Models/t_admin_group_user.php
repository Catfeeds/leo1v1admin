<?php
namespace App\Models;
use \App\Enums as E;

/**

 * @property t_admin_main_group_name  $t_admin_main_group_name
* @property t_admin_group_name  $t_admin_group_name

 * @property t_admin_main_group_user  $t_admin_main_group_user

 */

class t_admin_group_user extends \App\Models\Zgen\z_t_admin_group_user
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_list($groupid) {
        $sql=$this->gen_sql_new("select adminid,assign_percent from %s where groupid=%u",
                                self::DB_TABLE_NAME,
                                $groupid);
        return $this->main_get_list_as_page($sql);
    }
    public function get_groupid_value($adminid) {
        $sql=$this->gen_sql_new("select groupid from %s where adminid=%u",
                                self::DB_TABLE_NAME,
                                $adminid);
        return $this->main_get_value($sql);
    }

    public function get_master_adminid_by_adminid($adminid,$main_type=-1){
        $where_arr=[
            ["n.main_type=%u",$main_type,-1]
        ];
        $sql=$this->gen_sql_new("select master_adminid from %s u "
                                ." left join %s n on u.groupid = n.groupid"
                                ." where adminid=%u and %s",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                $adminid,
                                $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function get_master_adminid_group_info($adminid,$main_type=-1){
        $where_arr=[
            ["n.main_type=%u",$main_type,-1]
        ];
        $sql=$this->gen_sql_new("select master_adminid,n.group_name from %s u "
                                ." left join %s n on u.groupid = n.groupid"
                                ." where adminid=%u and %s",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                $adminid,
                                $where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_user_map($groupid) {
        $sql=$this->gen_sql_new("select adminid from %s where groupid=%u",
                                self::DB_TABLE_NAME,
                                $groupid);
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_userid_arr($groupid) {
        $where_arr=[
            ["groupid= %u",$groupid,-1]
        ];
        $sql=$this->gen_sql_new("select adminid from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        $list=$this->main_get_list($sql);
        $ret_arr=[];
        foreach ($list as $item) {
            $ret_arr[]=$item["adminid"];
        }

        return $ret_arr;

    }
    public function get_userid_list_new($groupid) {
        $where_arr=[
            ["groupid= %u",$groupid,-1]
        ];
        $sql=$this->gen_sql_new("select adminid from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);

    }
    public function get_userid_list_by_master_adminid($master_adminid) {

        $sql =$this->gen_sql_new("select adminid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and  "
                                 ."  master_adminid =%u ",
                                 self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                 $master_adminid);
        return $this->main_get_list($sql);
    }




    public function del_by_groupid($groupid) {
        $sql = $this->gen_sql_new("delete from %s where groupid=%u",
                                  self::DB_TABLE_NAME
                                  ,$groupid );
        return $this->main_update($sql);
    }

    public function get_groupid_by_adminid( $main_type, $adminid) {
        $where_arr=[
            ["main_type=%u",$main_type,-1]
        ];
        $sql =$this->gen_sql_new("select g.groupid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and "
                                 ." %s and adminid=%u ",
                                 self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                 $where_arr,
                                 $adminid
        );
        return $this->main_get_value($sql,0);
    }


    public function get_group_info_by_adminid( $main_type, $adminid) {
        $where_arr=[
            ["main_type=%u", $main_type , -1 ] ,
        ];
        $sql =$this->gen_sql_new("select g.groupid,g.master_adminid from %s gu, %s g where "
                                 ."gu.groupid= g.groupid and  "
                                 ." %s and adminid=%u ",
                                 self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                 $where_arr, $adminid);
        return $this->main_get_row($sql,0);
    }

    public function get_admin_list( $main_type ) {
        $sql =$this->gen_sql_new("select  gu.adminid, m.account , m.name from %s gu, %s g , %s m where "
                                 ."gu.groupid= g.groupid and  "
                                 ."gu.adminid= m.uid and  "
                                 ." main_type=%u ",
                                 self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 $main_type );
        return $this->main_get_list($sql);
    }
    public function get_user_list_new($groupid) {
        $sql=$this->gen_sql_new("select u.adminid,m.account,"
                                ."m.create_time,m.become_member_time,m.leave_member_time,m.del_flag,m.seller_level "
                                ." from %s u,%s m where u.adminid= m.uid and groupid=%u ",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $groupid);
        return $this->main_get_list($sql);
    }
    public function get_user_list_new_new($groupid,$month) {
        $sql=$this->gen_sql_new("select u.adminid,m.account,"
                                ."m.create_time,m.become_member_time,m.leave_member_time,m.del_flag,m.seller_level "
                                ." from %s u,%s m where u.adminid= m.uid and groupid=%u and "
                                // ."(m.leave_member_time>$month or m.leave_member_time =0)",
                                ."((m.leave_member_time>$month and m.del_flag=1) or m.del_flag =0)",
                                self::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                $groupid);
        return $this->main_get_list($sql);
    }
    public function get_master_adminid( $adminid ) {
        $sql=$this->gen_sql_new("select master_adminid from %s gu ,  %s  g    "
                                ." where gu.groupid=g.groupid and adminid=%u",
                                self::DB_TABLE_NAME,
                                 t_admin_group_name::DB_TABLE_NAME,
                                $adminid);
        return $this->main_get_value($sql);
    }

    public function get_group_num($start_time){
        $sql = $this->gen_sql_new("select count(*) num,groupid ".
                                  " from %s group by groupid ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item['groupid'];
        });
    }
    public function get_seller_month_money_info($start_first){
        $sql = $this->gen_sql_new("select u.groupid,adminid,month_money ".
                                  " from %s u,%s mt where u.groupid = mt.groupid and mt.month = '%s'",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_month_time::DB_TABLE_NAME,
                                  $start_first
        );
        return $this->main_get_list($sql,function($item){
            return $item['adminid'];
        });
    }
    public function get_up_level_users($adminid) {
        $groupid=$this->get_groupid_value($adminid);
        $item1=$this->t_admin_group_name->field_get_list($groupid, "master_adminid,up_groupid");
        $up_groupid=$item1["up_groupid"];
        $master_adminid2=$this->t_admin_main_group_name->get_master_adminid($up_groupid);

        return [
            "master_adminid1" => $item1["master_adminid"],
            "master_adminid2" =>   $master_adminid2,
        ];

    }

    public function get_group_id_lists($group_id){
        $where_arr=[
            ["groupid= %u",$group_id,-1]
        ];
        $sql=$this->gen_sql_new("select adminid from %s where %s",
                                self::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_all_list($main_type_flag=0){
        $where_arr=[
            ["n.main_type=%u",$main_type_flag,0],  
        ];

        $sql=$this->gen_sql_new("select u.* from %s u "
                                ." left join %s n on u.groupid = n.groupid "
                                ." where %s",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                $where_arr
        ) ;
        return $this->main_get_list($sql);
    }

    public function get_main_master_adminid($adminid){
        $sql = $this->gen_sql_new("select am.master_adminid from %s u "
                                  ." left join %s g on u.groupid = g.groupid"
                                  ." left join %s am on g.up_groupid = am.groupid"
                                  ." where u.adminid = %u",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_value($sql);
    }
    public function get_main_type($adminid) {
        $sql = $this->gen_sql_new(" select am.main_type from %s u "
                                  ." left join %s g on u.groupid = g.groupid"
                                  ." left join %s am on g.up_groupid = am.groupid"
                                  ." where u.adminid = %u",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_value($sql);

    }

    public function get_campus_id_by_adminid($adminid){
        $sql = $this->gen_sql_new("select am.campus_id from %s u"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ." left join %s am on n.up_groupid = am.groupid"
                                  ." where u.adminid = %u",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_value($sql);
    }

    public function get_group_master_adminid($adminid){
        $sql = $this->gen_sql_new(" select u.adminid, "
                                  ." g.master_adminid group_adminid, "
                                  ." am.master_adminid master_adminid, "
                                  ." gm.master_adminid major_master_adminid "
                                  ." from %s u "
                                  ." left join %s g on u.groupid = g.groupid"
                                  ." left join %s am on g.up_groupid = am.groupid"
                                  ." left join %s gm on gm.groupid = am.up_groupid"
                                  ." where u.adminid = %u",
                                  self::DB_TABLE_NAME,//u
                                  t_admin_group_name::DB_TABLE_NAME,//g
                                  t_admin_main_group_name::DB_TABLE_NAME,//am
                                  t_main_major_group_name_month::DB_TABLE_NAME,//gm
                                  $adminid
        );
        return $this->main_get_row($sql);
    }

    public function get_ass_group_name($ass_adminid){
        $sql = $this->gen_sql_new("  select group_name from %s gu "
                                  ." left join %s gn on gn.groupid=gu.groupid"
                                  ." where gu.adminid=$ass_adminid"
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_info_by_adminid($account_id){
        $where_arr = [
          "main_type != ''",
          ["adminid=%s",$account_id,-1]
        ];
        $sql = $this->gen_sql_new(" select main_type , group_name , m.account, adminid from %s a "
                                  ."left join %s g on a.groupid = g.groupid "
                                  ."left join %s m on m.uid = a.adminid "
                                  ."where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr);

        return $this->main_get_row($sql);
    }

    public function get_ass_master_openid($acc_id){
        $where_arr = [
            "au.adminid=$acc_id"
        ];
        $sql = $this->gen_sql_new("  select m.wx_openid from %s au "
                                  ." left join %s an on au.groupid=an.groupid"
                                  ." left join %s m on m.uid=an.master_adminid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_majordomo_adminid($opt_adminid){
        $where_arr = [
            "au.adminid=$opt_adminid"
        ];
        $sql = $this->gen_sql_new("  select md.master_adminid "
                                  ." from %s au "
                                  ." left join %s an on an.groupid=au.groupid"
                                  ." left join %s mg on mg.groupid=an.up_groupid"
                                  ." left join %s md on md.groupid=an.up_groupid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
                                  ,t_admin_majordomo_group_name::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_son_adminid_by_up_groupid($self_groupid){
        $sql = $this->gen_sql_new(
            " select groupid,adminid "
            ." from %s "
            ." where groupid = %u "
            ,self::DB_TABLE_NAME//g
            ,$self_groupid
        );
        return $this->main_get_list($sql);
    }

    public function get_main_major_group_name_by_adminid($adminid_arr){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,'u.adminid', $adminid_arr);
        $sql = $this->gen_sql_new(
            " select u.adminid,j.group_name "
            ." from %s u "
            ." left join %s g on u.groupid=g.groupid "
            ." left join %s m on m.groupid=g.up_groupid "
            ." left join %s j on j.groupid=m.up_groupid "
            ." where %s "
            ,self::DB_TABLE_NAME//u
            ,t_admin_group_name::DB_TABLE_NAME//g
            ,t_admin_main_group_name::DB_TABLE_NAME//m
            ,t_admin_majordomo_group_name::DB_TABLE_NAME//j
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_list($group_name){
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr, 'g.group_name', $group_name);
        $sql = $this->gen_sql_new(
            " select u.* "
            ." from %s u "
            ." left join %s g on u.groupid=g.groupid "
            ." where %s "
            ,self::DB_TABLE_NAME//u
            ,t_admin_group_name::DB_TABLE_NAME//g
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_list_new(){
        $where_arr = [];
        $this->where_arr_add_str_field($where_arr, 'g.groupid', 86);
        $sql = $this->gen_sql_new(
            " select u.* "
            ." from %s u "
            ." left join %s g on u.groupid=g.groupid "
            ." where %s "
            ,self::DB_TABLE_NAME//u
            ,t_admin_group_name::DB_TABLE_NAME//g
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function del_item_row($groupid,$adminid){
        $sql = $this->gen_sql_new("delete from %s where groupid=%u and adminid=%u",
                                  self::DB_TABLE_NAME
                                  ,$groupid,$adminid);
        return $this->main_update($sql);
    }
}
