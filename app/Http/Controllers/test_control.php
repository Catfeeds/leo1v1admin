<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
class test_control extends Controller
{
    var    $check_login_flag=false;
    public function test() {
        $dir=dir( __DIR__ );
        while (($file = $dir->read()) !== false)
        {
            \App\Helper\Utils::logger("deal: $file ");

            if (  substr( $file, -4 ) == ".php"  &&  $file[0] <>"."  ) {

                if (substr( $file,0,4) != "test" ) {
                    $className=substr($file, 0, -4 ) ;


                    if (! in_array( $className,["CacheNick","ViewDeal","InputDeal","TeaPower","LessonPower"] ) ) {
                        $str="\\App\\Http\\Controllers\\".  $className;
                        $str::getRouter() ;
                    }
                }
            }
            \App\Helper\Utils::logger("deal end: $file ");
        }
        $dir=dir( __DIR__ . "/../../Models" );
        while (($file = $dir->read()) !== false)
        {
            if (  substr( $file, -4 ) == ".php" &&  $file[0] <>"."  ) {
                $className=substr($file, 0, -4 ) ;
                if (! in_array( $className,["NewDB" ,"users","t_area" ] ) ) {
                    $str="\\App\\Models\\".  $className;
                    $name=$str::test();
                }
            }
        }

        $dir=dir( __DIR__ . "/../../Jobs" );
        while (($file = $dir->read()) !== false)
        {
            if (  substr( $file, -4 ) == ".php" &&  $file[0] <>"."  ) {
                $className=substr($file, 0, -4 ) ;
                if (! in_array( $className,["NewDB" ] ) ) {
                    $str="\\App\\Jobs\\".  $className;
                    $name=$str::test();
                }
            }
        }




        return "succ";
    }

}
