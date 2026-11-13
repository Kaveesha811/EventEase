<?php include '../app/views/partials/header.php'; ?>

<h2>Find Services</h2>
<form action="" method="GET">
    <input type="hidden" name="url" value="event/browseServices">
    <div class="form-group">
        <label>Select Event</label>
        <select name="event_id" required>
            <option value="">-- Select Event --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?php echo $event['event_id']; ?>"><?php echo htmlspecialchars($event['event_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Service Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['service_category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn">Browse Services</button>
</form>

<?php include '../app/views/partials/footer.php'; ?>