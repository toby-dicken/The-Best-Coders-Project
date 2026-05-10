<?php
session_start();
include("db.php");

// ---------------- AUTH CHECK ----------------
if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = (int)$_SESSION["UserID"];

// ---------------- USER ----------------
$stmt = $mysqli->prepare("SELECT email, FirstName, role FROM Persons WHERE UserID=?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found");
}

$isAdmin = ($user["role"] === "admin");

// ---------------- VIEW MODE ----------------
if (!isset($_SESSION["view_mode"])) {
    $_SESSION["view_mode"] = $user["role"];
}
$viewMode = $_SESSION["view_mode"];

// ---------------- HELPERS ----------------
function e($str) {
    return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}

// ---------------- CREATE CLUB ----------------
if (isset($_POST["create_club"])) {

    if (!$isAdmin || $viewMode !== "admin") {
        die("Access denied");
    }

    $name = trim($_POST["name"] ?? "");
    $desc = trim($_POST["description"] ?? "");
    $category = trim($_POST["category"] ?? "General");

    if ($name !== "") {
        $stmt = $mysqli->prepare("INSERT INTO clubs(name, description, category, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $desc, $category, $userID);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------------- JOIN CLUB ----------------
if (isset($_POST["join"])) {
    $clubID = (int)$_POST["clubID"];

    $stmt = $mysqli->prepare("SELECT 1 FROM club_members WHERE userID=? AND clubID=?");
    $stmt->bind_param("ii", $userID, $clubID);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $mysqli->prepare("INSERT INTO club_members(userID, clubID) VALUES (?, ?)");
        $stmt->bind_param("ii", $userID, $clubID);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------------- LEAVE CLUB ----------------
if (isset($_POST["leave"])) {
    $clubID = (int)$_POST["clubID"];

    $stmt = $mysqli->prepare("DELETE FROM club_members WHERE userID=? AND clubID=?");
    $stmt->bind_param("ii", $userID, $clubID);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------------- POST MESSAGE (ONLY IF JOINED) ----------------
if (isset($_POST["post"])) {

    $clubID = (int)$_POST["clubID"];
    $content = trim($_POST["content"] ?? "");

    // check membership server-side
    $stmt = $mysqli->prepare("SELECT 1 FROM club_members WHERE userID=? AND clubID=?");
    $stmt->bind_param("ii", $userID, $clubID);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        die("You must join the club before posting.");
    }

    if ($content !== "") {
        $stmt = $mysqli->prepare("INSERT INTO club_posts(clubID, userID, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $clubID, $userID, $content);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------------- CLUBS ----------------
$clubs = $mysqli->query("
    SELECT c.*, p.FirstName AS creator
    FROM clubs c
    JOIN Persons p ON p.UserID = c.created_by
    ORDER BY c.created_at DESC
");

// ---------------- JOINED CLUBS ----------------
$stmt = $mysqli->prepare("SELECT clubID FROM club_members WHERE userID=?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$res = $stmt->get_result();

$joined = [];
while ($r = $res->fetch_assoc()) {
    $joined[] = (int)$r["clubID"];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Clubs</title>

<style>
body {
    margin:0;
    font-family:Segoe UI;
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    color:white;
}

.grid {display:flex;flex-wrap:wrap;gap:20px;padding:20px;}

.card {
    background: rgba(255,255,255,0.15);
    padding:15px;
    border-radius:12px;
    flex:1 1 300px;
}

button {
    padding:8px;
    border:none;
    border-radius:8px;
    background:#00f2fe;
    cursor:pointer;
}

input,textarea,select {
    width:100%;
    padding:8px;
    margin:5px 0;
}
</style>
</head>

<body>
        <div><h2 style="padding:20px;"><a href="main(NS).php">Home</a> 🎧 Clubs System</h2></div>
<div class="grid">

<!-- CREATE CLUB -->
<div class="card">
<h3>Create Club</h3>

<?php if($isAdmin && $viewMode==="admin"){ ?>
<form method="POST">
<input name="name" placeholder="Club Name" required>
<textarea name="description" placeholder="Description"></textarea>

<select name="category">
    <option>Music</option>
    <option>Sports</option>
    <option>Tech</option>
    <option>Study</option>
    <option>Gaming</option>
    <option>General</option>
</select>

<button name="create_club">Create</button>
</form>
<?php } else { ?>
<p>Admin only</p>
<?php } ?>

</div>

<!-- CLUB LIST -->
<?php while($c=$clubs->fetch_assoc()){ 
    $clubID = (int)$c["clubID"];
    $isJoined = in_array($clubID, $joined);
?>

<div class="card">

<h3>
<?php 
$cat = $c["category"];
if($cat=="Music") echo "🎵 ";
elseif($cat=="Sports") echo "🏀 ";
elseif($cat=="Tech") echo "💻 ";
else echo "📌 ";
?>
<?php echo e($c["name"]); ?>
</h3>

<p><?php echo e($c["description"]); ?></p>
<p><small>Category: <?php echo e($c["category"]); ?></small></p>

<form method="POST">
<input type="hidden" name="clubID" value="<?php echo $clubID; ?>">

<?php if($isJoined){ ?>
    <button name="leave">Leave</button>
<?php } else { ?>
    <button name="join">Join</button>
<?php } ?>

</form>

<hr>

<h4>💬 Club Feed</h4>

<!-- ONLY SHOW POST BOX IF JOINED -->
<?php if($isJoined){ ?>
<form method="POST">
<input type="hidden" name="clubID" value="<?php echo $clubID; ?>">
<textarea name="content" placeholder="Write message"></textarea>
<button name="post">Post</button>
</form>
<?php } else { ?>
<p><i>Join the club to post messages.</i></p>
<?php } ?>

<?php
$stmt=$mysqli->prepare("
SELECT cp.*, p.FirstName 
FROM club_posts cp
JOIN Persons p ON p.UserID=cp.userID
WHERE cp.clubID=?
ORDER BY cp.created_at DESC LIMIT 5
");
$stmt->bind_param("i", $clubID);
$stmt->execute();
$posts=$stmt->get_result();

while($p=$posts->fetch_assoc()){
    echo "<p><b>".e($p['FirstName']).":</b> ".e($p['content'])."</p>";
}
?>

</div>

<?php } ?>

</div>

</body>
</html>