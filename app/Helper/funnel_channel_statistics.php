<?php
namespace App\Helper;
/*
 *@desn:统计漏斗型渠道数据
 *@date:2017-12-05
 *@author:Abner<zhanghuiyuan@leo.edu.com>
 */
class funnel_channel_sta extends  \App\Models\NewModel{
    //@desn:获取渠道用户的统计数据
    //@param:$example_info  例子信息[Arr]
    static function get_funnel_channel_statistics($example_info=[]){
        if(!$example_info)
            return false;
    }
    //@desn:将漏斗型渠道统计数据存档
    //@param:$example_info  例子信息[Arr]
    public function insert_funnel_channel_sta($example_info=[]){
        if(!$example_info)
            return false;
        $sta_arr = self::get_funnel_channel_statistics($example_info);
        $this->task->t_funnel_ch
    }
}