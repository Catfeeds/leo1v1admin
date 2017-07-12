<?php
namespace App\Models;
class t_error_info extends \App\Models\Zgen\z_t_error_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_error_info($lessonid){
        $sql=$this->gen_sql("insert into %s (lessonid,)"
                            
        );
    }
}











