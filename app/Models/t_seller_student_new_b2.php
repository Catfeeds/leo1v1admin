<?php
namespace App\Models;
use \App\Enums as E;


class t_seller_student_new_b2 extends \App\Models\Zgen\z_t_seller_student_new
{
    public function get_need_check_free_list() {

        $now= time(NULL);
        $start_time=$now - 3*86400 ;
        $end_time= $now;

        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag in (0, 1)",
            "admin_revisiterid >0",
        ];

        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select userid , admin_assign_time,admin_revisiterid,phone,tq_called_flag "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function  get_today_can_system_assign_count( ){
        $start_time= strtotime( date("Y-m-d"));
        $end_time= $start_time + 86400-1;

        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
        ];

        $this->where_arr_add_time_range($where_arr, "add_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*)  "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
    public function admin_hold_count($admin_revisiterid)  {

        $sql = $this->gen_sql_new(
            " select   count(*) as count "
            ." from %s  "
            ."where admin_revisiterid=%u "
            ,self::DB_TABLE_NAME
            , $admin_revisiterid
        );
        return $this->main_get_value($sql);
    }

    public function get_check_free_list( $start_time, $end_time ) {
        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag in (0,1)",
        ];
        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select userid,admin_revisiterid "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_today_new_count($adminid) {
        $start_time= strtotime( date("Y-m-d"));
        $end_time= $start_time + 86400-1;
        $where_arr=[
            "admin_revisiterid" =>$adminid,
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
        ];
        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*)  "
            ." from %s  "
            ." where %s "
            ,self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_value($sql);
    }

    public function  get_need_new_assign_list( $global_tq_called_flag =0 , $limit_count = 1000 ) {
        $where_arr=[
            ["n.global_tq_called_flag=%u",  $global_tq_called_flag , -1 ],
            "n.seller_student_assign_type=1", // 系统分配k
            "n.seller_resource_type=0", // 新例子
            "n.admin_revisiterid=0", // 未分配
            '(s.origin_level <= 4 or s.origin_level = 99)', //s a b c 类例子
            'n.cc_no_called_count<=3' //未拨通3次以内
        ];

        // $where_arr[] = '(tls.seller_student_status in (1,2,101,102) and n.cc_no_called_count<=2)';
        $start_time = time(NULL) -86400*30;
        $end_time = time(NULL) ;
        $this->where_arr_add_time_range($where_arr, "add_time", $start_time, $end_time);
        $sql= $this->gen_sql_new(
            "select  n.userid, s.origin_level "
            . " from %s n"
            . " join %s s on n.userid=s.userid "
            // . ' left join %s tls on tls.userid = n.userid '
            . "  where  %s order by origin_level asc limit $limit_count ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            // t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }
    //@desn:获取cc有效例子数
    //@param:$begin_time,$end_time 开始时间 结束时间
    //@param:$admin_revisiterid cc的id
    public function get_effect_num($begin_time,$end_time,$admin_revisiterid){
        $where_arr=[
            "seller_student_assign_type" => E\Eseller_student_assign_type::V_1,
            "tq_called_flag =2",
            "admin_revisiterid" => $admin_revisiterid
        ];

        $this->where_arr_add_time_range($where_arr, "admin_assign_time", $begin_time, $end_time);
        $sql = $this->gen_sql_new(
            'select count(*) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);

    }
    //@desn:获取转介绍统计
    //@param:$start_time,$end_time 开始时间 结束时间
    //@param:$principal 负责人身份 [助教、销售]
    //@param:$groupid 所属部门  [销售区、助教区]
    //@param:$create 创建人身份 [系统、助教、销售]
    //@param:$allocation 分配人 [系统、...]
    //@param:$type 分类类型 [助教自跟、助转销、...]
    //@param:$search 姓名/电话检索
    public function get_referral_statistics($start_time,$end_time,$principal,$groupid,$create,$allocation,$type,$search,$page_info){
        $where_arr = [
            'si.origin_userid > 0',
            'si.is_test_user = 0',
            ['rmi.account_role = %u',$principal,-1],
            ['amgn.groupid = %u',$groupid,-1],
            ['imi.account_role = %u',$create,-1],
            ['ssn.admin_assignerid = %u',$allocation,-1],
        ];

        if($type == 1)
            $this->where_arr_add_int_field($where_arr, 'imi.account_role', E\Eaccount_role::V_2);
        elseif(in_array($type,[2,3]))
            $this->where_arr_add_int_field($where_arr, 'imi.account_role', E\Eaccount_role::V_1);

        $this->where_arr_add_time_range($where_arr, 'si.reg_time', $start_time, $end_time);

        if($search){
            if(is_numeric($search))
                $this->where_arr_add_str_field($where_arr, 'si.phone', $search);
            else
                $where_arr[] = " (si.nick = '$search' or psi.nick = '$search' or rmi.account = '$search'".
                             " or gmi.account = '$search' or smi.account = '$search')";
        }

        $sql = $this->gen_sql_new(
            'select si.phone,si.nick,psi.nick origin_nick,si.reg_time,si.origin_assistantid,'.
            'ssn.admin_revisiterid,ssn.admin_assignerid,rmi.account admin_revisiter_nick,'.
            'rmi.account_role admin_revisiter_role,smi.account sd_nick,imi.account create_nick,'.
            'imi.account_role create_role,gmi.account admin_assigner_nick,si.userid,'.
            'ami.account_role origin_role '.
            'from %s si '.
            'left join %s sic on sic.userid = si.userid '.
            'join %s psi on si.origin_userid = psi.userid '.
            'join %s ssn on ssn.userid = si.userid '.
            'join %s ami on si.origin_assistantid = ami.uid '.         //转介绍负责人信息
            'left join %s imi on sic.adminid = imi.uid '.              //转介绍添加人信息
            'left join %s rmi on ssn.admin_revisiterid = rmi.uid '.    //转介绍销售信息
            'left join %s smi on ssn.sub_assign_adminid_1 = rmi.uid '. //转介绍SD
            'left join %s gmi on ssn.admin_assignerid = gmi.uid '.     //转介绍分配信息
            'join %s agu on agu.adminid = rmi.uid '.
            'join %s agn on agu.groupid = agn.groupid '.
            'join %s amgn on agn.up_groupid = amgn.groupid '.
            'where %s',
            t_student_info::DB_TABLE_NAME,
            t_student_introduce_create::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_admin_group_user::DB_TABLE_NAME,
            t_admin_group_name::DB_TABLE_NAME,
            t_admin_main_group_name::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql, $page_info);

    }
    //@desn:获取所有分配人
    public function get_allocation_list(){
        $where_arr = [
            'mi.del_flag = 0',
        ];
        $sql = $this->gen_sql_new(
            'select distinct ssn.admin_assignerid uid,mi.account from %s ssn '.
            'join %s mi on ssn.admin_assignerid = mi.uid '.
            'where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

}
