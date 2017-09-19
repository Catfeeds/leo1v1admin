<?php
namespace App\Models;
use \App\Enums as E;
class t_xmpp_server_config extends \App\Models\Zgen\z_t_xmpp_server_config
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list( $page_info ) {
        $sql= $this->gen_sql_new(
            "select * from %s ",
            self::DB_TABLE_NAME);
        return $this->main_get_list_by_page($sql,$page_info);
    }

}
