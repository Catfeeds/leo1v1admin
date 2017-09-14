<?php
namespace App\Models;
use \App\Enums as E;
class t_tongji_log extends \App\Models\Zgen\z_t_tongji_log
{
	public function __construct()
	{
		parent::__construct();
	}
    public function add($tongji_log_type, $logtime, $value ) {
        $this->row_insert_ignore([
            "tongji_log_type" => $tongji_log_type,
            "logtime" => $logtime,
            "value" => $value,
        ]);
    }

}











