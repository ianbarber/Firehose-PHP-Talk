<?php
define("TIMEOUT", 5);

$ctx = new ZMQContext();
$sock = $ctx->getSocket(ZMQ::SOCKET_SUB);
$sock->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
$sock->connect("tcp://localhost:5577");

$ctx = new ZMQContext();
$out = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$out->connect("tcp://localhost:5599");

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);

$zk = new Zookeeper();
$zk->connect("localhost:2181");
//Zookeeper::setDebugLevel(Zookeeper::LOG_LEVEL_DEBUG);

// Could set a watch callback here and update on changes
$ch = $zk->getChildren("/services");
$num_servs = count($ch);

while( $data = $sock->recv() ) {
    echo "Got message\n";
    $data = json_decode($data, true);
    $data['aug'] = array();
    $time_rem = TIMEOUT;
    
    do {
        $start = microtime(true);
        $aug = $redis->brpop($data['id'], intval($time_rem));
        if(is_array($aug) && isset($aug[1])) {
            $aug = json_decode($aug[1], true);
            $data['aug'][] = $aug;
        }
        $time_rem -= microtime(true) - $start;
    } while( $time_rem > 0 && count($data['aug']) != $num_servs);
    
    // Forward on to output
    $out->send(json_encode($data));
}