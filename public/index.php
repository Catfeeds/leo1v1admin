<?php


// url config  in  ../app/Http/NewRouteConfig.php
@ini_set('session.auto_start', 1);


function get_request_method() {
    global $HTTP_RAW_POST_DATA;

    if(isset($HTTP_RAW_POST_DATA)) {
    	parse_str($HTTP_RAW_POST_DATA, $_POST);
    }

    if (isset($_POST["_method"]) && $_POST["_method"] != null) {
        return $_POST["_method"];
    }

    return $_SERVER["REQUEST_METHOD"];
}
$method = get_request_method();

if ($method == "OPTIONS") {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, DELETE");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Cache-Control");
    exit;
}




ob_start();
//check in url
$url=@$_REQUEST["_url"];
if (!$url) { $url="";}



$arr=explode("/", $url);
if (!isset($arr[1]) )  {
    $arr[1]="index";
}

if (!isset($arr[2]) or  trim($arr[2])=="" )  {
    $arr[2]="index";
}


$url="/".$arr[1]."/".$arr[2];

require("../app/Http/NewRouteConfig.php");


if ( !(
    \App\Http\NewRouteConfig::check_is_new_url($url)
    || \App\Http\NewRouteConfig::check_is_new_ctl($arr[1] )) ){

    //
    $_REQUEST["ctl"]=$arr[1];
    $_GET["ctl"]=$arr[1];
    $_POST["ctl"]=$arr[1];

    $_REQUEST["act"]=$arr[2];
    $_GET["act"]=$arr[2];
    $_POST["act"]=$arr[2];

    require( "../old/webroot/index.php" );
    exit;
}

//$_REQUEST["_url"]=$url;
//$_GET["_url"]=$url;
//$_POST["_url"]=$url;
$_REQUEST["_ctl"]=$arr[1];
$_REQUEST["_act"]= $arr[2];



/**

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/'.'../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
