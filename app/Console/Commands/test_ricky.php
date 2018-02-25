<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class test_ricky extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_ricky';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉取数据';

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
        $task = new \App\Console\Tasks\TaskController();
        $money = new \App\Http\Controllers\teacher_money();

        // 老师ID、老师姓名、12月份授课课时数
        $rules1 = [[16, 17, 18, 20, 28], [26, 30, 36, 39, 46], [34, 38, 44, 49, 54], [38, 40, 48, 50, 58], [41, 43, 51, 53, 61]];
        $rules2 = [[18, 22, 28, 32, 38], [26, 28, 36, 39, 46], [30, 33, 40, 43, 50], [36, 38, 46, 48, 55], [38, 40, 48, 50, 58], [41, 43, 51, 53, 61]];
        $rules3 = [[24, 27, 30, 33], [31, 34, 37, 40], [38, 41, 44, 47], [45, 48, 51, 54]];
        // 2-25新规则
        $rules4 = [[18, 19, 20, 22, 26], [28, 29, 32, 34, 40], [34, 36, 40, 44, 52], [42, 44, 49, 53, 63], [45, 47, 52, 57, 67]];
        // 查武汉全职老师 select teacherid,realname from t_teacher_info where teacher_money_type = 7 and is_test_user=0;
        $info = $task->t_teacher_info->get_info_for_money_type();
        $tea = [];
        $month = [12, 1];
        foreach($month as $v) {
            if ($v == 12) { // 处理12月
                $start_time = strtotime("2017-12-1");
                $end_time = strtotime("2018-1-1");

            } else {
                $start_time = strtotime("2018-1-1");
                $end_time = strtotime("2018-2-1");
            }
            foreach($info as $item) {
                $teacherid = $item['teacherid'];
                $tea[$teacherid]["nick"] = $item["realname"];
                $data = $task->t_lesson_info_b3->get_lesson_list_by_teacherid($teacherid, $start_time, $end_time);
                $count_101 = 0; // 101 -105
                $count_106 = 0; // 106, 201 202
                $count_203 = 0; // 203
                $count_301 = 0; // 301 302
                $count_303 = 0; // 303
                $total_count = 0; // 总课时
                $money3 = 0;
                foreach($data as $val) {
                    //$lesson_count = floor(($val["lesson_end"] - $val["lesson_start"]) % 86400 / 60);

                    //echo "时长".$lesson_count;
                    //$count = $lesson_count / 40;
                    $count = $val["lesson_count"] / 100;

                    $type = $task->t_teacher_money_type->get_type_for_money($val["teacher_money_type"], $val["grade"], $val["level"]);
                    $reward = $money->get_lesson_reward_money(
                        0, $val['already_lesson_count'], $val['teacher_money_type'], $val['teacher_type'], $type
                    );
                    $total_count += $count;
                    $coef3 = $rules3[$val["level"]];
                    //　处理奥数
                    if ($val['competition_flag'] == 1) {
                        if (intval($val["grade"]) <= 106) {
                            $money3 += $count * ($coef3[2] + $reward);
                        } else {
                            $money3 += $count * ($coef3[3] + $reward);
                        }
                    }

                    if (intval($val["grade"]) >= 101 && intval($val["grade"]) <= 105) {
                        $count_101 += $count;
                        if ($val["competition_flag"] != 1) $money3 += $count * ($coef3[0] + $reward);
                    } elseif (intval($val["grade"]) >= 106 && intval($val["grade"]) <= 202) {
                        $count_106 += $count;
                        if ($val["competition_flag"] != 1) $money3 += $count * ($coef3[1] + $reward);
                    } elseif (intval($val["grade"]) == 203) {
                        $count_203 += $count;
                        if ($val["competition_flag"] != 1) $money3 += $count * ($coef3[2] + $reward);
                    } elseif ($val["grade"] >= 301 && $val["grade"] <= 302) {
                        $count_301 += $count;
                        if ($val["competition_flag"] != 1) $money3 += $count * ($coef3[2] + $reward);
                    } else {
                        $count_303 += $count;
                        if ($val["competition_flag"] != 1) {
                            $money3 += $count * ($coef3[3] + $reward);
                        }
                        //echo date("Y-m-d H:i:s", $val["lesson_start"])."课时: ".$count."课时基价: ".$coef3[3]."课时奖金: ".$reward." level: ".$val["level"].$val["grade"].PHP_EOL;
                    }
                }

                //$tea[$teacherid]["total_count_".$v] = $total_count."($count_101,$count_106,$count_203,$count_301,$count_303)";
                $tea[$teacherid]["total_count_".$v] = $total_count;
                $tea[$teacherid]["count_101_".$v] = $count_101;
                $tea[$teacherid]["count_106_".$v] = $count_106;
                $tea[$teacherid]["count_203_".$v] = $count_203;
                $tea[$teacherid]["count_301_".$v] = $count_301;
                $tea[$teacherid]["count_303_".$v] = $count_303;
                //echo "总课时数".$total_count;
                //echo $item['level'];
                //$coef3 = $rules3[$item["level"]];
                //$money3 = $count_101 * $coef3[0] + $count_106 * $coef3[1] + ($count_203 + $count_301) * $coef3[2] + $count_303 * $coef3[3];
                // 处理年级课时数
                if ($total_count <= 30) {
                    $money1 = 0;
                    $money2 = 0;
                    $money4 = 0;
                } elseif ($total_count >= 31 && $total_count <= 60) {
                    $coef1 = $rules1[0];
                    $coef2 = $rules2[0];
                    $coef4 = $rules4[0];
                    //var_dump($rules1[0]);
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                    $money2 = $count_101 * $coef2[0] + $count_106 * $coef2[1] + $count_203 * $coef2[2] + $count_301 * $coef2[3] + $count_303 * $coef2[4];
                    $money4 = $count_101 * $coef4[0] + $count_106 * $coef4[1] + $count_203 * $coef4[2] + $count_301 * $coef4[3] + $count_303 * $coef4[4];
                } elseif ($total_count >= 61 && $total_count <= 120) {
                    $coef1 = $rules1[1];
                    //var_dump($rules1[1]);
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                    if ($total_count <= 90) {
                        $coef2 = $rules2[1];
                    } else {
                        $coef2 = $rules2[2];
                    }
                    $coef4 = $rules4[1];
                    $money2 = $count_101 * $coef2[0] + $count_106 * $coef2[1] + $count_203 * $coef2[2] + $count_301 * $coef2[3] + $count_303 * $coef2[4];
                    $money4 = $count_101 * $coef4[0] + $count_106 * $coef4[1] + $count_203 * $coef4[2] + $count_301 * $coef4[3] + $count_303 * $coef4[4];
                } elseif ($total_count >= 121 && $total_count <= 150) {
                    $coef1 = $rules1[2];
                    $coef2 = $rules2[3];
                    //var_dump($rules1[2]);
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                    $money2 = $count_101 * $coef2[0] + $count_106 * $coef2[1] + $count_203 * $coef2[2] + $count_301 * $coef2[3] + $count_303 * $coef2[4];
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                } elseif ($total_count >= 151 && $total_count <= 195) {
                    $coef1 = $rules1[3];
                    $coef2 = $rules2[4];
                    $coef4 = $rules4[3];
                    //var_dump($rules1[3]);
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                    $money2 = $count_101 * $coef2[0] + $count_106 * $coef2[1] + $count_203 * $coef2[2] + $count_301 * $coef2[3] + $count_303 * $coef2[4];
                    $money4 = $count_101 * $coef4[0] + $count_106 * $coef4[1] + $count_203 * $coef4[2] + $count_301 * $coef4[3] + $count_303 * $coef4[4];
                } else {
                    $coef1 = $rules1[4];
                    $coef2 = $rules2[5];
                    $coef4 = $rules4[4];
                    //var_dump($rules1[4]);
                    $money1 = $count_101 * $coef1[0] + $count_106 * $coef1[1] + $count_203 * $coef1[2] + $count_301 * $coef1[3] + $count_303 * $coef1[4];
                    $money2 = $count_101 * $coef2[0] + $count_106 * $coef2[1] + $count_203 * $coef2[2] + $count_301 * $coef2[3] + $count_303 * $coef2[4];
                    $money4 = $count_101 * $coef4[0] + $count_106 * $coef4[1] + $count_203 * $coef4[2] + $count_301 * $coef4[3] + $count_303 * $coef4[4];
                }
                $tea[$teacherid]['money_'.$v] = $money1;
                $tea[$teacherid]['money_minny_'.$v] = $money2;
                $tea[$teacherid]["money_sal_".$v] = $money3;
                $tea[$teacherid]['money_neww_'.$v] = $money4;
            }
        }
        foreach($tea as $key => $t) {
            echo $key." ";
            if ($tea[$key]["nick"]) {
                echo $tea[$key]["nick"]." ";
            } else {
                echo $task->cache_get_teacher_nick($key).' ';
            }
            echo $tea[$key]["total_count_12"]." ".$tea[$key]["count_101_12"]." ".$tea[$key]["count_106_12"]." ".$tea[$key]["count_203_12"]." ";
            echo $tea[$key]["count_301_12"]." ".$tea[$key]["count_303_12"]." ".$tea[$key]["total_count_1"]." ".$tea[$key]["count_101_1"]." ";
            echo $tea[$key]["count_106_1"]." ".$tea[$key]["count_203_1"]." ".$tea[$key]["count_301_1"]." ".$tea[$key]["count_303_1"].' ';
            echo $tea[$key]["money_sal_12"]." ".$tea[$key]["money_sal_1"]." ".$tea[$key]["money_12"]." ".$tea[$key]["money_1"]." ";
            echo $tea[$key]["money_minny_12"]." ".$tea[$key]["money_minny_1"]." ".$tea[$key]["money_neww_12"]." ".$tea["money_neww_1"].PHP_EOL;
        }

        //dd($info);
        exit;

        $start_time = strtotime("2017-7-1");
        $reference = "18831899877";
        $identities = [0,5,6,7,8];
        foreach ($identities as $identity) {
            echo E\Eidentity::get_desc($identity).PHP_EOL;
            $info = $task->t_teacher_lecture_appointment_info_b2->get_money_list2($start_time, $reference, $identity);
            foreach($info as $item) {
                echo $item["teacherid"].",";
                echo $task->cache_get_teacher_nick($item["teacherid"]).",";
                echo date("Y-m-d H:i:s", $item["train_through_new_time"]).PHP_EOL;
            }
        }

        exit;

        // 试听课标准化讲义使用次数 科目、年级、文件名、教研员、浏览次数、使用次数
        $info = $task->t_resource->get_list_for_subject();
        foreach($info as $item) {
            echo $item["file_title"].",";
            echo E\Esubject::get_desc($item["subject"]).",";
            echo E\Egrade::get_desc($item["grade"]).",";
            echo $task->cache_get_account_nick($item["adminid"]).",";
            echo $item["visit_num"].",".$item["use_num"].PHP_EOL;
        }
        exit;

        //90分钟 --- 排课时间、课程ID、老师姓名、学生姓名、上课时间、助教姓名、学生合同创建时间（第一份合同）
        // 常规课表
        $info = $task->t_week_regular_course->get_all_info();
        // 寒假课表
        //$info = $task->t_winter_week_regular_course->get_all_info();
        foreach($info as $item) {
            $teacherid = $item["teacherid"];
            $userid = $item["userid"];
            $start_time = explode("-", $item["start_time"]);
            $date = $start_time[0];
            if ($date <= 3) {
                $stime = strtotime("2018-1-".(28 + $date)." ".$start_time[1]);
                $etime = strtotime("2018-1-".(28 + $date)." ".$item["end_time"]);
            } else {
                $stime = strtotime("2018-2-".($date - 3)." ".$start_time[1]);
                $etime = strtotime("2018-2-".($date - 3)." ".$item["end_time"]);
            }

            $count = floor(($etime-$stime)%86400/60);//($end_time - $stime) * 60 / 100ear

            if ($count >= 80 && $count <= 100) {
                // if ($date <= 3) $start_time = strtotime("2018-1-".(28 + $date)." ".$start_time[1]);
                // else $start_time = strtotime("2018-2-".($date - 3)." ".$start_time[1]);
                $lesson = $task->t_week_regular_course->get_info_for_start_time($teacherid, $userid, $stime);
                if ($lesson) {
                    echo $item["end_time"]." ".$start_time[1]." $count".",";
                    echo date("Y-m-d H:i:s", $lesson["operate_time"]).",";
                    echo $lesson["lessonid"].",";
                    echo $task->cache_get_teacher_nick($teacherid).",";
                    echo $task->cache_get_student_nick($userid).",";
                    echo date("Y-m-d H:i:s", $lesson["lesson_start"]).",";
                    echo ($lesson["lesson_count"] / 100)."课时".",";
                    echo $task->cache_get_assistant_nick($lesson["assistantid"]).",";
                    $order = $task->t_teacher_feedback_list->get_order_list($userid);
                    echo date("Y-m-d H:i:s", $order).PHP_EOL;
                }
            }
        }
        exit;

        //助教、组别、学生ID、学生姓名、第一次合同创建时间、科目、科目更换老师次数、未消耗课时、学员类型
        $info = $task->t_student_info->get_list_count_left();
        $group = $task->t_admin_group_name->get_ass_group_name(E\Emain_type::V_1);
        foreach ($info as $item) {
            $userid = $item["userid"];
            $list = $task->t_course_order->get_list($userid);
            $lesson_count = 0;
            foreach($list as $val) {
                $lesson_count += $val["no_finish_lesson_count"]/100;
            }
            if (!$lesson_count) continue;
            $aid = $item["assistantid"];
            echo $task->cache_get_assistant_nick($aid).",";
            $groud_id = $task->t_admin_group->get_group_id_by_aid2($aid);
            if (isset($group[$groud_id])) {
                $group_name = $group[$groud_id]["group_name"];
            } else {
                $group_name = $task->t_admin_group_name->get_group_name_by_groupid($groud_id);
            }
            echo $group_name.",";
            $userid = $item["userid"];
            echo $userid.",";
            echo $item["nick"].",";
            $order = $task->t_teacher_feedback_list->get_order_list($userid);
            echo date("Y-m-d H:i:s", $order).",";
            $subject = $task->t_student_info->get_list_subject($userid);
            $subj = "";
            foreach ($subject as $val) {
                $subject = $val["subject"];
                $count = $task->t_student_info->get_teacher_count($userid, $subject);
                $subj .= E\Esubject::get_desc($val["subject"])."(".$count.")-";
            }
            if ($subj) {
                $subj = substr($subj,0,-1);
            }
            echo $subj.",";
            //$count = $task->t_student_info->get_teacher_count($userid);
            //echo $count.",";
            echo $lesson_count.",";
            echo E\Estudent_type::get_desc($item["type"]).PHP_EOL;
        }

        // 拉取90分钟补偿数据
        // $month = [8,9,10,11,12,1];
        // foreach ($month as $item) {
        //     $start_time = strtotime('2017-'.$item.'-1');
        //     if ($item == 12) {
        //         $end_time = strtotime('2018-1-1');
        //     } else {
        //         $end_time = strtotime('2017-'.($item + 1).'-1');
        //     }
        //     if ($item == 1) {
        //         $start_time = strtotime("2018-1-1");
        //         $end_time = strtotime("2018-2-1");
        //     }
        //     echo $item."月".PHP_EOL;
        //     $info = $task->t_teacher_feedback_list->get_90_list($start_time, $end_time);
        //     foreach($info as $val) {
        //         if (!($val["teacherid"] && $val["lessonid"])) continue;
        //         echo $task->cache_get_teacher_nick($val["teacherid"]).",";
                
        //         $userid = $val["userid"];
        //         echo $task->cache_get_student_nick($userid).",";
        //         echo $val["lessonid"].",";
        //         echo $task->cache_get_assistant_nick($val["assistantid"]).",";
        //         echo date("Y-m-d H:i:s", $val["lesson_start"]).",";
        //         $order = $task->t_teacher_feedback_list->get_order_list($userid);
        //         echo date("Y-m-d H:i:s", $order).PHP_EOL;
        //     }
        // }

        

        // $month = [11, 12];
        // foreach ($month as $item) {
        //     echo $item.'月';
            // $start_time = strtotime('2017-'.$item.'-1');
            // if ($item == 12) {
            //     $end_time = strtotime('2018-1-1');
            // } else {
            //     $end_time = strtotime('2017-'.($item + 1).'-1');
            // }
        //     $call_count = $task->t_tq_call_info->get_count_called_phone($start_time, $end_time);
        //     echo '当前拨打总数'.$call_count;
        //     $stu_count = $task->t_tq_call_info->get_count_stu($start_time, $end_time);
        //     echo '当前例子总数'.$stu_count;
        //     echo '首次未接通但是被拨打N次后接通的平均拨打次数'.($call_count / $stu_count);
        // }

        // 拉取2017年下单学员的预警数据
        // $start_time = strtotime("2017-1-1");
        // $end_time = strtotime("2018-1-1");
        // $info = $task->t_revisit_info->get_all_info($start_time, $end_time);
        // $stu = $task->t_student_info->get_test_user();
        // $stus = [];
        // foreach($stu as $val) {
        //     array_push($stus, $val["userid"]);
        // }
        // foreach($info as $item) {
        //     if (in_array($item["userid"], $stus)) continue;
        //     echo $item["userid"]." ".E\Eis_warning_flag::get_desc($item["is_warning_flag"]).PHP_EOL;
        // }
        // //$user = exec("who | cut -d' ' -f1");
        // // $filename = "/tmp/userid.log";
        // $info = file_get_contents($filename);
        // $info = explode("\n", $info);
        // foreach ($info as $key => $item) {
        //     if ($key % 1000 == 0) sleep(5);
        //     $userid = str_replace(',', '', $item);
        //     $count = $task->t_lesson_info_b3->get_subject_count($userid);
        //     echo $userid." ".$count.PHP_EOL;
        // }
        //$info = implode(" ", $info);
        //dd(trim($info));
    }


}
