<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddTaobaoItem extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $taobao_item;
    var $cid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr,$cid)
    {
        //
        $this->taobao_item=$arr;
        $this->cid=$cid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $taobao_item  = $this->taobao_item;
        $taobao_table = new \App\Models\t_taobao_item();
        $cid_o='';
        $cid_n='';

        foreach($taobao_item as $val){
            $open_iid      = $val['open_iid'];
            $last_modified = strtotime($val['last_modified']);
            $time          = $taobao_table->get_last_modified($open_iid);
            $ret           = true;
            if($time>0){
                $cid_o = $taobao_table->get_cid($open_iid);
                if(strpos($cid_o,$this->cid)===false){
                    $cid_n = $cid_o.",".$this->cid;
                    $ret   = $taobao_table->field_update_list($open_iid,[
                        "cid" => $cid_n,
                    ]);
                }
                if($time < $last_modified){
                    $ret = $taobao_table->field_update_list($open_iid,[
                        "title"         => $val['title'],
                        "pict_url"      => $val['pict_url'],
                        "price"         => $val['price'],
                        "last_modified" => $last_modified,
                    ]);
                }
            }else{
                $ret = $taobao_table->row_insert([
                    "cid"           => $val['cid'],
                    "open_iid"      => $open_iid,
                    "title"         => $val['title'],
                    "pict_url"      => $val['pict_url'],
                    "price"         => $val['price'],
                    "last_modified" => $last_modified,
                ]);
            }
            if(!$ret){
                \App\Helper\Utils::logger("error the taobao is:".json_encode($val));
            }
        }
    }
}