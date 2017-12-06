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
    //@param:$begin_time  添加时间
    public function insert_funnel_channel_sta($example_info=[],$begin_time=0){
        if(!$example_info)
            return false;
        if(!$begin_time)
            return false;
        $sta_arr = self::get_funnel_channel_statistics($example_info);
        $this->task->t_funnel_channel_statistics->row_insert([
            'channel_name' => $sta_arr['channel_name'],
            'add_time' => $begin_time,
            'total_case' => $sta_arr['total_case'],
            'heavy_case' => $sta_arr['heavy_case'],
            'distribution_num' => $sta_arr['distribution_num'],
            'tmk_effect_num' => $sta_arr['tmk_effect_num'],
            'first_phone_average' => $sta_arr['first_phone_average'],
            'phoned_num' => $sta_arr['phoned_num'],
            'no_call_num' => $sta_arr['no_call_num'],
            'consumption_rate' => $sta_arr['consumption_rate'],
            'called_num' => $sta_arr['called_num'],
            'called_effect_num' => $sta_arr['called_effect_num'],
            'called_invalid_num' => $sta_arr['called_invalid_num'],
            'called_rate' => $sta_arr['called_rate'],
            'effect_rate' => $sta_arr['effect_rate'],
            'no_get_through_num' => $sta_arr['no_get_through_num'],
            'no_get_through_invalid_num' => $sta_arr['no_get_through_invalid_num'],
            'A_intention' => $sta_arr['A_intention'],
            'B_intention' => $sta_arr['B_intention'],
            'C_intention' => $sta_arr['C_intention'],
            'appointment_num' => $sta_arr['appointment_num'],
            'have_class_num' => $sta_arr['have_class_num'],
            'have_class_succ_num' => $sta_arr['have_class_succ_num'],
            'audition_rate' => $sta_arr['audition_rate'],
            'contract_num' => $sta_arr['contract_num'],
            'contract_people_num' => $sta_arr['contract_people_num'],
            'contract_money' => $sta_arr['contract_money'],
        ], $update_on_existed = true);
    }
}