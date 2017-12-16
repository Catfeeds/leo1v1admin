<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class modify_origin_key extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:modify_origin_key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修改标签';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $channel_arr = [
            '券妈妈','华院分析','电信app','问卷网','gaotiewifi',
            'hf','jingqi','moushi','xueneng','yinpiao','yishu','zhongji'
        ];
        $self_arr = [
            '理优1对1辅导','理优教育在线学习','理优升学帮','理优学习力研究中心',
            '官网','公开课','微课','优学帮'
        ];
        $flow_arr = [
            '百度','百度移动','百度PC','搜狗','搜狗移动','360移动','360pc','360搜索',
            '今日头条','今日头条1','今日头条2','朋友圈','十人','盛世','网易有道',
            '乐教乐学','有道词典','金山词霸'
        ];
        $origin_key_list = $this->task->t_origin_key->get_key1_list();
        foreach($origin_key_list as &$item){
            $key1=$item["key1"];
            echo "$key1 ok \n";
            if($key1 == '金数据')
                $this->task->t_origin_key->update_key0($key1, '公众号');
            elseif(in_array($key1, $channel_arr))
                $this->task->t_origin_key->update_key0($key1, '渠道');
            elseif(in_array($key1, $self_arr))
                $this->task->t_origin_key->update_key0($key1, '自有渠道');
            elseif(in_array($key1, $flow_arr))
                $this->task->t_origin_key->update_key0($key1, '信息流');
            else
                $this->task->t_origin_key->update_key0($key1, '自有渠道');

        }
    }
}
