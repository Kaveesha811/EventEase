<?php

require_once __DIR__ . '/../../config/database.php';

class Review
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
    | Create Review
    |--------------------------------------------------------------------------
    */

    public function createReview(
        $bookingID,
        $customerID,
        $rating,
        $comment
    ) {

        try {

            /*
             * Validate rating
             */

            $rating =
                (int) $rating;

            if (
                $rating < 1 ||
                $rating > 5
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'Rating must be between 1 and 5.'
                ];
            }


            /*
             * Check booking ownership
             * and completion status
             */

            $checkSQL =
                "SELECT
                    Booking.BookingID,
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

            $checkStmt =
                $this->conn->prepare(
                    $checkSQL
                );

            $checkStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );

            $checkStmt->bindParam(
                ':customerID',
                $customerID,
                PDO::PARAM_INT
            );

            $checkStmt->execute();

            $booking =
                $checkStmt->fetch(
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
             * Only completed bookings
             * can be reviewed.
             */

            if (
                $booking['Status'] !== 'Completed'
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'Only completed bookings can be reviewed.'
                ];
            }


            /*
             * Check whether review
             * already exists.
             */

            $existingSQL =
                "SELECT ReviewID
                 FROM Review
                 WHERE BookingID =
                       :bookingID
                 LIMIT 1";

            $existingStmt =
                $this->conn->prepare(
                    $existingSQL
                );

            $existingStmt->bindParam(
                ':bookingID',
                $bookingID,
                PDO::PARAM_INT
            );

            $existingStmt->execute();

            $existingReview =
                $existingStmt->fetch(
                    PDO::FETCH_ASSOC
                );


            if ($existingReview) {

                return [
                    'success' => false,
                    'message' =>
                        'You have already reviewed this booking.'
                ];
            }


            /*
             * Insert review
             */

            $insertSQL =
                "INSERT INTO Review
                (
                    BookingID,
                    Rating,
                    Comment,
                    ReviewDate
                )
                VALUES
                (
                    :bookingID,
                    :rating,
                    :comment,
                    NOW()
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
                ':rating',
                $rating,
                PDO::PARAM_INT
            );

            $insertStmt->bindParam(
                ':comment',
                $comment
            );

            $insertStmt->execute();


            return [
                'success' => true,
                'message' =>
                    'Review submitted successfully.'
            ];


        } catch (PDOException $exception) {

            return [
                'success' => false,
                'message' =>
                    'Failed to submit review.'
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Get Review For Booking
    |--------------------------------------------------------------------------
    */

    public function getReviewByBooking(
        $bookingID,
        $customerID
    ) {

        $sql =
            "SELECT
                Review.ReviewID,
                Review.BookingID,
                Review.Rating,
                Review.Comment,
                Review.ReviewDate,
                Vendor.BusinessName,
                Events.EventName,
                Events.EventDate
             FROM Review
             INNER JOIN Booking
                ON Review.BookingID =
                   Booking.BookingID
             INNER JOIN Events
                ON Booking.EventID =
                   Events.EventID
             INNER JOIN Vendor
                ON Booking.VendorID =
                   Vendor.VendorID
             WHERE Review.BookingID =
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
}

?>