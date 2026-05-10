<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // must be first

$servername = "localhost";
$username   = "admin";
$password   = "pass"; // THIS MUST BE PATCHED FOR SPRINT 2, VERY INSECURE
$dbname     = "wlv";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "DB connection failed"]));
}

$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);

$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($events);
?>
