<?php

namespace App\Models;

use PDO;

class Product
{
    // connect to the database
    public function getConnection()
    {
        $dsn = "mysql:
                host={$_ENV['DB_HOST']};
                dbname={$_ENV['DB_NAME']};
                charset=utf8;
                port=3306";

        return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES, false
        ]);
    }

    // method to gat all rows of data from the products table
    public function getData(): array
    {
        // establish db connection
        $conn = $this->getConnection();

        // create db query
        $sql = "SELECT * FROM `products`";

        // send query to db
        $stmt = $conn->prepare($sql);

        // execute query
        $stmt->execute();

        // return db result set to Products controller as associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // method to get a row(s) from db based on a specific ID 
    // passed from the index.php page in the site root directory
    public function find(string $id): array|bool
    {
        $conn = $this->getConnection();

        $sql = "SELECT * FROM `products` WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected array $errors = [];

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    protected function validate(array $data): bool
    {
        if (empty($data["name"])) {
            
            $this->addError("name", "Required");

        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function insert(array $data): int|bool {
        
        if ( ! $this->validate($data) ) {
            return false;
        }

        $sql = "INSERT INTO `products` (name, description) VALUES (?, ?)";

        $conn = $this->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(2, $data["description"], PDO::PARAM_STR);

        $stmt->execute();

        return $conn->lastInsertId();
    }

    public function update(string $id, array $data): bool
    {
        if ( ! $this->validate($data) ) {
            return false;
        }

        unset($data["id"]);

        $fields = array_keys($data);

        array_walk($fields, function (&$value) {
            $value = "$value = ?";
        });

        $sql = "UPDATE `products` SET " . implode(", ", $fields) . " WHERE id = ?";

        $conn = $this->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(2, $data["description"], PDO::PARAM_STR);
        $stmt->bindValue(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM `products` WHERE id = :id";

        $conn = $this->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
