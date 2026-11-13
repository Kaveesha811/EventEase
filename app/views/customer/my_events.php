<?php include '../app/views/partials/header.php'; ?>

<h2>My Events</h2>
<?php if (empty($events)): ?>
    <p>No events yet. <a href="?url=event/create">Create one</a>.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Type</th>
            <th>Date</th>
            <th>Location</th>
            <th>Budget</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                <td><?php echo htmlspecialchars($event['event_type_name']); ?></td>
                <td><?php echo $event['event_date']; ?></td>
                <td><?php echo htmlspecialchars($event['location']); ?></td>
                <td>LKR <?php echo number_format($event['budget'], 2); ?></td>
                <td>
                    <a href="?url=event/view/<?php echo $event['event_id']; ?>" class="btn">View</a>
                    <a href="?url=event/edit/<?php echo $event['event_id']; ?>" class="btn btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>