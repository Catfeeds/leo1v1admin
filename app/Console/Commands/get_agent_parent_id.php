<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_agent_parent_id extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_agent_parent_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取agent表中的父ID';

    protected $map = [];

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
        //dd('调用成功');
        $task = new \App\Console\Tasks\TaskController();
        $res = $task->t_teacher_lecture_appointment_info->get_recruit_name();
        foreach($res as $item) {
            echo $item['tname'].'     '.$item['name'].PHP_EOL;
        }
        exit;

        // 根据老师名字来招师名

        $paret_map=$task->t_agent->get_parent_map();
        foreach($paret_map as $id => $v ) {
            list( $error_flag, $id_map )=$this->get_id($id, $paret_map);
            if ($error_flag) {
                echo "id $id: error_flag:$error_flag, list:  ". join(",", array_keys($id_map) ). "\n";
            }
        }
        // $this->get_id($res);
    }

    function get_id($id , $parent_map )
    {
        $id_map=[ ];
        $id_map[$id]=true;
        $error_flag=false;
        $tmpid=$id;
        do{
            $tmpid=@$parent_map[$tmpid]["parentid"];
            if (isset ($id_map[ $tmpid]) ) {
                $error_flag=true;
                break;
            }
            $id_map[$tmpid] =true;

        }while (!$tmpid==0 );
        return array( $error_flag,  $id_map   );
    }
}
