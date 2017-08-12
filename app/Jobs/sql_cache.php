<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

//TODO sql 缓存结果
class sql_cache extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $store_key;
    public $table_class_name;
    public $function;
    public $time_fmt;
    public $args;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($time_fmt, $table_class_name, $function , $args )
    {
        /* "Y-m-d"*/
        $args_md5= md5(json_encode($args));

        $this->time_fmt= $time_fmt;
        $this->store_key="SQL-$table_class_name-$function-$args_md5" ;
        $this->table_class_name=$table_class_name;
        $this->function=$function;
        $this->args=$args;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $class=new $table_class_name();
        $class->switch_tongji_database();
        //($class-> $function );
        $data = array([
            "status" => "start",
            "start_time" => time(),
            "end_time" => 0 ,
            "data" => "",
        ]);
        \App\Helper\Common::redis_set_json($store_key,$data);
        $ret=call_user_func_array( array($class, $function ), $args );

        $data["end_time"] = time();
        $data["data"]     = $ret;
        $data["status"]   = "end";
        \App\Helper\Common::redis_set_json($store_key,$data);

    }
}
