<?php

$data = array('id' => 1, 'a' => 'a', 'b' => 'xyz', 'c' => array(1, 2, "abcdefg", array(5, 7, 8)));

file_put_contents('/tmp/json.json', json_encode($data));

file_put_contents('/tmp/msg.pack', msgpack_pack($data));

var_dump($data);

$d = msgpack_pack($data);
var_dump(msgpack_unpack($d));
$time = microtime(true);
for($i = 0; $i < 10000; $i++) {
    $a = msgpack_unpack($d);
}
echo microtime(true) - $time, "\n";

$d = json_encode($data);
var_dump(json_decode($d));
$time = microtime(true);
for($i = 0; $i < 10000; $i++) {
    $a = json_decode($d);
}
echo microtime(true) - $time, "\n";