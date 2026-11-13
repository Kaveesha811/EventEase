<?php

require_once __DIR__ . '/../../config/database.php';

class Package
{
    private $conn;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $database = new Database();

        $this->conn = $database->connect();
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SIDE
    | Get Active Packages
    |--------------------------------------------------------------------------
    */

    public function getActivePackages($eventTypeID = null)
    {
        $sql = "SELECT
                    Package.PackageID,
                    Package.VendorID,
                    Package.PackageName,
                    Package.Description,
                    Package.Price,
                    COALESCE(
                        (
                            SELECT SUM(
                                Service.Price * PackageService.Quantity
                            )
                            FROM PackageService
                            INNER JOIN Service
                                ON PackageService.ServiceID =
                                   Service.ServiceID
                            WHERE PackageService.PackageID =
                                  Package.PackageID
                        ),
                        0
                    ) AS OriginalPrice,
                    Vendor.BusinessName,
                    Vendor.BusinessAddress

                FROM Package

                INNER JOIN Vendor
                    ON Package.VendorID =
                       Vendor.VendorID

                WHERE Package.Status = 'Active'

                  AND Vendor.VerificationStatus =
                      'Approved'

        ";

        if ($eventTypeID !== null) {
            $sql .= "
                  AND EXISTS (
                      SELECT 1
                      FROM PackageService
                      INNER JOIN Service
                          ON PackageService.ServiceID =
                             Service.ServiceID
                      INNER JOIN EventTypeServiceCategory
                          ON Service.ServiceCategoryID =
                             EventTypeServiceCategory.ServiceCategoryID
                      WHERE PackageService.PackageID =
                            Package.PackageID
                        AND EventTypeServiceCategory.EventTypeID =
                            :eventTypeID
                  )
            ";
        }

        $sql .= "
                ORDER BY
                    Package.PackageName ASC";


        $stmt =
            $this->conn->prepare($sql);

        if ($eventTypeID !== null) {
            $stmt->bindValue(
                ':eventTypeID',
                $eventTypeID,
                PDO::PARAM_INT
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SIDE
    | Get Package Details
    |--------------------------------------------------------------------------
    */

    public function getPackageDetails($packageID)
    {
        $sql = "SELECT
                    Package.PackageID,
                    Package.VendorID,
                    Package.PackageName,
                    Package.Description,
                    Package.Price,
                    COALESCE(
                        (
                            SELECT SUM(
                                Service.Price * PackageService.Quantity
                            )
                            FROM PackageService
                            INNER JOIN Service
                                ON PackageService.ServiceID =
                                   Service.ServiceID
                            WHERE PackageService.PackageID =
                                  Package.PackageID
                        ),
                        0
                    ) AS OriginalPrice,
                    Vendor.BusinessName,
                    Vendor.BusinessDescription,
                    Vendor.BusinessAddress

                FROM Package

                INNER JOIN Vendor
                    ON Package.VendorID =
                       Vendor.VendorID

                WHERE Package.PackageID =
                      :packageID

                  AND Package.Status = 'Active'

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


        $stmt->execute();


        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SIDE
    | Get Services Included in Package
    |--------------------------------------------------------------------------
    */

    public function getPackageServices($packageID)
    {
        $sql = "SELECT
                    PackageService.PackageID,
                    PackageService.ServiceID,
                    PackageService.Quantity,
                    PackageService.IncludedDescription,

                    Service.ServiceName,
                    Service.Description,
                    Service.Price

                FROM PackageService

                INNER JOIN Service
                    ON PackageService.ServiceID =
                       Service.ServiceID

                WHERE PackageService.PackageID =
                      :packageID

                  AND Service.Status = 'Active'

                ORDER BY
                    Service.ServiceName ASC";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':packageID',
            $packageID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    | Get Vendor Packages
    |--------------------------------------------------------------------------
    */

    public function getVendorPackages($vendorID)
    {
        $sql = "SELECT
                    Package.PackageID,
                    Package.VendorID,
                    Package.PackageName,
                    Package.Description,
                    Package.Price,
                    Package.Status,
                    Package.CreatedAt

                FROM Package

                WHERE Package.VendorID =
                      :vendorID

                ORDER BY
                    Package.CreatedAt DESC";


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
    | VENDOR SIDE
    | Get Vendor's Active Services
    |--------------------------------------------------------------------------
    */

    public function getVendorActiveServices($vendorID)
    {
        $sql = "SELECT
                    Service.ServiceID,
                    Service.ServiceCategoryID,
                    Service.ServiceName,
                    Service.Description,
                    Service.Price,
                    Service.Status,
                    ServiceCategory.CategoryName

                FROM Service

                INNER JOIN ServiceCategory
                    ON Service.ServiceCategoryID =
                       ServiceCategory.ServiceCategoryID

                WHERE Service.VendorID =
                      :vendorID

                  AND Service.Status =
                      'Active'

                ORDER BY
                    ServiceCategory.CategoryName ASC,
                    Service.ServiceName ASC";


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


    public function getServicesTotal($vendorID, $serviceIDs)
    {
        if (empty($serviceIDs)) {
            return 0;
        }

        $placeholders = implode(
            ',',
            array_fill(0, count($serviceIDs), '?')
        );

        $sql =
            "SELECT COALESCE(
                SUM(Price),
                0
             )
             FROM Service
             WHERE VendorID = ?
               AND Status = 'Active'
               AND ServiceID IN ({$placeholders})";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            array_merge([$vendorID], $serviceIDs)
        );

        return (float) $stmt->fetchColumn();
    }


    public function getActiveEventTypes()
    {
        $stmt = $this->conn->prepare(
            "SELECT EventTypeID, EventTypeName
             FROM EventType
             WHERE Status = 'Active'
             ORDER BY EventTypeName ASC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getPackageEventTypeIDs($packageID, $vendorID)
    {
        $stmt = $this->conn->prepare(
            "SELECT PackageEventType.EventTypeID
             FROM PackageEventType
             INNER JOIN Package
                 ON PackageEventType.PackageID = Package.PackageID
             WHERE PackageEventType.PackageID = :packageID
               AND Package.VendorID = :vendorID"
        );

        $stmt->execute([
            ':packageID' => $packageID,
            ':vendorID' => $vendorID
        ]);

        return array_map(
            'intval',
            $stmt->fetchAll(PDO::FETCH_COLUMN)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    | Create Package
    |--------------------------------------------------------------------------
    */

    public function createPackage(
        $vendorID,
        $packageName,
        $description,
        $price,
        $serviceIDs,
        $eventTypeIDs
    ) {
        try {

            $this->conn->beginTransaction();


            /*
             * Create Package
             */

            $sql = "INSERT INTO Package
                    (
                        VendorID,
                        PackageName,
                        Description,
                        Price,
                        Status
                    )

                    VALUES
                    (
                        :vendorID,
                        :packageName,
                        :description,
                        :price,
                        'Active'
                    )";


            $stmt =
                $this->conn->prepare($sql);


            $stmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );

            $stmt->bindParam(
                ':packageName',
                $packageName
            );

            $stmt->bindParam(
                ':description',
                $description
            );

            $stmt->bindParam(
                ':price',
                $price
            );


            $stmt->execute();


            $packageID =
                $this->conn->lastInsertId();


            /*
             * Add Services to Package
             */

            $sqlService =
                "INSERT INTO PackageService
                (
                    PackageID,
                    ServiceID,
                    Quantity,
                    IncludedDescription
                )

                SELECT
                    :packageID,
                    ServiceID,
                    1,
                    NULL

                FROM Service

                WHERE ServiceID =
                      :serviceID

                  AND VendorID =
                      :vendorID

                  AND Status =
                      'Active'";


            $stmtService =
                $this->conn->prepare(
                    $sqlService
                );


            foreach (
                $serviceIDs as $serviceID
            ) {

                $stmtService->execute([
                    ':packageID' => $packageID,
                    ':serviceID' => $serviceID,
                    ':vendorID' => $vendorID
                ]);
            }

            $eventTypeStmt = $this->conn->prepare(
                "INSERT INTO PackageEventType
                 (PackageID, EventTypeID)
                 VALUES (:packageID, :eventTypeID)"
            );

            foreach ($eventTypeIDs as $eventTypeID) {
                $eventTypeStmt->execute([
                    ':packageID' => $packageID,
                    ':eventTypeID' => $eventTypeID
                ]);
            }


            $this->conn->commit();

            return $packageID;

        } catch (PDOException $exception) {

            if (
                $this->conn->inTransaction()
            ) {

                $this->conn->rollBack();
            }

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    | Get Single Vendor Package
    |--------------------------------------------------------------------------
    */

    public function getVendorPackage(
        $packageID,
        $vendorID
    ) {
        $sql = "SELECT
                    Package.PackageID,
                    Package.VendorID,
                    Package.PackageName,
                    Package.Description,
                    Package.Price,
                    Package.Status

                FROM Package

                WHERE Package.PackageID =
                      :packageID

                  AND Package.VendorID =
                      :vendorID

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':packageID',
            $packageID,
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
    | VENDOR SIDE
    | Get Services Already in Package
    |--------------------------------------------------------------------------
    */

    public function getPackageServiceIDs(
        $packageID,
        $vendorID
    ) {
        $sql = "SELECT
                    PackageService.ServiceID

                FROM PackageService

                INNER JOIN Package
                    ON PackageService.PackageID =
                       Package.PackageID

                WHERE PackageService.PackageID =
                      :packageID

                  AND Package.VendorID =
                      :vendorID";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':packageID',
            $packageID,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_COLUMN
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    | Update Package
    |--------------------------------------------------------------------------
    */

    public function updatePackage(
        $packageID,
        $vendorID,
        $packageName,
        $description,
        $price,
        $serviceIDs,
        $eventTypeIDs
    ) {
        try {

            $this->conn->beginTransaction();


            /*
             * Update Package Details
             */

            $sql = "UPDATE Package

                    SET
                        PackageName = :packageName,
                        Description = :description,
                        Price = :price

                    WHERE PackageID =
                          :packageID

                      AND VendorID =
                          :vendorID";


            $stmt =
                $this->conn->prepare($sql);


            $stmt->bindParam(
                ':packageName',
                $packageName
            );

            $stmt->bindParam(
                ':description',
                $description
            );

            $stmt->bindParam(
                ':price',
                $price
            );

            $stmt->bindParam(
                ':packageID',
                $packageID,
                PDO::PARAM_INT
            );

            $stmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );


            $stmt->execute();


            /*
             * Remove Existing Services
             */

            $sqlDelete =
                "DELETE FROM PackageService

                 WHERE PackageID =
                       :packageID";


            $stmtDelete =
                $this->conn->prepare(
                    $sqlDelete
                );


            $stmtDelete->bindParam(
                ':packageID',
                $packageID,
                PDO::PARAM_INT
            );


            $stmtDelete->execute();


            /*
             * Add Updated Services
             */

            $sqlService =
                "INSERT INTO PackageService
                (
                    PackageID,
                    ServiceID,
                    Quantity,
                    IncludedDescription
                )

                SELECT
                    :packageID,
                    ServiceID,
                    1,
                    NULL

                FROM Service

                WHERE ServiceID =
                      :serviceID

                  AND VendorID =
                      :vendorID

                  AND Status =
                      'Active'";


            $stmtService =
                $this->conn->prepare(
                    $sqlService
                );


            foreach (
                $serviceIDs as $serviceID
            ) {

                $stmtService->execute([
                    ':packageID' => $packageID,
                    ':serviceID' => $serviceID,
                    ':vendorID' => $vendorID
                ]);
            }

            $deleteEventTypes = $this->conn->prepare(
                "DELETE FROM PackageEventType
                 WHERE PackageID = :packageID"
            );

            $deleteEventTypes->execute([
                ':packageID' => $packageID
            ]);

            $eventTypeStmt = $this->conn->prepare(
                "INSERT INTO PackageEventType
                 (PackageID, EventTypeID)
                 VALUES (:packageID, :eventTypeID)"
            );

            foreach ($eventTypeIDs as $eventTypeID) {
                $eventTypeStmt->execute([
                    ':packageID' => $packageID,
                    ':eventTypeID' => $eventTypeID
                ]);
            }


            $this->conn->commit();

            return true;

        } catch (PDOException $exception) {

            if (
                $this->conn->inTransaction()
            ) {

                $this->conn->rollBack();
            }

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    | Deactivate Package
    |--------------------------------------------------------------------------
    */

    public function deactivatePackage(
        $packageID,
        $vendorID
    ) {
        $sql = "UPDATE Package

                SET Status = 'Inactive'

                WHERE PackageID =
                      :packageID

                  AND VendorID =
                      :vendorID

                  AND Status =
                      'Active'";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':packageID',
            $packageID,
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