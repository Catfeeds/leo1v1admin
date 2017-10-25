<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;

use Illuminate\Support\Facades\Mail ;

use App\Jobs\send_wx_notic_for_software;
use  App\Jobs\send_wx_notic_to_tea;


require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class test_james extends Controller
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
        $store=new \App\FileStore\file_store_tea();
        $ret=$store->list_dir("10001", "/log1");
        dd($ret);
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

        $start_time = $this->get_in_int_val('s');
        $end_time = $this->get_in_int_val('e');

        $new_order_info = $this->t_order_info->get_new_order_money($start_time, $end_time);// 新签合同

        $referral_order = $this->t_order_info->get_referral_income($start_time, $end_time); //  转介绍

        $b = $this->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 销售邀约数

        dd($b);
        // $a = $new_order_info['order_num_new'] + $referral_order['total_num'];
        // dd($a);

        // $now = time(NULL);
        // $lesson_list = $this->t_lesson_time_modify->get_need_notice_lessonid($now);

        dd($new_order_info['order_num_new']." ~ ".$new_order_info['total_price']);

        $wx = new \App\Helper\Wx();
        // 向家长发送推送
        $lesson_start_date = date('H:i:s');
        $parent_wx_openid    = "orwGAs_IqKFcTuZcU1xwuEtV3Kek";
        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
        $data_parent = [
            'first' => "调课申请被拒绝",
            'keyword1' =>"调换".$lesson_start_date."上课时间被拒绝",
            'keyword2' => "由于此时间段老师时间不方便,故调课申请未成功",
            'keyword3' => date('Y-m-d H:i:s'),
            'remark'   => "请耐心等待助教老师进行沟通!"
        ];
        $url_parent = '';
        $wx->send_template_msg($parent_wx_openid, $parent_template_id, $data_parent, $url_parent);
    }

    public function has_called(){
        $this->switch_tongji_database();
        $start_time = $this->get_in_int_val('s');
        $end_time = $this->get_in_int_val('e');

        // $order_info_total = $this->t_order_info->get_referral_income($start_time, $end_time);// 总收入
        $order_info_total = $this->t_order_info->get_new_order_money($start_time, $end_time);// 总收入

        // get_new_order_money
        // $ret_info['has_called'] = $this->t_tq_call_info->get_has_called_stu_num($start_time, $end_time); // 已拨打例子

        dd($order_info_total);
    }

    public function install(){
    }



    public function ss1(){ // 使用客服接口发送消息


        //使用客服接口发送消息
        $txt_arr = [
            'touser'   => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',// james
            'msgtype'  => 'news',
            "news"=>[
                "articles"=> [
                    [
                        "title"=>"TEST MSG",
                        "description"=>"Is Really A Happy Day",
                        "url"=>"https://mmbiz.qlogo.cn/mmbiz_jpg/cBWf565lml4NcGMWTiaeuDmWsUQpXz8TPJzfbsoUENe9dKqPKDXPZa7ITPCKvQiaVzmAvLBKPYmrhKNg2AkwwkVQ/0?wx_fmt=jpeg",
                        "picurl"=>"http://admin.yb1v1.com/article_wx/leo_teacher_new_teacher_deal_question"
                    ]
                ]
            ]
        ];

        $appid_tec     = config('admin')['teacher_wx']['appid'];
        $appsecret_tec = config('admin')['teacher_wx']['appsecret'];

        $wx = new \App\Helper\Wx() ;
        $token = $wx->get_wx_token($appid_tec,$appsecret_tec);


        $txt = $this->ch_json_encode($txt_arr);
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
        $txt_ret = $this->https_post($url,$txt);

    }


    public function https_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function ch_json_encode($data) {


        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);

        return urldecode($ret);
    }

    public function ch_urlencode($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } else if (is_array($data)) {
                    $data[$k] = self::ch_urlencode($v); //递归调用该函数
                } else if (is_object($data)) {
                    $data->$k = self::ch_urlencode($v);
                }
            }
        }

        return $data;
    }





    public function get_seller_total_info(){ // cc 总表信息
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range_month(date("Y-m-01"));
        $history_data = $this->get_in_int_val('history_data');

        if($history_data){ // 0:是历史数据 1:否历史数据
            $ret_info_arr['list'] = $this->t_seller_tongji_for_month->get_history_data($start_time);

            $ret_info = &$ret_info_arr['list'];
            //概况
            $order_info_total = $this->t_order_info->get_total_money($start_time, $end_time);// 总收入

            $referral_order = $this->t_order_info->get_referral_income($start_time, $end_time); //  转介绍

            $ret_info['income_referral'] = $referral_order['referral_price']; // 转介绍收入
            $ret_info['income_new']   = $order_info_total['total_price'] - $referral_order['referral_price']; //  新签
            $ret_info['income_price'] = $order_info_total['total_price'];
            $ret_info['income_num']   = $order_info_total['total_num']; // 有签单的销售人数


            if($order_info_total['total_num']>0){
                $ret_info['aver_count'] = $order_info_total['total_price']/$order_info_total['total_num'];//平均单笔
            }else{
                $ret_info['aver_count'] = 0; //平均单笔
            }

            $job_info = $this->t_order_info->get_formal_order_info($start_time,$end_time); // 入职完整月人员签单额
            $ret_info['formal_info'] = $job_info['job_price']; // 入职完整月人员签单额
            $ret_info['formal_num']  = $job_info['job_num']; // 入职完整月人员人数

            if($ret_info['formal_num']>0){
                $ret_info['aver_money'] = $ret_info['formal_info']/$ret_info['formal_num']; //平均人效
            }else{
                $ret_info['aver_money'] = 0;
            }

            // dd($ret_info);
            $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
            $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

            // $main_type = 2;// 销售
            $ret_info['seller_target_income'] = $this->get_month_finish_define_money(0,$start_time); // 销售月目标收入
            if (!$ret_info['seller_target_income'] ) {
                $ret_info['seller_target_income'] = 1600000;
            }

            $month_finish_define_money_2=$ret_info['seller_target_income']/100;
            $month_start_time = strtotime( date("Y-m-01",  $start_time));
            $month_end_time   = strtotime(date("Y-m-01",  ($month_start_time+86400*32)));
            $month_date_money_list = $this->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);
            $ret_info['cur_money']=0;
            $today=time(NULL);
            foreach ($month_date_money_list as $date=> &$item ) {
                $date_time=strtotime($date);
                if ($date_time<=$today) {
                    $ret_info['cur_money']+=@$item["money"];
                }
            }
            $ret_info['month_finish_persent'] = $ret_info['cur_money']/$ret_info['seller_target_income'];//月kpi完成率
            $ret_info['month_left_money'] = $ret_info['seller_target_income'] - $ret_info['cur_money'];//

            if($ret_info['seller_target_income']>0){
                $ret_info['seller_kpi'] = $ret_info['income_price']/$ret_info['seller_target_income']*100;
            }else{
                $ret_info['seller_kpi'] = 0;
            }

            // 计算电销人数
            $first_group  = '咨询一部';
            $second_group = '咨询二部';
            $third_group  = '咨询三部';
            $new_group    = '新人营';
            $ret_info['first_num']  = $seller_num_arr['first_num']  = $this->t_admin_group_name->get_group_seller_num($first_group);// 咨询一部
            $ret_info['second_num'] = $seller_num_arr['second_num'] = $this->t_admin_group_name->get_group_seller_num($second_group);// 咨询二部
            $ret_info['third_num']  = $seller_num_arr['third_num']  = $this->t_admin_group_name->get_group_seller_num($third_group);// 咨询三部
            $ret_info['new_num']    = $seller_num_arr['new_num']    = $this->t_admin_group_name->get_group_new_count($new_group);// 新人营
            $ret_info['traing_num'] = $seller_num_arr['traing_num'] = '';// 培训中
            $ret_info['seller_num'] = $ret_info['first_num']+$ret_info['second_num']+$ret_info['third_num']+$ret_info['new_num'];// 咨询一部+咨询二部+咨询三部+新人营
            $ret_info['department_num_info'] = json_encode($seller_num_arr);



            // 金额转化率占比
            $ret_info['high_school_money'] = $this->t_order_info->get_high_money_for_month($start_time, $end_time);
            $ret_info['junior_money']      = $this->t_order_info->get_junior_money_for_month($start_time, $end_time);
            $ret_info['primary_money']     = $this->t_order_info->get_primary_money_for_month($start_time, $end_time);

            if($ret_info['income_price']>0){
                $ret_info['referral_money_rate'] = $ret_info['income_referral']/$ret_info['income_price']*100;
                $ret_info['high_school_money_rate']   =  $ret_info['high_school_money']/$ret_info['income_price']*100;
                $ret_info['junior_money_rate']  = $ret_info['junior_money']/$ret_info['income_price']*100;
                $ret_info['primary_money_rate'] = $ret_info['primary_money']/$ret_info['income_price']*100;
            }else{
                $ret_info['referral_money_rate']    = 0;
                $ret_info['high_school_money_rate'] = 0;
                $ret_info['junior_money_rate']      = 0;
                $ret_info['primary_money_rate']     = 0;
            }

            // 转化率
            $ret_info['seller_invit_num'] = $this->t_test_lesson_subject_require->get_invit_num($start_time, $end_time); // 销售邀约数
            $ret_info['seller_schedule_num'] = $this->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 教务已排课
            $ret_info['test_lesson_succ_num'] = $this->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功
            $ret_info['new_order_num'] = $order_info_total['total_num']; // 合同数量



            $ret_info['has_tq_succ'] = $this->t_seller_student_new->get_tq_succ_num($start_time, $end_time); // 拨通电话数量

            //  外呼情况
            $ret_info['seller_call_num'] = $ret_info['has_called'] =  $this->t_tq_call_info->get_tq_succ_num($start_time, $end_time);//  呼出量
            $ret_info['has_called_stu'] = $this->t_tq_call_info->get_has_called_stu_num($start_time, $end_time); // 已拨打例子


            $ret_info['claim_num'] = $this->t_seller_student_new->get_claim_num($start_time, $end_time);//  认领量

            $ret_info['new_stu'] = $this->t_seller_student_new->get_new_stu_num($start_time, $end_time); // 本月新进例子数


            $ret_info['cc_called_num'] = $this->t_tq_call_info->get_cc_called_num($start_time, $end_time);// 拨打的cc量
            $ret_info['cc_call_time'] = $this->t_tq_call_info->get_cc_called_time($start_time, $end_time); // cc通话时长
            $ret_info['seller_invit_month'] = $this->t_test_lesson_subject_require->get_invit_num_for_month($start_time, $end_time); // 销售邀约数[月邀约数]
            $ret_info['has_tq_succ_invit_month']  = $this->t_seller_student_new->get_tq_succ_for_invit_month($start_time, $end_time); // 已拨通[月邀约数]

            $ret_info['seller_plan_invit_month'] = $this->t_test_lesson_subject_require->get_plan_invit_num_for_month($start_time, $end_time); // 试听邀约数[月排课率]
            $ret_info['seller_test_succ_month'] = $this->t_lesson_info_b3->get_test_succ_for_month($start_time, $end_time); // 试听成功数[月到课率]
            $ret_info['order_trans_month'] = $this->t_order_info->get_order_trans_month($start_time, $end_time); // 合同人数[月试听转化率]

            $ret_info['has_tq_succ_sign_month'] = $this->t_seller_student_new->get_tq_succ_num_for_sign($start_time, $end_time); // 拨通电话数量[月签约率]
            $ret_info['order_sign_month'] = $this->t_order_info->get_order_sign_month($start_time, $end_time); // 合同人数[月签约率]

            $ret_info['un_consumed'] = $ret_info['new_stu']-$ret_info['has_called_stu']; // 未消耗例子数



            if($ret_info['has_tq_succ_invit_month_funnel']>0){ //月邀约率
                $ret_info['invit_month_rate'] = $ret_info['seller_invit_month']/$ret_info['has_tq_succ_invit_month_funnel']*100;
            }else{
                $ret_info['invit_month_rate'] = 0;
            }


            if($ret_info['seller_plan_invit_month_funnel']>0){ //月排课率
                $ret_info['test_plan_month_rate'] = $ret_info['seller_schedule_num']/$ret_info['seller_plan_invit_month_funnel']*100;
            }else{
                $ret_info['test_plan_month_rate'] = 0;
            }

            if($ret_info['seller_schedule_num']>0){ //月到课率
                $ret_info['lesson_succ_month_rate'] = $ret_info['seller_test_succ_month_funnel']/$ret_info['seller_schedule_num']*100;
            }else{
                $ret_info['lesson_succ_month_rate'] = 0;
            }


            if($ret_info['seller_test_succ_month_funnel']>0){ //月试听转化率
                $ret_info['trans_month_rate'] = $ret_info['order_trans_month']/$ret_info['seller_test_succ_month_funnel']*100;
            }else{
                $ret_info['trans_month_rate'] = 0;
            }


            if($ret_info['has_tq_succ_sign_month']>0){ //月签约率
                $ret_info['sign_month_rate'] = $ret_info['order_sign_month']/$ret_info['has_tq_succ_sign_month']*100;
            }else{
                $ret_info['sign_month_rate'] = 0;
            }

            if($ret_info['has_called']>0){
                $ret_info['succ_called_rate'] = $ret_info['has_tq_succ']/$ret_info['has_called']*100; //接通率
                $ret_info['claim_num_rate'] = $ret_info['claim_num']/$ret_info['has_called']*100; //认领率
            }else{
                $ret_info['claim_num_rate'] = 0;
                $ret_info['succ_called_rate'] = 0;
            }


            if($ret_info['seller_num']>0){ // 人均通时
                $ret_info['called_rate'] = $ret_info['cc_call_time']/$ret_info['seller_num'];
            }else{
                $ret_info['called_rate'] = 0;
            }

            if($ret_info['cc_called_num']>0){
                $ret_info['aver_called'] = $ret_info['seller_call_num']/$ret_info['cc_called_num']; // 人均呼出量
                $ret_info['invit_rate'] = $ret_info['seller_invit_num']/$ret_info['cc_called_num']; // 人均邀约率
            }else{
                $ret_info['aver_called'] = 0;
                $ret_info['invit_rate'] = 0;
            }

            if($ret_info['new_stu']>0){ //月例子消耗数
                $ret_info['stu_consume_rate'] = $ret_info['has_called_stu']/$ret_info['new_stu']*100;
            }else{
                $ret_info['stu_consume_rate'] = 0;
            }

        }else{ // 历史数据 [从数据库中取]
            $ret_info_arr['list'] = $this->t_seller_tongji_for_month->get_history_data($start_time);

            $ret_info = &$ret_info_arr['list'];

            if($ret_info['has_tq_succ_invit_month']>0){ //月邀约率
                $ret_info['invit_month_rate'] = $ret_info['seller_invit_month']/$ret_info['has_tq_succ_invit_month']*100;
            }else{
                $ret_info['invit_month_rate'] = 0;
            }

            if($ret_info['seller_plan_invit_month']>0){ //月排课率
                $ret_info['test_plan_month_rate'] = $ret_info['seller_schedule_num']/$ret_info['seller_plan_invit_month']*100;
            }else{
                $ret_info['test_plan_month_rate'] = 0;
            }

            if($ret_info['seller_schedule_num']>0){ //月到课率
                $ret_info['lesson_succ_month_rate'] = $ret_info['seller_test_succ_month']/$ret_info['seller_schedule_num']*100;
            }else{
                $ret_info['lesson_succ_month_rate'] = 0;
            }


            if($ret_info['seller_test_succ_month']>0){ //月试听转化率
                $ret_info['trans_month_rate'] = $ret_info['order_trans_month']/$ret_info['seller_test_succ_month']*100;
            }else{
                $ret_info['trans_month_rate'] = 0;
            }


            if($ret_info['has_tq_succ_sign_month']>0){ //月签约率
                $ret_info['sign_month_rate'] = $ret_info['order_sign_month']/$ret_info['has_tq_succ_sign_month']*100;
            }else{
                $ret_info['sign_month_rate'] = 0;
            }

            if($ret_info['has_called']>0){
                $ret_info['succ_called_rate'] = $ret_info['has_tq_succ']/$ret_info['has_called']*100; //接通率
                $ret_info['claim_num_rate'] = $ret_info['claim_num']/$ret_info['has_called']*100; //认领率
            }else{
                $ret_info['claim_num_rate'] = 0;
                $ret_info['succ_called_rate'] = 0;
            }


            if($ret_info['seller_num']>0){ // 人均通时
                $ret_info['called_rate'] = $ret_info['cc_call_time']/$ret_info['seller_num'];
            }else{
                $ret_info['called_rate'] = 0;
            }

            if($ret_info['cc_called_num']>0){
                $ret_info['aver_called'] = $ret_info['seller_call_num']/$ret_info['cc_called_num']; // 人均呼出量
                $ret_info['invit_rate'] = $ret_info['seller_invit_num']/$ret_info['cc_called_num']; // 人均邀约率
            }else{
                $ret_info['aver_called'] = 0;
                $ret_info['invit_rate'] = 0;
            }

            if($ret_info['new_stu']>0){ //月例子消耗数
                $ret_info['stu_consume_rate'] = $ret_info['has_called_stu']/$ret_info['new_stu']*100;
            }else{
                $ret_info['stu_consume_rate'] = 0;
            }


        }

        $ret_info_arr["page_info"] = array(
            "total_num"      => 1,
            "per_page_count" => 100000,
            "page_num"       => 1,
        );


        return $this->pageView(__METHOD__, $ret_info_arr,[
            "ret_info" => $ret_info_arr['list']
        ]);
    }


    public function get_all_stu_info(){


        $parentid = $this->get_in_int_val('parentid');

        $student_info = $this->t_student_info->get_stu_info_by_parentid($parentid);

        return $this->output_succ(['data'=>$student_info]);
    }



    public function send_msg_to_parent(){
        dd(1);




    }


    public function send_msg_to_teacher(){


        $re = $this->t_teacher_info->get_openid_list();
        dd($re);
        dd('已处理');
        


        // dd($re);
    }



    public function dds(){


    }




    public function ssss(){

        $this->switch_tongji_database();
        // $parent_list = $this->t_parent_info->get_openid_list();

        // dd(count($parent_list));

        $start_time = $this->get_in_int_val('s');
        $end_time = $this->get_in_int_val('e');

        // $a = $this->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功


        $ret_info['seller_plan_invit_month'] = $this->t_test_lesson_subject_require->get_plan_invit_num_for_month($start_time, $end_time); // 试听邀约数[月排课率]
        $ret_info['seller_schedule_num'] = $this->t_test_lesson_subject_require->get_seller_schedule_num($start_time, $end_time); // 教务已排课

        dd($ret_info);
        $a = $this->t_admin_group_name->get_entry_month_num($start_time,$end_time);
        $b = [];
        foreach($a as $v){
            $b[]=$v['lessonid'];
        }

        dd($b);


        if($start_time == null && $end_time == null ){
            $end_time   = strtotime(date('Y-m-d 0:0:0'));
            $start_time = $end_time-7*86400;
        }


        $month_start_time_funnel = strtotime(date('Y-m-01',$start_time));

        if($month_start_time_funnel<$start_time){
            $month_start_time_funnel = $start_time;
        }




        $ret_info['has_tq_succ_invit_month']  = $this->t_seller_student_new->get_tq_succ_for_invit_month($start_time, $end_time); // 已拨通[月邀约数]


        dd($ret_info);

       //  $six_month_old = strtotime(date('Y-m-d 0:0:0',strtotime('-2 month',$start_time)));

       // echo date('Y-m-01', strtotime('+1 month'));

        $month_start_time = strtotime(date("Y-m-01",$start_time));
        $month_end_time = strtotime(date('Y-m-01', strtotime('+1 month',$month_start_time)));


        echo $month_start_time.' ~ '.$month_end_time;
        dd('ok');




        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new("");

        $main_type = 2;// 销售
        $ret_info['seller_target_income'] = $this->get_month_finish_define_money(0,$start_time); // 销售月目标收入
        if (!$ret_info['seller_target_income'] ) {
            $ret_info['seller_target_income'] = 1600000;
        }


        // $month_end_time   = strtotime(date("Y-m-01",  $end_time));
        // $month_start_time = strtotime(date("Y-m-01",  ($month_end_time-86400*20)));


        $month_start_time = strtotime(date("Y-m-01"));
        $month_end_time = strtotime(date('Y-m-01', strtotime('+1 month')));


        $month_date_money_list = $this->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);
        $ret_info['formal_info']=0;  // 完成金额
        $today=time(NULL);
        foreach ($month_date_money_list as $date=> &$item ) {
            $date_time=strtotime($date);
            if ($date_time<=$today) {
                $ret_info['formal_info']+=@$item["money"];
            }
        }

        dd($ret_info);
        // $r = $this->t_admin_group_name->get_entry_month_num($start_time,$end_time);

        // dd($r);
        // $arr = [];
        // foreach($r as $v){
        //     $arr[] = $v['account'];
        // }
        // dd($arr);

        // $this->switch_tongji_database();
        // $r = $this->t_parent_info->get_openid_list();
        // dd($r);

        // $userid= $this->get_in_str_val('u');

        // $userid  = $userid*10;
        // dd($userid);

        // $ass_openid = $this->t_student_info->get_ass_openid($userid);

        // $check = 1;
        // $send_openid = 'cccc';

        // if(!$ass_openid ){
        //     $send_openid = $this->t_seller_student_new->get_seller_openid($userid);
        //     $check = 2;

        // }

        $ret_info['test_succ_num'] = $this->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功


        $ret_info['seller_invit_month'] = $this->t_test_lesson_subject_require->get_invit_num_for_month($start_time, $end_time); // 销售邀约数[月邀约数]

        // dd($ass_openid." ~ ".$send_openid." ~ ".$check);

        $first_group  = '咨询一部';
        $second_group = '咨询二部';
        $third_group  = '咨询三部';
        $new_group    = '新人营';

        // $start_time = $this->get_in_int_val('s');

        // $new_order_info = $task->t_order_info->get_new_order_money($start_time, $end_time);// 全部合同信息[部包含新签+转介绍]

        // dd($new_order_info);

        $ret_info['one_department']    = $this->t_admin_group_name->get_group_seller_num($first_group,$start_time);// 咨询一部
        $ret_info['two_department']    = $this->t_admin_group_name->get_group_seller_num($second_group, $start_time);// 咨询二部
        $ret_info['three_department']  = $this->t_admin_group_name->get_group_seller_num($third_group, $start_time);// 咨询三部
        $ret_info['new_department']    = $this->t_admin_group_name->get_group_seller_num($new_group, $start_time);// 新人营

        dd($ret_info);
    }





    public function get_month_finish_define_money($seller_groupid_ex,$start_time){
        $task = new \App\Console\Tasks\TaskController();
        $task->t_admin_main_group_name->switch_tongji_database();
        $task->t_admin_group_name->switch_tongji_database();
        $task->t_manager_info->switch_tongji_database();
        $task->t_seller_month_money_target->switch_tongji_database();
        $task->t_admin_group_month_time->switch_tongji_database();
        $arr=explode(",",$seller_groupid_ex);
        $main_type="";
        $up_groupid="";
        $groupid="";
        $adminid="";
        $main_type_list =["助教"=>1,"销售"=>2,"教务"=>3];
        if (isset($arr[0]) && !empty($arr[0])){
            $main_type_name= $arr[0];
            $main_type = $main_type_list[$main_type_name];
        }
        if (isset($arr[1])  && !empty($arr[1])){
            $up_group_name= $arr[1];
            $up_groupid = $task->t_admin_main_group_name->get_groupid_by_group_name($up_group_name);
        }
        if (isset($arr[2])  && !empty($arr[2])){
            $group_name= $arr[2];
            $groupid = $task->t_admin_group_name->get_groupid_by_group_name($group_name);
        }
        if (isset($arr[3])  && !empty($arr[3])){
            $account= $arr[3];
            $adminid = $task->t_manager_info->get_id_by_account($account);
        }

        $month = date("Y-m-01",$start_time);
        $groupid_list = [];
        if($adminid){
            $month_finish_define_money=$task->t_seller_month_money_target->field_get_value_2( $adminid,$month,"personal_money");
        }else{
            if($groupid){
                $groupid_list[] = $groupid;
            }else{
                if($up_groupid){
                    $groupid_list = $task->t_admin_group_name->get_groupid_list_new($up_groupid,-1);
                }else{
                    if($main_type){
                        $groupid_list = $task->t_admin_group_name->get_groupid_list_new(-1,$main_type);
                    }
                }
            }
            $month_finish_define_money=$task->t_admin_group_month_time->get_month_money_by_month( $start_time,$groupid_list);
        }

        return $month_finish_define_money;
    }




    public function genxin(){

        // $a = [

        //     0 => "396007",
        //     1 => "392385",
        //     2 => "313629",
        //     3 => "232777",
        //     4 => "304219",
        //     5 => "224131",
        //     6 => "272284",
        //     7 => "314011",
        //     8 => "312960",
        //     9 => "392152",
        //     10 => "360983",
        //     11 => "273647",
        //     12 => "396086",
        //     13 => "370031",
        //     14 => "299764",
        //     15 => "346151",
        //     16 => "386177",
        //     17 => "329501",
        //     18 => "396441",
        //     19 => "365146",
        //     20 => "270487",
        //     21 => "389440",
        //     22 => "394503",
        //     23 => "283835",
        //     24 => "220702",
        //     25 => "392799",
        //     26 => "371930",
        //     27 => "261234",
        //     28 => "390947",
        //     29 => "395982",
        //     30 => "361018",
        //     31 => "392634",
        //     32 => "392366",
        //     33 => "390139",
        //     34 => "396453",
        //     35 => "214470",
        //     36 => "244159",
        //     37 => "272361",
        //     38 => "238491",
        //     39 => "271365",
        //     40 => "323650",
        //     41 => "396440",
        //     42 => "282950",
        //     43 => "390158",
        //     44 => "396100",
        //     45 => "161962",
        //     46 => "395411",
        //     47 => "347600",
        //     48 => "396362",
        //     49 => "392939",
        //     50 => "397028",
        //     51 => "395218",
        //     52 => "397584",
        //     53 => "392486",
        //     54 => "323232",
        //     55 => "352741",
        //     56 => "288015",
        //     57 => "225743",
        //     58 => "243422",
        //     59 => "276299",
        //     60 => "354105",
        //     61 => "197544",
        //     62 => "284175",
        //     63 => "392923",
        //     64 => "253608",
        //     65 => "344181",
        //     66 => "392474",
        // ];


        // $a = [
        //     0 => "110058",
        //     1 => "110054",
        //     2 => "110053",
        //     3 => "110051",
        //     4 => "110050",
        //     5 => "110048",
        //     6 => "110047",
        //     7 => "110046",
        //     8 => "110045",
        //     9 => "110043",
        //     10 => "110040",
        //     11 => "110036",
        //     12 => "110032",
        //     13 => "110027",
        //     14 => "110026",
        //     15 => "110025",
        //     16 => "110024",
        //     17 => "110023",
        //     18 => "110022",
        //     19 => "110021",
        //     20 => "110014",
        //     21 => "110006",
        //     22 => "110004",
        //     23 => "110003",
        //     24 => "110002",
        //     25 => "109992",
        //     26 => "109991",
        //     27 => "109988",
        //     28 => "109986",
        //     29 => "109985",
        //     30 => "109982",
        //     31 => "109979",
        //     32 => "109976",
        //     33 => "109975",
        //     34 => "109974",
        //     35 => "109973",
        //     36 => "109972",
        //     37 => "109971",
        //     38 => "109970",
        //     39 => "109968",
        //     40 => "109967",
        //     41 => "109966",
        //     42 => "109961",
        //     43 => "109960",
        //     44 => "109959",
        //     45 => "109958",
        //     46 => "109956",
        //     47 => "109955",
        //     48 => "109954",
        //     49 => "109953",
        //     50 => "109952",
        //     51 => "109947",
        //     52 => "109943",
        //     53 => "109942",
        //     54 => "109941",
        //     55 => "109938",
        //     56 => "109937",
        //     57 => "109936",
        //     58 => "109935",
        //     59 => "109933",
        //     60 => "109927",
        //     61 => "109926",
        //     62 => "109906",
        //     63 => "109861",
        //     64 => "109822",
        //     65 => "109811",
        //     66 => "109807",
           
            
        // ];

        $a = [
            '110034','109995','109981','109978','109969','109962','109957','109946','109944','109939','109934','109928','109909','109867',''
        ];

        foreach($a as $item){
            $this->t_student_score_info->field_update_list($item,["admin_type" => 1]);
        }

    }
    

   



}