
<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/AuthView.php";

$controller = new AuthController();
$view = new AuthView();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $message = $controller->login($email, $password);
}

$view->showLogin($message);

?>
