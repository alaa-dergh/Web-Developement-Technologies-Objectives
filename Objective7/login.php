<?php
session_start();
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!preg_match('/@esi\.dz$/i', $email)) {
        $message = "Seuls les emails @esi.dz sont autorisés.";
    } else {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"]    = $user["id"];
            $_SESSION["user_name"]  = $user["name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["role"]       = $user["role"];   

            if ($user["role"] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.html");
            }
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if ($message): ?>
            <div class="msg error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email @esi.dz" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
</body>
</html>