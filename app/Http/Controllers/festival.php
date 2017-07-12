<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class festival extends Controller
{
    public function festival_list()
    {
        $page_num = $this->get_in_page_num();
        $ret_info=$this->t_festival_info->get_festival_list($page_num);
        \App\Helper\Utils::debug_to_html( $ret_info['list'] );

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function update_festival_info(){
        $arr=array(
            "5月12日"=>"护士节",
            "5月14日"=>"提前批填报志愿",
            "5月15日"=>"提前批填报志愿",
            "5月16日"=>"统一志愿",
            "5月22日"=>"中职校填志愿",
            "5月31日"=>"统一志愿",
            "6月1日"=>"儿童节",
            "6月5日"=>"环境日",
            "6月7日"=>"高考",
            "6月8日"=>"高考",
            "6月9日"=>"端午节",
            "6月18日"=>"中考",
            "6月19日"=>"父亲节",
            "6月23日"=>"奥林匹克日",
            "7月1日"=>"建党节",
            "7月7日"=>"七七事变纪念日",
            "8月1日"=>"建军节",
            "8月6日"=>"理优教育2周年",
            "8月9日"=>"七夕节",
            "8月17日"=>"中元节"
        );
        foreach($arr as $k => $v){
            $this->t_festival_info->add_festival_info($k,$v);
        }
    }
}