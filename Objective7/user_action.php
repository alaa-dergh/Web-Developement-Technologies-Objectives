<?php
require "admin_check.php";

// Change role or delete a user (POST only)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id     = (int)($_POST["id"] ?? 0);
    $action = $_POST["action"] ?? "";

    // An admin cannot modify or delete their own account
    if ($id !== (int)$_SESSION["user_id"]) {
        if ($action === "make_admin") {
            $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = :id")->execute(["id" => $id]);
        } elseif ($action === "make_user") {
            $pdo->prepare("UPDATE users SET role = 'user' WHERE id = :id")->execute(["id" => $id]);
        } elseif ($action === "delete") {
            // delete the user's projects first (foreign key), then the user
            $pdo->prepare("DELETE FROM projects WHERE user_id = :id")->execute(["id" => $id]);
            $pdo->prepare("DELETE FROM users WHERE id = :id")->execute(["id" => $id]);
        }
    }
}

header("Location: admin_users.php");
exit();