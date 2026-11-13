<?php include '../app/views/partials/header.php'; ?>

<h2>Manage Availability</h2>
<form action="?url=vendor/addAvailability" method="POST">
    <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" required>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select name="status">
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
            <option value="booked">Booked</option>
        </select>
    </div>
    <button type="submit" class="btn">Add/Update</button>
</form>

<?php if (!empty($availabilities)): ?>
    <table>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($availabilities as $avail): ?>
            <tr>
                <td><?php echo $avail['availability_date']; ?></td>
                <td><?php echo ucfirst($avail['status']); ?></td>
                <td><a href="?url=vendor/deleteAvailability/<?php echo $avail['availability_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No availability records.</p>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>