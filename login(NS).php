<?php
require_once __DIR__ . '/security/session.php';
secure_session_start();
include("db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";
    if (!csrf_verify($token)) {
        $error = "Invalid request.";
    } else {

        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");

        if ($email === "" || $password === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email or password.";
        } else {

            $stmt = $mysqli->prepare("SELECT UserID, email, Password FROM Persons WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user["Password"])) {
                    session_regenerate_id(true);

                    $_SESSION["UserID"] = $user["UserID"];
                    $_SESSION["email"]  = $user["email"];
                    $_SESSION["last_activity"] = time();

                    header("Location: main(TD).php");
                    exit;
                }
            }

            $error = "Invalid email or password.";
            $stmt->close();
        }
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
        <form method="post" action="login(NS).php" autocomplete="on">
            <h2>Login</h2><br>

            <?php if ($error !== ""): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

            <label>Email</label><br>
            <input type="email" name="email" placeholder="Email" required><br><br>

            <label>Password</label><br>
            <input type="password" name="password" placeholder="Password" required><br><br>

            <input type="submit" value="Login" class="btn"><br><br>
            <p>Don't have account? <a href="Create_acc_page(NS).php">Register!</a></p>
        </form>
    </section>
</div>
</body>
</html>
