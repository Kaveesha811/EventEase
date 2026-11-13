<?php

require_once __DIR__ . '/../../config/database.php';

class Booking
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

            $this->conn =
                $database->connect();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Booking Requests for an Event
    |--------------------------------------------------------------------------
    |
    | One booking is created for each vendor involved
    | in the customer's selected event items.
    |
    */

    public function createBookingsForEvent(
        $eventID,
        $customerID,
        $notes = ''
    ) {
        try {

            /*
             * Start transaction
             */

            $this->conn->beginTransaction();


            /*
             * Verify that the event belongs
             * to the logged-in customer.
             */

            $eventSQL =
                "SELECT
                    EventID,
                    CustomerID,
                    EventDate,
                    EventTypeID,
                    Status

                 FROM Events

                 WHERE EventID =
                       :eventID

                   AND CustomerID =
                       :customerID

                 LIMIT 1";

            $eventStmt =
                $this->conn->prepare(
                    $eventSQL
                );

            $eventStmt->bindParam(
                ':eventID',
                $eventID,
                PDO::PARAM_INT
            );

            $eventStmt->bindParam(
                ':customerID',
                $customerID,
                PDO::PARAM_INT
            );

            $eventStmt->execute();

            $event =
                $eventStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if (!$event) {

                $this->conn->rollBack();

                return [
                    'success' => false,
                    'message' =>
                        'Event not found.'
                ];
            }


            /*
             * Get all selected event items
             * and identify their vendors.
             */

            $itemsSQL =
                "SELECT
                    EventItem.EventItemID,
                    EventItem.ServiceID,
                    EventItem.PackageID,
                    EventItem.Quantity,
                    EventItem.EstimatedPrice,

                    Service.VendorID
                        AS ServiceVendorID,

                    Package.VendorID
                        AS PackageVendorID

                 FROM EventItem

                 LEFT JOIN Service
                    ON EventItem.ServiceID =
                       Service.ServiceID

                 LEFT JOIN Package
                    ON EventItem.PackageID =
                       Package.PackageID

                 LEFT JOIN Vendor AS ServiceVendor
                    ON Service.VendorID =
                       ServiceVendor.VendorID

                 LEFT JOIN Vendor AS PackageVendor
                    ON Package.VendorID =
                       PackageVendor.VendorID

                 WHERE EventItem.EventID =
                       :eventID

                   AND (
                       (
                           EventItem.ServiceID IS NOT NULL
                           AND Service.Status = 'Active'
                           AND ServiceVendor.VerificationStatus = 'Approved'
                           AND EXISTS (
                               SELECT 1
                               FROM EventTypeServiceCategory
                               WHERE EventTypeServiceCategory.EventTypeID =
                                     :bookingEventTypeID
                                 AND EventTypeServiceCategory.ServiceCategoryID =
                                     Service.ServiceCategoryID
                           )
                       )
                       OR
                       (
                           EventItem.PackageID IS NOT NULL
                           AND Package.Status = 'Active'
                           AND PackageVendor.VerificationStatus = 'Approved'
                           AND EXISTS (
                               SELECT 1
                               FROM PackageService
                               INNER JOIN Service AS PackageServiceItem
                                   ON PackageService.ServiceID =
                                      PackageServiceItem.ServiceID
                               INNER JOIN EventTypeServiceCategory
                                   ON PackageServiceItem.ServiceCategoryID =
                                      EventTypeServiceCategory.ServiceCategoryID
                               WHERE PackageService.PackageID =
                                     Package.PackageID
                                 AND EventTypeServiceCategory.EventTypeID =
                                     :packageEventTypeID
                           )
                       )
                   )";

            $itemsStmt =
                $this->conn->prepare(
                    $itemsSQL
                );

            $itemsStmt->bindParam(
                ':eventID',
                $eventID,
                PDO::PARAM_INT
            );

            $itemsStmt->bindValue(
                ':bookingEventTypeID',
                $event['EventTypeID'],
                PDO::PARAM_INT
            );

            $itemsStmt->bindValue(
                ':packageEventTypeID',
                $event['EventTypeID'],
                PDO::PARAM_INT
            );

            $itemsStmt->execute();

            $items =
                $itemsStmt->fetchAll(
                    PDO::FETCH_ASSOC
                );


            /*
             * An event must contain at least
             * one selected service or package.
             */

            if (empty($items)) {

                $this->conn->rollBack();

                return [
                    'success' => false,
                    'message' =>
                        'Please add at least one service or package before requesting a booking.'
                ];
            }


            /*
             * Group event items by vendor.
             */

            $vendorItems = [];


            foreach ($items as $item) {

                $vendorID =
                    !empty(
                        $item['ServiceVendorID']
                    )
                    ? $item['ServiceVendorID']
                    : $item['PackageVendorID'];


                if (!$vendorID) {
                    continue;
                }


                if (
                    !isset(
                        $vendorItems[$vendorID]
                    )
                ) {

                    $vendorItems[$vendorID] =
                        [];
                }


                $vendorItems[$vendorID][] =
                    $item;
            }


            /*
             * Create one booking for each vendor.
             */

            foreach (
                $vendorItems
                as $vendorID =>
                    $vendorEventItems
            ) {

                /*
                 * Check whether a booking already
                 * exists for this event and vendor.
                 */

                $existingSQL =
                    "SELECT
                        BookingID

                     FROM Booking

                     WHERE EventID =
                           :eventID

                       AND VendorID =
                           :vendorID

                       AND Status IN
                           (
                               'Pending',
                               'Accepted',
                               'Completed'
                           )

                     LIMIT 1";

                $existingStmt =
                    $this->conn->prepare(
                        $existingSQL
                    );

                $existingStmt->bindParam(
                    ':eventID',
                    $eventID,
                    PDO::PARAM_INT
                );

                $existingStmt->bindParam(
                    ':vendorID',
                    $vendorID,
                    PDO::PARAM_INT
                );

                $existingStmt->execute();


                if (
                    $existingStmt->fetch(
                        PDO::FETCH_ASSOC
                    )
                ) {

                    continue;
                }


                /*
                 * Calculate vendor booking total.
                 */

                $totalAmount = 0;


                foreach (
                    $vendorEventItems
                    as $item
                ) {

                    $totalAmount +=
                        $item['Quantity'] *
                        $item['EstimatedPrice'];
                }


                /*
                 * Create Booking.
                 */

                $bookingSQL =
                    "INSERT INTO Booking
                    (
                        EventID,
                        VendorID,
                        BookingDate,
                        TotalAmount,
                        Status,
                        Notes
                    )

                    VALUES
                    (
                        :eventID,
                        :vendorID,
                        NOW(),
                        :totalAmount,
                        'Pending',
                        :notes
                    )";

                $bookingStmt =
                    $this->conn->prepare(
                        $bookingSQL
                    );

                $bookingStmt->bindParam(
                    ':eventID',
                    $eventID,
                    PDO::PARAM_INT
                );

                $bookingStmt->bindParam(
                    ':vendorID',
                    $vendorID,
                    PDO::PARAM_INT
                );

                $bookingStmt->bindParam(
                    ':totalAmount',
                    $totalAmount
                );

                $bookingStmt->bindParam(
                    ':notes',
                    $notes
                );

                $bookingStmt->execute();


                /*
                 * Get newly created Booking ID.
                 */

                $bookingID =
                    $this->conn->lastInsertId();


                /*
                 * Copy selected event items
                 * into BookingItem.
                 */

                foreach (
                    $vendorEventItems
                    as $item
                ) {

                    $serviceID =
                        !empty(
                            $item['ServiceID']
                        )
                        ? $item['ServiceID']
                        : null;

                    $packageID =
                        !empty(
                            $item['PackageID']
                        )
                        ? $item['PackageID']
                        : null;

                    $quantity =
                        $item['Quantity'];

                    $unitPrice =
                        $item['EstimatedPrice'];

                    $subTotal =
                        $quantity *
                        $unitPrice;


                    $bookingItemSQL =
                        "INSERT INTO BookingItem
                        (
                            BookingID,
                            ServiceID,
                            PackageID,
                            Quantity,
                            UnitPrice,
                            SubTotal
                        )

                        VALUES
                        (
                            :bookingID,
                            :serviceID,
                            :packageID,
                            :quantity,
                            :unitPrice,
                            :subTotal
                        )";

                    $bookingItemStmt =
                        $this->conn->prepare(
                            $bookingItemSQL
                        );


                    $bookingItemStmt->bindParam(
                        ':bookingID',
                        $bookingID,
                        PDO::PARAM_INT
                    );


                    /*
                     * Service ID
                     */

                    if ($serviceID !== null) {

                        $bookingItemStmt->bindValue(
                            ':serviceID',
                            $serviceID,
                            PDO::PARAM_INT
                        );

                    } else {

                        $bookingItemStmt->bindValue(
                            ':serviceID',
                            null,
                            PDO::PARAM_NULL
                        );
                    }


                    /*
                     * Package ID
                     */

                    if ($packageID !== null) {

                        $bookingItemStmt->bindValue(
                            ':packageID',
                            $packageID,
                            PDO::PARAM_INT
                        );

                    } else {

                        $bookingItemStmt->bindValue(
                            ':packageID',
                            null,
                            PDO::PARAM_NULL
                        );
                    }


                    $bookingItemStmt->bindParam(
                        ':quantity',
                        $quantity
                    );

                    $bookingItemStmt->bindParam(
                        ':unitPrice',
                        $unitPrice
                    );

                    $bookingItemStmt->bindParam(
                        ':subTotal',
                        $subTotal
                    );

                    $bookingItemStmt->execute();
                }
            }


            /*
             * Confirm transaction.
             */

            $this->conn->commit();


            return [
                'success' => true,
                'message' =>
                    'Booking request submitted successfully.'
            ];


        } catch (PDOException $exception) {

            /*
             * Roll back if something fails.
             */

            if (
                $this->conn->inTransaction()
            ) {

                $this->conn->rollBack();
            }


            return [
                'success' => false,
                'message' =>
                    'Failed to submit booking request.'
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Bookings
    |--------------------------------------------------------------------------
    |
    | Retrieves booking requests belonging to the
    | currently logged-in vendor.
    |
    */

    public function getVendorBookings(
        $vendorID
    ) {

        $sql =
            "SELECT
                Booking.BookingID,
                Booking.EventID,
                Booking.VendorID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status,
                Booking.Notes,

                Events.EventName,
                Events.EventDate,
                Events.Location,
                Events.GuestCount,

                Users.FirstName,
                Users.LastName,
                Users.Email,
                Users.Phone

             FROM Booking

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Customer
                ON Events.CustomerID =
                   Customer.CustomerID

             INNER JOIN Users
                ON Customer.UserID =
                   Users.UserID

             WHERE Booking.VendorID =
                   :vendorID

             ORDER BY Booking.BookingDate DESC";


        $stmt =
            $this->conn->prepare(
                $sql
            );


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
    | Get Vendor Transaction History
    |--------------------------------------------------------------------------
    |
    | Retrieves booking and payment transaction details
    | belonging to the currently logged-in vendor.
    |
    */

    public function getVendorTransactionHistory(
        $vendorID
    ) {

        $sql =
            "SELECT
                Booking.BookingID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status AS BookingStatus,

                Events.EventID,
                Events.EventName,
                Events.EventDate,
                Events.Location,

                Users.FirstName,
                Users.LastName,
                Users.Email,
                Users.Phone,

                COALESCE(
                    SUM(
                        CASE
                            WHEN Payment.PaymentStatus = 'Completed'
                            THEN Payment.Amount
                            ELSE 0
                        END
                    ),
                    0
                ) AS PaidAmount,

                MAX(
                    Payment.PaymentDate
                ) AS LastPaymentDate,

                COALESCE(
                    GROUP_CONCAT(
                        DISTINCT Payment.PaymentMethod
                        ORDER BY Payment.PaymentID
                        SEPARATOR ', '
                    ),
                    'Not Paid'
                ) AS PaymentMethods,

                CASE
                    WHEN COALESCE(
                        SUM(
                            CASE
                                WHEN Payment.PaymentStatus = 'Completed'
                                THEN Payment.Amount
                                ELSE 0
                            END
                        ),
                        0
                    ) >= Booking.TotalAmount
                    THEN 'Completed'

                    WHEN COALESCE(
                        SUM(
                            CASE
                                WHEN Payment.PaymentStatus = 'Completed'
                                THEN Payment.Amount
                                ELSE 0
                            END
                        ),
                        0
                    ) > 0
                    THEN 'Partially Paid'

                    ELSE 'Pending'
                END AS PaymentStatus

             FROM Booking

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Customer
                ON Events.CustomerID =
                   Customer.CustomerID

             INNER JOIN Users
                ON Customer.UserID =
                   Users.UserID

             LEFT JOIN Payment
                ON Booking.BookingID =
                   Payment.BookingID

             WHERE Booking.VendorID =
                   :vendorID

             GROUP BY
                Booking.BookingID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status,
                Events.EventID,
                Events.EventName,
                Events.EventDate,
                Events.Location,
                Users.FirstName,
                Users.LastName,
                Users.Email,
                Users.Phone

             ORDER BY
                Booking.BookingID DESC";

        $stmt =
            $this->conn->prepare(
                $sql
            );

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
    | Get Customer Bookings
    |--------------------------------------------------------------------------
    */

    public function getCustomerBookings(
        $customerID
    ) {

        $sql =
            "SELECT
                Booking.BookingID,
                Booking.EventID,
                Booking.VendorID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status,
                Booking.Notes,

                Events.EventName,
                Events.EventDate,
                Events.Location,

                Vendor.BusinessName

             FROM Booking

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Vendor
                ON Booking.VendorID =
                   Vendor.VendorID

             WHERE Events.CustomerID =
                   :customerID

             ORDER BY Booking.BookingDate DESC";


        $stmt =
            $this->conn->prepare(
                $sql
            );


        $stmt->bindParam(
            ':customerID',
            $customerID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Booking Details
    |--------------------------------------------------------------------------
    |
    | Used by the customer to view details of
    | a specific booking.
    |
    */

    public function getBookingDetails(
        $bookingID,
        $customerID
    ) {

        $sql =
            "SELECT
                Booking.BookingID,
                Booking.EventID,
                Booking.VendorID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status,
                Booking.Notes,

                Events.EventName,
                Events.EventDate,
                Events.Location,

                Vendor.BusinessName,
                Vendor.BusinessAddress

             FROM Booking

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Vendor
                ON Booking.VendorID =
                   Vendor.VendorID

             WHERE Booking.BookingID =
                   :bookingID

               AND Events.CustomerID =
                   :customerID

             LIMIT 1";


        $stmt =
            $this->conn->prepare(
                $sql
            );


        $stmt->bindParam(
            ':bookingID',
            $bookingID,
            PDO::PARAM_INT
        );


        $stmt->bindParam(
            ':customerID',
            $customerID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Booking Items
    |--------------------------------------------------------------------------
    |
    | Retrieves services and packages belonging
    | to a specific booking.
    |
    */

    public function getBookingItems(
        $bookingID
    ) {

        $sql =
            "SELECT
                BookingItem.BookingItemID,
                BookingItem.BookingID,
                BookingItem.ServiceID,
                BookingItem.PackageID,
                BookingItem.Quantity,
                BookingItem.UnitPrice,
                BookingItem.SubTotal,

                Service.ServiceName,

                Package.PackageName

             FROM BookingItem

             LEFT JOIN Service
                ON BookingItem.ServiceID =
                   Service.ServiceID

             LEFT JOIN Package
                ON BookingItem.PackageID =
                   Package.PackageID

             WHERE BookingItem.BookingID =
                   :bookingID

             ORDER BY
                BookingItem.BookingItemID ASC";


        $stmt =
            $this->conn->prepare(
                $sql
            );


        $stmt->bindParam(
            ':bookingID',
            $bookingID,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );


    }

    /*
|--------------------------------------------------------------------------
| Update Booking Status
|--------------------------------------------------------------------------
|
| Allows the vendor to accept, reject, cancel or complete
| a booking belonging to the logged-in vendor.
|
*/

public function updateBookingStatus(
    $bookingID,
    $vendorID,
    $newStatus
) {

    /*
     * Allowed status values
     */

    $allowedStatuses = [
        'Accepted',
        'Rejected',
        'Cancelled',
        'Completed'
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
                'Invalid booking status.'
        ];
    }


    try {

        /*
         * Start transaction
         */

        $this->conn->beginTransaction();


        /*
         * Get booking belonging to
         * the logged-in vendor.
         */

        $bookingSQL =
            "SELECT
                BookingID,
                EventID,
                VendorID,
                Status

             FROM Booking

             WHERE BookingID =
                   :bookingID

               AND VendorID =
                   :vendorID

             LIMIT 1";


        $bookingStmt =
            $this->conn->prepare(
                $bookingSQL
            );


        $bookingStmt->bindParam(
            ':bookingID',
            $bookingID,
            PDO::PARAM_INT
        );


        $bookingStmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $bookingStmt->execute();


        $booking =
            $bookingStmt->fetch(
                PDO::FETCH_ASSOC
            );


        /*
         * Booking not found
         */

        if (!$booking) {

            $this->conn->rollBack();

            return [
                'success' => false,
                'message' =>
                    'Booking not found.'
            ];
        }


        /*
         * Check current status
         */

        $currentStatus =
            $booking['Status'];


        /*
         * Validate status transition
         */

        $validTransition = false;


        if (
            $currentStatus === 'Pending' &&
            (
                $newStatus === 'Accepted' ||
                $newStatus === 'Rejected'
            )
        ) {

            $validTransition = true;

        } elseif (
            $currentStatus === 'Accepted' &&
            (
                $newStatus === 'Completed' ||
                $newStatus === 'Cancelled'
            )
        ) {

            $validTransition = true;
        }


        /*
         * Prevent invalid status changes
         */

        if (!$validTransition) {

            $this->conn->rollBack();

            return [
                'success' => false,
                'message' =>
                    'This booking cannot be changed from its current status.'
            ];
        }

        if ($newStatus === 'Accepted') {
            $dateSQL =
                "SELECT
                    Events.EventDate,
                    COALESCE(
                        Availability.Status,
                        'Not Specified'
                    ) AS AvailabilityStatus

                 FROM Booking

                 INNER JOIN Events
                    ON Booking.EventID =
                       Events.EventID

                 LEFT JOIN Availability
                    ON Availability.VendorID =
                       Booking.VendorID

                   AND Availability.AvailabilityDate =
                       Events.EventDate

                 WHERE Booking.BookingID =
                       :bookingID

                   AND Booking.VendorID =
                       :vendorID

                 LIMIT 1";

            $dateStmt =
                $this->conn->prepare($dateSQL);

            $dateStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );

            $dateStmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );

            $dateStmt->execute();
            $bookingDate =
                $dateStmt->fetch(PDO::FETCH_ASSOC);

            if (
                $bookingDate &&
                in_array(
                    $bookingDate['AvailabilityStatus'],
                    ['Unavailable', 'Booked'],
                    true
                )
            ) {
                $this->conn->rollBack();

                return [
                    'success' => false,
                    'message' =>
                        'This vendor is not available on the event date.'
                ];
            }

            $conflictSQL =
                "SELECT Booking.BookingID

                 FROM Booking

                 INNER JOIN Events
                    ON Booking.EventID =
                       Events.EventID

                 WHERE Booking.VendorID =
                       :vendorID

                   AND Events.EventDate =
                       :eventDate

                   AND Booking.Status IN
                       ('Accepted', 'Completed')

                   AND Booking.BookingID <>
                       :bookingID

                 LIMIT 1";

            $conflictStmt =
                $this->conn->prepare($conflictSQL);

            $conflictStmt->bindParam(
                ':vendorID',
                $vendorID,
                PDO::PARAM_INT
            );

            $conflictStmt->bindValue(
                ':eventDate',
                $bookingDate['EventDate']
            );

            $conflictStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );

            $conflictStmt->execute();

            if ($conflictStmt->fetch(PDO::FETCH_ASSOC)) {
                $this->conn->rollBack();

                return [
                    'success' => false,
                    'message' =>
                        'This vendor already has a confirmed booking on the event date.'
                ];
            }
        }


        /*
         * Update booking status
         */

        $updateSQL =
            "UPDATE Booking

             SET Status =
                 :newStatus

             WHERE BookingID =
                   :bookingID

               AND VendorID =
                   :vendorID

             LIMIT 1";


        $updateStmt =
            $this->conn->prepare(
                $updateSQL
            );


        $updateStmt->bindParam(
            ':newStatus',
            $newStatus
        );


        $updateStmt->bindParam(
            ':bookingID',
            $bookingID,
            PDO::PARAM_INT
        );


        $updateStmt->bindParam(
            ':vendorID',
            $vendorID,
            PDO::PARAM_INT
        );


        $updateStmt->execute();


        /*
         * If booking is accepted,
         * mark the event date as Booked.
         */

        if ($newStatus === 'Accepted') {

            $eventSQL =
                "SELECT
                    EventDate

                 FROM Events

                 WHERE EventID =
                       :eventID

                 LIMIT 1";


            $eventStmt =
                $this->conn->prepare(
                    $eventSQL
                );


            $eventStmt->bindParam(
                ':eventID',
                $booking['EventID'],
                PDO::PARAM_INT
            );


            $eventStmt->execute();


            $event =
                $eventStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if ($event) {

                $availabilitySQL =
                    "UPDATE Availability

                     SET Status = 'Booked'

                     WHERE VendorID =
                           :vendorID

                       AND AvailabilityDate =
                           :availabilityDate";


                $availabilityStmt =
                    $this->conn->prepare(
                        $availabilitySQL
                    );


                $availabilityStmt->bindParam(
                    ':vendorID',
                    $vendorID,
                    PDO::PARAM_INT
                );


                $availabilityStmt->bindParam(
                    ':availabilityDate',
                    $event['EventDate']
                );


                $availabilityStmt->execute();
            }
        }


        /*
         * If an accepted booking is cancelled,
         * make the date available again.
         */

        if (
            $newStatus === 'Cancelled' &&
            $currentStatus === 'Accepted'
        ) {

            $eventSQL =
                "SELECT
                    EventDate

                 FROM Events

                 WHERE EventID =
                       :eventID

                 LIMIT 1";


            $eventStmt =
                $this->conn->prepare(
                    $eventSQL
                );


            $eventStmt->bindParam(
                ':eventID',
                $booking['EventID'],
                PDO::PARAM_INT
            );


            $eventStmt->execute();


            $event =
                $eventStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if ($event) {

                $availabilitySQL =
                    "UPDATE Availability

                     SET Status = 'Available'

                     WHERE VendorID =
                           :vendorID

                       AND AvailabilityDate =
                           :availabilityDate";


                $availabilityStmt =
                    $this->conn->prepare(
                        $availabilitySQL
                    );


                $availabilityStmt->bindParam(
                    ':vendorID',
                    $vendorID,
                    PDO::PARAM_INT
                );


                $availabilityStmt->bindParam(
                    ':availabilityDate',
                    $event['EventDate']
                );


                $availabilityStmt->execute();
            }
        }


        /*
         * Confirm transaction
         */

        $this->conn->commit();


        return [
            'success' => true,
            'message' =>
                'Booking status updated successfully.'
        ];


    } catch (PDOException $exception) {

        /*
         * Roll back if something fails.
         */

        if (
            $this->conn->inTransaction()
        ) {

            $this->conn->rollBack();
        }


        return [
            'success' => false,
            'message' =>
                'Failed to update booking status.'
        ];
    }
}
}