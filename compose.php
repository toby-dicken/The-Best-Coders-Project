<?php
session_start();
if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Compose</title>
</head>
<body>

<h2>Compose Message</h2>

<form action="send.php" method="POST">
    <label>Receiver Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Subject:</label><br>
    <input type="text" name="subject" required><br><br>

    <label>Message:</label><br>
    <textarea name="body" required></textarea><br><br>

    <button type="submit">Send</button>
</form>

</body>
</html>