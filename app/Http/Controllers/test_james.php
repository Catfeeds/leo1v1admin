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

        //$this->t_teacher_info->
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

    public function get_file_url()
    {
        $file_url = $this->get_in_str_val('url');
        // 构建鉴权对象
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }



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


    public function ssss(){

        $start_time = $this->get_in_int_val('s');
        $end_time = $this->get_in_int_val('e');

        $re1    = $this->t_admin_group_name->get_entry_month_num($start_time,$end_time);// 入职完整月人数

        $re2 = $this->t_order_info->get_new_order_money($start_time, $end_time);
        $a = [];
        $g = [];


        foreach($re1 as $v){
            $a[] = $v['account'];
        }


        foreach($re2 as $v){
            $g[] = $v['account'];
        }


        // $f = array_diff($a,$g);
        // $f = array_diff($g,$a);
        $n1 = count($re1);
        $n2 = count($re2);
        echo $n1." ~ ".$n2;

        // dd($f);


        // $a = ['胡怀春'];

        $b = [
            "胡怀春",
            '郑亚慧',
            '李沂键',
            '林学平',
            '秦盛昊',
            '朱海宏',
            '朱雪见',
            '葛贤松',
            '王倩',
            '陈绩',
            '陈梦',
            '田羽婵',
            '罗飞',
            '宋宏义',
            '夏杰',
            '张宏斌',
            '李颂',
            '裴江蓝',
            '谭方',
            '康文龙',
            '李丹',
            '陈文恺',
            '张宇',
            '杨玉萍',
            '雷江博',
            '李圆芳',
            '池善烟',
            '葛云',
            '陈德麒',
            '刘苹',
            '肖巧',
            '蔡明霞',
            '王春雷',
            '叶宇晨',
            '赵蓉',
            '钟艾珈',
            '施健',
            '范玉辉',
            '何心',
            '倪姣',
            '欧翔',
            '王亚臣',
            '杨毅',
            '田鹏程',
            '胡月月',
            '张维达',
            '吴雨',
            '熊昌隆',
            // '朱珈儀',
            '朱珈儀（朱薇敏)',
            '李承汐',
            '陈育洁',
            '孙佩华',
            '刘佳丽',
            '邵安泽',
            '赵云',
            '范建新',
            '杨玉玉',
            '覃秋燕',
            '赵明',
            '栗成君',
            '冉超',
            '邓然',
            '张珂珂',
            '王小卫',
            '尹娟',
            '吴俊男',
            '马张艳',
            '詹坤',
            '李根强',
            '姚浩',
            '范晓恩',
            '陈同',
            '陈茜',
            '刘英菲',
            '温绍玲',
            '王扣',
            '赵世勇',
            '卓雨晨',
            '杨金秋',
            '王琦',
            '王娟娟',
            '吴梅',
            '乔朋飞',
            '刘晓',
            '唐嘉彬',
            '夏敏',
            '张盼红',
            '罗超群',
            '张行蠃',
            '潘婧',
            '余丹',
            '付伟',
            '杨翠翠',
            '单世鼎',
            '李丹2',
            '王云',
            '陈祖炎',
            '杨盼盼',
            '郭毅恒',

        ];

        $c = array_diff($a,$b);
        dd($c);

        // dd(session_id());

        $lessonid = $this->get_in_int_val('p');

        $wx_openid_arr = $this->t_lesson_info_b2->get_seller_wx_openid($lessonid);

        dd($wx_openid_arr);

        $parentid = $this->get_in_int_val('p');
        $lessonid = $this->get_in_int_val('l');
        $type = $this->get_in_int_val('y');

        if($type == 0){
            $lesson_type_str = '常规课';
            $type_str = "0,1,3";
        }elseif($type == 2){
            $type_str = "2";
            $lesson_type_str = '试听课';
        }else{
            $lesson_type_str = '';
        }

        $ret_list=$this->t_lesson_info_b2->get_list_by_parent_id($parentid,$lessonid=-1,$type_str);


        // $ret_list=$this->t_lesson_info_b2->get_list_by_parent_id($parentid,$lessonid=-1,$type);

        dd($ret_list);

        $this->switch_tongji_database();
        // $parent_list = $this->t_parent_info->get_openid_list();

        // dd(count($parent_list));



        $ret = $this->t_lesson_info_b3->get_test_lesson_succ_num($start_time, $end_time); // 试听成功

        $a = [];

        foreach($ret as $v){
            $a[] = $v['lessonid'];
        }

        dd($a);
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



    public function send_wx_msg(){
        $wx = new \App\Helper\Wx();

        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';

        $openid = 'orwGAs_IqKFcTuZcU1xwuEtV3Kek';

        $data_leo = [
            'first'    => "测试 first",
            'keyword1' => "keyword1",
            'keyword2' => "keyword2",
            'keyword3' => "keyword3",
            'remark'   => "测试信息!"
        ];

        $url_leo = 'http://admin.leo1v1.com/test_james/jilu?test=1';

        urldecode();

        $wx->send_template_msg($openid, $parent_template_id, $data_leo, $url_leo);



    }



    public function power_group_edit() {
        $group_list = $this->t_authority_group->get_auth_groups();
        $default_groupid = 0;
        if (count($group_list)>0) {
            $default_groupid= $group_list[0]["groupid"];
        }
        $groupid  = $this->get_in_int_val("groupid",$default_groupid);
        $show_flag= $this->get_in_int_val("show_flag", -1);
        $user_list=[];
        $user_list=$this->t_manager_info->get_power_group_user_list($groupid);
        if ($show_flag!=2) { //只用户
            $power_map=$this->t_authority_group->get_auth_group_map($groupid);
            $list=$this->get_menu_list($power_map );

            $n=["k1"=>"","k2"=>"","k3"=>"" ];
            $n["k1" ]= "其它";
            $n["pid" ]= 0;
            $k1_class= $this->gen_class(1);
            $n["k_class" ]= $k1_class;
            $n["class" ]=  "l_1 $k1_class " ;
            $n["level" ]=  "1" ;
            $n["folder" ]=  true;
            $n["has_power_flag" ]= "" ;
            $list[]=$n;

            foreach (E\Epower::$desc_map as $k=> $v) {
                $n=["k1"=>"----","k2"=>"","k3"=>"" ];
                $k2_pid=$k;
                $n["k2" ]= $v ;
                $n["pid" ]= $k2_pid;
                $k2_class= $this->gen_class(2);
                $n["k_class" ]= $k2_class;
                $n["class" ]= "l_2 $k1_class $k2_class";
                $n["level" ]=  "2" ;
                $n["folder" ]=  false;
                $n["has_power_flag" ]= isset($power_map["$k2_pid"])?"checked":"" ;
                $list[]=$n;
            }
            $ret_info=\App\Helper\Utils::list_to_page_info($list);
        }else{
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }

        // dd($ret_info);

        return $this->Pageview(__METHOD__,$ret_info,[
            "group_list"=>$group_list,
            "user_list"=>$user_list,
        ]);
    }


    public function get_win_rate($stu_type,$parentid){ // 获取中奖概率
        $rate   = mt_rand(1,10000);
        $today  = time();
        $eleven = strtotime('2017-11-11');
        $prize_type = 0; // 奖品类型

        /**
           array(1,"","书包" ),
           array(2,"","10元折扣券" ),
           array(3,"","50元折扣券" ),
           array(4,"","100元折扣券" ),
           array(5,"","300元折扣券" ),
           array(6,"","500元折扣券" ),
           array(7,"","免费3次正式课" ),
           array(8,"","试听课" ),
        **/

        if($stu_type == 1){ // 新用户
            if($today < $eleven){
                if($rate>1000 && $rate<=2000){ // 书包 10
                    $prize_type=1;
                }elseif($rate>2000 && $rate<=3000){ // 50元折扣券  10
                    $prize_type=3;
                }elseif($rate>3000 && $rate<=3375){ // 100元折扣券 3.75
                    $prize_type=4;
                }elseif($rate>4000 && $rate<=4125){ // 300元折扣券 1.25
                    $prize_type=5;
                }elseif($rate>5000 && $rate<=5013){ // 3次免费课程 0.13
                    $prize_type=7;
                }
            }else{
                if($rate>1000 && $rate<=2500){ // 书包 12.5
                    $prize_type=1;
                }elseif($rate>3000 && $rate<=4250){ // 50元折扣券  12.5
                    $prize_type=3;
                }elseif($rate>100 && $rate<=725){ // 100元折扣券 6.25
                    $prize_type=4;
                }elseif($rate>5000 && $rate<=5250){ // 300元折扣券 2.5
                    $prize_type=5;
                }elseif($rate>6000 && $rate<=6013){ // 500元折扣券 0.13
                    $prize_type=6;
                }elseif($rate>7000 && $rate<=7025){ // 3次免费课程 0.25
                    $prize_type=7;
                }
            }
        }elseif($stu_type==2){ //老用户
            if($today < $eleven){
                if($rate>100 && $rate<=150){ // 书包 0.5
                    $prize_type=1;
                }elseif($rate>500 && $rate<=1000){ // 50元折扣券  5
                    $prize_type=3;
                }elseif($rate>1000 && $rate<=1100){ // 100元折扣券 1
                    $prize_type=4;
                }elseif($rate>1500 && $rate<=1530){ // 300元折扣券 0.3
                    $prize_type=5;
                }elseif($rate>5000 && $rate<=5010){ // 3次免费课程 0.1
                    $prize_type=7;
                }
            }else{
                if($rate>100 && $rate<=200){ // 书包 10
                    $prize_type=1;
                }elseif($rate>500 && $rate<=1000){ // 50元折扣券  5
                    $prize_type=3;
                }elseif($rate>1000 && $rate<=1100){ // 100元折扣券 1
                    $prize_type=4;
                }elseif($rate>5000 && $rate<=5030){ // 300元折扣券 0.3
                    $prize_type=5;
                }elseif($rate>6000 && $rate<=6010){ // 500元折扣券 0.10
                    $prize_type=6;
                }elseif($rate>7000 && $rate<=7020){ // 3次免费课程 0.2
                    $prize_type=7;
                }
            }
        }
        return $prize_type;
    }






    public function upload_subject_grade_textbook_from_xls(){ // 测试区
        $file = Input::file('file');
        $list    = E\Eregion_version::$desc_map;
        $list_new =[];
        foreach($list as $k=>$i){
            $list_new[$i] = $k;
        }
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0 || $k==1 || $k==2){
                    unset($arr[$k]);
                }
            }
            foreach($arr as $item){
                $small = $item[2];
                $small_arr = explode("、",$small);
                $small_list=[];
                foreach($small_arr as $v){
                    if(isset($list_new[$v])){
                        $small_list[] = $list_new[$v];
                    }
                }

                $small_str =  implode(",",$small_list);

                // $is_exist3 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],100,2);
                $is_exist3=0;
                if($is_exist3>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist3,[
                        "teacher_textbook" =>$small_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>100,
                        "teacher_textbook"=>$small_str,
                        // "educational_system" =>$item[2]
                    ]);
                }

                $middle = $item[3];
                $middle_arr = explode("、",$middle);
                $middle_list=[];
                foreach($middle_arr as $v){
                    if(isset($list_new[$v])){
                        $middle_list[] = $list_new[$v];
                    }
                }

                $middle_str =  implode(",",$middle_list);

                //$is_exist = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],200,2);
                $is_exist=0;
                if($is_exist>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist,[
                       "teacher_textbook" =>$middle_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>200,
                        "teacher_textbook"=>$middle_str,
                        // "educational_system" =>$item[2]
                    ]);
                }


                $senior = $item[4];
                $senior_arr = explode("、",$senior);
                $senior_list=[];
                foreach($senior_arr as $v){
                    if(isset($list_new[$v])){
                        $senior_list[] = $list_new[$v];
                    }
                }
                $senior_str =  implode(",",$senior_list);
                // $is_exist2 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],300,2);
                $is_exist2=0;
                if($is_exist2>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist2,[
                        "teacher_textbook" =>$senior_str
                    ]);

                }else{

                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>300,
                        "teacher_textbook"=>$senior_str,
                        // "educational_system" =>$item[2]
                    ]);
                }



            }


            //dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }



    public function download_xls ()  { // 测试
        // $xls_data= session("xls_data" );
        $xsl_data = '
[["Field","Type","Collation","Null","Key","Default","Extra","Privileges","Comment"],["id","int(10) unsigned","","NO","PRI","","auto_increment","select,insert,update",""],["parentid","int(11)","","NO","MUL","","","select,insert,update","家长id"],["get_prize_time","varchar(255)","latin1_bin","NO","MUL","","","select,insert,update","领奖时间"],["presenterid","int(11)","","NO","MUL","","","select,insert,update","发奖人"],["prize_time","int(11)","","NO","","","","select,insert,update","抽奖时间"],["stu_type","tinyint(4)","","NO","","","","select,insert,update","学员类型 1:新用户 2:老用户"],["create_time","int(11)","","NO","","","","select,insert,update","后台奖品录入时间"],["validity_time","int(11)","","NO","","","","select,insert,update","有效期"],["to_orderid","int(11)","","NO","MUL","","","select,insert,update","合同id"],["prize_type","int(11)","","NO","","","","select,insert,update","ruffian_prize_type 枚举类"]]
';

        $xsl_data = json_decode($xsl_data,true);


        if(!is_array($xsl_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
                             ->setLastModifiedBy("jim")
                             ->setTitle("jim title")
                             ->setSubject("jim subject")
                             ->setDescription("jim Desc")
                             ->setKeywords("jim key")
                             ->setCategory("jim  category");

        $objPHPExcel->setActiveSheetIndex(0);

        $col_list=[
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T", "U","V","W","X","Y","Z"
            ,"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ"
            ,"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"
            ,"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ"

        ];

        foreach( $xsl_data as $index=> $item ) {
            foreach ( $item as $key => $cell_data ) {
                $index_str = $index+1;
                $pos_str   = $col_list[$key].$index_str;
                $objPHPExcel->getActiveSheet()->setCellValue( $pos_str, $cell_data);
            }
        }

      $date=\App\Helper\Utils::unixtime2date (time(NULL));
      header('Content-type: application/vnd.ms-excel');
      header( "Content-Disposition:attachment;filename=\"$date.xlsx\"");

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      $objWriter->save('php://output');
    }


    public function check_file(){
        $filename = "D:\\296.mid";

        $arr = explode('.', $filename);

        dd($arr);

        $file = fopen($filename, "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("c2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        dd($typeCode);
        switch ($typeCode)
        {
        case 7790:
            $fileType = 'exe';
            break;
        case 7784:
            $fileType = 'midi';
            break;
        case 8297:
            $fileType = 'rar';
            break;
        case 255216:
            $fileType = 'jpg';
            break;
        case 7173:
            $fileType = 'gif';
            break;
        case 6677:
            $fileType = 'bmp';
            break;
        case 13780:
            $fileType = 'png';
            break;
        default:
            echo 'unknown';
        }
        echo 'this is a(an) '.$fileType.' file:'.$typeCode;
    }


    public function deal_untreated_pdf(){// 处理未成功pdf文件
        $num = $this->get_in_int_val('n',-1);
        $limit_time = $this->get_in_int_val('time',-1);

        $pdf_list = $this->t_pdf_to_png_info->get_untreated_pdf($num,$limit_time);

        foreach($pdf_list as $v){
            $this->set_in_value("pdf_url", $v['pdf_url']);
            $this->set_in_value("lessonid", $v['lessonid']);

            $this->get_pdf_url();

            $this->t_pdf_to_png_info->field_update_list($v['id'], [
                "id_do_flag" => 1
            ]);
        }
    }

    public function ceshi(){

        $limit_time = strtotime(date('Y-m-1'));

        dd(date('Y-m-d',$limit_time+6*86400));
        dd($limit_time);
        $a = " https://fms.ipinyou.com/5/17/9E/0A/F001Nl1Q1NRQ000dMKdg.jpg";

        $filesize=filesize('/home/james/admin_yb1v1/public/wximg/13818837473_2.png');
        dd($filesize);
    }



    public function wx_news(){ // 使用客服接口发送消息
        //使用客服接口发送消息
        // $txt_arr = [
        //     'touser'   => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',// james
        //     'msgtype'  => 'news',
        //     "news"=>[
        //         "articles"=> [
        //             [
        //                 "title"=>"TEST MSG",
        //                 "description"=>"Is Really A Happy Day",
        //                 "url"=>"https://mmbiz.qlogo.cn/mmbiz_jpg/cBWf565lml4NcGMWTiaeuDmWsUQpXz8TPJzfbsoUENe9dKqPKDXPZa7ITPCKvQiaVzmAvLBKPYmrhKNg2AkwwkVQ/0?wx_fmt=jpeg",
        //                 "picurl"=>"http://admin.leo1v1.com/article_wx/leo_teacher_new_teacher_deal_question"
        //             ]
        //         ]
        //     ]
        // ];


        //使用客服接口发送消息
        $txt_arr = [
            'touser'   => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',// james
            'msgtype'  => 'text',
            "text"=>[
                "content"=>"Hello World <a  onclick='alert('你已经点击了我！');' >百度</a>"
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


    public function test_wx(){
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data = [
            "first"     => "微信 老师推送测试",
            "keyword1"  => "\<a href='https://baidu.com \>1\<\/a\>",
            "keyword2"  => "微信推送测试 ".'<a onclick="alert("你已经点击了我！");" >百度</a>',
            "keyword3"  => date('Y-m-d H:i:s'),
        ];
        $openid = "oJ_4fxPmwXgLmkCTdoJGhSY1FTlc";
        $url    = "http://admin.leo1v1.com/test_james/wx_news";

        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
    }




    /**
     * @ 测试 文件上传
     * @ 已为您生成SDK测试账号，token：bbcffc83539bd9069b755e1d359bc70a，其权限与微演示账户michael@leoedu.com相同。
     * @ 使用方法请参看 http://ts.whytouch.com/help.php#dev
     * @ 测试期间请勿上传过量文件，以免影响系统正常运行，否则客服人员可能暂停或关闭本测试账户。
     * @ michael@leoedu.com 密码 ： 021130
     * @gf5090e8e98978bfbf0e3e074593ade[cq161]
     * @ g9029ce6062262c6fd33a4bb38956ac8 //uuid [test.pdf]
     * @ curl -F doc=@'/home/ybai/test.pdf' 'http://ts.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=James&f n=新文件.pptx'
     **/
    public function translate_pdf(){
        $path = $this->get_in_str_val('path');
        $cmd  = "curl -F doc=@'$path' 'http://ts.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=James&fn=新文件.pptx'";
        $uuid = exec($cmd);
        dd($uuid);
    }

    public function ce(){
        $unbound_list = $this->t_teacher_info->get_unbound_teacher_list();

        dd($unbound_list);

        dd($lesson_start);
        $a = "﻿﻿ok:gb6c18f0de819d61b4d33ab0d3e6cce8";
    }


    /**
     * @ 叶老师 试听课
     **/
    public function update_lesson_list(){
        $start_time = $this->get_in_int_val("s");
        $end_time   = $this->get_in_int_val("e");
        $type = $this->get_in_int_val('type',-1);
        $lesson_list = $this->t_lesson_info_b3->get_unlesson($start_time, $end_time,$type);

        foreach($lesson_list as $item){
            $this->t_lesson_info_b3->field_update_list($item['lessonid'], [
                "lesson_user_online_status"=>2
            ]);
        }
        dd($lesson_list);
    }


    public function get_lesson_list(){
        $start_time = $this->get_in_int_val("s");
        $end_time   = $this->get_in_int_val("e");
        $type = $this->get_in_int_val('type',-1);
        $lesson_list = $this->t_lesson_info_b3->get_unlesson($start_time, $end_time,$type);
        dd($lesson_list);
    }

    public function ddd(){
        $lessonid    = $this->get_in_int_val("s");
        $lesson_type = $this->get_in_int_val("e");
        $is_fail = $this->t_lesson_info_b3->check_is_fail($lessonid,$lesson_type);

        dd($is_fail);
    }


    public function get_check_data(){
        $start_time = time();
        $end_time   = $start_time+60;

        $lesson_list = $this->t_lesson_info_b3->get_common_list($start_time,$end_time);

        foreach($lesson_list as $i=>$item){
            $not_first = $this->t_lesson_info_b3->check_not_first_lesson($item['userid'],$item['teacherid'],$item['subject'],$item['lesson_start']);

            if($not_first == 1){
                unset($lesson_list[$i]);
            }
        }

        foreach($lesson_list as &$item){
            $item['lesson_count_str'] = $item['lesson_count']/100;
            $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            $item['tea_nick'] = $this->cache_get_teacher_nick($item['teacherid']);
        }

        dd($lesson_list);
    }

    /**
     * @ 学生和老师同时在教室的时长超过20分钟则认为课程有效
     * @ 否则 将该课程标记 通知人工进行审查
     **/
    public function check_lesson_status(){
        $lessonid = $this->get_in_int_val('lessonid');

        $userid = $this->t_lesson_info_b3->get_userid($lessonid);
        $teaid  = $this->t_lesson_info_b3->get_teacherid($lessonid);

        $login_log_stu = $this->t_lesson_opt_log->get_stu_log($lessonid,$userid);
        $login_log_tea = $this->t_lesson_opt_log->get_stu_log($lessonid,$teaid);


    }

    /**
     * @ 百度语音识别
     **/

    function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);

        return $data;
    }

    /**
       App ID: 10485794

       API Key: sViPNnv5bUMEEHwC6FMriOOw

       Secret Key: 1e3f8259f26c7b5b202ec6c380b3de20


       string(585) "{"access_token":"24.17dde2e70ba5d12d9217220c62a95853.2592000.1515234816.282335-10485794","session_key":"9mzdCrO0iPQ\/XaC9XIeUd7o2tWb7JeLPwCHucfWyV6psR7MP+WceUG\/4AiEyFHExgiX1xtU\/zzpvH+vyHpjfcO21RfpfAQ==","scope":"public audio_voice_assistant_get wise_adapt lebo_resource_base lightservice_public hetu_basic lightcms_map_poi kaidian_kaidian ApsMisTest_Test\u6743\u9650 vis-classify_flower bnstest_fasf lpq_\u5f00\u653e","refresh_token":"25.9fb11b09ef6d52e2c7b8e1c437dbf801.315360000.1828002816.282335-10485794","session_secret":"06a483d75528389b43907dcb2ea66ba4","expires_in":2592000} "
     */

    public function get_baidu_token(){
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'sViPNnv5bUMEEHwC6FMriOOw';
        $post_data['client_secret'] = '1e3f8259f26c7b5b202ec6c380b3de20';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = $this->request_post($url, $post_data);

        var_dump($res);

    }


    public function chang_wen(){
        $url = "http://vop.baidu.com/server_api";

        $path = '/home/ybai/16k.wav';
        $fp = fopen($path, 'rb');  // 以二进制形式打开文件
        $content = fread($fp, filesize($path)); // 读取文件内容
        fclose($fp);
        $content = base64_encode($content); // 将二进制信息编码成字符串

        $content = str_replace("\n"," ",$content);

        $post_data = [
            "format"=>"wav",
            "rate"=>16000,
            "channel"=>1,
            "token"=>"24.17dde2e70ba5d12d9217220c62a95853.2592000.1515234816.282335-10485794",
            "cuid"=>"baidu_workshop122xuejijams",
            "len"=>127,
            "lan" => "zh",
            "speech"=>"$content",

            // "url" => "http://speech-doc.gz.bcebos.com/rest-api-asr/public_audio/16k.wav",
            // "callback" => "http://admin.leo1v1.com/test_james/get_post"

        ];

        $post_data = json_encode($post_data);



        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: '.strlen($post_data)
        ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $ret_arr = json_decode($output,true);

        return $ret_arr;
    }

    public function get_post(){
        $a = $_SESSION;
        dd($a);
    }

    public function get_no(){
        $a = $this->t_lesson_info_b2->get_need_late_notic();
        $b = $this->t_lesson_info_b2->get_test_lesson_to_notic();
        dd($b);
    }



}
