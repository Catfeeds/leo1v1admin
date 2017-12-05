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
    protected $description = '助教助长周报信息';

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
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $ret_info = $task->t_tq_call_info->get_all_info_group_by_phone();
        /*
        $is_full_time = 2;
        //$teacher_money_type = $task->get_in_int_val('teacher_money_type',-1);
        $teacher_money_type=-1;

        //$page_num = $task->get_in_page_num();
        //$task->switch_tongji_database();
        // $is_full_time = 1;  // 显示兼职老师
        // $task->switch_tongji_database();
        //$assistantid= $task->get_in_int_val("assistantid",-1);
        $assistantid = -1;

        //list($start_time,$end_time) = $task->get_in_date_range(0,0,0,[],3);
        $start_time = 1509465600;
        $end_time   = 1512057600;
        //权限写死,Erick要求
        //$adminid = $task->get_account_id();
        $adminid = 72;
        if(in_array($adminid,[72,967])){
            $show_all_flag=1;
        }else{
            $teacher_money_type=6;
            $show_all_flag=0;
        }
        $show_all_flag=1;
        
        $ret_info = $task->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time,$teacher_money_type,$show_all_flag );
        $stu_num_all = $task->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy_stu_num($start_time,$end_time,$is_full_time,$teacher_money_type);
        dd($start_time,$end_time);
        foreach($ret_info as &$item_list){
            $item_list['teacher_nick'] = $task->cache_get_teacher_nick($item_list['teacherid']);

            if($item_list['train_through_new_time'] !=0){
                $item_list["work_time"] = ceil((time()-$item_list["train_through_new_time"])/86400);
            }else{
                $item_list["work_time"] = 0;
            }

            if($item_list['valid_count']>0){
                $item_list['lesson_leavel_rate'] = number_format(($item_list['teacher_leave_lesson']/$item_list['valid_count'])*100,2);
                $item_list['lesson_come_late_rate'] = number_format(($item_list['teacher_come_late_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_cut_class_rate'] = number_format(($item_list['teacher_cut_class_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_change_rate'] = number_format(($item_list['teacher_change_lesson']/$item_list['valid_count'])*100,2);
            }else{
                $item_list['lesson_leavel_rate'] = 0;
                $item_list['lesson_come_late_rate'] = 0;
                $item_list['lesson_cut_class_rate'] = 0;
                $item_list['lesson_change_rate'] = 0;
            }

            E\Eteacher_money_type::set_item_value_str($item_list);
        }

        $all_item=["teacher_nick" => "全部" ];
        foreach ($ret_info as &$item) {
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && (($key == "stu_num") || ($key =="valid_count") || ($key == "teacher_come_late_count") || ($key == "teacher_cut_class_count") || ($key =="teacher_change_lesson")||($key == 'teacher_leave_lesson') || ($key == "work_time") )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
        }


        if($is_full_time == 1){
            $all_item['teacher_money_type_str'] = "兼职老师";
        }elseif($is_full_time == 2){
            $all_item['teacher_money_type_str'] = "全职老师";
        }


        $teacher_num = count($ret_info);
        if($teacher_num>0){
            $all_item['work_time'] = number_format($all_item['work_time']/$teacher_num,2);
            $all_item['lesson_leavel_rate'] = number_format($all_item['teacher_leave_lesson']/$all_item['valid_count']*100,2);
            $all_item['lesson_come_late_rate'] = number_format($all_item['teacher_come_late_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_cut_class_rate'] = number_format($all_item['teacher_cut_class_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_change_rate'] = number_format($all_item['teacher_change_lesson']/$all_item['valid_count']*100,2);
        }else{
            $all_item['work_time'] = 0;
            $all_item['lesson_leavel_rate'] = 0;
            $all_item['lesson_come_late_rate'] = 0;
            $all_item['lesson_cut_class_rate'] = 0;
            $all_item['lesson_change_rate'] = 0;
        }


        if($show_all_flag==1){
            array_unshift($ret_info, $all_item); 
        }
        $index_num=0;
        foreach($ret_info as &$p_item){
            $p_item["index_num"] = $index_num;
            $index_num++;

            if($p_item["teacher_nick"]=="全部"){
                $p_item["stu_num"]=$stu_num_all;
                $p_item["index_num"]=0;
            }
        }
        $path = '/var/www/admin.yb1v1.com/a.txt';
        //$path = '/home/sam/admin_yb1v1/a.txt';
        $fp = fopen($path,"a+");
        //dd($fp);
        foreach ($ret_info as $key => $value) {
            fwrite($fp, @$value['index_num']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_nick']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$value['stu_num']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$value['valid_count']);//4
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_come_late_count']);//5
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_come_late_rate']);//6
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_cut_class_count']);//7
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_cut_class_rate']);//8
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_change_lesson']);//9
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_change_rate']);//10
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_leave_lesson']);//11
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_leavel_rate']."%");//12
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_money_type_str']);//13
            fwrite($fp, '   ');
            fwrite($fp, @$value['work_time'].'天');//14
            fwrite($fp, "\n");
        }
        fclose($fp);
        */
    }
}
