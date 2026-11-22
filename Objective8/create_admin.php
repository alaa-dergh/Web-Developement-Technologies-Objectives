<?php

require "db.php";

$name = "Admin";
$email = "admin@esi.dz";
$password = "Admin123!";

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO users
        (name, email, password, role)
        VALUES
        (:name, :email, :password, :role)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "name" => $name,
    "email" => $email,
    "password" => $hashedPassword,
    "role" => "admin"
]);

echo "Admin account created.";

?>