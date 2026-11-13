<?php
class Vendor {
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Vendor (user_id, business_name, business_description, business_address, facebook_profile) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['business_name'],
            $data['business_description'],
            $data['business_address'],
            $data['facebook_profile']
        ]);
    }

    public static function findByUserId($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Vendor WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public static function getAllPending() {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT v.*, u.first_name, u.last_name, u.email, u.phone FROM Vendor v JOIN user u ON v.user_id = u.user_id WHERE v.verification_status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllApproved() {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT v.*, u.first_name, u.last_name, u.email, u.phone FROM Vendor v JOIN user u ON v.user_id = u.user_id WHERE v.verification_status = 'approved'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateVerification($vendorId, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Vendor SET verification_status = ? WHERE vendor_id = ?");
        return $stmt->execute([$status, $vendorId]);
    }
}
?>