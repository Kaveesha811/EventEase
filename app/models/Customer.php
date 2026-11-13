<?php

require_once __DIR__ . '/../../config/database.php';

class Customer
{
    private $conn;

    public function __construct($conn = null)
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            $database = new Database();
            $this->conn = $database->connect();
        }
    }

    public function createCustomer($userID, $address = null)
    {
        $sql = "INSERT INTO Customer
                (UserID, Address, RegistrationDate)
                VALUES
                (:userID, :address, CURDATE())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':address', $address);

        return $stmt->execute();
    }
}