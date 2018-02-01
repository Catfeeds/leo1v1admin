<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdvanceTeacherWxEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $tea_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_time,$teacher_money_type,$deal_type)
    {
        $this->tea_info=[
            "start_time" => $start_time,
            "teacher_money_type"        => $teacher_money_type,
            "deal_type"                 => $deal_type
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tea_info                      = $this->tea_info;
        $start_time                = $tea_info['start_time'];
        $teacher_money_type        = $tea_info['teacher_money_type'];
        $deal_type                 = $tea_info['deal_type'];
        $task=new \App\Console\Tasks\TaskController();
        if($deal_type==1){//处理晋升
            $list = $task->t_teacher_advance_list->get_all_accept_no_send_list($start_time,$teacher_money_type);
            \App\Helper\Utils::logger("11111");

            foreach($list as $val){
                $level_after = $val["level_after"];
                $old_level = $val["level_before"];
                $teacherid = $val["teacherid"];

                //更新老师等级
                $task->t_teacher_info->field_update_list($teacherid,["level"=>$level_after]);
                $info = $task->t_teacher_info->field_get_list($teacherid,"teacher_money_type,teacher_type,nick,realname,wx_openid");
                $info["level"] = $level_after;
                $info["old_level"] = $old_level;

                $level_degree    = \App\Helper\Utils::get_teacher_level_str($info);

                $score = $task->t_teacher_advance_list->get_total_score($start_time,$teacherid);

                //已排課程工資等級更改
                // $level_start = strtotime(date("Y-m-01",time()));
                //晋升生效时间(默认为下一季度开始时间)
                $level_start = strtotime("+3 months",$start_time);
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
                $wx_openid = $info["wx_openid"];
                $realname = $info["realname"];
                $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";//测试用
                if($wx_openid){
                    $data=[];
                    $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
                    $data['first']    = "恭喜".$realname."老师,您已经成功晋级到了".$level_degree;
                    $data['keyword1'] = $realname;
                    $data['keyword2'] = $level_degree;
                    $data['keyword3'] = date("Y-m-01 00:00",$level_start);
                    /* $data['remark']   = "晋升分数:".$score
                       ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";*/
                    $data['remark']   = "希望老师在今后的教学中继续努力,再创佳绩";

                    $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                    \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
               
                
                }

                // 邮件推送
                $html  = $task->teacher_level_up_html($info);
                $email = $task->t_teacher_info->get_email($teacherid);
                $email = "jack@leoedu.com";
                if($email){
                    dispatch( new \App\Jobs\SendEmailNew(
                        $email,"【理优1对1】老师晋升通知",$html
                    ));
                }

                //更新发送状态标识
                $task->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[              
                    "advance_wx_flag"=> 1
                ]);

            
            }
 
        }elseif($deal_type==2){//扣款处理
            // if ( \App\Helper\Utils::check_env_is_local() || \App\Helper\Utils::check_env_is_test() ){
            $list = $task->t_teacher_advance_list->get_no_deal_withhold_info($start_time,$teacher_money_type);
            $month_start = strtotime(date("Y-m-d",time()));
            foreach($list as $val){
                for($i=4;$i<7;$i++){
                    $month = strtotime(date("Y-m-d",strtotime("+$i months",$start_time)-86400)." 10:00");
                    $st = strtotime("+$i months",$start_time-86400);
                    if($st>=$month_start){                        
                        $task->t_teacher_money_list->row_insert([
                            "teacherid" =>$teacherid,
                            "type"      =>101,
                            "add_time"  =>$month,
                            "money"     => "-".$val["withhold_money"],
                            "money_info"=> date("Y-m-d",$month)." 等级不达标扣款"
                        ]);
                    }
                }
                $task->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                    "withhold_wx_flag"     => 1,
                ]);
            }

                   
                // }
 
        }
       


       
    }


}
