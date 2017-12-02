<?php
namespace App\Http\Controllers;

use \App\Enums as E;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;

use Illuminate\Support\Facades\Mail ;

require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class testbb extends Controller
{
    use CacheNick;


    var $check_login_flag = false;
    public function get_msg_num() {
        $a= new \App\Jobs\send_error_mail(1,33,33);
        $a->task->t_agent->get_agent_count_by_id(1);

    }



    public function assistant_info_new2(){
        $today      = date('Y-m-d',time(null));
        $today      = '20170626';
        $start_time = strtotime($today.'00:00:00');
        $end_time   = $start_time+24*3600;
        $userid=-1;
        $lesson_arr = [];
        $phone = '456';
        $lesson_arr = $this->t_agent->get_agent_info_row_by_phone($phone);
    }


    public function test1() {
        $account_id = $this->get_in_int_val('id');

        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);
        $ass_list = array_column($ass_list,'adminid');
        $ass_list_str = implode(',',$ass_list);
        dd($ass_list_str);
    }

    public function ttt(){// 更新扩课信息

        $require_id = $this->get_in_int_val('rid');
        $origin = $this->get_in_str_val('origin');
        $change_teacher_reason_type = $this->get_in_int_val('change_teacher_reason_type');
        $change_teacher_reason = $this->get_in_str_val('change_teacher_reason');
        $ret= $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "origin" => $origin,
            "change_teacher_reason_type" => $change_teacher_reason_type,
            "change_teacher_reason" => $change_teacher_reason
        ]);

        return $ret;
    }


    public function test () {

        $ret=\App\Helper\Config::get_config("audio_server_list");
        //$ret=\App\Helper\Common::env_obj( "AUDIO_SERVER_LIST" );
        dd($ret);
        dd($_SERVER);
        dd($num);

        $a = 'http://1111';
        $d = preg_match('/Http/i',$a);
        dd($d);


        $rand = mt_rand(0,100000);
        $money = $rand;

        if($rand>1000 && $rand<=1035){ // 中 91.0元
            $money = '9100'; // 单位分
        }elseif($rand>2000 && $rand <=3000){ // 中9.1元
            $money = '910'; // 单位分
        }elseif($rand>20000 && $rand<33000){ // 中0.91元
            $money = '91'; // 单位分
        }

        echo $money;


    }

    public function lesson_send_msg(){
        $start_time = time(null);
        $this->t_teacher_info->get_lesson_info_by_time($start_time,$end_time);
    }







    public function set_teacher_free_time(){
        $free_time = $this->get_in_str_val('parent_modify_time');

        // 加一个时间的限制
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
    }




    public function tt() {

        print_r(session("menu_html" ));
    }

    public function rename_file() {
        dd(date('Y年m月'));
    }


    public function test_img(){

        $this->switch_tongji_database();
        $ss = $this->t_lesson_info_b3->get_next_day_lesson_info();
        dd($ss);
        $next_day_begin = strtotime(date('Y-m-d',strtotime("+1 days")));
        $next_day_end   = strtotime(date('Y-m-d',strtotime("+2 days")));;
        dd($next_day_end);
    }



    //以下代码勿删
    public function get_pdf_url(){
        $pdf_url   = $this->get_in_str_val('pdf_url');
        $lessonid  = $this->get_in_int_val('lessonid');
        $pdf_file_path = $this->gen_download_url($pdf_url);

        // dd($pdf_file_path);
        $savePathFile = public_path('wximg').'/'.$pdf_url;

        if($pdf_url){

            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);

            $path = public_path().'/wximg';

            @chmod($savePathFile, 0777);
            $imgs_url_list = @$this->pdf2png($savePathFile,$path,$lessonid);

            // dd($imgs_url_list);
            $file_name_origi = array();
            foreach($imgs_url_list as $item){
                $file_name_origi[] = @$this->put_img_to_alibaba($item);
            }

            $file_name_origi_str = implode(',',$file_name_origi);

            $ret = $this->t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);

            foreach($imgs_url_list as $item_orgi){
                @unlink($item_orgi);
            }

            @unlink($savePathFile);


        }

        dd($file_name_origi_str);


    }


    private function gen_download_url($file_url)
    {
        // 构建鉴权对象
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }

    //
    public function pdf2png($pdf,$path, $lessonid){

        if(!extension_loaded('imagick')){
            return false;
        }
        if(!$pdf){
            return false;
        }
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);

        $is_exit = file_exists($pdf);

        if($is_exit){
            @$IM->readImage($pdf);
            foreach($IM as $key => $Var){
                @$Var->setImageFormat('png');
                $Filename = $path."/l_t_pdf_".$lessonid."_".$key.".png" ;
                if($Var->writeImage($Filename)==true){
                    $Return[]= $Filename;
                }
            }
            return $Return;
        }else{
            return [];
        }

    }


    public function put_img_to_alibaba($target){
        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);


            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            \App\Helper\Utils::logger('shangchun55'. $config["public"]["url"]."/".$file_name);

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }


    //以上代码勿删



    public function get_num(){

        $no    = rand(1,10000);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_identity_for_book" );
        $appid = 'wx636f1058abca1bc1';

        $u= "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&no=$no&scope=snsapi_userinfo&state=STATE_$no&connect_redirect=1#wechat_redirect";

        header("location: $u");

        $this->switch_tongji_database();
        // $teacherid = $this->t_lesson_info_b3->get_on_num();
        $teacherid = $this->t_lesson_info_b3->get_on_teacherid();
        //get_on_num
        $aa = [];
        foreach($teacherid as $item){
            $aa[] = $item['teacherid'];
        }
        // dd($aa);
        $str = implode(',',$aa);

        $ret_info = $this->t_teacher_info->get_on_total($str);
        dd($ret_info);

    }

    public function ss(){

        $a = "[1506866400,1506835800,1507039200,1507023000,1507282200]";
        $date_modify = json_decode($a,true);
        $day_date = [];
        foreach($date_modify as $item){
            $day_date[] = date('Y-m-d',$item);
        }
        $b = array_flip(array_flip($day_date));
        foreach($b as $val){
            $begin_time = strtotime($val);
            $end_time   = $begin_time+86400;
            $teacher_lesson_time[] = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $begin_time, $end_time);
        }


        dd($b);
        // dd(json_decode($a,true));

        $ret_arr = \App\Helper\Common::redis_set_json('a',['a'=>1]);

        list($start_time,$end_time) = $this->get_in_date_range_day(-1);
        dd($start_time.' ~ '.$end_time);
        $seller_student_status= E\Eseller_student_status::V_200;



    }

    public function install(){

        // 暂时未建

        // new_order_num  //test_lesson_succ_num
        Schema::create('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->integer("create_time"),"创建时间");
            t_field($table->integer("from_time"),"来自于那个月份 或者 周 第一天时间戳");
            t_field($table->integer("referral_money"),"转介绍合同收入");
            t_field($table->integer("new_money"),"新签合同收入");
            t_field($table->integer("order_num"),"下单总人数");
            t_field($table->integer("formal_info"),"入职完整月人员收入");
            t_field($table->integer("formal_num"),"入职完整月人员人数");
            t_field($table->integer("seller_target_income"),"销售目标收入");
            t_field($table->integer("total_num"),"销售合同量");
            t_field($table->integer("one_department"),"销售一部人数");
            t_field($table->integer("two_department"),"销售二部人数");
            t_field($table->integer("three_department"),"销售三部人数");
            t_field($table->integer("new_department"),"销售新人营人数");
            t_field($table->integer("train_department"),"销售培训中");
            t_field($table->integer("high_school_money"),"高中金额");
            t_field($table->integer("junior_money"),"初中金额");
            t_field($table->integer("primary_money"),"小学金额");



            t_field($table->integer("seller_invit_num"),"试听邀约数");
            t_field($table->integer("seller_schedule_num"),"试听排课数");
            t_field($table->integer("test_succ_num"),"试听成功数");
            t_field($table->integer("seller_invit_month"),"拨通电话数量[月签约率]");
            t_field($table->integer("has_tq_succ_invit_month"),"已拨通[月签约率]");
            //seller_plan_invit_month
            t_field($table->integer("seller_plan_invit_month"),"试听排课数[月排课率]");
            t_field($table->integer("seller_test_succ_month"),"试听成功数[月到课率]");
            t_field($table->integer("order_trans_month"),"合同人数[月试听转化率]");
            t_field($table->integer("has_tq_succ_sign_month"),"拨通电话数量[月签约率]");
            t_field($table->integer("has_tq_succ_sign_month"),"拨通电话数量[月签约率]");


            t_field($table->integer("seller_call_num"),"电话呼出量");
            t_field($table->integer("has_called"),"已拨打数量");
            t_field($table->integer("has_tq_succ"),"已拨通数量[接通率]");
            t_field($table->integer("claim_num"),"已认领[认领率]");
            t_field($table->integer("new_stu"),"本月新进例子数");
            t_field($table->integer("has_called_stu"),"已拨打例子量[月例子消耗率]");

            t_field($table->integer("cc_called_num"),"拨打的cc量");
            t_field($table->integer("cc_call_time"),"cc总计通话时长");

        });

    }







}