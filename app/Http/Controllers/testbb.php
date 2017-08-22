<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

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

        $t = $this->get_in_int_val('t',-1);
        dd($t);
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


    public function test_img(){
        // $img = $this->get_in_str_val('img');

        $img = [
            0=>'123.jpg'
        ];
        $ret = $this->img_to_pdf($img);
    }

    public function img_to_pdf($filesnames){
        ini_set("memory_limit",'-1');

        header("Content-type:text/html;charset=utf-8");

        $hostdir = public_path('wximg');

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        foreach ($filesnames as $name) {
            if(strstr($name,'jpg') || (strstr($name,'png') )){//如果是图片则添加到pdf中
                // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
                $pdf->AddPage();//添加一个页面
                $filename = $hostdir.'/'.$name;//拼接文件路径

                //gd库操作  读取图片
                $source = imagecreatefromjpeg($filename);
                //gd库操作  旋转90度
                $rotate = imagerotate($source, 0, 0);
                //gd库操作  生成旋转后的文件放入别的目录中
                // imagejpeg($rotate,$hostdir.'/123/'.$name.'_1.jpg');
                $tmp_name = time().'_'.rand().'jpg';
                imagejpeg($rotate,$hostdir."/$tmp_name.jpg");
                //tcpdf操作  添加图片到pdf中
                // $pdf->Image($hostdir.'\\123\\'.$name.'_1.jpg', 15, 26, 210, 297, 'JPG', '', 'center', true, 300);
                $pdf->Image($hostdir."/$tmp_name.jpg", 15, 26, 100, 100, 'JPG', '', 'center', true, 1000);

            }
        }


        $pdf_name_tmp = time().'_'.rand().'.pdf';
        $pdf_info = $pdf->Output("$pdf_name_tmp", 'I');

        $pdf_name = $hostdir.'/'.time().'_'.rand().'.pdf';
        dd($pdf_name);

        $pdf_file = fopen($pdf_name);
        fwrite($pdf_file,$pdf_info);
        fclose($pdf_file);

        // dd($pdf_file); //输出pdf文件

    }













}