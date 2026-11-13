<?php

require_once __DIR__ . '/../../config/database.php';


class Availability
{
    private $conn;


    public function __construct()
    {
        $database =
            new Database();

        $this->conn =
            $database->connect();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Availability
    |--------------------------------------------------------------------------
    */

    public function getVendorAvailability(
        $vendorID
    ) {

        $sql = "SELECT
                    Availability.AvailabilityID,
                    Availability.VendorID,
                    Availability.AvailabilityDate,
                    Availability.Status
                FROM Availability

                WHERE Availability.VendorID =
                      :vendorID

                ORDER BY
                    Availability.AvailabilityDate ASC";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Availability
    |--------------------------------------------------------------------------
    */

    public function addAvailability(
        $vendorID,
        $availabilityDate,
        $status
    ) {

        $sql = "INSERT INTO Availability
                    (
                        VendorID,
                        AvailabilityDate,
                        Status
                    )

                VALUES
                    (
                        :vendorID,
                        :availabilityDate,
                        :status
                    )";


        try {

            $stmt =
                $this->conn->prepare($sql);


            $stmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );


            $stmt->bindParam(
                ':availabilityDate',
                $availabilityDate
            );


            $stmt->bindParam(
                ':status',
                $status
            );


            return $stmt->execute();

        } catch (PDOException $exception) {

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Vendor Availability
    |--------------------------------------------------------------------------
    */

    public function getVendorAvailabilityByID(
        $availabilityID,
        $vendorID
    ) {

        $sql = "SELECT
                    AvailabilityID,
                    VendorID,
                    AvailabilityDate,
                    Status

                FROM Availability

                WHERE AvailabilityID =
                      :availabilityID

                  AND VendorID =
                      :vendorID

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':availabilityID',
            $availabilityID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Availability
    |--------------------------------------------------------------------------
    */

    public function updateAvailability(
        $availabilityID,
        $vendorID,
        $availabilityDate,
        $status
    ) {

        $sql = "UPDATE Availability

                SET
                    AvailabilityDate =
                        :availabilityDate,

                    Status =
                        :status

                WHERE AvailabilityID =
                      :availabilityID

                  AND VendorID =
                      :vendorID";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':availabilityDate',
            $availabilityDate
        );


        $stmt->bindParam(
            ':status',
            $status
        );


        $stmt->bindParam(
            ':availabilityID',
            $availabilityID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        return $stmt->execute();
    }
}