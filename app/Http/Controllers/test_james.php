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
                        "picurl"=>"http://admin.leo1v1.com/article_wx/leo_teacher_new_teacher_deal_question"
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

    public function jilu(){

        $str="http://www.jb51.net";  //定义字符串
        $result=urlencode($str);   //对指定字符串编码
        echo $result;  //输出结果

        header("Location:");

        return;


        $test = $this->get_in_int_val('test');

        header("Loaction ");

        if($test == 1){
            // 存入数据库
        }


        /*








         */

        dd($test);
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


    public function dd(){
        $type = $this->get_in_int_val('p_type');
        $num = $this->get_in_int_val('n');
        $validity_time = strtotime($this->get_in_str_val('t'));
        $stu_type = $this->get_in_int_val('s');
        $i = 1;
        for($i;$i<=$num;$i++){
            $this->t_ruffian_activity->row_insert([
                "validity_time" => $validity_time ,
                "prize_type"   => $type,
                "create_time" =>  time(),
                "stu_type" => $stu_type
            ]);
        }

        /*

          http://admin.leo1v1.com/test_james/dd?p_type=3&n=80&s=1&t=2017-11-7
          http://admin.leo1v1.com/test_james/dd?p_type=5&n=10&s=1&t=2017-11-7

         */

        exit;

        $end_time = strtotime('2017-11-1');
        $start_time = strtotime('2017-10-1');

        $ret_info['order_sign_month'] = $this->t_order_info->get_order_sign_month($start_time, $end_time); // 合同人数[月签约率]

        dd($ret_info);
        $ret_info['order_trans_month'] = $this->t_order_info->get_order_trans_month($start_time, $end_time); // 合同人数[月试听转化率]

        dd($ret_info);
        $new_order_info = $this->t_order_info->get_new_order_money($start_time, $end_time);// 全部合同信息[部包含新签+转介绍]

        dd($new_order_info);
        $ret_info['seller_test_succ_month'] = $this->t_lesson_info_b3->get_test_succ_for_month($start_time, $end_time); // 试听成功数[月到课率]

        dd($ret_info);



        $ruffian_money = $this->t_luck_draw_yxyx_for_ruffian->get_ruffian_money(211);
        if(!$ruffian_money){
            $ruffian_money = 0;
        }

        dd($ruffian_money);


        $a  = floor((5 - 20*4)/5)>0?floor((5 - 20*4)/5):0;

        dd($a);
        dd(floor(-75/5));

        $start_time = $this->get_in_int_val('s');
        $end_time = $this->get_in_int_val('e');
        $ret_info['seller_schedule_num_month'] = $this->t_test_lesson_subject_require->get_seller_schedule_num_month($start_time, $end_time); // 教务已排课['月排课数']

        dd($ret_info);
    }







}