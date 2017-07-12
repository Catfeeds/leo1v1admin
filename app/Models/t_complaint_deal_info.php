<?php
namespace App\Models;
use \App\Enums as E;
class t_complaint_deal_info extends \App\Models\Zgen\z_t_complaint_deal_info
{
	public function __construct()
	{
		parent::__construct();
	}


    public function del_row_by_complaint_id($complaint_id){
        $sql=sprintf("delete from %s  where  complaint_id='%s' ",
                     self::DB_TABLE_NAME,
                     $complaint_id
        );
        return $this->main_update($sql);

    }

}











