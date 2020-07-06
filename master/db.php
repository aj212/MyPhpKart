<?php
// Server connection info
define('DB_SERVER', 'classdb.it.mtu.edu');
define('DB_USERNAME', 'zcbonus');
define('DB_PASSWORD', 'Linear.327');
define('DB_NAME', 'zcbonus');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
