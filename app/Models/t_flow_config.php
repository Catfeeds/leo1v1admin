<?php
namespace App\Models;
use \App\Enums as E;
class t_flow_config extends \App\Models\Zgen\z_t_flow_config
{
    public function __construct()
    {
        parent::__construct();
    }

    public function gen_node_map()  {

    }

    // return [ $node_type, $adminid, $auto_pass ]
    public function get_next_node($flow_type,$node_type, $flow_info, $self_info , $adminid ) {

    }

}
