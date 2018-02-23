<?php

namespace App\Jobs;

use \App\Enums as E;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class add_train_lesson extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $arr;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        parent::__construct();
        $this->arr = $arr;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->init_task();
        $subject_map = E\Esubject::$desc_map;
        $grade_map   = E\Egrade::$desc_map;
        //0日期 1审核老师姓名 2审核老师手机号 3具体时间 4面试老师姓名 5面试老师手机号 6科目 7年级 
        foreach($this->arr as $key => $val){
            if($key!=0 && $val[4]!="" && $val[5]!="" && $val[7]!=''){
                $val[0] = str_replace(".","-",$val[0])." ".$val[3];
                $date   = date("Y",time());
                if(strpos($val[0],$date)===false){
                    $val[0] = $date."-".$val[0];
                }
                $teacher_info['lesson_start']   = strtotime($val[0]);
                $teacher_info["record_teacher"] = $val[1];
                $teacher_info["record_phone"]   = $val[2];
                $teacher_info['nick']           = $val[4];
                $teacher_info['phone_spare']    = $val[5];
                $teacher_info['phone']          = $val[5];
                $teacher_info['subject']        = array_search($val[6],$subject_map);
                $teacher_info['grade']          = array_search($val[7],$grade_map);
                \App\Helper\Utils::logger("phone:".$teacher_info['phone']);
                $train_teacherid = $this->task->t_teacher_info->check_teacher_phone($teacher_info['phone']);
                if(!$train_teacherid){
                    $train_teacherid = $this->add_teacher($teacher_info);
                    \App\Helper\Utils::logger("add_train_lesson,teacherid : ".$train_teacherid);
                }

                $teacher_info['teacherid'] = $train_teacherid;
                \App\Helper\Utils::logger("train_teacherid".$teacher_info['teacherid']);

                if(\App\Helper\Utils::check_env_is_release()){
                    $teacher_info['record_teacherid'] = $this->task->t_teacher_info->get_teacherid_by_phone(
                        $teacher_info['record_phone']
                    );
                    if(!$teacher_info['record_teacherid']){
                        continue;
                    }
                }else{
                    $teacher_info['record_teacherid'] = 60024;
                }

                $check_flag = $this->task->t_lesson_info->check_teacher_time_free(
                    $teacher_info['record_teacherid'],0,$teacher_info['lesson_start'],$teacher_info['lesson_start']+1800
                );
                if(!$check_flag && $teacher_info['lesson_start']>time()){
                    $this->add_train_lesson($teacher_info);
                }
            }
        }
    }

    public function add_teacher($teacher_info){
        $phone       = $teacher_info['phone'];
        $tea_nick    = $teacher_info['nick'];
        $subject     = $teacher_info['subject'];
        $grade       = $teacher_info['grade'];
        $phone_spare = $teacher_info['phone_spare'];
        $passwd      = md5(123456);

        $this->task->t_user_info->start_transaction();
        $this->task->t_user_info->row_insert([
            "passwd" => $passwd,
        ]);
        $teacherid = $this->task->t_user_info->get_last_insertid();
        if (!$teacherid) {
            $this->task->t_user_info->rollback();
            return false;
        }

        $ret = $this->task->t_phone_to_user->add($phone,E\Erole::V_TEACHER,$teacherid) ;
        if (!$ret) {
            $this->task->t_user_info->rollback();
            return false;
        }

        $this->task->t_teacher_info->add_teacher_info_to_ejabberd($teacherid,$passwd);
        $ret = $this->task->t_teacher_info->row_insert([
            "teacherid"          => $teacherid,
            "nick"               => $tea_nick,
            "realname"           => $tea_nick,
            "phone"              => $phone,
            "phone_spare"        => $phone_spare,
            "teacher_money_type" => 4,
            "level"              => 0,
            "is_test_user"       => 1,
            "subject"            => $subject,
            "grade"              => $grade,
            "wx_use_flag"        => 0,
        ]);

        if(!$ret){
            \App\Helper\Utils::logger("error");
            $this->task->t_user_info->rollback();
            return false;
        }else{
            \App\Helper\Utils::logger("add teacher succ :".$teacherid);
            $this->task->t_user_info->commit();
        }
        \App\Helper\Utils::logger("succ");
        return $teacherid;
    }

    public function add_train_lesson($teacher_info){
        extract($teacher_info);
        $grade_str   = E\Egrade::get_desc($grade);
        $subject_str = E\Esubject::get_desc($subject);
        $lesson_name = $grade_str.$subject_str."试讲";
        $courseid    = $this->task->t_course_order->add_open_course($teacherid,$lesson_name,$grade,$subject,1100);
        $tea_cw_url  = "http://leowww.oss-cn-shanghai.aliyuncs.com/Teacher/试讲内容——".$grade_str.$subject_str.".pdf";
        $this->task->t_lesson_info->row_insert([
            "courseid"           => $courseid,
            "lesson_name"        => $lesson_name,
            "lesson_start"       => $lesson_start,
            "lesson_end"         => $lesson_start+1800,
            "subject"            => $subject,
            "grade"              => $grade,
            "teacherid"          => $record_teacherid,
            "lesson_type"        => 1100,
            "server_type"        => 2,
            "lesson_sub_type"    => 1,
            "train_type"         => 5,
            "tea_cw_url"         => $tea_cw_url,
            "tea_cw_status"      => 1,
            "tea_cw_upload_time" => time(),
        ]);

        $lessonid = $this->task->t_lesson_info->get_last_insertid();
        $this->task->t_train_lesson_user->row_insert([
            "lessonid" => $lessonid,
            "add_time" => time(),
            "userid"   => $teacherid,
        ]);

        $realname = $this->task->t_teacher_info->get_realname($teacherid);
        $phone = $this->task->t_teacher_info->get_phone($teacherid);
        $lesson_start_str = date("Y-m-d H:i:s",$lesson_start);
        $subject_str = E\Esubject::get_desc($subject);
        $grade_str = E\Egrade::get_desc($grade);
        $time_str = date("Y-m-d H:i:s",time());
        
        $lesson_time = date("Y-m-d",$lesson_start);
        $start_str = date("H:i",$lesson_start);
        $end_str = date("H:i",$lesson_start+1800);
        $lesson_time_str = $lesson_time." ".$start_str."-".$end_str; 



        //微信通知面试老师
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */

        $wx_openid        = $this->task->t_teacher_info->get_wx_openid($teacherid);
        if($wx_openid){
            $data=[];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $realname."老师您好,您的面试课程已排好";
            $data['keyword1'] = "1对1面试课程";
            $data['keyword2'] = "\n面试时间：$lesson_time_str "
                              ."\n面试账号：$phone"
                              ."\n面试密码：123456"
                              ."\n年级科目 : ".$grade_str."".$subject_str;
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "请查阅邮件(报名时填写的邮箱),准备好耳机和话筒,并在面试开始前5分钟进入软件,理优教育致力于打造高水平的教学服务团队,期待您的加入,加油!";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
 
        }
     
        //微信通知教研老师
        $uid = $this->task->t_manager_info->get_adminid_by_teacherid($record_teacherid);
        $record_realname = $this->task->t_teacher_info->get_realname($record_teacherid);
       
        $this->task->t_manager_info->send_wx_todo_msg_by_adminid ($uid,"1对1面试课程",$record_realname."老师您好,您的面试课程已排好","
面试时间:".$lesson_time_str."
面试老师:".$realname."
年级科目:".$grade_str."".$subject_str."
请准备好耳机和话筒,并在面试开始前5分钟进入软件","http://admin.leo1v1.com/tea_manage/train_lecture_lesson?lessonid=".$lessonid);


        //邮件通知面试老师
        $email = $this->task->t_teacher_lecture_appointment_info->get_email_by_phone($phone);
        if($email){
            dispatch( new \App\Jobs\SendEmailNew(
                $email,"【理优1对1】试讲邀请和安排","尊敬的".$realname."老师：<br>
感谢您对理优1对1的关注，您的录制试讲申请已收到！<br>
为了更好的评估您的教学能力，需要您尽快按照如下要求提交试讲视频<br><br>
【试讲信息】<br>
账号：".$phone."<br>
密码：123456 <br>
时间：".$lesson_time_str."<br><br>
【试讲方式】<br>
 面试试讲（公校老师推荐）<br>
 电话联系老师预约可排课时间，评审老师和面试老师同时进入理优培训课堂进行面试，面试通过后，进行新师培训并完成自测即可入职<br>
注意：若面试老师因个人原因不能按时参加1对1面试，请提前至少4小时告知招师老师，以便招师老师安排其他面试，如未提前4小时告知招师组老师，将视为永久放弃面试机会。<br><br>

【试讲要求】<br>
请下载好理优老师客户端并准备好耳机和话筒，用指定内容在理优老师客户端进行试讲<br>
 [相关下载]↓↓↓<br>
 1、理优老师客户端<a href='http://www.leo1v1.com/common/download'>点击下载</a><br>
 2、指定内容<a href='http://file.leo1v1.com/index.php/s/pUaGAgLkiuaidmW'>点击下载</a><br>
 [结果通知]<br>
  <img src='http://admin.leo1v1.com/images/lsb.png' alt='对不起,图片失效了'><br>

（关注并绑定理优1对1老师帮公众号：随时了解入职进度）<br>
 [通关攻略]<br>
 1、保证相对安静的试讲环境和稳定的网络环境 [通关攻略]<br>
 2、要上传讲义和板书，试讲要结合板书<br>
 3、要注意跟学生的互动（假设电脑的另一端坐着学生）<br>
 4、简历、PPT完善后需转成PDF格式才能上传；<br>
 5、准备充分再录制，面试机会只有一次，要认真对待。<br>
（温馨提示：请在每次翻页后在白板中画一笔，保证白板和声音同步）<br>
 [面试步骤]<br>
 1、备课  —  2、试讲  —  3、培训  —  4、入职<br>
【联系我们】<br>
如有疑问请加QQ群 : 608794924<br>
  <img src='http://admin.leo1v1.com/images/sjdy.png' alt='对不起,图片失效了'><br>

【LEO】试讲-答疑QQ群<br><br>

【岗位介绍】<br>
名称：理优在线1对1授课教师（通过理优教师端进行网络语音或视频授课）<br>
时薪：50-100RMB<br><br>

【关于理优】<br>
理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）"
            ));

            $this->task->t_lesson_info->field_update_list($lessonid,[
               "train_email_flag"  =>1 
            ]);
 
        }
        \App\Helper\Utils::check_env_is_release();
    }
}
