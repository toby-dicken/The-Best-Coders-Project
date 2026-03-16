<?php
session_start();
include("db.php");

/* Fetch courses from database */
$query = "SELECT * FROM Courses";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Courses - WLV</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
<div class="logo">WLV</div>

<div class="nav-links">
<a href="main.php">Home</a>
<a href="courses.php">Courses</a>
<a href="students.php">Students</a>
<a href="contact.php">Contact</a>
</div>
</nav>

<section class="section">

<h1>Our Courses</h1>
<br>

<p>We offer Computer Science, Business, Engineering and more.</p>

<br><br>

<?php

if ($result->num_rows > 0) {

while ($row = $result->fetch_assoc()) {

echo "<div class='card'>";
echo "<h3>".$row["CourseName"]."</h3>";
echo "<p>".$row["Description"]."</p>";
echo "<small>".$row["Department"]."</small>";
echo "</div>";

}

} else {

echo "<p>No courses available.</p>";

}

?>

</section>

<footer>
<p>© 2026 WLV Web Page</p>
</footer>

</body>
</html>