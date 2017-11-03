<?php
namespace App\OrderPrice\Activity;
class activity_base {
    /**
     * 购买课程次数
     */
    public  $lesson_times;


    /**
     *   试听课  lessonid
     */
    public  $from_test_lesson_id ;

    public  $args;

    public function __construct(  $args   ) {
        $this->from_test_lesson_id= $args["from_test_lesson_id"];
        $this->lesson_times  = $args["lesson_times"];
        $this->args = $args;
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
        return (strtotime($start_date ) <= $now && $now < strtotime($end_date )   );
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
        return [ "order_activity_type" => static::$order_activity_type ,
                 "succ_flag"=> $succ_flag ,
                 "activity_desc"=>$desc,
                 "cur_price" => $cur_price  ,
                 "cur_present_lesson_count" => $cur_present_lesson_count,
                 "can_period_flag" => $can_period_flag,
        ];
    }


    //需要实现
    protected function do_exec ( &$can_period_flag,  &$price,  &$present_lesson_count,  &$desc_list )   {


    }

    public function exec (  &$can_period_flag, &$price,  &$present_lesson_count,  &$desc_list  )   {
        $old_price= $price;
        $old_present_lesson_count= $present_lesson_count;
        $old_desc_list = $desc_list;

        $this->do_exec($can_period_flag, $price,$present_lesson_count,$desc_list);
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

}