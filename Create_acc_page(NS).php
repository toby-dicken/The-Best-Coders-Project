<?php
require_once __DIR__ . '/security/session.php';
secure_session_start();
include("db.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";
    if (!csrf_verify($token)) {
        $error = "Invalid request.";
    } else {

        $FirstName = trim($_POST["FirstName"] ?? "");
        $LastName  = trim($_POST["LastName"] ?? "");
        $email     = trim($_POST["email"] ?? "");
        $password  = trim($_POST["password"] ?? "");

        if ($FirstName === "" || $LastName === "" || $email === "" || $password === "") {
            $error = "Please fill all fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {

            $check = $mysqli->prepare("SELECT UserID FROM Persons WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $error = "Email already registered!";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $mysqli->prepare("INSERT INTO Persons (FirstName, LastName, email, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $FirstName, $LastName, $email, $hashed);

                if ($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    error_log("Registration DB error: " . $mysqli->error);
                    $error = "Registration failed. Please try again.";
                }

                $stmt->close();
            }

            $check->close();
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
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post" action="Create_acc_page(NS).php" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

                <label class="form-label">First Name</label>
                <input type="text" name="FirstName" class="form-control mb-3" required>

                <label class="form-label">Last Name</label>
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
