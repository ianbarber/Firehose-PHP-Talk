<?php
include_once "augmentor.php";

function source() {
    $m = new Mongo();
    $c = $m->starbucks->locations;
    return $c;
}

function augment($data, $c) {
    $lat = $data['lat'];
    $lon = $data['lon'];
    
    $res = $c->findOne(array('loc' => array('$near' => array((float)$lon, (float)$lat))));
    return array('name' => 'starbucks', 'val' => $res['street']);
}

register_service($zk, "starbucks");
start_service();