<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class assistant_performance extends Controller
{
    use CacheNick;
    use TeaPower;
    public function ass_revisit_info_month() {
        $assistantid = $this->t_assistant_info->get_assistantid( $this->get_account());
        return $this->pageView(__METHOD__,$ret_info);
    }

}
