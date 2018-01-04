<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OSS\OssClient;

class pdfConversionH5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pdfConversionH5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * @ 从七牛下载->上传未达->从未达下载->上传到七牛
         * @ michael@leoedu.com 密码 ： 021130
        **/
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $email = "michael@leoedu.com";
        $pwd   = 'bbcffc83539bd9069b755e1d359bc70a';// md5(021130)
        $task=new \App\Console\Tasks\TaskController();

        // $handoutArray = $task->t_resource_file->getResourceList();

        // 小班课测试PPT 1bef90ebf32aa93ba0c43433eefb9848  470981
        $handoutArray = [
            [
                "uuid"     => '1bef90ebf32aa93ba0c43433eefb9848',
                "lessonid" => 470981
            ]
        ];

        foreach($handoutArray as $item){
            $uuid = $item['uuid'];
            # 从未达下载
            $h5DownloadUrl  = "http://leo1v1.whytouch.com/export.php?uuid=".$uuid."&email=".$email."&pwd=".$pwd;
            $saveH5FilePath = public_path('wximg').'/'.$uuid.".zip";
            $unzipFilePath  =  public_path('wximg'); // 解压后的文件夹

            $data=@file_get_contents($h5DownloadUrl);
            file_put_contents($saveH5FilePath, $data);

            # 文件解压
            $unzipShell = "unzip $saveH5FilePath -d $unzipFilePath ";
            shell_exec($unzipShell);

            # 获取index.html中实际引用的文件
            $indexFilePath = public_path('wximg')."/".$uuid."/index.html";
            $doneFilePath  = $indexFilePath;
            $link_arr = $this->dealHtml($indexFilePath, $doneFilePath);

            \App\Helper\Utils::logger(" css_link_1: ".json_encode($link_arr['css']));
            \App\Helper\Utils::logger(" js_link_1: ".json_encode($link_arr['js']));

            # 将需要的文件复制到文件夹中
            foreach($link_arr['css'] as $css_item){
                $csPathFrom = public_path('pptfiles')."/".$css_item;
                $csPathTo   = public_path('wximg')."/".$uuid."/".$css_item;
                $cpCs = "cp $csPathFrom $csPathTo ";
                shell_exec($cpCs);
            }

            foreach($link_arr['js'] as $js_item){
                $jsPathFrom = public_path('pptfiles')."/".$js_item;
                $jsPathTo   = public_path('wximg')."/".$uuid."/".$js_item;
                $cpJs = "cp $jsPathFrom $jsPathTo ";
                shell_exec($cpJs);
            }

            # 重新打包压缩
            $zip_new_resource = public_path('wximg')."/".$uuid.".zip";
            $zip_new_file = public_path('wximg')."/".$uuid;
            $zipCmd  = "zip $zip_new_resource $zip_new_file";
            shell_exec($zipCmd);

            # 使用七牛上传  七牛 资源域名 https://ybprodpub.leo1v1.com/
            $qiniu     = \App\Helper\Config::get_config("qiniu");
            $bucket    = $qiniu['public']['bucket'];
            $accessKey = $qiniu['access_key'];
            $secretKey = $qiniu['secret_key'];
            # 构建鉴权对象
            $auth = new \Qiniu\Auth ($accessKey, $secretKey);

            # 压缩包上传七牛
            if(file_exists($zip_new_resource)){
                $saveH5Upload =  \App\Helper\Utils::qiniu_upload($zip_new_resource);
                $rmZipCmd = "rm $zip_new_resource"; // 删除解压包
                shell_exec($rmZipCmd);
                $task->t_lesson_info_b3->field_update_list($item['lessonid'],[
                    "zip_url" => $saveH5Upload
                ]);
            }
        }
    }


    public function dealHtml($indexFilePath, $doneFilePath){
        $html = file_get_contents($indexFilePath);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);


        # 遍历数据 link 引用css数据
        $nodeList = $xpath->query("//link");
        $cssLink = [];
        foreach ($nodeList as $node) {
            $cssLink_tmp = $node->attributes->getNamedItem('href')->nodeValue;
            $cssLink_arr = explode('/', $cssLink_tmp);
            if($cssLink_arr[0] == '..'){
                $cssLink[] = $cssLink_arr[1];
                $node->setAttribute('href', $cssLink_arr[1]);
            }
        }

        # 遍历数据 script 引用js数据
        $scriptList = $xpath->query("//script[@type = 'text/javascript']");
        $jsLink = [];
        foreach ($scriptList as $node) {
            $jsLink_tmp = $node->attributes->getNamedItem('src')->nodeValue;
            $jsLink_arr = explode('/', $jsLink_tmp);
            if($jsLink_arr[0] == '..'){
                $jsLink[] = $jsLink_arr[1];
                $node->setAttribute('src', $cssLink_arr[1]);

                # 测试修改节点属性
                // if($jsLink_arr[1] == 'wxpt.js'){
                //     $node->setAttribute('src', 'wxpt.js');
                //     # 创建DOM节点
                //     //appendChild
                //     $root = $dom->createElement('test','ssssssss');
                //     $node->appendChild( $root );
                // }
            }
        }

        $saveData = $dom->saveHTML();
        file_put_contents($doneFilePath, $saveData);
        $dom=null;
        $ret['css'] = $cssLink;
        $ret['js']  = $jsLink;
        return $ret;
    }


    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    // $handler = opendir($unzipFilePath."/".$uuid);
    // while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
    //     if ($filename != "." && $filename != "..") {
    //         $files[] = $filename ;
    //     }
    // }
    // @closedir($handler);


    // foreach ($files as $key) {
    //     // 上传到七牛后保存的文件名
    //     $upkey = $h5Path."/".$uuid."/".$key;

    //     // 生成上传 Token
    //     $token = $auth->uploadToken($bucket,$upkey);
    //     $Upfile = $unzipFilePath."/".$uuid."/".$key;

    //     // 初始化 UploadManager 对象并进行文件的上传。
    //     $uploadMgr = new \Qiniu\Storage\UploadManager();

    //     // 调用 UploadManager 的 putFile 方法进行文件的上传。
    //     $checkIsExists = file_exists($Upfile);
    //     if($checkIsExists){
    //         list($ret, $err) = @$uploadMgr->putFile($token, $upkey, $Upfile);
    //         $test_data .= $ret["key"]." ";
    //         if($key == 'index.html'){
    //             \App\Helper\Utils::logger("upkey_qiniu: $upkey");
    //             $task->t_resource_file->field_update_list($item['file_id'],[
    //                 "wx_index" => "https://ybprodpub.leo1v1.com/".$upkey
    //             ]);

    //         }
    //     }
    // }




}
