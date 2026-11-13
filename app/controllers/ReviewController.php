<?php
class ReviewController {
    // Customer: show review form for a completed booking
    public function create($bookingId = null) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        if (!$bookingId) {
            header('Location: ?url=booking/myBookings');
            exit;
        }
        $customer = Customer::findByUserId($_SESSION['user_id']);
        $booking = Booking::findById($bookingId);
        if (!$booking || $booking['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "Invalid booking.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
        if ($booking['status'] != 'completed') {
            $_SESSION['error'] = "You can only review completed bookings.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
        $existing = Review::getByBooking($bookingId);
        if ($existing) {
            $_SESSION['error'] = "You have already reviewed this booking.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
        require_once '../app/views/customer/write_review.php';
    }

    // Customer: submit review
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bookingId = $_POST['booking_id'];
            $rating = intval($_POST['rating']);
            $comment = trim($_POST['comment']);
            if ($rating < 1 || $rating > 5) {
                $_SESSION['error'] = "Rating must be between 1 and 5.";
                header('Location: ?url=review/create/' . $bookingId);
                exit;
            }
            $customer = Customer::findByUserId($_SESSION['user_id']);
            $booking = Booking::findById($bookingId);
            if (!$booking || $booking['customer_id'] != $customer['customer_id']) {
                $_SESSION['error'] = "Invalid booking.";
                header('Location: ?url=booking/myBookings');
                exit;
            }
            Review::create([
                'booking_id' => $bookingId,
                'rating' => $rating,
                'comment' => $comment
            ]);
            $_SESSION['success'] = "Review submitted. Thank you!";
            header('Location: ?url=booking/myBookings');
            exit;
        }
    }

    // Vendor: view reviews customers have left for their bookings
    public function vendorReviews() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
            header('Location: ?url=auth/login');
            exit;
        }
        $vendor = Vendor::findByUserId($_SESSION['user_id']);
        if (!$vendor) {
            header('Location: ?url=auth/login');
            exit;
        }
        $reviews = Review::getByVendor($vendor['vendor_id']);
        require_once '../app/views/vendor/reviews.php';
    }
}
?>