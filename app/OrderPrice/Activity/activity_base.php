<?php
namespace App\OrderPrice\Activity;

use \App\Enums as E;
/**
 * @property   \App\Console\Tasks\TaskController $task
 */
class activity_base {
    static public  $class_map=[
        E\Eorder_activity_type::V_0 =>  activity_0::class,
        E\Eorder_activity_type::V_2017080101  =>  activity_2017080101::class,
        E\Eorder_activity_type::V_2017090101  =>  activity_2017090101::class,
        E\Eorder_activity_type::V_2017100701  =>  activity_2017100701::class,
        E\Eorder_activity_type::V_2017102701  =>  activity_2017102701::class,
        E\Eorder_activity_type::V_2017102702  =>  activity_2017102702::class,
        E\Eorder_activity_type::V_2017110301  =>  activity_2017110301::class,
        E\Eorder_activity_type::V_2017110401  =>  activity_2017110401::class,
        E\Eorder_activity_type::V_2017110801  =>  activity_2017110801::class,
        E\Eorder_activity_type::V_2017110802  =>  activity_2017110802::class,
        E\Eorder_activity_type::V_2017110803  =>  activity_2017110803::class,

        E\Eorder_activity_type::V_2017110901  =>  activity_2017110901::class,
        E\Eorder_activity_type::V_2017111002  =>  activity_2017111002::class,
    ];

    /**
     * 购买课程次数
     */
    public  $lesson_times;

    public  $userid;
    public  $contract_type;

    /**
     *   试听课  lessonid
     */
    public  $from_test_lesson_id ;

    public  $args;
    public  $grade;


    public function __construct(  $args   ) {
        if (count( $args )>0) {
            $this->from_test_lesson_id= $args["from_test_lesson_id"];
            $this->lesson_times  = $args["lesson_times"];

            $this->contract_type = $args["contract_type"];
            $this->userid = $args["userid"];
            $this->grade= $args["grade"];
            $this->args = $args;
        }

    }
    static function check_use_count($max_count ) {
        $task= self::get_task_controler();
        $now_count= $task->t_order_activity_info->get_count_by_order_activity_type(static::$order_activity_type );
        $activity_desc_cur_count="当前已用($now_count/$max_count) ";
        $count_check_ok_flag= ($now_count<$max_count);
        return array( $count_check_ok_flag,$now_count, $activity_desc_cur_count);
    }

    static function check_now( $start_date, $end_date ) {
        $now=time(NULL);
        return (strtotime($start_date ) <= $now && $now < (strtotime($end_date )+86400 )   );
    }
    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task_controler() {
        return new \App\Console\Tasks\TaskController ();
    }


    static function get_value_from_config_ex($config,$check_key,$def_value=[0,100]) {

        $last_k=$def_value[0];
        $last_value=$def_value[1];
        foreach ($config as  $k =>$v ) {
            if ($k > $check_key )  {
                return array($last_k , $last_value);
            }
            $last_value= $v;
            $last_k= $k;
        }
        return array($last_k, $last_value);
    }

    static function get_value_from_config($config,$check_key,$def_value=0) {

        $last_value=$def_value;
        foreach ($config as  $k =>$v ) {
            if ($k > $check_key )  {
                return $last_value;
            }
            $last_value= $v;
        }
        return $last_value;
    }

    static public function gen_activity_item($succ_flag, $desc , $cur_price, $cur_present_lesson_count ,$can_period_flag ) {
        \App\Helper\Utils::logger("  $desc ");

        return [ "order_activity_type" => static::$order_activity_type ,
                 "succ_flag"=> $succ_flag ,
                 "activity_desc"=>$desc,
                 "cur_price" => $cur_price  ,
                 "cur_present_lesson_count" => $cur_present_lesson_count,
                 "can_period_flag" => $can_period_flag,
        ];
    }


    //需要实现
    protected function do_exec ( &$out_args, &$can_period_flag,  &$price,  &$present_lesson_count,  &$desc_list )   {


    }

    public function exec ( &$out_args, &$can_period_flag, &$price,  &$present_lesson_count,  &$desc_list  )   {
        $old_price= $price;
        $old_present_lesson_count= $present_lesson_count;
        $old_desc_list = $desc_list;
        \App\Helper\Utils::logger("do : " . E\Eorder_activity_type::get_desc(static::$order_activity_type ) );


        $this->do_exec( $out_args,$can_period_flag, $price,$present_lesson_count,$desc_list);
        if ( count($desc_list ) - count($old_desc_list )  > 1 ) {
            throw  new \Exception(" desc_list 增加　超过１，出错　" )  ;
        }

        if ($old_price != $price || $old_present_lesson_count != $present_lesson_count ) {
            //数据有变化，
            if ( count($desc_list ) - count($old_desc_list )  !=1 ) {
                throw  new \Exception("有修改数据，却没有设置desc_list　" )  ;
            }else {
                $last_item=$desc_list[count($desc_list) -1 ];
                if ( !$last_item["succ_flag"]  ) { //
                    throw  new \Exception(" desc_list item succ_flag err,有修改数据， succ_flag 却为false " )  ;
                }
                return true;
            }
        } else {
            if ( count($desc_list ) - count($old_desc_list )  ==1 ) {
                $last_item=$desc_list[count($desc_list) -1 ];
                if ( $last_item["succ_flag"]  ) { //
                    throw  new \Exception(" desc_list item succ_flag err,无修改数据， succ_flag 却为 true" )  ;
                }
                return false;
            }else {

            }

        }

    }

    public function __get( $name ) {
        if ($name == "task" ) {
            return $this->$name= new \App\Console\Tasks\TaskController();
        }else if (substr($name ,0,2  ) == "t_") {
            $reflectionObj = new \ReflectionClass( "App\\Models\\$name");
            return $this->$name= $reflectionObj->newInstanceArgs();
        }else{
            throw new \Exception() ;
        }
    }
    public  function get_desc(){
        return [];
    }


}