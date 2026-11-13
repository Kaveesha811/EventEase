<?php include '../app/views/partials/header.php'; ?>

<h2>Payments</h2>
<table>
    <tr>
        <th>Payment ID</th>
        <th>Booking ID</th>
        <th>Customer</th>
        <th>Event</th>
        <th>Amount</th>
        <th>Reference</th>
        <th>Slip</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?php echo $payment['payment_id']; ?></td>
            <td><?php echo $payment['booking_id']; ?></td>
            <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
            <td><?php echo htmlspecialchars($payment['event_name']); ?></td>
            <td>LKR <?php echo number_format($payment['amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($payment['reference_no']); ?></td>
            <td><?php if ($payment['payment_slip']): ?><a href="../public/<?php echo $payment['payment_slip']; ?>" target="_blank">View</a><?php endif; ?></td>
            <td><?php echo ucfirst($payment['payment_status']); ?></td>
            <td><?php echo $payment['payment_date']; ?></td>
            <td>
                <?php if ($payment['payment_status'] == 'pending'): ?>
                    <a href="?url=payment/updateStatus/<?php echo $payment['payment_id']; ?>/completed" class="btn btn-success">Mark Completed</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../app/views/partials/footer.php'; ?>