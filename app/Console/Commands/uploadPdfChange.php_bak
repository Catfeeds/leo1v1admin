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

        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $email = "michael@leoedu.com";
        $pwd   = 'bbcffc83539bd9069b755e1d359bc70a'; //md5(021130)
        $task  = new \App\Console\Tasks\TaskController();

        $handoutArray = $this->getTeaUploadPPTLink();

        foreach($handoutArray as $item){
            //七牛下载
            if($item['stu_cw_url'] && $item['zip_url_stu']=='' && $item['use_ppt_stu'] ==1){
                $this->deal($item,2); # 处理学生讲义
            }
            if($item['tea_cw_url'] && $item['zip_url']=='' && $item['use_ppt'] ==1){
                $this->deal($item,1); # 处理老师讲义
            }
        }
    }

    public function deal($item,$is_tea){
        if($is_tea == 1){
            $pdf_file_path = $this->gen_download_url($item['tea_cw_url']);
            $ppt_key = $item['tea_cw_url'];
            $title   = $item['tea_cw_name']."_tea";
        }else{
            $pdf_file_path = $this->gen_download_url($item['stu_cw_url']);
            $ppt_key = $item['stu_cw_url'];
            $title   = $item['stu_cw_name']."_stu";
        }


        $savePathFile = public_path('wximg').'/'.$ppt_key;
        \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
        @chmod($savePathFile, 0777);

        //上传未达
        $cmd  = "curl -F doc=@'$savePathFile' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=0&aut=leoedu&fn=".$title.".pptx'";
        $uuid_tmp = shell_exec($cmd);
        $uuid_arr = explode(':', $uuid_tmp);
        $uuid = @$uuid_arr[1];
        @unlink($savePathFile);

        # 42服务器端更新uuid
        $this->updateLessonUUid($item['lessonid'],$uuid,$is_tea);
    }

    public function getTeaUploadPPTLink(){
        // $url = "http://test.admin.leo1v1.com/common_new/getTeaUploadPPTLink";
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

    public function updateLessonUUid($lessonid,$uuid,$is_tea){
        $url = "http://test.admin.leo1v1.com/common_new/updateLessonUUid";
        $post_data = [
            "lessonid" => $lessonid,
            "uuid"     => $uuid,
            "is_tea"   => $is_tea
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
