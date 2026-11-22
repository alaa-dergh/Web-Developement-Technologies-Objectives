
<?php

require_once __DIR__ . "/Database.php";

class UserModel {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    // Find a user by email
    public function findByEmail($email) {

        $sql = "SELECT id, name, email, password, role
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function createUser($name, $email, $password) {

        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);
    }
}

?>
