<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

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


        $a = '
134257,
134643,
154876,
162060,
202106,
250704,
384755,
416212,
418358,
422178,
435790,
436887,
436957,
437062,
438561,
441394,
446161,
446962,
452258,
452893,
463512,
465682,
134386,
297258,
356558,
373339,
373830,
393082,
393361,
396052,
404809,
407229,
417582,
437520,
351178,
346831,
342420,
284692,
344022,
384766,
346727,
330632,
356384,
390030,
296642,
324764,
357225,
319397,
268962,
309589,
264490,
246391,
135007,
233004,
226570,
249806,
286027,
270380,
253876,
225335,
230886,
231068,
222319,
197444,
197054,
222817,
223871,
209358,
208600,
278442,
280027,
281536,
282881,
283620,
288535,
288706,
296502,
302306,
299008,
288945,
309382,
294611,
302570,
285900,
309478,
309139,
301406,
292725,
332075,
333590,
333036,
322520,
286480,
328398,
344343,
340996,
388347,
355039,
';




        $ret_info = $task->t_teacher_info->get_teacher_bank_info_tmp($isbank, $a);

        foreach($ret_info as $key => &$item) {
            // $ret_info['list'][$key]['bind_bankcard_time_str'] = '';
            $time = '';
            if (@$item['bind_bankcard_time']) {
                $time = date('Y-m-d H:i:s', @$item['bind_bankcard_time']);
            }
            E\Esubject::set_item_value_str($item);
            $item["phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',@$item['phone']);
            $item["bank_phone"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',@$item['bank_phone']);

            // t.teacherid,t.nick,t.subject,t.phone,t.bank_account,t.bankcard,t.bank_type,t.bank_province,t.bank_city,t.bank_address,t.bank_phone,t.idcard,t.bind_bankcard_time
            echo @$item['teacherid'].','.@$item['nick'].','.@$item['subject'].','.$item['phone'].','.@$item['bank_account'].','.@$item['bankcard'].','.@$item['bank_type'].','.@$item['bank_province'].','.@$item['bank_city'].','.@$item['bank_address'].','.@$item['bank_phone'].','.@$item['idcard'].','.$time.PHP_EOL;
        }






        // foreach($admin_list as &$item){
        //     echo date("Y-m-d H:i",$item['order_time']).','.date("Y-m-d H:i",$item['check_money_time']).','.$item['sys_operator'].','.date("Y-m-d H:i",$item['create_time']).','.$item['price_money'].PHP_EOL;
        // }
    }
}
