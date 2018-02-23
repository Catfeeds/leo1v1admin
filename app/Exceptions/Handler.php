<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        CommandNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        /*
          "SERVER_ADDR" => "118.190.65.189"
          "SERVER_PORT" => "80"
          "SERVER_NAME" => "admin.yb1v1.com"
        */

        $server_info=@$_SERVER["SERVER_NAME"].@$_SERVER["SERVER_ADDR"];
        $cmd_info= @join(" ", @$global["argv"]);
        global  $_SESSION;

        $account=@$_SESSION["acc"];

        $url=preg_split("/\\?/", @$_SERVER["REQUEST_URI"])[0];

        $bt_str= "user:$account<br/>.url:$url<br/> server_info $server_info  $cmd_info<br/> ";

        foreach( $e->getTrace() as &$bt_item ) {
            //$args=json_encode($bt_item["args"]);
            $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                @$bt_item["file"].":".@$bt_item["line"].
                "<br/>";
        }

        $ip=@$_SERVER["REMOTE_ADDR"];

        if ( strpos($url,"." ) ===false) { //找文件,
            if( \App\Helper\Utils::check_env_is_release()  ) {
                $ip_fix=preg_replace("/\.[^.]*$/","", $ip );
                if ( !in_array( $ip_fix ,["59.173.189","140.205.201","121.42.0", "140.205.225" ])   ) { //阿里云盾
                    if ( !preg_match("/Method.*does not exist/", $e->getMessage(), $matches )) {
                        dispatch( new \App\Jobs\send_error_mail(
                            "", date("H:i:s")."ERR1:" .$e->getMessage(),
                            "$bt_str".
                            "<br>client_ip:$ip", \App\Enums\Ereport_error_from_type::V_1
                        ));
                    }

                }
            }
        }
        $list_str=preg_replace("/<br\/>/","\n" ,$bt_str );
        \App\Helper\Utils::logger( "LOG_HANDER:". $e->getMessage()."\n $list_str ");


        if( \App\Helper\Utils::check_env_is_release() && !\App\Helper\Config::check_in_admin_list($account)   ) {
            response("500 :系统出错")->send();
            exit;
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render(  $request, Exception $e)
    {

        if ($this->isHttpException($e)) {
            if ($e->getStatusCode()==404) {
                /*
                if ( \App::environment("testing") ) {
                    global $g_request;
                    //  @var $g_request Illuminate\Http\Request
                    $path=$g_request->path();
                    $arr=explode("/", $path);
                    $act="";
                    if (isset($arr[1]) ) {
                        $act=$arr[1] ;
                    }
                    $ctr= $arr[0];
                    if (!$ctr)  {
                        $ctr="index";
                    }
                    if (!$act)  {
                        $act="index";
                    }

                    echo "path $path =>  $ctr @ $act \n";
                    $reflectionObj = new \ReflectionClass( "App\\Http\\Controllers\\$ctr" );
                    $data= $reflectionObj->newInstanceArgs()->$act();
                    return  $data;
                }
            */
                return  "no find url=[".  $request->url() ."]" ;
            }
        }


        return parent::render($request, $e);
    }
}
