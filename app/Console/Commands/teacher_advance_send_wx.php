<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_advance_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_advance_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '季度晋升老师微信/邮件推送';

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
        $season = ceil((date('n'))/3)-1;//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
       
        $ret_info = $task->t_teacher_advance_list->get_advance_success_list($start_time);
        foreach($ret_info as $val){
            $old_level = $val["level_before"];
            $level_after = $val["level_after"];
            $teacherid = $val["teacherid"];
            $score = $val["total_score"];
            $info = $task->t_teacher_info->field_get_list($teacherid,"teacher_money_type,teacher_type,nick,realname");
            $info["level"] = $level_after;
            $info["old_level"] = $old_level;
 
            $level_degree    = \App\Helper\Utils::get_teacher_level_str($info);

            
            //已排課程工資等級更改
            $level_start = strtotime(date("Y-m-01",time()));
            $task->t_teacher_info->field_update_list($teacherid,["level"=>$level_after]);
            $teacher_money_type = $info["teacher_money_type"];
            $task->t_lesson_info->set_teacher_level_info_from_now($teacherid,$teacher_money_type,$level_after,$level_start);

            
            //微信通知老师
            /**
             * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
             * 标题课程 : 等级升级通知
             * {{first.DATA}}
             * 用户昵称：{{keyword1.DATA}}
             * 最新等级：{{keyword2.DATA}}
             * 生效时间：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $wx_openid = $task->t_teacher_info->get_wx_openid($teacherid);
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜您获得了晋升";
                $data['keyword1'] = $info["realname"];
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "晋升分数:".$score
                                  ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";
                $url = "http://admin.yb1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }

            //邮件推送
            $html = $task->teacher_level_up_html($info);
            $email = $task->t_teacher_info->get_email($teacherid);
            // $email = "jack@leoedu.com";
            if($email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,"【理优1对1】老师晋升通知",$html
                ));

 
            }

           
            //微信通知教研
            $subject = $task->t_teacher_info->get_subject($teacherid);
            $master_adminid = $task->get_tea_adminid_by_subject($subject);
            $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($master_adminid);
            $jy_teacherid = $teacher_info["teacherid"];
            $wx_openid = $task->t_teacher_info->get_wx_openid($jy_teacherid);
            // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";
            if($wx_openid){
                $data=[];
                $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                $data['first']    = "恭喜".$info["realname"]."获得了晋升";
                $data['keyword1'] = $info["realname"];
                $data['keyword2'] = $level_degree;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "";
                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
            }
        }

        // dd($ret_info);

     
        
       
               

    }
}
