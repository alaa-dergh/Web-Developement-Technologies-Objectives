<?php
require "admin_check.php";

// Active projects by default, archived ones with ?archived=1
$showArchived = isset($_GET["archived"]);

$sql = "SELECT p.id, u.name, u.email, p.informations, p.program,
               p.status, p.remarks, p.created_at
        FROM projects p
        JOIN users u ON p.user_id = u.id
        WHERE p.archived = :archived
        ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(["archived" => $showArchived ? 1 : 0]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="topbar">
    <h2>Admin Dashboard</h2>
    <div class="topLinks">
        <a href="admin_dashboard.php">Projects</a>
        <a href="admin_users.php">Users</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="subbar">
    <?php if ($showArchived): ?>
        Archived projects | <a href="admin_dashboard.php">Back to active projects</a>
    <?php else: ?>
        Active projects | <a href="admin_dashboard.php?archived=1">View archived</a>
    <?php endif; ?>
</div>

<div class="tableWrap">
<table class="adminTable">
    <tr>
        <th>ID</th><th>User</th><th>Email</th><th>Project</th>
        <th>Program</th><th>Status</th><th>Remarks</th><th>Date</th><th>Actions</th>
    </tr>

    <?php foreach ($projects as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p["id"]) ?></td>
        <td><?= htmlspecialchars($p["name"]) ?></td>
        <td><?= htmlspecialchars($p["email"]) ?></td>
        <td><?= htmlspecialchars($p["informations"]) ?></td>
        <td><?= htmlspecialchars($p["program"]) ?></td>
        <td><span class="badge <?= htmlspecialchars($p["status"]) ?>"><?= htmlspecialchars($p["status"]) ?></span></td>
        <td>
            <form method="POST" action="project_action.php" class="remarkForm">
                <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
                <input type="hidden" name="action" value="remarks">
                <?php if ($showArchived): ?>
                    <input type="hidden" name="archived" value="1">
                <?php endif; ?>
                <textarea name="remarks" rows="2" placeholder="Write a remark..."><?= htmlspecialchars($p["remarks"] ?? "") ?></textarea>
                <button class="btnSmall">Save</button>
            </form>
        </td>
        <td><?= htmlspecialchars($p["created_at"]) ?></td>
        <td class="actions">
            <a class="btnSmall" href="edit_project.php?id=<?= (int)$p["id"] ?>">Edit</a>

            <form method="POST" action="project_action.php">
                <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
                <input type="hidden" name="action" value="<?= $showArchived ? "unarchive" : "archive" ?>">
                <button class="btnSmall gray"><?= $showArchived ? "Restore" : "Archive" ?></button>
            </form>

            <form method="POST" action="project_action.php"
                  onsubmit="return confirm('Delete this project permanently?');">
                <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
                <input type="hidden" name="action" value="delete">
                <button class="btnSmall red">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>

    <?php if (empty($projects)): ?>
    <tr><td colspan="9">No projects.</td></tr>
    <?php endif; ?>
</table>
</div>

</body>
</html>