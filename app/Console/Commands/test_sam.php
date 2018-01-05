<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_sam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_sam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test_sam';

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

        echo "姓名|电话";
        echo PHP_EOL;
        echo "1123213123|aklfjalksdfj";


        exit;
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $ret_info1 = $task->t_student_score_info->get_total_student_b1();
        $ret_info2 = $task->t_student_score_info->get_total_student_b2();
        $ret_info3 = $task->t_student_score_info->get_total_student_b3();
        $ret_info4 = $task->t_student_score_info->get_total_student_b4();
        $ret_info5 = $task->t_student_score_info->get_total_student_b5();
        $ret_info6 = $task->t_student_score_info->get_total_student_b6();
        dd($ret_info1,$ret_info2,$ret_info3,$ret_info4,$ret_info5,$ret_info6);

        /*
        $ret_info = $task->t_student_score_info->get_all_student_info();
        $file_name = 'sam123';
        $arr_title = ['ID',"昵称","号码"];
        $arr_data  = ['userid','nick','phone'];
        $ret_file_name = \App\Helper\Utils::download_txt($file_name,$ret_info,$arr_title,$arr_data);
        */
        dd($ret_file_name);
    }
}
