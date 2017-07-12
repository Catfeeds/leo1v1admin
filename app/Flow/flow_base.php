<?php
namespace App\Flow;

function next_node_process_end( $adminid,$flow_id ) {
    /**  @var  $t_flow_node \App\Models\t_flow_node */
    $t_flow_node= new \App\Models\t_flow_node();
}

class flow_base{

    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task_controler() {
        return new \App\Console\Tasks\TaskController ();
    }

    static function get_node_name( $node_type ) {
        return  static::$node_data[$node_type][1];
    }

    static function get_info( $flowid ) {
        $flow_info = static::get_flow_info($flowid);
        $self_info = static::get_self_info($flow_info["from_key_int"], $flow_info["from_key_str"], $flow_info["from_key2_int"] );
        return array($flow_info, $self_info);
    }

    static function get_flow_info($flowid) {
        $t_flow = new \App\Models\t_flow();
        return $t_flow->field_get_list($flowid,"*");
    }

    /*
    static function get_self_info( $from_key_int,  $from_key_str ) {
        return null;
    }
    */

    // return [ $node_type, $adminid ]
    static function get_next_node_info_by_nodeid($nodeid) {
        /**  @var  $t_flow_node \App\Models\t_flow_node */
        $t_flow_node= new \App\Models\t_flow_node();
        $ret_item=$t_flow_node->field_get_list($nodeid,"flowid,node_type,adminid" );
        $node_type=$ret_item["node_type"] ;
        $flowid=$ret_item["flowid"] ;
        $adminid=$ret_item["adminid"] ;

        return static::get_next_node_info( $node_type, $flowid, $adminid  );
    }

    // return [ $node_type, $adminid ]
    static function get_next_node_info($node_type, $flowid, $adminid ) {
        $node_def_item=static::$node_data[$node_type];
        $next_node_process_fun="next_node_process_$node_type";
        $ret=static::$next_node_process_fun( $flowid,  $adminid );
        if(!is_array($ret) ) { //next_adminid
            $ret=[ $node_def_item[0], $ret ];
        }
        return $ret;
    }




}
