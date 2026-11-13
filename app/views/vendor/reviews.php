<?php include '../app/views/partials/header.php'; ?>

<h2>Customer Reviews</h2>
<?php if (empty($reviews)): ?>
    <p>No reviews yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Booking</th>
            <th>Event</th>
            <th>Customer</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
        </tr>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td>#<?php echo $review['booking_id']; ?></td>
                <td><?php echo htmlspecialchars($review['event_name']); ?></td>
                <td><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></td>
                <td><?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($review['comment'])); ?></td>
                <td><?php echo $review['review_date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>
