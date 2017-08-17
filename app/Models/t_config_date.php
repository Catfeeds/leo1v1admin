<?php
namespace App\Models;
use \App\Enums as E;
class t_config_date extends \App\Models\Zgen\z_t_config_date
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_config_value ($config_date_type, $opt_time ,$config_date_sub_type=0  )  {
        $sql=$this->gen_sql_new(
            "select value from %s"
            . " where  config_date_type=%u and config_date_sub_type=%u and  opt_time= %u ",
            self::DB_TABLE_NAME ,
            $config_date_type, $config_date_sub_type  ,$opt_time  );
        return $this->main_get_value($sql);
    }
    public function set_config_value  ($config_date_type, $opt_time ,$value ,$config_date_sub_type=0  )  {
        return $this->row_insert([
            "config_date_type" => $config_date_type,
            "config_date_sub_type" => $config_date_sub_type,
            "opt_time" => $opt_time,
            "value" => $value,
        ],true);
    }

}











