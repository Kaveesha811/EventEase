<?php

require_once __DIR__ . '/../models/Package.php';

class PackageController
{
    private $packageModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->packageModel = new Package();
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER SIDE
    |--------------------------------------------------------------------------
    */

    public function getActivePackages($eventTypeID = null)
    {
        return $this->packageModel
            ->getActivePackages($eventTypeID);
    }


    public function getPackageDetails($packageID)
    {
        return $this->packageModel
            ->getPackageDetails($packageID);
    }


    public function getPackageServices($packageID)
    {
        return $this->packageModel
            ->getPackageServices($packageID);
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR SIDE
    |--------------------------------------------------------------------------
    */

    public function getVendorPackages($vendorID)
    {
        return $this->packageModel
            ->getVendorPackages($vendorID);
    }


    public function getVendorActiveServices($vendorID)
    {
        return $this->packageModel
            ->getVendorActiveServices($vendorID);
    }


    public function getServicesTotal($vendorID, $serviceIDs)
    {
        return $this->packageModel
            ->getServicesTotal($vendorID, $serviceIDs);
    }


    public function getActiveEventTypes()
    {
        return $this->packageModel
            ->getActiveEventTypes();
    }


    public function getPackageEventTypeIDs($packageID, $vendorID)
    {
        return $this->packageModel
            ->getPackageEventTypeIDs($packageID, $vendorID);
    }


    public function createPackage(
        $vendorID,
        $packageName,
        $description,
        $price,
        $serviceIDs,
        $eventTypeIDs
    ) {
        return $this->packageModel
            ->createPackage(
                $vendorID,
                $packageName,
                $description,
                $price,
                $serviceIDs,
                $eventTypeIDs
            );
    }


    public function getVendorPackage(
        $packageID,
        $vendorID
    ) {
        return $this->packageModel
            ->getVendorPackage(
                $packageID,
                $vendorID
            );
    }


    public function getPackageServiceIDs(
        $packageID,
        $vendorID
    ) {
        return $this->packageModel
            ->getPackageServiceIDs(
                $packageID,
                $vendorID
            );
    }


    public function updatePackage(
        $packageID,
        $vendorID,
        $packageName,
        $description,
        $price,
        $serviceIDs,
        $eventTypeIDs
    ) {
        return $this->packageModel
            ->updatePackage(
                $packageID,
                $vendorID,
                $packageName,
                $description,
                $price,
                $serviceIDs,
                $eventTypeIDs
            );
    }


    public function deactivatePackage(
        $packageID,
        $vendorID
    ) {
        return $this->packageModel
            ->deactivatePackage(
                $packageID,
                $vendorID
            );
    }
}