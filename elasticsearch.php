<?php

/* Some elastic search setup: 
curl -XPUT 'localhost:9200/twitter'
-> {"ok":true,"acknowledged":true}
*/


function escall($server, $path, $http) {
  return json_decode(
      file_get_contents(
        $server . '/' . $path, NULL, 
        stream_context_create(array('http' => $http))), true);
}

function register_query($host, $path, $name, $query) {
    $query = array('query' => array('field' => array('tweet' => $query)));
    return escall($host, $path . "/" . $name, array('method' => 'PUT', 'content' => json_encode($query)));
}

function percolate($host, $path, $tweet) {
    $tweet = array('doc' => array('tweet' => $tweet['text']));
    $matches = escall($host, $path, array('content' => json_encode($tweet)));
    return $matches['matches'];
}

$ctx = new ZMQContext();

$in = $ctx->getSocket(ZMQ::SOCKET_SUB);
$in->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
$in->connect("tcp://localhost:5544");

$out = $ctx->getSocket(ZMQ::SOCKET_PUB);
$out->connect("tcp://localhost:5511");

$ctl = $ctx->getSocket(ZMQ::SOCKET_PULL);
$ctl->connect("tcp://localhost:5533");

$host = "http://localhost:9200";

$poll = new ZMQPoll();
$poll->add($in, ZMQ::POLL_IN);
$poll->add($ctl, ZMQ::POLL_IN);
$read = $write = array();

while(true) {
    $ev = $poll->poll($read, $write, -1);
    if($ev > 0) {
        if($read[0] === $in) {
            $t = $in->recv();
            $matches = percolate($host, "/twitter/tweet/_percolate", json_decode($t, true));
            foreach($matches as $match) {
                echo "Tweet matched " , $match, "\n";
                $out->sendMulti(array($match, $t));
            }
        } else if($read[0] === $ctl) {
            $q = json_decode($ctl->recv(), true);
            $res = register_query($host, '_percolator/twitter', $q['name'], $q['query']);
            echo "Registered filter for " , $res['_id'], "\n";
        }
    }
}