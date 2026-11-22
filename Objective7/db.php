<?php
$host = "localhost";
$dbname = "tdw";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $dbname);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if($conn){
    // Connection established
} else {
    echo "Could not connect!";
}

?>