<?php include '../app/views/partials/header.php'; ?>

<h2>My Bookings</h2>
<?php if (empty($bookings)): ?>
    <p>No bookings yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Event</th>
            <th>Vendor</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['event_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['business_name']); ?></td>
                <td>LKR <?php echo number_format($booking['total_amount'], 2); ?></td>
                <td><?php echo ucfirst($booking['status']); ?></td>
                <td><?php echo $booking['booking_date']; ?></td>
                <td>
                    <?php if ($booking['status'] == 'accepted'): ?>
                        <a href="?url=payment/create/<?php echo $booking['booking_id']; ?>" class="btn">Make Payment</a>
                    <?php elseif ($booking['status'] == 'completed' && !Review::getByBooking($booking['booking_id'])): ?>
                        <a href="?url=review/create/<?php echo $booking['booking_id']; ?>" class="btn">Review</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>