<?php

require_once __DIR__ . '/../../config/database.php';

class User
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

    public function registerUser(
        $firstName,
        $lastName,
        $email,
        $password,
        $phone,
        $role = 'Customer'
    ) {
        $sql = "INSERT INTO Users
                (FirstName, LastName, Email, Password, Phone, Role)
                VALUES
                (:firstName, :lastName, :email, :password, :phone, :role)";

        $stmt = $this->conn->prepare($sql);

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function emailExists($email)
    {
        $sql = "SELECT UserID
                FROM Users
                WHERE Email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function emailAndPhoneMatch($email, $phone)
    {
        $sql = "SELECT UserID FROM Users WHERE Email = :email AND Phone = :phone LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function updatePasswordByEmail($email, $newPassword)
    {
        $sql = "UPDATE Users SET Password = :password WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function loginUser($email, $password)
    {
        $sql = "SELECT
                    Users.UserID,
                    Users.FirstName,
                    Users.LastName,
                    Users.Email,
                    Users.Password,
                    Users.Phone,
                    Users.Role,
                    Users.Status,

                    Customer.CustomerID,

                    Vendor.VendorID,
                    Vendor.VerificationStatus

                FROM Users

                LEFT JOIN Customer
                    ON Users.UserID =
                       Customer.UserID

                LEFT JOIN Vendor
                    ON Users.UserID =
                       Vendor.UserID

                WHERE Users.Email =
                      :email

                LIMIT 1";

        $stmt =
            $this->conn->prepare(
                $sql
            );

        $stmt->bindParam(
            ':email',
            $email
        );

        $stmt->execute();

        $user =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        /*
         * User not found
         */

        if (!$user) {

            return false;
        }


        /*
         * Check account status
         */

        if ($user['Status'] !== 'Active') {

            return false;
        }


        /*
         * Verify password
         */

        if (
            !password_verify(
                $password,
                $user['Password']
            )
        ) {

            return false;
        }


        /*
         |--------------------------------------------------------------------------
         | Vendor Verification Check
         |--------------------------------------------------------------------------
         */

        if (
            $user['Role'] === 'Vendor' &&
            $user['VerificationStatus'] !== 'Approved'
        ) {

            return [
                'loginError' =>
                    'Your vendor account is waiting for admin approval.'
            ];
        }


        return $user;
    }
}

?>