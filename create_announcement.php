<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = $_SESSION["UserID"];

/* USER + ROLE */
$stmt = $mysqli->prepare("SELECT email, FirstName, is_admin FROM Persons WHERE UserID=?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$isAdmin = ($user["is_admin"] == 1);

if(!$isAdmin){
    die("Access denied");
}

/* POST ANNOUNCEMENT */
if(isset($_POST["submit"])){

    $title = $_POST["title"];
    $content = $_POST["content"];

    $stmt = $mysqli->prepare("INSERT INTO announcements(title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();

    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Announcement</title>

<style>
body {
    margin:0;
    font-family:Segoe UI;
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    color:white;
}

.navbar{
    display:flex;
    justify-content:space-between;
    padding:15px;
    background:rgba(0,0,0,0.6);
}

.container{
    width:50%;
    margin:40px auto;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    padding:25px;
    border-radius:15px;
}

input,textarea{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
}

textarea{height:150px;resize:none;}

button{
    background:#00f2fe;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="navbar">
    <div>Create Announcement</div>
    <div>
        <a href="main(NS).php" style="color:white;">Home</a>
    </div>
</div>

<div class="container">

<h2>📢 New Announcement</h2>

<form method="POST">

<input type="text" name="title" placeholder="Title" required>

<textarea name="content" placeholder="Content" required></textarea>

<button name="submit">Publish</button>

</form>

</div>

</body>
</html>