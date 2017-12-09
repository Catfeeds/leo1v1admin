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
        $one_week_start=strtotime($this->option('s'));
        $one_week_end=strtotime($this->option('e'));

        // $one_week_start = $this->get_in_int_val('s');
        // $one_week_end   = $this->get_in_int_val('e');

        $c = '';
        // $stu_num = $this->t_seller_student_new->get_data($one_week_start, $one_week_end);
        // $phone_list = $this->t_seller_student_new->getPhoneList($one_week_start, $one_week_end);
        $admin_list = $task->t_seller_student_new->getAdminList($one_week_start, $one_week_end);
        foreach($admin_list as &$item){
            $item['called_succ'] = $task->t_tq_call_info->get_succ_num($item['adminid'],$one_week_start,$one_week_end);
            $item['has_called'] = $task->t_tq_call_info->get_called_num($item['adminid'],$one_week_start,$one_week_end);
            $item['total_money'] = $task->t_order_info->get_total_price_for_tq($item['adminid'],$one_week_start,$one_week_end);

            if(!$item['adminid']){$item['adminid'] = 0;}
            if(!$item['name']){$item['name'] = 0;}
            if(!$item['called_succ']){$item['called_succ'] = 0;}
            if(!$item['has_called']){$item['has_called'] = 0;}
            if(!$item['total_money']){$item['total_money'] = 0;}
            // $c.='['.$item['adminid'].',"'.$item['name'].'",'.$item['called_succ'].','.$item['has_called'].','.$item['total_money'].'],<br/>';
            $c.=$item['adminid'].'  '.$item['name'].'  '.$item['called_succ'].'   '.$item['has_called'].'   '.$item['total_money']."".PHP_EOL;
        }

        dd($c);




    }
}
