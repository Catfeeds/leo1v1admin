<?php
namespace Yxyx ;

// session_start();
//引入配置文件
include_once __DIR__.'/config_yxyx.php';
//引入自动载入函数
include_once __DIR__.'/autoloader_yxyx.php';
//调用自动载入函数
AutoLoaderYXYX::register();

include_once __DIR__.'/../../Libs/LaneWeChat/autoloader.php';
\LaneWeChat\AutoLoader::register();
