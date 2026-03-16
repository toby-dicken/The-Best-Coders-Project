<?php
session_start();
include("db.php");

/* LOGIN PROTECTION */
if(!isset($_SESSION["UserID"])){
header("Location: login.php");
exit();
}

$userID = $_SESSION["UserID"];

/* GET USER EMAIL */
$stmt = $mysqli->prepare("SELECT email FROM Persons WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


/* --------------------
SEARCH SYSTEM
--------------------*/

$searchResults = null;

if(isset($_GET["search"]) && $_GET["search"] != ""){

$keyword = "%".$_GET["search"]."%";

$stmt = $mysqli->prepare("SELECT CourseName,Description FROM Courses WHERE CourseName LIKE ?");
$stmt->bind_param("s",$keyword);
$stmt->execute();

$searchResults = $stmt->get_result();

}


/* --------------------
CHECK IN SYSTEM
--------------------*/

$message = "";
$points = 0;
$lastCheck = null;

$stmt = $mysqli->prepare("SELECT points,last_checkin FROM rewards WHERE UserID=?");
$stmt->bind_param("i",$userID);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows==0){

$stmt = $mysqli->prepare("INSERT INTO rewards(UserID,points,last_checkin) VALUES (?,0,NULL)");
$stmt->bind_param("i",$userID);
$stmt->execute();

}else{

$data = $result->fetch_assoc();
$points = $data["points"];
$lastCheck = $data["last_checkin"];

}


/* CHECK IN BUTTON */

if(isset($_POST["checkin"])){

$today = date("Y-m-d");

if($lastCheck != $today){

$points += 10;

$stmt = $mysqli->prepare("UPDATE rewards SET points=?,last_checkin=? WHERE UserID=?");
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
WLV | <?php echo $user["FullName"]; ?>
</div>

<div class="nav-links">
<a href="main(NS).php" id="navHome">Home</a>
<a href="courses(NS).php" id="navCourses">Courses</a>
<a href="contact(NS).php" id="navSupport">Contact</a>
<a href="logout.php">Logout</a>
</div>

<form class="search-box" method="GET">
<input type="text" name="search" id="searchInput" placeholder="Search..."
value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
<button type="submit" id="searchBtn">Search</button>
</form>

</nav>


<section class="hero">
<h1 id="welcome">Welcome to WLV Web Page</h1>
<p id="subtitle">Professional responsive website</p>
</section>


<!-- CHECK IN + POINTS -->

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

<a href="courses(NS).php" class="card-link">
<div class="card">
<h3 id="courseTitle">Courses</h3>
<p id="courseDesc">High quality academic courses.</p>
</div>
</a>


<a href="library(NS).php" class="card-link">
<div class="card">
<h3 id="libraryTitle">Library</h3>
<p id="libraryDesc">Digital resources & materials.</p>
</div>
</a>

<a href="contact(NS).php" class="card-link">
<div class="card">
<h3 id="supportTitle">Support</h3>
<p id="supportDesc">24/7 help & assistance.</p>
</div>
</a>

</div>

</section>


<!-- SEARCH RESULTS -->

<?php if(isset($_GET["search"])) { ?>

<section class="section">

<h2>Search Results</h2>

<?php

if($searchResults && $searchResults->num_rows>0){

while($row=$searchResults->fetch_assoc()){

echo "<div class='card'>";
echo "<h3>".$row["CourseName"]."</h3>";
echo "<p>".$row["Description"]."</p>";
echo "</div>";

}

}else{

echo "<p>No courses found.</p>";

}

?>

</section>

<?php } ?>


<footer>
<p id="footerText">© 2026 WLV Web Page | All Rights Reserved</p>
</footer>


<script>

/* SAVE LANGUAGE */

function setLanguage(lang){
localStorage.setItem("selectedLanguage",lang);
translatePage(lang);
}

/* LOAD LANGUAGE */

window.onload=function(){

let savedLang=localStorage.getItem("selectedLanguage");

if(savedLang){
document.getElementById("languageSelect").value=savedLang;
translatePage(savedLang);
}

}

/* TRANSLATION FUNCTION */

function translatePage(lang){

navHome.innerHTML=(lang=="es")?"Inicio":
(lang=="fr")?"Accueil":
(lang=="de")?"Startseite":
(lang=="zh")?"首页":
(lang=="ar")?"الرئيسية":
(lang=="ne")?"घर":"Home";

navCourses.innerHTML=(lang=="es")?"Cursos":
(lang=="fr")?"Cours":
(lang=="de")?"Kurse":
(lang=="zh")?"课程":
(lang=="ar")?"الدورات":
(lang=="ne")?"पाठ्यक्रम":"Courses";

navStudents.innerHTML=(lang=="es")?"Estudiantes":
(lang=="fr")?"Étudiants":
(lang=="de")?"Studenten":
(lang=="zh")?"学生":
(lang=="ar")?"الطلاب":
(lang=="ne")?"विद्यार्थी":"Students";

navSupport.innerHTML=(lang=="es")?"Soporte":
(lang=="fr")?"Support":
(lang=="de")?"Support":
(lang=="zh")?"支持":
(lang=="ar")?"الدعم":
(lang=="ne")?"समर्थन":"Contact";

}

</script>

</body>
</html>