<?php
namespace App\Flow;
use \App\Enums as E;
class flow_ass_order_refund  extends flow_base{


    static $type= E\Eflow_type::V_ASS_ORDER_REFUND ;

    static $node_data=[

        4=>[ -1,"xixi审核" ], //原来的
        //nodeid => next_nodeid(s) name  ,next_node_process
        0=>[ 1 , "退费申请"  ],
        1=>[ 2,"主管审批"  ],

        2=>[ [9,8],"[部]主审批" ],

        9=>[ 8 ,"经理 " ],

        8=>[ 3 ,"linlin 财务复核" ],

        3=>[ [7,5] ,"echo 财务复核" ],
        5=>[ 6 ," 助教主管 复核 " ],
        7=>[ 6 ," 销售主管 复核 " ],


        6 =>[8, " xixi复核 "  ],
        10 =>[-1, " 抄送 "  ],

    ];
    static function get_self_info( $from_key_int,  $from_key_str, $from_key2_int   ) {
        $t_order_refund  = new \App\Models\t_order_refund();
        return $t_order_refund->field_get_list_2($from_key_int ,$from_key2_int,"*");
    }

    static function get_table_data( $flowid ) {
        list($flow_info,$self_info)=static::get_info($flowid);

        $task=static::get_task_controler();
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type=$self_info["contract_type"];
        $lesson_total=$self_info["lesson_total"] /100;
        $file_url=$self_info["file_url"];
        if ($file_url){
            $file_url=\App\Helper\Utils::gen_download_url( $file_url);
            $down_load_str="<a href=\"$file_url\">下载</a>";
        }else{
            $down_load_str="无";
        }
        $orderid= $self_info["orderid"];
        $t_order_info= new \App\Models\t_order_info();
        $order_item=$t_order_info->field_get_list($orderid,"price, lesson_total, default_lesson_count");

        /*
        `should_refund` int(11) NOT NULL DEFAULT '0' COMMENT '应退课时',
        `real_refund` int(11) NOT NULL DEFAULT '0' COMMENT '实际退费金额',
        */

        return [
            ["申请人",  $post_admin_nick ] ,
            ["申请时间", date("Y-m-d H",$flow_info["post_time"] ) ] ,
            ["学生",  $user_nick ] ,
            ["课程类型",  E\Econtract_type::get_desc(  $contract_type )  ] ,
            ["原合同金额", $order_item["price"]/100 ] ,
            ["原合同 课时数", ($order_item["lesson_total"]*$order_item["default_lesson_count"]) /100 ] ,
            ["退费课时数", $self_info["should_refund"]/100 ] ,
            ["退费金额", $self_info["real_refund"]/100  ] ,
            ["是否有发票", E\Eboolean::get_desc($self_info["has_receipt"]) ],
            ["退费理由", $self_info["refund_info"] ] ,
            ["挽单结果", $self_info["save_info"] ] ,
            ["退费说明附件", $down_load_str  ] ,
        ];

    }

    static function get_line_data( $from_key_int,$from_key_str, $from_key2_int=0 ) {
        $self_info=static::get_self_info( $from_key_int,$from_key_str ,  $from_key2_int);

        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type_str= E\Econtract_type::get_desc($self_info["contract_type"]);
        $lesson_total=$self_info["should_refund"] /100;
        $price=$self_info["real_refund"]/100;
        return   "$user_nick-退费课时数: $lesson_total - 金额 :$price ";

    }


    static function next_node_process_0 ($flowid ,$adminid){ //

        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        if(!$item["master_adminid1"]){
            $item["master_adminid1"]=1004;
        }

        return $item["master_adminid1"];

        //return $t_manager_info->get_up_adminid($adminid);
    }

    static function next_node_process_1 ($flowid ,$adminid){ //
        $t=  new \App\Models\t_admin_group_user();
        $item=$t->get_up_level_users($adminid);
        \App\Helper\Utils::logger( "master_adminid2:". $item["master_adminid2"] );
        if(!$item["master_adminid2"]){
            $item["master_adminid2"]=1004;
        }

        return $item["master_adminid2"];
    }

    static function next_node_process_8 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return $flag? "echo":"jim" ;
    }


    static function next_node_process_10 ($flowid, $adminid){ //
        return 0;
    }




    static function next_node_process_2 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        if (static::check_post_admin_account_type( $adminid, E\Erole::V_ASSISTENT) ) {
            return [9, $flag? "孙佳旭":"jim"] ;
        }else {
            return [8, $flag? "zero":"jim"] ;
        }

    }

    static function next_node_process_9 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return $flag? "zero":"jim" ;

    }


    static function next_node_process_3 ($flowid, $adminid){ //
        list($flow_info,$self_info)=static::get_info($flowid);
        $t=  new \App\Models\t_manager_info();
        $account_role= $t->get_account_role( $flow_info["post_adminid"] );
        \App\Helper\Utils::logger(" GET_ACCOUNT_ROLE   $account_role");
        if ($account_role== E\Eaccount_role::V_1) {
            //助教
            return [5,"louis" ];
        }else{
            //$flag=\App\Helper\Utils::check_env_is_release() ;
            return [7,"班洁" ];
            //return [6, $flag?"xixi":"jim" , 1 ]; //自动通过
        }
    }


    static function next_node_process_5 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return [6, $flag?"xixi":"jim" , 0 ]; //不自动通过
    }


    static function next_node_process_7 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return [6, $flag?"xixi":"jim" , 0 ]; //不自动通过
    }

    static function next_node_process_6 ($flowid, $adminid){ //
        $flag=\App\Helper\Utils::check_env_is_release() ;
        return [10, $flag?"班洁":"jim" , 1 ]; //自动通过
    }


    static function do_succ_end( $flow_info, $self_info ) {
        $task=static::get_task_controler();
        //$post_admin_nick=$self_info["sys_operator"];
        $user_nick= $task->cache_get_student_nick($self_info["userid"]);
        $contract_type_str= E\Econtract_type::get_desc($self_info["contract_type"]);
        $lesson_total=$self_info["should_refund"] /100;
        $price=$self_info["real_refund"]/100;
        $post_admin_nick=$task->cache_get_account_nick($flow_info["post_adminid"]);
        if (\App\Helper\Utils::check_env_is_release() )  {
            //return [7,"班洁" ];
            //$task->t_manager_info ->send_wx_todo_msg("班洁","退费完成","申请人[$post_admin_nick], $user_nick-退费课时数: $lesson_total - 金额 :$price ");
        }else{
            //$task->t_manager_info->send_wx_todo_msg("jim","退费完成","申请人[$post_admin_nick], $user_nick-退费课时数: $lesson_total - 金额 :$price ");
        }
    }


}
