<?php
session_start();
include("db.php");

/* -------------------------
   LOGIN CHECK
--------------------------*/
if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$sender_id = $_SESSION["UserID"];

/* -------------------------
   GET FORM DATA
--------------------------*/
$receiver_email = trim($_POST["email"]);
$subject = trim($_POST["subject"]);
$body = trim($_POST["body"]);

/* VALIDATION */
if (empty($receiver_email) || empty($subject) || empty($body)) {
    echo "<p style='color:red;'>All fields are required.</p>";
    echo "<a href='compose.php'>Go Back</a>";
    exit;
}

/* -------------------------
   CHECK IF USER EXISTS
--------------------------*/
$stmt = $mysqli->prepare("SELECT UserID FROM Persons WHERE email = ?");
$stmt->bind_param("s", $receiver_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p style='color:red;'>User not found!</p>";
    echo "<a href='compose.php'>Try Again</a>";
    exit;
}

/* -------------------------
   USER EXISTS → SEND MESSAGE
--------------------------*/
$receiver = $result->fetch_assoc();
$receiver_id = $receiver["UserID"];

/* INSERT MESSAGE */
$stmt = $mysqli->prepare("
    INSERT INTO messages (sender_id, receiver_id, subject, body) 
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiss", $sender_id, $receiver_id, $subject, $body);

if ($stmt->execute()) {
    echo "<p style='color:green;'>Message sent successfully!</p>";
    echo "<a href='inbox.php'>Go to Inbox</a>";
} else {
    echo "<p style='color:red;'>Error sending message.</p>";
}
?>