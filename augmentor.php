<?php

$zk = new Zookeeper();
$zk->connect("localhost:2181");

function register_service($zk, $service_type) {
    $path = "/services";
    if(!$zk->exists($path)) {
        $zk->create($path, "", array(array("perms" => Zookeeper::PERM_ALL, "scheme" => "world", "id" => "anyone")));
    }
    
    $path = $path . "/" . $service_type;
    if(!$zk->exists($path)) {
        $zk->create($path, "", array(array("perms" => Zookeeper::PERM_ALL, "scheme" => "world", "id" => "anyone")));
    }
    
    $zk->create($path . "/" . uniqid(), NULL, array(array("perms" => Zookeeper::PERM_ALL, "scheme" => "world", "id" => "anyone")), Zookeeper::EPHEMERAL);
}

function start_service() {
    $ctx = new ZMQContext();
    $sock = $ctx->getSocket(ZMQ::SOCKET_SUB);
    $sock->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
    $sock->connect("tcp://localhost:5577");
    
    $redis = new Redis();
    $redis->connect("localhost", 6379);
    $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
    
    $source = source();
    
    while( $data = $sock->recv() ) {
        echo "Got message\n";
        $data = json_decode($data, true);
        $result = augment($data, $source);
        $redis->lpush($data['id'], json_encode($result));
    }
}