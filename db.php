
<?php
// Database 1
$db1 = new mysqli("localhost","2444208","nima2006","db2444208");
if ($db1->connect_errno) {
    echo "Failed to connect to DB1: " . $db1->connect_error;
    exit();
}
?>

