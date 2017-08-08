<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

// use Qiniu\Auth;
// use Qiniu\Storage\UploadManager;
// use Qiniu\Storage\BucketManager;

// use App\Jobs\deal_pdf_to_image;

// require_once  app_path("/Libs/Qiniu/functions.php");


class t_yxyx_wxnews_info extends Controller
{
    public function all_news(){
        $page_info= $this->get_in_page_info();
        $res = $this->t_yxyx_wxnews_info->get_news_info($page_info);
        foreach ($res['list'] as &$item) {
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }
        return $this->pageView( __METHOD__,$res);
    }
}