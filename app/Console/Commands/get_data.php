<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_data {--s=} {--e=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $task=new \App\Console\Tasks\TaskController();
        $month_start = strtotime($this->option('s'));
        $month_end   = strtotime($this->option('e'));

        $isbank = 1;
        $page_info = 1;
        $ret_info = $task->t_teacher_info->get_teacher_bank_info_tmp($isbank, 5000);

        foreach($ret_info['list'] as $key => &$item) {
            $ret_info['list'][$key]['bind_bankcard_time_str'] = '';
            $time = '';
            if ($item['bind_bankcard_time']) {
                $time = date('Y-m-d H:i:s', $item['bind_bankcard_time']);
            }
            E\Esubject::set_item_value_str($item);
            $item["phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);
            $item["bank_phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['bank_phone']);

            // t.teacherid,t.nick,t.subject,t.phone,t.bank_account,t.bankcard,t.bank_type,t.bank_province,t.bank_city,t.bank_address,t.bank_phone,t.idcard,t.bind_bankcard_time
            echo $item['teacherid'].','.$item['nick'].','.$item['subject'].','.$item['phone'].','.$item['bank_account'].','.$item['bankcard'].','.$item['bank_type'].','.$item['bank_province'].','.$item['bank_city'].','.$item['bank_address'].','.$item['bank_phone'].','.$item['idcard'].','.$time.PHP_EOL;
        }






        // foreach($admin_list as &$item){
        //     echo date("Y-m-d H:i",$item['order_time']).','.date("Y-m-d H:i",$item['check_money_time']).','.$item['sys_operator'].','.date("Y-m-d H:i",$item['create_time']).','.$item['price_money'].PHP_EOL;
        // }
    }
}
