<?php include '../app/views/partials/header.php'; ?>

<h2>My Payments</h2>
<?php if (empty($payments)): ?>
    <p>No payments yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Event</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Reference</th>
            <th>Slip</th>
            <th>Date</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?php echo $payment['payment_id']; ?></td>
                <td><?php echo htmlspecialchars($payment['event_name']); ?></td>
                <td>LKR <?php echo number_format($payment['amount'], 2); ?></td>
                <td><?php echo ucfirst($payment['payment_status']); ?></td>
                <td><?php echo htmlspecialchars($payment['reference_no']); ?></td>
                <td><?php if ($payment['payment_slip']): ?><a href="../public/<?php echo $payment['payment_slip']; ?>" target="_blank">View</a><?php endif; ?></td>
                <td><?php echo $payment['payment_date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>