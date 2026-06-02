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

<style>
body {
    font-family: Arial, sans-serif;
    background: #f1f3f4;
    margin: 0;
    color: #202124;
}

/* NAVBAR (MATCH INBOX EXACTLY) */
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

/* PAGE WRAPPER */
.container {
    width: 75%;
    margin: 25px auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    padding: 25px;
}

/* HEADER (MATCH INBOX STYLE FEEL) */
.header {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 20px;
}

/* LABELS */
label {
    font-size: 13px;
    color: #5f6368;
    display: block;
    margin-bottom: 5px;
}

/* INPUTS (GMAIL STYLE) */
input[type="email"],
input[type="text"],
textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 18px;
    border: 1px solid #dadce0;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
    font-family: inherit;
}

input:focus,
textarea:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 0 2px #e8f0fe;
}

/* MESSAGE BOX */
textarea {
    height: 200px;
    resize: none;
}

/* SEND BUTTON (MATCH INBOX BLUE STYLE) */
button {
    background: #1a73e8;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #1558b0;
}

/* SMALL FOOTER AREA LIKE GMAIL ACTION BAR FEEL */
.actions {
    display: flex;
    justify-content: flex-end;
}
</style>
</head>

<body>

<div class="navbar">
    <div>Compose</div>
    <div>
        <a href="inbox(NS).php">Inbox</a>
        <a href="logout(NS).php">Logout</a>
    </div>
</div>

<div class="container">

<div class="header">New Message</div>

<form action="send(NS).php" method="POST">

    <label>To</label>
    <input type="email" name="email" placeholder="Recipient email" required>

    <label>Subject</label>
    <input type="text" name="subject" placeholder="Subject" required>

    <label>Message</label>
    <textarea name="body" placeholder="Write your message..." required></textarea>

    <div class="actions">
        <button type="submit">Send</button>
    </div>

</form>

</div>

</body>
</html>