<?php
require_once "./XMPPOperator.php";

$config=[
    "ip"=> "120.26.58.183",
    "xmpp_port" =>5222,
] ;
$xmpp_server = new \XMPPOperator($config['ip'], $config['xmpp_port'],
                                 "sys_user", "xx",
                                 $config['ip']);
$ret=$xmpp_server->get_room_user("l_01011");


print_r($ret);
    #public function get_room_user($roomid)
