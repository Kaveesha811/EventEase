<?php

require_once __DIR__ .
    '/../models/Availability.php';


class AvailabilityController
{
    private $availabilityModel;


    public function __construct()
    {
        $this->availabilityModel =
            new Availability();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendor Availability
    |--------------------------------------------------------------------------
    */

    public function getVendorAvailability(
        $vendorID
    ) {
        return $this->availabilityModel
            ->getVendorAvailability(
                $vendorID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Availability
    |--------------------------------------------------------------------------
    */

    public function addAvailability(
        $vendorID,
        $availabilityDate,
        $status
    ) {
        return $this->availabilityModel
            ->addAvailability(
                $vendorID,
                $availabilityDate,
                $status
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Availability
    |--------------------------------------------------------------------------
    */

    public function getVendorAvailabilityByID(
        $availabilityID,
        $vendorID
    ) {
        return $this->availabilityModel
            ->getVendorAvailabilityByID(
                $availabilityID,
                $vendorID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Availability
    |--------------------------------------------------------------------------
    */

    public function updateAvailability(
        $availabilityID,
        $vendorID,
        $availabilityDate,
        $status
    ) {
        return $this->availabilityModel
            ->updateAvailability(
                $availabilityID,
                $vendorID,
                $availabilityDate,
                $status
            );
    }
}