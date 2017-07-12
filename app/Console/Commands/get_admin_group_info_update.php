<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_admin_group_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_admin_group_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '后台分组信息每月数据更新保存';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $month = strtotime(date("Y-m-01",time()));
        // $start_time = strtotime(date("Y-m-01",time()-86400));
        $admin_group_name_list = $task->t_admin_group_name->get_all_list();
        foreach($admin_group_name_list as $item){
            $task->t_group_name_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "main_type"  =>$item["main_type"],
                "group_name" =>$item["group_name"],
                "master_adminid"=>$item["master_adminid"],
                "up_groupid"   =>$item["up_groupid"],
                "group_assign_percent" =>$item["group_assign_percent"]
            ]);
        }
        $admin_group_user_list = $task->t_admin_group_user->get_all_list();
        foreach($admin_group_user_list as $item){
            $task->t_group_user_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "adminid"  =>$item["adminid"],
                "assign_percent" =>$item["assign_percent"]
            ]);
        }

        $admin_main_group_name_list = $task->t_admin_main_group_name->get_all_list();
        foreach($admin_main_group_name_list as $item){
            $task->t_main_group_name_month->row_insert([
                "groupid"    =>$item["groupid"],
                "month"      =>$month,
                "main_type"  =>$item["main_type"],
                "group_name" =>$item["group_name"],
                "master_adminid"=>$item["master_adminid"],
                "main_assign_percent" =>$item["main_assign_percent"]
            ]);
        }



        // dd($admin_group_name_list);

              
    }
}
