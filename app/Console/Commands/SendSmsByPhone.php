<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSmsByPhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendSmsByPhone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送短信命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // $phone = $this->get_b_txt();
        // $phone = [
        //     "17621197944"
        // ];
        // $data = [
        //     "time" => "2018年1月1日"
        // ];
        // $type = 34775122;
        // foreach($phone as $val){
        //     if($val!=''){
        //         \App\Helper\Utils::sms_common($val, $type, $data);
        //     }
        // }
    }

    public function get_b_txt($file_name="b"){
        $info = file_get_contents("/tmp/".$file_name.".txt");
        $arr  = explode("\n",$info);
        return $arr;
    }
}
