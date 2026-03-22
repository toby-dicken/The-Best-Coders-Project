<?php
session_start();
include("db.php");

//  Protect the page
 if (!isset($_SESSION["UserID"])) {
     header("Location: login.php");
     exit;
}


// Fetch all courses
$stmt = $mysqli->prepare("SELECT CourseName, Description FROM Courses ORDER BY CourseName ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Courses - WLV</title>
    <link rel="stylesheet" href="style.css">

    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 18px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background: #004080;
            color: white;
            padding: 10px;
        }
        td {
            padding: 12px;
            background: #f9f9f9;
        }
        tr:nth-child(even) td {
            background: #f0f0f0;
        }
    </style>

</head>
<body>

<nav class="navbar">
    <div class="logo">WLV</div>
    <div class="nav-links">
        <a href="main(TD).php">Home</a>
        <a href="courses(TD).php">Courses</a>
        <a href="contact(NS).php">Contact</a>
    </div>
</nav>

<section class="section">
    <h1>Our Courses</h1>
    <p>Explore a wide range of academic courses offered at WLV.</p>
</section>

<section class="section">

<table>
    <tr>
        <th>Course Name</th>
        <th>Description</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['CourseName']); ?></td>
            <td><?php echo htmlspecialchars($row['Description']); ?></td>
        </tr>
    <?php endwhile; ?>

</table>

</section>

<footer>
    <p>© 2026 WLV Web Page</p>
</footer>

</body>
</html>
