<?php
namespace App\Models;
class t_user_lesson_account_log extends \App\Models\Zgen\z_t_user_lesson_account_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add($lesson_account_id,  $reason, $modify_money, $left_money, $lessonid, $info_arr) {
        return $this->row_insert([
            self::C_lesson_account_id => $lesson_account_id,
            self::C_add_time => time(NULL),
            self::C_reason => $reason,
            self::C_modify_money => $modify_money,
            self::C_left_money => $left_money,
            self::C_lessonid => $lessonid,
            self::C_info => json_encode( $info_arr),
        ]);
    }



}











