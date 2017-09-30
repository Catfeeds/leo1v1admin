<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Libs;
use \App\Helper\Config;
use Illuminate\Support\Facades\Redis ;

class warning_overtime extends Controller
{

    use CacheNick;
    public function __construct(){

    }
    //每分钟刷新，获取7*24*3600（7天前）的对应分钟内的超时信息
    public function add_overtime() {
        $now = strtotime( date("Y-m-d H:i:00", time()) );
        $end_time = $now - 7*24*3600;
        $start_time   = $end_time - 60;
        $ret_list   = $this->t_revisit_info->get_overtime_by_now($start_time, $end_time);
        dd($ret_list);

        foreach ($ret_list as $item) {
            if(is_array($item)) {
                $this->t_revisit_warning_overtime_info->row_insert([
                    'userid'       => $item['userid'],
                    'revisit_time' => $item['revisit_time'],
                    'sys_operator' => $item['sys_operator'],
                    'create_time'  => time(),
                    'deal_type'    => 0
                ]);
            }
        }
    }
}
