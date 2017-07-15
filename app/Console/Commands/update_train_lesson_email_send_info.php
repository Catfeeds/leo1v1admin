<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class update_train_lesson_email_send_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_train_lesson_email_send_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1v1邮件发送更新';

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
        $start_time = date("Y-m-d",time()+86400);
        $end_time =  $start_time+86400;
        $ret = $task->t_lesson_info_b2->get_all_train_interview_lesson_info($start_time,$end_time);
       
        foreach($ret as $val){
           
            $lesson_time = date("Y-m-d",$val["lesson_start"]);
            $start_str = date("H:i",$val["lesson_start"]);
            $end_str = date("H:i",$val["lesson_end"]);
            $lesson_time_str = $lesson_time." ".$start_str."-".$end_str; 

            $phone = $val["phone"];
            $email = $task->t_teacher_lecture_appointment_info->get_email_by_phone($phone);
            if($email){
                dispatch( new \App\Jobs\SendEmailNew(
                    $email,"【理优1对1】试讲邀请和安排","尊敬的".$val["realname"]."老师：<br>
感谢您对理优1对1的关注，您的录制试讲申请已收到！<br>
为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频<br><br>
【试讲信息】<br>
<font color='#FF0000'>账号：".$phone."</font><br>
<font color='#FF0000'>密码：123456 </font><br>
<font color='#FF0000'>时间：".$lesson_time_str."</font><br><br>
【试讲方式】<br>
 面试试讲（公校老师推荐）<br>
 电话联系老师预约可排课时间，评审老师和面试老师同时进入理优培训课堂进行面试，面试通过后，进行新师培训并完成自测即可入职<br>
<font color='#FF0000'>注意：若面试老师因个人原因不能按时参加1对1面试，请提前至少4小时告知招师老师，以便招师老师安排其他面试，如未提前4小时告知招师组老师，将视为永久放弃面试机会。</font><br><br>

【试讲要求】<br>
请下载好<font color='#FF0000'>理优老师客户端</font>并准备好<font color='#FF0000'>耳机和话筒</font>，用<font color='#FF0000'>指定内容</font>在理优老师客户端进行试讲<br>
 [相关下载]↓↓↓<br>
 1、理优老师客户端<a href='http://www.leo1v1.com/common/download'>点击下载</a><br>
 2、指定内容(内附【1对1面试】操作视频教程)<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.yb1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

（关注并绑定理优1对1老师帮公众号：随时了解入职进度）<br>
 [通关攻略]<br>
 1、保证相对安静的试讲环境和稳定的网络环境 [通关攻略]<br>
 2、要上传讲义和板书，试讲要结合板书<br>
 3、要注意跟学生的互动（假设电脑的另一端坐着学生）<br>
 4、简历、PPT完善后需转成PDF格式才能上传；<br>
 5、准备充分再录制，面试机会只有一次，要认真对待。<br>
<font color='#FF0000'>（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）</font><br>
 [面试步骤]<br>
 1、备课  —  2、试讲  —  3、培训  —  4、入职<br>
【联系我们】<br>
如有疑问请加QQ群 : 608794924<br>
  <img src='http://admin.yb1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
                ));

         
                $task->t_lesson_info->field_update_list($val["lessonid"],[
                    "train_email_flag"  =>1 
                ]);


            }
        }

        
       
               

    }
}
