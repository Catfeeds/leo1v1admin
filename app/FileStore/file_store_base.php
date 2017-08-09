<?php

namespace  App\FileStore;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");
class file_store_base {
    public $bucket="loe-file";

    public $project_dir="base"; //tea,stu

    /**
     * @return \Qiniu\Auth ;
     */
    public function get_auth() {
        $config=\App\Helper\Config::get_config("qiniu");
        // 构建鉴权对象
        $auth = new Auth($config["access_key"], $config["secret_key"]);
        return $auth;
    }
    /**
     * @return  \Qiniu\Storage\BucketManager
     */
    public function get_bucketMgr() {
        $auth = $this->get_auth() ;
        return  new BucketManager($auth);
    }

    public function  list_dir_ex( $dir ) {
        $dir=rtrim($dir, "/")."/";
        // 要列取的空间名称
        $bucketMgr = $this->get_bucketMgr() ;
        $bucket = $this->bucket ;
        // 要列取文件的公共前缀
        $prefix = $dir;
        $marker = '';
        $limit = 3;
        list($items, $marker, $err, $dirs ) = $bucketMgr->listFiles($bucket, $prefix, $marker, 100, "/" );
        $ret_list=[];
        $dir_len=strlen($dir);
        foreach ($dirs  as $sub_dir ) {
            $ret_list[] = ["is_dir"=>1 , "file_name"  => substr($sub_dir, $dir_len ) ];
        }
        /* 0 => array:6 [▼
           "key" => "tea/10001/order_19658_1499767249.pdf"
           "hash" => "Fg_e3PIK9NUCfeD0LG_uHQSH2Xg3"
           "fsize" => 400829
           "mimeType" => "application/pdf"
           "putTime" => 15021727097972488
           "type" => 1
           ]
        */
        foreach ($items as $item ) {
            $file_name=substr($item["key"], $dir_len );
            if ( $file_name)  {
                $ret_list[] = [
                    "is_dir"=>0 ,
                    "file_name"  => substr($item["key"], $dir_len ),
                    "create_time" => intval($item["putTime"]/10000000),
                    "file_size" => $item["fsize"],
                ];
            }
        }
        return $ret_list;
    }

    public function download_url_ex($file_url) {
        $file_url = "http://file-store.leo1v1.com/".rtrim( $file_url,"/");
        $base_url=$auth->privateDownloadUrl($file_url );
    }

    public function del_file_ex($file) {
        $bucketMgr = $this->get_bucketMgr() ;
        $BucketMgr->delete($this->bucket,$file);
        return true;
    }

    public function move_file($file, $new_file ) {
        $bucket    = $this->bucket;
        $bucketMgr = $this->get_bucketMgr() ;
        $bucketMgr->move($bucket, $file, $bucket, $new_file);
        return true;
    }

    public function add_file_ex( $file, $data ) {
        // 生成上传 Token
        $bucket    = $this->bucket;
        $auth = $this->get_auth();
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager(new \Qiniu\Config( \Qiniu\Zone::zone2() ));

        list($ret, $err) = $uploadMgr->put($token, $file, $data);

        return $err? false: true;

    }
    public function get_upload_token() {
        $auth = $this->get_auth();
        return  $auth->uploadToken($this->bucket);
    }


}