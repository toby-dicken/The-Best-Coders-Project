<?php
session_start();
include("db.php");

/* -------------------------
   LOGIN PROTECTION
--------------------------*/
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

/* -------------------------
   GET USER INFORMATION
--------------------------*/
$stmt = $mysqli->prepare("SELECT email FROM Persons WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


/* -------------------------
   SEARCH SYSTEM
--------------------------*/
$searchResults = [];

if (isset($_GET["search"])) {

    $keyword = "%" . $_GET["search"] . "%";

    $stmt = $mysqli->prepare("SELECT CourseName, Description FROM Courses WHERE CourseName LIKE ?");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();

    $searchResults = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>WLV Home</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">

<div class="logo">WLV | <?php echo $user["email"]; ?></div>

<div class="nav-links">
<a href="main.php">Home</a>
<a href="courses.php">Courses</a>
<a href="students.php">Students</a>
<a href="contact.php">Contact</a>
<a href="logout.php">Logout</a>
</div>

<form class="search-box" method="GET">
<input type="text" name="search" placeholder="Search courses...">
<button type="submit">Search</button>
</form>

</nav>


<section class="hero">
<h1>Welcome to WLV Web Page</h1>
<p>Professional responsive website</p>
</section>


<section class="section">

<h2>Our Services</h2>
<br>

<div class="cards">

<a href="courses.php" class="card-link">
<div class="card">
<h3>Courses</h3>
<p>High quality academic courses.</p>
</div>
</a>

<a href="students.php" class="card-link">
<div class="card">
<h3>Students</h3>
<p>Student dashboard & profiles.</p>
</div>
</a>

<a href="library.php" class="card-link">
<div class="card">
<h3>Library</h3>
<p>Digital resources & materials.</p>
</div>
</a>

<a href="contact.php" class="card-link">
<div class="card">
<h3>Support</h3>
<p>24/7 help & assistance.</p>
</div>
</a>

</div>

</section>


<!-- SEARCH RESULTS -->

<?php if (!empty($searchResults)) { ?>

<section class="section">

<h2>Search Results</h2>
<br>

<?php
while ($row = $searchResults->fetch_assoc()) {

echo "<div class='card'>";
echo "<h3>".$row["CourseName"]."</h3>";
echo "<p>".$row["Description"]."</p>";
echo "</div>";

}
?>

</section>

<?php } ?>


<footer>
<p>© 2026 WLV Web Page | All Rights Reserved</p>
</footer>

</body>
</html>