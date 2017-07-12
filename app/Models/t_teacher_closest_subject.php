<?php
namespace App\Models;
class t_teacher_closest_subject extends \App\Models\Zgen\z_t_teacher_closest_subject
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_subject_info($teacherid)
    {
        $sql = $this->gen_sql("select subject from %s where teacherid = %u ",
                              self::DB_TABLE_NAME,
                              $teacherid
        );

        return $this->main_get_list($sql);
    }


}











