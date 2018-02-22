<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::any("/","index@index");
global $_REQUEST;


/*
  if (!isset($_REQUEST["_ctl"] )) { //phpunit
  $url=$_REQUEST["_url"];

  $arr=explode("/", $url); 
  if (!isset($arr[1]) )  {
  $arr[1]="index";
  }

  if (!isset($arr[2]) or  $arr[2]=="" )  {
  $arr[2]="index";
  }
  $url="/".$arr[1]."/".$arr[2];
  $_REQUEST["_url"]=$url;
  $_GET["_url"]=$url;
  $_POST["_url"]=$url;
  $_REQUEST["_ctl"]=$arr[1];
  $_REQUEST["_act"]= $arr[2];
  }
*/


if (isset( $_REQUEST["_ctl"] )) {
    $ctl= $_REQUEST["_ctl"];
    $act= $_REQUEST["_act"];
    if (!isset( $_REQUEST["_url"])  ) {
        $_REQUEST["_url"] ="/";
    }
    if ($ctl=="custom") {
        Route::any (  $_REQUEST["_url"] , "custom@index" );
    }else{

        if (   !preg_match("/^[a-zA-Z][a-zA-Z0-9_]*$/", $ctl )  ) {
            echo ("无效链接" );
            exit;
        }
        try {
            $class = new ReflectionClass("\\App\\Http\\Controllers\\$ctl" ); 
        }catch(\Exception $e) {
            throw $e;
        }


        if (   !preg_match("/^[a-zA-Z][a-zA-Z0-9_]*$/", $act   )  ) {
            echo ("无效链接" );
            exit;
        }
        \App\Helper\Utils::logger( "ROUTE:[".session("acc")."]: ". $_REQUEST["_url"]  );
        Route::any (  $_REQUEST["_url"] , $ctl."@".$act );
    }
}else{
    Route::any('{ctl}/{act}', 'index@publish');
    Route::any('{ctl}', 'index@publish');
    /*
      foreach( \App\Http\NewRouteConfig::$url_map as $url=>$v  ) {
      Route::any (  $url, str_replace("/", "@" , substr  ($url, 1 )));
      }
    */
}

