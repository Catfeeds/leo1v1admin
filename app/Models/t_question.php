<?php
namespace App\Models;
use \App\Enums as E;
class t_question extends \App\Models\Zgen\z_t_question
{   
    public $config_fix="question";
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s limit 100",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_question_tongji($noteid){
        $sql = $this->gen_sql_new("select noteid,subject,grade,sum(if(question_type=1,1,0)) select_count,sum(if(question_type=3,1,0)) wd_count,sum(if(question_type=2,1,0)) tk_count,sum(if(question_type=4,1,0)) zsd_count,note_name "
                                  ." from %s q "
                                  ." join %s n on q.noteid =n.note_id "
                                  ." where noteid = %u ",
                                  self::DB_TABLE_NAME,
                                  t_lesson_note::DB_TABLE_NAME,
                                  $noteid
        );
        return $this->main_get_row($sql);
    }
}











