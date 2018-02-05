<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_group_name extends \App\Models\Zgen\z_t_admin_group_name
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_group_list ($main_type) {
        $sql=$this->gen_sql_new("select groupid,group_name  from %s where main_type=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list($sql);
    }

    public function get_groupid_by_master_adminid( $master_adminid) {
        $sql=$this->gen_sql_new("select  groupid from %s where master_adminid=%u  ",
                           self::DB_TABLE_NAME, $master_adminid) ;
        return $this->main_get_value($sql);
    }

    public function get_group_name_by_master_adminid( $master_adminid) {
        $sql=$this->gen_sql_new("select  group_name from %s where master_adminid=%u  ",
                                self::DB_TABLE_NAME, $master_adminid) ;
        return $this->main_get_value($sql);
    }


    public function get_group_list_new($page_num,$main_type){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid from %s where main_type=%u  " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list_by_page($sql,$page_num);
    }




    public function get_group_name_list($main_type,$up_groupid){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid  from %s where main_type=%u and up_groupid = %u order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type,$up_groupid);
        return $this->main_get_list($sql);
    }

    public function get_groupid_list_new($up_groupid,$main_type){
        $where_arr=[
            ["up_groupid = %u",$up_groupid,-1],
            ["main_type = %u",$main_type,-1]
        ];
        $sql=$this->gen_sql_new("select groupid from %s where %s " ,
                                self::DB_TABLE_NAME, $where_arr);
        $ret =  $this->main_get_list($sql);
        $groupid_list=[];
        foreach($ret as $item){
            $groupid_list[]= $item['groupid'];
        }
        return $groupid_list;
    }
    public function get_adminid_list_by_up_groupid($up_groupid){
        $sql=$this->gen_sql_new("select u.adminid  from %s n ,%s u ".
                                " where n.groupid = u.groupid and n.up_groupid = %u  " ,
                                self::DB_TABLE_NAME,
                                t_admin_group_user::DB_TABLE_NAME,
                                $up_groupid);
        return $this->main_get_list($sql);
    }
    public function get_adminid_list_by_main_type($main_type){
        $sql=$this->gen_sql_new("select u.adminid  from %s n ,%s u ".
                                " where n.groupid = u.groupid and n.main_type = %u  " ,
                                self::DB_TABLE_NAME,
                                t_admin_group_user::DB_TABLE_NAME,
                                $main_type);
        return $this->main_get_list($sql);

    }

    public function update_by_up_groupid($up_groupid) {
        $sql = $this->gen_sql_new("update %s set up_groupid = 0 where up_groupid=%u",
                                  self::DB_TABLE_NAME
                                  ,$up_groupid );
        return $this->main_update($sql);
    }


    public function get_seller_admin_info($main_type,$account="",$month){
        $where_arr = [];
        $where_arr[] = "u.adminid not in (60,68)";
        if ($account) {
            $where_arr[]=["m.account like '%%%s%%'",$account,""];
        }

        $sql=$this->gen_sql_new("select u.adminid adminid,account,t.month_time,t.leave_and_overtime".
                                " from %s n left join %s u on n.groupid = u.groupid".
                                " left join %s m on u.adminid = m.uid ".
                                " left join %s t on (t.adminid = u.adminid and t.month = '%s')".
                                " where n.main_type = %u and %s" ,
                                self::DB_TABLE_NAME,
                                t_admin_group_user::DB_TABLE_NAME,
                                t_manager_info::DB_TABLE_NAME,
                                t_seller_month_money_target::DB_TABLE_NAME,
                                $month,
                                $main_type,
                                $where_arr
        );
        return $this->main_get_list_as_page($sql);

    }

    public function get_seller_no_attend_list($day,$month){
        $ret_info = $this->get_seller_admin_info(2,"",$month);
        $list = "";
        foreach($ret_info["list"] as $k=>&$item){
            $month_time = json_decode($item['month_time'],true);
            if(!empty($month_time)){
                foreach($month_time as $val){
                    if(substr($val[0],0,10)==$day ){
                        $item["plan_do"] = substr($val[0],11,1);
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],0,10) ==$day ){
                            $item["real_do"] = substr($v[0],11,1);
                        }
                    }
                }

                if(!isset($item["real_do"])){
                    $item["real_do"] = $item["plan_do"];
                }

            }
            $item["plan_do"] = isset($item["plan_do"])?$item["plan_do"]:0;
            $item["real_do"] = isset($item["real_do"])?$item["real_do"]:0;
            if($item["real_do"] == 0){
                $list .= $item["adminid"].",";
            }
        }
        if(!empty($list)){
            $list = "(".trim($list,",").")";
        }
        return $list;
    }

    public function get_groupid_by_group_name($group_name,$main_type=-1){
        $where_arr=[
            ["main_type = %u",$main_type,-1]
        ];
        $sql = $this->gen_sql_new("select groupid from %s where group_name = '%s' and %s",
                                  self::DB_TABLE_NAME,
                                  $group_name,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_leader_list($main_type){
        $where_arr=[
            ["main_type = %u",$main_type,-1]
        ];
        $sql=$this->gen_sql_new("select distinct master_adminid  from %s where %s order by master_adminid " ,
                                self::DB_TABLE_NAME, $where_arr);
        $ret =  $this->main_get_list($sql);
        $arr=[];
        foreach($ret as $item){
            $arr[]= $item['master_adminid'];
        }
        return $arr;

    }

    public function check_is_master($main_type,$account_id){
        $sql = $this->gen_sql_new("select 1 from %s where main_type = %u and master_adminid=%u",
                                  self::DB_TABLE_NAME,
                                  $main_type,
                                  $account_id
        );
        return $this->main_get_value($sql);
    }

    public function get_all_info_by_master_adminid($main_type,$account_id){
        $sql = $this->gen_sql_new("select g.group_name,mg.group_name master_group_name "
                                  ." from %s g left join %s mg on g.up_groupid = mg.groupid "
                                  ." where g.main_type = %u and g.master_adminid=%u",
                                  self::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $main_type,
                                  $account_id
        );
        return $this->main_get_row($sql);

    }


    public function get_group_id($adminid){
        $sql=$this->gen_sql_new("select  groupid from %s where master_adminid=%u  ",
                                self::DB_TABLE_NAME, $adminid) ;
        return $this->main_get_value($sql);
    }

    public function get_all_list($main_type_flag=0){
        $where_arr=[
            ["main_type=%u",$main_type_flag,0]
        ];
        $sql=$this->gen_sql_new("select * from %s where %s ",
                                self::DB_TABLE_NAME,
                                $where_arr
        ) ;
        return $this->main_get_list($sql);
    }

    public function get_group_name($adminid){
        $sql=$this->gen_sql_new("select  group_name from %s where master_adminid=%u  ",
                                self::DB_TABLE_NAME, $adminid) ;
        return $this->main_get_value($sql);
    }

    public function get_group_name_by_groupid($groupid){
        $sql=$this->gen_sql_new("select  group_name from %s where groupid=%u  ",
                                self::DB_TABLE_NAME, $groupid) ;
        return $this->main_get_value($sql);
    }


    public function get_master_adminid_by_subject($subject){
        $sql=$this->gen_sql_new("select master_adminid from %s where subject=%u  ",
                                self::DB_TABLE_NAME, $subject) ;
        return $this->main_get_value($sql);
    }

    public function get_all_master_adminid_list($main_type){
        $where_arr=[
            ["main_type = %u",$main_type,-1]
        ];
        $arr=[];
        $sql=$this->gen_sql_new("select distinct master_adminid  from %s where %s  " ,
                                self::DB_TABLE_NAME, $where_arr);
        $ret =  $this->main_get_list($sql);
        foreach($ret as $val){
            $arr[$val["master_adminid"]] = $val["master_adminid"];
        }
        $sql=$this->gen_sql_new("select distinct master_adminid  from %s where %s  " ,
                                t_admin_main_group_name::DB_TABLE_NAME, $where_arr);
        $ret =  $this->main_get_list($sql);
        foreach($ret as $val){
            if(!isset($arr[$val["master_adminid"]])){
                $arr[$val["master_adminid"]] = $val["master_adminid"];
            }
        }
        return $arr;
    }

    public function get_group_id_by_aid($ass_admind){
        $sql = $this->gen_sql_new(" select agn.master_adminid,agn.group_name from %s agn".
                                  " left join %s agu on agu.groupid = agn.groupid ".
                                  " where agu.adminid = $ass_admind",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }

    public function get_group_admin_name(){
        $sql = $this->gen_sql_new(" select account_role, up_groupid,master_adminid,group_name, account from %s ta  ".
                                  " left join %s m on m.uid=ta.master_adminid  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_main_group_admin_name(){
        $sql = $this->gen_sql_new(" select account_role, master_adminid,group_name, account from %s ta  ".
                                  " left join %s m on m.uid=ta.master_adminid  ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_up_groupid_by_master_adminid($master_adminid){
        $sql = $this->gen_sql_new("select up_groupid from %s where master_adminid = %d",
                                  self::DB_TABLE_NAME,
                                  $master_adminid
        );

        return $this->main_get_value($sql);
    }


    public function get_group_admin_list($adminid){
        $where_arr = [
            ["gn.master_adminid = %d ",$adminid]
        ];

        $sql = $this->gen_sql_new(" select adminid from %s gn ".
                                  " left join %s gu on gn.groupid = gu.groupid".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);

    }


    public function get_ass_master_adminid_by_campus_id($campus_id){
        $sql = $this->gen_sql_new("select n.master_adminid from %s n"
                                  ." left join %s am on n.up_groupid = am.groupid"
                                  ." where am.campus_id = %u and n.main_type =1 and am.main_type=1",
                                  self::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $campus_id
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_master_adminid_by_campus_id($campus_id){
        $sql = $this->gen_sql_new("select n.master_adminid from %s n"
                                  ." left join %s am on n.up_groupid = am.groupid"
                                  ." where am.campus_id = %u and n.main_type =2 and am.main_type=2",
                                  self::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $campus_id
        );
        return $this->main_get_list($sql);
    }

    public function update_group_img_by_master_adminid($master_adminid,$group_img) {
        $sql = $this->gen_sql_new("update %s set group_img = '".$group_img."' where master_adminid=%u",
                                  self::DB_TABLE_NAME
                                  ,$master_adminid);
        return $this->main_update($sql);
    }

    public function get_seller_num(){
        $sql = $this->gen_sql_new("  select count(u.adminid) as seller_num from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." where mg.main_type=2 "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_group_seller_num($group_name, $start_time){
        $sql = $this->gen_sql_new("  select count(u.adminid) as seller_num from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." left join %s mgn on mgn.groupid=mg.up_groupid"
                                  ." left join %s m on m.uid=u.adminid"
                                  ." where mg.main_type=2 and mgn.group_name='$group_name' and (m.leave_member_time >$start_time or m.leave_member_time=0) "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
                                  ,t_admin_majordomo_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_group_new_count($group_name){
        $sql = $this->gen_sql_new("  select count(u.adminid) as seller_num from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." where mg.main_type=2 and n.group_name='$group_name' "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_stu_num_leader($start_time, $end_time){
        $where_arr = [
            "ra.create_time>=$start_time",
            "ra.create_time<$end_time",
            "a.main_type = 1",
        ];
        $sql = $this->gen_sql_new(
            "select  a.master_adminid, m.name,sum(ra.stu_num) as stu_num"
            ." from %s a"
            ." left join %s u on u.groupid=a.groupid"
            ." left join %s ra on ra.uid=u.adminid  "
            ." left join %s m on m.uid=a.master_adminid"
            ." where %s "
            ."group by a.master_adminid "
            ,self::DB_TABLE_NAME
            ,t_admin_group_user::DB_TABLE_NAME
            ,t_revisit_assess_info::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_entry_total_price($start_time, $end_time){
        $where_arr = [
            "((m.leave_member_time=0 and $end_time-m.create_time>29*86400) or (m.leave_member_time>=$start_time and $end_time-m.create_time>29*86400 ))",
            " mg.main_type=2",
            "o.contract_type=0",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);

        $sql = $this->gen_sql_new("  select  sum(o.price)/100 from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." left join %s mgn on mgn.groupid=mg.up_groupid"
                                  ." left join %s m on m.uid=u.adminid"
                                  ." left join %s o on o.sys_operator=m.account"
                                  ." where %s   "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
                                  ,t_admin_majordomo_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);

    }


    public function get_entry_month_num( $start_time,$end_time){
        $where_arr = [
            "((m.leave_member_time=0 and $end_time-m.create_time>29*86400) or (m.leave_member_time>=$start_time and $end_time-m.create_time>29*86400 ))",
            " mg.main_type=2",
            "o.contract_type=0",
            "contract_status <> 0",
        ];

        $this->where_arr_add_time_range($where_arr,"o.order_time",$start_time,$end_time);

        // $sql = $this->gen_sql_new("  select  m.account from %s n"
        $sql = $this->gen_sql_new("  select  count(distinct(m.uid)) from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." left join %s mgn on mgn.groupid=mg.up_groupid"
                                  ." left join %s m on m.uid=u.adminid"
                                  ." left join %s o on o.sys_operator=m.account"
                                  ." where %s  "
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
                                  ,t_admin_majordomo_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);

        // return $this->main_get_list($sql);


    }

    public function is_master($adminid){
        $sql = $this->gen_sql_new("select groupid from %s where master_adminid='$adminid'",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_son_adminid_by_up_groupid($admin_main_groupid){
        $sql = $this->gen_sql_new(
            " select g.up_groupid,u.adminid "
            ." from %s g "
            ." left join %s u on u.groupid = g.groupid "
            ." where g.up_groupid = %u "
            ,self::DB_TABLE_NAME//g
            ,t_admin_group_user::DB_TABLE_NAME//u
            ,$admin_main_groupid
        );
        return $this->main_get_list($sql);
    }

    # 临时测试
    public function getGroupSellerNum( $start_time){
        $sql = $this->gen_sql_new("  select  mgn.group_name, count(u.adminid) as seller_num from %s n"
                                  ." left join %s u on u.groupid=n.groupid "
                                  ." left join %s mg on mg.groupid=n.up_groupid"
                                  ." left join %s mgn on mgn.groupid=mg.up_groupid"
                                  ." left join %s m on m.uid=u.adminid"
                                  ." where mg.main_type=2 and (m.leave_member_time >$start_time or m.leave_member_time=0 or m.del_flag=0) "
                                  ." group by mgn.group_name"
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,t_admin_main_group_name::DB_TABLE_NAME
                                  ,t_admin_majordomo_group_name::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_ass_group_name($main_type) {
        $sql = $this->gen_sql_new("select groupid,group_name from %s where main_type=1",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql, function($item) {
            return $item["groupid"];
        });
    }

}
