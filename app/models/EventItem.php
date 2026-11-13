<?php

require_once __DIR__ . '/../../config/database.php';

class EventItem
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


    /*
    |--------------------------------------------------------------------------
    | Add Service to Event
    |--------------------------------------------------------------------------
    */

    public function addServiceToEvent(
        $eventID,
        $serviceID,
        $eventDate
    ) {

        /*
         * Check whether the service is
         * available on the event date.
         */

        $sql = "SELECT
                    Service.ServiceID,
                    Service.Price,

                    COALESCE(
                        Availability.Status,
                        'Not Specified'
                    ) AS AvailabilityStatus

                FROM Service

                INNER JOIN Vendor
                    ON Service.VendorID =
                       Vendor.VendorID

                INNER JOIN Events
                    ON Events.EventID =
                       :eventIDForType

                LEFT JOIN Availability
                    ON Vendor.VendorID =
                       Availability.VendorID

                    AND Availability.AvailabilityDate =
                        :eventDate

                WHERE Service.ServiceID =
                      :serviceID

                  AND Service.Status =
                      'Active'

                  AND Vendor.VerificationStatus =
                      'Approved'

                  AND EXISTS (
                      SELECT 1
                      FROM EventTypeServiceCategory
                      WHERE EventTypeServiceCategory.EventTypeID =
                            Events.EventTypeID
                        AND EventTypeServiceCategory.ServiceCategoryID =
                            Service.ServiceCategoryID
                  )

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':eventIDForType',
            $eventID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':eventDate',
            $eventDate
        );


        $stmt->execute();


        $service =
            $stmt->fetch(PDO::FETCH_ASSOC);


        /*
         * Service not found
         */

        if (!$service) {

            return [
                'success' => false,
                'message' => 'Service not found.'
            ];
        }


        /*
         * Service must be available
         */

        if (
            $service['AvailabilityStatus']
            !== 'Available'
        ) {

            return [
                'success' => false,
                'message' =>
                    'This service is not available for the selected event date.'
            ];
        }


        /*
         * Check whether the service
         * is already added.
         */

        $checkSQL =
            "SELECT EventItemID
             FROM EventItem
             WHERE EventID = :eventID
               AND ServiceID = :serviceID
             LIMIT 1";


        $checkStmt =
            $this->conn->prepare(
                $checkSQL
            );


        $checkStmt->bindParam(
            ':eventID',
            $eventID,
            PDO::PARAM_INT
        );


        $checkStmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );


        $checkStmt->execute();


        if (
            $checkStmt->fetch(PDO::FETCH_ASSOC)
        ) {

            return [
                'success' => false,
                'message' =>
                    'This service is already added to your event.'
            ];
        }


        /*
         * Add service to EventItem
         */

        $insertSQL =
            "INSERT INTO EventItem
            (
                EventID,
                ServiceID,
                PackageID,
                Quantity,
                EstimatedPrice,
                AddedDate
            )
            VALUES
            (
                :eventID,
                :serviceID,
                NULL,
                1,
                :estimatedPrice,
                NOW()
            )";


        $insertStmt =
            $this->conn->prepare(
                $insertSQL
            );


        $insertStmt->bindParam(
            ':eventID',
            $eventID,
            PDO::PARAM_INT
        );


        $insertStmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );


        $estimatedPrice =
            $service['Price'];


        $insertStmt->bindParam(
            ':estimatedPrice',
            $estimatedPrice
        );


        if ($insertStmt->execute()) {

            return [
                'success' => true,
                'message' =>
                    'Service added to your event.'
            ];
        }


        return [
            'success' => false,
            'message' =>
                'Failed to add service.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Add Package to Event
    |--------------------------------------------------------------------------
    */

    public function addPackageToEvent(
        $eventID,
        $packageID,
        $eventDate
    ) {

        /*
         * Check whether the package is
         * available on the event date.
         */

        $sql = "SELECT
                    Package.PackageID,
                    Package.VendorID,
                    Package.Price,

                    COALESCE(
                        Availability.Status,
                        'Not Specified'
                    ) AS AvailabilityStatus

                FROM Package

                INNER JOIN Vendor
                    ON Package.VendorID =
                       Vendor.VendorID

                LEFT JOIN Availability
                    ON Vendor.VendorID =
                       Availability.VendorID

                    AND Availability.AvailabilityDate =
                        :eventDate

                WHERE Package.PackageID =
                      :packageID

                  AND Package.Status =
                      'Active'

                  AND Vendor.VerificationStatus =
                      'Approved'

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':packageID',
            $packageID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':eventDate',
            $eventDate
        );


        $stmt->execute();


        $package =
            $stmt->fetch(PDO::FETCH_ASSOC);


        /*
         * Package not found
         */

        if (!$package) {

            return [
                'success' => false,
                'message' => 'Package not found.'
            ];
        }


        /*
         * Package must be available
         */

        if (
            $package['AvailabilityStatus']
            !== 'Available'
        ) {

            return [
                'success' => false,
                'message' =>
                    'This package is not available for the selected event date.'
            ];
        }


        /*
         * Check whether the package
         * is already added.
         */

        $checkSQL =
            "SELECT EventItemID
             FROM EventItem
             WHERE EventID = :eventID
               AND PackageID = :packageID
             LIMIT 1";


        $checkStmt =
            $this->conn->prepare(
                $checkSQL
            );


        $checkStmt->bindParam(
            ':eventID',
            $eventID,
            PDO::PARAM_INT
        );


        $checkStmt->bindParam(
            ':packageID',
            $packageID,
            PDO::PARAM_INT
        );


        $checkStmt->execute();


        if (
            $checkStmt->fetch(PDO::FETCH_ASSOC)
        ) {

            return [
                'success' => false,
                'message' =>
                    'This package is already added to your event.'
            ];
        }


        /*
         * Add package to EventItem
         */

        $insertSQL =
            "INSERT INTO EventItem
            (
                EventID,
                ServiceID,
                PackageID,
                Quantity,
                EstimatedPrice,
                AddedDate
            )
            VALUES
            (
                :eventID,
                NULL,
                :packageID,
                1,
                :estimatedPrice,
                NOW()
            )";


        $insertStmt =
            $this->conn->prepare(
                $insertSQL
            );


        $insertStmt->bindParam(
            ':eventID',
            $eventID,
            PDO::PARAM_INT
        );


        $insertStmt->bindParam(
            ':packageID',
            $packageID,
            PDO::PARAM_INT
        );


        $estimatedPrice =
            $package['Price'];


        $insertStmt->bindParam(
            ':estimatedPrice',
            $estimatedPrice
        );


        if ($insertStmt->execute()) {

            return [
                'success' => true,
                'message' =>
                    'Package added to your event.'
            ];
        }


        return [
            'success' => false,
            'message' =>
                'Failed to add package.'
        ];
    }


/*
|--------------------------------------------------------------------------
| Get Event Items
|--------------------------------------------------------------------------
*/

public function getEventItems($eventID)
{
    $sql =
        "SELECT

            EventItem.EventItemID,
            EventItem.EventID,
            EventItem.ServiceID,
            EventItem.PackageID,
            EventItem.Quantity,
            EventItem.EstimatedPrice,
            EventItem.AddedDate,

            Service.ServiceName,
            Service.Description AS ServiceDescription,

            Package.PackageName,
            Package.Description AS PackageDescription,

            COALESCE(
                ServiceVendor.BusinessName,
                PackageVendor.BusinessName
            ) AS BusinessName

        FROM EventItem

        LEFT JOIN Service
            ON EventItem.ServiceID =
               Service.ServiceID

        LEFT JOIN Vendor AS ServiceVendor
            ON Service.VendorID =
               ServiceVendor.VendorID

        LEFT JOIN Package
            ON EventItem.PackageID =
               Package.PackageID

        LEFT JOIN Vendor AS PackageVendor
            ON Package.VendorID =
               PackageVendor.VendorID

        WHERE EventItem.EventID =
              :eventID

        ORDER BY EventItem.AddedDate ASC";


    $stmt =
        $this->conn->prepare($sql);


    $stmt->bindParam(
        ':eventID',
        $eventID,
        PDO::PARAM_INT
    );


    $stmt->execute();


    return $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );
}


/*
|--------------------------------------------------------------------------
| Remove Event Item
|--------------------------------------------------------------------------
*/

public function removeEventItem($eventItemID, $eventID)
{
    /*
     * Check whether the item belongs
     * to the selected event.
     */

    $checkSQL =
        "SELECT EventItemID
         FROM EventItem
         WHERE EventItemID = :eventItemID
           AND EventID = :eventID
         LIMIT 1";


    $checkStmt =
        $this->conn->prepare(
            $checkSQL
        );


    $checkStmt->bindParam(
        ':eventItemID',
        $eventItemID,
        PDO::PARAM_INT
    );


    $checkStmt->bindParam(
        ':eventID',
        $eventID,
        PDO::PARAM_INT
    );


    $checkStmt->execute();


    if (
        !$checkStmt->fetch(
            PDO::FETCH_ASSOC
        )
    ) {

        return [
            'success' => false,
            'message' =>
                'Event item not found.'
        ];
    }


    /*
     * Remove the selected item.
     */

    $deleteSQL =
        "DELETE FROM EventItem
         WHERE EventItemID = :eventItemID
           AND EventID = :eventID";


    $deleteStmt =
        $this->conn->prepare(
            $deleteSQL
        );


    $deleteStmt->bindParam(
        ':eventItemID',
        $eventItemID,
        PDO::PARAM_INT
    );


    $deleteStmt->bindParam(
        ':eventID',
        $eventID,
        PDO::PARAM_INT
    );


    if (
        $deleteStmt->execute()
    ) {

        return [
            'success' => true,
            'message' =>
                'Item removed from your event.'
        ];
    }


    return [
        'success' => false,
        'message' =>
            'Failed to remove item.'
    ];
}
    /*
    |--------------------------------------------------------------------------
    | Get Event Estimated Total
    |--------------------------------------------------------------------------
    */

    public function getEventTotal($eventID)
    {
        $sql =
            "SELECT
                COALESCE(
                    SUM(
                        Quantity * EstimatedPrice
                    ),
                    0
                ) AS EstimatedTotal

            FROM EventItem

            WHERE EventID = :eventID";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':eventID',
            $eventID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        $result =
            $stmt->fetch(PDO::FETCH_ASSOC);


        return $result['EstimatedTotal'];
    }
}