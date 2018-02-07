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

        $handoutArray = $this->getNeedTranLessonUid();

        foreach($handoutArray as $item){
            $this->deal_change($item,$email,$pwd); # 处理学生讲义
        }
    }

    public function deal_change($item,$email,$pwd){
        $uuid = $item['uuid'];
        # 从未达下载
        $h5DownloadUrl  = "http://leo1v1.whytouch.com/export.php?uuid=".$uuid."&email=".$email."&pwd=".$pwd;
        $saveH5FilePath = public_path('ppt').'/'.$uuid.".zip";
        $unzipFilePath  =  public_path('ppt'); // 解压后的文件夹

        $data=@file_get_contents($h5DownloadUrl);
        file_put_contents($saveH5FilePath, $data);

        # 文件解压
        $unzipShell = "unzip $saveH5FilePath -d $unzipFilePath ";
        shell_exec($unzipShell);

        # 获取index.html中实际引用的文件
        $indexFilePath = public_path('ppt')."/".$uuid."/index.html";
        $doneFilePath  = $indexFilePath;
        $link_arr = $this->dealHtml($indexFilePath, $doneFilePath);

        # 将需要的文件复制到文件夹中
        foreach($link_arr['css'] as $css_item){
            $csPathFrom = public_path('pptfiles')."/".$css_item;
            $csPathTo   = public_path('ppt')."/".$uuid."/".$css_item;
            $cpCs = "cp $csPathFrom $csPathTo ";
            shell_exec($cpCs);
        }

        foreach($link_arr['js'] as $js_item){
            $jsPathFrom = public_path('pptfiles')."/".$js_item;
            $jsPathTo   = public_path('ppt')."/".$uuid."/".$js_item;
            $cpJs = "cp $jsPathFrom $jsPathTo ";
            shell_exec($cpJs);
        }

        foreach($link_arr['img'] as $img_item){
            $imgPathFrom = public_path('pptfiles')."/".$img_item;
            $imgPathTo   = public_path('ppt')."/".$uuid."/".$img_item;
            $cpImg = "cp $imgPathFrom $imgPathTo ";
            shell_exec($cpImg);
        }

        $fntPathFrom = public_path('ppt')."/".$uuid."/data/fnt0.woff";
        $fntPathTo   = public_path('ppt')."/".$uuid."/fnt0.woff";
        $cpFnt = "cp $fntPathFrom $fntPathTo ";
        shell_exec($cpFnt);


        # 重新打包压缩
        $work_path= public_path('ppt');
        $del_zip = $work_path."/".$uuid.".zip";
        $zip_new_resource = public_path('ppt')."/".$uuid."_leo123.zip";
        $zipCmd  = " cd ".$work_path."/".$uuid.";  zip -r ../".$uuid."_leo123.zip * ";
        \App\Helper\Utils::exec_cmd($zipCmd);

        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];
        # 构建鉴权对象
        $auth = new \Qiniu\Auth ($accessKey, $secretKey);

        # 压缩包上传七牛
        if(file_exists($zip_new_resource)){
            $saveH5Upload =  \App\Helper\Utils::qiniu_upload_private($zip_new_resource);
            $this->deldir($work_path."/".$uuid);
            $rmZipCmd = "rm $del_zip"; // 删除解压包
            $rmResourceCmd = "rm $zip_new_resource";
            shell_exec($rmZipCmd);
            shell_exec($rmResourceCmd);

            # 在42服务器端执行此段程序
            $this->updateTranResult($item['lessonid'],$saveH5Upload,$item['is_tea'],$item['id']);
        }

    }

    public function updateTranResult($lessonid,$saveH5Upload,$is_tea,$id){
        $url = "http://admin.leo1v1.com/common_new/updateTranResult";
        $post_data = array(
            "lessonid" => $lessonid,
            "zip_url"  => $saveH5Upload,
            "is_tea"   => $is_tea,
            "id"       => $id
        );
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $ret_arr = json_decode($output,true);
    }

    public function getNeedTranLessonUid(){
        $url = "http://admin.leo1v1.com/common_new/getNeedTranLessonUid";
        $post_data = [];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $ret_arr = json_decode($output,true);
        return $ret_arr['data'];
    }



    public function dealHtml($indexFilePath, $doneFilePath){
        $html = file_get_contents($indexFilePath);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);


        # 遍历数据 link 引用css数据
        $nodeList = $xpath->query("//link");
        $cssLink = [];
        foreach ($nodeList as $node) {
            $cssLink_tmp = $node->attributes->getNamedItem('href')->nodeValue;
            $cssLink_arr = explode('/', $cssLink_tmp);
            if($cssLink_arr[0] == '..'){
                $cssLink[] = $cssLink_arr[1];
                \App\Helper\Utils::logger("cssLink_arr_item: ".$cssLink_arr[1]);

                $node->setAttribute('href', $cssLink_arr[1]);
            }
        }

        # 遍历数据 script 引用js数据
        $scriptList = $xpath->query("//script");
        $jsLink = [];
        foreach ($scriptList as $node_js) {
            $jsLink_tmp = @$node_js->attributes->getNamedItem('src')->nodeValue;
            $jsLink_arr = explode('/', $jsLink_tmp);

            if($jsLink_arr[0] == ''){
                # 删除节点
                $nodeContent = $node_js->nodeValue;
                $domain = strstr($nodeContent,'shareimg');

                if($domain){
                    $node_js->parentNode->removeChild($node_js);
                }
                # 替换 节点内容

                $domain_jq = strstr($nodeContent,'jquery-1.8.1.min.js');
                \App\Helper\Utils::logger('nodeContent_test: '.$nodeContent." domain: ".$domain." domain_jq: ".$domain_jq);

                if($domain_jq)
                {
                    # jq文件复制到index同级目录
                    $node_js->parentNode->removeChild($node_js);
                }


            }

            if($jsLink_arr[0] == '..'){
                # 修改属性
                if($jsLink_arr[1] != 'wxpt.js' && $jsLink_arr[1] != 'recommend-0.2.js'){
                    $node_js->setAttribute('src', $jsLink_arr[1]);
                    $jsLink[] = $jsLink_arr[1];
                }

                # 删除无需引用的节点
                if($jsLink_arr[1] == 'recommend-0.2.js' || $jsLink_arr[1] == 'wxpt.js'){
                    $node_js->parentNode->removeChild($node_js);
                }
            }

            if($jsLink_arr[0] == 'http:'){
                # 删除节点 不需要的节点
                $node_js->parentNode->removeChild($node_js);
            }
        }

        # 遍历数据 img 处理img标签数据 [处理中]
        $imgList = $xpath->query("//img[@src = '../loading.gif']");
        $imgLink = [];
        foreach ($imgList as $node_img) {
            $imgLink_tmp = $node_img->attributes->getNamedItem('src')->nodeValue;
            $imgLink_arr = explode('/', $imgLink_tmp);
            if($imgLink_arr[1] == 'loading.gif'){
                $imgLink[] = $imgLink_arr[1];
                $node_img->setAttribute('src', $imgLink_arr[1]);
            }
        }

        # 删除 HTML中无用标签 例如:[audio] [link href=data/f.css]
        $audioList = $xpath->query("//audio");
        foreach ($audioList as $node_audio) {
            $node_audio->parentNode->removeChild($node_audio);
        }
        $linkList = $xpath->query("//link[@href='data/f.css']");
        foreach ($linkList as $node_link) {
            $node_link->parentNode->removeChild($node_link);
        }

        # 创建link节点 script节点
        $htmlList = $xpath->query("//html");
        foreach ($htmlList as $node_html) {
            $root = $dom->createElement('link','');
            $node_html->insertBefore($root,$node_html->firstChild);
            $root->setAttribute('rel', 'stylesheet');
            $root->setAttribute('type', 'text/css');
            $root->setAttribute('href', 'data/f.css');
        }

        # 页面中添加 gavan 代码
        $headList = $xpath->query("//head");
        foreach ($headList as $node_head) {
            $root_js = $dom->createElement('script','');
            $root_js_b = $dom->createElement('script','');
            $node_head->appendChild($root_js);
            $node_head->appendChild($root_js_b);
            $root_js_b->setAttribute('type', 'text/javascript');
            $root_js_b->setAttribute('src', 'bridge.js');

            $root_js->setAttribute('type', 'text/javascript');
            $root_js->setAttribute('src', 'jquery-1.8.1.min.js');

            $jsLink[] = 'jquery-1.8.1.min.js';
            $jsLink[] = 'bridge.js';

        }



        $saveData = $dom->saveHTML();
        file_put_contents($doneFilePath, $saveData);
        $dom=null;
        $ret['css'] = $cssLink;
        $ret['js']  = $jsLink;
        $ret['img'] = $imgLink;
        return $ret;
    }


   public  function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
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
}
