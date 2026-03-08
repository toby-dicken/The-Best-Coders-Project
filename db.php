<?php
$mysqli = new mysqli("localhost","2444208","nima2006","db2444208");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>
