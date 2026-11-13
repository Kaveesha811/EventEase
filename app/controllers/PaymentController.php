<?php

require_once __DIR__ .
    '/../models/Payment.php';


class PaymentController
{
    private $paymentModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->paymentModel =
            new Payment();
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

        return $this->paymentModel
            ->createPayment(
                $bookingID,
                $customerID,
                $amount,
                $referenceNo,
                $paymentSlip
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Customer Payments
    |--------------------------------------------------------------------------
    */

    public function getCustomerPayments(
        $customerID
    ) {

        return $this->paymentModel
            ->getCustomerPayments(
                $customerID
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

        return $this->paymentModel
            ->getBookingPayments(
                $bookingID
            );

    }

    /*
|--------------------------------------------------------------------------
| Get All Payments
|--------------------------------------------------------------------------
*/

public function getAllPayments()
{

    return $this->paymentModel
        ->getAllPayments();

}


/*
|--------------------------------------------------------------------------
| Update Payment Status
|--------------------------------------------------------------------------
*/

public function updatePaymentStatus(
    $paymentID,
    $newStatus
) {

    return $this->paymentModel
        ->updatePaymentStatus(
            $paymentID,
            $newStatus
        );

}
}