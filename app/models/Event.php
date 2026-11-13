<?php
class Event {
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Events (customer_id, event_type_id, event_name, event_date, location, guest_count, budget) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['customer_id'],
            $data['event_type_id'],
            $data['event_name'],
            $data['event_date'],
            $data['location'],
            $data['guest_count'],
            $data['budget']
        ]);
        return $db->lastInsertId();
    }

    public static function findById($eventId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT e.*, et.event_type_name FROM Events e JOIN EventType et ON e.event_type_id = et.event_type_id WHERE e.event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetch();
    }

    public static function findByCustomer($customerId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT e.*, et.event_type_name FROM Events e JOIN EventType et ON e.event_type_id = et.event_type_id WHERE e.customer_id = ? ORDER BY e.event_date DESC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public static function update($eventId, $data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Events SET event_name = ?, event_type_id = ?, event_date = ?, location = ?, guest_count = ?, budget = ? WHERE event_id = ?");
        return $stmt->execute([
            $data['event_name'],
            $data['event_type_id'],
            $data['event_date'],
            $data['location'],
            $data['guest_count'],
            $data['budget'],
            $eventId
        ]);
    }

    public static function getAllEventTypes() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM EventType WHERE status = 'active'");
        return $stmt->fetchAll();
    }
}
?>