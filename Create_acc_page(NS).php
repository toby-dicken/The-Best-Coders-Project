<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("db.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $FirstName = trim($_POST["FirstName"]);
    $LastName = trim($_POST["LastName"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $Course = trim($_POST["Course"]);

    if (!$FirstName || !$LastName || !$email || !$password || !$Course) {
        $error = "Please fill all fields.";
    } else {

        // Check if email exists
        $check = $mysqli->prepare("SELECT UserID FROM Persons WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO Persons (FirstName, LastName, email, password, Course) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $FirstName, $LastName, $email, $hashed, $Course);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Error: " . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    height: 100vh;
    background: linear-gradient(135deg, #4b6cb7, #182848);
    display: flex;
    justify-content: center;
    align-items: center;
}

#form-container {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 350px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

label {
    font-size: 14px;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: 0.3s;
}

input:focus,
select:focus {
    border-color: #4b6cb7;
    outline: none;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4b6cb7;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

input[type="submit"]:hover {
    background-color: #182848;
}

.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}

.alert-danger {
    background: #ffdddd;
    color: #a70000;
}

.alert-success {
    background: #ddffdd;
    color: #007500;
}

p {
    text-align: center;
    margin-top: 15px;
}

a {
    color: #4b6cb7;
    text-decoration: none;
}

</style>

</head>

<body>

<div id="form-container">

<h2>Register</h2>

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">

<label>First Name</label>
<input type="text" name="FirstName" required>

<label>Last Name</label>
<input type="text" name="LastName" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Course</label>
<select name="Course" required>
<option value="">Select Course</option>
<option value="Computer Science">Computer Science</option>
<option value="Biology">Biology</option>
<option value="Health Care">Health Care</option>
</select>

<input type="submit" value="Register">

</form>

<p>Already have an account? <a href="login(NS).php">Login</a></p>

</div>

</body>
</html>