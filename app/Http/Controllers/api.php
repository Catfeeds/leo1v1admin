<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class api extends Controller
{
    var $check_login_flag =false;
    use CacheNick;
    public $sn="";
    public function api_output_succ($data) {

        $out_str= outputJson(
            ["status"=>1,
             "info"=>"ok",
             "data"=>  $data
            ]);
        \App\Helper\Utils::logger("API OUT:" .  $out_str );
        return   $out_str;

    }
    public function do_get(){
        //Q11163910103
        $sn= $this->get_in_str_val("sn");
        $this->sn=$sn;
        $this->t_kaoqin_machine->set_last_post_time($sn);
        $key="kaoqin_$sn";
        $sync_data_list=\App\Helper\Common::redis_get_json($key);
        \App\Helper\Common::redis_set_json($key,[]);
        return $this->api_output_succ($sync_data_list );

        /*
        return $this->api_output_succ([
            ["id"=>"8001",
             "do"=>"update",
             "data"=>"user",
             "ccid"=>1236,
             "name"=>"张三",
             "passwd"=>"",
             "card"=>"",
             "deptid"=>0,
             "auth"=>0,
             "faceexist"=>0
            ]
        ]);
        */
    }
    public function do_post_clockin($data){
        //"time":"2017-04-1413:10:58","verify":1,"ccid":"1236
        if ($data["verify"] ) { //登录成功
            $this->t_admin_card_log->row_insert([
                "logtime" => strtotime($data["time"] ),
                "cardid" => $data["ccid"] ,
            ],false,true);
            //fingerprint
        }
    }
    public function do_post_fingerprint($data) {
        $ccid = $data["ccid"];
        $this->t_manager_info->field_update_list($ccid,[
            "fingerprint1" => @$data["fingerprint"][0],
            "fingerprint2" => @$data["fingerprint"][1],
        ]);
        $this->t_manager_info->sync_kaoqin_user($ccid);
    }

    public function do_post_headpic($data) {
        $ccid = $data["ccid"];
        $this->t_manager_info->field_update_list($ccid,[
            "headpic" => @$data["headpic"],
        ]);
        $this->t_manager_info->sync_kaoqin_user($ccid);
    }
    public function do_post_user($data)  {
        $ccid = $data["ccid"];
        $row=$this->t_manager_info->field_get_list($ccid,"del_flag");
        $del_flag=1;
        if ($row) {
            $del_flag=$row["del_flag"];
        }
        if ($del_flag) {
            $this->t_manager_info->sync_kaoqin_del_user([$ccid]);
        }

    }


    public function do_post() {
        $sn= $this->get_in_str_val("sn");
        $this->sn=$sn;
        $post_data = file_get_contents("php://input");
        $data_list=json_decode($post_data, true );
        $id_list=[];

        \App\Helper\Utils::logger("API_DATA:".$post_data );
        foreach ( $data_list as $data  ) {
            $id=$data["id"];
            $id_list[]=$id;
            $data_name=$data["data"];
            switch ( $data_name) {
            case "clockin" :
                $this->do_post_clockin($data);
                break;
            case "fingerprint" :
                $this->do_post_fingerprint($data);
                break;


            case "headpic" :
                $this->do_post_headpic($data);
                break;

            case "user" :
                $this->do_post_user($data);
                break;
            default:
                break;
            }
        }
        return $this->api_output_succ( $id_list );
    }

    public function data() {
        //api/data/get
        $opt_arr=preg_split( "/\//",$_GET["_url"]  );
        $opt=$opt_arr[3];
        if ($opt=="get") {
            return $this->do_get();
        }
        if ($opt=="post") {
            return $this->do_post();
        }

    }
}
