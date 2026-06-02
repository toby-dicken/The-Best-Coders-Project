<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "Please fill all fields.";
    } else {

        $stmt = $mysqli->prepare("SELECT UserID, email, Password FROM Persons WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["Password"])) {

                session_regenerate_id(true);

                $_SESSION["UserID"] = $user["UserID"];
                $_SESSION["email"] = $user["email"];

                header("Location: main(NS).php");
                exit;

            } else {
                $error = "Incorrect password!";
            }

        } else {
            $error = "No account found with this email.";
        }

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

<form method="post">

<h2>Login</h2><br>

<?php
if ($error != "") {
    echo "<p style='color:red;'>$error</p>";
}
?>

<label>Email</label><br>
<input type="email" name="email" placeholder="Email" required><br><br>

<label>Password</label><br>
<input type="password" name="password" placeholder="Password" required><br><br>

<input type="submit" value="Login" class="btn"><br><br>
<p>Don't have account? <a href="Create_acc_page(NS).php" >Register! </a></p>

</form>

</section>

</div>

</body>
</html>
