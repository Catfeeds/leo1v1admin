<?php
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Enums as E;

class new_seller_student extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $userid;
    var $uid;
    var $posterTag;
    var $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid,$uid,$posterTag,$phone)
    {

        parent::__construct();
        $this->userid=$userid;
        $this->uid=$uid;
        $this->phone=$phone;
        $this->posterTag=$posterTag;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $n=new \App\Models\t_seller_student_new_b2();
        $t_personality_poster = new \App\Models\t_personality_poster();
        $t_seller_student_new = new \App\Models\t_seller_student_new();
        $t_manager_info       = new \App\Models\t_manager_info();
        $t_poster_share_log   = new \App\Models\t_poster_share_log();

        $need_count= \App\Helper\Config::get_day_system_assign_count();
        if( $n->get_today_can_system_assign_count() < $need_count ) {
            //分配模式 调整
            $n->field_update_list( $this->userid, [
                "seller_student_assign_type"=> E\Eseller_student_assign_type::V_1,
            ]);
        }

        //例子设置未拨打
        $n->field_update_list( $this->userid, [
            "global_tq_called_flag"=>0,
        ]);


        # 获取分享链接打开次数 [市场部活动-分享个性海报]
        $checkHas = $t_personality_poster->checkHas($this->uid);
        $hasAdminRevisiterid = $t_seller_student_new->hasAdminRevisiterid($this->userid);
        if($this->posterTag>0){
            if($checkHas>0){
                $t_personality_poster->updateStuNum($this->uid);
            }else{
                $t_personality_poster->row_insert([
                    "uid" => $this->uid,
                    "stuNum" => 1
                ]);
            }
            $t_poster_share_log->row_insert([
                "uid" => $this->uid,
                "phone" => $this->phone,
                "studentid" => $this->userid,
                "add_time"  => time()
            ]);

            # 将市场海报分享进来的学生 放入到对应的CC\CR的私库中
            if(!$hasAdminRevisiterid){
                $opt_adminid  = $this->uid;
                $opt_account  = $t_manager_info->get_account($opt_adminid);
                $self_adminid = 684;
                $account = '系统-市场个性海报转发';
                $t_seller_student_new->allotStuToDepot($opt_adminid, $opt_account, $this->userid, $self_adminid,$account);
            }
        }


    }
}