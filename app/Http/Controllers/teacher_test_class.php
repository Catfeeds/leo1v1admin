<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class teacher_test_class extends Controller
{
    use CacheNick;
    use TeaPower;

    /**
     * @author adrian
     * 此类中所有方法只能在非正式环境中使用!
     */
    public function __construct(){
        parent::__construct();
        if(\App\Helper\Utils::check_env_is_release()){
            echo $this->output_err("此功能只能在非正式环境使用!");
            exit;
        }
    }

    /**
     * 一键快速添加测试老师的报名信息和账号
     * 使用页面 /human_resource/teacher_lecture_appointment_info
     * @param int teacher_type 老师身份
     * @param int reference    推荐人id
     */
    public function add_teacher_lecture_appointment_for_test(){
        $teacher_type = $this->get_in_int_val("teacher_type");
        $reference    = $this->get_in_int_val("reference");

        $reference_phone = $this->t_teacher_info->get_phone($reference);
        $max_phone = $this->t_teacher_info->get_max_test_phone();
        $test_phone = $max_phone+1;
        $this->t_teacher_lecture_appointment_info->row_insert([
            "answer_begin_time" => time(),
            "phone"             => $test_phone,
            "name"              => $test_phone,
            "reference"         => $reference_phone,
            "teacher_type"      => $teacher_type,
            "accept_adminid"    => $this->get_account_id(),
            "accept_time"       => time(),
            "hand_flag"         => 1
        ]);
        $teacher_info['phone'] =$test_phone;
        $teacher_info['identity'] =$teacher_type;
        $this->add_teacher_common($teacher_info);
        return $this->output_succ();
    }

    /**
     * 一键使老师通过
     * 使用页面 /human_resource/teacher_lecture_appointment_info
     * @param string phone 待通过的老师手机
     */
    public function set_teacher_through(){
        $phone = $this->get_in_str_val("phone");

        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $this->teacher_train_through_deal($teacherid);
        return $this->output_succ();
    }


}
