<?php

namespace App\Http\Middleware;

use Closure;

use App\Enums as  E;
use Illuminate\Support\Facades\Log;
class MyMiddleware
{
    static $power_map =[
        "/supervisor/monitor"  =>[ E\Epower::V_LESSON_MONITOR ]
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        global $g_request;
        $g_request=$request;

        global $g_account;
        $g_account=session("acc");

        $in_arr=$request->input();
        // dd($in_arr);
        // \Utils::debug_to_html(   );
        $arr=[];
        $in_start_time=time(NULL);



        $check_flag__userid=false;

        foreach (  $request->input() as  $key=>$value ) {
            if ($key!="_url" && $key != "_ctl"  && $key != "_act" ) {
                if (is_string($value)){
                    if (strlen( $value)>1000) {
                        $arr[]="$key=" . urlencode(substr( $value,0,100 ));
                    }else{
                        $arr[]="$key=" . urlencode($value);
                    }
                }
                if ($key=="_userid") {
                    $check_flag__userid=true;
                }

            }
        }

        if (!$check_flag__userid) {
            $arr[]= "_userid=".@session("login_userid");
            $arr[]= "_role=".@session("login_user_role");
        }


        $json_data=json_encode($request->input() );
        //echo "1111:[$json_data]\n";
        if ($json_data === false) { //json 出错
            return new \Illuminate\Http\Response ( json_encode(["ret" => -8002, "info" => " input  data format json error " ]));
        }

        $ip=@$_SERVER["REMOTE_ADDR"];
        $g_in_info="[$ip]IN_URL:[$g_account] ". $request->url(). "?". join("&", $arr );
        \App\Helper\Utils::logger( $g_in_info  );

        if(!$this->power_check($request))  {

            \App\Helper\Utils::logger("without power !!");
            \App\Helper\Utils::logger( session("power_list"));
            if (isset( $in_arr["callback"])) {

                return  outputjson_error( 1001,array("info"=>"没有权限!"));

            }else{
                $ret_str=\App\Http\Controllers\Controller::view_with_header_info(
                    "common.without-power", [],[
                        "_ctr"=>  "xx",
                        "_act"=> "xx",
                        "js_values_str"=>"",
                    ] );
                return new \Illuminate\Http\Response($ret_str );
            }

        }

        \App\Helper\Utils::logger("ROUTE START ");
        $ret=$next($request);

        $in_end_time=time(NULL);
        $diff= $in_end_time- $in_start_time;
        \App\Helper\Utils::logger("account:$g_account:WORK_TIME:$diff:URL:". $request->url());
        \App\Models\NewDB::close_all_dbs();
        return $ret;

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function power_check($request) {
        $url=\App\Helper\Utils::get_full_url( $request->path());

        $map=\App\Helper\Config:: get_url_power_map();
        $need_powerid=isset($map[$url])? $map[$url]:0 ;
        \App\Helper\Utils::logger("CHECK_POWER: URL:$url,need_powerid:$need_powerid");

        if ($need_powerid) { //url
            if (!session("acc")) {
                \App\Helper\Utils::logger("SESSION ACC NOFIND");

                if (!\App\Helper\Utils::check_env_is_testing() ) {
                    $in_arr=$request->input();
                    if (isset($in_arr["callback"])) {
                        $resp=outputjson_error(1005,"没有权限!");
                        echo $resp->send();
                        exit;
                    }else{
                        header("Location: /?to_url=". urlencode(@$_SERVER['REQUEST_URI'] )   );
                    }
                }
                exit;
            }
            /*
            $power_change_time=\App\Helper\Common::redis_get("POWER_CHANGE_TIME");
            $power_set_time=session("power_set_time");

            if($power_set_time < $power_change_time ) {
                $login =new \App\Http\Controllers\login();
                $permission=  $login->reset_power( session("acc")  );
                session([
                    "power_set_time" => time(NULL),
                ]);
                $_SESSION['power_set_time']    =time(NULL);
            }
            */

            $power_list=json_decode(session("power_list"),true);
            return isset($power_list[$need_powerid]);
        }else{
            return true;
        }
    }

}
