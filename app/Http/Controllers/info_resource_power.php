<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class info_resource_power extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_resource_power(){
        
        $ret = $this->t_info_resource_power->get_list();
        foreach($ret as &$item) {
            $item['consult_power'] = E\Epower_resource::get_desc($item['consult']);
            $item['assistant_power'] = E\Epower_resource::get_desc($item['assistant']);
            $item['market_power'] = E\Epower_resource::get_desc($item['market']);
        }

        return $this->pageView(__METHOD__, $ret);
    }

}