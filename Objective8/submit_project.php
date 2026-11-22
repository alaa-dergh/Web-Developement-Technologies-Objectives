<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

// Read form data
$informations = $_POST["informations"] ?? "";
$program = $_POST["program"] ?? "";
$call = $_POST["call"] ?? "";
$partners = $_POST["partners"] ?? "";
$needs = $_POST["needs"] ?? "";

// Insert project into database
$sql = "INSERT INTO projects
        (user_id, informations, program, call_name, partners, needs)
        VALUES
        (:user_id, :informations, :program, :call_name, :partners, :needs)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "user_id" => $_SESSION["user_id"],
    "informations" => $informations,
    "program" => $program,
    "call_name" => $call,
    "partners" => $partners,
    "needs" => $needs
]);

header("Location: project.php?success=1");

exit();

?>