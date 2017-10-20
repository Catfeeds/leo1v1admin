<?php
namespace App\Models;
use \App\Enums as E;
class t_interview_remind extends \App\Models\Zgen\z_t_interview_remind
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_interview_remind_list(){
        $sql = $this->gen_sql_new("  select hr_adminid, interviewer_id, name, post, interview_time, dept, is_send_flag, send_msg_time from %s i "
                                  ." where %s"
                                  // ,self::D
        );
    }

}











