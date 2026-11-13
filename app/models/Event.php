<?php

require_once __DIR__ . '/../../config/database.php';

class Event
{
    private $conn;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct($conn = null)
    {
        if ($conn) {
            $this->conn = $conn;
        } else {
            $database = new Database();
            $this->conn = $database->connect();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Get Event Types
    |--------------------------------------------------------------------------
    */

    public function getEventTypes()
    {
        $sql = "SELECT
                    EventTypeID,
                    EventTypeName,
                    Description
                FROM EventType
                WHERE Status = 'Active'
                ORDER BY EventTypeName ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Event
    |--------------------------------------------------------------------------
    */

    public function createEvent(
        $customerID,
        $eventTypeID,
        $eventName,
        $eventDate,
        $location,
        $guestCount,
        $budget
    ) {
        $sql = "INSERT INTO Events
                (
                    CustomerID,
                    EventTypeID,
                    EventName,
                    EventDate,
                    Location,
                    GuestCount,
                    Budget,
                    Status,
                    CreatedAt
                )
                VALUES
                (
                    :customerID,
                    :eventTypeID,
                    :eventName,
                    :eventDate,
                    :location,
                    :guestCount,
                    :budget,
                    'Active',
                    NOW()
                )";

        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ':customerID',
            $customerID,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':eventTypeID',
            $eventTypeID,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':eventName',
            $eventName
        );

        $stmt->bindParam(
            ':eventDate',
            $eventDate
        );

        $stmt->bindParam(
            ':location',
            $location
        );

        $stmt->bindParam(
            ':guestCount',
            $guestCount,
            PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':budget',
            $budget
        );

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Customer Event
    |--------------------------------------------------------------------------
    */

    public function updateEvent(
        $eventID,
        $customerID,
        $eventTypeID,
        $eventName,
        $eventDate,
        $location,
        $guestCount,
        $budget
    ) {
        $sql = "UPDATE Events
                SET EventTypeID = :eventTypeID,
                    EventName = :eventName,
                    EventDate = :eventDate,
                    Location = :location,
                    GuestCount = :guestCount,
                    Budget = :budget
                WHERE EventID = :eventID
                  AND CustomerID = :customerID";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':eventTypeID' => $eventTypeID,
            ':eventName' => $eventName,
            ':eventDate' => $eventDate,
            ':location' => $location,
            ':guestCount' => $guestCount,
            ':budget' => $budget,
            ':eventID' => $eventID,
            ':customerID' => $customerID
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Customer Events
    |--------------------------------------------------------------------------
    */

    public function getCustomerEvents(
        $customerID
    ) {
        $sql = "SELECT
                    Events.EventID,
                    Events.CustomerID,
                    Events.EventTypeID,
                    Events.EventName,
                    Events.EventDate,
                    Events.Location,
                    Events.GuestCount,
                    Events.Budget,
                    Events.Status,
                    Events.CreatedAt,
                    EventType.EventTypeName
                FROM Events
                INNER JOIN EventType
                    ON Events.EventTypeID =
                       EventType.EventTypeID
                WHERE Events.CustomerID =
                      :customerID
                ORDER BY Events.EventDate ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(
            ':customerID',
            $customerID,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Customer Event
    |--------------------------------------------------------------------------
    */

    public function getEvent(
        $eventID,
        $customerID
    ) {
        $sql = "SELECT
                    Events.EventID,
                    Events.CustomerID,
                    Events.EventTypeID,
                    Events.EventName,
                    Events.EventDate,
                    Events.Location,
                    Events.GuestCount,
                    Events.Budget,
                    Events.Status,
                    Events.CreatedAt,
                    EventType.EventTypeName,
                    EventType.Description
                        AS EventTypeDescription
                FROM Events
                INNER JOIN EventType
                    ON Events.EventTypeID =
                       EventType.EventTypeID
                WHERE Events.EventID =
                      :eventID
                  AND Events.CustomerID =
                      :customerID
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(
            ':eventID',
            $eventID,
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


    /*
    |--------------------------------------------------------------------------
    | Get Single Customer Event - Backward Compatibility
    |--------------------------------------------------------------------------
    */

    public function getEventByID(
        $eventID,
        $customerID
    ) {
        return $this->getEvent(
            $eventID,
            $customerID
        );
    }
}