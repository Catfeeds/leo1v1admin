<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_menu_url_power_map extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_menu_url_power_map';

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
    public function gen_url_field_default_true() {
        $cmd = app_path("../app/Config/url_desc_power" );
        $lines=\App\Helper\Utils::exec_cmd("cd ".$cmd.";ls */*.php");
        $line=preg_split("/\n/", $lines);
        $pre = "\\App\\Config\\url_desc_power\\";

        if( is_array($line)){

            $str="<?php\n"
                ."namespace App\Config;\n"
                ."class power_config {\n"

                ."\tstatic  public  function get_default_config()  {\n"
                . "\t\treturn [\n";

            foreach( $line as $var ){
                $midd = strpos($var,"/") ;
                $fileName = substr($var, 0, $midd);
                $className  = substr($var, $midd + 1, -4 );
                $desc_class = $pre.$fileName."\\".$className;
                $url = '/'.$fileName.'/'.$className;
                if(!class_exists($desc_class)){
                    continue;
                };
                $get_config = $desc_class::get_config();
                foreach( $get_config as $item){
                    $get_default = "";
                    if( @$item['default_value'] ){
                        foreach( $item as $k => $v){
                            $get_default.="\t\t\t\t'$k'\t=>'$v',\n";
                        }
                    }
                    if(!empty($get_default)){
                        $str .= "\t\t\t'$url' \t=> [\n".$get_default."\t\t\t],\n ";
                    }
                }
                
            }

            $str.="\t\t];\n ";
            $str.="\t}\n ";
            $str.="}\n ";

            file_put_contents(app_path("./Config/power_config.php"),$str);
        }

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //user_manage_new
        $m=new \App\Http\Controllers\user_manage_new(false);
        $list=$m->get_menu_list([]);
        $url_power_map=[];
        $power_map=[];
        foreach ($list  as $item ) {
            if ( $item["pid"] >0) {
                $url = $item["url"];
                $url_arr=explode("/",$url);
                $c=trim(@$url_arr[1]);
                $a_arr=explode("?",@$url_arr[2]);
                $a=trim($a_arr[0]);
                if ($a=="") {
                    $a="index";
                }
                $url_power_map["/$c/$a"] = $item["pid"];
                $name=$item["k1"]. $item["k2"]. $item["k3"];

                if (isset( $power_map [$item["pid"]] )) {
                    echo "ERROR :权限冲突: " .$power_map [$item["pid"]] . "<==>$name \n" ;
                    exit;
                }else{
                    $power_map [$item["pid"]]= $name;
                }
            }
        }
        $str="<?php\n"
            ."namespace App\Config;\n"
            ."class url_power_map {\n"

            ."\tstatic  public  function get_config()  {\n"
            . "\t\treturn [\n";
        foreach ($url_power_map as $k =>$v) {

            $str.="\t\t\t'$k'\t=>$v,\n";
        };
        $str.="\t\t];\n ";
        $str.="\t}\n ";
        $str.="}\n ";

        $this->gen_url_field_default_true();

        file_put_contents(app_path("./Config/url_power_map.php"),$str);

        return ;

    }
}
