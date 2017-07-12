<?php
namespace App\Models;
class t_paper_info extends \App\Models\Zgen\z_t_paper_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list_for_test() {
        $sql=$this->gen_sql( "select * from %s where paper_type=2", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }



}
