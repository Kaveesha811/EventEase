<?php
class BookingItem {
    public static function add($bookingId, $serviceId, $quantity, $unitPrice, $subtotal) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO BookingItem (booking_id, service_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$bookingId, $serviceId, $quantity, $unitPrice, $subtotal]);
    }
}
?>