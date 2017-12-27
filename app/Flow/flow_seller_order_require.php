<?php
namespace App\Flow;
use \App\Enums as E;
class flow_seller_order_require  extends flow_base{


    static $type= E\Eflow_type::V_SELLER_ORDER_REQUIRE;
    static $node_data=[
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ [7, 1] , "申请"  ],


        1=>[ 2,"申请->主管审批"  ],
        2=>[ [4,3,-1],"主管审批->[部]主审批" ],

        7=>[ [-1,-1],"主管审批->[部]主审批" ],
        3=>[ -1 ,"[部]主审批->市场主管审批" ],
        4=>[ 5 ,"[部]主审批->优惠券审批" ],
        5=>[ -1 ,"[优惠券审批->市场主管审批" ],
    ];
    static function get_self_info( $from_key_int,  $from_key_str ) {
        $t_order_info  = new \App\Models\t_order_info();
        return $t_order_info->field_get_list($from_key_int ,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;
        $parent_order_id=$self_info["parent_order_id"];
        $orderid=$self_info["orderid"];
        $t_order_info  = new \App\Models\t_order_info();
        $parent_order_info=$t_order_info->field_get_list($parent_order_id,"*");

        $parent_lesson_total=$parent_order_info["lesson_total"]*  $parent_order_info["default_lesson_count"] /100;

        //特殊申请
        $date_range=\App\Helper\Utils::get_month_range($self_info["order_time"] );

        $spec_diff_money_all= $task->t_order_info->  get_spec_diff_money_all( $date_range["sdate"], $date_range["edate"] ,$task->t_manager_info->get_account_role( $flow_info["post_adminid"] )    );
        $promotion_spec_diff_money= $self_info["promotion_spec_diff_money"]/100;
        $role_2_diff_money_def= $task->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY , $date_range["sdate"] );

        $left_diff_money= $role_2_diff_money_def - $spec_diff_money_all;

        //if ($contract_type==0 ) {
            return [
                ["申请人",  $post_admin_nick ] ,
                ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
                ["学生",  $user_nick ] ,
                ["课程类型",  E\Econtract_type::get_desc( $contract_type )  ] ,
                ["年级", E\Egrade::get_desc($self_info["grade"] )    ] ,
                ["-","-"],
                ["课时数", $lesson_total  ] ,
                ["原价", $self_info["discount_price"]/100  ] ,
                ["折后价", $self_info["price"]/100  ] ,
                ["打折说明", $self_info["discount_reason"]  ] ,
                ["关联课程",  "<a href=\"/user_manage_new/get_relation_order_list?orderid=$orderid&contract_type=$contract_type\" target=\"_blank\" >关联课程</a>" ] ,
                ["-","-"],
                ["(完成)续费时间", \App\Helper\Utils::unixtime2date($parent_order_info["order_time"]) ] ,
                ["(完成)续费课时数", $parent_lesson_total ] ,
                ["月度配额说明","总共:$role_2_diff_money_def,已消耗 $spec_diff_money_all,剩余 $left_diff_money , 本申请消耗: $promotion_spec_diff_money ", ] ,

                ["销售说明", $flow_info["post_msg"]  ] ,
            ];
            /*
        }else{
            return [
                ["申请人",  $post_admin_nick ] ,
                ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
                ["学生",  $user_nick ] ,
                ["年级", E\Egrade::get_desc($self_info["grade"] )    ] ,
                ["课程类型",  E\Econtract_type::get_desc( $contract_type )  ] ,
                ["(完成)续费时间", \App\Helper\Utils::unixtime2date($parent_order_info["order_time"]) ] ,
                ["(完成)续费课时数", $parent_lesson_total ] ,
                ["(请求)赠送课时数", $lesson_total  ] ,
                ["销售说明", $flow_info["post_msg"]  ] ,
            ];

        }
            */

    }

    static function get_line_data( $from_key_int,$from_key_str) {
        $self_info=static::get_self_info( $from_key_int,$from_key_str );

        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type_str= E\Econtract_type::get_desc($self_info["contract_type"]);
        $grade_str=E\Egrade::get_desc($self_info["grade"] ) ;
        $lesson_total=$self_info["lesson_total"]* $self_info["default_lesson_count"] /100;

        $discount_price=$self_info["discount_price"]/100;
        $price=$self_info["price"]/100;
        $msg="";
        if (  $self_info["order_promotion_type"] ==1 ||   $self_info["promotion_present_lesson"] !=$self_info["promotion_spec_present_lesson"]) {
            $msg="赠送 :".($self_info["promotion_spec_present_lesson"]/100)."课时,";
        }
        if ($self_info["promotion_discount_price"] !=$self_info["promotion_spec_discount"]) {
            $msg.=" 价格:".($self_info["promotion_spec_discount"]/100)."元(". (intval($self_info["promotion_spec_discount"]*10000/$self_info["discount_price"])/100)." 折)";
        }
        return   "$contract_type_str-$user_nick-$grade_str-课时数: $lesson_total -原价:$discount_price-现价:$price, $msg";

    }


    static function next_node_process_0 ($flowid ,$adminid){ //

        list($flow_info,$self_info)=static::get_info($flowid);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;

        //新签，续费 ,　不用市场确认
        if ( $contract_type==E\Econtract_type::V_0    ) {
            return [ 7,  static::get_admin_account_by_env(  "班洁" ,"jim")];
        }else   { //其他
            $t=  new \App\Models\t_admin_group_user();
            $item=$t->get_up_level_users($adminid);
            return [ 1, $item["master_adminid1"]    ];
        }
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        \App\Helper\Utils::logger( "master_adminid2:". $item["master_adminid2"] );

        return $item["master_adminid2"];
    }

    static function next_node_process_7 ($flowid, $adminid){ //
        list($flow_info,$self_info)=static::get_info($flowid);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;
        return 0;
    }


    static function next_node_process_2 ($flowid, $adminid){ //
        list($flow_info,$self_info)=static::get_info($flowid);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"]*  $self_info["default_lesson_count"] /100;

        //新签，续费 ,　不用市场确认
        if (  $contract_type==E\Econtract_type::V_3  ) {
            return [ 7,  static::get_admin_account_by_env(  "孙佳旭" ,"jim")];
        }

        if (preg_match("/Y[0-9A-Za-z][0-9][0-9][0-9][0-9]/", $self_info["discount_reason"])) {
            return [4, 281]; //amanda
        }

        if ($contract_type==E\Econtract_type::V_0 &&  $lesson_total <90 ) { //30次课

            if (($self_info["promotion_present_lesson"] !=$self_info["promotion_spec_present_lesson"]) ||
                ($self_info["promotion_discount_price"] !=$self_info["promotion_spec_discount"])
            ) {
                return [3,282];
            }

            return [-1, 0 ];
        }else{
            return [3,282];
        }
    }

    static function next_node_process_3 ($flowid, $adminid){ //
        return 0;
    }
    static function next_node_process_4 ($flowid, $adminid){ //
        return 282;
    }
    static function next_node_process_5 ($flowid, $adminid){ //
        return 0;
    }




}
