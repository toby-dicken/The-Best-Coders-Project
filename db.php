<?php
$mysqli = new mysqli("localhost","2435269","University1039","db2435269");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>


