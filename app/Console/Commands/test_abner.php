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
        $start_time = strtotime(date('Y-09-01'));
        $end_time = strtotime('+1 month -1 second',$start_time);
        $flag_map = [];
        $teacher_map = [];
        $teacher_case = [
            '小学语文'=>[
                'name' => '小学语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中语文'=>[
                'name' => '初中语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中语文'=>[
                'name' => '高中语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '小学数学'=>[
                'name' => '小学数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中数学'=>[
                'name' => '初中数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中数学'=>[
                'name' => '高中数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '小学英语'=>[
                'name' => '小学英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中英语'=>[
                'name' => '初中英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中英语'=>[
                'name' => '高中英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中化学'=>[
                'name' => '初中化学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中化学'=>[
                'name' => '高中化学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中物理'=>[
                'name' => '初中物理',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中物理'=>[
                'name' => '高中物理',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中科学'=>[
                'name' => '初中科学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '其他综合'=>[
                'name' => '其他综合',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ]
        ];

        $data = $this->task->t_teacher_info->get_teacher_code($start_time,$end_time); 
        foreach($data as $key => $item){

            if(!@$flag_map[$key]){
                if($item['subject'] == 1 && $item['grade'] <= 106 && !empty($item['courseid'])){
                    $teacher_case['小学语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])){
                    $teacher_case['高中语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] <= 106 && !empty($item['courseid'])){
                    $teacher_case['小学数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])){
                    $teacher_case['高中数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] <= 106 && !empty($item['courseid'])){
                    $teacher_case['小学英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])){
                    $teacher_case['高中英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中化学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])){
                    $teacher_case['高中化学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中物理']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])){
                    $teacher_case['高中物理']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])){
                    $teacher_case['初中科学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif(!empty($item['courseid'])){
                    $teacher_case['其他综合']['has_class'] ++;
                    $flag_map[$key]=true;
                }

                

            }




            if(!@$teacher_map[$key]){
                if($item['subject'] == 1 && $item['grade'] <= 106){
                    $teacher_case['小学语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] <= 106){
                    $teacher_case['小学数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] <= 106){
                    $teacher_case['小学英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中化学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中化学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中物理']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中物理']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中科学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }else{
                    $teacher_case['其他综合']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }
            }




            if($item['subject'] == 1 && $item['grade'] <= 106){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学语文']['test_count'] ++;
                else
                    $teacher_case['小学语文']['regular_count'] ++;
            }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中语文']['test_count'] ++;
                else
                    $teacher_case['初中语文']['regular_count'] ++;

            }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中语文']['test_count'] ++;
                else
                    $teacher_case['高中语文']['regular_count'] ++;

            }elseif($item['subject'] == 2 && $item['grade'] <= 106){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学数学']['test_count'] ++;
                else
                    $teacher_case['小学数学']['regular_count'] ++;

            }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中数学']['test_count'] ++;
                else
                    $teacher_case['初中数学']['regular_count'] ++;

            }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中数学']['test_count'] ++;
                else
                    $teacher_case['高中数学']['regular_count'] ++;

            }elseif($item['subject'] == 3 && $item['grade'] <= 106  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学英语']['test_count'] ++;
                else
                    $teacher_case['小学英语']['regular_count'] ++;

            }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中英语']['test_count'] ++;
                else
                    $teacher_case['初中英语']['regular_count'] ++;

            }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中英语']['test_count'] ++;
                else
                    $teacher_case['高中英语']['regular_count'] ++;

            }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中化学']['test_count'] ++;
                else
                    $teacher_case['初中化学']['regular_count'] ++;

            }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中化学']['test_count'] ++;
                else
                    $teacher_case['高中化学']['regular_count'] ++;

            }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中物理']['test_count'] ++;
                else
                    $teacher_case['初中物理']['regular_count'] ++;

            }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中物理']['test_count'] ++;
                else
                    $teacher_case['高中物理']['regular_count'] ++;

            }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中科学']['test_count'] ++;
                else
                    $teacher_case['初中科学']['regular_count'] ++;

            }else{
                if($item['lesson_type'] == 2)
                    $teacher_case['其他综合']['test_count'] ++;
                else
                    $teacher_case['其他综合']['regular_count'] ++;

            }



        }

        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        foreach($teacher_case as $item){
            fwrite($fp, @$item['name']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$item['teacher_count']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['test_count']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$item['regular_count']);//3
            fwrite($fp, "\n");
        }

        fclose($fp);
        echo 'ok!';
    }
}
