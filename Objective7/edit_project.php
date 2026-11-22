<?php
require "admin_check.php";

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);
$statuses = ["submitted", "reviewed", "rejected", "accepted"];

// Save changes
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $_POST["status"] ?? "submitted";
    if (!in_array($status, $statuses, true)) {
        $status = "submitted";
    }

    $stmt = $pdo->prepare("UPDATE projects
                           SET informations = :informations,
                               program = :program,
                               status = :status,
                               remarks = :remarks
                           WHERE id = :id");
    $stmt->execute([
        "informations" => trim($_POST["informations"] ?? ""),
        "program"      => $_POST["program"] ?? "",
        "status"       => $status,
        "remarks"      => trim($_POST["remarks"] ?? ""),
        "id"           => $id
    ]);

    header("Location: admin_dashboard.php");
    exit();
}

// Load the project
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id");
$stmt->execute(["id" => $id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    exit("Project not found.");
}

$programs = ["horizon", "cbhe", "pnr", "cnepru"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Edit project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container wide">
        <h2>Edit project #<?= (int)$project["id"] ?></h2>

        <form method="POST" action="edit_project.php">
            <input type="hidden" name="id" value="<?= (int)$project["id"] ?>">

            <label>Informations</label>
            <textarea name="informations" rows="4"><?= htmlspecialchars($project["informations"]) ?></textarea>

            <label>Program</label>
            <select name="program">
                <?php foreach ($programs as $pr): ?>
                    <option value="<?= $pr ?>" <?= $project["program"] === $pr ? "selected" : "" ?>>
                        <?= strtoupper($pr) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Status</label>
            <select name="status">
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>" <?= $project["status"] === $s ? "selected" : "" ?>>
                        <?= ucfirst($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Remarks</label>
            <textarea name="remarks" rows="3"><?= htmlspecialchars($project["remarks"] ?? "") ?></textarea>

            <button type="submit">Save</button>
        </form>

        <div class="link"><a href="admin_dashboard.php">Cancel</a></div>
    </div>
</body>
</html>