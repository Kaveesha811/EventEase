<?php include '../app/views/partials/header.php'; ?>

<h2>Booking Requests</h2>
<p style="color:#7f8c8d; margin-bottom:20px;">Review the event date and customer details before accepting or rejecting.</p>

<?php if (empty($bookings)): ?>
    <div class="alert alert-error">No booking requests yet.</div>
<?php else: ?>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Customer</th>
            <th>Requested On</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Items</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo $booking['booking_id']; ?></td>
                <td><?php echo htmlspecialchars($booking['event_name']); ?></td>
                <td>
                    <strong><?php echo date('d M Y', strtotime($booking['event_date'])); ?></strong>
                </td>
                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                <td><?php echo date('d M Y, h:i A', strtotime($booking['booking_date'])); ?></td>
                <td>LKR <?php echo number_format($booking['total_amount'], 2); ?></td>
                <td>
                    <span class="badge badge-<?php echo strtolower($booking['status']); ?>">
                        <?php echo ucfirst($booking['status']); ?>
                    </span>
                </td>
                <td>
                    <?php $items = Booking::getItems($booking['booking_id']); ?>
                    <ul style="margin:0; padding-left:18px;">
                        <?php foreach ($items as $item): ?>
                            <li><?php echo htmlspecialchars($item['service_name']); ?> x <?php echo $item['quantity']; ?> = LKR <?php echo number_format($item['subtotal'], 2); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <?php if ($booking['status'] == 'pending'): ?>
                        <a href="?url=booking/updateStatus/<?php echo $booking['booking_id']; ?>/accepted" class="btn btn-success" onclick="return confirm('Accept this booking?')">Accept</a>
                        <a href="?url=booking/updateStatus/<?php echo $booking['booking_id']; ?>/rejected" class="btn btn-danger" onclick="return confirm('Reject this booking?')">Reject</a>
                    <?php elseif ($booking['status'] == 'accepted'): ?>
                        <a href="?url=booking/updateStatus/<?php echo $booking['booking_id']; ?>/completed" class="btn btn-success" onclick="return confirm('Mark as completed?')">Mark Completed</a>
                        <a href="?url=booking/updateStatus/<?php echo $booking['booking_id']; ?>/cancelled" class="btn btn-danger" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    <?php else: ?>
                        <em>No action</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>