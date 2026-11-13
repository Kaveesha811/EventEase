<?php

require_once __DIR__ . '/../models/Vendor.php';


class VendorController
{
    private $vendorModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->vendorModel =
            new Vendor();
    }


    /*
    |--------------------------------------------------------------------------
    | Get All Vendors - Admin
    |--------------------------------------------------------------------------
    */

    public function getAllVendors()
    {
        return $this->vendorModel
            ->getAllVendors();
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vendor Verification Status - Admin
    |--------------------------------------------------------------------------
    */

    public function updateVerificationStatus(
        $vendorID,
        $newStatus
    ) {

        return $this->vendorModel
            ->updateVerificationStatus(
                $vendorID,
                $newStatus
            );
    }
}

?>