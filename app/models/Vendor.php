<?php

require_once __DIR__ . '/../../config/database.php';


class Vendor
{
    private $conn;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct($conn = null)
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            $database = new Database();
            $this->conn = $database->connect();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Vendor Profile
    |--------------------------------------------------------------------------
    */

    public function createVendor(
        $userID,
        $businessName,
        $businessDescription,
        $businessAddress,
        $facebookProfile = null
    ) {
        $sql =
            "INSERT INTO Vendor
            (
                UserID,
                BusinessName,
                BusinessDescription,
                BusinessAddress,
                FacebookProfile,
                VerificationStatus
            )

            VALUES
            (
                :userID,
                :businessName,
                :businessDescription,
                :businessAddress,
                :facebookProfile,
                'Pending'
            )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(
            ':userID',
            $userID,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':businessName',
            $businessName
        );

        $stmt->bindParam(
            ':businessDescription',
            $businessDescription
        );

        $stmt->bindParam(
            ':businessAddress',
            $businessAddress
        );

        $stmt->bindValue(
            ':facebookProfile',
            $facebookProfile !== '' ? $facebookProfile : null
        );

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Get All Vendors - Admin
    |--------------------------------------------------------------------------
    */

    public function getAllVendors()
    {
        $sql =
            "SELECT

                Vendor.VendorID,
                Vendor.UserID,
                Vendor.BusinessName,
                Vendor.BusinessDescription,
                Vendor.BusinessAddress,
                Vendor.FacebookProfile,
                Vendor.VerificationStatus,
                Vendor.RegistrationDate,

                Users.FirstName,
                Users.LastName,
                Users.Email,
                Users.Phone,
                Users.Status AS UserStatus

             FROM Vendor

             INNER JOIN Users
                ON Vendor.UserID =
                   Users.UserID

             ORDER BY
                Vendor.VendorID DESC";


        $stmt =
            $this->conn->prepare(
                $sql
            );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vendor Verification Status - Admin
    |--------------------------------------------------------------------------
    */

    public function updateVerificationStatus(
        $vendorID,
        $newStatus
    ) {

        try {

            /*
             * Allowed verification statuses
             */

            $allowedStatuses = [
                'Pending',
                'Approved',
                'Rejected'
            ];


            if (
                !in_array(
                    $newStatus,
                    $allowedStatuses,
                    true
                )
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'Invalid verification status.'
                ];
            }


            /*
             * Check vendor exists
             */

            $checkSQL =
                "SELECT
                    VendorID,
                    VerificationStatus

                 FROM Vendor

                 WHERE VendorID =
                       :vendorID

                 LIMIT 1";


            $checkStmt =
                $this->conn->prepare(
                    $checkSQL
                );


            $checkStmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );


            $checkStmt->execute();


            $vendor =
                $checkStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if (!$vendor) {

                return [
                    'success' => false,
                    'message' =>
                        'Vendor not found.'
                ];
            }


            /*
             * Update verification status
             */

            $updateSQL =
                "UPDATE Vendor

                 SET VerificationStatus =
                     :newStatus

                 WHERE VendorID =
                       :vendorID";


            $updateStmt =
                $this->conn->prepare(
                    $updateSQL
                );


            $updateStmt->bindParam(
                ':newStatus',
                $newStatus
            );


            $updateStmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );


            $updateStmt->execute();


            return [
                'success' => true,
                'message' =>
                    'Vendor verification status updated successfully.'
            ];


        } catch (PDOException $exception) {

            return [
                'success' => false,
                'message' =>
                    'Failed to update vendor verification status.'
            ];
        }
    }
}

?>