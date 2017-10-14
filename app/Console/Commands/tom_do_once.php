<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");

class tom_do_once extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tom_do_once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        $ret_level_goal = $this->task->t_seller_level_goal->get_all_list_new();
        foreach($seller_list as $item){
            $adminid = $item['uid'];
            $level_face = $item['level_face'];
            $item['level_face_pic'];
        }


        $now = time();
        $list=$this->task->t_seller_student_new->get_all_list();
        foreach ($list as $item) {
            $userid = $item["userid"];
            $phone = $item["phone"];
            // echo "$userid\n";
            if($userid){
                //例子试听成功次数
                // $succ_test_info = $this->task->t_lesson_info_b2->get_succ_test_lesson_count($userid);
                // $succ_count = $succ_test_info['count'];
                // echo "$userid".':'."$succ_count"."\n";
                // if($item['test_lesson_count'] != $succ_count){
                //     $this->task->t_seller_student_new->field_update_list($userid,['test_lesson_count'=>$succ_count]);
                // }

                //例子回流时间
                // $ret = $this->task->t_test_subject_free_list->get_all_list_by_userid($userid);
                // if($ret){
                //     $this->task->t_seller_student_new->field_update_list($userid,['free_adminid'=>$ret['adminid'],'free_time'=>$ret['add_time']]);
                // }

                //例子最后回访时间
                $ret = $this->task->t_tq_call_info->get_last_call_by_phone($phone);
                $call_time = isset($ret['start_time'])?$ret['start_time']:0;
                echo $userid.':'."$call_time"."\n";
                $set_arr=[];
                $set_arr["last_revisit_time"]=$call_time;
                $this->task->t_seller_student_new->field_update_list($userid,$set_arr);
            }
        }
    }
            /*

        $task        = new \App\Console\Tasks\TaskController();
        $teacher_list=$task->t_teacher_info->get_all_list();
        foreach ($teacher_list as $tea_item  ) {
            $teacherid=$tea_item["teacherid"];
            $arr=$task->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
            foreach( $arr as $item) {
                $end = $item['end_time'];
                $arr=explode("-",$item['start_time']);
                $start = @$arr[1];
                $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
                $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end");
                $diff=($lesson_end-$lesson_start)/60;
                if ($diff<=40) {
                    $lesson_count=100;
                } else if ( $diff <= 60) {
                    $lesson_count=150;
                } else if ( $diff <=90 ) {
                    $lesson_count=200;
                }else{
                    $lesson_count= ceil($diff/40)*100 ;
                }


                echo "insert into  db_weiyi.t_week_regular_course (teacherid,userid,start_time,end_time,lesson_count) values('".$teacherid."','".$item['userid']."','".$item['start_time']."','".$item['end_time']."','".$lesson_count."');\n" ;
            }

            */

        /*
        $task        = new \App\Console\Tasks\TaskController();
        $sql="select userid,grade from db_weiyi.t_student_info ";
        $list=$task->t_student_info->main_get_list($sql);
        foreach ($list as $item) {
            $userid         = $item["userid"];
            $grade = $item["grade"];
            $next_grade= \App\Helper\Utils::get_next_grade($grade);

            echo  "$userid\t$grade $next_grade\n  ";
            $task->t_student_info->field_update_list($userid,[
                "grade" => $next_grade,
            ]);

        }
        */

    //处理等级头像
    public function get_top_img(){
        $adminid = 99;
        $datapath = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/032b2cc936860b03048302d991c3498f1505471050366test.jpg';
        $datapath_new = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/aedfd832fcef79e331577652efba5acf1507626407041.png';
        $image_1 = imagecreatefromjpeg($datapath);
        $image_2 = imagecreatefrompng($datapath_new);
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        // $color = imagecolorallocate($image_3,255,255,255);
        $color = imagecolorallocatealpha($image_3,255,255,255,1);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);

        imagecopyresampled($image_3,$image_2,0,0,0,0,imagesx($image_3),imagesy($image_3),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_1,$image_3,0,0,0,0,imagesx($image_3),imagesx($image_3),100);
        $tmp_url = "/tmp/".$adminid."_gk.png";
        imagepng($image_1,$tmp_url);
        $file_name = \App\Helper\Utils::qiniu_upload($tmp_url);
        $level_face_url = '';
        if($file_name!=''){
            $cmd_rm = "rm /tmp/".$adminid."*.png";
            \App\Helper\Utils::exec_cmd($cmd_rm);
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$file_name;
        }
        dd($level_face_url);
    }


}
