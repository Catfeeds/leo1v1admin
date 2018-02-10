<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_student_system_assign_log extends \App\Models\Zgen\z_t_seller_student_system_assign_log
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_check_call_info_list ($start_time, $end_time){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);

        $sql=$this->gen_sql_new(
            "select g.id, n.userid,n.phone, g.adminid "
            . " from %s g "
            ." join %s n on g.userid = n.userid"
            . " where %s  ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function check_userid_adminid_existed( $userid, $adminid) {
        $sql=$this->gen_sql_new(
            "select count(*) from %s"
            . " where userid=%u and adminid=%u ",
            self::DB_TABLE_NAME,
            $userid, $adminid
        );
        return $this->main_get_value($sql)>=1;
    }

    public function add( $seller_student_assign_from_type,$userid,  $adminid,$check_hold_flag){
       $this->row_insert([
           "userid" => $userid  ,
           "logtime" => time(NULL),
           "adminid" => $adminid ,
           "seller_student_assign_from_type" => $seller_student_assign_from_type,
           'check_hold_flag' => $check_hold_flag
       ]);
    }

    public function get_admin_assign_count_info( $start_time, $end_time ){
        $where_arr=[];
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $sql=$this->gen_sql_new(
            "select adminid,"
            . " sum(seller_student_assign_from_type=0 and check_hold_flag = 0) as new_count , "
            . " sum(seller_student_assign_from_type=1 and check_hold_flag = 0) as no_connected_count "
            . "from %s  "
            . "where %s group by adminid  "
            ,
            self::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_list($sql, function($item){
            return $item["adminid"];
        });

    }
    public function get_seller_student_assign_from_type_list( $adminid, $userid_list )  {
        $where_arr=[
            "adminid" => $adminid
        ];
        $this->where_arr_add_int_or_idlist($where_arr, "userid", $userid_list );
        $sql= $this->gen_sql_new("select userid, seller_student_assign_from_type  from %s "
                                 . "where %s ",
                                 self::DB_TABLE_NAME, $where_arr
        );

        return $this->main_get_list($sql);
    }
    //@desn:释放例子时更新系统释放状态为非系统释放
    //@param:$userid 用户id
    //@param:$admin_revisiterid 用户id
    //@param:$check_hold_flag 是否系统自动释放表示0否1是
    public function update_check_flag($userid,$admin_revisiterid,$check_hold_flag=0){
        $where = "adminid = $admin_revisiterid and userid = $userid";
        $sql=sprintf("update %s set check_hold_flag = %u where %s  ",
                     self::DB_TABLE_NAME,
                     $check_hold_flag,
                     $where);
        return $this->main_update($sql);
    }

    public function  get_list($page_info, $order_by_str,  $start_time, $end_time, $adminid,$userid, $called_flag ,$seller_student_assign_from_type, $check_hold_flag,$same_admin_flag )
    {
        $where_arr=[
            ["g.userid=%d", $userid, -1 ],
            ["g.adminid=%d", $adminid, -1 ],
        ];

        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $this->where_arr_add_int_or_idlist($where_arr,"check_hold_flag" , $check_hold_flag);
        $this->where_arr_add_int_or_idlist($where_arr,"g.seller_student_assign_from_type" , $seller_student_assign_from_type);
        $this->where_arr_add_int_or_idlist($where_arr,"g.called_flag" , $called_flag);
        if ($same_admin_flag ==0) {
            $where_arr[]="g.adminid <> n.admin_revisiterid ";
        }else if ( $same_admin_flag ==1){
            $where_arr[]="g.adminid = n.admin_revisiterid ";
        }

        $sql=$this->gen_sql_new(
            "select g.*, n.phone,n.userid, s.origin_level,  s.origin, n.add_time, n.admin_revisiterid  "
            ." from  %s g "
            ." join  %s n on n.userid=g.userid "
            ." join  %s s on s.userid=g.userid "
            ." where  %s "
            . " $order_by_str ",
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            $where_arr);

        return $this->main_get_list_by_page($sql,$page_info);
    }
    //@desn:获取销售今日已分配系统分配例子个数
    //@desn:$adminid 销售id
    //@desn:$start_time, $end_time  开始时间 结束时间
    public function get_cc_assign_count($adminid,$start_time, $end_time ){
        $where_arr=[
            'adminid' => $adminid,
            'seller_student_assign_from_type' => 0,
            'check_hold_flag' => 0
        ];
        $this->where_arr_add_time_range($where_arr, "logtime", $start_time, $end_time);
        $sql=$this->gen_sql_new(
            'select count(*) from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_value($sql);
    }


}
