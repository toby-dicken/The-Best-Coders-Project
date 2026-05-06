<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = $_SESSION["UserID"];

$stmt = $mysqli->prepare("SELECT email FROM Persons WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$sql = "SELECT m.id, m.subject, m.created_at, m.is_read,
               p.email AS sender_email
        FROM messages m
        JOIN Persons p ON m.sender_id = p.UserID
        WHERE m.receiver_id = ?
        ORDER BY m.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Inbox</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    margin: 0;
}

/* NAVBAR */
.navbar {
    background: #1e3a8a;
    color: white;
    padding: 15px;
    display:flex;
    justify-content:space-between;
}

.navbar a {
    color: white;
    margin-left: 15px;
    text-decoration: none;
}

/* CONTAINER */
.container {
    width: 70%;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* MESSAGE ITEM */
.message {
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: 0.2s;
}

.message:hover {
    background: #f1f5ff;
}

.unread {
    background: #e8f0fe;
    font-weight: bold;
}

.subject {
    font-size: 16px;
}

.meta {
    font-size: 12px;
    color: gray;
}
</style>
</head>

<body>

<div class="navbar">
    <div>Inbox</div>
    <div>
        <a href="main(NS).php">Home</a>
        <a href="compose.php">Compose</a>
        <a href="logout(NS).php">Logout</a>
    </div>
</div>

<div class="container">

<p>Logged in as: <b><?php echo htmlspecialchars($user["email"]); ?></b></p>

<?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) { 
        $class = $row["is_read"] ? "message" : "message unread";
    ?>
        <div class="<?php echo $class; ?>">
            <a href="read.php?id=<?php echo $row["id"]; ?>" style="text-decoration:none; color:black;">
                <div class="subject"><?php echo htmlspecialchars($row["subject"]); ?></div>
                <div class="meta">From: <?php echo htmlspecialchars($row["sender_email"]); ?></div>
                <div class="meta"><?php echo $row["created_at"]; ?></div>
            </a>
        </div>
    <?php } ?>
<?php } else { ?>
    <p>No messages yet.</p>
<?php } ?>

</div>

</body>
</html>