<?php
session_start();
include("db.php");

$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {

        $stmt = $mysqli->prepare("INSERT INTO ContactMessages (FullName, Email, Subject, Message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();

        $success = "Message sent successfully!";
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
<a href="main(TD).php">Home</a>
<a href="courses(NS).html">Courses</a>
<a href="contact(NS).php">Contact</a>
</div>
</nav>


<section class="section">

<h1>Contact Us</h1>
<br>

<?php
if ($success != "") {
echo "<p style='color:green;'>$success</p>";
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
