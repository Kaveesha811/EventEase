<?php
class BookingController {
    // Customer: request booking for event (create bookings per vendor)
    public function requestBooking($eventId = null) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        if (!$eventId) {
            header('Location: ?url=event/dashboard');
            exit;
        }
        $customer = Customer::findByUserId($_SESSION['user_id']);
        $event = Event::findById($eventId);
        if (!$event || $event['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "Invalid event.";
            header('Location: ?url=event/dashboard');
            exit;
        }
        // Get all event items grouped by vendor
        $items = EventItem::getByEvent($eventId);
        if (empty($items)) {
            $_SESSION['error'] = "No services selected for this event.";
            header('Location: ?url=event/view/' . $eventId);
            exit;
        }
        // Group by vendor_id
        $grouped = [];
        foreach ($items as $item) {
            $grouped[$item['vendor_id']][] = $item;
        }
        // Create a booking for each vendor
        foreach ($grouped as $vendorId => $vendorItems) {
            $total = 0;
            foreach ($vendorItems as $vi) {
                $total += $vi['estimated_price'];
            }
            $bookingId = Booking::create($eventId, $vendorId, $total, 'Booking requested by customer');
            // Insert booking items
            foreach ($vendorItems as $vi) {
                BookingItem::add($bookingId, $vi['service_id'], $vi['quantity'], $vi['price'], $vi['estimated_price']);
            }
        }
        $_SESSION['success'] = "Booking request submitted successfully.";
        header('Location: ?url=event/view/' . $eventId);
        exit;
    }

    // Customer: view all their bookings
    public function myBookings() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        $customer = Customer::findByUserId($_SESSION['user_id']);
        $bookings = Booking::getByCustomer($customer['customer_id']);
        require_once '../app/views/customer/my_bookings.php';
    }

    // Vendor: view bookings for them
    public function vendorBookings() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
            header('Location: ?url=auth/login');
            exit;
        }
        $vendor = Vendor::findByUserId($_SESSION['user_id']);
        if (!$vendor || $vendor['verification_status'] != 'approved') {
            header('Location: ?url=auth/login');
            exit;
        }
        $bookings = Booking::getByVendor($vendor['vendor_id']);
        require_once '../app/views/vendor/bookings.php';
    }

    // Vendor: update booking status (accept, reject, complete, cancel)
    public function updateStatus($bookingId = null, $newStatus = null) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
            header('Location: ?url=auth/login');
            exit;
        }
        if (!$bookingId || !$newStatus || !in_array($newStatus, ['accepted','rejected','completed','cancelled'])) {
            header('Location: ?url=booking/vendorBookings');
            exit;
        }
        $vendor = Vendor::findByUserId($_SESSION['user_id']);
        $booking = Booking::findById($bookingId);
        if (!$booking || $booking['vendor_id'] != $vendor['vendor_id']) {
            $_SESSION['error'] = "You are not authorised to modify this booking.";
            header('Location: ?url=booking/vendorBookings');
            exit;
        }
        Booking::updateStatus($bookingId, $newStatus);
        $_SESSION['success'] = "Booking status updated to " . ucfirst($newStatus) . ".";
        header('Location: ?url=booking/vendorBookings');
        exit;
    }
}
?>