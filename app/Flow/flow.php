<?php
namespace App\Flow;
use \App\Enums as E;
class flow{
    static $data=[
        E\Eflow_type::V_QINGJIA                               => flow_qingjia::class,
        E\Eflow_type::V_SELLER_ORDER_REQUIRE                  => flow_seller_order_require::class,
        E\Eflow_type::V_ASS_ORDER_REFUND                      => flow_ass_order_refund::class,
        E\Eflow_type::V_SELLER_POST_TEST_LESSON_WITHOUT_PAPER => flow_seller_test_lesson_without_test_paper::class,
        E\Eflow_type::V_ASS_LESSON_CONFIRM_FLAG_4             => flow_ass_lesson_confirm_flag_4::class,
        E\Eflow_type::V_SELLER_RECHECK_LESSON_SUCESS          => flow_recheck_lesson_sucess::class,
        E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE                => flow_agent_money_ex_examine::class,
        E\Eflow_type::V_CONFIRM_TEACHER_QUIT                  => flow_confirm_teacher_quit::class,
        E\Eflow_type::V_ORDER_EXCHANGE                        => flow_order_exchange::class,
    ];

    static function get_flow_class($flow_type ) {
        $flow_class= @static::$data[$flow_type];
        if ($flow_class){
            $flow_class::set_node_map();
        }
        return $flow_class;
    }


    static function get_flow_class_node_map($flow_type ) {
        $flow_class= @static::$data[$flow_type];
        if ($flow_class) {
            $flow_class::set_node_map();
        }
        return $flow_class::$node_map;
    }


}