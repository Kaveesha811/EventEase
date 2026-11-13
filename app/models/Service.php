<?php

require_once __DIR__ . '/../../config/database.php';

class Service
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
    | Get Service Categories
    |--------------------------------------------------------------------------
    */

    public function getServiceCategories($eventTypeID = null)
    {
        $sql = "SELECT DISTINCT
                    ServiceCategory.ServiceCategoryID,
                    ServiceCategory.CategoryName,
                    ServiceCategory.Description

                FROM ServiceCategory

                INNER JOIN Service
                    ON ServiceCategory.ServiceCategoryID =
                       Service.ServiceCategoryID

                WHERE ServiceCategory.Status = 'Active'

                  AND Service.Status = 'Active'
        ";

        if ($eventTypeID !== null) {
            $sql .= "
                  AND EXISTS (
                      SELECT 1
                      FROM EventTypeServiceCategory
                      WHERE EventTypeServiceCategory.EventTypeID =
                            :eventTypeID
                        AND EventTypeServiceCategory.ServiceCategoryID =
                            ServiceCategory.ServiceCategoryID
                  )
            ";
        }

        $sql .= "

                ORDER BY
                    ServiceCategory.CategoryName ASC";

        $stmt = $this->conn->prepare($sql);

        if ($eventTypeID !== null) {
            $stmt->bindValue(
                ':eventTypeID',
                $eventTypeID,
                PDO::PARAM_INT
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendors by Service Category
    |--------------------------------------------------------------------------
    */

    public function getVendorsByCategory(
        $serviceCategoryID,
        $eventDate
    ) {

        $sql = "SELECT

                    Vendor.VendorID,
                    Vendor.BusinessName,
                    Vendor.BusinessDescription,
                    Vendor.BusinessAddress,
                    Vendor.FacebookProfile,

                    Service.ServiceID,
                    Service.ServiceName,
                    Service.Description AS ServiceDescription,
                    Service.Price,

                    COALESCE(
                        Availability.Status,
                        'Not Specified'
                    ) AS AvailabilityStatus

                FROM Vendor

                INNER JOIN Service
                    ON Vendor.VendorID =
                       Service.VendorID

                INNER JOIN VendorServiceCategory
                    ON Vendor.VendorID =
                       VendorServiceCategory.VendorID

                    AND Service.ServiceCategoryID =
                        VendorServiceCategory.ServiceCategoryID

                LEFT JOIN Availability
                    ON Vendor.VendorID =
                       Availability.VendorID

                    AND Availability.AvailabilityDate =
                        :eventDate

                WHERE Vendor.VerificationStatus =
                      'Approved'

                  AND Service.Status =
                      'Active'

                  AND VendorServiceCategory.ServiceCategoryID =
                      :serviceCategoryID

                ORDER BY
                    Vendor.BusinessName ASC,
                    Service.ServiceName ASC";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceCategoryID',
            $serviceCategoryID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':eventDate',
            $eventDate
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Service Details
    |--------------------------------------------------------------------------
    */

    public function getServiceDetails(
        $serviceID,
        $eventDate
    ) {

        $sql = "SELECT

                    Vendor.VendorID,
                    Vendor.BusinessName,
                    Vendor.BusinessDescription,
                    Vendor.BusinessAddress,
                    Vendor.FacebookProfile,

                    Service.ServiceID,
                    Service.ServiceCategoryID,
                    Service.ServiceName,
                    Service.Description AS ServiceDescription,
                    Service.Price,

                    ServiceCategory.CategoryName,

                    COALESCE(
                        Availability.Status,
                        'Not Specified'
                    ) AS AvailabilityStatus

                FROM Service

                INNER JOIN Vendor
                    ON Service.VendorID =
                       Vendor.VendorID

                INNER JOIN ServiceCategory
                    ON Service.ServiceCategoryID =
                       ServiceCategory.ServiceCategoryID

                LEFT JOIN Availability
                    ON Vendor.VendorID =
                       Availability.VendorID

                    AND Availability.AvailabilityDate =
                        :eventDate

                WHERE Service.ServiceID =
                      :serviceID

                  AND Vendor.VerificationStatus =
                      'Approved'

                  AND Service.Status =
                      'Active'

                LIMIT 1";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':eventDate',
            $eventDate
        );


        $stmt->execute();


        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Services
    |--------------------------------------------------------------------------
    */

    public function getVendorServices($vendorID)
    {
        $sql = "SELECT

                    Service.ServiceID,
                    Service.VendorID,
                    Service.ServiceCategoryID,
                    Service.ServiceName,
                    Service.Description,
                    Service.Price,
                    Service.Status,
                    Service.CreatedAt,

                    ServiceCategory.CategoryName

                FROM Service

                INNER JOIN ServiceCategory
                    ON Service.ServiceCategoryID =
                       ServiceCategory.ServiceCategoryID

                WHERE Service.VendorID =
                      :vendorID

                ORDER BY
                    Service.CreatedAt DESC";


        $stmt = $this->conn->prepare($sql);


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
    | Get Active Service Categories
    |--------------------------------------------------------------------------
    */

    public function getActiveServiceCategories()
    {
        $sql = "SELECT

                    ServiceCategoryID,
                    CategoryName,
                    Description

                FROM ServiceCategory

                WHERE Status = 'Active'

                ORDER BY
                    CategoryName ASC";


        $stmt = $this->conn->prepare($sql);

        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Vendor Service
    |--------------------------------------------------------------------------
    */

    public function createService(
        $vendorID,
        $serviceCategoryID,
        $serviceName,
        $description,
        $price
    ) {

        $sql = "INSERT INTO Service
                (
                    VendorID,
                    ServiceCategoryID,
                    ServiceName,
                    Description,
                    Price,
                    Status
                )

                VALUES
                (
                    :vendorID,
                    :serviceCategoryID,
                    :serviceName,
                    :description,
                    :price,
                    'Active'
                )";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':serviceCategoryID',
            $serviceCategoryID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':serviceName',
            $serviceName
        );


        $stmt->bindParam(
            ':description',
            $description
        );


        $stmt->bindParam(
            ':price',
            $price
        );


        if ($stmt->execute()) {

            $categorySQL =
                "INSERT IGNORE INTO VendorServiceCategory
                (
                    VendorID,
                    ServiceCategoryID
                )

                VALUES
                (
                    :vendorID,
                    :serviceCategoryID
                )";

            $categoryStmt =
                $this->conn->prepare($categorySQL);

            $categoryStmt->execute([
                ':vendorID' => $vendorID,
                ':serviceCategoryID' => $serviceCategoryID
            ]);

            return $this->conn->lastInsertId();
        }


        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Vendor Service
    |--------------------------------------------------------------------------
    */

    public function getVendorService(
        $serviceID,
        $vendorID
    ) {

        $sql = "SELECT

                    Service.ServiceID,
                    Service.VendorID,
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

                WHERE Service.ServiceID =
                      :serviceID

                  AND Service.VendorID =
                      :vendorID

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceID',
            $serviceID,
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
    | Deactivate Vendor Service
    |--------------------------------------------------------------------------
    */

    public function deactivateService(
        $serviceID,
        $vendorID
    ) {

        $sql = "UPDATE Service

                SET Status = 'Inactive'

                WHERE ServiceID =
                      :serviceID

                  AND VendorID =
                      :vendorID

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        if ($stmt->execute()) {

            return $stmt->rowCount() > 0;
        }


        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vendor Service
    |--------------------------------------------------------------------------
    */

    public function updateService(
        $serviceID,
        $vendorID,
        $serviceCategoryID,
        $serviceName,
        $description,
        $price
    ) {

        $sql = "UPDATE Service

                SET
                    ServiceCategoryID = :serviceCategoryID,
                    ServiceName = :serviceName,
                    Description = :description,
                    Price = :price

                WHERE ServiceID = :serviceID

                  AND VendorID = :vendorID

                LIMIT 1";


        $stmt =
            $this->conn->prepare($sql);


        $stmt->bindParam(
            ':serviceID',
            $serviceID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':serviceCategoryID',
            $serviceCategoryID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':serviceName',
            $serviceName
        );


        $stmt->bindParam(
            ':description',
            $description
        );


        $stmt->bindParam(
            ':price',
            $price
        );


        if ($stmt->execute()) {

            return true;
        }


        return false;
    }
}