<?php
require "admin_check.php";

$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manage users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="topbar">
    <h2>Manage Users</h2>
    <div class="topLinks">
        <a href="admin_dashboard.php">Projects</a>
        <a href="admin_users.php">Users</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="tableWrap">
<table class="adminTable">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>

    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= (int)$u["id"] ?></td>
        <td><?= htmlspecialchars($u["name"]) ?></td>
        <td><?= htmlspecialchars($u["email"]) ?></td>
        <td><span class="badge"><?= htmlspecialchars($u["role"]) ?></span></td>
        <td class="actions">
            <?php if ((int)$u["id"] !== (int)$_SESSION["user_id"]): ?>
                <form method="POST" action="user_action.php">
                    <input type="hidden" name="id" value="<?= (int)$u["id"] ?>">
                    <input type="hidden" name="action" value="<?= $u["role"] === "admin" ? "make_user" : "make_admin" ?>">
                    <button class="btnSmall gray">
                        <?= $u["role"] === "admin" ? "Make user" : "Make admin" ?>
                    </button>
                </form>

                <form method="POST" action="user_action.php"
                      onsubmit="return confirm('Delete this user and all their projects?');">
                    <input type="hidden" name="id" value="<?= (int)$u["id"] ?>">
                    <input type="hidden" name="action" value="delete">
                    <button class="btnSmall red">Delete</button>
                </form>
            <?php else: ?>
                (you)
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

</body>
</html>