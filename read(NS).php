<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    die("Not logged in");
}

$user_id = $_SESSION["UserID"];
$message_id = $_GET["id"];

// Secure message fetch
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

    // Mark as read (SAFE VERSION)
    $update = $mysqli->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $update->bind_param("i", $message_id);
    $update->execute();

} else {
    die("Message not found or access denied.");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Read Message</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f1f3f4;
    margin: 0;
    color: #202124;
}

/* NAVBAR (MATCH INBOX STYLE) */
.navbar {
    background: #ffffff;
    border-bottom: 1px solid #e0e0e0;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    font-weight: 600;
}

.navbar a {
    color: #1a73e8;
    margin-left: 15px;
    text-decoration: none;
    font-weight: 500;
}

/* CONTAINER */
.container {
    width: 70%;
    margin: 25px auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.1);
    padding: 0;
    overflow: hidden;
}

/* HEADER SECTION */
.header {
    padding: 18px 20px;
    border-bottom: 1px solid #eaecef;
}

/* SUBJECT */
.subject {
    font-size: 20px;
    font-weight: 500;
}

/* META INFO BAR */
.meta {
    padding: 12px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eaecef;
    font-size: 13px;
    color: #5f6368;
}

.meta b {
    color: #202124;
}

/* MESSAGE BODY */
.body {
    padding: 20px;
    font-size: 14px;
    line-height: 1.6;
    white-space: pre-wrap;
}

/* BACK BUTTON */
.back {
    padding: 12px 20px;
    border-bottom: 1px solid #eaecef;
}

.back a {
    color: #1a73e8;
    text-decoration: none;
    font-size: 14px;
}
</style>

</head>

<body>

<div class="navbar">
    <div>View Message</div>
    <div>
        <a href="inbox(NS).php">Inbox</a>
        <a href="compose(NS).php">Compose</a>
        <a href="logout(NS).php">Logout</a>
    </div>
</div>

<div class="container">

<div class="back">
    <a href="inbox(NS).php">← Back to Inbox</a>
</div>

<div class="header">
    <div class="subject">
        <?php echo htmlspecialchars($msg["subject"]); ?>
    </div>
</div>

<div class="meta">
    <b>From:</b> <?php echo htmlspecialchars($msg["sender_email"]); ?><br>
    <b>Date:</b> <?php echo $msg["created_at"]; ?>
</div>

<div class="body">
    <?php echo nl2br(htmlspecialchars($msg["body"])); ?>
</div>

</div>

</body>
</html>