<?php
namespace App\Flow;

use \App\Enums as E;

class flow_base_new{

    /**
     * @return \App\Console\Tasks\TaskController
     */
    static function get_task_controler() {
        return new \App\Console\Tasks\TaskController ();
    }

    static function get_adminid_by_account($account) {
        $task=static::get_task_controler();
        return $task->t_manager_info->get_adminid_by_account($account);
    }


}
