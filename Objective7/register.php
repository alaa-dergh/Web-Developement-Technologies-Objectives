<?php

require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    
    if (!preg_match('/@esi\.dz$/i', $email)) {

        $message = "Only ESI email addresses are accepted.";

    } else {

        // Hash the password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insert the user
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";

        $stmt = $pdo->prepare($sql);

        try {

            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ]);

            $message = "Registration successful!";

        } catch (PDOException $e) {

            $message = "This email may already be registered.";

        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Result</title>
</head>
<body>

    <h2>Registration Result</h2>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <a href="register.html">Back to registration</a>

</body>
</html>