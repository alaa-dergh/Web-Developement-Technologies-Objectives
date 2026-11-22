
<?php

require_once __DIR__ . "/UserModel.php";

class AuthController {

    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    // REGISTER
    public function register($name, $email, $password) {

        $name = trim($name);
        $email = trim($email);

        if ($name === "" || $password === "") {
            return "Please fill in all fields.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || !preg_match('/@esi\.dz$/i', $email)) {

            return "Only ESI email addresses are accepted.";
        }

        // Ask the Model if the user exists
        $user = $this->model->findByEmail($email);

        if ($user) {
            return "This email is already registered.";
        }

        // Hash the password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Ask the Model to save the user
        try {

            $this->model->createUser(
                $name,
                $email,
                $hashedPassword
            );

        } catch (PDOException $e) {
            return "Registration failed. Please try again.";
        }

        return "Registration successful!";
    }

    // LOGIN
    public function login($email, $password) {

        $email = trim($email);

        // Ask the Model to find the user
        $user = $this->model->findByEmail($email);

        if ($user && password_verify(
            $password,
            $user["password"]
        )) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.html");
            }

            exit();
        }

        return "Email or password incorrect.";
    }

    // LOGOUT
    public function logout() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: login.php");
        exit();
    }
}

?>
