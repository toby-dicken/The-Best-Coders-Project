<?php
session_start();
include("db.php");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $stmt = $mysqli->prepare("INSERT INTO ContactMessages (FullName, Email, Subject, Message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            $stmt->execute();
            $stmt->close();

            $success = "Message sent successfully!";

        } else {

            $error = "Invalid email address.";

        }

    } else {

        $error = "Please fill in all fields.";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact - WLV</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
<div class="logo">WLV</div>

<div class="nav-links">
<a href="main.php">Home</a>
<a href="courses.html">Courses</a>
<a href="students.html">Students</a>
<a href="contact.php">Contact</a>
</div>
</nav>

<section class="section">

<h1>Contact Us</h1>
<br>

<?php
if ($success != "") {
echo "<p style='color:green;'>$success</p>";
}

if ($error != "") {
echo "<p style='color:red;'>$error</p>";
}
?>

<div class="contact-container">

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email Address" required>

<input type="text" name="subject" placeholder="Subject" required>

<textarea name="message" placeholder="Your Message" required></textarea>

<button type="submit">Send Message</button>

</form>

</div>

</section>

<footer>
<p>© 2026 WLV Web Page</p>
</footer>

</body>
</html>