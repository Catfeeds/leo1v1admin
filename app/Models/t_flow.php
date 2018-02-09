<?php
namespace App\Models;
use \App\Enums as E;

/**
 * @property  t_flow_node $t_flow_node

 */
class t_flow extends \App\Models\Zgen\z_t_flow
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_flow_int( $flow_type, $from_key_int, $from_key2_int ) {
        $sql = $this->gen_sql_new(
            "select 1 from %s"
            ." where  flow_type=%u and from_key_int=%u and from_key2_int =%u "
            ,self::DB_TABLE_NAME, $flow_type, $from_key_int, $from_key2_int
        );
        return $this->main_get_value($sql );
    }


    public function add_flow_new( $flow_type, $adminid, $msg, $from_key_int, $from_key_str=NULL ,  $from_key2_int=0 )  {
        if ($this->check_flow_int(  $flow_type, $from_key_int, $from_key2_int  ) ) {
            \App\Helper\Utils::logger("find !!");
            return false;
        }
        $this->row_insert([
            'flow_type'        => $flow_type,
            'post_adminid'     => $adminid,
            'post_time'        => time(NULL),
            'from_key_int'     => $from_key_int,
            'from_key_str'     => $from_key_str,
            'from_key2_int'    => $from_key2_int,
            'post_msg'         => $msg,
            'flow_status'      => E\Eflow_status::V_START ,
            'flow_status_time' => time(NULL),
        ],false,false,true);
        $flowid= $this->get_last_insertid();
        //init t_flow_node
        /**  @var  $flow_class    \App\Flow\flow_qingjia  */
        $flow_class= \App\Flow\flow::get_flow_class($flow_type );
        $init_node_type=0;
        list($next_node_type, $next_adminid)=$flow_class::get_next_node_info($init_node_type, $flowid, $adminid   );
        \App\Helper\Utils::logger(" next_node_type :". json_encode($next_node_type));

        if (!$next_adminid) {
            return false;
        }
        $this->t_flow_node->add_node($next_node_type,$flowid,$next_adminid);
        return true;
    }

    public function add_flow( $flow_type, $adminid, $msg, $from_key_int, $from_key_str=NULL ,  $from_key2_int=0 )  {
        if ($this->check_flow_int(  $flow_type, $from_key_int, $from_key2_int  ) ) {
            \App\Helper\Utils::logger("find !!");
            return false;
        }
        $this->row_insert([
            'flow_type'        => $flow_type,
            'post_adminid'     => $adminid,
            'post_time'        => time(NULL),
            'from_key_int'     => $from_key_int,
            'from_key_str'     => $from_key_str,
            'from_key2_int'    => $from_key2_int,
            'post_msg'         => $msg,
            'flow_status'      => E\Eflow_status::V_START ,
            'flow_status_time' => time(NULL),
        ],false,false,true);
        $flowid= $this->get_last_insertid();
        //init t_flow_node
        /**  @var  $flow_class    \App\Flow\flow_qingjia  */
        $flow_class= \App\Flow\flow::get_flow_class($flow_type );
        $init_node_type=0;
        list($next_node_type, $next_adminid)=$flow_class::get_next_node_info($init_node_type, $flowid, $adminid   );
        \App\Helper\Utils::logger(" next_node_type :". json_encode($next_node_type));

        if (!$next_adminid) {
            return false;
        }
        $this->t_flow_node->add_node($next_node_type,$flowid,$next_adminid);
        return true;
    }

    public function flow_del( $flowid,$check_only_init_del_flag=true ) {
        if($check_only_init_del_flag  ) {
            //检查node 个数 >2 这有人审核过了,不能删除
            $node_count=$this->t_flow_node-> get_count_by_flowid( $flowid);
            if ($node_count>2) {
                return false;
            }
        }
        $this->t_flow_node-> del_by_flowid($flowid);
        $this->row_delete($flowid);
        return true;
    }

    public function get_flowid_from_key_int(  $flow_type, $from_key_int,$post_adminid= -1 ){
        $where_arr=[
            ["post_adminid=%u", $post_adminid , -1]
        ];
        $sql= $this->gen_sql_new( "select flowid from %s where  flow_type=%u and from_key_int=%u and %s ",
                                  self::DB_TABLE_NAME,
                                  $flow_type,
                                  $from_key_int,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function flow_del_by_from_key_int($post_adminid, $flow_type, $from_key_int) {
        //check adminid
        $flowid=$this->get_flowid_from_key_int($flow_type,$from_key_int,$post_adminid);
        if ($flowid) {
            return $this->flow_del($flowid);
        }else{
            return false;
        }
    }

    public function get_sth_by_int($from_key_int){
        $sql = $this->gen_sql_new("select * from %s order by flowid desc ",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    public function get_info_by_key($flow_type ,$from_key_int, $from_key2_int=0)  {
        $sql= $this->gen_sql_new(
            "select * from %s "
            . " where flow_type=%u and from_key_int=%u and from_key2_int=%u ",
            self::DB_TABLE_NAME,
            $flow_type,
            $from_key_int,
            $from_key2_int
        );
        return $this->main_get_row($sql);
    }
    public function set_flow_status( $flowid, $flow_status ) {
        return   $this->field_update_list($flowid,[
            "flow_status"=> $flow_status,
            "flow_status_time"=> time(NULL),
        ]);
    }
}
