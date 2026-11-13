<?php include '../app/views/partials/header.php'; ?>

<h2>Create Event</h2>
<form action="?url=event/store" method="POST">
    <div class="form-group">
        <label>Event Name</label>
        <input type="text" name="event_name" value="<?php echo isset($_SESSION['old']['event_name']) ? htmlspecialchars($_SESSION['old']['event_name']) : ''; ?>" required>
    </div>
    <div class="form-group">
        <label>Event Type</label>
        <select name="event_type_id" required>
            <option value="">Select</option>
            <?php foreach ($eventTypes as $type): ?>
                <option value="<?php echo $type['event_type_id']; ?>" <?php echo (isset($_SESSION['old']['event_type_id']) && $_SESSION['old']['event_type_id'] == $type['event_type_id']) ? 'selected' : ''; ?>><?php echo $type['event_type_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Event Date</label>
        <input type="date" name="event_date" value="<?php echo isset($_SESSION['old']['event_date']) ? $_SESSION['old']['event_date'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label>Location</label>
        <input type="text" name="location" value="<?php echo isset($_SESSION['old']['location']) ? htmlspecialchars($_SESSION['old']['location']) : ''; ?>" required>
    </div>
    <div class="form-group">
        <label>Guest Count</label>
        <input type="number" name="guest_count" value="<?php echo isset($_SESSION['old']['guest_count']) ? $_SESSION['old']['guest_count'] : ''; ?>" required min="1">
    </div>
    <div class="form-group">
        <label>Budget (LKR)</label>
        <input type="number" step="0.01" name="budget" value="<?php echo isset($_SESSION['old']['budget']) ? $_SESSION['old']['budget'] : ''; ?>" required min="0">
    </div>
    <button type="submit" class="btn">Create Event</button>
</form>

<?php include '../app/views/partials/footer.php'; ?>