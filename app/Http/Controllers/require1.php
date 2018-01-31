<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config as Config;

use Illuminate\Support\Facades\Redis;
class require1 extends Controller
{
    use CacheNick;

    public function  __construct() {
        $this->check_approval_require();
    }

    // 试听课标准化讲义使用次数
    public function get_resource_count() {

        $info = $this->t_resource->get_list_for_subject();
        foreach($info as &$item) {
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            $item["nick"] = $this->cache_get_account_nick($item["adminid"]);
        }
        return $this->pageView(__METHOD__, "", [
            "info" => $info
        ]);
    }
}