<?php
class Customer {
    public static function create($userId, $address) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Customer (user_id, address) VALUES (?, ?)");
        return $stmt->execute([$userId, $address]);
    }

    public static function findByUserId($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Customer WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
?>