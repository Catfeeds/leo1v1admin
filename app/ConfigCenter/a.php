<?php
$redis=new \Redis();
$redis->connect("127.0.0.1");
$redis->select(9);

$str=json_encode([
    "ip" => "192.168.0.53",
    "port" => 5222,
    "report_time" => time(NULL),
]);
#$redis->hset("account_server", "192.168.0.53-46001" ,  $str  );
#$redis->hset("account_server", "192.168.0.53-26001" ,  $str  );
while (true) {
    echo " do once \n";
    try {
        $redis->select(9);
        print_r($redis->hKeys("account_server"));
    }catch( \RedisException $e ) {
        echo "error \n";
        $redis->close();
        $redis->connect("127.0.0.1");
    }
    sleep (1);
}
print_r($redis->hGetAll("account_server"));

print_r($redis->keys("*"));
