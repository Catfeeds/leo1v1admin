<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use Qiniu\Auth;

use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

use App\Jobs\deal_pdf_to_image;

require_once  app_path("/Libs/Qiniu/functions.php");

class test_bacon extends Controller
{
    use CacheNick;

    public function __construct(){
        parent::__construct();
    }

    public function get_power(){
        $file = $this->get_in_str_val('file');
        $data = $this->get_in_str_val('data');
        if($data){
            $this->download_power($data,$file);
        }
        //$file = $file.'.blade.php';
        //$file = $file.'.html';
        return view("test_bacon.".$file);  

    }

    public function download_power($data,$file) {
        //$data = $this->get_in_str_val('data');
        $xls_data= json_decode($data,true);
        //dd($xls_data);
        if(!is_array($xls_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
             ->setLastModifiedBy("jim")
             ->setSubject("jim subject")
             ->setDescription("jim Desc")
             ->setKeywords("jim key")
             ->setCategory("jim  category");

        //dd($xls_data);

        foreach( $xls_data as $index=> $item ) {
            $index_str = $index+1;
            switch($item['level'])
            {
            case 1:
                $hang = 'A'.$index_str;
                break;
            case 2:
                $hang = 'B'.$index_str;
                break;
            case 3:
                $hang = 'C'.$index_str;
                break;
            case 4:
                $hang = 'D'.$index_str;
                break;
            case 5:
                $hang = 'E'.$index_str;
                break;
            case 6:
                $hang = 'F'.$index_str;
                break;
            case 7:
                $hang = 'G'.$index_str;
                break;
            case 8:
                $hang = 'H'.$index_str;
                break;
            case 9:
                $hang = 'I'.$index_str;
                break;
            case 10:
                $hang = 'J'.$index_str;
                break;

            default:
                $hang = 'A'.$index_str;
                break;

            };
            $objPHPExcel->getActiveSheet()->setCellValue($hang, @$item['name']);
        }

        //$objPHPExcel->getActiveSheet()->setCellValue('A1','haode');

        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function test_img(){
        $wx_openid = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $phone = 18898881852;
        $agent = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $agent['wx_openid'] = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $agent['phone'] = 18898881852;
        $agent['id'] = 45;
        //$phone = uniqid();
        $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";

        $request = ['fromusername'=>'tset'];

        // \App\Helper\Utils::wx_make_and_send_img($bg_url,$qr_code_url,$request,$agent,1);
        $task=new \App\Jobs\make_and_send_wx_img($wx_openid,$bg_url,$qr_code_url,$request,$agent);
        $task->handle();

    }

    public function test_lang(){
        $nonce = rand(100000,999999);
        echo $nonce;
        echo '<br/>';

        $current = time();
        echo $current;
        echo '<br/>';

        $text = "关于电流，下列说法中正确的是（&nbsp;&nbsp）<br/>A．电流是正电荷沿一定方向移动形成的<br/>B．电流是电荷沿一定方向移动形成的<br/>C．电流是负电喝沿一定方向移动形成的<br/>D．物理学规定，电荷定向移动的方向为电留方向";
        //$text = "睡交吃饭";

        $secretKey = 'DSSDjzz4tSlEj0yd2ViRzqPjngLsQi2E';
        $srcStr = 'GETwenzhi.api.qcloud.com/v2/index.php?Action=LexicalCheck&Nonce='.$nonce.'&Region=ap-shanghai&SecretId=AKIDaqpY359OgjUzFGniiVnGa0TwoiN0nvqL&SignatureMethod=HmacSHA256&Timestamp='.$current.'&text='.$text;
        //"&SignatureMethod=HmacSHA256";
        echo $srcStr;
        echo '<br/>';

        $signStr = base64_encode(hash_hmac('sha256', $srcStr, $secretKey, true));
        //$signStr = urlencode($signStr);
        echo $signStr;
        echo '<br/>';

        $checkUrl = "https://wenzhi.api.qcloud.com/v2/index.php";
        $data = [
            'Action' => 'LexicalCheck',
            'Nonce' => $nonce,
            'Region' => 'ap-shanghai',
            'SecretId' => 'AKIDaqpY359OgjUzFGniiVnGa0TwoiN0nvqL',
            'Timestamp' => $current,
            'SignatureMethod' => 'HmacSHA256',
            'Signature' => $signStr,
            'text'  => $text
        ];

        $jsonDataEncoded = json_encode($data);
        echo $jsonDataEncoded;
        echo '<br/>';
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $checkUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        //打印获得的数据
        dd($output);

    }

    public function test_ajax_more(){
        return $this->Pageview(__METHOD__); 
    }

    //
    public function import_power(){
        set_time_limit(3600); 
        $user_power = $this->t_manager_info->get_all_users();
        //dd($user_power);
        $all = count($user_power);
        if($user_power){
            foreach( $user_power as $user){
                $per_arr = $user['permission'] != '' ? explode(',',$user['permission']) : [];
                //print_r($per_arr);
                foreach($per_arr as $per){
                    if($per != ''){
                        $data = [
                            'uid' => $user['uid'],
                            'gid' => $per
                        ];
                        if(!$this->t_user_power_group->is_user_power_exit($user['uid'],$per)){
                            $this->t_user_power_group->row_insert($data);
                            echo "<font style='color:red'>".$user['uid']." 权限：".$per." 添加完毕"."</font>";
                            echo "<br/>";
                        }else{
                            echo $user['uid']." 权限：".$per." 已添加";
                            echo "<br/>";
                        }
                       
                    }

                }
              
            }
        }
    }

    public function luru_tag(){
        set_time_limit(3600);
        $data = \App\Helper\Utils::get_sub_grade_tag(-1,-1,true);
        foreach($data as $sub=>$item){
          
            foreach($item as $grade=>$var){
               
                foreach($var as $v){
                    $insertData = [
                        "subject"=>$sub,
                        "grade"  =>$grade,
                        "tag"    =>$v,
                        "bookid" =>50000,
                        "del_flag"=>0
                    ];
                    //$this->t_sub_grade_book_tag->row_insert($insertData);
                    //print_r($insertData);
                }
            }
        }
        dd($data);
    }

    public function modify_resource(){
        set_time_limit(3600);
        $data = $this->t_resource->get_resource_type_all();
        if($data){
            foreach($data as $var){
                $old_tag_arr = \App\Helper\Utils::get_sub_grade_tag($var['subject'],$var['grade']);
                $old_tag = @$old_tag_arr[$var['tag_four']];
                //print_r($old_tag_arr);
                $new_tag_id = $this->t_sub_grade_book_tag->get_id($var['subject'],$var['grade'],$old_tag);
                if($new_tag_id && !empty(@$new_tag_id['id'])){
                    //print_r($new_tag_id);
                    $up_data = ['tag_four'=>$new_tag_id['id']];
                    //$this->t_resource->field_update_list($var['resource_id'],$up_data);
                }
            }
        }
        dd($data);
    }

    public function modify_res_agree_info(){
        set_time_limit(36000);
        $num = $this->get_in_str_val('num');
        $num = 2000*$num + 1;
        //$data = $this->t_resource->get_resource_type_all();
        $data = $this->t_resource_agree_info->get_agree_resource_num($num);
        if($data){
            foreach($data as $var){
                if( ( $var['tag_four'] < 5 && $var['subject'] != 5 ) || ( $var['tag_four'] < 7 && $var['subject'] == 5 )){
                    $old_tag_arr = \App\Helper\Utils::get_sub_grade_tag($var['subject'],$var['grade']);
                    $old_tag = @$old_tag_arr[$var['tag_four']];
                    //print_r($old_tag_arr);
                    $new_tag_id = $this->t_sub_grade_book_tag->get_id($var['subject'],$var['grade'],$old_tag);
                    if($new_tag_id && !empty(@$new_tag_id['id'])){
                        //print_r($new_tag_id);
                        $up_data = ['tag_four'=>$new_tag_id['id']];
                        //$this->t_resource_agree_info->field_update_list($var['agree_id'],$up_data);
                    }

                }
            }
            dd($num);
        }else{
            dd('完事'.$num);
        }
    }

    public function change_tag(){
        set_time_limit(36000);
        $biao_tag = $this->t_sub_grade_book_tag->get_biaozun(50000,1);
        if($biao_tag){
            $data = ['resource_type'=>3];
            foreach( $biao_tag as $var){
                if($var['resource_type'] != 3){
                    //$this->t_sub_grade_book_tag->field_update_list($var['id'],$data);
                }
            }
        }

        $jin_tag = $this->t_sub_grade_book_tag->get_biaozun(50000,0);
        if($jin_tag){
            $data2 = ['resource_type'=>1,'season_id'=>4];
            foreach( $jin_tag as $item){
                if($item['resource_type'] != 1 || $item['season_id'] != 4){
                    //$this->t_sub_grade_book_tag->field_update_list($item['id'],$data2);
                }
            }
        }
        dd($jin_tag);
    }

    public function change_resource(){
        // $this->t_resource_file_evalutation->row_insert([
        //     "file_id"          => 59,
        //     "teacherid"        => 663,
        //     "add_time"         => time(NULL),
        //     "resource_type"    => 1,

        //     "quality_score"    => 5,
        //     "help_score"       => 5,
        //     "overall_score"    => 4,
        //     "detail_score"     => 4,
        //     "size"             => 2,
        //     "gap"              => 2,
        //     "bg_picture"       => 3,
        //     "text_type"        => 2,
        //     "answer"           => 2,
        //     "suit_student"     => 1,
        //     "time_length"      => '90分钟',
        // ]);

        
    }
}
