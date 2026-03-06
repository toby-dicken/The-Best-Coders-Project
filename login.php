<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Basic validation
    if ($email === "" || $password === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email or password.";
    } else {

        $stmt = $mysqli->prepare("SELECT user_id, username, password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Always use a generic error to avoid user enumeration
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];

                header("Location: main.php"); // change if needed
                exit;
            }
        }

        $error = "Invalid email or password.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div>
    <div>
        <img src="img/University-of-Wolverhampton.png" alt="University of Wolverhampton Logo" class="logo" width="200" height="100"><br>
    </div>
    <br>
    <section id="form-container">
        <form action="login.php" method="POST" autocomplete="on">
            <h2>Login</h2><br>

            <?php if (!empty($error)) : ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <label for="email">Email</label><br>
            <input type="email" placeholder="email" name="email" required><br><br>

            <label for="password">Password</label><br>
            <input type="password" placeholder="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login" class="btn"><br><br>
        </form>
    </section>
</div>
</body>
</html>
