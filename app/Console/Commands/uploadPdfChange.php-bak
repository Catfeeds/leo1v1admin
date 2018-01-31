<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class uploadPdfChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:uploadPdfChange';

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
        //

        /**
         * @ 从七牛下载->上传未达->从未达下载->上传到七牛
         * @ michael@leoedu.com 密码 ： 021130
        **/
        // $store=new \App\FileStore\file_store_tea();
        // $auth=$store->get_auth();


        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );


        $email = "michael@leoedu.com";
        $pwd   = 'bbcffc83539bd9069b755e1d359bc70a'; //md5(021130)
        $task=new \App\Console\Tasks\TaskController();

        $handoutArray = $this->getTeaUploadPPTLink();

        foreach($handoutArray as $item){
            //七牛下载
            // $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$item['file_link'] );
            $pdf_file_path = $this->gen_download_url($item['file_link']);

            $savePathFile = public_path('wximg').'/'.$item['file_link'];
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            @chmod($savePathFile, 0777);

            //上传未达
            $cmd  = "curl -F doc=@'$savePathFile' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=0&aut=leoedu&fn=".$item['file_title'].".pptx'";
            $uuid_tmp = shell_exec($cmd);
            $uuid_arr = explode(':', $uuid_tmp);
            $uuid = @$uuid_arr[1];
            @unlink($savePathFile);

            # 42服务器端更新uuid
            $this->updateLessonUUid($item['lessonid'],$uuid);

            # 161服务器端更新uuid
            // $task->t_lesson_info->field_update_list($lessonid,[
            //     "uuid" => $uuid,
            // ]);
        }

    }

    public function getTeaUploadPPTLink(){
        $url = "http://admin.leo1v1.com/common_new/getTeaUploadPPTLink";
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

    public function updateLessonUUid($lessonid,$uuid){
        $url = "http://admin.leo1v1.com/common_new/updateLessonUUid";
        $post_data = [
            "lessonid" => $lessonid,
            "uuid"     => $uuid
        ];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $ret_arr = json_decode($output,true);
        return $ret_arr;
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

}
