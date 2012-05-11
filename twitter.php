<?php

$msg = <<<EOF
{"text":"RT @xdebug: Xdebug is 10 years old today and to celebrate that I've released Xdebug 2.2! http:\/\/t.co\/tJjjSLSO #linktuesday","in_reply_to_status_id_str":null,"in_reply_to_status_id":null,"in_reply_to_user_id_str":null,"retweeted":false,"in_reply_to_user_id":null,"possibly_sensitive":false,"id_str":"199949412917719044","truncated":false,"source":"\u003Ca href=\"http:\/\/twitter.com\/#!\/download\/ipad\" rel=\"nofollow\"\u003ETwitter for iPad\u003C\/a\u003E","geo":null,"in_reply_to_screen_name":null,"retweeted_status":{"text":"Xdebug is 10 years old today and to celebrate that I've released Xdebug 2.2! http:\/\/t.co\/tJjjSLSO #linktuesday","in_reply_to_status_id_str":null,"in_reply_to_status_id":null,"in_reply_to_user_id_str":null,"retweeted":false,"in_reply_to_user_id":null,"possibly_sensitive":false,"id_str":"199846188734877696","truncated":false,"source":"\u003Ca href=\"http:\/\/software.complete.org\/twidge\" rel=\"nofollow\"\u003Etwidge\u003C\/a\u003E","geo":null,"in_reply_to_screen_name":null,"retweet_count":28,"favorited":false,"created_at":"Tue May 08 13:00:19 +0000 2012","entities":{"urls":[{"indices":[77,97],"display_url":"drck.me\/xdebug-10-9eo","expanded_url":"http:\/\/drck.me\/xdebug-10-9eo","url":"http:\/\/t.co\/tJjjSLSO"}],"hashtags":[{"text":"linktuesday","indices":[98,110]}],"user_mentions":[]},"contributors":null,"user":{"default_profile_image":false,"show_all_inline_media":false,"notifications":null,"friends_count":1,"profile_text_color":"333333","following":null,"profile_background_image_url_https":"https:\/\/si0.twimg.com\/images\/themes\/theme14\/bg.gif","profile_background_image_url":"http:\/\/a0.twimg.com\/images\/themes\/theme14\/bg.gif","favourites_count":0,"location":"London","follow_request_sent":null,"profile_link_color":"009999","description":"Xdebug debugging extension for PHP","id_str":"23243559","profile_background_color":"131516","listed_count":178,"profile_background_tile":true,"profile_sidebar_fill_color":"efefef","profile_image_url_https":"https:\/\/si0.twimg.com\/profile_images\/90008128\/xdebug24_normal.png","default_profile":false,"protected":false,"is_translator":false,"contributors_enabled":false,"statuses_count":322,"geo_enabled":false,"created_at":"Sat Mar 07 22:36:40 +0000 2009","profile_sidebar_border_color":"eeeeee","screen_name":"xdebug","name":"Xdebug","lang":"en","time_zone":"London","followers_count":1710,"url":"http:\/\/xdebug.org","profile_image_url":"http:\/\/a0.twimg.com\/profile_images\/90008128\/xdebug24_normal.png","id":23243559,"verified":false,"profile_use_background_image":true,"utc_offset":0},"id":199846188734877696,"possibly_sensitive_editable":true,"place":null,"coordinates":null},"retweet_count":28,"favorited":false,"created_at":"Tue May 08 19:50:29 +0000 2012","entities":{"urls":[{"indices":[89,109],"display_url":"drck.me\/xdebug-10-9eo","expanded_url":"http:\/\/drck.me\/xdebug-10-9eo","url":"http:\/\/t.co\/tJjjSLSO"}],"hashtags":[{"text":"linktuesday","indices":[110,122]}],"user_mentions":[{"indices":[3,10],"id_str":"23243559","name":"Xdebug","screen_name":"xdebug","id":23243559}]},"contributors":null,"user":{"default_profile_image":false,"show_all_inline_media":true,"notifications":null,"friends_count":263,"profile_text_color":"333333","following":null,"profile_background_image_url_https":"https:\/\/si0.twimg.com\/images\/themes\/theme14\/bg.gif","profile_background_image_url":"http:\/\/a0.twimg.com\/images\/themes\/theme14\/bg.gif","favourites_count":1302,"location":"New York","follow_request_sent":null,"profile_link_color":"009999","description":"Support Engineering Department Lead @adotube.com (highload, javascript, php, mysql, node.js, redis). #wp7 #ios #mac","id_str":"66209189","profile_background_color":"131516","listed_count":52,"profile_background_tile":true,"profile_sidebar_fill_color":"efefef","profile_image_url_https":"https:\/\/si0.twimg.com\/profile_images\/1701422602\/petr_normal.jpg","default_profile":false,"protected":false,"is_translator":false,"contributors_enabled":false,"statuses_count":22285,"geo_enabled":true,"created_at":"Sun Aug 16 22:33:52 +0000 2009","profile_sidebar_border_color":"eeeeee","screen_name":"sedictor","name":"Vadim","lang":"en","time_zone":"Irkutsk","followers_count":738,"url":"http:\/\/sedictor.ru","profile_image_url":"http:\/\/a0.twimg.com\/profile_images\/1701422602\/petr_normal.jpg","id":66209189,"verified":false,"profile_use_background_image":true,"utc_offset":32400},"id":199949412917719044,"possibly_sensitive_editable":true,"place":null,"coordinates":null}
EOF;


// $ctx = new ZMQContext();
// $sock = $ctx->getSocket(ZMQ::SOCKET_PUB);
// $sock->bind("tcp://*:5544");
// sleep(5);
// $sock->send($msg);
// 
// exit();

$user = "ADDME"; $pass = "EATME";
$ctx = new ZMQContext();
$sock = $ctx->getSocket(ZMQ::SOCKET_PUB);
$sock->bind("tcp://*:5544");
sleep(1); // allow conns to stabilise
$fh = fopen("https://".$user.":".$pass."@stream.twitter.com/1/statuses/filter.json?track=xdebug", "r");

if($fh) {
    while(!feof($fh)) {
        $data = fgets($fh);
        if(strlen($data) > 4) {
            $sock->send($data);
        }
        //echo $data, "\n\n";
    }
    fclose($fh);
}