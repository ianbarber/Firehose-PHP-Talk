<?php
/* Dump from ingress */
// $ctx = new ZMQContext(); 
// $sock = $ctx->getSocket(ZMQ::SOCKET_PULL);
// $sock->bind("tcp://127.0.0.1:5566");
// 
// while(true) {
//     var_dump($sock->recvMulti());
// }


/* Test flow through augmentor */
// $ctx = new ZMQContext(); 
// $sock = $ctx->getSocket(ZMQ::SOCKET_PUB);
// $sock->bind("tcp://127.0.0.1:5577");
// sleep(5);
// 
// $sock->send(json_encode(array("id" => 1, "text" => "hello world, how are you doing? ")));
// 
// $sock2 = $ctx->getSocket(ZMQ::SOCKET_PULL);
// $sock2->bind("tcp://127.0.0.1:5599");
// var_dump(json_decode($sock2->recv()));


/* And for the place augmentor */
$ctx = new ZMQContext(); 
$sock = $ctx->getSocket(ZMQ::SOCKET_PUB);
$sock->bind("tcp://127.0.0.1:5577");
sleep(5);

$data = array(
    'id' => 1,
    'uid' => 1,
    'lat' => "51.495058",
    'lon' => "-0.216425"  
);

$sock->send(json_encode($data));

$sock2 = $ctx->getSocket(ZMQ::SOCKET_PULL);
$sock2->bind("tcp://127.0.0.1:5599");
var_dump(json_decode($sock2->recv()));

/* Test tweet -> elasticsearch filter -> output: add queries and listen */
// $ctx = new ZMQContext();
// $sub = $ctx->getSocket(ZMQ::SOCKET_SUB);
// $sub->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
// $sub->bind("tcp://*:5511");
// 
// $ctl = $ctx->getSocket(ZMQ::SOCKET_PUSH);
// $ctl->bind("tcp://*:5533");
// $ctl->send(json_encode(array('name' => "rasmus", "query" => "rasmus")));
// $ctl->send(json_encode(array('name' => "derick", "query" => "derickr")));
// $ctl->send(json_encode(array('name' => "xdebug", "query" => "derickr xdebug")));
// 
// while(true) {
//     list($search, $tweet) = $sub->recvMulti();
//     echo $search, "\n";
//     $tweet = json_decode($tweet, true);
//     if(isset($tweet['retweeted_status']['user']['screen_name'])) {
//         echo "\t", $tweet['retweeted_status']['user']['screen_name'], 
//             " - ", $tweet['text'], " RT by ", $tweet['user']['name'], "\n";
//     } else {
//         echo "\t", $tweet['user']['name'], " - ", $tweet['text'], "\n";
//     }
// }


// $ctx = new ZMQContext();
// $pub = $ctx->getSocket(ZMQ::SOCKET_PUB);
// $pub->connect("tcp://localhost:5500");
// sleep(1);
// $pub->sendMulti(array("ian", json_encode(array("hello" => "world"))));
// $pub->sendMulti(array("ted", json_encode(array("hello" => "world"))));
// $pub->sendMulti(array("ian", json_encode(array("hello" => "everyone"))));