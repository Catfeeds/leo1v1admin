<?php
namespace App\Models;

/**
 * @property t_order_info  $t_order_info
 * @property t_kaoqin_machine_adminid $t_kaoqin_machine_adminid
 * @property t_user_info $t_user_info
 * @property t_admin_users  $t_admin_users
 */
class t_manager_info extends \App\Models\Zgen\z_t_manager_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tt() {
        $this->main_get_value($sql);
        $this->main_get_row($sql);
        $this->main_get_list($sql);
        $this->main_get_list_by_page($sql,$page_info);
        //t_adid_to_adminid::DB_TABLE_NAME
        //t_student_info::DB_TABLE_NAME
    }
    public function get_account_by_uid($uid) {
        $sql = $this->gen_sql_new("select account from %s where uid='%s'",
                                  self::DB_TABLE_NAME, $uid);
        return $this->main_get_value($sql);
    }
    public function get_phone_by_uid($uid) {
        $sql = $this->gen_sql_new("select phone from %s where uid='%s'",
                                  self::DB_TABLE_NAME, $uid);
        return $this->main_get_value($sql);
    }

    public function get_info_by_tquin($tquin, $field_str="*") {
        $sql=$this->gen_sql_new("select  $field_str from %s where tquin=%u ", self::DB_TABLE_NAME,$tquin );
        return $this->main_get_row($sql);
    }


    public function get_list_for_select($id,$gender, $nick_phone,  $page_num,$main_type)
    {
        $where_arr = array(
            array( "uid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%s%%' or name like '%s%%' or phone like '%s%%'  )",
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone));
        }
        $where_arr[]=["account_role=%u", $main_type, -1];

        $sql =  $this->gen_sql_new( "select uid as id ,  account as  nick,   name as realname,  phone,'' as gender  from %s    where %s ",
                       self::DB_TABLE_NAME,  $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
        $this->main_update($sql);
    }
    public function get_product_user_list_for_select($id,$gender,$nick_phone,$page_num,$main_type)
    {
        $main_type = 10;
        $where_arr = array(
            array( "uid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%%%s%%' or name like '%%%s%%'  )",
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone));
        }
        $where_arr[]=["account_role=%u", $main_type, -1];

        $sql =  $this->gen_sql_new( "select uid as id ,  account as  nick,   name as realname,  phone,'' as gender  from %s    where %s ",
                       self::DB_TABLE_NAME,  $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
        $this->main_update($sql);
    }

    public function get_list_for_select_new($id_arr,$gender, $nick_phone,  $page_num,$main_type)
    {
        $where_arr = array(
        );
        $this->where_arr_add_int_or_idlist($where_arr,'uid',$id_arr);
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%%%s%%' or name like '%%%s%%'  )",
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone));
        }
        $where_arr[]=["account_role=%u", $main_type, -1];
        $sql =  $this->gen_sql_new( "select uid as id ,  account as  nick,   name as realname,  phone,'' as gender  from %s    where %s ",
                                    self::DB_TABLE_NAME,  $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
        $this->main_update($sql);
    }

    public function get_group_master_for_select($id,$gender, $nick_phone,  $page_num,$main_type )
    {
        $where_arr = array(
            array( "uid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%%%s%%' or name like '%%%s%%'  )",
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone));
        }
        $where_arr[]=["main_type=%u", $main_type, -1];
        $sql =  $this->gen_sql_new( "select uid as id ,  account as  nick,   name as realname,  phone,'' as gender  from %s m, %s g where master_adminid=uid and   %s and del_flag=0",
                                    self::DB_TABLE_NAME,
                                    t_admin_group_name::DB_TABLE_NAME,
                                    $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_group_user_list_for_select($id,$gender, $nick_phone,  $page_num, $groupid )
    {
        $where_arr = array(
            array( "uid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(account like '%%%s%%' or name like '%%%s%%'  )",
                                  $this->ensql($nick_phone),
                                  $this->ensql($nick_phone));
        }
        $where_arr[]=["groupid=%u", $groupid, -1];
        $sql =  $this->gen_sql_new( "select uid as id ,  account as  nick,   name as realname,  phone,'' as gender  from %s m, %s g where adminid=uid and   %s and del_flag=0",
                                    self::DB_TABLE_NAME,
                                    t_admin_group_user::DB_TABLE_NAME,
                                    $where_arr );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_permission_list($account )
    {
        $sql = sprintf("select permission , power from %s where account = '%s' ",
                       self::DB_TABLE_NAME, $this->ensql( $account));
        $row = $this->main_get_row( $sql);
        $power_str = $row["permission"].",".$row["power"] ;
        $grpid_arr = explode(',', $power_str);
        $perms = "";
        foreach($grpid_arr as $key => $value){
            $sql = sprintf("select group_authority from %s where groupid = %u",
                           t_authority_group::DB_TABLE_NAME,
                            $value);
            $perms .= "," . $this->main_get_value( $sql);
        }

        return explode(',',$perms);
    }

    public function get_manager_permission()
    {
        $sql = sprintf("select account, permission from %s where del_flag = 0",
                       self::DB_TABLE_NAME);

        return $this->main_get_list( $sql );
    }

    public function get_xx( ) {
        $sql= $this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME,
            t_adid_to_adminid::DB_TABLE_NAME
        );
    }

    public function check_permission( $account,$permission ) {
        $sql = $this->gen_sql("select permission from %s where account = '%s' ",
                       self::DB_TABLE_NAME,
                       $account
        );
        $grpid = $this->main_get_value( $sql);
        $grpid_arr = explode(',', $grpid);
        $perms = "";
        foreach($grpid_arr as $key => $value){
            $sql = sprintf("select group_authority from %s where groupid = %u",
                           \App\Models\Zgen\z_t_authority_group::DB_TABLE_NAME,
                           $value
            );
            $perms .= "," . $this->main_get_value( $sql);
        }
        $perm_arr = explode(',',$perms);
        if(in_array($permission, $perm_arr))
            return true;
        return false;
    }

    public function get_all_manager($page_num,$uid,$user_info,$has_question_user,$creater_adminid,$account_role,$del_flag,$cardid,$tquin ,$day_new_user_flag,$seller_level=-1,$adminid=-1,$fulltime_teacher_type=-1,$call_phone_type=-1,$adminid_list=[])
    {
        $where_arr=[
            [  "t1.creater_adminid =%u ", $creater_adminid,  -1] ,
            [  "t1.cardid =%u ", $cardid,  -1] ,
            [  "t1.tquin =%u ", $tquin,  -1] ,
            [  "t1.call_phone_type =%u ", $call_phone_type ,  -1] ,
            [  "t1.account_role =%u ", $account_role,  -1] ,
            [  "t1.day_new_user_flag =%u ", $day_new_user_flag ,  -1] ,
            [  "t1.fulltime_teacher_type =%u ", $fulltime_teacher_type ,  -1] ,
        ];
        if ($user_info >0 ) {
            if  ($user_info < 10000) {
                $where_arr[]=[  "t1.uid=%u", $user_info, "" ] ;
            }else{
                $where_arr[]=[  "t1.phone like '%%%s%%'", $user_info, "" ] ;
            }
        }else{
            if ($user_info!=""){
                $where_arr[]=array( "(t1.account like '%%%s%%' or  t1.name like '%%%s%%')",
                                    array(
                                        $this->ensql($user_info),
                                        $this->ensql($user_info)));
            }
        }
        if ( !$has_question_user  ) {
            $where_arr[] = [  "t1.account not like 'c\_%s%%'", "",  1] ;
            $where_arr[] = [  "t1.account not like 'q\_%s%%'", "",  1] ;
        }

        $this->where_arr_add_int_or_idlist($where_arr,"seller_level", $seller_level);
        $this->where_arr_add_int_or_idlist($where_arr,"t1.del_flag", $del_flag);
        $this->where_arr_add_int_or_idlist($where_arr,"t1.uid", $adminid);
        $this->where_arr_adminid_in_list($where_arr,"t1.uid", $adminid_list );


        $sql =$this->gen_sql_new("select t1.no_update_seller_level_flag,t1.create_time,leave_member_time,become_member_time,call_phone_type, call_phone_passwd, fingerprint1 ,ytx_phone,wx_id,up_adminid,day_new_user_flag, account_role,creater_adminid,t1.uid,t1.del_flag,t1.account,t1.seller_level, name,nickname, email, phone,password, permission,tquin,wx_openid ,cardid,become_full_member_flag,main_department,fulltime_teacher_type, seller_student_assign_type  from %s t1  left join %s t2 on t1.uid=t2.id    left join %s t_wx on t1.wx_openid =t_wx.openid  where  %s  order by t1.uid desc",
                                 self::DB_TABLE_NAME,
                                 t_admin_users::DB_TABLE_NAME,
                                 t_wx_user_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_num,10);
    }

    public function get_product_department_memeber_list($page_info,$user_info,$adminid,$post,$department,$department_group,$main_department){
        $where_arr=[
             "del_flag=0",
            [  "uid =%u ", $adminid,  -1] ,
            [  "post =%u ", $post,  -1] ,
            [  "department =%u ", $department,  -1] ,
            [  "department_group =%u ", $department_group,  -1] ,
        ];
        if($user_info){
            if ($user_info >0 ) {
                $where_arr[]=[  "phone like '%%%s%%'", $user_info, "" ] ;
            }else{
                $where_arr[]=[  "account like '%%%s%%'", $user_info, "" ] ;
            }
            if($main_department != -1){
                $where_arr[] = "(main_department=0 or main_department=".$main_department.")";
            }

        }else{
            $where_arr[] =[  "main_department =%u ", $main_department,  -1] ;
        }
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list_by_page( $sql,$page_info,10);
    }

    public function get_all_assistant_renew($start_time,$end_time )
    {
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status in (1,2,3)" ,
        ];
        $sql =$this->gen_sql_new("select  o.sys_operator,count(distinct userid) all_student,sum(o.price) all_price,sum(o.lesson_total*o.default_lesson_count) all_total,sum(if(contract_type=1,lesson_total*default_lesson_count,0)) give_total from %s m,%s o where m.account = o.sys_operator and m.account_role=1 and %s group by sys_operator order by all_price desc",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_user_permission($account = array())
    {
        if (!is_array($account) || empty($account)) {
            return false;
        }

        $cond_str = '';
        $comma = '';
        foreach ($account as $key => $value) {
            $cond_str .= $comma . $this->ensql($value);
            if ($comma != ',') {
                $comma = ',';
            }
        }

        $sql = sprintf("select account, permission, power from %s where account in ('%s') "
                       ,self::DB_TABLE_NAME
                       ,$cond_str
        );
        return $this->main_get_list($sql);
    }

    public function add_user_name($user_name)
    {
        $this->addData('user_name', $user_name);
        $this->addData('create_time', time());

        $ret_insert = $this->dataInsert(SELF::DB_TABLE_NAME);
        if ($ret_insert == true) {
            return $this->db_insert_id;
        } else {
            return false;
        }
    }

    //获取浏览的用户信息
    public function get_show_manage_info($uid){
        $sql=sprintf(
            " select m.*,g.groupid from %s m "
            ." left join %s g on g.master_adminid = m.uid "
            ." where uid= %u",
            SELF::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            $uid
        );
        return $this->main_get_row($sql);
    }

    public function get_info_by_account($account, $field_str="*") {
        $sql=$this->gen_sql("select %s from  %s where account='%s'",
                            $field_str,
                            self::DB_TABLE_NAME,
                            $account
        );
        return $this->main_get_row($sql);
    }

    public function get_id_by_account($account) {
        $sql=$this->gen_sql("select uid from  %s where account='%s'",
                            self::DB_TABLE_NAME,
                            $account
        );
        return $this->main_get_value($sql);
    }

    public function get_id_by_phone($phone) {
        $sql=$this->gen_sql("select uid from  %s where phone='%s'",
                            self::DB_TABLE_NAME,
                            $phone
        );
        return $this->main_get_value($sql);
    }

    public function del_ass_manager($uid)
    {
        /*
        $sql = sprintf("delete from %s  where uid = %u ",
                       self::DB_TABLE_NAME,
                       $uid
        );
        $this->main_update( $sql  );

        $sql2 = sprintf("delete from %s where id = %u",
                        \App\Models\Zgen\z_t_admin_users::DB_TABLE_NAME,
                        $uid
        );
        return $this->main_update($sql2);
        */
    }
    public function get_sim_role($account){
        $sql   =    $this->gen_sql("select account_role from %s where account = '%s'",
                                   self::DB_TABLE_NAME,
                                   $account
        );
        return $this->main_get_value($sql);
    }

    public function get_del_flag_new($account){
        $sql   =    $this->gen_sql("select del_flag from %s where account = '%s'",
                                   self::DB_TABLE_NAME,
                                   $account
        );
        return $this->main_get_value($sql);
    }

    public function set_only_role($account,$role){
        $sql   =    $this->gen_sql("update %s set account_role = %u where account = '%s'",
                                   self::DB_TABLE_NAME,
                                   $role,
                                   $account
        );
        return $this->main_get_value($sql);
    }

    public function get_power_group_user_list($groupid)
    {
        $sql = $this->gen_sql("select uid, account,name, permission from %s where  del_flag = 0"
                              ,self::DB_TABLE_NAME
        );
        $list=$this->main_get_list($sql);
        $ret_list=[];
        foreach($list as $item ) {
            $grpid = $item["permission"];
            $grpid_arr = explode(',', $grpid);
            if(in_array($groupid, $grpid_arr)) {
                $ret_list[]=$item;
            }
        }

        return $ret_list;
    }

    public function get_power_group_user_list_sec($groupid){
        $sql = $this->gen_sql("select user.uid, user.account,user.name, user.permission,GROUP_CONCAT(auth.group_name) as permit_name from %s user
                              left join %s power on user.uid = power.uid
                              left join %s auth on power.gid = auth.groupid where user.del_flag = 0 and power.gid = %u group by user.uid",
                              self::DB_TABLE_NAME,
                              t_user_power_group::DB_TABLE_NAME,
                              t_authority_group::DB_TABLE_NAME,
                              $groupid);
        return $this->main_get_list($sql);
    }

    public function opt_group($uid,$opt_type, $groupid) {
        $arr=$this->get_show_manage_info($uid);
        $permission=$arr["permission"];

        $perm_a = explode(',',$permission);
        if ($opt_type=="add") {
            $find=false;
            foreach($perm_a as $key => $value){
                if($value == $groupid){
                    $find=true;
                }
            }
            if ($find==false) {
                    $perm_a[]=$groupid;
            }

        }else{
            foreach($perm_a as $key => $value){
                if($value == $groupid){
                    unset($perm_a[$key]);
                }
            }
        }
        $permission = implode(',',$perm_a);
        return $this->field_update_list($uid,[
          "permission"=>  $permission
        ]);
    }

    public function get_permission_info_by_uid($uid)
    {
        $sql = $this->gen_sql("select permission from %s where del_flag = 0 and uid = %u ",
                              self::DB_TABLE_NAME,
                              $uid
        );

        return $this->main_get_list( $sql );

    }

    public function get_info_for_wx_openid($wx_openid ) {
        $sql=$this->gen_sql_new(" select * from %s where wx_openid='%s'"
                           ,self::DB_TABLE_NAME,$wx_openid );
        return $this->main_get_row($sql);
    }

    public function get_wx_openid_by_account($account) {
        $sql = $this->gen_sql_new("select wx_openid from %s where account='%s'",
                                 self::DB_TABLE_NAME, $account);
        return $this->main_get_value($sql);
    }

    public function send_wx_todo_msg_by_adminid ($adminid, $from_user, $header_msg,$msg,$url="",$desc="点击进入管理系统操作"  ) {
        $account=$this->get_account($adminid);
        \App\Helper\Utils::logger("SEND TODO MSG: $account ");

        return $this->send_wx_todo_msg($account,$from_user,$header_msg,$msg,$url,$desc);
    }

    public function send_wx_todo_msg($account, $from_user, $header_msg,$msg="",$url="",$desc="点击进入管理系统操作"){
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

        $ret = $this->send_template_msg($account,$template_id,[
            "first"    => $header_msg,
            "keyword1" => $from_user,
            "keyword2" => $msg,
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => $desc,
        ],$url);

        return $ret;
    }

    public function send_template_msg ($account,  $template_id, $data ,$url="http://admin.leo1v1.com" ) {
        if (substr($url,0,7 )!="http://") {
            $url="http://admin.leo1v1.com/".trim($url,"/ \t");
        }

        $wx     = new \App\Helper\Wx();
        $openid = $this->get_wx_openid_by_account($account);
        if ($openid) {
            $ret = $wx->send_template_msg($openid,$template_id,$data ,$url);
        }else{
            return false;
        }
        return $ret;
    }

    public function get_uid_by_cardid($cardid) {
        $sql=$this->gen_sql_new("select  uid from %s where cardid = %u ",
                                self::DB_TABLE_NAME, $cardid );
        return $this->main_get_value($sql);

    }
    public function get_uid_by_phone($phone) {
        $sql=$this->gen_sql_new("select  uid from %s where phone = %u ",
                                self::DB_TABLE_NAME, $phone );
        return $this->main_get_value($sql);

    }
    public function get_uid_by_tquin($tquin) {
        $sql=$this->gen_sql_new("select  uid from %s where tquin = %u",
                                self::DB_TABLE_NAME, $tquin);
        return $this->main_get_value($sql);

    }

    public function get_user_info_for_tq ($tquin) {
        $sql=$this->gen_sql_new("select  uid,account_role from %s where tquin = %u",
                                self::DB_TABLE_NAME, $tquin);
        return $this->main_get_row($sql);

    }


    public function set_cardid_null( $uid) {
        $sql=$this->gen_sql_new("update %s set cardid=NULL where uid=%u",
                                self::DB_TABLE_NAME, $uid);
        return $this->main_update($sql);
    }
    //@param:$sales_assistant_flag 获取后台管理员中的销售助教
    public function get_admin_member_list(  $main_type = -1 ,$adminid=-1,$sales_assistant_flag=0){
        $where_arr=[
            [ "m.main_type =%u ", $main_type,-1] ,
            [  "am.account not like 'c\_%s%%'", "",  1] ,
            [  "am.account not like 'q\_%s%%'", "",  1] ,
        ];
        $this->where_arr_add_int_field($where_arr,"u.adminid",$adminid);
        if($sales_assistant_flag == 1)
            $where_arr[]='am.account_role in (1,2)';

        $sql = $this->gen_sql_new(
            "select g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,".
            "am.uid adminid,am.account,am.seller_level,".
            "am.create_time,am.become_member_time,am.leave_member_time,".
            "am.del_flag,am.seller_level,".
            "(case am.account_role when 1 then '助教' when 2 then '销售' else '其他' end) as account_role".
            " from %s am ".
            " left join %s u on am.uid = u.adminid".
            " left join %s g on u.groupid = g.groupid".
            " left join %s m on g.up_groupid = m.groupid".
            " left join %s ss on am.uid = ss.admin_revisiterid ".
            " left join %s t on ss.userid = t.userid ".
            " where %s ".
            "  group by am.uid",
            self::DB_TABLE_NAME,//am
            t_admin_group_user::DB_TABLE_NAME,//u
            t_admin_group_name::DB_TABLE_NAME,//g
            t_admin_main_group_name::DB_TABLE_NAME,//m
            t_seller_student_new::DB_TABLE_NAME,//ss
            t_test_lesson_subject::DB_TABLE_NAME,//t
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_admin_member_list_tmp(  $month=-1,$main_type = -1 ,$adminid=-1,$group_adminid_list=[]){
        $where_arr=[
            [ "tm.main_type =%u ", $main_type,-1] , // 测试
            // [ "m.main_type =%u ", $main_type,-1] ,
            [  "am.account not like 'c\_%s%%'", "",  1] ,
            [  "am.account not like 'q\_%s%%'", "",  1] ,
            // "(am.leave_member_time>$month or am.leave_member_time =0)"
            "((am.leave_member_time>$month and am.del_flag=1) or am.del_flag =0)",
        ];
        if(count($group_adminid_list)>0){
            $this->where_arr_add_int_or_idlist($where_arr,'u.adminid',$group_adminid_list);
        }else{
            $this->where_arr_add_int_field($where_arr,"u.adminid",$adminid);
        }

        $sql = $this->gen_sql_new("select tm.group_name first_group_name, g.main_type,"
                                  ."g.group_name group_name, g.groupid groupid,m.group_name up_group_name,".
                                  "am.uid adminid,am.account,".
                                  "am.create_time,am.become_member_time,am.leave_member_time,am.del_flag,am.seller_level ".
                                  " from %s am ".
                                  " left join %s u on am.uid = u.adminid".
                                  " left join %s g on u.groupid = g.groupid".
                                  " left join %s m on g.up_groupid = m.groupid".
                                  " left join %s ss on am.uid = ss.admin_revisiterid ".
                                  " left join %s t on ss.userid = t.userid ".
                                  " left join %s tm on tm.groupid=m.up_groupid".
                                  " where %s ".
                                  "  group by am.uid",
                                  self::DB_TABLE_NAME,//am
                                  t_admin_group_user::DB_TABLE_NAME,//u
                                  t_admin_group_name::DB_TABLE_NAME,//g
                                  t_admin_main_group_name::DB_TABLE_NAME,//m
                                  t_seller_student_new::DB_TABLE_NAME,//ss
                                  t_test_lesson_subject::DB_TABLE_NAME,//t
                                  t_admin_majordomo_group_name::DB_TABLE_NAME,//tm
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_admin_member_list_new( $month, $main_type = -1 ,$adminid=-1,$group_adminid_list=[]){
        $where_arr=[
            [ "m.main_type =%u ", $main_type,-1] ,
            [  "am.account not like 'c\_%s%%'", "",  1] ,
            [  "am.account not like 'q\_%s%%'", "",  1] ,
             // "(am.leave_member_time>$month or am.leave_member_time =0)",
            "((am.leave_member_time>$month and am.del_flag=1) or am.del_flag =0)",
        ];
        if(count($group_adminid_list)>0){
            $this->where_arr_add_int_or_idlist($where_arr,'u.adminid',$group_adminid_list);
        }else{
            $this->where_arr_add_int_field($where_arr,"u.adminid",$adminid);
        }

        $sql = $this->gen_sql_new("select tm.group_name first_group_name,g.main_type,g.group_name group_name,"
                                  ."g.groupid groupid,m.group_name up_group_name,am.uid adminid,".
                                  "am.account,am.seller_level, ".
                                  "am.create_time,am.become_member_time,am.leave_member_time,am.del_flag,am.seller_level ".
                                  " from %s am left join %s u on (am.uid = u.adminid and u.month=%u)".
                                  " left join %s g on (u.groupid = g.groupid and g.month=%u)".
                                  " left join %s m on (g.up_groupid = m.groupid and m.month=%u)".
                                  " left join %s ss on am.uid = ss.admin_revisiterid ".
                                  " left join %s t on ss.userid = t.userid ".
                                  " left join %s tm on tm.groupid = m.up_groupid and tm.month = %u".
                                  // " where %s and am.del_flag=0".
                                  " where %s ".
                                  "  group by am.uid",
                                  self::DB_TABLE_NAME,//am
                                  t_group_user_month::DB_TABLE_NAME,//u
                                  $month,
                                  t_group_name_month::DB_TABLE_NAME,//g
                                  $month,
                                  t_main_group_name_month::DB_TABLE_NAME,//m
                                  $month,
                                  t_seller_student_new::DB_TABLE_NAME,//ss
                                  t_test_lesson_subject::DB_TABLE_NAME,//t
                                  t_main_major_group_name_month::DB_TABLE_NAME,
                                  $month,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }


    public function get_seller_month_money_info($start_time){
        $sql = $this->gen_sql_new("select g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,".
                                  " am.uid adminid,am.become_member_time,".
                                  " am.account,mm.money,mm.personal_money,mt.month_money, mm.test_lesson_count".
                                  " from %s am left join %s u on am.uid = u.adminid".
                                  " left join %s g on u.groupid = g.groupid".
                                  " left join %s m on g.up_groupid = m.groupid".
                                  " left join %s ss on am.uid = ss.admin_revisiterid ".
                                  " left join %s t on ss.userid = t.userid ".
                                  " left join %s mm on (am.uid = mm.adminid and mm.month = '%s')".
                                  " left join %s mt on (g.groupid = mt.groupid and mt.month = '%s') ".
                                  " group by am.uid",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_month_money_target::DB_TABLE_NAME,
                                  $start_time,
                                  t_admin_group_month_time::DB_TABLE_NAME,
                                  $start_time
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }

    public function get_assistant_month_target_info($start_time,$up_master_adminid=-1,$account_id=74){
        $where_arr=[
            "am.account_role=1 ",
            "am.del_flag =0 "
        ];
        if($up_master_adminid !=-1){
            $where_arr[] = ["m.master_adminid=%u",$account_id,-1];
        }

        $sql = $this->gen_sql_new("select g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,".
                                  " am.uid adminid".
                                  " from %s am left join %s u on am.uid = u.adminid".
                                  " left join %s g on u.groupid = g.groupid".
                                  " left join %s m on g.up_groupid = m.groupid".
                                  " where %s group by am.uid",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });

    }

    public function get_tquin_uid_map() {
        $sql=$this->gen_sql_new("select uid,tquin,account_role from %s "
                                , self::DB_TABLE_NAME  );
        return $this->main_get_list($sql,function($item){
            return $item["tquin"];
        });
    }


    public function get_uid_account_map() {
        $sql=$this->gen_sql_new("select uid,account from %s "
                                , self::DB_TABLE_NAME  );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_max_get_new_user_count($adminid,&$self_count,&$order_count) {
        $seller_level= $this->get_seller_level($adminid);
        $config=\App\Helper\Config::get_seller_new_user_day_count();
        $max_count=$config[$seller_level];
        $start_time =strtotime(date("Y-m-d"));
        $end_time= $start_time+86400;
        $start_time-=14*86400;
        $account=$this->get_account($adminid);

        $order_count=$this->t_order_info->get_new_order_seller_new_user_count($account,$start_time,$end_time );
        $self_count=$max_count;
        return $max_count+$order_count;
        //$order
    }

    public function get_manage_info_by_role($role){
        /*$where_arr = [
            [""]
            ];*/
    }

    public function get_seller_list( $seller_student_assign_type=-1) {
        $time_now = strtotime(date('Y-m-d'));
        $where_arr=[
            ["mi.seller_student_assign_type=%u", $seller_student_assign_type, -1],
            "mi.seller_level<700",
            // "seller_level>0",
            "mi.del_flag=0",
            //\App\Enums\Eaccount_role::V_2
            "mi.account_role=2", //cc
            // "day_new_user_flag=1",
        ];

        $sql=$this->gen_sql_new(
            "select mi.uid,mi.seller_level,mi.account,if(cdt.score>0,1,0) is_top ".
            "from %s mi " .
            'left join %s cdt on mi.uid = cdt.uid and add_time = %u '.
            "where %s order by  seller_level asc ",
            self::DB_TABLE_NAME,
            t_cc_day_top::DB_TABLE_NAME,
            $time_now,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_list_new($account_role){
        $where_arr = [
            ["m.account_role =%u ", $account_role,  -1] ,
        ];
        $sql=$this->gen_sql_new(
            "select uid,account_role,become_member_time  "
            ." from %s m "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_list_new_two($account_role){
        $where_arr = [
            ["m.account_role =%u",$account_role,-1],
        ];
        $sql=$this->gen_sql_new(
            "select uid,account,account_role,m.create_time,m.seller_level,"
            ."m.face_pic,m.level_face_pic,no_update_seller_level_flag,m.become_member_time,"
            ."g.level_face,g.level_goal,g.seller_level_goal,g.num "
            ." from %s m "
            ." left join %s g on g.seller_level=m.seller_level "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_seller_level_goal::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_seller_list_new_three($account_role){
        $where_arr = [
            ["m.account_role =%u ",$account_role,  -1] ,
            "m.del_flag =0 ",
        ];
        $sql=$this->gen_sql_new(
            "select uid,account_role,create_time,seller_level,g.level_face  "
            ." from %s m "
            ." left join %s g on g.seller_level = m.seller_level "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_seller_level_goal::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_jw_teacher_list(){
        $time=time();
        $sql = $this->gen_sql_new("select uid,tr.require_id from %s m".
                                  " left join %s tr on (m.uid = tr.accept_adminid ".
                                  " and tr.test_lesson_student_status=200 ".
                                  " and tr.jw_test_lesson_status = 0 and tr.is_green_flag=0 ".
                                  " and tr.curl_stu_request_test_lesson_time >%u)".
                                  " where  account_role = 3 and admin_work_status = 1 having(tr.require_id is null)"
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,$time
        );
        $res =  $this->main_get_list($sql);
        $arr = [];
        foreach($res as $val){
            $arr[$val["uid"]] = $val["uid"];
        }
        return $arr;
    }

    public function get_jw_teacher_list_detail(){
        $time=time();
        $sql = $this->gen_sql_new("select uid,tr.require_id from %s m".
                                  " left join %s tr on (m.uid = tr.accept_adminid ".
                                  " and tr.test_lesson_student_status=200 ".
                                  " and tr.jw_test_lesson_status = 0 and tr.is_green_flag=0 ".
                                  " and tr.curl_stu_request_test_lesson_time >%u)".
                                  " where  account_role = 3 and admin_work_status = 1 "
                                  ,self::DB_TABLE_NAME
                                  ,t_test_lesson_subject_require::DB_TABLE_NAME
                                  ,$time
        );
        return $this->main_get_list($sql);
    }

    public function get_jw_teacher_list_all(){
        $sql = $this->gen_sql_new("select uid from %s ".
                                  " where account_role = 3 and admin_work_status = 1 "
                                  ,self::DB_TABLE_NAME
        );
        $res =  $this->main_get_list($sql);
        $arr = [];
        foreach($res as $val){
            $arr[$val["uid"]] = $val["uid"];
        }
        return $arr;
    }

   public function get_jw_teacher_list_leader(){
       $sql = $this->gen_sql_new("select uid from %s ".
                                 " where account_role = 3 and admin_work_status = 1 and uid in (436,343,434,418,723) "
                                 ,self::DB_TABLE_NAME
       );
        $res =  $this->main_get_list($sql);
        $arr = [];
        foreach($res as $val){
            $arr[$val["uid"]] = $val["uid"];
        }
        return $arr;

   }

    public function get_jw_teacher_list_new($del_flag=0){
        $where_arr=[
            ["del_flag=%u",$del_flag,-1]
        ];
        $sql = $this->gen_sql_new("select uid,account from %s ".
                                  " where account_role = 3 and %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return  $this->main_get_list($sql);
    }

    public function get_req_list(){
        $sql = $this->gen_sql_new("select require_id,test_lesson_student_status,jw_test_lesson_status from %s where accept_adminid=343 and test_lesson_student_status=200 and jw_test_lesson_status = 0",
                                  t_test_lesson_subject_require::DB_TABLE_NAME
        );
         return  $this->main_get_list($sql);
    }

    public function get_teacher_info_by_adminid($adminid){
        $sql = $this->gen_sql_new("select t.teacherid,t.subject,t.second_subject,t.third_subject,m.account_role,"
                                  ." t.grade_part_ex,t.train_through_new_time,m.fulltime_teacher_type  "
                                  ." from %s m "
                                  ." join %s t on m.phone = t.phone "
                                  ." where m.uid = %u"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$adminid
        );
        return $this->main_get_row($sql);
    }
    public function get_adminid_by_phone($phone){
        $sql = $this->gen_sql_new("select uid from %s where phone =%u",self::DB_TABLE_NAME,$phone);
        return $this->main_get_value($sql);
    }

    public function tongji_assistant_revisit_info($start_time,$end_time){
        $where_arr=[
            ["r.revisit_time >=%u",$start_time,-1],
            ["r.revisit_time <%u",$end_time,-1]
        ];
        $sql = $this->gen_sql_new("select uid,count(*) revisit_count "
                                  ." from %s m left join %s r on m.account = r.sys_operator"
                                  ." where %s and m.account_role = 1 and del_flag =0 group by uid",
                                  self::DB_TABLE_NAME,
                                  t_revisit_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_assistant_stu_info_new($end_time){
        $sql = $this->gen_sql_new("select uid,ass_assign_time "
                                  ." from %s m left join %s a on m.phone = a.phone"
                                  ." left join %s s on s.assistantid = a.assistantid"
                                  ." where m.account_role = 1 and m.del_flag =0 and s.type =0 and s.ass_assign_time <= %u",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $end_time
        );
        return $this->main_get_list($sql);

    }

    public function get_assistant_lesson_count_info($start_time,$end_time,$assistantid=-1) {

        $where_arr=[
            ["l.assistantid=%u",$assistantid,-1]  
        ];
        $sql=$this->gen_sql_new("select uid,sum(l.lesson_count) as lesson_count, count(distinct l.userid ) as user_count"
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." where s.is_test_user=0 and l.lesson_start >=%u and l.lesson_start<%u  and l.lesson_status =2 and l.confirm_flag in (0,1,3)  and l.lesson_type in (0,1,3)"
                                . " and l.lesson_del_flag=0 and l.assistantid <> 59329 and m.account_role=1  and m.uid <>74 and %s "
                                ." group by m.uid  ",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $start_time,$end_time,$where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }
    public function get_assistant_lesson_count_info_all($start_time,$end_time) {

        $sql=$this->gen_sql_new("select sum(l.lesson_count) as lesson_count "
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." where s.is_test_user=0 and l.lesson_start >=%u and l.lesson_start<%u  and l.lesson_status =2 and l.confirm_flag in (0,1,3)  and l.lesson_type in (0,1,3)"
                                . " and l.lesson_del_flag=0 and l.assistantid <> 59329 and m.account_role=1 and m.uid <>74  ",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $start_time,$end_time
        );

        return $this->main_get_value($sql);
    }


    public function get_assistant_lesson_money_info($start_time,$end_time)
    {
        $sql=$this->gen_sql_new("select uid,sum(lo.price) lesson_price"
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." left join %s lo on l.lessonid = lo.lessonid"
                                ." where s.is_test_user=0 "
                                ." and l.lesson_start >=%u "
                                ." and l.lesson_start<%u "
                                ." and l.lesson_status =2 "
                                ." and l.confirm_flag in (0,1,3) "
                                ." and l.lesson_type in (0,1,3)"
                                ." and l.lesson_del_flag=0 "
                                ." and l.assistantid <> 59329 "
                                ." and m.account_role=1 and m.uid <>74"
                                ." group by m.uid",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                t_order_lesson_list::DB_TABLE_NAME,
                                $start_time,$end_time
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_assistant_lesson_money_info_all($start_time,$end_time)
    {
        $sql=$this->gen_sql_new("select sum(lo.price) lesson_price"
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." left join %s lo on l.lessonid = lo.lessonid"
                                ." where s.is_test_user=0 "
                                ." and l.lesson_start >=%u "
                                ." and l.lesson_start<%u "
                                ." and l.lesson_status =2 "
                                ." and l.confirm_flag in (0,1,3) "
                                ." and l.lesson_type in (0,1,3)"
                                ." and l.lesson_del_flag=0 "
                                ." and l.assistantid <> 59329 "
                                ." and m.account_role=1 and m.uid <>74",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                t_order_lesson_list::DB_TABLE_NAME,
                                $start_time,$end_time
        );

        return $this->main_get_value($sql);
    }



    public function get_assistant_lesson_count_info_old($start_time,$end_time) {
        $start = strtotime(date("2017-03-05"));
        $sql=$this->gen_sql_new("select uid,sum(l.lesson_count) as lesson_count, count(distinct l.userid ) as user_count"
                            ." from  %s m left join %s a on m.phone = a.phone"
                            ." left join %s l on l.assistantid = a.assistantid"
                            ." left join %s s on l.userid = s.userid"
                            ." where s.is_test_user=0 and l.lesson_start >=%u and l.lesson_start<%u  and l.lesson_status =2 and l.confirm_flag <>2  and l.lesson_type in (0,1,3)"
                            . " and l.lesson_del_flag=0 and l.assistantid <> 59329 and m.account_role=1 and m.del_flag =0 and m.uid <>74  and s.ass_assign_time <%u"
                            ." group by m.uid  ",
                            self::DB_TABLE_NAME,
                            t_assistant_info::DB_TABLE_NAME,
                            t_lesson_info::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME, //
                            $start_time,$end_time,$start
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_assistant_lesson_count_old($start_time,$end_time,$adminid,$userid_list) {
        $where_arr=[
            "s.is_test_user=0",
            "l.lesson_status=2",
            "l.confirm_flag <>2",
            "l.lesson_type in (0,1,3)",
            "l.lesson_del_flag = 0",
            "m.uid =".$adminid
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        if($start_time < strtotime(date("2017-03-27"))){
            $start = strtotime(date("2017-03-05"));
            $sql=$this->gen_sql_new("select sum(l.lesson_count) as lesson_count"
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." where %s and  s.ass_assign_time <%u",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $where_arr,$start
            );
        }else{
            $userid_in_str=$this->where_get_in_str("s.userid",$userid_list,false);
            $where_arr[] = $userid_in_str;
            $sql=$this->gen_sql_new("select sum(l.lesson_count) as lesson_count"
                                ." from  %s m left join %s a on m.phone = a.phone"
                                ." left join %s l on l.assistantid = a.assistantid"
                                ." left join %s s on l.userid = s.userid"
                                ." where %s ",
                                self::DB_TABLE_NAME,
                                t_assistant_info::DB_TABLE_NAME,
                                t_lesson_info::DB_TABLE_NAME,
                                t_student_info::DB_TABLE_NAME, //
                                $where_arr
            );
        }

        // return $where_arr;
        return $this->main_get_value($sql);
    }



    public function get_all_assistant_renew_new($start_time,$end_time )
    {
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status in (1,2,3)" ,
            "(m.uid <> 68 and m.uid <> 74)",
            "m.account_role = 1 ",
            // "m.del_flag =0"
        ];
        $sql =$this->gen_sql_new("select  uid,count(distinct userid) all_student,sum(o.price) all_price,sum(o.lesson_total*o.default_lesson_count) all_total,sum(if(contract_type=1,lesson_total*default_lesson_count,0)) give_total,sum(if(contract_type=0,price,0)) tran_total".
                                 " from  %s m ".
                                 " left join %s o on o.sys_operator  = m.account".
                                 " where %s group by uid",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_all_assistant_renew_list_new($start_time,$end_time,$warning_stu_list=[],$adminid=-1 )
    {
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            ["uid=%u",$adminid,-1],
            "o.contract_status in (1,2)" ,
            "(m.uid <> 68 and m.uid <> 74)",
            "m.account_role = 1 ",
            // "m.del_flag =0",
            "o.price >0"
        ];
        $where_arr[] = $this->where_get_in_str("o.userid",$warning_stu_list,true);
        $sql =$this->gen_sql_new("select  uid,count(distinct userid) all_student,sum(o.price) all_price,sum(if(contract_type=0,price,0)) tran_price,sum(if(contract_type=0,1,0)) tran_num,sum(if(contract_type in (3,3001),price,0)) renw_price,sum(if(contract_type in (3,3001),1,0)) renw_num ".
                                 " from  %s m ".
                                 " left join %s o on o.sys_operator  = m.account".
                                 " where %s group by uid",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    //cc签单助教转介绍数据
    public function get_cc_tran_origin_order_info($start_time,$end_time){
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status in (1,2)" ,
            "(m.uid <> 68 and m.uid <> 74)",
            "m.account_role = 1 ",
            // "m.del_flag =0",
            "o.price >0",
            "mm.account_role=2"
        ];
        $sql = $this->gen_sql_new("select m.uid,count(o.userid) stu_num,"
                                  ."sum(o.price) all_price "
                                  ." from %s m left join %s s on m.uid=s.origin_assistantid "
                                  ." left join %s o on s.userid = o.userid and o.sys_operator <> m.account"
                                  ." left join %s mm on o.sys_operator = mm.account"
                                  ." where %s group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });


    }




    //助教续费金额 分期按80%计算
    public function get_ass_renw_money_new($start_time,$end_time){
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            //  ["uid=%u",$adminid,-1],
            //"o.contract_status in (1)" ,
            "(m.uid <> 68 and m.uid <> 74)",
            "m.account_role = 1 ",
            // "m.del_flag =0",
            "o.price >0",
            "o.contract_type in (3,3001)",
            "o.orderid not in (29362)"
        ];
        // $where_arr[] = $this->where_get_in_str("o.userid",$warning_stu_list,true);
        $sql =$this->gen_sql_new("select  uid,sum(if(co.child_order_type=2 and (co.channel='建行分期' or co.channel='baidu'),co.price*0.8,co.price)) money ".
                                 " from  %s m ".
                                 " left join %s o on o.sys_operator  = m.account".
                                 " left join %s co on o.orderid = co.parent_orderid and co.pay_status=1".
                                 " where %s group by uid",
                                 self::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 t_child_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }


    public function get_assistant_jk_stu_info(){
        $sql=$this->gen_sql("select uid,count(userid) jk_num"
                            ." from  %s m left join %s a on m.phone = a.phone"
                            ." left join %s s on a.assistantid = s.assistantid"
                            ." where s.is_test_user=0 and s.type=1 and m.account_role=1 and m.del_flag =0 and m.uid <>74  "
                            ." group by m.uid  ",
                            self::DB_TABLE_NAME,
                            t_assistant_info::DB_TABLE_NAME,
                            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql,function($item){
            return $item["uid"];
        });

    }

    public function get_assistant_admin_member_list($up_master_adminid,$account_id){
        $where_arr=[
            "am.account_role=1 ",
            "am.del_flag =0 "
        ];
        if($up_master_adminid !=-1){
            $where_arr[] = ["g.master_adminid=%u",$account_id,-1];
        }
        $sql = $this->gen_sql_new("select g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,am.uid adminid,".
                                  "am.account ".
                                  " from %s am left join %s u on am.uid = u.adminid".
                                  " left join %s g on u.groupid = g.groupid".
                                  " left join %s m on g.up_groupid = m.groupid".
                                  " where %s group by am.uid",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  t_admin_main_group_name::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item){
            return $item['adminid'];
        });
    }


    public function get_teacher_permission($teacherid){
        $sql = $this->gen_sql_new("select permission from %s m".
                                   " join %s t on m.phone=t.phone".
                                   " where t.teacherid = %u",
                                   self::DB_TABLE_NAME,
                                   t_teacher_info::DB_TABLE_NAME,
                                   $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_permission_list(){
        $where_arr=[
            "t.teacherid not in (51094,130374,130462,53484)"
        ];
        $sql = $this->gen_sql_new("select permission,teacherid,t.realname from %s m".
                                  " join %s t on m.phone=t.phone where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_account_role_by_teacherid($teacherid){
        $sql = $this->gen_sql_new("select account_role,uid from %s m join %s t on m.phone=t.phone where t.teacherid=%u and m.del_flag=0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $teacherid
        );
        return $this->main_get_row($sql);
    }

    public function get_account_role_by_uid($uid){
        $sql = $this->gen_sql_new("select account_role,uid from %s m where m.uid=".$uid,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_row($sql);
    }
    public function get_account_role_by_phone($phone){
        $sql = $this->gen_sql_new("select account_role from %s m where m.phone=".$phone,
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }


    public function get_research_teacher_list($account_role){
        $sql = $this->gen_sql_new("select teacherid,t.realname from %s m".
                                  " join %s t on m.phone=t.phone where account_role=%u ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $account_role
        );
        $research_teacher_list = $this->main_get_list($sql);
        $tea_arr=[];
        foreach($research_teacher_list as $item){
            $tea_arr[] =$item["teacherid"];
        }
        return $tea_arr;
    }

    public function get_research_teacher_list_subject($account_role){
        $sql = $this->gen_sql_new("select teacherid,t.realname from %s m".
                                  " join %s t on m.phone=t.phone where account_role=%u and t.subject in (1,2,3) ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $account_role
        );
        $research_teacher_list = $this->main_get_list($sql);
        $tea_arr=[];
        foreach($research_teacher_list as $item){
            $tea_arr[] =$item["teacherid"];
        }
        return $tea_arr;
    }


    public function get_research_teacher_list_new($account_role,$fulltime_teacher_type=-1,$adminid_list=[]){
        $where_arr=[
            ["m.fulltime_teacher_type=%u",$fulltime_teacher_type,-1]
        ];
        $this->where_arr_adminid_in_list($where_arr,"m.uid", $adminid_list );
        $sql = $this->gen_sql_new("select t.teacherid,t.realname,t.train_through_new_time,t.create_time from %s m".
                                  " join %s t on m.phone=t.phone where %s and account_role=%u and del_flag =0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $account_role
        );
        return $this->main_get_list($sql);
    }


    public function get_adminid_list_by_account_role($account_role){
        $where_arr=[];
        if($account_role==-2){
            $where_arr[]="account_role in (4,9)";
        }else{
            $where_arr[]=["account_role=%u",$account_role,-1];
        }
        $sql = $this->gen_sql_new("select uid,account,a.nick,m.name,n.master_adminid,n.group_name".
                                  " from %s m left join %s a on m.phone = a.phone ".
                                  " left join %s u on m.uid=u.adminid".
                                  " left join %s n on u.groupid = n.groupid".
                                  " where %s and del_flag =0 and uid <> 325 and uid<>74",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  $where_arr
        );
        return  $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_adminid_list_by_account_role_new($account_role,$month,$history_flag=0){
        $where_arr=[];
        $where_arr[]=["n.main_type=%u",$account_role,-1];
        if($history_flag==0){
            $sql = $this->gen_sql_new("select uid,account,a.nick,m.name,n.master_adminid,n.group_name".
                                      " from %s m left join %s a on m.phone = a.phone ".
                                      " left join %s u on m.uid=u.adminid".
                                      " left join %s n on u.groupid = n.groupid".
                                      " where %s ",
                                      self::DB_TABLE_NAME,
                                      t_assistant_info::DB_TABLE_NAME,
                                      t_admin_group_user::DB_TABLE_NAME,
                                      t_admin_group_name::DB_TABLE_NAME,
                                      $where_arr
            );
        }else{
            $sql = $this->gen_sql_new("select uid,account,a.nick,m.name,n.master_adminid,n.group_name".
                                      " from %s m left join %s a on m.phone = a.phone ".
                                      " left join %s u on m.uid=u.adminid and u.month= %u".
                                      " left join %s n on u.groupid = n.groupid and n.month= %u".
                                      " where %s ",
                                      self::DB_TABLE_NAME,
                                      t_assistant_info::DB_TABLE_NAME,
                                      t_group_user_month::DB_TABLE_NAME,
                                      $month,
                                      t_group_name_month::DB_TABLE_NAME,
                                      $month,
                                      $where_arr
            );

        }
        return  $this->main_get_list($sql,function($item){
            return $item["uid"];
        });
    }

    public function get_uid_str_by_adminid($adminid){
        $where_arr = [
            "account_role=1",
            "n.master_adminid=$adminid",
        ];
        $sql = $this->gen_sql_new("select  GROUP_CONCAT(uid)".
                                  " from %s m ".
                                  " left join %s u on m.uid=u.adminid".
                                  " left join %s n on u.groupid = n.groupid".
                                  " where %s and del_flag =0 and uid <> 325 and uid<>74",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  $where_arr
        );

        return  $this->main_get_value($sql);
    }


    public function get_adminid_num_by_account_role($account_role){
        $sql = $this->gen_sql_new("select count(*) from %s ".
                                  "where account_role=%u and del_flag =0 and uid <> 325",
                                  self::DB_TABLE_NAME,
                                  $account_role
        );

        return  $this->main_get_value($sql);

    }


    public  function get_ytx_account_map ( $ytx_phone   ) {
        $where_arr=[
            [ "tquin=%d", $ytx_phone, -1 ],
        ];

        $sql = $this->gen_sql_new(
            "select tquin,account from %s ".
            "where  tquin >0 and  %s ",
            self::DB_TABLE_NAME,
            $where_arr);

        return  $this->main_get_list($sql,function($item){
            return $item["tquin"];
        });
    }

    public function get_research_quanzhi_teacher_lesson_info($start_time,$end_time){
        $where_arr=[
            "m.account_role in (4,5) ",
            "m.del_flag =0 ",
            "l.lesson_type in (0,2)",
            "l.lesson_del_flag = 0",
            "l.confirm_flag <>2",
            "(tss.success_flag <>2 or tss.success_flag is null)"
        ];
        $this->where_arr_add_time_range($where_arr,"l.lesson_start",$start_time,$end_time);
        $sql = $this->gen_sql_new("select t.teacherid,realname,l.lesson_start,l.lesson_end,t.subject,t.grade_part_ex,m.account_role "
                                  ." from %s m left join %s t on m.phone=t.phone"
                                  ." left join %s l on l.teacherid=t.teacherid "
                                  ." left join %s tss on tss.lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return  $this->main_get_list($sql);
    }

    public function sync_kaoqin_user($ccid, $machine_id=-1) {

        $cur_sn= "";
        if ( $machine_id != -1 ) {
            $cur_sn= $this->task->t_kaoqin_machine->get_sn($machine_id);
        }

        $this->sync_kaoqin_del_user([$ccid],$cur_sn);

        $ret_info=$this->t_kaoqin_machine_adminid->get_list(null,$machine_id,$ccid,-1,0);
        $sn_list= $ret_info["list"] ;
        foreach( $sn_list as $item) {
            $sn=$item["sn"];
            $open_door_flag=$item["open_door_flag"];
            $auth_flag=$item["auth_flag"];
            $passwd="";
            if (!$open_door_flag) {
                //$passwd= $this->t_admin_users->get_password($ccid);
                $passwd= md5("123"); //$this->t_user_info->get_passwd($ccid);
            }
            \App\Helper\Utils::logger("SN_passwd:$passwd");

            $this->sync_kaoqin_by_sn($sn,[
                "id"=>time(NULL),
                "do"=>"update",
                "data"=>"user",
                "ccid"=>$ccid,
                "auth"=> $auth_flag?14:0,
                "deptid"=> 0,
                "passwd" => $passwd,
                "name"=> $this->get_name($ccid) ,
            ]);
        }


        $this->sync_kaoqin_fingerprint($ccid,$cur_sn);
        $this->sync_kaoqin_headpic($ccid,$cur_sn);
    }

    public function sync_kaoqin_by_sn( $sn, $data ) {
        if (\App\Helper\Utils::check_env_is_release() ) {
            $this->task->t_kaoqin_machine->send_cmd_by_sn($sn , $data );
            /*
            $key="kaoqin_$sn";
            $sync_data_list=\App\Helper\Common::redis_get_json($key);
            if (!is_array($sync_data_list)) {
                $sync_data_list=[];
            }
            $sync_data_list[]=$data;
            \App\Helper\Common::redis_set_json($key, $sync_data_list );
            */
        }
    }
    //{id:”1006”,do:”delete”,data:[”user”,”fingerprint”,”face”,”headpic”,”clockin”,”pic”],ccid:[13245,8784,54878]}

    public function sync_kaoqin( $data, $sn="" ) {
        if (\App\Helper\Utils::check_env_is_release() ) {
            $sn_list=\App\Helper\Config::get_config("kaoqin_sn_list");
            foreach ($sn_list as $check_sn ) {
                if ($sn == "" ||  $check_sn == $sn ) {
                    $this->task->t_kaoqin_machine->send_cmd_by_sn($check_sn , $data );
                }
            }
        }
    }
    //{id:”1006”,do:”delete”,data:[”user”,”fingerprint”,”face”,”headpic”,”clockin”,”pic”],ccid:[13245,8784,54878]}
    public function sync_kaoqin_del_user( $adminid_list,$sn="" ){
        $this->sync_kaoqin([
            "id"=>time(NULL),
            "do"=>"delete",
            "data"=>["user","fingerprint" , "face", "headpic","clockin", "pic" ],
            "ccid"=>$adminid_list,
        ], $sn);
    }
    //{id:”1006”,do:”upload”,data:[”user”,”fingerprint”,”face”,”headpic”,”clockin”,”pic”],ccid:[13245,8784,54878]}
    public function sync_kaoqin_re_upload_user_info() {
        $this->sync_kaoqin([
            "id"=>time(NULL),
            "do"=>"upload",
            "data"=>["user" ],
            //"ccid"=>$adminid_list,
        ]);
    }


    //{id:”1004”,do:”update”,data:”headpic”,ccid:123456,headpic:”base64”}
    public function sync_kaoqin_headpic( $adminid,$sn="" ){
        $row=$this->field_get_list($adminid,"headpic") ;
        if  ($row) {
            if ($row["headpic"] ) {
                $this->sync_kaoqin([
                    "id"=>time(NULL),
                    "do"=>"update",
                    "data"=>"headpic",
                    "ccid"=>$adminid,
                    "headpic"=> $row["headpic"],
                ],$sn);
            }
        }
        //{id:”1002”,do:”update”,data:””,ccid:123456, fingerprint:[“base64”,”base64”]}
        //data_list
    }

    public function sync_kaoqin_fingerprint( $adminid,$sn="" ){
        $row=$this->field_get_list($adminid,"fingerprint1,fingerprint2") ;
        if  ($row) {
            $this->sync_kaoqin([
                "id"=>time(NULL),
                "do"=>"update",
                "data"=>"fingerprint",
                "ccid"=>$adminid,
                "fingerprint"=>[$row["fingerprint1"], $row["fingerprint2"] ],
            ], $sn);
        }
        //{id:”1002”,do:”update”,data:””,ccid:123456, fingerprint:[“base64”,”base64”]}
        //data_list
    }


    public function get_call_duration_time($start_time,$end_time,$grade_list){
        $where_arr=[
            [  "t.start_time >= %u", $start_time, -1 ] ,
            [  "t.end_time <= %u", $end_time, -1 ] ,
        ];

        $sql = $this->gen_sql_new("select sum(t.duration) as duration, sum(is_called_phone=1) as callnum, count(*) as calltotal, m.uid  as adminid "
                                  ." from %s m join %s t on m.tquin=t.uid "
                                  ." where %s"
                                  ." group by m.uid",
                                  self::DB_TABLE_NAME,
                                  t_tq_call_info::DB_TABLE_NAME,
                                  $where_arr
        );

        return  $this->main_get_list($sql);
    }

    public function get_permission_by_adminid($adminid){
        $sql = $this->gen_sql_new("select permission from %s where uid=%d",
                                  self::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_value($sql);
    }

    public function get_operation_phone($adminid){
        $sql=$this->gen_sql("select phone from  %s where uid='%s'",
                            self::DB_TABLE_NAME,
                            $adminid
        );
        return $this->main_get_value($sql);

    }


    public function get_ass_adminid($aid){
        $sql = $this->gen_sql_new("select uid from %s m left join %s ass ".
                                  " on m.phone= ass.phone where ass.assistantid=$aid",
                                  self::DB_TABLE_NAME,
                                  t_assistant_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }


    public function get_ass_master_nick($master_adminid=-1){
        $sql = $this->gen_sql_new(" select account from %s where uid=%d",
                                  self::DB_TABLE_NAME,
                                  $master_adminid
        );
        return $this->main_get_value($sql);
    }

    public function get_adminid_by_account($account){
        $sql = $this->gen_sql_new(" select uid from %s where account = '%s'  ",
                                  self::DB_TABLE_NAME,
                                  $account,
                                  $account
        );
        return $this->main_get_value($sql);

    }
    public function get_create_time_list(   ) {
        $sql= $this->gen_sql_new(
            "select uid as adminid, create_time from %s ",
            self::DB_TABLE_NAME
        ) ;
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }


    public function get_fulltime_teacher_admin_info($adminid){
        $sql = $this->gen_sql_new("select t.realname,m.main_department,m.post,m.email,m.create_time,t.level"
                                  ." from %s m left join %s t on m.phone = t.phone"
                                  ." where m.uid = %u",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $adminid
        );
        return $this->main_get_row($sql);
    }

    public function get_fulltime_teacher_assessment_positive_info($page_info,$adminid,$become_full_member_flag,$main_flg,$fulltime_teacher_type=-1){
        $where_arr=[
            "m.account_role =5 ",
            "m.del_flag =0 ",
            ["m.uid = %u",$adminid,-1],
            ["m.become_full_member_flag = %u",$become_full_member_flag,-1],
            ["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
        ];
        if($main_flg==1){
            $where_arr[] = "(p.master_deal_flag=1 or p.main_master_deal_flag>0)";
        }
        $sql = $this->gen_sql_new("select m.create_time,m.uid,m.account,m.become_full_member_flag,m.become_full_member_time,"
                                  ." a.id,a.assess_time,p.id positive_id,p.master_deal_flag,p.main_master_deal_flag,m.name, "
                                  ." a.assess_adminid,p.mater_adminid,p.master_assess_time ,p.main_mater_adminid"
                                  ." ,p.main_master_assess_time,p.positive_type,a.add_time "
                                  ." from %s m left join %s a on (m.uid= a.adminid and a.add_time= (select max(add_time) from %s where adminid = m.uid))"
                                  ." left join %s p on a.id= p.assess_id "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_fulltime_teacher_assessment_list::DB_TABLE_NAME,
                                  t_fulltime_teacher_assessment_list::DB_TABLE_NAME,
                                  t_fulltime_teacher_positive_require_list::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }


    public function get_adminid_by_teacherid($teacherid){
        $sql = $this->gen_sql_new("select uid from %s m"
                                  ." join %s t on m.phone= t.phone"
                                  ." where t.teacherid=%u",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $teacherid
        );
        return $this->main_get_value($sql);
    }

    public function get_list_test( $page_info, $nick_phone, $account_role, $start_time, $end_time) {
        $where_arr = [
            ["create_time>=%s",$start_time,0],
            ["create_time<=%s",$end_time,0],
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"account_role",$account_role);
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "phone like '%%%s%%'  ", $this->ensql($nick_phone));
        }
        $sql =  $this->gen_sql_new( "select uid, account, account_role, name, phone, create_time "
                                    . " from %s "
                                    . "  where %s  ",
                                    self::DB_TABLE_NAME,
                                    $where_arr );
        return $this->main_get_list_by_page($sql,$page_info);

    }
    public function get_tea_sub_list_by_orderid($idstr = 0){
        $where_arr = [
            "ol.orderid in ({$idstr})",
            // "ol.orderid in (199)",
        ];
        // // $this->where_arr_add_int_or_idlist($where_arr,"account_role",$account_role);
        // if ($orderid!=""){
        //     $where_arr[]=sprintf( "orderid like '%%%s%%'  ", $this->ensql($orderid));
        // }
        $sql =  $this->gen_sql_new( "select distinct ol.orderid, t.nick, l.subject"
                                    . " from %s l"
                                    . " left join %s ol on ol.lessonid=l.lessonid"
                                    . " left join %s t on t.teacherid=l.teacherid"
                                    . "  where %s  "
                                    ,t_lesson_info::DB_TABLE_NAME
                                    ,t_order_lesson_list::DB_TABLE_NAME
                                    ,t_teacher_info::DB_TABLE_NAME
                                    ,$where_arr
        );
        // dd($sql);
        return $this->main_get_list($sql);
    }
    public function test_ff( $page_info, $nick_phone, $account_role) {
        $where_arr=array();

        if ($nick_phone!=""){
            $where_arr[]=sprintf( "account like '%%%s%%'  ", $this->ensql($nick_phone));
        }
        //print_r($where_arr);
        $this->where_arr_add_int_or_idlist($where_arr,"account_role",$account_role);
        $where_arr[]=" create_time > 1010111";
        $where_arr[]=" create_time < 101011122222";
        //print_r( $this->where_arr_add_int_or_idlist($where_arr,"account_role",$account_role) );

        $sql =  $this->gen_sql_new( "select uid ,  account ,   account_role, name ,  phone, create_time "
                                    . " from %s "
                                    . "  where %s  ",
                                    self::DB_TABLE_NAME,
                                    $where_arr );
        //print_r($sql);
        // dd($this->main_update($sql));
        // dd($this->row_delete($sql));
        // dd($this->row_delete($sql));


        //dd($this->main_get_list_by_page($sql,$page_info));
        return $this->main_get_list_by_page($sql,$page_info);


        $this->main_update($sql);//返回影响行数
        $this->row_delete($uid);//返回影响行数
        $this->row_insert([
        ]);

        $this->main_get_list($sql);
        $this->main_get_list_by_page($sql,$page_info);
        $this->main_get_row($sql);
        $this->main_get_value($sql);


        $sql =  $this->gen_sql_new(
            "select uid ,  account ,   account_role, name ,  phone "
            . " from %s "
            . "  where %s  ",
            self::DB_TABLE_NAME,
            $where_arr );

        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_admin_work_status_info($account_role){
        $where_arr=[
            ["account_role = %u",$account_role,-1],
            "del_flag=0",
        ];
        $sql = $this->gen_sql_new("select uid,admin_work_status,account,name from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }

    public function get_zs_work_status_adminid($account_role){
        $where_arr=[
            ["account_role = %u",$account_role,-1],
            "del_flag=0",
            "admin_work_status=1"
        ];
        $sql = $this->gen_sql_new("select uid from %s where %s order by uid",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);

    }
    //全职老师统计
    public function get_fulltime_teacher_count($account_role){

        $where_arr=[
            "m.account_role =5 ",
            "m.del_flag =0 "
            //[".uid = %u",$adminid,-1],
            //["m.become_full_member_flag = %u",$become_full_member_flag,-1],
            //["m.fulltime_teacher_type = %u",$fulltime_teacher_type,-1],
        ];
        $sql = $this->gen_sql_new("select count(m.uid) as fulltime_teacher_count  "
                                  ." from %s m  join %s a on m.phone= a.phone "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }
    //全职老师学生数统计（此处获取全职老师id列表)
    public function get_fulltime_teacher_student_count($account_role){
        $where_arr=[
            " m.account_role=5 ",
            "m.del_flag =0 ",
            "t.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select t.teacherid  from %s m"
                                  ." join %s t on m.phone=t.phone where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_fulltime_teacher_num($end_time,$fulltime_teacher_type=-1){
        $where_arr=[
            "del_flag=0",
            "account_role=5",
            ["fulltime_teacher_type=%u",$fulltime_teacher_type,-1],
            "create_time <=".$end_time
        ];

        $sql = $this->gen_sql_new("select count(*) from %s where %s and uid <>480",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }


    public function get_admin_list_by_role($role){
        $where_arr = [
            ["account_role=%u",$role,-1],
            "del_flag=0"
        ];
        $sql = $this->gen_sql_new("select uid "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_up_group_cancle_rate_flag($adminid){
        $where_arr = [
            ["uid=%u",$adminid,-1],
            "del_flag=0",
        ];
        $sql = $this->gen_sql_new("select uid "
                                  ." from %s "
                                  ." left join %s  "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function reset_all_email_create_flag ( $email_list ) {

        $sql=$this->gen_sql_new("update %s set  email_create_flag=0 ",  self::DB_TABLE_NAME);
        $this->main_update($sql);
        foreach ( $email_list as &$email  ) {
            $email= "'".  trim($email ) . "'";
        }
        $email_list_str=join( ",",$email_list );


        $sql=$this->gen_sql_new("update %s set email_create_flag=1 where email in (%s) ",
                                self::DB_TABLE_NAME, [$email_list_str]
        );
        $this->main_update($sql);

    }

    public function get_del_ass_list($account_role){
        $sql = $this->gen_sql_new("select uid,name from %s where account_role = %u and del_flag=1",self::DB_TABLE_NAME,$account_role);
        return $this->main_get_list($sql);
    }

    public function get_assistant_id($uid){
        $sql = $this->gen_sql_new("select  s.assistantid  from db_weiyi_admin.t_manager_info m left join t_assistant_info s on s.phone = m.phone where  m.phone > 0 and s.phone > 0 and m.uid = %s",$uid);
        return $this->main_get_value($sql);
    }

    public function get_formal_num($start_time, $end_time){
        $check_time = time() - 30*86400;
        $where_arr = [
            "m.account_role=2",
            "m.become_full_member_time <= $check_time"
        ];

        $sql = $this->gen_sql_new("  select count(*) from %s m "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_value($sql);
    }

    public function get_cr_num($start_time,$end_time){
        $where_arr = [
            'main_type = 1 ',
            ["month=%u",$start_time,-1],
            "(del_flag = 0 or(del_flag = 1 and  leave_member_time > $start_time))"
        ];
        $sql = $this->gen_sql_new(" select  count(distinct(adminid)) as total"
                                  ." from db_weiyi_admin.t_group_name_month n left join db_weiyi_admin.t_admin_group_user g on g.groupid = n.groupid left join db_weiyi_admin.t_manager_info m on g.adminid = m.uid  where %s"
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_cr_num_new_b2($start_time,$end_time){
        $where_arr = [
            'main_type = 1 ',
            ["month=%u",$start_time,-1],
            "(del_flag = 0 or(del_flag = 1 and  leave_member_time > $end_time))"
        ];
        $sql = $this->gen_sql_new(" select  count(distinct(adminid)) as total"
                                  ." from db_weiyi_admin.t_group_name_month n left join db_weiyi_admin.t_admin_group_user g on g.groupid = n.groupid left join db_weiyi_admin.t_manager_info m on g.adminid = m.uid  where %s"
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_cr_num_new($month_time,$start_time,$end_time){
        $where_arr = [
            'main_type = 1 ',
            ["month=%u",$month_time,-1],
            "(del_flag = 0 or(del_flag = 1 and  leave_member_time > $start_time))"
        ];
        $sql = $this->gen_sql_new(" select  count(distinct(adminid)) as total"
                                  ." from db_weiyi_admin.t_group_name_month n left join db_weiyi_admin.t_admin_group_user g on g.groupid = n.groupid left join db_weiyi_admin.t_manager_info m on g.adminid = m.uid  where %s"
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }
    public function get_cr_target($last_month){
        $where_arr = [
            'account_role = 1',
            'kpi_type = 1',
            'del_flag = 0',
            ["month=%u",$last_month,-1]
        ];
        $sql = $this->gen_sql_new(" select  sum(warning_student * 0.8 * 8000) as num "
                                  ." from %s m"
                                  ." left join %s a  on m.uid = a.adminid  "
                                  ."where %s",
                                  self::DB_TABLE_NAME
                                  ,t_month_ass_student_info::DB_TABLE_NAME
                                  ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_uid_stu_num(){
        $where_arr = [
            'm.account_role = 1 ',
            'm.del_flag = 0 ',
        ];
        $sql = $this->gen_sql_new(
            "select uid,count(distinct s.userid) as stu_num "
            ." from %s m"
            ." left join %s a on a.phone=m.phone"
            ." left join %s s on s.assistantid=a.assistantid and s.is_test_user=0 and s.type=0"
            ." where %s"
            ." group by uid"
            ,self::DB_TABLE_NAME
            ,t_assistant_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_today_assess_info_by_uid($ass_adminid, $start_time,$end_time){
        $where_arr = [
            "m.uid = $ass_adminid",
            'm.del_flag = 0 ',
        ];
        $sql = $this->gen_sql_new(
            "select count(distinct s.userid) as stu_num,"
            ." count(distinct r.userid) as revisit_num"
            ." from %s m"
            ." left join %s a on a.phone=m.phone"
            ." left join %s s on s.assistantid=a.assistantid and s.is_test_user=0 and s.type=0"
            ." left join %s r on r.sys_operator=m.account and r.revisit_time>=$start_time and r.revisit_time<$end_time and r.revisit_type=0"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_assistant_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,t_revisit_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);

    }

    public function get_leader_revisit_info($ass_adminid, $start_time,$end_time){
        $where_arr = [
            ["m.uid=%u",$ass_adminid,-1],
            "r.revisit_time>=$start_time",
            "r.revisit_time<$end_time",
            "r.revisit_type=7"
        ];

        $sql = $this->gen_sql_new(
            "select count(distinct r.userid)"
            ." from %s m"
            ." left join %s r on r.sys_operator=m.account"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_revisit_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_admin($wx_openid){
        $where_arr = [
            "del_flag=0",
            ["wx_openid ='%s'",$wx_openid],
        ];
        $sql = $this->gen_sql_new(
            "select phone from %s"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_admin_by_phone($phone){
        $where_arr = [
            "del_flag=0",
            ["phone='%s'",$phone],
        ];
        $sql = $this->gen_sql_new(
            "select 1 from %s"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_account_by_phone($phone) {
        $sql = $this->gen_sql_new("select account from %s where phone='%s'",
                                  self::DB_TABLE_NAME, $phone);
        return $this->main_get_value($sql);
    }


    public function get_user_list($group_arr){
        // $where_arr = ["permission in ( $group_str )"];

        foreach($group_arr as $item){
            $where_arr[] = "permission like %$item%";
        }


        foreach($group_arr as $item){
            $where_arr[] = "permission like %$item%";
        }

        $group_str= implode(' or ',$where_arr);


        $sql = $this->gen_sql_new("  select uid as adminid, account as name from %s m "
                                  ." where permission %u"
                                  ,self::DB_TABLE_NAME
                                  ,$group_str
        );

        return $this->main_get_list($sql);
    }

    public function get_all(){
        $sql = $this->gen_sql_new(" select permission, uid, account from %s m"
                                  ." where leave_member_time = 0"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_all_list() {
        $sql = $this->gen_sql_new(" select account,name,phone from %s ",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql, function($item) {
            return $item['phone'];
        });
    }

    public function get_ass_leader_opneid($assid){
        $where_arr = [
            "au.adminid=$assid"
        ];
        $sql = $this->gen_sql_new("  select wx_openid from %s m "
                                  ." left join %s an on an.master_adminid=m.uid"
                                  ." left join %s au on au.groupid=an.groupid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_admin_group_name::DB_TABLE_NAME
                                  ,t_admin_group_user::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_fulltime_teacher_lesson_count($start_time,$end_time){
        $where_arr = [
            "l.confirm_flag IN (0, 1)",
            "l.lesson_del_flag = 0 ",
            "l.lesson_type <> 2 ",
            "l.lesson_status = 2 ",
            [" l.lesson_start >= %u ",$start_time,-1],
            ["l.lesson_start < %u ",$end_time,-1],
            "m.account_role = 5 ",
            "m.del_flag = 0 ",
            "(m.fulltime_teacher_type = 1 or m.fulltime_teacher_type =2)"
        ];
        $sql = $this->gen_sql_new(" select SUM(lesson_count) AS lesson_all, "
                                  ." sum(if (m.fulltime_teacher_type = 1, lesson_count,0)) sh_lesson_all,"
                                  ." sum(if (m.fulltime_teacher_type = 2, lesson_count,0)) wh_lesson_all "
                                  ." from %s m "
                                  ." left join %s t ON m.phone = t.phone "
                                  ." left join %s l on t.teacherid = l.teacherid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_fulltime_teacher_cc_transfer($start_time,$end_time,$fulltime_teacher_type=-1){
        $where_arr = [
            ["kk.fulltime_teacher_type=%u",$fulltime_teacher_type,-1],
            "kk.account_role = 5 ",
            "kk.del_flag = 0 ",
            "l.lesson_type = 2 ",
            "l.lesson_del_flag = 0",
            "tss.success_flag IN (0, 1) ",
            "l.lesson_user_online_status = 1 ",
            "(m.account_role = 2 OR tq.origin LIKE '%%转介绍%%') ",
            "m.del_flag = 0 ",
            ["l.lesson_start >= %u",$start_time,-1],
            ["l.lesson_start < %u ",$end_time,-1]
        ];

        $sql = $this->gen_sql_new("select COUNT(DISTINCT c.userid, c.teacherid, c.subject) AS order_num,"
                                  ." COUNT(DISTINCT l.userid, l.subject) AS all_lesson "
                                  ." from %s kk "
                                  ." left join %s tt ON tt.phone = kk.phone"
                                  ." left join %s l ON l.teacherid = tt.teacherid"
                                  ." left join %s tss ON l.lessonid = tss.lessonid"
                                  ." left join %s c ON l.userid = c.userid AND l.teacherid = c.teacherid AND "
                                  ." l.subject = c.subject AND c.course_type = 0 AND c.courseid > 0 "
                                  ." left join %s tq ON tq.require_id = tss.require_id"
                                  ." left join %s ts ON ts.test_lesson_subject_id = tq.test_lesson_subject_id "
                                  ." left join %s m ON tq.cur_require_adminid = m.uid "
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_course_order::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_no_order_list($level,$adminid,$start_time,$end_time){
        $where_arr = [
            'm.account_role =2',
            'g.main_type=2',
            "( m.leave_member_time =0 or m.leave_member_time>=$end_time)",
            "m.become_member_time<$end_time",
            'm.uid <> 1239 and m.uid<>892 and m.uid<>1257 and m.uid<>491 and m.uid<> 68',
        ];
        $order_arr = [
            ['o.order_time>=%u', $start_time, -1],
            ['o.order_time<%u', $end_time, -1],
        ];
        if ($level == 1){//组长
            $where_arr[] = "g.master_adminid=$adminid";
            $sql = $this->gen_sql_new(
                "select m.name,g.group_name,max( if( o.contract_type=0 and o.contract_status >0 ,o.orderid,0)) no_order"
                ." from %s m "
                ." left join %s o on o.sys_operator=m.account and %s "
                ." left join %s gu on gu.adminid=m.uid "
                ." left join %s g on g.groupid=gu.groupid "
                ." where %s"
                ." group by m.uid"
                ." having no_order=0"
                ,self::DB_TABLE_NAME
                ,t_order_info::DB_TABLE_NAME
                ,$order_arr
                ,t_admin_group_user::DB_TABLE_NAME
                ,t_admin_group_name::DB_TABLE_NAME
                ,$where_arr
            );
        } else if($level == 2){//主管

            $where_arr[] = "mg.master_adminid=$adminid";
            $sql = $this->gen_sql_new(
                "select m.name,g.group_name,max( if( o.contract_type=0 and o.contract_status >0 ,o.orderid,0)) no_order"
                ." from %s m "
                ." left join %s o on o.sys_operator=m.account and %s "
                ." left join %s gu on gu.adminid=m.uid "
                ." left join %s g on g.groupid=gu.groupid "
                ." left join %s mg on mg.groupid=g.up_groupid"
                ." where %s"
                ." group by m.uid"
                ." having no_order=0"
                ,self::DB_TABLE_NAME
                ,t_order_info::DB_TABLE_NAME
                ,$order_arr
                ,t_admin_group_user::DB_TABLE_NAME
                ,t_admin_group_name::DB_TABLE_NAME
                ,t_admin_main_group_name::DB_TABLE_NAME
                ,$where_arr
            );

        } else if($level == 3){//总监

            $where_arr[] = "amg.master_adminid=$adminid";
            $sql = $this->gen_sql_new(
                "select m.name,g.group_name,max( if( o.contract_type=0 and o.contract_status >0 ,o.orderid,0)) no_order"
                ." from %s m "
                ." left join %s o on o.sys_operator=m.account and %s "
                ." left join %s gu on gu.adminid=m.uid "
                ." left join %s g on g.groupid=gu.groupid "
                ." left join %s mg on mg.groupid=g.up_groupid"
                ." left join %s amg on amg.groupid=mg.up_groupid"
                ." where %s"
                ." group by m.uid"
                ." having no_order=0"
                ,self::DB_TABLE_NAME
                ,t_order_info::DB_TABLE_NAME
                ,$order_arr
                ,t_admin_group_user::DB_TABLE_NAME
                ,t_admin_group_name::DB_TABLE_NAME
                ,t_admin_main_group_name::DB_TABLE_NAME
                ,t_admin_majordomo_group_name::DB_TABLE_NAME
                ,$where_arr
            );

        } else if($level == 4){

            $sql = $this->gen_sql_new(
                "select m.name,g.group_name,max( if( o.contract_type=0 and o.contract_status >0 ,o.orderid,0)) no_order"
                ." from %s m "
                ." left join %s o on o.sys_operator=m.account and %s "
                ." left join %s gu on gu.adminid=m.uid "
                ." left join %s g on g.groupid=gu.groupid "
                ." where %s"
                ." group by m.uid"
                ." having no_order=0"
                ,self::DB_TABLE_NAME
                ,t_order_info::DB_TABLE_NAME
                ,$order_arr
                ,t_admin_group_user::DB_TABLE_NAME
                ,t_admin_group_name::DB_TABLE_NAME
                ,$where_arr
            );
        }

        return $this->main_get_list($sql);
    }

    public function get_alert_time_by_uid($uid) {
        $sql = $this->gen_sql_new("select alert_time from %s where uid='%s'",
                                  self::DB_TABLE_NAME, $uid);
        return $this->main_get_value($sql);
    }

    public function get_user_list_sec($gender,$name_phone,$page_num){
        $where_arr=[
            ["del_flag=%u", 0]
        ];
        if(!empty($gender)){
            $where_arr[] = ["gender=%s", $gender, -1];
        }
        if(!empty($name_phone)){
            if(!is_numeric($name_phone)){
                $where_arr[] = sprintf( "name like '%s%%' ",$this->ensql($name_phone));
            }else{
                $where_arr[] = sprintf( "phone = %s ",$this->ensql($name_phone));
            }    
        }

        $sql = $this->gen_sql_new(" select uid as id,account,name,phone,gender,permission,account_role from %s where %s order by uid desc",
                                  self::DB_TABLE_NAME,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_account_role_by_account($account){
        $sql = $this->gen_sql_new("  select 1 from %s m "
                                  ." where account='$account' and account_role in (10,12) "
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_all_fulltime_teacherinfo(){

        $sql = $this->gen_sql_new("select uid,name from %s where account_role = 5 and (del_flag =0 or (del_flag =1 and leave_member_time > 1506787200) ) and uid<1000",
                                    self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_phone_by_name($name) {
        $sql = $this->gen_sql_new("select uid,phone from %s where name='$name'", self::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }

    public function get_phone_by_account($account) {
        $sql = $this->gen_sql_new("select uid,phone from %s where account='$account'", self::DB_TABLE_NAME);
        return $this->main_get_row($sql);
    }


    public function get_admin_leave_num($start_time,$end_time){
        $where_arr = [
            'account_role =5',
            'fulltime_teacher_type=2',
            'del_flag=1'
        ];
        $where_arr[] =  ['leave_member_time>=%u', $start_time, 0];
        $where_arr[] = ['leave_member_time<%u', $end_time, 0];
        $sql = $this->gen_sql_new("select count(*) from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }

    public function getEmailLeft(){
        $sql = $this->gen_sql_new("  select email from %s m "
                                  ." where del_flag=1"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_group_admin_list($adminid_list){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,'uid',$adminid_list);
        $sql = $this->gen_sql_new(" select uid adminid,del_flag,create_time,leave_member_time "
                                  ." from %s ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_item_list($adminid_list=[]){
        $where_arr = [];
        $this->where_arr_add_int_or_idlist($where_arr,'uid',$adminid_list);
        $sql = $this->gen_sql_new(" select uid adminid,account "
                                  ." from %s ".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_account_role_by_name($name){
        $where_arr = [
            "name = '$name'",
        ];
        $sql = $this->gen_sql_new("select account_role from db_weiyi_admin.t_manager_info where  %s",$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_all_users(){
        $sql = $this->gen_sql_new("select account_role,uid,permission,phone from %s where del_flag = 0",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_detail_info($uid){
        $where_arr = [
            ['uid=%s',$uid,-1]
        ];
        $sql = $this->gen_sql_new("select gender,account_role,account,age from db_weiyi_admin.t_manager_info where %s ",$where_arr);
        return $this->main_get_row($sql);
    }

    public function get_group_info_detail($assistantid){
        $where_arr = [
            ["assistantid=%s",$assistantid,-1]
        ];


        $sql = $this->gen_sql_new("select kk.group_name , gg.group_name as name "
                                 ." from %s a "
                                 ." left join %s m on a.phone = m.phone "
                                 ." left join %s g on m.uid = g.adminid "
                                 ." left join %s gg on g.groupid = gg.groupid "
                                 ." left join %s kk on gg.up_groupid = kk.groupid "
                                 ." where %s "
                                 ,t_assistant_info::DB_TABLE_NAME
                                 ,t_manager_info::DB_TABLE_NAME
                                 ,t_admin_group_user::DB_TABLE_NAME
                                 ,t_admin_group_name::DB_TABLE_NAME
                                 ,t_admin_main_group_name::DB_TABLE_NAME
                                 ,$where_arr);
        return $this->main_get_row($sql);
    }

    public function checkIsRole($userOpenid){
        $where_arr = [
            // "m.account_role in (1,2)",
            "m.account_role in (2,12)",
            "m.wx_openid='$userOpenid'"
        ];
        $sql = $this->gen_sql_new("  select uid from %s m where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_item_seller_list(){
        $where_arr = [
            "account_role =2",
            "del_flag=0",
        ];
        $sql = $this->gen_sql_new(
            "select uid,account "
            ." from %s "
            ." where %s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_rs_tea_info() {
        // select account,name from db_weiyi_admin.t_manager_info where account_role = 4 and del_flag=0
        //select t.teacher_type,t.teacher_money_type from db_weiyi_admin.t_manager_info m left join db_weiyi.t_teacher_info t on m.phone=t.phone where account_role = 4 and del_flag=0
        $sql = $this->gen_sql_new("select t.teacherid,t.teacher_type,t.teacher_money_type from %s m "
                                  ." left join %s t on m.phone=t.phone"
                                  ." where account_role = 4 and del_flag = 0",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_ass_info ($role) {
        $sql = $this->gen_sql_new("select account from %s where account_role = $role and del_flag = 0 ", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
}
