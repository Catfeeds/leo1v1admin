<?php
namespace App\Models;
use \App\Enums as E;
class t_agent extends \App\Models\Zgen\z_t_agent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_agent_info($page_info, $userid,$parentid,$phone,$wx_openid)
    {
        $where_arr = array();
        //$this->where_arr_add_int_or_idlist($where_arr,$field_name,$value);
        // $this->where_arr_add_int_or_idlist($where_arr,"userid",$userid);
        $this->where_arr_add_str_field($where_arr,"phone",$phone);
        $this->where_arr_add_str_field($where_arr,"wx_openid",$wx_openid);
        // $this->where_arr_add_int_or_idlist($where_arr,"parentid",$parentid);
        // if($rate_score == 1){
        //     $where_arr[] = "(rate_score >= 10 and rate_score < 20)";
        // }elseif($rate_score == 2){
        //     $where_arr[] = "(rate_score >= 20 and rate_score < 30)";
        // }elseif($rate_score == 3){
        //     $where_arr[] = "(rate_score >= 30 and rate_score < 40)";
        // }elseif($rate_score == 4){
        //     $where_arr[] = "(rate_score >= 40 and rate_score < 50)";
        // }elseif($rate_score == 5){
        //     $where_arr[] = "rate_score > 50 ";
        // }

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_list_by_page( $sql,$page_info);
    }

    public function get_agent_info_by_phone($phone)
    {
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"phone",$phone);

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_info_by_openid($wx_openid){
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"wx_openid",$wx_openid);

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_info_by_id($id){
        $where_arr = array();
        $this->where_arr_add_str_field($where_arr,"id",$id);

        $sql=$this->gen_sql_new ("select * "
                                 ." from %s where %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function xx(){
        $sql="update xx set f=10";
        /*
        $this->main_update($sql);
        $this->main_get_value("select f from Ts where uid=12");
        $this->main_get_row("select f,s from Ts where uid=12");
        $this->main_get_list("select f,s from Ts where uid=12");
        $this->main_get_list_by_page("select f,s from Ts where uid=12", 0,10);
        */
    }

    public function agent_row_del($wx_openid){
        $ret = $this->row_delete($wx_openid);
        return $ret;
    }

    public function get_agent_list_by_phone($phone){
        $where_arr = [
            'a2.phone = '.$phone.' or a1.phone ='.$phone,
        ];
        $sql=$this->gen_sql_new("select a1.phone phone,a2.phone p_phone,a1.id"
                                ." from %s a1 "
                                ." left join %s a2 on a2.id=a1.parentid "
                                ."where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function add_agent_row($parentid,$phone,$userid){
        $ret = $this->row_insert([
            "parentid" => $parentid,
            "phone"    => $phone,
            "userid"    => $userid,
            "create_time" => time(null),
        ],true);
        return $ret;
    }

    public function add_agent_row_new($phone,$headimgurl,$nickname,$wx_openid){
        $ret = $this->row_insert([
            "parentid"    => 0,
            "phone"       => $phone,
            "wx_openid"   => $wx_openid,
            "headimgurl"  => $headimgurl,
            "nickname"    => $nickname,
            "create_time" => time(null),
        ],true);
        if($ret){
            $ret = $this->get_last_insertid();
        }
        return $ret;
    }

    public function get_agent_count_by_id($id){
        $where_arr = [
            ['a.parentid = %d',$id],
            ['l.lesson_type = %d',2],
            ['l.lesson_del_flag = %d',0],
            'l.confirm_flag in (0,1) '
        ];
        $sql=$this->gen_sql_new("select count(a.id) "
                                ." from %s a "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s l on l.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_level1_order_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,o.price/20 price "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_agent_level1_p_price_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select sum(o.price/20) sum "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_agent_level2_p_price_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        // $sql=$this->gen_sql_new("select sum(if(o.price/10>1000,1000,o.price/10)) price "
        $sql=$this->gen_sql_new("select o.* "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );
        // dd($sql);
        return $this->main_get_row($sql);
    }

    public function get_agent_level2_p_order_by_phone($phone){
        $where_arr = [
            ['a2.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];
        $sql=$this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,if(o.price/10>1000,1000,o.price/10) price "
                                ." from %s a "
                                ." left join %s a2 on a2.id = a.parentid "
                                ." left join %s s on s.phone = a.phone "
                                ." left join %s o on o.userid = s.userid "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_agent_level2_pp_order_by_phone($phone){
        $where_arr = [
            ['a3.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];

        $sql = $this->gen_sql_new("select a.id,a.phone,s.userid,o.orderid,if(o.price/20>500,500,o.price/20) price  "
                                  ." from %s a "
                                  ." left join %s a2 on a2.id = a.parentid "
                                  ." left join %s a3 on a3.id = a2.parentid "
                                  ." left join %s s on s.phone = a.phone "
                                  ." left join %s o on o.userid = s.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_level2_pp_price_by_phone($phone){
        $where_arr = [
            ['a3.phone = %s',$phone],
            ['o.order_status = %d',1],
            ['o.is_new_stu = %d',1],
            ['o.contract_type = %d',0],
            ['o.contract_status = %d',1],
        ];

        $sql = $this->gen_sql_new("select sum(if(o.price/20>500,500,o.price/20)) price "
                                  ." from %s a "
                                  ." left join %s a2 on a2.id = a.parentid "
                                  ." left join %s a3 on a3.id = a2.parentid "
                                  ." left join %s s on s.phone = a.phone "
                                  ." left join %s o on o.userid = s.userid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_agent_test_lesson_count_by_id($id){
        $where_arr=[
            ['a.parentid = %s ',$id],
            ['l.lesson_type = %d ',2],
            ['l.lesson_del_flag = %d ',0],
            ['l.lesson_status = %d ',2],
            'l.confirm_flag in (0,1) ',
        ];

        $sql= $this->gen_sql_new(
            " select a.id,a.phone,s.userid,l.lessonid "
            . " from %s a "
            . " left join %s s on s.phone = a.phone "
            . " left join %s l on s.userid = l.userid "
            . " where %s ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_userid(){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_pid_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_ppid_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a.phone,s.userid "
            ." from %s a "
            ." left join %s s on s.phone = a.phone ",
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_count_by_phone($phone){
        $where_arr=[
            ['a1.phone = %s ',$phone],
        ];

        $sql= $this->gen_sql_new(
            "select count(a.id) count "
            . " from %s a "
            . " left join %s a1 on a1.id = a.parentid "
            . " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_row($sql);
    }

    public function get_id_by_phone($phone_str){
        $where_arr = [
            'phone in ('.$phone_str.')',
        ];
        $sql= $this->gen_sql_new(
            "select id,phone "
            . " from %s "
            . " where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_id_row_by_phone($phone){
        $sql= $this->gen_sql_new(
            "select id,phone "
            . " from %s "
            . " where phone=%s ",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_row($sql);
    }

    public function get_agent_info_row_by_phone($phone){
        $sql= $this->gen_sql_new(
            "select * "
            . " from %s "
            . " where phone = %s ",
            self::DB_TABLE_NAME,
            $phone
        );
        return $this->main_get_row($sql);
    }

    public function get_p_list_by_phone($phone){
        $sql = $this->gen_sql_new(
            " select a1.phone pp_phone,a2.phone p_phone,a2.create_time,a2.id p_id,a3.phone phone,a3.id id "
            ." from %s a1 "
            ." left join %s a2 on a2.parentid = a1.id "
            ." left join %s a3 on a3.parentid = a2.id "
            ." where a1.phone = %s "
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$phone
        );
        return $this->main_get_list($sql);
    }

    public function get_agent_order_by_phone($p_id){
        $where_arr = [];
        if($p_id){
            $this->where_arr_add_int_or_idlist($where_arr,'a.id',$p_id);
        }
        $sql = $this->gen_sql_new(
            " select a.id p_id,a.phone,o.userid,s.nick,a.create_time p_create_time,if(o.order_status,o.order_status,0) order_status "
            ." from %s a "
            ." left join %s ao on ao.aid=a.id "
            ." left join %s o on o.orderid=ao.orderid "
            ." left join %s s on s.userid=o.userid "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,t_agent_order::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function update_agent_order(){
        $where_arr = [];
        $sql = $this->gen_sql_new(
            " select a.phone ".
            " from %s ".
            " left join %s "
        );
    }

    public function get_p_pp_id_by_phone($phone){
        $where_arr = [
            ['a.phone = %s',$phone],
        ];
        $sql = $this->gen_sql_new(
            " select a.id,a.phone,a.parentid pid,a1.phone p_phone,a1.parentid ppid,a2.phone pp_phone".
            " from %s a ".
            " left join %s a1 on a1.id=a.parentid".
            " left join %s a2 on a2.id=a1.parentid".
            " where %s ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
}
