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
     * @desn $origin 例子渠道
     * @desn $subject 预定科目
     */
    public function __construct($userid,$uid,$posterTag,$phone,$origin='',$subject='')
    {

        parent::__construct();
        $this->userid=$userid;
        $this->uid=$uid;
        $this->phone=$phone;
        $this->posterTag=$posterTag;
        $this->origin=$origin;
        $this->subject=$subject;

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
        $t_seller_student_origin = new \App\Models\t_seller_student_origin();
        $t_student_info = new \App\Models\t_student_info();
        $t_origin_key = new \App\Models\t_origin_key();

        $need_count= \App\Helper\Config::get_day_system_assign_count();
        //系统自动分配序满足条件[非特殊渠道,已注册在公海,非在读学员] --begin--
        //特殊渠道不进入自动分配例子
        $special_origin = ['美团—1230','学校-180112'];
        $special_origin_level = [90,99,100];
        if($this->origin)
            $origin_level = $t_origin_key->field_get_value($this->origin, 'origin_level');
        else
            $origin_level = 0;
        if(!in_array($this->origin, $special_origin) && !in_array($origin_level, $special_origin_level)){
            $is_public = 0;//该用户从未注册
            /*//判断该用户是否重复且在公海中
            $data_item = $t_seller_student_new->field_get_list($this->userid,"admin_revisiterid,seller_resource_type" );
            if ($data_item) {
                $admin_revisiterid    = $data_item["admin_revisiterid"];
                $seller_resource_type = $data_item["seller_resource_type"];
            }
            $add_flag=$t_seller_student_origin->check_and_add($this->userid,$this->origin,$this->subject);
            if(!$add_flag) { //用户渠道增加失败[已注册]
                if($seller_resource_type==1 && $admin_revisiterid==0  ) //在公海里
                    $is_public = 1;//用户注册过但是在公海里
                else
                    $is_public = 2;//用户注册过但是不在公海里
            }*/ //判断学员是否在读
            $is_reading = $t_student_info->field_get_value($this->userid, 'type');
            if($is_reading == 1)
                $is_public = 3;//用户是在读学员

        //系统自动分配序满足条件[非特殊渠道,已注册在公海,非在读学员] --end--

            \App\Helper\Utils::logger("添加到自动抢单测试need_count:$need_count is_public:$is_public");
            if( $n->get_today_can_system_assign_count() < $need_count && in_array($is_public, [0,1])) {
                //分配模式 调整
                $n->field_update_list( $this->userid, [
                    "seller_student_assign_type"=> E\Eseller_student_assign_type::V_1,
                ]);
            }
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