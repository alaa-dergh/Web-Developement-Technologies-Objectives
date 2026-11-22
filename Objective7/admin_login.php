<?php

session_start();

require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users
            WHERE email = :email
            AND role = 'admin'";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email" => $email
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        $admin &&
        password_verify($password, $admin["password"])
    ) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $admin["id"];
        $_SESSION["role"] = "admin";

        header("Location: admin_dashboard.php");

        exit();

    } else {

        $message = "Invalid administrator credentials.";

    }

}
systeem out println -

?>
    }
}

?>