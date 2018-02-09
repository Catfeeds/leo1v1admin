<?php
namespace App\Models;
use \App\Enums as E;
class t_flow_config extends \App\Models\Zgen\z_t_flow_config
{
    public function __construct()
    {
        parent::__construct();
    }

    public function gen_node_map( $config) {
        //check start end
        $get_type = function($type_str ) {
            $arr=preg_split("/ /", $type_str);
            return $arr[0];
        };
        $start_id=0;
        $end_id=0;
        $node_map=[];

        foreach ( $config["nodes"] as $id=> $node) {
            $type= $get_type($node["type"]);
            if ($type=="start" ) {
                $start_id=$id;
                $id=0;
            }

            if ($type=="end" ) {
                $end_id=$id;
                $id=-1;
            }

            $node["type"]=$type;
            unset($node["alt"]);
            unset($node["left"]);
            unset($node["height"]);
            unset($node["top"]);
            unset($node["width"]);
            $node["pre_node_list"]=[];
            $node["next_node_list"]=[];
            $node_map[$id] = $node;
        }
        $get_id=function( $id, $start_id, $end_id ) {
            if ($id==$start_id) return 0;
            else  if  ($id==$end_id) return -1;
            else return $id;
        };

        foreach ( $config["lines"] as $id=> $link) {
            $from_id = $get_id( $link["from"],$start_id, $end_id);
            $to_id   = $get_id( $link["to"], $start_id, $end_id);
            $switch_value=  @$link["switch_value"] ;

            $node_map[$from_id]["next_node_list"][]=[
                "id"  => $to_id,
                "switch_value" => $switch_value,
            ];

            $node_map[$to_id]["pre_node_list"][]=[
                "id"  => $from_id,
                "switch_value" => $switch_value,
            ];


            /*
              "type" => "sl"
              "from" => "1518084573"
              "to" => "1518084596"
              "name" => ""
              "dash" => false
              "alt" => true
            */
        }

        return $node_map;
    }

    // return [ $node_type, $adminid, $auto_pass ]
    public function get_next_node( $node_map,$flow_type,$node_type, $flow_info, $self_info , $adminid ) {

        $cur_node=$node_map[$node_type];
        \App\Helper\Utils::logger("=====node_type:$node_type:". $cur_node["name"]  );
        $next_node_list=$cur_node["next_node_list"];
        foreach ($next_node_list as $node_info ) {
            $id=$node_info["id"];
            $check_node=$node_map[$id];
            $type= $check_node["type"];
            if ($type =="admin") { //设置给某人
                return [  $id, $check_node["adminid"], false];
            }else if ($type =="uplevel_admin") {//上级
                $uplevel_adminid= $this->get_uplevel_adminid($adminid);
                return [  $id, $uplevel_adminid  , false];
            }else if ($type =="end") {
                return  [  $id, 0, false ];
            }else if ($type =="function" ){

                $flow_function= $check_node["flow_function"];
                //得到切换的分支值
                $switch_value=\App\Flow\flow_base::do_function($flow_function, $flow_type, $id, $flow_info, $self_info, $adminid);
                \App\Helper\Utils::logger("do function == switch_value : $switch_value ");
                foreach ($check_node["next_node_list"] as $func_item  ) {
                    if ($func_item["switch_value"]== $switch_value ){
                        return $this->get_next_node($node_map, $flow_type, $func_item["id"] , $flow_info, $self_info , $adminid  );
                        break;
                    }
                }
            }
            dd("遍历路线出错: 当前节点". $cur_node["name"] );
            return null;
        }

    }

    public function get_uplevel_adminid ( $adminid ) {
        //TODO
        return 99;
    }
}
