<?php
namespace App\Models;
use \App\Enums as E;
class t_xmpp_server_config extends \App\Models\Zgen\z_t_xmpp_server_config
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_list( $page_info, $server_name="" ) {
        $where_arr =[
            ["server_name='%s'", $server_name, "" ],
        ];
        $sql= $this->gen_sql_new(
            "select * from %s where %s ",
            self::DB_TABLE_NAME, $where_arr );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_xmpp_id($xmpp_value){
        $sql = $this->gen_sql_new(" select id "
                                  ." from %s "
                                  ." where server_name ='%s' ",
                                  self::DB_TABLE_NAME ,
                                  $xmpp_value);
        return $this->main_get_value($sql);

    }

    public function get_info_by_server_name($server_name){

        /*
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `server_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '服务器名称: q_27 ..',
          `server_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '说明 ',
          `ip` varchar(20) COLLATE latin1_bin NOT NULL COMMENT 'ip',
          `xmpp_port` int(11) NOT NULL COMMENT 'xmpp_port',
          `webrtc_port` int(11) NOT NULL COMMENT 'dobango webrtc_port',
          `websocket_port` int(11) NOT NULL COMMENT '网页上直接看课程',
          `weights` int(11) NOT NULL COMMENT '权值',
        */

        $sql=$this->gen_sql_new(
            "select id,server_name,server_desc,ip,xmpp_port,webrtc_port,websocket_port,weights"
            . " from %s where server_name='%s' ",  self::DB_TABLE_NAME, $server_name );

        return $this->main_get_row($sql);
    }

    public function get_server_name_map(){
        /*
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `server_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '服务器名称: q_27 ..',
          `server_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '说明 ',
          `ip` varchar(20) COLLATE latin1_bin NOT NULL COMMENT 'ip',
          `xmpp_port` int(11) NOT NULL COMMENT 'xmpp_port',
          `webrtc_port` int(11) NOT NULL COMMENT 'dobango webrtc_port',
          `websocket_port` int(11) NOT NULL COMMENT '网页上直接看课程',
          `weights` int(11) NOT NULL COMMENT '权值',
        */

        $sql=$this->gen_sql_new(
            "select id,server_name,server_desc,ip,xmpp_port,webrtc_port,websocket_port,weights"
            . " from %s  ",  self::DB_TABLE_NAME );

        return $this->main_get_list($sql,function($item){
            return $item["server_name"];
        });
    }


}
