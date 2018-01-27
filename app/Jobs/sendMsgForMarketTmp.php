<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMsgForMarketTmp extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
                //

        /**
           9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU

           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
        **/

        $this->delete();
        $t_parent_info = new  \App\Models\t_parent_info();
        $t_parent_send_mgs_log  = new \App\Models\t_parent_send_mgs_log();
        $t_lesson_info_b3   = new \App\Models\t_lesson_info_b3();
        $send_list = $t_parent_info->get_stu();

        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        // $send_list = [
        //     [
        //         "parentid" => 99999,
        //         "wx_openid" => 'orwGAs_IqKFcTuZcU1xwuEtV3Kek',
        //         "nick"      => "James",
        //         "userid" => 1
        //     ],
        //     [
        //         "parentid" => 99999,
        //         "wx_openid" => 'orwGAs6OXWNkItnMgG-Y2_6ZkkX4',
        //         "nick"      => "James",
        //         "userid" => 1
        //     ],
        // ];


        if(count($send_list)<7000){
            foreach($send_list as $i=> $item){
                $checkNeedSend = $t_lesson_info_b3->checkNeedSend($item['userid']);
                // $checkNeedSend = 1; //测试
                if($checkNeedSend == 1){


                    // $data = [
                    //     "first"    => '99%的孩子都在学数学思维，到底为什么？特邀新东方数学思维名师来揭密！',
                    //     "keyword1" => '1小时培养孩子的思维力',
                    //     "keyword2" => '点击，识别二维码参与课程',
                    //     "keyword3" => date('Y年m月d日'),
                    // ];
                    // $url = "http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E5%9C%A8%E7%BA%BF%E6%95%99%E8%82%B2.jpg";
                    // $data['remark'] = '';
                    // \App\Helper\Utils::send_wx_to_parent($item['wx_openid'] ,$template_id,$data,$url);
                    // $t_parent_send_mgs_log->row_insert([
                    //     "parentid"     => $item['parentid'],
                    //     "create_time"  => time(),
                    //     "is_send_flag" => 9 // 市场活动推送模板消息
                    // ]);
                }else{
                    unset($send_list[$i]);
                }

            }

            echo count($send_list);
        }
    }


}
// $teacher_list = [
//     [
//         "teacherid" => 225427,
//         "wx_openid" => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',
//         "nick"      => "James",
//         "teacher_money_type" => 7,
//         "teacher_type" => 1
//     ],
//     [
//         "teacherid" => 225427,
//         "wx_openid" => 'oJ_4fxAN36kXG9EmI0ttGzjymXm0',
//         "nick"      => "侯勇",
//         "teacher_money_type" => 7,
//         "teacher_type" => 1
//     ],
// ];
