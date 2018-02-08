<?php
namespace App\Models;
use \App\Enums as E;
/**


 * @property t_admin_group_user  $t_admin_group_user
 * @property t_admin_group_name  $t_admin_group_name
 * @property t_manager_info  $t_manager_info
 * @property t_admin_main_group_name  $t_admin_main_group_name
 */


class t_admin_main_group_name extends \App\Models\Zgen\z_t_admin_main_group_name
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_group_list_for_major ($main_type,$up_groupid) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u and up_groupid=%d order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type,$up_groupid);
        return $this->main_get_list($sql);
    }


    public function get_group_list ($main_type) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid  from %s where main_type=%u order by group_name  asc " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list($sql);
    }

    public function get_groupid_by_master_adminid( $master_adminid) {
        $sql=$this->gen_sql_new("select  groupid from %s where master_adminid=%u  ",
                                self::DB_TABLE_NAME, $master_adminid) ;
        return $this->main_get_value($sql);
    }

    public function get_adminid_list_by_master_adminid( $master_adminid,$main_type) {
        $where_arr=[
            ["am.master_adminid=%u",$master_adminid,-1],
            ["am.main_type=%u",$main_type,-1]
        ];
        $sql=$this->gen_sql_new("select  u.adminid"
                                ." from %s am left join %s n on am.groupid = n.up_groupid"
                                ." left join %s u on n.groupid = u.groupid"
                                ." where %s  ",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                t_admin_group_user::DB_TABLE_NAME,
                                $where_arr) ;
        $arr=  $this->main_get_list($sql);
        $list=[];
        foreach($arr as $item){
            $list[]=$item["adminid"];
        }
        return $list;
    }


    public function get_group_name_by_master_adminid( $master_adminid) {
        $sql=$this->gen_sql_new("select  group_name from %s where master_adminid=%u  ",
                                self::DB_TABLE_NAME, $master_adminid) ;
        return $this->main_get_value($sql);
    }


    public function get_user_list($groupid,$main_type) {
        $where_arr = [];
        if($groupid > 0){
            $where_arr[]= "n.groupid=".$groupid;
        }else{
            $where_arr[] = "n.groupid= -1";
        }
        if($main_type == 0){
            $main_type = -1;
        }
        $where_arr[] = "n.main_type=".$main_type;

        $sql=$this->gen_sql_new("select u.groupid child_groupid,u.group_name child_group_name,u.master_adminid,group_assign_percent from %s n left join %s u on n.groupid=u.up_groupid where %s",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list_as_page($sql);
    }
    public function get_up_group_adminid( $adminid) {

        $sql=$this->gen_sql_new("select n.master_adminid from %s n ,%s u where  n.groupid=u.up_groupid  and u.master_adminid=%u  ",
                                self::DB_TABLE_NAME,
                                t_admin_group_name::DB_TABLE_NAME,
                                $adminid);
        return $this->main_get_value($sql);
    }

    public function get_in_str_adminid_list($seller_groupid_ex,$str){
        $arr=explode(",",$seller_groupid_ex);
        $main_type="";
        $up_groupid="";
        $groupid="";
        $adminid="";
        if (isset($arr[0])) $main_type= $arr[0];
        if (isset($arr[1])) $up_groupid= $arr[1];
        if (isset($arr[2])) $groupid= $arr[2];
        if (isset($arr[3])) $adminid= $arr[3];

        if($adminid){
            return " $str=".$adminid;
        }else{
            if($groupid){
                $adminid_list = $this->t_admin_group_user->get_userid_list_new($groupid);
            }else{
                if($up_groupid){
                    $adminid_list = $this->t_admin_group_name->get_adminid_list_by_up_groupid($up_groupid);
                }else{
                    if($main_type){
                        $adminid_list = $this->t_admin_group_name->get_adminid_list_by_main_type($main_type);
                    }else{
                        return "true";
                    }
                }
            }
        }
        if(isset($adminid_list)){
            $ret_arr=[];
            foreach ($adminid_list as $item) {
                $ret_arr[]=$item["adminid"];
            }
            if (count( $ret_arr)==0) {
                return "$str= -100";
            } else  {
                return " $str in (". join(",", $ret_arr) . ")" ;
            }
        }

    }


    public function get_adminid_list_new($seller_groupid_ex){
        $arr=explode(",",$seller_groupid_ex);
        $main_type="";
        $up_groupid="";
        $groupid="";
        $adminid="";
        $adminid_list = [];
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3,"全职老师"=>5];
        if (isset($arr[0]) && !empty($arr[0])){
            $main_type_name= $arr[0];
            $main_type = $main_type_list[$main_type_name];
        }
        if(empty($main_type)){
            $main_type=-1;
        }
        if (isset($arr[1])  && !empty($arr[1])){
            $up_group_name= $arr[1];
            $up_groupid = $this->t_admin_main_group_name->get_groupid_by_group_name($up_group_name,$main_type);
        }
        if (isset($arr[2])  && !empty($arr[2])){
            $group_name= $arr[2];
            $groupid = $this->t_admin_group_name->get_groupid_by_group_name($group_name,$main_type);
        }
        if (isset($arr[3])  && !empty($arr[3])){
            $account= $arr[3];
            $adminid = $this->t_manager_info->get_id_by_account($account);
        }

        if($adminid){
            $adminid_list[] = $adminid;
        }else{
            if($groupid){
                $adminid_list_ex = $this->t_admin_group_user->get_userid_list_new($groupid);
            }else{
                if($up_groupid){
                    $adminid_list_ex = $this->t_admin_group_name->get_adminid_list_by_up_groupid($up_groupid);
                }else{
                    if($main_type){
                        $adminid_list_ex = $this->t_admin_group_name->get_adminid_list_by_main_type($main_type);
                    }
                }
            }
        }
        if(isset($adminid_list_ex)){
            foreach ($adminid_list_ex as $item) {
                $adminid_list[]=$item["adminid"];
            }
        }
        return $adminid_list;
    }

    public function get_groupid_by_group_name($group_name,$main_type=-1){
        $where_arr=[
            ["main_type=%u",$main_type,-1]
        ];
        $sql = $this->gen_sql_new("select groupid from %s where group_name = '%s' and %s",
                                  self::DB_TABLE_NAME,
                                  $group_name,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_is_master($main_type,$account_id){
        $sql = $this->gen_sql_new("select 1 from %s where main_type = %u and master_adminid=%u",
                                  self::DB_TABLE_NAME,
                                  $main_type,
                                  $account_id
        );
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

    public function get_master_adminid_list($main_type){
        $sql = $this->gen_sql_new("select master_adminid from %s where main_type=%u",
                                  self::DB_TABLE_NAME,
                                  $main_type
        );
        $arr = $this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[] = $val["master_adminid"];
        }
        return $list;
    }

    public function get_all_memeber_list($main_type,$group_name){
        $sql = $this->gen_sql_new("select am.master_adminid,am.group_name,u.adminid,t.subject,t.grade_part_ex "
                                  ." from %s am left join %s n on am.groupid = n.up_groupid"
                                  ." left join %s u on u.groupid= n.groupid"
                                  ." left join %s m on m.uid= u.adminid"
                                  ." left join %s t on t.phone = m.phone"
                                  ." where am.main_type=%u and am.group_name='%s'",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $main_type,
                                  $group_name
        );
        return $this->main_get_list($sql);
    }
    public function get_maste_admin_name($main_type,$group_name){
        $sql = $this->gen_sql_new("select am.master_adminid,m.account "
                                  ." from %s am left join %s m on am.master_adminid = m.uid"
                                  ." where am.main_type=%u and am.group_name='%s'",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $main_type,
                                  $group_name
        );
        return $this->main_get_row($sql);
    }

    public function ceshi(){
        $sql = $this->gen_sql_new('select group_name from %s where master_adminid=478',
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

    public function get_max_main_type(){
        $sql = $this->gen_sql_new("select max(main_type) from %s ",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_group_list_by_campus_id ($campus_id) {
        $sql=$this->gen_sql_new("select groupid,group_name,main_assign_percent,master_adminid,main_type  from %s where campus_id=%u order by main_type  asc " ,
                                self::DB_TABLE_NAME, $campus_id);
        return $this->main_get_list($sql);
    }

    public function get_group_list_campus($page_num){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid,main_type from %s where main_type in (1,2)  " ,
                                self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function set_campus_info($campus_id){
        $sql = $this->gen_sql_new("update %s set campus_id = 0 where campus_id = %u",
                                  self::DB_TABLE_NAME,
                                  $campus_id
        );
        $this->main_update($sql);
    }

    public function get_seller_master_adminid_by_campus_id($campus_id){
        $where_arr=[
            ["campus_id=%u",$campus_id,-1]  
        ];
        $sql = $this->gen_sql_new("select master_adminid from %s "
                                  ." where %s and main_type=2",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_son_adminid($adminid){
        $sql = $this->gen_sql_new(
            " select g.master_adminid,n.master_adminid group_adminid,u.adminid "
            ." from %s g "
            ." left join %s n on n.up_groupid = g.groupid "
            ." left join %s u on u.groupid = n.groupid "
            ." where g.master_adminid = %u "
            ,self::DB_TABLE_NAME
            ,t_admin_group_name::DB_TABLE_NAME
            ,t_admin_group_user::DB_TABLE_NAME
            ,$adminid
        );
        $master_adminid = $this->main_get_list($sql);
        if($master_adminid){
            return $master_adminid;
        }else{
            $sql = $this->gen_sql_new(
                " select n.master_adminid group_adminid,u.adminid "
                ." from %s n "
                ." left join %s u on u.groupid = n.groupid "
                ." where n.master_adminid = %u "
                ,t_admin_group_name::DB_TABLE_NAME
                ,t_admin_group_user::DB_TABLE_NAME
                ,$adminid
            );
            $group_adminid = $this->main_get_list($sql);
            if($group_adminid){
                return $group_adminid;
            }else{
                return [];
            }
        }
    }

    public function update_by_up_groupid($up_groupid) {
        $sql = $this->gen_sql_new("update %s set up_groupid = 0 where up_groupid=%u",
                                  self::DB_TABLE_NAME
                                  ,$up_groupid );
        return $this->main_update($sql);
    }

    public function get_main_group_list($page_num,$main_type){
        $sql=$this->gen_sql_new("select groupid,group_name,master_adminid from %s where main_type=%u  " ,
                                self::DB_TABLE_NAME, $main_type);
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function is_master($adminid){
        $sql = $this->gen_sql_new("select groupid from %s where master_adminid='$adminid'",self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }

    public function get_son_adminid_by_up_groupid($majordomo_groupid){
        $sql = $this->gen_sql_new(
            " select mg.up_groupid,u.adminid "
            ." from %s mg "
            ." left join %s g on g.up_groupid = mg.groupid "
            ." left join %s u on u.groupid = g.groupid "
            ." where mg.up_groupid = %u "
            ,self::DB_TABLE_NAME//mg
            ,t_admin_group_name::DB_TABLE_NAME//g
            ,t_admin_group_user::DB_TABLE_NAME//u
            ,$majordomo_groupid
        );
        return $this->main_get_list($sql);
    }

    public function get_groupid_by_adminid($adminid){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'master_adminid', $adminid);
        $sql = $this->gen_sql_new(
            " select groupid "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    //获得上一级主管id
    public function get_major_master_adminid($adminid){
        $sql = $this->gen_sql_new("select m.master_adminid"
                                  ." from %s a left join %s m on a.up_groupid = m.groupid"
                                  ." where a.master_adminid = %u",
                                  self::DB_TABLE_NAME,
                                  t_admin_majordomo_group_name::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_value($sql);

    }

}
