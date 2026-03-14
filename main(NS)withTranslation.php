<?php
session_start();
include("db.php");

/* LOGIN PROTECTION */
if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

$userID = $_SESSION["UserID"];

/* GET USER EMAIL */
$stmt = $mysqli->prepare("SELECT email FROM Persons WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* SEARCH SYSTEM */
$searchResults = null;

if(isset($_GET["search"])){

$keyword = "%".$_GET["search"]."%";

$stmt = $mysqli->prepare("SELECT CourseName,Description FROM Courses WHERE CourseName LIKE ?");
$stmt->bind_param("s",$keyword);
$stmt->execute();

$searchResults = $stmt->get_result();

}

/* CHECK IN SYSTEM */

$message="";
$points=0;
$lastCheck=null;

$stmt=$mysqli->prepare("SELECT points,last_checkin FROM rewards WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows==0){

$stmt=$mysqli->prepare("INSERT INTO rewards(UserID,points,last_checkin) VALUES (?,0,NULL)");
$stmt->bind_param("i",$userID);
$stmt->execute();

}else{

$data=$result->fetch_assoc();
$points=$data["points"];
$lastCheck=$data["last_checkin"];

}

if(isset($_POST["checkin"])){

$today=date("Y-m-d");

if($lastCheck!=$today){

$points+=10;

$stmt=$mysqli->prepare("UPDATE rewards SET points=?,last_checkin=? WHERE UserID=?");
$stmt->bind_param("isi",$points,$today,$userID);
$stmt->execute();

$message="Check-in successful! +10 points";

}else{

$message="You already checked in today";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>WLV Home</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- LANGUAGE SELECT -->

<div style="text-align:right; padding:10px;">
<select id="languageSelect" onchange="setLanguage(this.value)">
<option value="en">English</option>
<option value="es">Spanish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="zh">Chinese</option>
<option value="ar">Arabic</option>
<option value="ne">Nepali</option>
</select>
</div>


<nav class="navbar">

<div class="logo">
WLV | <?php echo $user["email"]; ?>
</div>

<div class="nav-links">
<a href="main.php" id="navHome">Home</a>
<a href="courses.php" id="navCourses">Courses</a>
<a href="students.php" id="navStudents">Students</a>
<a href="contact.php" id="navSupport">Contact</a>
<a href="logout.php">Logout</a>
</div>

<form class="search-box" method="GET">
<input type="text" name="search" id="searchInput" placeholder="Search...">
<button type="submit" id="searchBtn">Search</button>
</form>

</nav>


<section class="hero">
<h1 id="welcome">Welcome to WLV Web Page</h1>
<p id="subtitle">Professional responsive website</p>
</section>


<!-- CHECK IN SYSTEM -->

<section class="section">

<h2>Your Reward Points</h2>

<p><strong><?php echo $points; ?> Points</strong></p>

<form method="POST">
<button type="submit" name="checkin">Daily Check-In</button>
</form>

<p><?php echo $message; ?></p>

</section>


<section class="section">

<h2 id="servicesTitle">Our Services</h2>

<div class="cards">

<div class="card">
<h3 id="courseTitle">Courses</h3>
<p id="courseDesc">High quality academic courses.</p>
</div>

<div class="card">
<h3 id="studentTitle">Students</h3>
<p id="studentDesc">Student dashboard & profiles.</p>
</div>

<div class="card">
<h3 id="libraryTitle">Library</h3>
<p id="libraryDesc">Digital resources & materials.</p>
</div>

<div class="card">
<h3 id="supportTitle">Support</h3>
<p id="supportDesc">24/7 help & assistance.</p>
</div>

</div>

</section>


<!-- SEARCH RESULTS -->

<?php if($searchResults && $searchResults->num_rows>0){ ?>

<section class="section">

<h2>Search Results</h2>

<?php
while($row=$searchResults->fetch_assoc()){

echo "<div class='card'>";
echo "<h3>".$row["CourseName"]."</h3>";
echo "<p>".$row["Description"]."</p>";
echo "</div>";

}
?>

</section>

<?php } ?>


<footer>
<p id="footerText">© 2026 WLV Web Page | All Rights Reserved</p>
</footer>


<!-- YOUR ORIGINAL TRANSLATION JS (UNCHANGED) -->

<script>
function setLanguage(lang){
localStorage.setItem("selectedLanguage", lang);
translatePage(lang);
}

window.onload = function(){
let savedLang = localStorage.getItem("selectedLanguage");
if(savedLang){
document.getElementById("languageSelect").value = savedLang;
translatePage(savedLang);
}
}
</script>

</body>
</html>