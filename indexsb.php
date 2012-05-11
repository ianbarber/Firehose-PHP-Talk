<?php
$m = new Mongo();
$c = $m->starbucks->locations;
$c->ensureIndex(array("loc" => "2d"));

// http://geocommons.com/overlays/3098
$locations = array();
$fh = fopen("3098.csv", 'r');
$data = fgetcsv($fh); // skip headers
// city, lat, lon, address
while($data = fgetcsv($fh)) {
    $obj = array('street' => $data[3], 'loc' => array((float)$data[2], (float)$data[1]));
    $c->insert($obj);
}