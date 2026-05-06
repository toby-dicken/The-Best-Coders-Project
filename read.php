<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    die("Not logged in");
}

$user_id = $_SESSION["UserID"];
$message_id = $_GET["id"];

// Get message securely
$sql = "SELECT messages.*, Persons.email AS sender_email
        FROM messages
        JOIN Persons ON messages.sender_id = Persons.UserID
        WHERE messages.id = ? AND messages.receiver_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $message_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $msg = $result->fetch_assoc();

    // Mark as read
    $mysqli->query("UPDATE messages SET is_read = 1 WHERE id = $message_id");

    echo "<h2>".$msg["subject"]."</h2>";
    echo "<p><b>From:</b> ".$msg["sender_email"]."</p>";
    echo "<p>".$msg["body"]."</p>";

} else {
    echo "Message not found or access denied.";
}
?>