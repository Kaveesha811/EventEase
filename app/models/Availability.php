<?php
class Availability {
    public static function getByVendor($vendorId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Availability WHERE vendor_id = ? ORDER BY availability_date");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll();
    }

    public static function add($vendorId, $date, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Availability (vendor_id, availability_date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
        return $stmt->execute([$vendorId, $date, $status, $status]);
    }

    public static function delete($availabilityId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM Availability WHERE availability_id = ?");
        return $stmt->execute([$availabilityId]);
    }
}
?>