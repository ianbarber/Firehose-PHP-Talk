<?php

$ctx = new ZMQContext(); 
$sock = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$sock->connect("tcp://localhost:5566");

$mc = new Memcache();
$mc->connect("localhost", 11211);
$mc->add("message_cnt", 0);

$data = array(
    'id' => $mc->increment("message_cnt"),
    'uid' => $_COOKIE['uid'],
    'lat' => $_POST['lat'],
    'lon' => $_POST['lon']  
);

$sock->send(json_encode($data));