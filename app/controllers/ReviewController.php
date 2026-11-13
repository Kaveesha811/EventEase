<?php

require_once __DIR__ . '/../models/Review.php';

class ReviewController
{
    private $reviewModel;


    public function __construct()
    {
        $this->reviewModel =
            new Review();
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

        return $this->reviewModel
            ->createReview(
                $bookingID,
                $customerID,
                $rating,
                $comment
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Review
    |--------------------------------------------------------------------------
    */

    public function getReviewByBooking(
        $bookingID,
        $customerID
    ) {

        return $this->reviewModel
            ->getReviewByBooking(
                $bookingID,
                $customerID
            );
    }
}

?>