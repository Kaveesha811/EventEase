<?php include '../app/views/partials/header.php'; ?>

<h2>Make Payment for Booking #<?php echo $booking['booking_id']; ?></h2>
<p>Please transfer the total amount to our bank account and submit the slip.</p>
<p><strong>Amount to Pay:</strong> LKR <?php echo number_format($booking['total_amount'], 2); ?></p>
<form action="?url=payment/store" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
    <input type="hidden" name="amount" value="<?php echo $booking['total_amount']; ?>">
    <div class="form-group">
        <label>Bank Transfer Reference Number</label>
        <input type="text" name="reference_no" required>
    </div>
    <div class="form-group">
        <label>Upload Payment Slip (JPG/PNG/PDF, max 2MB)</label>
        <input type="file" name="payment_slip" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <button type="submit" class="btn">Submit Payment</button>
</form>

<?php include '../app/views/partials/footer.php'; ?>