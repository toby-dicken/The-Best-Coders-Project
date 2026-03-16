<?php
session_start();
include("db.php");

/* -------- LOGIN PROTECTION -------- */
if (!isset($_SESSION["UserID"])) {
    header("Location: login(NS).php");
    exit;
}

/* -------- SEARCH SYSTEM -------- */
$search = "";
$query = "SELECT * FROM LibraryResources";

if (isset($_GET["search"])) {

    $search = $_GET["search"];
    $stmt = $mysqli->prepare("SELECT * FROM LibraryResources WHERE Title LIKE ?");
    $keyword = "%".$search."%";
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $result = $mysqli->query($query);

}
?>

<!DOCTYPE html>
<html>
<head>
<title>WLV Library</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">

<div class="logo">WLV Library</div>

<div class="nav-links">
<a href="main(NS).php">Home</a>
<a href="courses(NS).php">Courses</a>
<a href="support(NS).php">Support</a>
<a href="logout.php">Logout</a>
</div>

<form method="GET" class="search-box">
<input type="text" name="search" placeholder="Search library..." value="<?php echo $search; ?>">
<button type="submit">Search</button>
</form>

</nav>


<section class="hero">
<h1>WLV Digital Library</h1>
<p>Access books, journals & study materials</p>
</section>


<section class="section">

<h2>Available Resources</h2>

<br>

<?php

if ($result->num_rows > 0) {

while ($row = $result->fetch_assoc()) {

echo "<div class='card'>";
echo "<h3>".$row["Title"]."</h3>";
echo "<p>".$row["Description"]."</p>";
echo "<small>".$row["Category"]."</small>";
echo "</div>";

}

} else {

echo "<p>No resources found.</p>";

}

?>

</section>


<footer>
<p>© 2026 WLV Library | All Rights Reserved</p>
</footer>

</body>
</html>