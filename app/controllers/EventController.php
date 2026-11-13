<?php

require_once __DIR__ . '/../models/Event.php';

class EventController
{
    private $eventModel;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->eventModel =
            new Event();
    }


    /*
    |--------------------------------------------------------------------------
    | Show Create Event Form
    |--------------------------------------------------------------------------
    */

    public function showCreateForm()
    {
        return $this->eventModel
            ->getEventTypes();
    }


    /*
    |--------------------------------------------------------------------------
    | Create Event
    |--------------------------------------------------------------------------
    */

    public function create(
        $customerID,
        $eventTypeID,
        $eventName,
        $eventDate,
        $location,
        $guestCount,
        $budget
    ) {
        return $this->eventModel
            ->createEvent(
                $customerID,
                $eventTypeID,
                $eventName,
                $eventDate,
                $location,
                $guestCount,
                $budget
            );
    }


    public function update(
        $eventID,
        $customerID,
        $eventTypeID,
        $eventName,
        $eventDate,
        $location,
        $guestCount,
        $budget
    ) {
        return $this->eventModel->updateEvent(
            $eventID,
            $customerID,
            $eventTypeID,
            $eventName,
            $eventDate,
            $location,
            $guestCount,
            $budget
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Customer Events
    |--------------------------------------------------------------------------
    */

    public function getCustomerEvents(
        $customerID
    ) {
        return $this->eventModel
            ->getCustomerEvents(
                $customerID
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Event
    |--------------------------------------------------------------------------
    */

    public function getEvent(
        $eventID,
        $customerID
    ) {
        return $this->eventModel
            ->getEvent(
                $eventID,
                $customerID
            );
    }
}