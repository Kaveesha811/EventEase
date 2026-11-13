<?php
class VendorController {
    private function checkVendorAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
            header('Location: ?url=auth/login');
            exit;
        }
        $vendor = Vendor::findByUserId($_SESSION['user_id']);
        if (!$vendor || $vendor['verification_status'] != 'approved') {
            header('Location: ?url=auth/login');
            exit;
        }
        return $vendor;
    }

    public function dashboard() {
        $vendor = $this->checkVendorAuth();
        $servicesCount = count(Service::getByVendor($vendor['vendor_id']));
        $bookingsCount = count(Booking::getByVendor($vendor['vendor_id']));
        $availabilityCount = count(Availability::getByVendor($vendor['vendor_id']));
        $reviewsCount = count(Review::getByVendor($vendor['vendor_id']));
        require_once '../app/views/vendor/dashboard.php';
    }

    // Availability management
    public function availability() {
        $vendor = $this->checkVendorAuth();
        $availabilities = Availability::getByVendor($vendor['vendor_id']);
        require_once '../app/views/vendor/availability.php';
    }

    public function addAvailability() {
        $vendor = $this->checkVendorAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $date = $_POST['date'];
            $status = $_POST['status'];
            if (empty($date) || !in_array($status, ['available','unavailable','booked'])) {
                $_SESSION['error'] = "Invalid availability data.";
                header('Location: ?url=vendor/availability');
                exit;
            }
            Availability::add($vendor['vendor_id'], $date, $status);
            $_SESSION['success'] = "Availability updated.";
            header('Location: ?url=vendor/availability');
            exit;
        }
    }

    public function deleteAvailability($availabilityId = null) {
        $vendor = $this->checkVendorAuth();
        if (!$availabilityId) {
            header('Location: ?url=vendor/availability');
            exit;
        }
        // Verify ownership
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Availability WHERE availability_id = ? AND vendor_id = ?");
        $stmt->execute([$availabilityId, $vendor['vendor_id']]);
        $avail = $stmt->fetch();
        if (!$avail) {
            $_SESSION['error'] = "Availability not found.";
            header('Location: ?url=vendor/availability');
            exit;
        }
        Availability::delete($availabilityId);
        $_SESSION['success'] = "Availability deleted.";
        header('Location: ?url=vendor/availability');
        exit;
    }
}
?>