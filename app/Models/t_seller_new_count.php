<?php
namespace App\Models;
use \App\Enums as E;

/**
 * @property t_seller_new_count_get_detail  $t_seller_new_count_get_detail

 */
class t_seller_new_count extends \App\Models\Zgen\z_t_seller_new_count
{
    public function __construct()
    {
        parent::__construct();
    }
    public  function add( $start_time, $end_time, $seller_new_count_type, $count, $adminid, $value_ex ) {
        $this->row_insert([
            "start_time"  => $start_time,
            "end_time"  => $end_time,
            "seller_new_count_type"  => $seller_new_count_type,
            "adminid"  => $adminid,
            "count"  => $count,
            "value_ex"  => $value_ex,
            "add_time" => time(NULL),
        ],true);
    }
    public function check_adminid_seller_new_count_type_start_time($adminid, $seller_new_count_type, $start_time )
    {
        $sql=$this->gen_sql_new(
            "select count(1) from %s "
            . " where  adminid=%u and seller_new_count_type=%u and  start_time=%u  ",
            self::DB_TABLE_NAME, $adminid ,  $seller_new_count_type, $start_time );

        return $this->main_get_value($sql);
    }
    public function check_adminid_seller_new_count_type_value_ex($adminid, $seller_new_count_type, $value_ex )
    {
        $sql=$this->gen_sql_new(
            "select count(1) from %s "
            . " where  adminid=%u and seller_new_count_type=%u and  value_ex=%u  ",
            self::DB_TABLE_NAME, $adminid ,  $seller_new_count_type, $value_ex );

        return $this->main_get_value($sql);
    }


    public function get_now_count_info($adminid) {
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<=$time",
            ["adminid=%d",$adminid , -1],
        ];
        $sql=$this->gen_sql_new(
            "select   sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );
        $ret_1=$this->main_get_row($sql);

        $sql=$this->gen_sql_new(
            "select   sum(count)  as count "
            ."from %s n "
            ." where %s "
            ,self::DB_TABLE_NAME ,
            $where_arr  );
        $ret_2=$this->main_get_row($sql);
        return array_merge($ret_1,$ret_2);
    }
    public function get_list_for_check_work($adminid, $seller_new_count_type,$start_time, $end_time )  {

        $where_arr=[
            ["adminid=%d",$adminid , -1],
            ["seller_new_count_type=%d",$seller_new_count_type ,-1],
        ];
        $this->where_arr_add_time_range($where_arr,"start_time" ,$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select n.adminid,  add_time, start_time, end_time, seller_new_count_type ,value_ex, count , sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s "
            ." group by  n.new_count_id  order by start_time "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );

        return $this->main_get_list($sql,function($item) {
            return $item["start_time"];
        });

    }

    public function get_free_new_count_id($adminid) {
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
        ];
        $sql=$this->gen_sql_new(
            "select n.new_count_id ,count-if(sum(get_time>0),sum(get_time>0),0 ) as left_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s "
            ."group by n.new_count_id  having left_count>0  "
            ."order by end_time asc limit 1 "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );
        return $this->main_get_value($sql);

    }

    public function check_and_add_new_count($adminid,$get_desc,$userid=0)  {
        $new_count_id=$this->get_free_new_count_id($adminid);
        if (!$new_count_id) {
            return false;
        }

        $detail_id = $this->t_seller_new_count_get_detail->get_row_by_userid($new_count_id,$userid);
        if($detail_id == 0){
            $this->t_seller_new_count_get_detail->add($new_count_id,$get_desc,$userid);
        }
        return true;
    }

    public function get_list($page_num,$adminid , $seller_new_count_type, $time=0 )   {

        if (!$time) {
            $time=time(NULL);
        }
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
            ["seller_new_count_type=%d",$seller_new_count_type ,-1],
        ];
        $sql=$this->gen_sql_new(
            "select n.adminid, n.new_count_id, add_time, start_time, end_time, seller_new_count_type ,"
            ."value_ex, count , sum(get_time>0) as get_count,nd.detail_id "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s "
            ." group by  n.new_count_id   "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );

        return $this->main_get_list_by_page($sql,$page_num,10,true, "order by add_time ");
    }

    public function tongji_get_admin_list_get_count($adminid,$start_time, $end_time){
        $time=time(NULL);
        $where_arr=[
            ["adminid=%d",$adminid , -1],
        ];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );

        return $this->main_get_list_as_page($sql);
    }



    public function tongji_get_admin_list_get_count_new($adminid,$start_time, $end_time){
        $time=time(NULL);
        $where_arr=[
            ["adminid=%d",$adminid , -1],
        ];
        $this->where_arr_add_time_range($where_arr,"get_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );

        return $this->main_get_list_as_page($sql);
    }

    public function get_admin_list_get_count($adminid){
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
        ];
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );
        return $this->main_get_list_as_page($sql);
    }

    public function get_admin_list_get_count_new_new($month,$adminid){
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
            "(m.leave_member_time>$month or m.leave_member_time =0)"
        ];
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id "
            ."left join %s m on m.uid = n.adminid "
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr  );
        return $this->main_get_list_as_page($sql);
    }

    public function get_admin_list_get_count_new($adminid_list  )   {
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
        ];
        if ($adminid_list) {
            $this->where_arr_add_int_or_idlist($where_arr,"n.adminid",$adminid_list);
        }

        $sql=$this->gen_sql_new(
            "select n.adminid, sum(get_time>0) as get_count "
            ."from %s n "
            ."left join %s nd on nd.new_count_id=n.new_count_id"
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_seller_new_count_get_detail::DB_TABLE_NAME,
            $where_arr  );

        return $this->main_get_list_as_page($sql);
    }

    public function get_admin_list_count($adminid  )   {

        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
        ];
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(count) as count "
            ."from %s n "
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }
    public function get_admin_list_count_new_new($month,$adminid  )   {

        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
            ["adminid=%d",$adminid , -1],
            "(m.leave_member_time>$month or m.leave_member_time =0)"
        ];
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(count) as count "
            ."from %s n "
            ." left join %s m on n.adminid=m.uid "
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function tongji_get_admin_list_count($adminid ,$start_time, $end_time )   {
        $where_arr=[
            ["adminid=%d",$adminid , -1],
        ];
        $this->where_arr_add_time_range($where_arr,"start_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select n.adminid, sum(count) as count "
            ."from %s n "
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            $where_arr  );

        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_admin_list_count_new($adminid_list  )   {
        $time=time(NULL);
        $where_arr=[
            "end_time>$time",
            "start_time<$time",
        ];
        if ($adminid_list) {
            $this->where_arr_add_int_or_idlist($where_arr,"n.adminid",$adminid_list);
        }

        $sql=$this->gen_sql_new(
            "select n.adminid, sum(count) as count "
            ."from %s n "
            ." where %s  "
            ."group  by adminid "
            ,self::DB_TABLE_NAME ,
            $where_arr  );

        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

    public function get_item_day_row($adminid,$start_time,$end_time){
        $where_arr=[
            'seller_new_count_type=1',
            "start_time=$start_time",
            "end_time=$end_time",
            'count=5',
            'value_ex=0',
        ];
        $this->where_arr_add_int_or_idlist($where_arr,"adminid",$adminid);

        $sql=$this->gen_sql_new(
            "select new_count_id "
            ."from %s n "
            ." where %s  "
            ,self::DB_TABLE_NAME ,
            $where_arr
        );

        return $this->main_get_value($sql);
    }
}
