<?php
session_start();
include("db.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $FirstName = trim($_POST["FirstName"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!$FirstName || !$email || !$password) {
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

            $stmt = $mysqli->prepare("INSERT INTO Persons (FirstName, LastName, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $FirstName, $_POST["LastName"], $email, $hashed);

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
</head>

<body>
<div>
    <div>
        <div>
            <h2>Register</h2>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="post">

                <label class="form-label">First Name</label>
                <input type="text" name="FirstName" class="form-control mb-3" required>
                
                <LABEL class="form-label">Last Name</LABEL>
                <input type="text" name="LastName" class="form-control mb-3" required>

                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control mb-3" required>

                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control mb-3" required>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="mt-3 text-center">
                Already have an account? <a href="login(NS).php">Login</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
