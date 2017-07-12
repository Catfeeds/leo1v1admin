<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_week_freeze_teacher_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_week_freeze_teacher_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每周课前4小时取消课程达课两次的老师,下一周禁止排课';

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();        
        $date_week           = \App\Helper\Utils::get_week_range(time(),1);
        $jw_teacher_list = $task->t_manager_info->get_jw_teacher_list_new();
        $tea_arr=[];
        foreach($jw_teacher_list as $lll){
            $tea_arr[$lll["uid"]]=$lll["account"];
        }

        $cancel_teacher_list = $task->t_teacher_cancel_lesson_list->get_cancel_teacher_list_new($date_week["sdate"],$date_week["edate"]);
        foreach($cancel_teacher_list as $item){
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "is_week_freeze"     =>1,
                "week_freeze_time"   =>time(),
                "week_freeze_reason" =>"一周内试听课课前4小时内取消课程次数达到2次以上"
            ]);
            
            /**
             * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
             * 标题   :课程冻结通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";
            $data['first']    = "老师您好,由于您本周多次(>=2次)课前取消试听课,将冻结您下周安排试听课机会,请及时安排好您的空余时间,避免此类情况再次发生";
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "如有疑问请联系教务老师，理优期待与你一起共同进步，提高教学服务质量。";
           
             $openid = $task->t_teacher_info->get_wx_openid($item["teacherid"]);
            /// $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($openid){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }
            foreach($tea_arr as $k=>$v){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优监课组","老师试听课前4小时取消两次以上,课程冻结通知",$v."老师你好,系统检查到".$item["realname"]."老师本周有两次以上试听课前4小时取消,现予以冻结下周安排试听课的措施,请知悉","");

            }

        }

        $warning_teacher_list = $task->t_teacher_cancel_lesson_list->get_warning_cancel_teacher_list_new($date_week["sdate"],$date_week["edate"]);
        foreach($warning_teacher_list as $item){           
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "week_freeze_warning_flag"     =>1,
            ]);

            /**
             * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
             * 标题   :课程冻结通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";
            $data['first']    = "老师您好,您本周已经有过一次取消试听课行为,请合理安排好您的空余时间。取消试听课≥2次/周,将会冻结您下周安排试听课的机会";
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "如有疑问请联系教务老师，理优期待与你一起共同进步，提高教学服务质量。";
           
            $openid = $task->t_teacher_info->get_wx_openid($item["teacherid"]);
            // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($openid){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }
        }

       
        $week_start = $date_week["sdate"]-14*86400;
        $week_end   = $date_week["edate"]-14*86400;
        $week_freeze_teacher_list = $task->t_teacher_info->get_week_freeze_teacher_list($week_start,$week_end);
        foreach($week_freeze_teacher_list as $item){
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "is_week_freeze"   =>0,
                "week_freeze_warning_flag"     =>0,
                "week_freeze_time" =>time()
            ]);
            /**
             * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
             * 标题课程 : 解冻通知
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 操作时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA";
            $data['first']    = "老师您好，您的课程已经解冻。";
            $data['keyword1'] = "试听课";
            $data['keyword2'] = date("Y-m-d H:i",time());
            $data['remark']   = "请合理安排好您的空闲时间，理优期待与你一起共同进步，提高教学服务质量。";
            
            $openid = $task->t_teacher_info->get_wx_openid($item["teacherid"]);
            // $openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($openid){
                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }

            foreach($tea_arr as $k=>$v){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优监课组","课程解冻通知",$v."老师你好,".$item["realname"]."老师课程已经解冻,请知悉","");

            }

            
        }
        
       
        

    }
}
