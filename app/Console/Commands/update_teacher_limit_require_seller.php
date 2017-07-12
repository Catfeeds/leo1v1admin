<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_teacher_limit_require_seller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_limit_require_seller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '销售要求更改限课状态恢复';

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
        //  return ;
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time= time()-86400;
        $ret = $task->t_teacher_record_list->get_seller_require_record_info($time);
        $record_info = "咨询部老师排课相关操作已执行完毕，现恢复之前限课状态，希望老师认真备课，钻研学生试听需求，结合线上教学特色，制定出完善的课程计划，体现1对1个性化教学风格，老师加油！"; 
        foreach($ret as $item){
            $res = $task->t_lesson_info->check_seller_plan_lesson($item["teacherid"],$item["add_time"]);
            if($res==1){
                if($item["type"]==3){
                    $task->t_teacher_info->field_update_list($item["teacherid"],[
                        "limit_plan_lesson_type"=>$item["limit_plan_lesson_type_old"],
                        "limit_plan_lesson_time" =>time()
                    ]);
                    $task->t_teacher_record_list->row_insert([
                        "type" =>3,
                        "teacherid" =>$item["teacherid"],
                        "add_time" =>time(),
                        "limit_plan_lesson_type_old"=>$item["limit_plan_lesson_type"],
                        "limit_plan_lesson_type"=>$item["limit_plan_lesson_type_old"],
                        "record_info"   => $record_info,
                        "acc"   =>"system"
                    ]);
               
                                     

                    if($item["limit_plan_lesson_type_old"] >0 ){
                        /**
                         * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
                         * 标题   :课程冻结通知
                         * {{first.DATA}}
                         * 课程名称：{{keyword1.DATA}}
                         * 操作时间：{{keyword2.DATA}}
                         * {{remark.DATA}}
                         */
                        $data=[];
                        $template_id      = "n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc";//old

                        $data['first']    = "老师您好，近期我们进行教学质量抽查，你的课程被限制排课,一周试听课排课数量不超过".$item["limit_plan_lesson_type_old"]."次。\n您的课程反馈情况是：".$record_info;
                        $data['keyword1'] = "试听课";
                        $data['keyword2'] = date("Y-m-d H:i",time());
                        $data['remark']   = "参加相关培训达标后，系统会放开排课限制，"
                                          ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";
                        $openid = $task->t_teacher_info->get_wx_openid($item["teacherid"]);
                        //$openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
                        if(isset($openid) && isset($template_id)){
                            \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
                        }


                    }
                }else{
                    $task->t_teacher_info->field_update_list($item["teacherid"],[
                        "limit_week_lesson_num"=>$item["limit_week_lesson_num_old"],
                    ]);
                    $task->t_teacher_record_list->row_insert([
                        "type" =>7,
                        "teacherid" =>$item["teacherid"],
                        "add_time" =>time(),
                        "limit_week_lesson_num_old"=>$item["limit_week_lesson_num_new"],
                        "limit_week_lesson_num_new"=>$item["limit_week_lesson_num_old"],
                        "record_info"   => "周排课数恢复",
                        "acc"   =>"system"
                    ]);

                }
                $realname = $task->t_teacher_info->get_realname($item["teacherid"]);
                $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","老师限课状态恢复",$realname."老师的限课状态已经由系统恢复","");

            }
        }      

    }
}
