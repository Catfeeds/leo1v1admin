<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use LaneWeChat\Core\UserManage;
use \App\Enums as E;
use Illuminate\Support\Facades\Input ;

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

//引入分词类
use Analysis\PhpAnalysis;

require_once  app_path("Libs/Pingpp/init.php");

class account_common extends Controller
{
    use TeaPower;
    use CacheNick;
    var $check_login_flag =false;

    
   
   

}
