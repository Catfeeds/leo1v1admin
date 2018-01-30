<?php

namespace App\Console\Commands;
use \App\Enums as E;
use Illuminate\Console\Command;

class add_new_tea_entry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_new_tea_entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新师入职(面试,培训-模拟试听)存档';

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
        // 每日存档(每天凌晨二点刷新前一天数据) 月存档(每月存档)
        //$start_time = date('Y-m-d 00:00:00', strtotime('-1 day'));
        //$end_time = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $task = new \App\Console\Tasks\TaskController();

        echo "测试是否成功";
        exit;
        $task->t_teacher_info->field_update_list(469110, [
            'bank_type' => '中国工商银行'
        ]);
        exit;

        
        // 明日之星
        // $start_time = strtotime("2017-11-1");
        // $end_time = strtotime("2017-12-1");
        // $info = $task->t_teacher_lecture_appointment_info_b2->get_money_list($start_time, $end_time);
        // $data = $task->t_teacher_lecture_appointment_info_b2->get_money_list1($start_time, $end_time);
        // foreach($data as $key => $item) {
        //     if (!isset($info[$key])) echo $item['teacherid'].' '.$item['name'].PHP_EOL;
        // }
        // echo '===========相同==========='.PHP_EOL;
        // foreach($data as $key => $item) {
        //     if (isset($info[$key])) echo $item['teacherid'].' '.$item['name'].PHP_EOL;
        // }
        // exit;

        $teacher_money = new \App\Http\Controllers\teacher_money();
        $start_time = strtotime('2017-11-1');
        $end_time = strtotime("2017-12-1");
        $teacher= $teacher_money->get_teacher_salary(146762,$start_time,$end_time);
        dd($teacher);

        // 拉取数据(6月至11月的总工资)
        //$arr = [6,7,8,9,10,11];
        $arr = [11];
        foreach($arr as $item) {
            $start = '2017-'.$item.'-1';
            $end = '2017-'.($item+1).'-1';
            $start_time = strtotime($start);
            $end_time = strtotime($end);
            //$info = $task->t_teacher_salary_list->get_salary_list($start_time,$end_time);
            $info = $task->t_teacher_salary_list->get_teacher_money_info();

            $all_money = 0;//总工资
            $all_all_money = 0;//全职老师
            $all_not_money = 0;//兼职老师
            // foreach($info['list'] as &$t_val){
            //     $t_val['money']   /= 100;
            //     $all_money += $t_val['money'];
            //     if ($t_val['teacher_money_type'] == 7 || ($t_val['teacher_type'] == 3 && $t_val["teacher_money_type"] == 0)) {
            //         $all_all_money += $t_val['money'];
            //     } else {
            //         $all_not_money += $t_val['money'];
            //     }
            // }
            // $all_money_tax = $all_money*0.98;
            // echo  $item.'月 '.$all_money.' '.$all_all_money.' '.$all_not_money.' '.$all_money_tax.PHP_EOL;

            // $info= $teacher_money->get_teacher_salary($t_val['teacherid'],$start_time,$end_time);
            // $lesson_money;   总工资
            // $info['lesson_total'];    总课时
            // $info['lesson_trial_total']; 试听总课时
            // $info['teacher_lesson_price']; 老师课时总收入


            foreach($info as $val) {
                //echo 'teacherid : '.$val['teacherid'];
                $teacher= $teacher_money->get_teacher_salary(50274,$start_time,$end_time);
                dd($teacher);
                $lesson_count = $teacher['lesson_total'];
                $money = $teacher['teacher_lesson_price'];
                $price = $teacher['lesson_price_tax'];
                if ($price == 0) continue;
                // $lesson_money;   总工资
                // $info['lesson_total'];    总课时
                // $info['lesson_trial_total']; 试听总课时
                // $info['teacher_lesson_price']; 老师课时总收入

                // $teacherid = $val['teacherid'];
                // $init_start_date = date("Y-m-01",  $val['add_time']);
                // $init_end_date   = date("Y-m-d",  strtotime(date("Y-m-01",  ($val['add_time']+86400*32)     ))-86400 );
                // $start_time2 = strtotime($init_start_date);
                // $end_time2 = strtotime($init_end_date);
                // $last_month_info = $task->t_lesson_info->get_teacher_last_month_lesson_count($teacherid,$start_time,$end_time);
                // $teacher_honor            = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,1);
                // $teacher_trial            = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,2);
                // $teacher_compensate       = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,3);
                // $teacher_compensate_price = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,4);
                // $teacher_reference        = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,6);
                // $teacher_train            = $task->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,5);
                // $redward = $teacher_honor + $teacher_trial + $teacher_compensate + $teacher_compensate_price + $teacher_reference + $teacher_train;
                
                //$val['money']   /= 100;
                // $money = $val['money'] - ($redward / 100);
                // $lesson_count = $last_month_info / 100;
                echo $item.'月/'.$val['teacherid'].'/'.$price.'/'.$lesson_count.'/'.$money.'/'.trim($val['realname']).PHP_EOL;
                if ($val['teacher_money_type'] == 7 || ($val['teacher_type'] == 3 && $val["teacher_money_type"] == 0)) {
                    $all_all_money += $price;
                } else {
                    $all_not_money += $price;
                }
                $all_money += $price;
            }
            $all_money_tax = $all_money*0.98;
            echo  $item.'月 '.$all_money.' '.$all_all_money.' '.$all_not_money.' '.$all_money_tax.PHP_EOL;
            sleep(2);
        }
        // 拉取数据(6月至11月的老师工资)

        //$start_time = strtotime('')
        exit;

        $user = $task->t_teacher_lecture_appointment_info_b2->get_manager_info();
        $start_time = strtotime('2017-11-1');
        $end_time = strtotime("2017-12-1");
        $info = $task->t_teacher_lecture_appointment_info_b2->get_info_for_cc($start_time, $end_time);
        $users = '';
        foreach($info as $item) {
            if (isset($users[$item['uid']]['tag'])) {
                if ($item['status']) {
                    $users[$item['uid']]['tag'] += 1;
                }
            } else {
                $users[$item['uid']]['tag'] = 0;
            }
            if (isset($users[$item['uid']]['order'])) {
                if ($item['orderid']) {
                    $users[$item['uid']]['order'] += 1;
                }
            } else {
                $users[$item['uid']]['order'] = 0;
            }
        }
        foreach($users as $key => $item) {
            if (!isset($user[$key])) continue;
            echo $user[$key]['name'].' '.$item['tag'].' '.$item['order'].','.PHP_EOL;
       }
        //dd($users);
        exit;
        $info = $task->t_teacher_lecture_appointment_info_b2->get_name_for_tea_name();
        foreach($info as $item){
            echo $item['tname'].'   '.$item['name'].',';
        }
        exit;
        $add_time = time();
        $month = date('m') - 1;
        $begin = date("Y-$month-01 00:00:00");
        $start_time = strtotime($begin);
        $end = date('Y-m-d', strtotime("$begin +1 month -1 day"));
        $end_time = strtotime($end);
        $add_time = time();

        // $info = $this->array_init();
        // $tea_list = $task->t_teacher_flow->get_tea_list_for_subject($start_time, $end_time);
        // foreach($tea_list as $item) {
        //     if ($item['subject'] == 1 && $item['grade'] == 100) {
        //         $info[0] = $item;
        //     }
        //     if ($item['subject'] == 1 && $item[''])
        // }

            // 面试通过人数
            $tea_list = $task->t_teacher_flow->get_tea_list($start_time, $end_time);
            $primary_china = $this->recruit_init([], 1, 100);
            $middle_china = $this->recruit_init([], 1, 200);
            $high_china = $this->recruit_init([], 1, 300);
            $primary_math = $this->recruit_init([], 2, 100);
            $middle_math = $this->recruit_init([], 2, 200);
            $high_math = $this->recruit_init([], 2, 300);
            $primary_eng = $this->recruit_init([], 3, 100);
            $middle_eng = $this->recruit_init([], 3, 200);
            $high_eng = $this->recruit_init([], 3, 300);
            $chemistry = $this->recruit_init([], 4);
            $physics = $this->recruit_init([], 5);
            $biology = $this->recruit_init([], 6);
            $science = $this->recruit_init([], 10);

            // 老师身份
            $identity_no_set = $this->recruit_init([], '', '', 0);
            $identity_organ = $this->recruit_init([], '', '', 5);
            $identity_public = $this->recruit_init([], '', '', 6);
            $identity_stu = $this->recruit_init([], '', '', 7);
            $identity_other = $this->recruit_init([], '', '', 8);
            foreach($tea_list as $item) {
                // 语文
                if ($item['subject'] == 1) {
                    if ($item['grade'] == 100) {
                        $primary_china = $this->accumulation($primary_china, $item);
                    }
                    if ($item['grade'] == 200) {
                        $middle_china = $this->accumulation($middle_china, $item);
                    }
                    if ($item['grade'] == 300) {
                        $high_china = $this->accumulation($high_china, $item);
                    }
                }
                if ($item['subject'] == 2) {
                    if ($item['grade'] == 100) {
                        $primary_math = $this->accumulation($primary_math, $item);
                    }
                    if ($item['grade'] == 200) {
                        $middle_math = $this->accumulation($middle_math, $item);
                    }
                    if ($item['grade'] == 300) {
                        $high_math = $this->accumulation($high_math, $item);
                    }
                }
                if ($item['subject'] == 3) {
                    if ($item['grade'] == 100) {
                        $primary_eng = $this->accumulation($primary_eng, $item);
                    }
                    if ($item['grade'] == 200) {
                        $middle_eng = $this->accumulation($middle_eng, $item);
                    }
                    if ($item['grade'] == 300) {
                        $high_eng = $this->accumulation($high_eng, $item);
                    }
                }
                if ($item['subject'] == 4) {
                    $chemistry = $this->accumulation($chemistry, $item);
                }
                if ($item['subject'] == 5) {
                    $physics = $this->accumulation($physics, $item);
                }
                if ($item['subject'] == 6) {
                    $biology = $this->accumulation($biology, $item);
                }
                if ($item['subject'] == 10) {
                    $science = $this->accumulation($science, $item);
                }
                if ($item['identity'] == 0) {
                    $identity_no_set = $this->accumulation($identity_no_set, $item);
                }
                if ($item['identity'] == 5) {
                    $identity_organ = $this->accumulation($identity_organ, $item);
                }
                if ($item["identity"] == 6) {
                    $identity_public = $this->accumulation($identity_public, $item);
                }
                if ($item['identity'] == 7) {
                    $identity_other = $this->accumulation($identity_other, $item);
                }
                if ($item['identity'] == 8) {
                    $identity_stu = $this->accumulation($identity_stu, $item);
                }
            }
            $ret_info = [];
            array_push($ret_info, $primary_china);
            array_push($ret_info, $middle_china);
            array_push($ret_info, $high_china);
            array_push($ret_info, $primary_math);
            array_push($ret_info, $middle_math);
            array_push($ret_info, $high_math);
            array_push($ret_info, $primary_eng);
            array_push($ret_info, $middle_eng);
            array_push($ret_info, $high_eng);
            array_push($ret_info, $chemistry);
            array_push($ret_info, $physics);
            array_push($ret_info, $biology);
            array_push($ret_info, $science);
            $total['sum'] = 0;
            $total['imit_sum'] = 0;
            $total['attend_sum'] = 0;
            $total['adopt_sum'] = 0;
            $total['train_tea_sum'] = 0;
            $total['train_qual_sum'] = 0;
            foreach($ret_info as $key => &$item) {
                $total['train_tea_sum'] += $item['train_tea_sum'];
                $total['train_qual_sum'] += $item['train_qual_sum'];
                $total['sum'] += $item['sum'];
                $total['imit_sum'] += $item['imit_sum'];
                $total['attend_sum'] += $item['attend_sum'];
                $total['adopt_sum'] += $item['adopt_sum'];
                $task->t_new_tea_entry->row_insert([
                    'subject' => $item['subject'],
                    'grade' => $item['grade'],
                    'interview_pass_num' => $item['sum'],
                    'train_attend_new_tea_num' => $item['sum'],
                    'train_qual_new_tea_num' => $item['sum'],
                    'imit_listen_sched_lesson_num' => $item['sum'],
                    'imit_listen_attend_lesson_num' => $item['sum'],
                    'imit_listen_pass_lesson_num' => $item['sum'],
                    'add_time' => $add_time
                ]);
            }
            // 添加总计
            $task->t_new_tea_entry->row_insert([
                'subject' => '-2',
                'interview_pass_num' => $total['sum'],
                'train_attend_new_tea_num' => $total['train_tea_sum'],
                'train_qual_new_tea_num' => $total['train_qual_sum'],
                'imit_listen_sched_lesson_num' => $total['imit_sum'],
                'imit_listen_attend_lesson_num' => $total['attend_sum'],
                'imit_listen_pass_lesson_num' => $total['adopt_sum'],
                'add_time' => $add_time
            ]);

            $type_total['sum'] = 0;
            $type_total['train_tea_sum'] = 0;
            $type_total['train_qual_sum'] = 0;
            $type_total['imit_sum'] = 0;
            $type_total['attend_sum'] = 0;
            $type_total['adopt_sum'] = 0;
            $type_ret_info = [];
            array_push($type_ret_info, $identity_no_set);
            array_push($type_ret_info, $identity_organ);
            array_push($type_ret_info, $identity_public);
            array_push($type_ret_info, $identity_stu);
            array_push($type_ret_info, $identity_other);
            foreach($type_ret_info as $key => &$item) {
                $type_total['train_tea_sum'] += $item['train_tea_sum'];
                $type_total['train_qual_sum'] += $item['train_qual_sum'];
                $type_total['sum'] += $item['sum'];
                $type_total['imit_sum'] += $item['imit_sum'];
                $type_total['attend_sum'] += $item['attend_sum'];
                $type_total['adopt_sum'] += $item['adopt_sum'];
                $task->t_new_tea_entry->row_insert([
                    'identity' => $item['identity'],
                    'interview_pass_num' => $item['sum'],
                    'train_attend_new_tea_num' => $item['sum'],
                    'train_qual_new_tea_num' => $item['sum'],
                    'imit_listen_sched_lesson_num' => $item['sum'],
                    'imit_listen_attend_lesson_num' => $item['sum'],
                    'imit_listen_pass_lesson_num' => $item['sum'],
                    'add_time' => $add_time
                ]);
            }
            // 添加总计
            $task->t_new_tea_entry->row_insert([
                'identity' => '-2',
                'interview_pass_num' => $type_total['sum'],
                'train_attend_new_tea_num' => $type_total['train_tea_sum'],
                'train_qual_new_tea_num' => $type_total['train_qual_sum'],
                'imit_listen_sched_lesson_num' => $type_total['imit_sum'],
                'imit_listen_attend_lesson_num' => $type_total['attend_sum'],
                'imit_listen_pass_lesson_num' => $type_total['adopt_sum'],
                'add_time' => $add_time
            ]);
    }

    public function recruit_init($info, $subject = '', $grade = '', $identity = '') {
        $info['sum'] = $info['train_tea_sum'] = $info['train_qual_sum'] = $info['imit_sum'] = $info['attend_sum'] = $info['adopt_sum'] = 0;
        if ($subject <= 3)
        {
            $info['subject'] = $subject;
            $info['grade'] = $grade;
        } else {
            $info['grade'] = '';
            $info['subject'] = $subject;
        }
        if ($identity || $identity == 0) {
            $info['identity'] = $identity;
        }
        return $info;
    }

    public function accumulation($info, $item) {
        $info['sum'] ++;
        // 新师参训
        $train_tea_sum = $task->t_teacher_info->get_train_inter_teacher_count($item['trial_lecture_pass_time'], $item['teacherid']);
        if ($train_tea_sum) $info['train_tea_sum'] ++;
        if ($item['train_through_new_time']) $info['train_qual_sum'] ++;

        // 模拟试听总排课人数
        $imit_sum = $task->t_lesson_info->get_imit_audi_sched_count($item['trial_lecture_pass_time'], $item['teacherid']);
        if ($imit_sum) {
            $info['imit_sum']++; $info['attend_sum']++;
        }
        //$attend_sum = $task->t_lesson_info->get_attend_lesson_count($item['trial_lecture_pass_time'], $item['teacherid']);
        //if ($attend_sum) $info['attend_sum']++;
        if ($item['simul_test_lesson_pass_time'] && $item['simul_test_lesson_pass_time'] > $item['train_through_new_time']) $info['adopt_sum']++;
        return $info;
    }

    public function array_init(){
        $info = [['subject' => 1, 'grade' => 100],['subject' => 1, 'grade' => 200],['subject' => 3, 'grade' => 300],[
        'subject'=>2,'grade'=>100],['subject'=>2,'grade'=>200],['subject'=>3,'grade'=>300],['subject'=>4],['subject'=>5],['subject'=>6],['subject'=>10]];
        foreach ($info as $key => $item) {
            $info[$key]['sum'] = 0;
            $info[$key]['train_tea_sum'] = 0;
            $info[$key]['train_qual_sum'] = 0;
            $info[$key]['imit_sum'] = 0;
            $info[$key]['attend_sum'] = 0;
            $info[$key]['adopt_sum'] = 0;
        }
        return $info;
    }
}
