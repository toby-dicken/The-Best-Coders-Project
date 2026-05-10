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

$sql = "SELECT m.id, m.subject, m.body, m.created_at, m.is_read,
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
    font-family: Arial, sans-serif;
    background: #f1f3f4;
    margin: 0;
    color: #202124;
}

/* NAVBAR */
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
    width: 80%;
    margin: 25px auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    padding: 10px 0;
}

/* EMAIL ROW */
.email-row {
    display: flex;
    align-items: center;
    padding: 12px 18px;
    border-bottom: 1px solid #eef1f5;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s ease;
}

.email-row:hover {
    background: #f5f7fb;
}

/* UNREAD STYLE */
.unread {
    background: #eef3fd;
    border-left: 4px solid #1a73e8;
}

.unread .email-subject {
    font-weight: 700;
}

/* SENDER */
.email-sender {
    width: 200px;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* CONTENT */
.email-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-right: 10px;
}

.email-subject {
    font-size: 14px;
}

.email-preview {
    font-size: 13px;
    color: #5f6368;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* TIME */
.email-time {
    width: 80px;
    text-align: right;
    font-size: 12px;
    color: #5f6368;
}

/* USER INFO */
.user-info {
    padding: 10px 18px;
    font-size: 13px;
    color: #5f6368;
    border-bottom: 1px solid #eee;
}
</style>
</head>

<body>

<div class="navbar">
    <div>Inbox</div>
    <div>
        <a href="main(NS).php">Home</a>
        <a href="compose(NS).php">Compose</a>
        <a href="logout(NS).php">Logout</a>
    </div>
</div>

<div class="container">

<div class="user-info">
    Logged in as: <b><?php echo htmlspecialchars($user["email"]); ?></b>
</div>

<?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) { 
        $class = $row["is_read"] ? "email-row" : "email-row unread";
    ?>
        <a href="read(NS).php?id=<?php echo $row["id"]; ?>" class="<?php echo $class; ?>">

            <div class="email-sender">
                <?php echo htmlspecialchars($row["sender_email"]); ?>
            </div>

            <div class="email-content">
                <div class="email-subject">
                    <?php echo htmlspecialchars($row["subject"]); ?>
                </div>

                <div class="email-preview">
                    <?php
                        $snippet = substr(strip_tags($row["body"]), 0, 120);
                        echo htmlspecialchars($snippet);
                        if (strlen($row["body"]) > 120) echo "...";
                    ?>
                </div>
            </div>

            <div class="email-time">
                <?php echo date("M d", strtotime($row["created_at"])); ?>
            </div>

        </a>
    <?php } ?>
<?php } else { ?>
    <div style="padding:15px;">No messages yet.</div>
<?php } ?>

</div>

</body>
</html>