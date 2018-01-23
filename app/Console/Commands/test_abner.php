<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_abner extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_abner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'abner测试及导数据专用';

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
     * param:获取新老师帯课及课耗情况
     * @return mixed
     */
    public function handle()
    {
        $start_time = strtotime(date('2017-10-01'));
        $end_time = strtotime(date('2018-01-01'));
        $count_one = 0;
        $count_two = 0;
        //获取第四季度有常规课的学生
        $q4_reading_stu = $this->task->t_lesson_info_b3->get_q4_reading_stu($start_time,$end_time);
        //获取首冲相关信息
        foreach($q4_reading_stu as $key => &$item){
            $first_flush_info = $this->task->t_order_info->get_first_flush_info($key,$end_time);
            $item['first_flush_time'] = $first_flush_info['first_flush_time'];
            $item['first_flush_class_pag'] = $first_flush_info['first_flush_class_pag']/100;

            //获取续费相关信息
            $renewal_info = $this->task->t_order_info->get_renewal_info($key,$start_time,$end_time);
            $item['renewal_count'] = $renewal_info['renewal_count'];
            $item['renewal_class_pag'] = $renewal_info['renewal_class_pag']/100;
            if($renewal_info['q4_renewal'] > 0)
                $item['is_11_12_renewal'] = 'Y';
            else
                $item['is_11_12_renewal'] = 'N';

            //计算到第四季度末剩余课时数
            //总课时数
            $all_class_pag = $this->task->t_order_info->get_all_class_pag($key,$end_time);
            //消耗课时数
            $use_class_pag = $this->task->t_lesson_info_b3->get_use_class_pag($key,$end_time);
            $item['left_class_pag'] = ($all_class_pag-$use_class_pag)/100;

            //计算课耗相关数据
            $q4_class_info = $this->task->t_lesson_info_b3->get_q4_class_info($key,$start_time,$end_time);
            $item['q4_lesson_count'] = $q4_class_info['q4_lesson_count']/100;
            $item['q4_class_count'] = $q4_class_info['q4_class_count'];
            $q4_subject_count = $q4_class_info['q4_subject_count'];
            $q4_class_month = ceil(($q4_class_info['lesson_end'] - $q4_class_info['lesson_start'])/(3600*24*30));
            if($q4_class_month == 0)
                $q4_class_month = 1;
            $item['month_average_subject'] = number_format($q4_subject_count/$q4_class_month,2);


            //转介绍相关数据
            $introduce_count = $this->task->t_student_info->get_introduce_count($key,$start_time,$end_time);
            if($introduce_count > 0)
                $item['is_introduce'] = 'Y';
            else
                $item['is_introduce'] = 'N';
            $item['introduce_count'] = $introduce_count;

            //小班课 公开课相关数据
            $lesson_type_info = $this->task->t_lesson_info_b3->get_lesson_type_info($key,$start_time,$end_time);
            if($lesson_type_info['small_class_count'] > 0)
                $item['is_small_class'] = 'Y';
            else
                $item['is_small_class'] = 'N';
            $item['public_class_count'] = $lesson_type_info['public_class_count'];

            //获取扩科信息
            $expand_subject_count = $this->task->t_lesson_info_b3->get_expand_subject_count($key,$start_time,$end_time);
            if($expand_subject_count > 1){
                $item['is_expand'] = 'Y';
                //首次扩科时间
                $first_expand_list = $this->task->t_lesson_info_b3->get_first_expand_time($key);
                $first_expand_arr = array_column($first_expand_list, 'lesson_start');
                $first_expand_time = max($first_expand_arr);
                $item['expand_first_year'] = date('Y',$first_expand_time);
                $item['expand_first_month'] = date('m',$first_expand_time);

            }else{
                $item['is_expand'] = 'N';
                $item['expand_first_year'] = '空';
                $item['expand_first_month'] = '空';
            }

            //处理信息
            if($item['gender'] == 0)
                $item['sex'] = '保密';
            elseif($item['gender'] == 1)
                $item['sex'] = '男';
            elseif($item['gender'] == 2)
                $item['sex'] = '女';

            E\Egrade::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item,"editionid");
            $item['first_flush_time'] = date('Y年m月',$item['first_flush_time']);
            if($item['grade']<=106)
                $item['grade_sort'] = '小学';
            elseif($item['grade']<=203)
                $item['grade_sort'] = '初中';
            elseif($item['grade']<=303)
                $item['grade_sort'] = '高中';

            if(empty($item['phone_province']))
                $item['phone_province']='空';

            if(empty($item['renewal_class_pag']))
                $item['renewal_class_pag'] = 0;

            if(empty($item['nick']))
                $item['nick'] = '空';

            echo 'count_one:'.$count_one++.'ok'."\n";
        }

        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        fwrite($fp, 'ID');//ID
        fwrite($fp, '   ');
        fwrite($fp, '姓名');//姓名
        fwrite($fp, '   ');
        fwrite($fp, '性别');//性别
        fwrite($fp, '   ');
        fwrite($fp, '年级');//年级
        fwrite($fp, '   ');
        fwrite($fp, '年级分类');//年级分类
        fwrite($fp, '   ');
        fwrite($fp, '城市');//城市
        fwrite($fp, '   ');
        fwrite($fp, '教材版本');//教材版本
        fwrite($fp, '   ');
        fwrite($fp, '首冲时间');//首冲时间
        fwrite($fp, '   ');
        fwrite($fp, '首冲课时包');//首冲课时包
        fwrite($fp, '   ');
        fwrite($fp, '累计续费次数');//累计续费次数
        fwrite($fp, '   ');
        fwrite($fp, '累计续费课时包');//累计续费课时包
        fwrite($fp, '   ');
        fwrite($fp, '剩余课时数');//剩余课时数
        fwrite($fp, '   ');
        fwrite($fp, '是否17年11&12月续费');//是否17年11&12月续费
        fwrite($fp, '   ');
        fwrite($fp, '单月平均在读门数');//单月平均在读门数
        fwrite($fp, '   ');
        fwrite($fp, 'Q4累计课耗数');//Q4累计课耗数
        fwrite($fp, '   ');
        fwrite($fp, 'Q4累计课次');//Q4累计课次
        fwrite($fp, '   ');
        fwrite($fp, '是否扩科');//是否扩科
        fwrite($fp, '   ');
        fwrite($fp, '扩科时间(年)');//扩科时间(年)
        fwrite($fp, '   ');
        fwrite($fp, '扩科时间(月)');//扩科时间(月)
        fwrite($fp, '   ');
        fwrite($fp, '是否转介绍');//是否转介绍
        fwrite($fp, '   ');
        fwrite($fp, '转介绍成功数');//转介绍成功数
        fwrite($fp, '   ');
        fwrite($fp, '是否小班课');//是否小班课
        fwrite($fp, '   ');
        fwrite($fp, '公开课次数');//公开课次数
        fwrite($fp, "\n");
        foreach($q4_reading_stu as $item){
            fwrite($fp, @$item['userid']);//ID
            fwrite($fp, '   ');
            fwrite($fp, @$item['nick']);//姓名
            fwrite($fp, '   ');
            fwrite($fp, @$item['sex']);//性别
            fwrite($fp, '   ');
            fwrite($fp, @$item['grade_str']);//年级
            fwrite($fp, '   ');
            fwrite($fp, @$item['grade_sort']);//年级分类
            fwrite($fp, '   ');
            fwrite($fp, @$item['phone_province']);//城市
            fwrite($fp, '   ');
            fwrite($fp, @$item['editionid_str']);//教材版本
            fwrite($fp, '   ');
            fwrite($fp, @$item['first_flush_time']);//首冲时间
            fwrite($fp, '   ');
            fwrite($fp, @$item['first_flush_class_pag']);//首冲课时包
            fwrite($fp, '   ');
            fwrite($fp, @$item['renewal_count']);//累计续费次数
            fwrite($fp, '   ');
            fwrite($fp, @$item['renewal_class_pag']);//累计续费课时包
            fwrite($fp, '   ');
            fwrite($fp, @$item['left_class_pag']);//剩余课时数
            fwrite($fp, '   ');
            fwrite($fp, @$item['is_11_12_renewal']);//是否17年11&12月续费
            fwrite($fp, '   ');
            fwrite($fp, @$item['month_average_subject']);//单月平均在读门数
            fwrite($fp, '   ');
            fwrite($fp, @$item['q4_lesson_count']);//Q4累计课耗数
            fwrite($fp, '   ');
            fwrite($fp, @$item['q4_class_count']);//Q4累计课次
            fwrite($fp, '   ');
            fwrite($fp, @$item['is_expand']);//是否扩科
            fwrite($fp, '   ');
            fwrite($fp, @$item['expand_first_year']);//扩科时间(年)
            fwrite($fp, '   ');
            fwrite($fp, @$item['expand_first_month']);//扩科时间(月)
            fwrite($fp, '   ');
            fwrite($fp, @$item['is_introduce']);//是否转介绍
            fwrite($fp, '   ');
            fwrite($fp, @$item['introduce_count']);//转介绍成功数
            fwrite($fp, '   ');
            fwrite($fp, @$item['is_small_class']);//是否小班课
            fwrite($fp, '   ');
            fwrite($fp, @$item['public_class_count']);//公开课次数
            fwrite($fp, "\n");

            echo 'count_two:'.$count_two++.'ok'."\n";
        }
        fclose($fp);
        echo 'ok!';

    }
    //@desn:更新优学优享用户头像
    private function update_yxyx_head(){
        $data = $this->get_wx_user_info($wx_openid='oAJiDwEId4b1lA6WV1wbRS83WXvo');
        print_r($data);
        $status = $this->task->t_agent->field_updte_list($id=1316, [
            'headimgurl' => $data['headimgurl']
        ]);
        echo $status;
        echo 'ok';
    }
    //@desn:1月例子未接通分析
    private function call_fail_analysis(){
        $begin_time = strtotime(date('2018-01-01'));
        $end_time = strtotime('+ 1 month',$begin_time);
        $call_arr = [
            'all_example_count' => 0,
            'no_connect_count' => 0,
            'no_call_succ' => 0,
            'cc_shut_count' => 0,
            'user_shut_count' => 0,
            'cc_shut_rate' => 0,
            'user_shut_rate' => 0,
            'less_30_count' => 0,
            'less_30_cc_shut' => 0,
            'less_30_user_shut' => 0,
            'less_30_cc_shut_rate' => 0,
            'less_30_user_shut_rate' => 0
        ];
        $user_arr = [];
        $is_connect_arr = [];
        $call_succ_arr= [];
        $more_30_arr = [];
        $count_arr = [];
        $count_30_arr = [];

        //获取全部进入的例子量，和未接通的例子
        $example_call_result = $this->task->t_test_lesson_subject->get_example_call_result($begin_time,$end_time);
        foreach($example_call_result as &$item){
            //总数量
            if(!@$user_arr[$item['userid']]){
                $call_arr['all_example_count'] ++;
                $user_arr[$item['userid']] = true;
            }
            //已接通 [status 为1的都为真正未接通的]
            if($item['is_called_phone'] == 1)
                $is_connect_arr[$item['userid']]=true;
            //接通并且通话时长大于60s
            if($item['duration'] >= 60)
                $call_succ_arr[$item['userid']]=true;
            //通话时长大于30s
            if($item['duration'] >= 30 && $item['duration'] < 60)
                $more_30_arr[$item['userid']]=true;

        }


        //遍历第二遍[将同一用户的状态调一致]
        foreach($example_call_result as &$item){
            if(@$is_connect_arr[$item['userid']])
                $item['status'] = 2; //status 为1的都为真正未接通
            if(@$call_succ_arr[$item['userid']])
                $item['status'] = 3; //status 为2的都为通话时长小于30s
            if(@$more_30_arr[$item['userid']])
                $item['status'] = 4; //status 为4的都为通话时长30-60s
        }

        $no_connect_arr = [];
        $shut_arr = [];
        $shut_30_arr = [];
        //遍历第三遍[计算60 30 总数]
        foreach($example_call_result as $item){
            if(!@$no_connect_arr[$item['userid']] && $item['status'] == 1){
                $call_arr['no_connect_count'] ++;
                $no_connect_arr[$item['userid']] = true;
            }

            if(!@$count_arr[$item['userid']] && in_array($item['status'], [2,4])){
                $call_arr['no_call_succ'] ++;
                $count_arr[$item['userid']] = true;
            }

            if(in_array($item['status'], [2,4]) && $item['end_reason'] == 0)
                $shut_arr[$item['userid']] = true;

            if(!@$count_30_arr[$item['userid']] && $item['status'] == 2){
                $call_arr['less_30_count'] ++;
                $count_30_arr[$item['userid']] = true;
            }

            if($item['status'] == 2 && $item['end_reason'] == 0)
                $shut_30_arr[$item['userid']] = true;
        }

        $shut_identity_arr = [];
        $shut_30_identity_arr = [];
        //遍历第四遍[计算挂断者]
        foreach($example_call_result as $item){
            if(!@$shut_identity_arr[$item['userid']] && in_array($item['status'], [2,4]) && @$shut_arr[$item['userid']])
                $call_arr['cc_shut_count'] ++;
            if(!@$shut_identity_arr[$item['userid']] && in_array($item['status'], [2,4]) && !@$shut_arr[$item['userid']])
                $call_arr['user_shut_count'] ++;

            $shut_identity_arr[$item['userid']] = true;

            if(!@$shut_30_identity_arr[$item['userid']] && $item['status'] == 2 && @$shut_30_arr[$item['userid']])
                $call_arr['less_30_cc_shut'] ++;
            elseif(!@$shut_30_identity_arr[$item['userid']] && $item['status'] == 2 && !@$shut_30_arr[$item['userid']])
                $call_arr['less_30_user_shut'] ++;

            $shut_30_identity_arr[$item['userid']] = true;

        }

        if($call_arr['no_call_succ'] != 0){
            $call_arr['cc_shut_rate'] = number_format(($call_arr['cc_shut_count']/$call_arr['no_call_succ']*100),2).'%';
            $call_arr['user_shut_rate'] = number_format(($call_arr['user_shut_count']/$call_arr['no_call_succ']*100),2).'%';
        }
        if($call_arr['less_30_count'] != 0){
            $call_arr['less_30_cc_shut_rate'] = number_format(($call_arr['less_30_cc_shut']/$call_arr['less_30_count']*100),2).'%';
            $call_arr['less_30_user_shut_rate'] = number_format(($call_arr['less_30_user_shut']/$call_arr['less_30_count']*100),2).'%';
        }

        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        fwrite($fp, '1月例子未接通分析');//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['all_example_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['no_connect_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['no_call_succ']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['cc_shut_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['user_shut_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['cc_shut_rate']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['user_shut_rate']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['less_30_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['less_30_cc_shut']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['less_30_user_shut']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['less_30_cc_shut_rate']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$call_arr['less_30_user_shut_rate']);//1
        fwrite($fp, "\n");
        fclose($fp);
        echo 'ok!';
    }
    //@desn:获取课程评价情况
    private function get_class_evaluation(){
        $begin_time = strtotime(date('2017-08-01'));
        $end_time = strtotime('+ 1 month',$begin_time);
        $lesson_evaluation_data = $this->task->t_lesson_info_b3->get_lesson_evaluation_data($begin_time,$end_time);
        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        fwrite($fp, '8月份');//1
        fwrite($fp, '   ');
        fwrite($fp, @$lesson_evaluation_data['test_lesson_count']);//1
        fwrite($fp, '   ');
        fwrite($fp, @$lesson_evaluation_data['test_evaluation_count']);//2
        fwrite($fp, '   ');
        fwrite($fp, @$lesson_evaluation_data['regular_lesson_count']);//3
        fwrite($fp, '   ');
        fwrite($fp, @$lesson_evaluation_data['regular_evaluation_count']);//13
        fwrite($fp, "\n");
        fclose($fp);
        echo 'ok!';
    }

    //@desn:获取老师违规数据明细
    //@date:2018-01-05
    private function get_teacher_violation_data(){
        //获取所有违规老师的数据
        $begin_time = strtotime(date('2017-10-1'));
        $end_time = strtotime('+ 1 month',$begin_time);
        $teacher_violation = $this->task->t_teacher_info->get_teacher_violation($begin_time,$end_time);
        $teacher_violation_arr = [];
        //记录学生课程情况数组
        $teacher_student_arr = [];
        $is_turn_teacher = 0;
        foreach($teacher_violation as $item){
            if(!@$teacher_violation_arr[$item['teacherid']]){
                //初始化每个老师的数据
                $teacher_violation_arr[$item['teacherid']] = [
                    'teacher_id' => $item['teacherid'],
                    'teacher_name' => $item['realname'],
                    'all_test_lesson_count' => 0,
                    'all_regular_lesson_count' => 0,
                    'test_lesson_count' => 0,
                    'regular_lesson_count' => 0,
                    'no_notes_count' => 0,
                    'test_lesson_later_count' => 0,
                    'regular_lesson_later_count' => 0,
                    'no_evaluation_count' => 0,
                    'turn_class_count' => 0,
                    'ask_for_leavel_count' => 0,
                    'test_lesson_truancy_count' => 0,
                    'regular_lesson_truancy_count' => 0,
                    'turn_teacher_count' => 0
                ];

                $teacher_student_arr[$item['userid']][$item['subject']][] = $item['teacherid'];
            }

            if(@isset($teacher_student_arr[$item['userid']][$item['subject']])
               // && @$teacher_student_arr[$item['userid']][$item['subject']]['teacherid'] != $item['teacherid']
               && !in_array($item['teacherid'],$teacher_student_arr[$item['userid']][$item['subject']])
            ){
                //该学生该课程的老师存在变更
                $is_turn_teacher = 1;
                $length = count($teacher_student_arr[$item['userid']][$item['subject']]);
                $key = $length-1;
                $err_teacher_id = $teacher_student_arr[$item['userid']][$item['subject']][$key];

                $teacher_student_arr[$item['userid']][$item['subject']][] = $item['teacherid'];
            }else{
                $is_turn_teacher = 0;
            }



            if($item['lesson_del_flag'] == 0 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['all_test_lesson_count'] ++;
            if($item['lesson_del_flag'] == 0 && in_array($item['lesson_type'],[0,1,3]))
                $teacher_violation_arr[$item['teacherid']]['all_regular_lesson_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && in_array($item['lesson_type'],[0,1,3]))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_upload_cw'] == 1)
                $teacher_violation_arr[$item['teacherid']]['no_notes_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_come_late'] == 1 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_later_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_come_late'] == 1  && in_array($item['lesson_type'],[0,1,3]))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_later_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_rate_student'] == 1)
                $teacher_violation_arr[$item['teacherid']]['no_evaluation_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_change_class'] == 1)
                $teacher_violation_arr[$item['teacherid']]['turn_class_count'] ++;


            if($item['confirm_flag'] == 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 12)
                $teacher_violation_arr[$item['teacherid']]['ask_for_leavel_count'] ++;
            if($item['confirm_flag'] == 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 21 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_truancy_count'] ++;
            if($item['confirm_flag'] == 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 21  && in_array($item['lesson_type'],[0,1,3]))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_truancy_count'] ++;

            if($is_turn_teacher == 1 && in_array($item['lesson_type'],[0,1,3])){
                $teacher_violation_arr[$err_teacher_id]['turn_teacher_count'] ++;
            }


        }


        //打印老师数据
        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        foreach($teacher_violation_arr as $item){
            fwrite($fp, @$item['teacher_id']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$item['teacher_name']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['all_test_lesson_count']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$item['all_regular_lesson_count']);//13
            fwrite($fp, "\n");
        }

        fclose($fp);
        echo 'ok!';

    }

    public function get_wx_user_info($wx_openid){
        $wx_config    = \App\Helper\Config::get_config("yxyx_wx");
        $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output,true);

        return $data;
    }

    //@param:获取每月各科目教师入职、帯课、试听课耗、常规课耗
    private function get_teacher_case(){
        //
        $start_time = strtotime(date('Y-09-01'));
        $end_time = strtotime('+1 month -1 second',$start_time);

        $flag_map = [];
        $teacher_map = [];
        $teacher_case = [
            '小学语文'=>[
                'name' => '小学语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中语文'=>[
                'name' => '初中语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中语文'=>[
                'name' => '高中语文',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '小学数学'=>[
                'name' => '小学数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中数学'=>[
                'name' => '初中数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中数学'=>[
                'name' => '高中数学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '小学英语'=>[
                'name' => '小学英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中英语'=>[
                'name' => '初中英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中英语'=>[
                'name' => '高中英语',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中化学'=>[
                'name' => '初中化学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中化学'=>[
                'name' => '高中化学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中物理'=>[
                'name' => '初中物理',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '高中物理'=>[
                'name' => '高中物理',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '初中科学'=>[
                'name' => '初中科学',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ],
            '其他综合'=>[
                'name' => '其他综合',
                'teacher_count' => 0,
                'has_class' => 0,
                'test_count' => 0,
                'regular_count' => 0
            ]
        ];

        $data = $this->task->t_teacher_info->get_teacher_code($start_time,$end_time);
        foreach($data as $key => $item){

            if(!@$flag_map[$key]){
                if($item['subject'] == 1 && $item['grade'] <= 106 && !empty($item['courseid']) && $item['lesson_type'] < 1000){
                    $teacher_case['小学语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['高中语文']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] <= 106 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['小学数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['高中数学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] <= 106 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['小学英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['高中英语']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中化学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['高中化学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中物理']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['高中物理']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203 && !empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['初中科学']['has_class'] ++;
                    $flag_map[$key]=true;
                }elseif(!empty($item['courseid'])  && $item['lesson_type'] < 1000){
                    $teacher_case['其他综合']['has_class'] ++;
                    $flag_map[$key]=true;
                }

                

            }




            if(!@$teacher_map[$key]){
                if($item['subject'] == 1 && $item['grade'] <= 106){
                    $teacher_case['小学语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中语文']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] <= 106){
                    $teacher_case['小学数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中数学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] <= 106){
                    $teacher_case['小学英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中英语']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中化学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中化学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中物理']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303){
                    $teacher_case['高中物理']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203){
                    $teacher_case['初中科学']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }else{
                    $teacher_case['其他综合']['teacher_count'] ++;
                    $teacher_map[$key]=true;
                }
            }




            if($item['subject'] == 1 && $item['grade'] <= 106){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学语文']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['小学语文']['regular_count'] += $item['lesson_count'];
            }elseif($item['subject'] == 1 && $item['grade'] >= 200 && $item['grade'] <= 203){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中语文']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中语文']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 1 && $item['grade'] >= 300 && $item['grade'] <= 303){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中语文']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['高中语文']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 2 && $item['grade'] <= 106){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学数学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['小学数学']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 2 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中数学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中数学']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 2 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中数学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['高中数学']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 3 && $item['grade'] <= 106  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['小学英语']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['小学英语']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 3 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中英语']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中英语']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 3 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中英语']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['高中英语']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 4 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中化学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中化学']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 4 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中化学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['高中化学']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 5 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中物理']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中物理']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 5 && $item['grade'] >= 300 && $item['grade'] <= 303  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['高中物理']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['高中物理']['regular_count'] += $item['lesson_count'];

            }elseif($item['subject'] == 10 && $item['grade'] >= 200 && $item['grade'] <= 203  ){
                if($item['lesson_type'] == 2)
                    $teacher_case['初中科学']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['初中科学']['regular_count'] += $item['lesson_count'];

            }else{
                if($item['lesson_type'] == 2)
                    $teacher_case['其他综合']['test_count'] += $item['lesson_count'];
                else
                    $teacher_case['其他综合']['regular_count'] += $item['lesson_count'];

            }



        }

        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        foreach($teacher_case as $item){
            fwrite($fp, @$item['name']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$item['teacher_count']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['has_class']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['test_count']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$item['regular_count']);//3
            fwrite($fp, "\n");
        }

        fclose($fp);
        echo 'ok!';

    }
}
