<?php
namespace App\Models;
use \App\Enums as E;
class t_log_event_type extends \App\Models\Zgen\z_t_log_event_type
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_event_type_id_by_event_name(  $project, $sub_project, $event_name )  {
        $sql= $this->gen_sql_new(
            "select event_type_id from %s "
            . " where project='%s' and sub_project='%s' and event_name= '%s' ",
            self::DB_TABLE_NAME,
            $project, $sub_project, $event_name
        );
        return $this->main_get_value($sql);
    }

    public function get_event_type_id_with_check( $project, $sub_project, $event_name ) {
        $event_type_id= $this->get_event_type_id_by_event_name ( $project, $sub_project, $event_name);
        if ($event_type_id) {
            return $event_type_id;
        }
        $this->row_insert ([
            "project" => $project,
            "sub_project" => $sub_project,
            "event_name" => $event_name,
        ]);
        return $this->get_last_insertid();
    }

    public function get_event_type_id_list ( $project, $sub_project ){
        $sql= $this->gen_sql_new(
            "select event_type_id"
            . " from %s "
            . " where project='%s' and sub_project='%s'  ",
            self::DB_TABLE_NAME,
            $project, $sub_project
        );

        $list= $this->main_get_list($sql);
        $ret_list=[];
        foreach($list as $item ) {
            $ret_list[]= $item["event_type_id"];
        }
        return $ret_list;
    }

}
