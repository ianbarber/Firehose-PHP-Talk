<?php

include_once "Kafka/Request.php";
include_once "Kafka/RequestKeys.php";
include_once "Kafka/MessageSet.php";
include_once "Kafka/Message.php";
include_once "Kafka/BoundedByteBuffer/Send.php";
include_once "Kafka/BoundedByteBuffer/Receive.php";
include_once "Kafka/SimpleConsumer.php";
include_once "Kafka/FetchRequest.php";
$topic = $_GET['topic'] ?: 'ian';
$offset = $_GET['offset'] ?: 0;

$max = 1024*1024;
// 1 sec timeout
$c = new Kafka_SimpleConsumer('localhost', 9092, 1, $max);
do {
    $req = new Kafka_FetchRequest($topic, 0, $offset, $max);
    $msgs = $c->fetch($req);
    foreach($msgs as $msg) {
        echo $msg->payload(), "\n";
    }
    $offset += $msgs->validBytes();
    unset($req);
} while($msgs->validBytes() > 0);

echo json_encode(array("consumed" => $offset)), "\n";