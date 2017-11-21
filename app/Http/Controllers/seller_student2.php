<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;

class seller_student2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function show_order_activity_info() {
       
        $order_activity_type = $this->get_in_e_order_activity_type();
        $class_map = \App\OrderPrice\Activity\activity_base::$class_map;
        $list = [];
        if(isset($class_map[$order_activity_type])){
            $class_name=  $class_map[$order_activity_type];
            $class=new $class_name([]);
            $list=$class->get_desc();
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list));
    }

    public function add_order_activity(){
        
    }

    public function modify_order_activity(){
        
    }

    public function dele_order_activity(){
        
    }
}
