<?php

require_once __DIR__ . '/../models/Service.php';

class ServiceController
{
    private $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new Service();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Service Categories
    |--------------------------------------------------------------------------
    */

    public function getServiceCategories($eventTypeID = null)
    {
        return $this->serviceModel
            ->getServiceCategories($eventTypeID);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vendors by Category
    |--------------------------------------------------------------------------
    */

    public function getVendorsByCategory(
        $serviceCategoryID,
        $eventDate
    ) {
        return $this->serviceModel
            ->getVendorsByCategory(
                $serviceCategoryID,
                $eventDate
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Service Details
    |--------------------------------------------------------------------------
    */

    public function getServiceDetails(
        $serviceID,
        $eventDate
    ) {
        return $this->serviceModel
            ->getServiceDetails(
                $serviceID,
                $eventDate
            );
    }

        /*
    |--------------------------------------------------------------------------
    | Get Vendor Services
    |--------------------------------------------------------------------------
    */

    public function getVendorServices($vendorID)
    {
        return $this->serviceModel
            ->getVendorServices($vendorID);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Active Service Categories
    |--------------------------------------------------------------------------
    */

    public function getActiveServiceCategories()
    {
        return $this->serviceModel
            ->getActiveServiceCategories();
    }


    /*
    |--------------------------------------------------------------------------
    | Create Vendor Service
    |--------------------------------------------------------------------------
    */

    public function createService(
        $vendorID,
        $serviceCategoryID,
        $serviceName,
        $description,
        $price
    ) {
        return $this->serviceModel
            ->createService(
                $vendorID,
                $serviceCategoryID,
                $serviceName,
                $description,
                $price
            );
    }

        /*
    |--------------------------------------------------------------------------
    | Get Single Vendor Service
    |--------------------------------------------------------------------------
    */

    public function getVendorService(
        $serviceID,
        $vendorID
    ) {
        return $this->serviceModel
            ->getVendorService(
                $serviceID,
                $vendorID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Deactivate Vendor Service
    |--------------------------------------------------------------------------
    */

    public function deactivateService(
        $serviceID,
        $vendorID
    ) {
        return $this->serviceModel
            ->deactivateService(
                $serviceID,
                $vendorID
            );
    }

        /*
    |--------------------------------------------------------------------------
    | Update Vendor Service
    |--------------------------------------------------------------------------
    */

    public function updateService(
        $serviceID,
        $vendorID,
        $serviceCategoryID,
        $serviceName,
        $description,
        $price
    ) {
        return $this->serviceModel
            ->updateService(
                $serviceID,
                $vendorID,
                $serviceCategoryID,
                $serviceName,
                $description,
                $price
            );
    }
}