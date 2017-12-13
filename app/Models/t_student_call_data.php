<?php
namespace App\Models;
class t_student_call_data extends \App\Models\Zgen\z_t_student_call_data
{
    public function __construct()
    {
        parent::__construct();
    }
    public function check($userid){
        $sql = "select count(userid) from db_weiyi.t_student_call_data where userid = $userid";
        return $this->main_get_value($sql);
    }
}
