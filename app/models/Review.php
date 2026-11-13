<?php
class Review {
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Review (booking_id, rating, comment) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['booking_id'],
            $data['rating'],
            $data['comment']
        ]);
    }

    public static function getByBooking($bookingId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Review WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    // Reviews left by customers for a given vendor's bookings
    public static function getByVendor($vendorId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT r.*, b.booking_id, e.event_name, u.first_name, u.last_name
            FROM Review r
            JOIN Booking b ON r.booking_id = b.booking_id
            JOIN Events e ON b.event_id = e.event_id
            JOIN Customer c ON e.customer_id = c.customer_id
            JOIN user u ON c.user_id = u.user_id
            WHERE b.vendor_id = ?
            ORDER BY r.review_date DESC
        ");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll();
    }
}
?>