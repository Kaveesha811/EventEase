<?php
class User {
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM user WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO user (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['phone'],
            $data['role']
        ]);
        return $db->lastInsertId();
    }

    public static function updatePassword($userId, $newPassword) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        return $stmt->execute([$newPassword, $userId]);
    }
}
?>