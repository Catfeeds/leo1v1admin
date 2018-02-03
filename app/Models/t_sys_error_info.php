<?php
namespace App\Models;
use \App\Enums as E;
class t_sys_error_info extends \App\Models\Zgen\z_t_sys_error_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add( $report_error_from_type, $report_error_type, $error_msg ) {
        $this->row_insert([
            "add_time"               => time(NULL),
            "report_error_from_type" => $report_error_from_type ,
            "report_error_type"      => $report_error_type ,
            "error_msg"              => $error_msg,
        ]);
        return $this->get_last_insertid();
    }

}











