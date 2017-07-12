<?php
namespace App\Models;
use \App\Enums as E;
class t_lesson_note extends \App\Models\Zgen\z_t_lesson_note
{

    public $config_fix="question";
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info(){
        $sql = $this->gen_sql_new("select * from %s where mod(note_id,100)<>0",self::DB_TABLE_NAME);
        return $this->main_get_list_as_page($sql);
    }

    public function get_note_id(){
        $sql = $this->gen_sql_new("select substring(note_id,-2) tt from %s having (tt>4)",self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
}











