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
        $tea = new \App\Http\Controllers\TeaPower();
        $tea->add_reference_price(1,1);
        exit;
        $task = new \App\Console\Tasks\TaskController();

        // 加载老师绑定数据
        $file = '/tmp/bank.txt';
        $str = file_get_contents($file);
        $info = explode("\n",$str);
        echo '正在添加数据,请稍等 ...'.PHP_EOL;
        foreach($info as $key=>$item) {
            if ($item) {
                $val = explode("\t",$item);
                $teacherid = $val[0];
                $bank = $task->t_teacher_info->get_bank_for_teacherid($teacherid);
                var_dump($bank);
                if (!(isset($bank['bankcard']) && $bank['bankcard'])) {
                    $where_arr['bankcard'] = $val[3];
                    if (!(isset($bank['bank_phone']) && $bank['bank_phone'])) {
                        $where_arr['bank_phone'] = $val[1];
                    }
                    if (!(isset($bank['bank_account']) && $bank['bank_account'])) {
                        $where_arr['bank_account'] = $val[2];
                    }
                    if (!(isset($bank['bank_type']) && $bank['bank_type'])) {
                        $where_arr['bank_type'] = $val[4];
                    }
                    if (!(isset($bank['bank_province']) && $bank['bank_province'])) {
                        $where_arr['bank_province'] = $val[5];
                    }
                    if (!(isset($bank['bank_city']) && $bank['bank_city'])) {
                        $where_arr['bank_city'] = $val[6];
                    }
                    if (!(isset($bank['bank_address']) && $bank['bank_address'])) {
                        $where_arr['bank_address'] = $val[7];
                    }
                    if (!(isset($bank['idcard']) && $bank['idcard'])) {
                        $where_arr['idcard'] = $val[8];
                    }
                    $ret = $task->t_teacher_info->field_update_list($teacherid,$where_arr);
                    if (!$ret) echo '更新失败';
                }
                echo "添加完成 current id : ".$teacherid." 添加 $key 条 ".PHP_EOL;
            }
        }
        exit("添加数据完成 ...");

        // 拉取数据 (用于拉取学生老师版本)
        $start_time = strtotime("2017-8-1");
        $end_time = strtotime('2017-11-1');
        $teacher = $task->t_teacher_lecture_appointment_info_b2->get_teacher_list($start_time, $end_time);
        foreach($teacher as $item) {
            if (stripos($item['user_agent'],'ipad')) continue;
            if (stripos($item['user_agent'],'mac') ) {
                $version = json_decode($item['user_agent'], true);
                if (isset($version['device_model'])) {
                    if($version['version'] < 4.3) {
                        echo $item['teacherid'].' '.$item['realname'].' mac '.$version['version'].','.PHP_EOL;
                    }
                }
            }
            elseif(stripos($item['user_agent'],"windows") ) {
                    $version = json_decode($item['user_agent'], true);
                    if (isset($version['device_model'])) {
                        if($version['version'] < 4.3) {
                            echo $item['teacherid'].' '.$item['realname'].' windows '.$version['version'].','.PHP_EOL;
                            }
                    }
                }
            else {
                $version = json_decode($item['user_agent'], true);
                if (isset($version['device_model'])) {
                    if($version['version'] < 5.3) {
                        echo $item['teacherid'].' '.$item['realname'].' android '.$version['version'].','.PHP_EOL;
                    }
                }

            }
        }
        echo PHP_EOL.'=================学生================='.PHP_EOL;
        $student = $task->t_teacher_lecture_appointment_info_b2->get_student_list($start_time, $end_time);
        $assistant = $task->t_teacher_lecture_appointment_info_b2->get_assistant_info();
        foreach($student as $item) {
            if (stripos($item['user_agent'],'ipad')) continue;
            $nick = '';
            $id = $item['assistantid'];
            if ($id && isset($assistant[$id])) {
                $nick = $assistant[$id]['nick'];
            }
            if (stripos($item['user_agent'],'mac') ) {
                $version = json_decode($item['user_agent'], true);
                if (isset($version['device_model'])) {
                    if($version['version'] < 4.3) {
                        echo $item['userid'].' '.$item['realname'].' '.$item['nick'].' '.$nick.' mac '.$version['version'].','.PHP_EOL;
                    }
                }
            }  elseif(stripos($item['user_agent'],"windows") ) {
                $version = json_decode($item['user_agent'], true);
                if (isset($version['device_model'])) {
                    if($version['version'] < 4.3) {
                        echo $item['userid'].' '.$item['realname'].' '.$item['nick'].' '.$nick.' windows '.$version['version'].','.PHP_EOL;
                    }
                }
            } else {
                $version = json_decode($item['user_agent'], true);
                if (isset($version['device_model'])) {
                    if($version['version'] < 5.3) {
                        echo $item['userid'].' '.$item['realname'].' '.$item['nick'].' '.$nick.' android '.$version['version'].','.PHP_EOL;
                    }
                }

            }
        }
        exit;

        $tname = ['王永兴','汪煜婷','杨哲','韩雪','张健','刘建敏','石铁军','冯雨丽','李烨','王波','王洪权','仲卫懿','钟淑芬','陈雨蒙','李娟','朱秀秀','丛润泽','刘佳','战琼','吴姗姗','王晓华','张金强','张萌萌','向文欣','邓细红','周睿珊','苏保卫','王帅蓉','陈雪冬','任守荣','喻冰雪','吕瑞肇','辛老师','陈淑芳','马海军','曲学轩','罗召鹏','李浩博','韩梦馨','熊孟秋','白晓雯','余泳','陈梦莹','刘玮','王鸿雨','杨凯伟','周春梅','樊诗慧','刘惠玲','梁文香','宋洁','李静','郭蕾','杨连臣','彭章裕','岳文忠','秦天阳','马宇杰','张育贵','李锦胜','王莹','代雅丽','郭荣艳','刘珊','付昱杰','赵文彩','吴勇贫','余洁','侯翠玲','齐恩薇','戢茜','谢新生','姜波','樊超','聂显山','马刚','姚晓妮','尹金萍','梁金凤','汪洋','林守庄','潘俊英','吕锋','鲍禾铮','邱丽红','梁青峰','李红英','唐湘璐','倪老师','张冬晨','文美玉','付小谧','刘国权','陈国腾','张也','徐胜','陈聪慧','黄冬梅','宋雅洁','杨爽','郭彦均','王丹青','李君君','游志坪','董海丽','娄小瑀','唐婷婷','程扶一','殷涛','邹洁','贺毅龙','孙长启','李东耀','邹寒','闫金香','沈铭洋','李雁鸣','王崇辉','田楚楚','焦佳','林双','刘静静','胡雨芳','陈晓晴','赵敏','何丽丽','何映烁','宋文怡','甄宏伟','黄蓉','张璐','郝滢','张金梁','金铃','周心怡','王呼雷','齐雨欣','李碧芳','姜芳','王娟','宋宋','阮淑怡','叶京茹','胡益洲','杨彤','阚鸿瑶','谢珏','刘瑶瑶','刘佳文','张晓帆','刘婉旭','陈静','宋迪','郭晓琳','段双双','李长城','宋佳丽','王霞','钟圆圆','田小恬','沈云云','张和','许一然','孙丹','吴明','杜飞','李阿芳','顾浩','付娟','王瑛','赵紫薇','谢晓耘','黄梦颖','秦雪如','张明华','李小雪','庞梅芳','李红霞','田红贤','焦丽娜','高芷若','耿海玉','袁俊龙','李晨阳','陈玉梅','罗王勇','孙家琛','李英才','胡明亮','苏郡','何青','潘雯','于梦思','魏涛','王英','宋佳月','丁老师','肖宁','马莉霞','李贺','李丹','董琦','邢丽月','朱玲婷','吴伟新','郝晓莉','朱丽莎','刘玉满','王欣'];
        $name = $task->t_teacher_lecture_appointment_info_b2->get_name_data();
        foreach($tname as $item) {
            $info = $task->t_teacher_lecture_appointment_info_b2->get_name_for_tea_name($item);
            if (!$info) continue;
            if ($info['grade'] >= 100 && $info['grade'] < 200) $info['grade'] = E\Egrade::get_desc(200);
            if ($info['grade'] >= 200 && $info['grade'] < 300) $info['grade'] = E\Egrade::get_desc(200);
            if ($info['grade'] >= 300) $info['grade'] = E\Egrade::get_desc(300);
            //$name = $info[count($info) - 1]['name'];
            echo $item.'   '.$name[$info['accept_adminid']]['name'].'   '.E\Esubject::get_desc($info['subject']).'   '.$info['grade'].',';
        }
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
