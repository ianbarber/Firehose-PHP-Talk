<?php
// sudo pear install Text_LanguageDetect-alpha
require_once 'Text/LanguageDetect.php';
include_once "augmentor.php";

function source() {
    $ld = new Text_LanguageDetect();
    $ld->setNameMode(2);
    
    return $ld;
}

function augment($data, $ld) {
   /*
    array(1) {
      ["en"]=>
      float(0.24702222222222)
    } */
    $names = $ld->detect($data['text'], 1);
    return array('name' => 'lang', 'val' => key($names));
}

register_service($zk, "langdetect");
start_service();