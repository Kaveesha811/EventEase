<?php

require_once __DIR__ .
    '/../models/Booking.php';


class BookingController
{
    private $bookingModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->bookingModel =
            new Booking();
    }


    /*
    |--------------------------------------------------------------------------
    | Create Booking Requests
    |--------------------------------------------------------------------------
    */

    public function createBookingsForEvent(
        $eventID,
        $customerID,
        $notes = ''
    ) {
        return $this->bookingModel
            ->createBookingsForEvent(
                $eventID,
                $customerID,
                $notes
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Bookings
    |--------------------------------------------------------------------------
    */

    public function getVendorBookings(
        $vendorID
    ) {
        return $this->bookingModel
            ->getVendorBookings(
                $vendorID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Transaction History
    |--------------------------------------------------------------------------
    */

    public function getVendorTransactionHistory(
        $vendorID
    ) {
        return $this->bookingModel
            ->getVendorTransactionHistory(
                $vendorID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Booking Status
    |--------------------------------------------------------------------------
    */

    public function updateBookingStatus(
        $bookingID,
        $vendorID,
        $newStatus
    ) {

        return $this->bookingModel
            ->updateBookingStatus(
                $bookingID,
                $vendorID,
                $newStatus
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
        return $this->bookingModel
            ->getCustomerBookings(
                $customerID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Booking Details
    |--------------------------------------------------------------------------
    */

    public function getBookingDetails(
        $bookingID,
        $customerID
    ) {
        return $this->bookingModel
            ->getBookingDetails(
                $bookingID,
                $customerID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Booking Items
    |--------------------------------------------------------------------------
    */

    public function getBookingItems(
        $bookingID
    ) {
        return $this->bookingModel
            ->getBookingItems(
                $bookingID
            );
    }
}
