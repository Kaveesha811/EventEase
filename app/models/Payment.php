<?php
class Payment {
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Payment (booking_id, amount, reference_no, payment_slip) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['booking_id'],
            $data['amount'],
            $data['reference_no'],
            $data['payment_slip']
        ]);
    }

    public static function getById($paymentId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Payment WHERE payment_id = ?");
        $stmt->execute([$paymentId]);
        return $stmt->fetch();
    }

    public static function getByBooking($bookingId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Payment WHERE booking_id = ? ORDER BY payment_date DESC LIMIT 1");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT p.*, b.booking_id, b.total_amount, e.event_name, u.first_name, u.last_name
            FROM Payment p
            JOIN Booking b ON p.booking_id = b.booking_id
            JOIN Events e ON b.event_id = e.event_id
            JOIN Customer c ON e.customer_id = c.customer_id
            JOIN user u ON c.user_id = u.user_id
            ORDER BY p.payment_date DESC
        ");
        return $stmt->fetchAll();
    }

    public static function updateStatus($paymentId, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Payment SET payment_status = ? WHERE payment_id = ?");
        return $stmt->execute([$status, $paymentId]);
    }
}
?>