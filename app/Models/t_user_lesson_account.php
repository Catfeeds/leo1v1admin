<?php
namespace App\Models;
use \App\Enums as E;
/**

 * @property t_course_order  $t_course_order
 * @property t_user_lesson_account_log  $t_user_lesson_account_log

 */

class t_user_lesson_account extends \App\Models\Zgen\z_t_user_lesson_account
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list_by_userid($userid) {
        $sql=$this->gen_sql("select * from %s where userid=%u order by add_time desc",
                            self::DB_TABLE_NAME, $userid );
        return $this->main_get_list($sql);
    }

    public function add($adminid, $userid,  $course_name, $total_money,$lesson_1v1_price )
    {

        $this->start_transaction();
        $ret= $this->t_course_order->add_courser_order($userid,$course_name,1,1);
        if ($ret!=1) {
            $this->rollback();
            return false;
        }
        $courseid= $this->t_course_order->get_last_insertid();

        $ret=$this->row_insert([
            self::C_userid           => $userid,
            self::C_lesson_1v1_price => $lesson_1v1_price,
            self::C_course_name      => $course_name,
            self::C_add_time         => time(NULL),
            self::C_left_money       => 0,
            self::C_total_money      => 0,
            self::C_courseid         => $courseid,
        ]);

        if ($ret!=1) {
            $this->rollback();
            return false;
        }

        $lesson_account_id= $this->get_last_insertid();
        $insert_ret=$this->t_user_lesson_account_log->add(
            $lesson_account_id,
            E\Euser_lesson_account_reason::V_ADD_MONEY_FOR_INIT,
            0,0,0,["admin" => $adminid, "lesson_1v1_price" => $lesson_1v1_price ]);

        if ($insert_ret!=1) { //==0
            $this->rollback();
            return false;
        }

        if ( $total_money >0) {
            $ret=$this->modify_money($lesson_account_id,
                                     E\Euser_lesson_account_reason::V_ADD_MONEY_FOR_USER_ADD,
                                     $total_money,0 , ["admin"=>$adminid]);
        }

        if (!$ret) {
            $this->rollback();
            return false;
        }else{
            $this->commit();
            return $lesson_account_id;
        }
    }


    public function modify_money( $lesson_account_id,$reason, $modify_value  ,$lessonid,$info_arr )  {
        $row= $this->field_get_list($lesson_account_id,"*");
        if (!$row) {
            return false;
        }
        $left_money=$row["left_money"];
        $left_money+= $modify_value ;
        if ($left_money<0){
            return false;
        }
        $set_arr=[ ["left_money", $left_money    ]];
        if ( $reason == E\Euser_lesson_account_reason::V_ADD_MONEY_FOR_USER_ADD) {
            $set_arr[]=["total_money", $modify_value, "+" ];
        }
        $ret=$this->field_update_list($lesson_account_id,$set_arr);
        if ($ret!=1) {
            return false;
        }

        $ret= $this->t_user_lesson_account_log->add($lesson_account_id,$reason,$modify_value,$left_money,$lessonid,$info_arr);
        if ($ret!=1) {
            return false;
        }
        return true;

    }



}











