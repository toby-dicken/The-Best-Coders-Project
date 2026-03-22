


<?php
// Database for toby's localhost
$mysqli = new mysqli("localhost","admin","pass","wlv");
if ($mysqli->connect_errno) {
    echo "Failed to connect to DB1: " . $mysqli->connect_error;
    exit();
}
?>

