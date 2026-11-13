<?php
class AdminController {
    private function checkAdminAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: ?url=auth/login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdminAuth();
        // Get statistics
        $db = Database::getConnection();
        $totalCustomers = $db->query("SELECT COUNT(*) FROM Customer")->fetchColumn();
        $totalVendors = $db->query("SELECT COUNT(*) FROM Vendor")->fetchColumn();
        $pendingVendors = $db->query("SELECT COUNT(*) FROM Vendor WHERE verification_status = 'pending'")->fetchColumn();
        $pendingPayments = $db->query("SELECT COUNT(*) FROM Payment WHERE payment_status = 'pending'")->fetchColumn();
        $totalBookings = $db->query("SELECT COUNT(*) FROM Booking")->fetchColumn();
        require_once '../app/views/admin/dashboard.php';
    }

    // Vendor management
    public function vendors() {
        $this->checkAdminAuth();
        $pendingVendors = Vendor::getAllPending();
        $approvedVendors = Vendor::getAllApproved();
        require_once '../app/views/admin/vendors.php';
    }

    public function approveVendor($vendorId = null) {
        $this->checkAdminAuth();
        if (!$vendorId) {
            header('Location: ?url=admin/vendors');
            exit;
        }
        Vendor::updateVerification($vendorId, 'approved');
        $_SESSION['success'] = "Vendor approved.";
        header('Location: ?url=admin/vendors');
        exit;
    }

    public function rejectVendor($vendorId = null) {
        $this->checkAdminAuth();
        if (!$vendorId) {
            header('Location: ?url=admin/vendors');
            exit;
        }
        Vendor::updateVerification($vendorId, 'rejected');
        $_SESSION['success'] = "Vendor rejected.";
        header('Location: ?url=admin/vendors');
        exit;
    }
}
?>