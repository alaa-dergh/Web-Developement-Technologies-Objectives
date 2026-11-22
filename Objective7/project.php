<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$success = isset($_GET["success"]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Your Project</h2>
        <?php if ($success): ?>
            <div class="msg success">Project submitted successfully!</div>
        <?php endif; ?>
        <div class="link">
            <a href="index.html">Submit another project</a> |
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>