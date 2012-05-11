<?php
include_once "Kafka/Producer.php";
include_once "Kafka/Encoder.php";
define('PRODUCE_REQUEST_ID', 0);

$ctx = new ZMQContext();
$in = $ctx->getSocket(ZMQ::SOCKET_SUB);
$in->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "");
$in->bind("tcp://*:5500");
$producer = new Kafka_Producer("localhost", 9092);

while ($data = $in->recvMulti()) {
	$topic = $data[0];
	$msg = $data[1];
	$bytes = $producer->send(array($msg), $topic);
	printf("Successfully sent %d bytes\n", $bytes);
}
