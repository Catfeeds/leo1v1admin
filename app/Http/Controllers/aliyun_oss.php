<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;
use App\Http\Requests;
use Request;
class aliyun_oss  extends Controller
{
    use CacheNick;
    use TeaPower;

    var $oss;
    public function __construct(){
        include_once(app_path("Libs/aliyun/OSS.php"));
        $this->oss = new \OSS();
        // dd($oss);
    }
    public function upload_list(){
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_version_control->get_list($page_info,$start_time,$end_time);
        foreach($ret_info['list'] as $key => &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"publish_time");
            E\Efile_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function upload_page(){
        return $this->pageView(__METHOD__,null,null);
    }
    public function upload_add(Request $request){
        $file = $_FILES['file'];
        echo "<pre>";
        var_dump($file);
        echo "</per>";
        if($file['error'] != 0){
            dd("文件上传有误,错误代码".$file['error']);
        }
        $file_name = $_POST['file_name'];
        $file_type = $_POST['file_type'];
        $file_origin = $file['name'];

        $info =  pathinfo($file_origin);
        $file_extension = $info['extension'];
        if($file_type == 1){
            $new_name = "student/".$file_name.".".$file_extension;
        }else{
            $new_name = "teacher/".$file_name.".".$file_extension;
        }
        $tmp_url = $file['tmp_name'];
        $tmp_type = $file['type'];
        /*
        $ret_info = $this->oss::publicUpload('sam-test', $new_name, $tmp_url, [
            'ContentType' => $tmp_type,
        ]);
        */
        $ret_info = $this->oss::multi_publicUpload('sam-test', $new_name, $tmp_url, [
            'ContentType' => $tmp_type,
        ]);
        
        if($ret_info){
            echo "<pre>";
            var_dump($ret_info);
            echo "</pre>";
            $data = [
                "publish_time" => time(),
                "file_path"  => $new_name,
                "file_type"    => $file_type,
                "file_url"     => $file_type,
            ];
            $this->t_version_control->row_insert($data);
            print("<a href=upload_list>点击跳转到列表页</a>");
        }else{
            dd("上传出错");
        }
    }
    public function file_manage(){
        $ret_info = $this->oss::publicUpload('sam-test', 'student/test_sam01.jpg', '/home/sam/coder01.jpg', [
            'ContentType' => 'application/jpg',
        ]);
        
        dd($ret_info);
    }
    public function add_file(){
        $file_type = $this->get_in_int_val("file_type",-1);
        $file_name = $this->get_in_str_val("file_name","");
        $file_url  = $this->get_in_str_val("file_url","");
        $data = [
            "publish_time" => time(),
            "file_path"    => $file_name,
            "file_type"    => $file_type,
            "file_url"     => $file_url,
        ];
        $ret_info = $this->t_version_control->row_insert($data);
        if($ret_info){
            return outputjson_success();
        }else{
            return outputjson_error();
        }
    }

}

