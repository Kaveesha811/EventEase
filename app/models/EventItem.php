<?php
class EventItem {
    public static function add($eventId, $serviceId, $quantity) {
        $db = Database::getConnection();
        $service = Service::getById($serviceId);
        $price = $service['price'];
        $estimated = $price * $quantity;
        $stmt = $db->prepare("INSERT INTO EventItem (event_id, service_id, quantity, estimated_price) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = ?, estimated_price = ?");
        return $stmt->execute([$eventId, $serviceId, $quantity, $estimated, $quantity, $estimated]);
    }

    public static function remove($eventItemId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM EventItem WHERE event_item_id = ?");
        return $stmt->execute([$eventItemId]);
    }

    public static function getByEvent($eventId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT ei.*, s.service_name, s.price, v.business_name, v.vendor_id, sc.category_name
            FROM EventItem ei
            JOIN Service s ON ei.service_id = s.service_id
            JOIN Vendor v ON s.vendor_id = v.vendor_id
            JOIN ServiceCategory sc ON s.service_category_id = sc.service_category_id
            WHERE ei.event_id = ?
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public static function getDistinctVendors($eventId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT DISTINCT v.vendor_id, v.business_name
            FROM EventItem ei
            JOIN Service s ON ei.service_id = s.service_id
            JOIN Vendor v ON s.vendor_id = v.vendor_id
            WHERE ei.event_id = ?
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }
}
?>