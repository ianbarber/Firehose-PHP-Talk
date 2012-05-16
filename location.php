<?php
$guid = uniqid();
setcookie("uid", $guid, time() + (60*60*24*365));
?><!DOCTYPE html>
<html>
<head>
    <title>Location Updater</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
<script>
    function sendPos() {
        navigator.geolocation.getCurrentPosition( 
            function(pos) { 
                $.ajax({
                    type: 'POST',
                    url: 'http://general.local/fh/input.php',
                    data: {lat: pos.coords.latitude, lon: pos.coords.longitude} 
                });
            });
        setTimeout(sendPos, 60000);
    }
    sendPos();
</script>
</body>
</html>