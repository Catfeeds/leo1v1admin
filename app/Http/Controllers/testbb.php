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

        $date_time = date("Y-m-d");

        $url_teacher = "";

        foreach($teacher_list as $item){
            // dispatch( new \App\Jobs\send_wx_to_teacher_for_update_software( $item['wx_openid']));
        }

    }

    public function get_data_for_qc(){
        // $s = 1501516800;
        // $e = 1502726400;
        $s = $this->get_in_int_val('s');
        $e = $this->get_in_int_val('e');

        $this->t_lesson_info_b2->switch_tongji_database();
        $ret = $this->t_lesson_info_b2->get_data_for_qc($s,$e);


        // 导出excel数据


        // $name = '试听课未评价数据';
        // $this->push($ret,$name);
        dd($ret);
    }

    public function push($data,$name='试听课未评价数据'){
        $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("试听课未评价数据")
             ->setLastModifiedBy("试听课未评价数据")
             ->setTitle("数据EXCEL导出")
             ->setSubject("数据EXCEL导出")
             ->setDescription("备份数据")
             ->setKeywords("excel")
             ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        foreach($data as $k => $v){
            $num=$k+1;
            $objPHPExcel->setActiveSheetIndex(0)
                 //Excel的第A列，uid是你查出数组的键值，下面以此类推
                 ->setCellValue('A'.$num, $v['lessonid'])
                 ->setCellValue('B'.$num, $v['seller_name'])
                 ->setCellValue('C'.$num, $v['stu_nick'])
                 ->setCellValue('D'.$num, $v['tea_name']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('试听课未评价数据');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


        $objWriter->save('php://output');


        // $objWriter->save(public_path()."/wximg/试听课未评价数据.xls");
        exit;
    }


}