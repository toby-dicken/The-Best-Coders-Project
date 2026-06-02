<?php
session_start();
include("db.php");

if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = $_SESSION["UserID"];

/* USER */
$stmt = $mysqli->prepare("SELECT email, FirstName, role FROM Persons WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* ADMIN ROLE */
$isAdmin = ($user["role"] == "admin");

/* VIEW MODE */
if(!isset($_SESSION["view_mode"])){
    $_SESSION["view_mode"] = $user["role"];
}
$viewMode = $_SESSION["view_mode"];

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

/* CHECKIN */
if(isset($_POST["checkin"])){

    $stmt=$mysqli->prepare("SELECT last_checkin FROM rewards WHERE UserID=?");
    $stmt->bind_param("i",$userID);
    $stmt->execute();
    $row=$stmt->get_result()->fetch_assoc();

    $now = time();
    $last = $row["last_checkin"] ? strtotime($row["last_checkin"]) : 0;

    if(!$row["last_checkin"] || ($now - $last) >= 7200){

        $currentTime = date("Y-m-d H:i:s");

        $stmt=$mysqli->prepare("UPDATE rewards SET points=points+10, last_checkin=? WHERE UserID=?");
        $stmt->bind_param("si",$currentTime,$userID);
        $stmt->execute();

        $points+=10;
        $checkinMessage="✅ +10 points!";

    } else {

        $remaining = 7200 - ($now - $last);
        $minutes = floor($remaining / 60);

        $checkinMessage="⏳ Try again in $minutes minutes";
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

/* ANNOUNCEMENTS */
$announcements=$mysqli->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");

/* EVENTS */
$events=$mysqli->query("SELECT * FROM events WHERE event_date>=NOW() ORDER BY event_date ASC LIMIT 3");

/* POSTS */
$posts=$mysqli->query("
SELECT posts.*,Persons.FirstName 
FROM posts JOIN Persons ON Persons.UserID=posts.userID 
ORDER BY posts.created_at DESC LIMIT 5");

/* POST */
if(isset($_POST["post_content"])){
    $content=$_POST["post_content"];
    $stmt=$mysqli->prepare("INSERT INTO posts(userID,content) VALUES (?,?)");
    $stmt->bind_param("is",$userID,$content);
    $stmt->execute();
    header("Refresh:0");
}

/* ANNOUNCEMENT */
if(isset($_POST["add_announcement"])){

    if(!$isAdmin || $viewMode!="admin"){
        die("Access denied");
    }

    $title=$_POST["title"];
    $content=$_POST["content"];

    $stmt=$mysqli->prepare("INSERT INTO announcements(title,content) VALUES (?,?)");
    $stmt->bind_param("ss",$title,$content);
    $stmt->execute();

    header("Refresh:0");
}

/* EVENT ADD */
if(isset($_POST["add_event"])){

    if(!$isAdmin || $viewMode!="admin"){
        die("Access denied");
    }

    $title=$_POST["event_title"];
    $date=$_POST["event_date"];
    $content=$_POST["event_content"];

    $stmt=$mysqli->prepare("INSERT INTO events(title,event_date,description) VALUES (?,?,?)");
    $stmt->bind_param("sss",$title,$date,$content);
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

.navbar{
display:flex;
justify-content:space-between;
padding:15px;
background:rgba(0,0,0,0.6);
}

.nav-links a{color:white;margin:10px;text-decoration:none;}

.hero{
background:rgba(0,0,0,0.3);
padding:25px;
border-radius:15px;
margin:20px;
}

.grid{display:flex;flex-wrap:wrap;gap:20px;padding:20px;}

.card{
background:rgba(255,255,255,0.15);
padding:15px;
border-radius:12px;
flex:1 1 250px;
}

button{
padding:10px;
border:none;
border-radius:8px;
background:#00f2fe;
cursor:pointer;
}

input,textarea{
width:100%;
padding:8px;
margin:5px 0;
}

/* 🌙 DARK MODE (ADDED ONLY) */
body.dark {
    background: #111 !important;
    color: #eee !important;
}

body.dark .card {
    background: rgba(255,255,255,0.08) !important;
}

body.dark .navbar {
    background: rgba(0,0,0,0.9) !important;
}

body.dark .hero {
    background: rgba(255,255,255,0.08) !important;
}
</style>
</head>

<body>

<div class="navbar">
<div>WLV | <?php echo $user["email"]; ?></div>

<div class="nav-links">
<a href="main(NS).php">Home</a>
<a href="courses(NS).php">Courses</a>
<a href="inbox(NS).php">Mail</a>
<a href="club(NS).php">Clubs</a>
</div>

<?php if($isAdmin){ ?>
<a href="toggle_view(NS).php">
<button>
<?php echo ($viewMode=="admin") ? "Student View" : "Admin View"; ?>
</button>
</a>
<?php } ?>

<!-- 🌙 DARK MODE BUTTON (ADDED ONLY) -->
<button onclick="toggleDark()" style="margin-left:10px;">🌙</button>
<div><a href="logout(NS).php">Logout</a></div>
</div>


<!-- HERO -->
<div class="hero">
<h1>Welcome <?php echo $user["FirstName"]; ?> 👋</h1>

<div id="clock"></div>

<!-- SEARCH BAR -->
<form method="GET" style="margin-top:15px;">
<input type="text" name="search" placeholder="Search courses..." style="width:60%;padding:8px;">
<button>Search</button>
</form>

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

<?php if($isAdmin && $viewMode=="admin"){ ?>
<form method="POST">
<input name="title">
<textarea name="content"></textarea>
<button name="add_announcement">Post</button>
</form>
<hr>
<?php } ?>

<?php while($a=$announcements->fetch_assoc()){ ?>
<p><b><?php echo $a["title"]; ?></b><br><?php echo $a["content"]; ?></p>
<?php } ?>
</div>

<!-- EVENTS -->
<div class="card">
<h3>📅 Events</h3>

<?php if($isAdmin && $viewMode=="admin"){ ?>
<form method="POST">
<input name="event_title">
<input type="date" name="event_date">
<textarea name="event_content"></textarea>
<button name="add_event">Add Event</button>
</form>
<hr>
<?php } ?>

<?php while($e=$events->fetch_assoc()){ ?>
<p><b><?php echo $e["title"]; ?></b><br><?php echo $e["event_date"]; ?></p>
<hr>
<?php } ?>
</div>

<!-- COURSES -->
<div class="card">
<h3>📚 Courses</h3>
<p>Access your enrolled courses and modules.</p>
<a href="courses(NS).php"><button>Go to Courses</button></a>
</div>

<div class="card">

<h3>🗺️ Campus Map</h3>

<p>Find University of Wolverhampton locations, buildings, and routes.</p>

<!-- MAP PREVIEW -->
<div style="border-radius:10px; overflow:hidden; height:200px; margin-bottom:10px;">

<iframe 
src="map.html"
style="width:100%; height:200px; border:0;">
</iframe>

</div>

<a href="map.html">
<button>Open Full Map</button>
</a>

</div>
<!-- POSTS -->
<div class="card">
<h3>💬 Feed</h3>

<form method="POST">
<textarea name="post_content"></textarea>
<button>Post</button>
</form>

<?php while($p=$posts->fetch_assoc()){ ?>
<p><b><?php echo $p["FirstName"]; ?></b>: <?php echo $p["content"]; ?></p>
<?php } ?>
</div>

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