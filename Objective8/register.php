
<?php

require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/AuthView.php";

$controller = new AuthController();
$view = new AuthView();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $message = $controller->register(
        $name,
        $email,
        $password
    );
}

$view->showRegister($message);

?>
