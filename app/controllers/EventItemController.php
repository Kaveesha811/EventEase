<?php

require_once __DIR__ . '/../models/EventItem.php';

class EventItemController
{
    private $eventItemModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->eventItemModel =
            new EventItem();
    }


    /*
    |--------------------------------------------------------------------------
    | Add Service to Event
    |--------------------------------------------------------------------------
    */

    public function addServiceToEvent(
        $eventID,
        $serviceID,
        $eventDate
    ) {
        return $this->eventItemModel
            ->addServiceToEvent(
                $eventID,
                $serviceID,
                $eventDate
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Package to Event
    |--------------------------------------------------------------------------
    */

    public function addPackageToEvent(
        $eventID,
        $packageID,
        $eventDate
    ) {
        return $this->eventItemModel
            ->addPackageToEvent(
                $eventID,
                $packageID,
                $eventDate
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Event Item
    |--------------------------------------------------------------------------
    */

    public function removeEventItem(
        $eventItemID,
        $eventID
    ) {
        return $this->eventItemModel
            ->removeEventItem(
                $eventItemID,
                $eventID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Event Items
    |--------------------------------------------------------------------------
    */

    public function getEventItems(
        $eventID
    ) {
        return $this->eventItemModel
            ->getEventItems(
                $eventID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Event Estimated Total
    |--------------------------------------------------------------------------
    */

    public function getEventTotal(
        $eventID
    ) {
        return $this->eventItemModel
            ->getEventTotal(
                $eventID
            );
    }
}