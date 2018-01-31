<?php
namespace App\Console\Tasks;
use \App\Enums as E;
use Illuminate\Support\Facades\Log;

use App\Helper\Net;
use App\Helper\Utils;

class TeacherTask extends TaskController
{
    var $teacher_wx_url;
    var $teacher_money;

    public function __construct(){
        parent::__construct();
        $this->teacher_wx_url = \App\Helper\Config::get_teacher_wx_url();
        $this->teacher_money  = \App\Helper\Config::get_config("teacher_money");
    }

    /**
     * 与课程相关内容的老师微信推送
     * @param openid 老师微信绑定id
     * @param lesson_info 课堂信息
     * @param type 1 课堂未评价通知 2-6 扣款通知 7 课堂结算通知 8 提醒上传学生讲义
     *             9 学生上传作业 10 课堂信息有误(学生,老师讲义,作业上传有问题)
     *             15试听/试听模拟课课前4小时未上传讲义通知
     * @param url  点击通知所跳转的地址
     * @return array
     */
    private function teacher_wx_data($openid,$lesson_info,$type=1,$url=""){
        if(!isset($lesson_info['stu_nick']) || $lesson_info['stu_nick']==''){
            $lesson_info['stu_nick'] = $this->cache_get_student_nick($lesson_info['userid']);
        }
        $stu_nick = $lesson_info['stu_nick'];

        if(!isset($lesson_info['tea_nick']) || $lesson_info['tea_nick']==''){
            $lesson_info['tea_nick']=$this->cache_get_teacher_nick($lesson_info['teacherid']);
        }
        $tea_nick = $lesson_info['tea_nick'];

        $grade_str       = E\Egrade::get_desc($lesson_info['grade']);
        if(@$lesson_info['subject']){
            $subject_str  = E\Esubject::get_desc($lesson_info['subject']);
        }else{
            $subject_str  = "";
        }

        $lesson_count    = $lesson_info['lesson_count'];
        $lesson_type_str = $lesson_info['lesson_type']==2?"试听课":"1对1";
        $lesson_time     = date("m-d H:i",$lesson_info['lesson_start'])."-".date("H:i",$lesson_info['lesson_end']);
        $str_ex          = "";

        if($type==1 || $type==11){
            /**
             * 标题        课程结束通知
             * template_id kCX3Vcbs_72dZdh1paHJZN1fgCBVc7_4obcSbovTrec
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 结束时间：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "kCX3Vcbs_72dZdh1paHJZN1fgCBVc7_4obcSbovTrec";//old
            $data['keyword1'] = $lesson_type_str;
            $data['keyword2'] = $lesson_time;
            if(isset($lesson_info['shut_time'])){
                $str_ex = "\n评价截止时间:".date("Y-m-d H:i",$lesson_info['shut_time']);
            }
        }elseif($type>1 && $type<7){
            /**
             * 标题        扣款通知
             * template_id 2yt4M2mJD7LMLcphWp6PS7VhC0Gv1mXG5zpHAyaeLEU
             * {{first.DATA}}
             * 扣款金额：{{keyword1.DATA}}
             * 扣款原因：{{keyword2.DATA}}
             * {{remark.DATA}}
             */
            $template_id = "2yt4M2mJD7LMLcphWp6PS7VhC0Gv1mXG5zpHAyaeLEU";//old
            if(isset($lesson_info['cost'])){
                $price = $lesson_info['cost'];
            }else{
                $price = \App\Helper\Utils::get_lesson_deduct_price($lesson_info,$type);
            }
            $data['keyword1'] = $price."元";
            $data['keyword2'] = $lesson_info['reason'];

            $str_ex = "\n上课时间:".$lesson_time;
        }elseif($type==7){
            /**
             * 标题        课程结算通知
             * template_id hZuApkEoPF16pIiyTSbpJZvGLfDgaOWNuBRSpVokFaY
             * {{first.DATA}}
             * 课程类型：{{keyword1.DATA}}
             * 上课时间：{{keyword2.DATA}}
             * 课时金额：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "hZuApkEoPF16pIiyTSbpJZvGLfDgaOWNuBRSpVokFaY";//old
            $data['keyword1'] = $lesson_type_str;
            $data['keyword2'] = $lesson_time;
            $data['keyword3'] = "待确认";
        }elseif($type==8 || $type==12 || $type=16){
            /**
             * 标题        课前提醒
             * template_id gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
             * {{first.DATA}}
             * 上课时间：{{keyword1.DATA}}
             * 课程类型：{{keyword2.DATA}}
             * 教师姓名：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
            $data['keyword1'] = $lesson_time;
            $data['keyword2'] = $lesson_type_str;
            $data['keyword3'] = $tea_nick;
        }elseif($type==9){
            /**
             * 标题        作业提醒
             * template_id fDZA7CfvQ10jKxQnrdHhAZm58kJTJBLe3XVnCpbwlLI
             * {{first.DATA}}
             * 课程名称：{{keyword1.DATA}}
             * 作业名称：{{keyword2.DATA}}
             * 截止日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $template_id      = "fDZA7CfvQ10jKxQnrdHhAZm58kJTJBLe3XVnCpbwlLI";
            $data['keyword1'] = $lesson_time."课程";
            $data['keyword2'] = $stu_nick."的作业";
        }

        $data['first']  = $lesson_info['info'];
        if($type==7){
            $data['remark'] = "上课学生:".$stu_nick
                ."\n年级:".$grade_str
                ."\n科目:".$subject_str
                .$str_ex;
        }else{
            $data['remark'] = "上课学生:".$stu_nick
                ."\n年级:".$grade_str
                ."\n课时数:".($lesson_count/100)."课时"
                .$str_ex;
        }

        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
    }

    /**
     * 课堂结束提醒老师评价学生
     * @param type=1
     */
    public function notice_set_stu_performance($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        foreach($lesson_list as &$val){
            \App\Helper\Utils::logger("notice set stu performance ".$val['lessonid']);
            if(!isset($val['lesson_type'])){
                \App\Helper\Utils::logger("lesson type is not set ".$val['lessonid']);
                continue;
            }

            if($val['lesson_type']==2){
                $end_time    = strtotime(date("Y-m-d",$val['lesson_start']))+86400;
                $lesson_end  = $val['lesson_end'];
                $time_period = 45*60;
                $val['shut_time'] = $lesson_end+$time_period;

                $lesson_list = $this->t_lesson_info->get_free_lesson_next($val['lesson_start'],$end_time,$val['teacherid']);
                foreach($lesson_list as $v){
                    $different=$v['lesson_start']-$lesson_end;
                    if($different>$time_period){
                        $val['shut_time']=$lesson_end+45*60;
                        break;
                    }else{
                        $lesson_end=$v['lesson_end'];
                    }
                }
                $val['info'] = "老师，辛苦了！刚才的试听课已经结束,请及时给出反馈报告";
                $url         = $this->teacher_wx_url['trial_list'];
            }else{
                $val['shut_time'] = $val['lesson_end']+86400*2;
                $val['info']      = "老师，辛苦了！刚才的1对1课程已经结束,请及时给出反馈报告";
                $url = $this->teacher_wx_url['normal_list'];
            }

            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $this->teacher_wx_data($openid,$val,$type,$url);
                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_comment_flag"=>1,
                ]);
                \App\Helper\Utils::logger("push succ lessonid info :".json_encode($val));
            }else{
                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_comment_flag"=>2,
                ]);
                \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']." this lessonid is :".$val['lessonid']);
            }
        }
    }

    /**
     * 上课迟到扣款,3次以内免责,前后课程时间间隔30分钟内,不算迟到
     * @param type=2
     */
    public function late_for_lesson($type){
        $end_time   = time()-300;
        $start_time = $end_time-60;
        $now        = date("Y-m-d",time());

        $month_date  = \App\Helper\Utils::get_month_date(time());
        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                if($val["train_type"]==4 && $val["lesson_type"]==1100){
                    //模拟试听课迟到五分钟推送
                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $lesson_time = date("H:i",$val["lesson_start"]);
                        /**
                         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                         * 标题课程 : 待办事项提醒
                         * {{first.DATA}}
                         * 待办主题：{{keyword1.DATA}}
                         * 待办内容：{{keyword2.DATA}}
                         * 日期：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */

                        $data=[];
                        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                        $data['first']    = "老师您好，请尽快进入课堂。";
                        $data['keyword1'] = "课程提醒";
                        $data['keyword2'] = $lesson_time."的模拟试听已开始5分钟，请尽快进入课堂，如有紧急情况请尽快联系教务老师";
                        $data['keyword3'] = date("Y-m-d H:i",time());
                        $data['remark']   = "";
                        $url = "";
                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                        $wx_come_flag = 1;
                    }else{
                        $wx_come_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }
                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "wx_come_flag"     => $wx_come_flag,
                        "deduct_come_late" => 1
                    ]);
                }else{
                    $account_str = "";
                    if($val['assistantid']>0 && $val['lesson_type']!=2){
                        $account = $this->cache_get_assistant_nick($val['assistantid']);
                        $url="/supervisor/monitor_ass?date=".$now."&teacherid=".$val['teacherid'];
                        $account_str="助教-".$account;
                    }elseif($val['lesson_type']==2){
                        $account = $this->t_seller_student_info->get_lesson_admin($val['lessonid']);

                        if(!$account){
                            $require_id             = $this->t_test_lesson_subject_sub_list->get_require_id($val['lessonid']);
                            $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
                            $require_adminid        = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
                            $account = $this->cache_get_account_nick($require_adminid);
                        }

                        $url="/supervisor/monitor_seller?date=".$now."&teacherid=".$val['teacherid'];
                        $account_str="销售-".$account;
                    }

                    if($account_str!=""){
                        $stu_nick   = $val['stu_nick'];
                        $tea_nick   = $val['tea_nick'];
                        $header_msg = $val['lessonid']."课程开始5分钟老师未到!!";
                        $from_user  = "老师-$tea_nick";
                        $msg        = $account_str;
                        $this->t_manager_info->send_wx_todo_msg($account,$from_user,$header_msg,$msg,$url);
                        \App\Helper\Utils::logger("teacher late for lesson, notice:".$account_str." this lessonid is".$val['lessonid']);
                    }

                    $last_lesson_end = $this->t_lesson_info->get_last_lesson_end($val['lesson_start'],$val['teacherid']);
                    if(($val['lesson_start']-$last_lesson_end)>1800){
                        $late_num = $this->t_lesson_info->get_cost_num($month_date['start'],$month_date['end'],$val['teacherid'],1);
                        if($late_num>=3){
                            $val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($val,$type);
                            $val['info'] = "老师您上课迟到5分钟，扣款".$val['cost']."元，从本月工资扣除，请下次提前进入课堂。";
                        }else{
                            $val['cost'] = 0;
                            $val['info'] = "老师你好，本月你已经迟到".($late_num+1)."次，每月总共有3次迟到机会，请下次提前进入课堂。";
                        }

                        $wx_come_flag = 2;
                        $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                        if($openid && $val['info']!=''){
                            $val['reason'] = "上课迟到";
                            $data          = $this->teacher_wx_data($openid,$val,$type);
                            $wx_come_flag  = 1;
                        }

                        $this->t_lesson_info->field_update_list($val['lessonid'],[
                            "wx_come_flag"     => $wx_come_flag,
                            "deduct_come_late" => 1
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 常规课课前未传学生讲义
     * @param type=3
     */
    public function not_upload_cw($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($val,$type);
                $val['info'] = "老师您上课未传学生讲义,扣款".$val['cost']."元,从本月工资扣除.下次注意上课前上传学生讲义";
                \App\Helper\Utils::logger("not upload cw. lessonid".$val['lessonid']
                                          ."stu_cw_time:".date("Y-m-d",$val['stu_cw_upload_time'])
                                          ."lesson_start:".date("Y-m-d",$val['lesson_start']));

                $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                if($openid){
                    $val['reason'] = "课前未传学生讲义";
                    $this->teacher_wx_data($openid,$val,$type);
                    $wx_upload_flag = 1;
                }else{
                    $wx_upload_flag = 2;
                    \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                }

                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_upload_flag"   => $wx_upload_flag,
                    "deduct_upload_cw" => 1
                ]);
            }
        }
    }

    /**
     * 试听课未及时评价扣款
     * @param type=4
     */
    public function late_for_rate_trial($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $end_time    = $start_time+86400;
                $lesson_end  = $val['lesson_end'];
                $ret_list    = $this->t_lesson_info->get_free_lesson_next($val['lesson_start'],$end_time,$val['teacherid']);
                $time_period = 120*60;
                $val['shut_time'] = $lesson_end+$time_period;
                if(is_array($ret_list)){
                    foreach($ret_list as $k=>$v){
                        $different = $v['lesson_start']-$lesson_end;
                        if($different > $time_period || ($k+1)==count($ret_list)){
                            $val['shut_time'] = $v['lesson_end']+$time_period;
                            break;
                        }else{
                            $lesson_end = $v['lesson_end'];
                        }
                    }
                }

                if($val['shut_time']<time()){
                    $val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($val,$type);
                    $val['info'] = "由于您的试听课未在课程结束后120分钟给出反馈,对家长了解孩子学习情况造成影响，"
                                 ."因此扣款".$val['cost']."元";
                    $openid      = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $val['reason'] = "试听课未及时评价";
                        $this->teacher_wx_data($openid,$val,$type);
                        $wx_rate_late_flag = 1;
                    }else{
                        $wx_rate_late_flag = 2;
                    }
                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "deduct_rate_student" => 1,
                        "wx_rate_late_flag"   => $wx_rate_late_flag
                    ]);
                }
            }
        }
    }

    /**
     * 常规课未及时评价扣款
     * @param type=5
     */
    public function late_for_rate_normal($type){
        $start_time = time()-86400*2;
        $end_time   = $start_time+60;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($val,$type);
                // $val['info'] = "老师由于您的1对1未在课程结束后2天内,未给出反馈，对家长了解孩子情况造成不便，扣款"
                //              .$val['cost']."元,请下次注意及时给出反馈";

                $val['info'] = "由于您未在规定时间内向进行评价反馈，依据《理优薪资规则》扣款".$val['cost']."元，请下次注意并及时给出评价反馈。";
                $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);

                $wx_rate_late_flag=2;
                if($openid){
                    // $val['reason'] = "1对1未及时评价";
                    // 扣费通知不再老师上课时间发送
                    $check_is_doing = $this->t_lesson_info_b3->check_is_doing($val['teacherid']);
                    if($check_is_doing != 1){
                        $val['reason'] = "常规课未及时评价";
                        $this->teacher_wx_data($openid,$val,$type);
                        $wx_rate_late_flag=1;
                    }
                }else{
                    $wx_rate_late_flag=2;
                }
                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_rate_late_flag"   => $wx_rate_late_flag,
                    "deduct_rate_student" => 1
                ]);
            }
        }
    }

    /**
     * 两天未批改学生作业(目前已取消)
     * @param type=6
     */
    public function late_for_check_homework($type){
        $start_time = time()-86400*2;
        $end_time   = $start_time+60;

        $week_time   = strtotime("tomorrow");
        $lesson_list = $this->t_lesson_info->get_not_check_homework_lesson($start_time,$end_time,$week_time);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($val,$type);
                $val['info'] = "老师,由于学生提交作业后,您未在48小时内批改,扣款".$val['cost']."元,请下次准时批改作业";
                $openid      = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                if($openid){
                    $val['reason'] = "未及时批改作业";
                    $this->teacher_wx_data($openid,$val,$type);
                    $wx_homework_flag=1;
                }else{
                    $wx_homework_flag=2;
                }
                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_homework_flag"      => $wx_homework_flag,
                    "deduct_check_homework" => 1
                ]);
            }
        }
    }

    /**
     * 课程结束的工资信息
     * @param type=7
     */
    public function notice_teacher_lesson_end($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        foreach($lesson_list as $val){
            if($val['lesson_type']!=2){
                if($val['teacherid']==71743){
                    $lesson_base = 60*$val['lesson_count']/100;
                }else{
                    $lesson_base = $val['money']*$val['lesson_count']/100;
                }
            }else{
                $lesson_base = \App\Helper\Utils::get_trial_base_price(
                    $val['teacher_money_type'],$val['teacher_type'],$val['lesson_start']
                );
            }

            // $url = 'http://wx-teacher-web.leo1v1.com/wage_details.html';
            $url = "http://wx-teacher.leo1v1.com/wx_teacher_web/gotoWage";
            $val['info'] = "老师您好，本次课程已结束，您本次课的基本工资为".$lesson_base."元，如有疑问请及时到老师帮【个人中心】-【我的收入】中添加申诉说明或点击'详情'申诉。本月课程申诉通道将于下月5号24:00关闭，给您带来不便,敬请谅解。";

            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $this->teacher_wx_data($openid,$val,$type,$url);
                $wx_tea_price_flag=1;
            }else{
                $wx_tea_price_flag=2;
            }
            $this->t_lesson_info->field_update_list($val['lessonid'],[
                "wx_tea_price_flag" => $wx_tea_price_flag
            ]);

        }
    }

    /**
     * 课前提醒老师上传学生讲义(前一天晚8点提醒)
     * @param type=8
     */
    public function notice_teacher_upload_stu_cw($type){
        $start_time = strtotime("tomorrow");
        $end_time   = $start_time+86400;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        foreach($lesson_list as $val){
            $val['info'] = "老师你好，你的学生明天的课程讲义未上传，请及时处理避免扣款。";
            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $this->teacher_wx_data($openid,$val,$type);
                \App\Helper\Utils::logger("notice teacher upload stu cw succ".$val['lessonid']);
            }else{
                \App\Helper\Utils::logger("notice teacher upload stu cw error".$val['lessonid']);
            }
        }
    }

    /**
     * 学生上传作业提醒老师(学生端触发"/stu_lesson/syn_homework_finish",触发后台"/wx_teacher/upload_stu_homework")
     * type=9
     * @param lessonid 需要提醒的课程id
     */
    public function notice_teacher_check_stu_homework($lessonid){
        $type        = 9;
        $lesson_info = $this->t_lesson_info->get_all_lesson_info($lessonid);
        if(!$lesson_info){
            return $this->output_err("课堂不存在!");
        }
        $openid = $this->t_teacher_info->get_wx_openid($lesson_info['teacherid']);

        if(!$openid){
            \App\Helper\Utils::logger("The teacher is not bound.".$lesson_info['teacherid']." lessonid ".$lesson_info['lessonid']);
        }else{
            $lesson_info['info'] = "您有一名学生提交了作业,请尽快批改!";
            $this->teacher_wx_data($openid,$lesson_info,$type);
        }
    }

    /**
     * 课堂信息有误(学生,老师讲义,作业上传有问题)
     * @param type=10
     */
    public function notice_teacher_for_lesson_info($type){
        $start   = strtotime(date("Y-m-d",time()));
        $end     = $start+86400;
        $account = "adrian";

        $ret_list = $this->t_lesson_info->get_error_lesson_list($start,$end);
        foreach($ret_list as $val){
            if($val['assistantid']>0 && $val['lesson_type']!=2){
                //$account = $this->cache_get_assistant_nick($val['assistantid']);
                $url="/supervisor/monitor_ass?date=".$now."&teacherid=".$val['teacherid'];
                $account_str="助教-".$account;
            }elseif($val['lesson_type']==2){
                //$account = $this->t_seller_student_info->get_lesson_admin($val['lessonid']);
                if(!$account){
                    $require_id             = $this->t_test_lesson_subject_sub_list->get_require_id($val['lessonid']);
                    $test_lesson_subject_id = $this->t_test_lesson_subject_require->get_test_lesson_subject_id($require_id);
                    $require_adminid        = $this->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
                    $account = $this->cache_get_account_nick($require_adminid);
                }
                $url="/supervisor/monitor_seller?date=".$now."&teacherid=".$val['teacherid'];
                $account_str="销售-".$account;
            }else{
                $account="";
            }

            if($account){
                $stu_nick    = $val['stu_nick'];
                $tea_nick    = $val['tea_nick'];
                // $template_id = "1600puebtp9CfcIg41Oz9VHu6iRXHAJ8VpHKPYvZXT0";//old
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

                $ret = $this->t_manager_info->send_template_msg($account,$template_id,[
                    "first"    => $val['lessonid']."课程课件上传状态出错,请提醒老师重新上传!!",
                    "keyword1" => "老师-$tea_nick",
                    "keyword2" => $account_str,
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => "请点击[详情],进入管理系统查看",
                ],$url);
            }
        }
    }

    /**
     * 常规课后24小时老师未评价,提醒老师评价学生
     * @param type=11
     */
    public function notice_teacher_for_rate_student($type){
        $start_time = time()-86400;
        $end_time   = $start_time+60;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        foreach($lesson_list as &$val){
            $val['shut_time'] = $val['lesson_end']+86400*2;
            $val['info']      = "老师，您昨天有一堂1对1需要评价,请及时给出反馈报告";
            $url = $this->teacher_wx_url['normal_list'];

            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $this->teacher_wx_data($openid,$val,$type,$url);
                \App\Helper\Utils::logger("push succ lessonid info :".json_encode($val));
            }
        }
    }

    /**
     * 试听课课前1小时提醒未下载试卷的老师下载试卷
     * @param type=12
     */
    public function notice_teacher_download_paper($type){
        $start_time = time()+3600;
        $end_time   = $start_time+60;

        $lesson_list = $this->t_lesson_info->get_tea_paper_lesson_list($start_time,$end_time);
        foreach($lesson_list as $val){
            $val['info'] = "老师你好，您有一堂试听课即将开始，请到官网后台下载学生试卷并做好充分备课！。";
            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $this->teacher_wx_data($openid,$val,$type);
                \App\Helper\Utils::logger("notice teacher upload stu cw succ".$val['lessonid']);
            }else{
                \App\Helper\Utils::logger("notice teacher upload stu cw error".$val['lessonid']);
            }
        }
    }

    /**
     * 每周多次排课只需评价1节课程
     * @param type=13
     */
    public function late_for_rate_normal_by_week_num($type){
        $now        = time();
        $end_time   = strtotime("-2 day",$now);
        $start_time = strtotime("-9 day",$now);

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);

        if(is_array($lesson_list)){
            foreach($lesson_list as $lesson_val){
                $key_str=$lesson_val['teacherid']."_".$lesson_val['userid'];
                if(!isset($has_lesson[$key_str])){
                    $has_lesson[$key_str]["has_rate"]=0;
                }
                if($lesson_val["tea_rate_time"]>0){
                    $has_lesson[$key_str]["has_rate"]=1;
                }
                $has_lesson[$key_str][]=$lesson_val;
            }
        }

        if(is_array($has_lesson)){
            foreach($has_lesson as $has_val){
                if($has_val['has_rate']==0){
                    foreach($has_val as $lesson_key=>$lesson_val){
                        if($lesson_key==="has_rate"){
                            continue;
                        }
                        $lesson_val['cost'] = \App\Helper\Utils::get_lesson_deduct_price($lesson_val,$type);
                        $lesson_val['info'] = "老师由于您的1对1未在课程结束后2天内，未给出反馈，对家长了解孩子情况造成不便，扣款"
                                     .$lesson_val['cost']."元，请下次注意及时给出反馈。";
                        $openid = $this->t_teacher_info->get_wx_openid($lesson_val['teacherid']);
                        if($openid){
                            $lesson_val['reason'] = "1对1未及时评价";
                            $this->teacher_wx_data($openid,$lesson_val,$type);
                            $wx_rate_late_flag=1;
                        }else{
                            $wx_rate_late_flag=2;
                        }
                        $this->t_lesson_info->field_update_list($lesson_val['lessonid'],[
                            "wx_rate_late_flag"   => $wx_rate_late_flag,
                            "deduct_rate_student" => 1
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 每3天给培训未通过的老师推送
     * @param type=14
     */
    public function notice_teacher_not_through_list($type){
        $list = $this->t_train_lesson_user->get_not_through_user(0,0);

        /**
         * 标题   待处理通知
         * template_id 9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        foreach($list as $l_val){
            $data = [];
            if($l_val['wx_openid']!=""){
                $data['first']    = $l_val['nick']."老师您好！";
                $data['keyword1'] = "邀请参训通知";
                $data['keyword2'] = "近期我们通过数据调取，发现您试讲通过多日后培训依旧未有通过。考虑到近期入职老师较多，为方便各位老师顺利参加培训课程，我们的新师培训业已增设到每周4期，分别定于：周三周四晚19点，周五晚18点30，周六下午15点，老师可按照您的时间安排自由选择参训时间；如若时间冲突，亦可登录理优教师端后，点击【我的培训】，选择最新一期的新师培训，点击【播放视频】按钮观看回放，并在录像学习完毕后，点击【自我测评】按钮进行问卷答题。";
                $data['keyword3'] = date("Y-m-d",time());
                $data['remark']   = "此问卷可多次递交至90分即培训通过，通过后老师可收到公司正式【入职offer】并开启您在理优的线上教学之旅。若测评答题过程中有任何问题可以加入新师培训QQ群：315540732，并私聊管理员【师训】沈老师即可获得1对1小灶指导~ 暑期课程多多，福利多多~理优期待老师的加入，老师加油！";

                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data);
            }
        }

    }

    /**
     * 试听/试听模拟课课前4小时未传学生讲义,老师讲义,作业
     * @param type=15
     */
    public function before_four_hour_not_upload_cw($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = $start_time+86400;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                if(time() >= ($val["lesson_start"]-4*3600)){
                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    $subject_str = E\Esubject::get_desc($val['subject']);
                    if($openid){
                        $lesson_time = date("H:i",$val["lesson_start"]);
                        if($val["work_status"]==1){
                            $status_str ="讲义";
                        }elseif($val["stu_cw_upload_time"]>0 && $val["tea_cw_upload_time"]>0){
                            $status_str = "作业";
                        }else{
                             $status_str ="讲义和作业";
                        }
                        /**
                         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                         * 标题课程 : 待办事项提醒
                         * {{first.DATA}}
                         * 待办主题：{{keyword1.DATA}}
                         * 待办内容：{{keyword2.DATA}}
                         * 日期：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */

                        if($val['train_type'] == 4){ // 模拟试听
                            $data=[];
                            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                            $data['first']    = "老师您好,".$lesson_time."的模拟课程未上传".$status_str;
                            $data['keyword1'] = $status_str."上传提醒";
                            $data['keyword2'] = $lesson_time."的模拟课程未上传".$status_str.",请尽快登录老师后台进行处理";
                            $data['keyword3'] = date("Y-m-d H:i",time());
                            $data['remark']   = "";
                            $url = "";
                        }elseif($val['lesson_type'] == 2){ // 试听课
                            $data=[];
                            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                            $data['first']    = "老师您好,".$lesson_time."的 $subject_str 课未上传讲义";
                            $data['keyword1'] = "讲义上传提醒";
                            $data['keyword2'] = $lesson_time."的 $subject_str 课未上传讲义,请尽快登录老师后台进行处理";
                            $data['keyword3'] = date("Y-m-d H:i",time());
                            $data['remark']   = "";
                            $url = "";
                        }

                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                        $wx_before_four_hour_cw_flag = 1;
                    }else{
                        $wx_before_four_hour_cw_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }

                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "wx_before_four_hour_cw_flag"   => $wx_before_four_hour_cw_flag
                    ]);

                }


            }
        }
    }

    /**
     * 试听/试听模拟课课前30分钟上课提醒
     * @param type=16
     */
    public function before_thirty_minute_wx($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = $start_time+86400;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                if(time() >= ($val["lesson_start"]-1800)){

                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $lesson_time     = date("m-d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
                        /**
                         * 标题        课前提醒
                         * template_id gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
                         * {{first.DATA}}
                         * 上课时间：{{keyword1.DATA}}
                         * 课程类型：{{keyword2.DATA}}
                         * 教师姓名：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */
                        $template_id      = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
                        $data['first']    = "老师您好,您于30分钟后有一节模拟试听课";
                        $data['keyword1'] = $lesson_time;
                        $data['keyword2'] = $val["lesson_name"];
                        $data['keyword3'] = $val["tea_nick"];

                        $data['remark']   = "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作";
                        $url = "";

                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                        $wx_before_thiry_minute_remind_flag = 1;
                    }else{
                        $wx_before_thiry_minute_remind_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }

                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "wx_before_thiry_minute_remind_flag"   => $wx_before_thiry_minute_remind_flag
                    ]);
                }
            }
        }
    }


    /**
     * 试听/试听模拟课课前一天晚八点上课提醒
     * @param type=17
     */
    public function tomorrow_lesson_remind_wx($type){
        $start_time = strtotime("tomorrow");
        $end_time   = $start_time+86400;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);

        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){

                $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                if($openid){
                    $lesson_start = date("H:i",$val['lesson_start']);
                    $lesson_time     = date("m-d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
                    /**
                     * 标题        课前提醒
                     * template_id gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
                     * {{first.DATA}}
                     * 上课时间：{{keyword1.DATA}}
                     * 课程类型：{{keyword2.DATA}}
                     * 教师姓名：{{keyword3.DATA}}
                     * {{remark.DATA}}
                     */
                    $template_id      = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
                    $data['first']    = "老师您好，您于明天".$lesson_start."有一节模拟试听课。";
                    $data['keyword1'] = $lesson_time;
                    $data['keyword2'] = "模拟试听";
                    $data['keyword3'] = $val["tea_nick"];

                    $data['remark']   = "请保持网络畅通，提前做好上课准备。";
                    $url = "";

                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
                }
            }
        }
    }

    /**
     * 模拟试听课堂结束提醒老师评价学生
     * @param type=18
     */
    public function train_lesson_notice_set_stu_performance($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        foreach($lesson_list as &$val){
            \App\Helper\Utils::logger("notice set stu performance ".$val['lessonid']);
            if(!isset($val['lesson_type'])){
                \App\Helper\Utils::logger("lesson type is not set ".$val['lessonid']);
                continue;
            }
            $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
            if($openid){
                $lesson_time = date("H:i",$val["lesson_start"]);
                /**
                 * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                 * 标题课程 : 待办事项提醒
                 * {{first.DATA}}
                 * 待办主题：{{keyword1.DATA}}
                 * 待办内容：{{keyword2.DATA}}
                 * 日期：{{keyword3.DATA}}
                 * {{remark.DATA}}
                 */

                $data=[];
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']    = "老师您好，请尽快对本节课做出评价。";
                $data['keyword1'] = "课程评价";
                $data['keyword2'] = $lesson_time."的模拟课程已结束，请尽快登录老师后台，进行评价。";
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "";
                $url = "";
                // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                $wx_comment_flag = 1;
            }else{
                $wx_comment_flag = 2;
                \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
            }
            $this->t_lesson_info->field_update_list($val['lessonid'],[
                "wx_comment_flag"     => $wx_comment_flag
            ]);
        }
    }


    /**
     * 模拟试听课未及时评价扣款
     * @param type=19
     */
    public function train_lesson_late_for_rate_trial($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $end_time    = $start_time+86400;
                $lesson_end  = $val['lesson_end'];
                $ret_list    = $this->t_lesson_info->get_free_lesson_next($val['lesson_start'],$end_time,$val['teacherid']);
                // $time_period = 45*60;
                $time_period = 120*60;
                $val['shut_time'] = $lesson_end+$time_period;
                if(is_array($ret_list)){
                    foreach($ret_list as $k=>$v){
                        $different = $v['lesson_start']-$lesson_end;
                        if($different > $time_period || ($k+1)==count($ret_list)){
                            $val['shut_time'] = $v['lesson_end']+$time_period;
                            break;
                        }else{
                            $lesson_end = $v['lesson_end'];
                        }
                    }
                }

                if($val['shut_time']<time()){
                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $grade_str   = E\Egrade::get_desc($val['grade']);
                        $lesson_time = date("m-d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);

                        /**
                         * 标题        扣款通知
                         * template_id 2yt4M2mJD7LMLcphWp6PS7VhC0Gv1mXG5zpHAyaeLEU
                         * {{first.DATA}}
                         * 扣款金额：{{keyword1.DATA}}
                         * 扣款原因：{{keyword2.DATA}}
                         * {{remark.DATA}}
                         */
                        $template_id = "2yt4M2mJD7LMLcphWp6PS7VhC0Gv1mXG5zpHAyaeLEU";//old

                        $data=[];
                        $data['first']    = "由于您未在规定时间内向进行评价反馈（2小时内），依据《理优薪资规则》扣款5元，请下次注意并及时给出评价反馈。（本次试听为模拟课程，将不进行实际扣款）";
                        $data['keyword1'] = "5元";
                        $data['keyword2'] = "模拟试听未及时评价";
                        $data['remark']   = "\n学生信息:".$val["stu_nick"]."(".$grade_str.")"
                                          ."\n上课时间:".$lesson_time
                                          ."\n课时数:1小时";
                        $url = "";
                        // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                        $wx_rate_late_flag = 1;
                    }else{
                        $wx_rate_late_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }


                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "deduct_rate_student" => 1,
                        "wx_rate_late_flag"   => $wx_rate_late_flag
                    ]);
                }
            }
        }
    }

    /**
     * 模拟试听课未评价倒计时15分钟提醒
     * @param type=20
     */
    public function train_lesson_no_comment_remind($type){
        $start_time = time()-105*60;
        $end_time   = time()-104*60;

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                if($openid){
                    $subject_str   = E\Esubject::get_desc($val["subject"]);
                    $lesson_time = date("H:i",$val['lesson_start']);
                    $lesson_day = date("Y-m-d H:i",$val['lesson_start']);

                    /**
                     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                     * 标题课程 : 待办事项提醒
                     * {{first.DATA}}
                     * 待办主题：{{keyword1.DATA}}
                     * 待办内容：{{keyword2.DATA}}
                     * 日期：{{keyword3.DATA}}
                     * {{remark.DATA}}
                     */

                    $data=[];
                    $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                    $data['first']    = "老师您好，".$lesson_time."的".$subject_str."课程已结束，距离课程评价截止时间只剩15分钟了";
                    $data['keyword1'] = "课程评价";
                    $data['keyword2'] = "\n 课程时间:".$lesson_day."\n评价方式:老师后台"
                                      ."\n距离评价截止时间只剩15分钟，请尽快进行评价。";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "";
                    $url = "";
                    // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);


                    $wx_no_comment_count_down_flag = 1;
                }else{
                    $wx_no_comment_count_down_flag = 2;
                    \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                }


                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_no_comment_count_down_flag"   => $wx_no_comment_count_down_flag
                ]);
            }
        }
    }

    /**
     * 模拟试听课旷课微信推送
     * @param type=21
     */
    public function train_lesson_absenteeism_set($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                if($openid){
                    $subject_str   = E\Esubject::get_desc($val["subject"]);
                    $lesson_time = date("H:i",$val['lesson_start']);
                    $lesson_day = date("Y-m-d H:i",$val['lesson_start']);

                    /**
                     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                     * 标题课程 : 待办事项提醒
                     * {{first.DATA}}
                     * 待办主题：{{keyword1.DATA}}
                     * 待办内容：{{keyword2.DATA}}
                     * 日期：{{keyword3.DATA}}
                     * {{remark.DATA}}
                     */

                    $data=[];
                    $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                    $data['first']    = $val["tea_nick"]."老师您好，".$lesson_time."的模拟试听课程已结束，您未能按时进入课堂。 ";
                    $data['keyword1'] = "旷课提醒";
                    $data['keyword2'] = "开课30分钟未进入课堂";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "";
                    $url = "";
                    // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                    \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);

                    $wx_absenteeism_flag = 1;
                }else{
                    $wx_absenteeism_flag = 2;
                    \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                }

                $this->t_lesson_info->field_update_list($val['lessonid'],[
                    "wx_absenteeism_flag"   => $wx_absenteeism_flag,
                    "absenteeism_flag"      => 1,
                ]);
                $id = $this->t_teacher_record_list->check_lesson_record_exist($val["lessonid"],1,5);
                $this->t_teacher_record_list->field_update_list($id,[
                    "trial_train_status"               => 2,
                    "record_info"                      => "旷课",
                    "add_time"                         => time(),
                    "acc"                              => "system"
                ]);

                $teacher_info = $this->t_teacher_info->get_teacher_info($val["teacherid"]);
                $this->add_trial_train_lesson($teacher_info,1);

            }
        }
    }


    /**
     * 模拟试听课离开课堂10分钟微信推送,先不执行
     * @param type=22
     */
    public function train_lesson_leave_set($type){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wx($start_time,$end_time,$type);
        if(is_array($lesson_list)){
            foreach($lesson_list as &$val){
                $leave_flag=0;
                $out_time = $this->t_lesson_opt_log->get_last_logout_time($val["lessonid"],$val["teacherid"],time());
                if($out_time>0 && (time()-$out_time)>=600 && (time()-$out_time)<660){
                    $in_time = $this->t_lesson_opt_log->get_min_login_time($val["lessonid"],$val["teacherid"],$out_time);
                    if(empty($in_time) || (($in_time-$out_time) > 600)){
                        $leave_flag=1;
                    }
                }
                if($leave_flag==1){
                    $openid = $this->t_teacher_info->get_wx_openid($val['teacherid']);
                    if($openid){
                        $subject_str   = E\Esubject::get_desc($val["subject"]);
                        $lesson_time = date("H:i",$val['lesson_start']);
                        $lesson_day = date("Y-m-d H:i",$val['lesson_start']);

                        /**
                         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
                         * 标题课程 : 待办事项提醒
                         * {{first.DATA}}
                         * 待办主题：{{keyword1.DATA}}
                         * 待办内容：{{keyword2.DATA}}
                         * 日期：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */
                        $data=[];
                        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                        $data['first']    = $val["tea_nick"]."老师您好，".$lesson_time."的模拟试听课程已结束，您未能按时进入课堂。 ";
                        $data['keyword1'] = "旷课提醒";
                        $data['keyword2'] = "开课30分钟未进入课堂";
                        $data['keyword3'] = date("Y-m-d H:i",time());
                        $data['remark']   = "";
                        $url = "";
                        // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);


                        $wx_absenteeism_flag = 1;
                    }else{
                        $wx_absenteeism_flag = 2;
                        \App\Helper\Utils::logger("teacher no bind wx".$val['teacherid']);
                    }


                    $this->t_lesson_info->field_update_list($val['lessonid'],[
                        "wx_absenteeism_flag"   => $wx_absenteeism_flag,
                        "absenteeism_flag"      => $absenteeism_flag
                    ]);
                }
            }
        }
    }
}