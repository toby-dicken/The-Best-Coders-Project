<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "2444208";

$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>