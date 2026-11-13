<?php

require_once __DIR__ . '/../../config/database.php';


class Payment
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
    | Create Payment
    |--------------------------------------------------------------------------
    */

    public function createPayment(
        $bookingID,
        $customerID,
        $amount,
        $referenceNo,
        $paymentSlip = ''
    ) {

        try {

            /*
             * Validate amount
             */

            if ((float) $amount <= 0) {

                return [
                    'success' => false,
                    'message' =>
                        'Payment amount must be greater than zero.'
                ];
            }


            /*
             * Validate reference number
             */

            $referenceNo =
                trim($referenceNo);

            if ($referenceNo === '') {

                return [
                    'success' => false,
                    'message' =>
                        'Payment reference number is required.'
                ];
            }


            /*
             * Check booking belongs
             * to logged-in customer
             */

            $bookingSQL =
                "SELECT
                    Booking.BookingID,
                    Booking.TotalAmount,
                    Booking.Status,
                    Events.CustomerID

                 FROM Booking

                 INNER JOIN Events
                    ON Booking.EventID =
                       Events.EventID

                 WHERE Booking.BookingID =
                       :bookingID

                   AND Events.CustomerID =
                       :customerID

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
                ':customerID',
                $customerID,
                PDO::PARAM_INT
            );


            $bookingStmt->execute();


            $booking =
                $bookingStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if (!$booking) {

                return [
                    'success' => false,
                    'message' =>
                        'Booking not found.'
                ];
            }


            /*
             * Payment is allowed only for
             * Accepted or Completed bookings
             */

            if (
                $booking['Status'] !== 'Accepted' &&
                $booking['Status'] !== 'Completed'
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'Payment can only be made for accepted or completed bookings.'
                ];
            }

            $paidSQL =
                "SELECT COALESCE(
                    SUM(Amount),
                    0
                ) AS PaidAmount

                 FROM Payment

                 WHERE BookingID =
                       :bookingID

                   AND PaymentStatus =
                       'Completed'";

            $paidStmt =
                $this->conn->prepare($paidSQL);

            $paidStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );

            $paidStmt->execute();

            $paidAmount =
                (float) $paidStmt
                    ->fetchColumn();

            $remainingAmount =
                (float) $booking['TotalAmount'] -
                $paidAmount;

            if ((float) $amount > $remainingAmount) {
                return [
                    'success' => false,
                    'message' =>
                        'Payment amount cannot exceed the remaining balance.'
                ];
            }


            /*
             * Check duplicate reference number
             */

            $referenceSQL =
                "SELECT PaymentID
                 FROM Payment
                 WHERE ReferenceNo =
                       :referenceNo
                 LIMIT 1";


            $referenceStmt =
                $this->conn->prepare(
                    $referenceSQL
                );


            $referenceStmt->bindParam(
                ':referenceNo',
                $referenceNo
            );


            $referenceStmt->execute();


            if ($referenceStmt->fetch()) {

                return [
                    'success' => false,
                    'message' =>
                        'This payment reference number already exists.'
                ];
            }


            /*
             * Insert Payment
             */

            $insertSQL =
                "INSERT INTO Payment
                (
                    BookingID,
                    PaymentDate,
                    Amount,
                    PaymentMethod,
                    PaymentStatus,
                    ReferenceNo,
                    PaymentSlip
                )

                VALUES
                (
                    :bookingID,
                    NOW(),
                    :amount,
                    'Bank Transfer',
                    'Pending',
                    :referenceNo,
                    :paymentSlip
                )";


            $insertStmt =
                $this->conn->prepare(
                    $insertSQL
                );


            $insertStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );


            $insertStmt->bindParam(
                ':amount',
                $amount
            );


            $insertStmt->bindParam(
                ':referenceNo',
                $referenceNo
            );


            $insertStmt->bindParam(
                ':paymentSlip',
                $paymentSlip
            );


            if ($insertStmt->execute()) {

                return [
                    'success' => true,
                    'message' =>
                        'Payment submitted successfully.'
                ];
            }


            return [
                'success' => false,
                'message' =>
                    'Failed to submit payment.'
            ];


        } catch (PDOException $exception) {

            return [
                'success' => false,
                'message' =>
                    'Failed to submit payment.'
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Get Customer Payments
    |--------------------------------------------------------------------------
    */

    public function getCustomerPayments(
        $customerID
    ) {

        $sql =
            "SELECT

                Payment.PaymentID,
                Payment.BookingID,
                Payment.PaymentDate,
                Payment.Amount,
                Payment.PaymentMethod,
                Payment.PaymentStatus,
                Payment.ReferenceNo,
                Payment.PaymentSlip,

                Booking.TotalAmount,
                Booking.Status AS BookingStatus,

                Events.EventName,
                Events.EventDate,

                Vendor.BusinessName

             FROM Payment

             INNER JOIN Booking
                ON Payment.BookingID =
                   Booking.BookingID

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Vendor
                ON Booking.VendorID =
                   Vendor.VendorID

             WHERE Events.CustomerID =
                   :customerID

             ORDER BY
                Payment.PaymentID DESC";


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
    | Get Booking Payments
    |--------------------------------------------------------------------------
    */

    public function getBookingPayments(
        $bookingID
    ) {

        $sql =
            "SELECT

                PaymentID,
                BookingID,
                PaymentDate,
                Amount,
                PaymentMethod,
                PaymentStatus,
                ReferenceNo,
                PaymentSlip

             FROM Payment

             WHERE BookingID =
                   :bookingID

             ORDER BY
                PaymentID DESC";


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
    | Get All Payments - Admin
    |--------------------------------------------------------------------------
    */

    public function getAllPayments()
    {

        $sql =
            "SELECT

                Payment.PaymentID,
                Payment.BookingID,
                Payment.PaymentDate,
                Payment.Amount,
                Payment.PaymentMethod,
                Payment.PaymentStatus,
                Payment.ReferenceNo,
                Payment.PaymentSlip,

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

                Vendor.BusinessName

             FROM Payment

             INNER JOIN Booking
                ON Payment.BookingID =
                   Booking.BookingID

             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID

             INNER JOIN Customer
                ON Events.CustomerID =
                   Customer.CustomerID

             INNER JOIN Users
                ON Customer.UserID =
                   Users.UserID

             INNER JOIN Vendor
                ON Booking.VendorID =
                   Vendor.VendorID

             ORDER BY
                Payment.PaymentID DESC";


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
    | Update Payment Status - Admin
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        $paymentID,
        $newStatus
    ) {

        try {

            /*
             * Allowed payment statuses
             */

            $allowedStatuses = [
                'Pending',
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
                        'Invalid payment status.'
                ];
            }


            /*
             * Check payment exists
             */

            $checkSQL =
                "SELECT
                    PaymentID,
                    PaymentStatus

                 FROM Payment

                 WHERE PaymentID =
                       :paymentID

                 LIMIT 1";


            $checkStmt =
                $this->conn->prepare(
                    $checkSQL
                );


            $checkStmt->bindParam(
                ':paymentID',
                $paymentID,
                PDO::PARAM_INT
            );


            $checkStmt->execute();


            $payment =
                $checkStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if (!$payment) {

                return [
                    'success' => false,
                    'message' =>
                        'Payment not found.'
                ];
            }


            /*
             * Update payment status
             */

            $updateSQL =
                "UPDATE Payment

                 SET PaymentStatus =
                     :newStatus

                 WHERE PaymentID =
                       :paymentID";


            $updateStmt =
                $this->conn->prepare(
                    $updateSQL
                );


            $updateStmt->bindParam(
                ':newStatus',
                $newStatus
            );


            $updateStmt->bindParam(
                ':paymentID',
                $paymentID,
                PDO::PARAM_INT
            );


            $updateStmt->execute();


            return [
                'success' => true,
                'message' =>
                    'Payment status updated successfully.'
            ];


        } catch (PDOException $exception) {

            return [
                'success' => false,
                'message' =>
                    'Failed to update payment status.'
            ];
        }
    }
}

?>