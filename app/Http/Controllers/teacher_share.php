<?php
namespace App\Http\Controllers;

use Teacher\Core\WeChatOAuth;

use Teacher\Core\UserManage;

use Teacher\Core\TemplateMessage;

use Teacher\Core\Media;

use Teacher\Core\AccessToken;


use \App\Enums as E;

include(app_path("Wx/Teacher/lanewechat_teacher.php"));



class teacher_share extends Controller
{
    use CacheNick;
    var $check_login_flag =false;

    public function index() {
        $sign=$this->get_in_str_val("sign");
        $dir = $this->get_in_str_val("dir");
        if (!$dir) {
            $dir="/";
        }
        $key= "xcwen142857xcwAB";
        $data=@\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::decrypt($sign,$key));
        if (!is_array($data)) {
            //check md5
            return $this->error_view([
                "无效链接"
            ]);
        }
        $teacherid = $data["teacherid"] ;
        $share_path = $data["share_path"] ;
        $create_time = $data["create_time"] ;
        $end_time = $data["end_time"] ;
        $md5_sum= $data["md5_sum"] ;



        if( $md5_sum!== substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)) {
            return $this->error_view([
                "md5 校验失败"
            ]);
        }

        $file_name="";
        if ($share_path[strlen( $share_path)-1]!="/") {
            $file_name=basename($share_path );
            $share_path=dirname($share_path );
        }


        $store=new \App\FileStore\file_store_tea();
        $obj_dir=  rtrim(  rtrim ($share_path,"/"). "/" . trim( $dir , "/" ), "/")  ."/";
        $ret_list=$store->list_dir($teacherid, $obj_dir);
        $list=[];
        foreach ( $ret_list  as $item  ) {
            if (!$item["is_dir"]) {
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            }
            $item["abs_path"] =  $dir .$item["file_name"];
            $item["file_size"]= \App\Helper\Common::size_str(@$item["file_size"] );
            if ( $file_name ==""  ) {
                $list[]=$item;
            }else if ( $file_name== $item["file_name"]) {
                $list[]=$item;
            }
        }

        array_unshift( $list, [
            "is_dir"      => 1,
            "file_name"   => "返回上级目录" ,
            "abs_path"    => dirname($dir),
            "file_size"   => "",
            "create_time" => "",
        ]);

        return $this->pageView(
                __METHOD__,
                \App\Helper\Utils::list_to_page_info($list) ,["cur_dir"=>$dir] );

    }
    public function get_download_url( ) {


        $sign=$this->get_in_str_val("sign");
        $file_path= $this->get_in_str_val("file_path");
        $key= "xcwen142857xcwAB";
        $data=@\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::decrypt($sign,$key));
        if (!is_array($data)) {
            //check md5
            return $this->error_view([
                "无效链接"
            ]);
        }
        $teacherid = $data["teacherid"] ;
        $share_path = $data["share_path"] ;
        $create_time = $data["create_time"] ;
        $end_time = $data["end_time"] ;
        $md5_sum= $data["md5_sum"] ;

        if( $md5_sum!== substr( md5("$teacherid:$share_path:$create_time,$end_time"), 0, 16)) {
            return $this->error_view([
                "md5 校验失败"
            ]);
        }


        $store=new \App\FileStore\file_store_tea();

        $auth=$store->get_auth();

        $file_path =    rtrim ($share_path,"/"). "/" . trim( $file_path)   ;
        $file_path = $store->get_file_path($teacherid,$file_path);
        $authUrl = $auth->privateDownloadUrl("http://file-store.leo1v1.com/". $file_path );
        return $this->output_succ(["url" => $authUrl]);
    }

    public function christmas_list() {
        //list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $start_time = strtotime("2017-12-23");
        $total = $this->t_teacher_christmas->get_total($start_time);
        $info = $this->t_teacher_christmas->get_all_list($start_time);

        foreach($info as &$item){
            $userInfo = UserManage::getUserInfo($item['wx_openid']);
            $item['wx_nick'] = $userInfo['nickname'];
            $item['phone'] = substr($item['phone'],0,3)."****".substr($item['phone'],7);
        }




        return $this->pageView(__METHOD__, '', [
            'total' => $total,
            "info" => $info
        ]);
    }
}
