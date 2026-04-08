<?php

// THIS SCRIPT HASHES ALL PASSWORDS IN THE TABLE "Persons"
// IT DOES NOT HASH ALREADY HASHED PASSWORDS



include "db.php";

// fetch all users
$result = $mysqli->query("SELECT UserID, Password FROM Persons");

while ($row = $result->fetch_assoc()) {

    $id = $row['UserID'];
    $oldPass = $row['Password'];

    // Skip if already hashed (hashes always start with "$2y$")
    if (str_starts_with($oldPass, '$2y$')) {
        continue;
    }

    // hash the existing plaintext password
    $newHash = password_hash($oldPass, PASSWORD_DEFAULT);

    // update the database
    $stmt = $mysqli->prepare("UPDATE Persons SET Password = ? WHERE UserID = ?");
    $stmt->bind_param("si", $newHash, $id);
    $stmt->execute();
}

echo "Passwords successfully hashed!";
?>
