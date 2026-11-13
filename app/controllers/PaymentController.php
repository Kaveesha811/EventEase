<?php
class PaymentController {
    // Customer: show payment form for a booking
    public function create($bookingId = null) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        if (!$bookingId) {
            header('Location: ?url=booking/myBookings');
            exit;
        }
        // Verify booking belongs to customer
        $customer = Customer::findByUserId($_SESSION['user_id']);
        $booking = Booking::findById($bookingId);
        if (!$booking || $booking['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "Invalid booking.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
        // Check if already paid
        $payment = Payment::getByBooking($bookingId);
        if ($payment && $payment['payment_status'] == 'completed') {
            $_SESSION['error'] = "This booking is already paid.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
        require_once '../app/views/customer/make_payment.php';
    }

    // Customer: submit payment details (POST)
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bookingId = $_POST['booking_id'];
            $amount = $_POST['amount'];
            $referenceNo = trim($_POST['reference_no']);
            $customer = Customer::findByUserId($_SESSION['user_id']);
            $booking = Booking::findById($bookingId);
            if (!$booking || $booking['customer_id'] != $customer['customer_id']) {
                $_SESSION['error'] = "Invalid booking.";
                header('Location: ?url=booking/myBookings');
                exit;
            }
            // Validate amount matches booking total
            if ($amount != $booking['total_amount']) {
                $_SESSION['error'] = "Payment amount does not match booking total.";
                header('Location: ?url=payment/create/' . $bookingId);
                exit;
            }
            // Handle file upload
            $slipPath = null;
            if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
                $allowed = ['jpg','jpeg','png','pdf'];
                $ext = pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($ext), $allowed)) {
                    $_SESSION['error'] = "Invalid file type. Allowed: jpg, jpeg, png, pdf.";
                    header('Location: ?url=payment/create/' . $bookingId);
                    exit;
                }
                if ($_FILES['payment_slip']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['error'] = "File size must be less than 2MB.";
                    header('Location: ?url=payment/create/' . $bookingId);
                    exit;
                }
                $newName = uniqid('slip_') . '.' . $ext;
                $uploadDir = '../public/uploads/payment_slips/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $uploadDir . $newName)) {
                    $slipPath = 'uploads/payment_slips/' . $newName;
                } else {
                    $_SESSION['error'] = "Failed to upload payment slip.";
                    header('Location: ?url=payment/create/' . $bookingId);
                    exit;
                }
            } else {
                $_SESSION['error'] = "Payment slip is required.";
                header('Location: ?url=payment/create/' . $bookingId);
                exit;
            }
            // Insert payment record
            $data = [
                'booking_id' => $bookingId,
                'amount' => $amount,
                'reference_no' => $referenceNo,
                'payment_slip' => $slipPath
            ];
            Payment::create($data);
            $_SESSION['success'] = "Payment submitted successfully. Awaiting admin verification.";
            header('Location: ?url=booking/myBookings');
            exit;
        }
    }

    // Customer: view payment history
    public function myPayments() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
        $customer = Customer::findByUserId($_SESSION['user_id']);
        // Get all bookings for customer, then their payments
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, b.booking_id, b.total_amount, e.event_name
            FROM Payment p
            JOIN Booking b ON p.booking_id = b.booking_id
            JOIN Events e ON b.event_id = e.event_id
            WHERE e.customer_id = ?
            ORDER BY p.payment_date DESC
        ");
        $stmt->execute([$customer['customer_id']]);
        $payments = $stmt->fetchAll();
        require_once '../app/views/customer/my_payments.php';
    }

    // Admin: view all payments
    public function adminPayments() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: ?url=auth/login');
            exit;
        }
        $payments = Payment::getAll();
        require_once '../app/views/admin/payments.php';
    }

    // Admin: update payment status
    public function updateStatus($paymentId = null, $status = null) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: ?url=auth/login');
            exit;
        }
        if (!$paymentId || !in_array($status, ['pending','completed'])) {
            header('Location: ?url=payment/adminPayments');
            exit;
        }
        $payment = Payment::getById($paymentId);
        if (!$payment) {
            $_SESSION['error'] = "Payment not found.";
            header('Location: ?url=payment/adminPayments');
            exit;
        }
        Payment::updateStatus($paymentId, $status);
        // When the admin confirms a payment, the booking itself is now
        // fulfilled: mark it completed so the customer's account reflects
        // that and the review action becomes available to them.
        if ($status == 'completed') {
            Booking::updateStatus($payment['booking_id'], 'completed');
        }
        $_SESSION['success'] = "Payment status updated.";
        header('Location: ?url=payment/adminPayments');
        exit;
    }
}
?>