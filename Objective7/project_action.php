<?php
require "admin_check.php";

// Archive / restore / delete / save remarks (POST only)
$back = "admin_dashboard.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id     = (int)($_POST["id"] ?? 0);
    $action = $_POST["action"] ?? "";

    if ($action === "archive") {
        $pdo->prepare("UPDATE projects SET archived = 1 WHERE id = :id")->execute(["id" => $id]);
    } elseif ($action === "unarchive") {
        $pdo->prepare("UPDATE projects SET archived = 0 WHERE id = :id")->execute(["id" => $id]);
    } elseif ($action === "delete") {
        $pdo->prepare("DELETE FROM projects WHERE id = :id")->execute(["id" => $id]);
    } elseif ($action === "remarks") {
        $pdo->prepare("UPDATE projects SET remarks = :remarks WHERE id = :id")
            ->execute(["remarks" => trim($_POST["remarks"] ?? ""), "id" => $id]);

        // stay on the archived list if we came from it
        if (isset($_POST["archived"])) {
            $back = "admin_dashboard.php?archived=1";
        }
    }
}

header("Location: " . $back);
exit();