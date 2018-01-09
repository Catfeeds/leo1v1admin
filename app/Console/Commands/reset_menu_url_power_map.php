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
        $lines=\App\Helper\Utils::exec_cmd("cd ;ls */*.php");
        $line=preg_split("/\n/", $lines);
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



        file_put_contents(app_path("./Config/url_power_map.php"),$str);

        return ;

    }
}
