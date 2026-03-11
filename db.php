
<?php
// Database 1
$db1 = new mysqli("localhost","2444208","nima2006","db2444208");
if ($db1->connect_errno) {
    echo "Failed to connect to DB1: " . $db1->connect_error;
    exit();
}

// Database 2
$db2 = new mysqli("localhost","2435269","University1039","db2435269");
if ($db2->connect_errno) {
    echo "Failed to connect to DB2: " . $db2->connect_error;
    exit();
}

// Database 3
$db3 = new mysqli("localhost","2452032","Neetu@123","db2452032");
if ($db3->connect_errno) {
    echo "Failed to connect to DB3: " . $db3->connect_error;
    exit();
}
?>

