<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = $_SESSION["UserID"];

/* USER */
$stmt = $mysqli->prepare("SELECT email, FirstName FROM Persons WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* REWARDS */
$points=0; $lastCheck=null; $checkinMessage="";
$stmt=$mysqli->prepare("SELECT points,last_checkin FROM rewards WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$res=$stmt->get_result();

if($res->num_rows==0){
    $mysqli->query("INSERT INTO rewards(UserID,points) VALUES ($userID,0)");
}else{
    $row=$res->fetch_assoc();
    $points=$row["points"];
    $lastCheck=$row["last_checkin"];
}

if(isset($_POST["checkin"])){
    $today=date("Y-m-d");
    if($lastCheck!=$today){
        $points+=10;
        $stmt=$mysqli->prepare("UPDATE rewards SET points=?,last_checkin=? WHERE UserID=?");
        $stmt->bind_param("isi",$points,$today,$userID);
        $stmt->execute();
        $lastCheck=$today;
        $checkinMessage="✅ +10 points!";
    } else {
        $checkinMessage="⚠ Already checked today";
    }
}

/* SEARCH */
$searchResults=null;
if(isset($_GET["search"])){
    $k="%".$_GET["search"]."%";
    $stmt=$mysqli->prepare("SELECT CourseName,Description FROM Courses WHERE CourseName LIKE ? OR Description LIKE ?");
    $stmt->bind_param("ss",$k,$k);
    $stmt->execute();
    $searchResults=$stmt->get_result();
}

/* EXTRA FEATURES */
$announcements=$mysqli->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");
$events=$mysqli->query("SELECT * FROM events WHERE event_date>=NOW() ORDER BY event_date ASC LIMIT 3");

$leaderboard=$mysqli->query("
SELECT Persons.FirstName,rewards.points 
FROM rewards JOIN Persons ON Persons.UserID=rewards.UserID 
ORDER BY points DESC LIMIT 5");

$posts=$mysqli->query("
SELECT posts.*,Persons.FirstName 
FROM posts JOIN Persons ON Persons.UserID=posts.userID 
ORDER BY posts.created_at DESC LIMIT 5");

/* POST SUBMIT */
if(isset($_POST["post_content"])){
    $content=$_POST["post_content"];
    $stmt=$mysqli->prepare("INSERT INTO posts(userID,content) VALUES (?,?)");
    $stmt->bind_param("is",$userID,$content);
    $stmt->execute();
    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Campus Hub</title>

<style>
body {
    margin:0;
    font-family:Segoe UI;
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    color:white;
}

/* NAV */
.navbar{
    display:flex;
    justify-content:space-between;
    padding:15px;
    background:rgba(0,0,0,0.6);
    backdrop-filter:blur(10px);
}

.nav-links a{color:white;margin:10px;text-decoration:none;}
.nav-links a:hover{color:#00f2fe}

/* HERO */
.hero{
    background:rgba(0,0,0,0.3);
    padding:25px;
    border-radius:15px;
    margin:20px;
}

/* GRID */
.grid{display:flex;flex-wrap:wrap;gap:20px;padding:20px;}

.card{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    padding:15px;
    border-radius:12px;
    flex:1 1 250px;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* BUTTON */
button{
    padding:10px;
    border:none;
    border-radius:8px;
    background:#00f2fe;
    cursor:pointer;
}

/* DARK */
.dark{background:#111;color:#eee;}
</style>
</head>

<body>

<div class="navbar">
<div>WLV | <?php echo $user["email"]; ?></div>

<div class="nav-links">
<a href="#">Home</a>
<a href="#">Courses</a>
<a href="#">Clubs</a>
<a href="#">Support</a>
</div>

<button onclick="toggleDark()">🌙</button>
</div>

<!-- HERO -->
<div class="hero">
<h1>Welcome <?php echo $user["FirstName"]; ?> 👋</h1>
<p>Campus Live Hub Dashboard</p>

<div id="clock"></div>

<p>🔥 <?php echo $points; ?> Points</p>

<form method="POST">
<button name="checkin">Daily Check-in</button>
</form>

<p><?php echo $checkinMessage; ?></p>
</div>

<div class="grid">

<!-- ANNOUNCEMENTS -->
<div class="card">
<h3>📢 Announcements</h3>
<?php while($a=$announcements->fetch_assoc()){ ?>
<p><b><?php echo $a["title"]; ?></b><br><?php echo $a["content"]; ?></p>
<?php } ?>
</div>

<!-- EVENTS -->
<div class="card">
<h3>📅 Events</h3>
<?php while($e=$events->fetch_assoc()){ ?>
<p><?php echo $e["title"]; ?><br><?php echo $e["event_date"]; ?></p>
<?php } ?>
</div>

<!-- TERM DATES -->
<div class="card">
<h3>📅 Term Dates</h3>

<p><b>Autumn Term</b><br>23 Sept – 13 Dec 2024</p>
<p><b>Winter Break</b><br>16 Dec – 10 Jan</p>
<p><b>Spring Term</b><br>13 Jan – 4 Apr 2025</p>
<p><b>Spring Break</b><br>7 Apr – 25 Apr</p>
<p><b>Summer Term</b><br>28 Apr – 20 Jun 2025</p>

</div>

<!-- LEADERBOARD -->
<div class="card">
<h3>🏆 Leaderboard</h3>
<?php while($l=$leaderboard->fetch_assoc()){ ?>
<p><?php echo $l["FirstName"]; ?> - <?php echo $l["points"]; ?></p>
<?php } ?>
</div>

<!-- POSTS -->
<div class="card">
<h3>💬 Student Feed</h3>

<form method="POST">
<textarea name="post_content" placeholder="Share something..." style="width:100%"></textarea>
<button>Post</button>
</form>

<?php while($p=$posts->fetch_assoc()){ ?>
<p><b><?php echo $p["FirstName"]; ?></b>: <?php echo $p["content"]; ?></p>
<?php } ?>
</div>

</div>

<!-- SEARCH -->
<div style="padding:20px;">
<form method="GET">
<input type="text" name="search" placeholder="Search courses">
<button>Search</button>
</form>

<?php if($searchResults){ 
while($r=$searchResults->fetch_assoc()){ ?>
<p><?php echo $r["CourseName"]; ?> - <?php echo $r["Description"]; ?></p>
<?php }} ?>
</div>

<script>
function toggleDark(){
    document.body.classList.toggle("dark");
}

setInterval(()=>{
document.getElementById("clock").innerHTML=new Date().toLocaleTimeString();
},1000);
</script>

</body>
</html>