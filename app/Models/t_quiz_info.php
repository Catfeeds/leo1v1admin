<?php
namespace App\Models;
class t_quiz_info extends \App\Models\Zgen\z_t_quiz_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_unchecked_quiz_by_teacherid($teacherid)
	{
		$sql = sprintf("select count(*) as num from %s where teacherid = %u and work_status = 2",
                       self::DB_TABLE_NAME,
                       $teacherid
        );
		//log::write("get_unchecked_quiz_by_teacherid :".$sql);
		return $this->main_get_row( $sql  );
	}


}











