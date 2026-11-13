<?php
class Service {
    public static function getByVendor($vendorId) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT s.*, sc.category_name
             FROM Service s
             LEFT JOIN ServiceCategory sc ON s.service_category_id = sc.service_category_id
             WHERE s.vendor_id = ? AND (LOWER(s.status) = 'active' OR s.status IS NULL)"
        );
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll();
    }

    public static function getById($serviceId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT s.*, v.business_name, sc.category_name FROM Service s JOIN Vendor v ON s.vendor_id = v.vendor_id JOIN ServiceCategory sc ON s.service_category_id = sc.service_category_id WHERE s.service_id = ?");
        $stmt->execute([$serviceId]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Service (vendor_id, service_category_id, service_name, description, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['vendor_id'],
            $data['service_category_id'],
            $data['service_name'],
            $data['description'],
            $data['price']
        ]);
        return $db->lastInsertId();
    }

    public static function update($serviceId, $data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Service SET service_category_id = ?, service_name = ?, description = ?, price = ? WHERE service_id = ?");
        return $stmt->execute([
            $data['service_category_id'],
            $data['service_name'],
            $data['description'],
            $data['price'],
            $serviceId
        ]);
    }

    public static function deactivate($serviceId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Service SET status = 'inactive' WHERE service_id = ?");
        return $stmt->execute([$serviceId]);
    }

    public static function getCategories() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ServiceCategory WHERE status = 'active'");
        return $stmt->fetchAll();
    }

    public static function getAvailableById($serviceId) {
    $db = Database::getConnection();

    $stmt = $db->prepare("
        SELECT s.*, 
               v.business_name, 
               v.business_address, 
               v.facebook_profile, 
               sc.category_name
        FROM Service s
        JOIN Vendor v 
            ON s.vendor_id = v.vendor_id
        JOIN ServiceCategory sc 
            ON s.service_category_id = sc.service_category_id
        WHERE s.service_id = ?
          AND s.status = 'active'
          AND v.verification_status = 'approved'
    ");

    $stmt->execute([$serviceId]);

    return $stmt->fetch();
}

    public static function searchByCategory($categoryId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT s.*, v.business_name, v.business_address, v.facebook_profile, sc.category_name
            FROM Service s
            JOIN Vendor v ON s.vendor_id = v.vendor_id
            JOIN ServiceCategory sc ON s.service_category_id = sc.service_category_id
            WHERE s.service_category_id = ? AND s.status = 'active' AND v.verification_status = 'approved'
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public static function getAllActive() {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT s.*, v.business_name, v.business_address, v.facebook_profile, sc.category_name
            FROM Service s
            JOIN Vendor v ON s.vendor_id = v.vendor_id
            JOIN ServiceCategory sc ON s.service_category_id = sc.service_category_id
            WHERE s.status = 'active' AND v.verification_status = 'approved'
        ");
        return $stmt->fetchAll();
    }
}
?>