
<?php

class Database {

    private $host = "localhost";
    private $dbname = "tdw";
    private $username = "root";
    private $password = "";

    private $connection;

    public function connect() {

        if ($this->connection === null) {

            $dsn = "mysql:host=" . $this->host .
                   ";dbname=" . $this->dbname .
                   ";charset=utf8mb4";

            $this->connection = new PDO(
                $dsn,
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return $this->connection;
    }
}

?>
