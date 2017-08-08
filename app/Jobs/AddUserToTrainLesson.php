<?php
namespace App\Jobs;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserToTrainLesson extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $lessonid;
    public $teacherid_list;
    public $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lessonid,$teacherid_list,$type)
    {
        $this->lessonid       = $lessonid;
        $this->teacherid_list = $teacherid_list;
        $this->type           = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(is_array($this->teacherid_list)){
            foreach($this->teacherid_list as $val){
                if(isset($val['teacherid']) && $val['teacherid']>0){
                    $this->add_user_to_lesson($this->lessonid,$val['teacherid'],$this->type);
                }
            }
        }else{
            $this->add_user_to_lesson($this->lessonid,$this->teacherid_list,$this->type);
        }
    }

    private function add_user_to_lesson($lessonid,$userid,$type){
        $t_teacher_info      = new \App\Models\t_teacher_info();
        $t_lesson_info       = new \App\Models\t_lesson_info();
        $t_train_lesson_user = new \App\Models\t_train_lesson_user();

        $ret = $t_train_lesson_user->row_insert([
            "lessonid" => $lessonid,
            "userid"   => $userid,
            "add_time" => time(),
        ]);
        \App\Helper\Utils::logger("lessonid :".$lessonid." userid ".$userid);

        if($type==1){
            $teacher_info = $t_teacher_info->get_teacher_info($userid);
            $lesson       = $t_lesson_info->get_lesson_info($lessonid);
            $lesson_time  = \App\Helper\Utils::get_lesson_time($lesson['lesson_start'],$lesson['lesson_end']);
            if($teacher_info['wx_openid']==""){
                /**
                 * 模板名称 : 老师培训通知
                 * 模板ID   : SMS_53130038
                 * 模板内容 : ${name}老师您好，理优教育特邀请你参加新老师入职培训，培训时间${date_time}，
                 请用你的老师帐号绑定“理优1对1老师帮”公众号，绑定成功后即可参加培训，培训通过后通过公众号即可查看入职，
                 排课，薪资等信息。
                */
                $sms_id    = 53130038;
                $sign_name = \App\Helper\Utils::get_sms_sign_name();
                $arr = [
                    "name"      => $teacher_info['nick'],
                    "date_time" => $lesson_time,
                ];
                \App\Helper\Utils::sms_common($teacher_info['phone'],$sms_id,$arr,0,$sign_name);
            }else{
                /**
                 * 模板ID : dnpMpxpO0k7ykLfcp9LzPQHfoSq38pIa5l2YJdRSmdE
                 * 标题   : 课程开课通知
                 * 您好，{{userName.DATA}}。
                 * 您报名参加的{{courseName.DATA}}将于{{date.DATA}}开课，特此通知。
                 {{remark.DATA}}
                */
                $template_id        = "dnpMpxpO0k7ykLfcp9LzPQHfoSq38pIa5l2YJdRSmdE";
                $data['userName']   = $teacher_info['nick'];
                $data['courseName'] = $lesson['lesson_name'];
                $data['date']       = $lesson_time;
                $data['remark']     = "请准时参加！";
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data);
            }
        }
    }

}
