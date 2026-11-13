<?php

require_once __DIR__ . '/../../config/database.php';

class VendorTransaction
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getVendorTransactions($vendorID)
    {
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

                Customer.CustomerID,

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

                GROUP_CONCAT(
                    DISTINCT Payment.PaymentMethod
                    SEPARATOR ', '
                ) AS PaymentMethods,

                MAX(Payment.PaymentDate) AS LastPaymentDate,

                CASE
                    WHEN COUNT(Payment.PaymentID) = 0
                    THEN 'Pending'

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
                ON Booking.EventID = Events.EventID

             INNER JOIN Customer
                ON Events.CustomerID = Customer.CustomerID

             INNER JOIN Users
                ON Customer.UserID = Users.UserID

             LEFT JOIN Payment
                ON Booking.BookingID = Payment.BookingID

             WHERE Booking.VendorID = :vendorID

             GROUP BY
                Booking.BookingID,
                Booking.BookingDate,
                Booking.TotalAmount,
                Booking.Status,
                Events.EventID,
                Events.EventName,
                Events.EventDate,
                Events.Location,
                Customer.CustomerID,
                Users.FirstName,
                Users.LastName,
                Users.Email,
                Users.Phone

             ORDER BY Booking.BookingID DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vendorID', $vendorID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
