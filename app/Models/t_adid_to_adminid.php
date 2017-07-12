<?php
namespace App\Models;
class t_adid_to_adminid extends \App\Models\Zgen\z_t_adid_to_adminid
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_admin_from_ad($assistant_id)
    {
        $sql = sprintf("select adminid from %s where adid = %u",
                       self::DB_TABLE_NAME,
                       $assistant_id
        );
        return $this->main_get_value($sql);
    }

    public function add_ad_to_admin($assistant_id, $admin_id)
    {
        $this->addData('adid', $assistant_id);
		$this->addData('adminid', $admin_id);
		return $this->dataInsert(self::DB_TABLE_NAME);
    }


}











