<?php
$mysqli = new mysqli("localhost","2435269","University1039","db2435269");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>


<?php
// Database 1
$db1 = new mysqli("localhost","2452032","Neetu@123","db2452032");
if ($db1->connect_errno) {
    echo "Failed to connect to DB1: " . $db1->connect_error;
    exit();
}