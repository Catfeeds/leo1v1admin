<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class testbb extends Controller
{
    use CacheNick;

    var $check_login_flag = false;
    public function get_msg_num() {
        $bt_str=" ";
        $e=new \Exception();
        foreach( $e->getTrace() as &$bt_item ) {
            //$args=json_encode($bt_item["args"]);
            $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                @$bt_item["file"].":".@$bt_item["line"].
                "<br/>";
        }
        echo $bt_str;

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



    public function test () {

        // date('Y-m-d H:i:s');
        dd(date('Y-m-d h:i:s'));
        $teacherid = $this->get_in_int_val('id');
        $start_time = 1501516800;
        $end_time = 1501516800 + 86400;
        $seller_arr = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($teacherid,$start_time, $end_time);

        $ret = $this->t_lesson_info_b2->get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr);
        dd($ret);


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




    }





    //  向 老师推送老师端版本更新 通知

    public function send_wx_to_update_software(){
        $teacher_list = $this->t_teacher_info->get_teacher_openid_list();
        dd($teacher_list);


        // $
        /**
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
         **/

        $date_time = date("Y-m-d");

        $teacher_list = [
            "wx_openid"=>'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc'
        ];

        foreach($teacher_list as $item){
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data_teacher['first']      = "$date_time";
            $data_teacher['keyword1']   = "【PC老师端4.0.0】";
            $data_teacher['keyword2']   = "
1、新增扩科（扩年级）功能；
2、白板中增加播放视频功能。
下载地址（老师端后台）：<a href='http://www.leo1v1.com/login/teacher'>http://www.leo1v1.com/login/teacher</a>";
            $data_teacher['keyword3']   = "$date_time";

            $data_teacher['remark']     = "更新方法：输入下载地址→点击【下载】→【PC电脑】→【立即下载】";

            \App\Helper\Utils::send_teacher_msg_for_wx($item_teacher,$template_id_teacher, $data_teacher,$url_teacher);

        }

    }




}