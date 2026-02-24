<?php
$mysqli = new mysqli("localhost","root","","db2435269");

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>