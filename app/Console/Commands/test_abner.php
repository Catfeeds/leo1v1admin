<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //获取所有违规老师的数据
        $begin_time = strtotime(date('Y-10-1'));
        $end_time = strtotime('+ 1 month',$begin_time);
        $teacher_violation = $this->task->t_teacher_info->get_teacher_violation($begin_time,$end_time);
        $teacher_violation_arr = [];
        //记录学生课程情况数组
        $teacher_student_arr = [];
        $is_turn_teacher = 0;
        foreach($teacher_violation as $item){
            if(@$teacher_student_arr[$item['userid']][$item['subject']]['teacherid'] && @$teacher_student_arr[$item['userid']][$item['subject']]['teacherid'] != $item['teacherid'])//该学生该课程的老师存在变更
                $is_turn_teacher = 1;

            $teacher_student_arr[$item['userid']][$item['subject']] = [
                'teacherid' => $item['teacherid'],
            ];

            if(!@$teacher_violation_arr[$item['teacherid']]){
                //初始化每个老师的数据
                $teacher_violation_arr[$item['teacherid']] = [
                    'teacher_id' => $item['teacherid'],
                    'teacher_name' => $item['realname'],
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
            }

            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && ($item['lesson_type'] == 0 || $item['lesson_type'] == 1 && $item['lesson_type'] == 3))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_upload_cw'] == 1)
                $teacher_violation_arr[$item['teacherid']]['no_notes_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_come_late'] == 1 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_later_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_come_late'] == 1  && ($item['lesson_type'] == 0 || $item['lesson_type'] == 1 && $item['lesson_type'] == 3))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_later_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_rate_student'] == 1)
                $teacher_violation_arr[$item['teacherid']]['no_evaluation_count'] ++;
            if($item['confirm_flag'] != 2 && $item['lesson_del_flag'] == 0 && $item['deduct_change_class'] == 1)
                $teacher_violation_arr[$item['teacherid']]['turn_class_count'] ++;


            if($item['confirm_flag'] = 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 12)
                $teacher_violation_arr[$item['teacherid']]['ask_for_leavel_count'] ++;
            if($item['confirm_flag'] = 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 21 && $item['lesson_type'] == 2)
                $teacher_violation_arr[$item['teacherid']]['test_lesson_truancy_count'] ++;
            if($item['confirm_flag'] = 2 && $item['lesson_del_flag'] == 0 && $item['lesson_cancel_reason_type'] == 21 && ($item['lesson_type'] == 0 || $item['lesson_type'] == 1 && $item['lesson_type'] == 3))
                $teacher_violation_arr[$item['teacherid']]['regular_lesson_truancy_count'] ++;

            if($is_turn_teacher == 1 && ($item['lesson_type'] == 0 || $item['lesson_type'] == 1 && $item['lesson_type'] == 3))
                $teacher_violation_arr[$teacher_student_arr[$item['userid']][$item['subject']]['teacherid']]['turn_teacher_count'] ++;


        }

        //打印老师数据
        $path = '/var/www/admin.yb1v1.com/10.txt';
        $fp = fopen($path,"a+");
        foreach($teacher_violation_arr as $item){
            fwrite($fp, @$item['teacher_id']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$item['teacher_name']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$item['test_lesson_count']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$item['regular_lesson_count']);//4
            fwrite($fp, '   ');
            fwrite($fp, @$item['no_notes_count']);//5
            fwrite($fp, '   ');
            fwrite($fp, @$item['test_lesson_later_count']);//6
            fwrite($fp, '   ');
            fwrite($fp, @$item['regular_lesson_later_count']);//7
            fwrite($fp, '   ');
            fwrite($fp, @$item['no_evaluation_count']);//8
            fwrite($fp, '   ');
            fwrite($fp, @$item['turn_class_count']);//9
            fwrite($fp, '   ');
            fwrite($fp, @$item['ask_for_leavel_count']);//10
            fwrite($fp, '   ');
            fwrite($fp, @$item['test_lesson_truancy_count']);//11
            fwrite($fp, '   ');
            fwrite($fp, @$item['regular_lesson_truancy_count']);//12
            fwrite($fp, '   ');
            fwrite($fp, @$item['turn_teacher_count']);//13
            fwrite($fp, "\n");
        }

        fclose($fp);
        echo 'ok!';

        //更新优学优享用户头像  --begin--
        // $data = $this->get_wx_user_info($wx_openid='oAJiDwEId4b1lA6WV1wbRS83WXvo');
        // print_r($data);
        // $status = $this->task->t_agent->field_updte_list($id=1316, [
        //     'headimgurl' => $data['headimgurl']
        // ]);
        // echo $status;
        // echo 'ok';
        //更新优学优享用户头像  --begin--

        // $this->get_teacher_case();
        // $this->get_today_headline
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
