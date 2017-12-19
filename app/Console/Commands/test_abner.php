<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test_abner extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_abner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'abner测试及导数据专用';

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
     * param:获取新老师帯课及课耗情况
     * @return mixed
     */
    public function handle()
    {
        //
        $start_time = strtotime(date('Y-10-01'));
        $end_time = strtotime('+1 month -1 second',$start_time);

        $data = $this->task->t_teacher_info->get_teacher_code($start_time,$end_time); 
        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        foreach($data as $item){
            fwrite($fp, @$item['subject_grade']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$item['guest_number']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['class_consumption']);//3
            fwrite($fp, "\n");
        }
        fclose($fp);
        echo 'ok!';
    }
}
