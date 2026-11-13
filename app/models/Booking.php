<?php
class Booking {
    public static function create($eventId, $vendorId, $totalAmount, $notes = null) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Booking (event_id, vendor_id, total_amount, notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$eventId, $vendorId, $totalAmount, $notes]);
        return $db->lastInsertId();
    }

    public static function findById($bookingId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT b.*, e.event_name, e.event_date, e.location, c.customer_id, u.first_name, u.last_name, u.email, u.phone
            FROM Booking b
            JOIN Events e ON b.event_id = e.event_id
            JOIN Customer c ON e.customer_id = c.customer_id
            JOIN user u ON c.user_id = u.user_id
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public static function getByVendor($vendorId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT b.*, e.event_name, e.event_date, u.first_name, u.last_name
            FROM Booking b
            JOIN Events e ON b.event_id = e.event_id
            JOIN Customer c ON e.customer_id = c.customer_id
            JOIN user u ON c.user_id = u.user_id
            WHERE b.vendor_id = ?
            ORDER BY b.booking_date DESC
        ");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll();
    }

    public static function getByCustomer($customerId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT b.*, v.business_name, e.event_name
            FROM Booking b
            JOIN Events e ON b.event_id = e.event_id
            JOIN Vendor v ON b.vendor_id = v.vendor_id
            WHERE e.customer_id = ?
            ORDER BY b.booking_date DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($bookingId, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Booking SET status = ? WHERE booking_id = ?");
        return $stmt->execute([$status, $bookingId]);
    }

    public static function getItems($bookingId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT bi.*, s.service_name
            FROM BookingItem bi
            JOIN Service s ON bi.service_id = s.service_id
            WHERE bi.booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll();
    }
}
?>