<?php include '../app/views/partials/header.php'; ?>

<h2>Edit Event</h2>

<?php
// Pre-fill form with existing event data or old input from validation error
$old = isset($_SESSION['old']) ? $_SESSION['old'] : [];
$eventName = isset($old['event_name']) ? $old['event_name'] : $event['event_name'];
$eventTypeId = isset($old['event_type_id']) ? $old['event_type_id'] : $event['event_type_id'];
$eventDate = isset($old['event_date']) ? $old['event_date'] : $event['event_date'];
$location = isset($old['location']) ? $old['location'] : $event['location'];
$guestCount = isset($old['guest_count']) ? $old['guest_count'] : $event['guest_count'];
$budget = isset($old['budget']) ? $old['budget'] : $event['budget'];
unset($_SESSION['old']); // clear old input after using
?>

<form action="?url=event/update/<?php echo $event['event_id']; ?>" method="POST">
    <div class="form-group">
        <label>Event Name</label>
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($eventName); ?>" required>
    </div>
    <div class="form-group">
        <label>Event Type</label>
        <select name="event_type_id" required>
            <option value="">Select Type</option>
            <?php foreach ($eventTypes as $type): ?>
                <option value="<?php echo $type['event_type_id']; ?>" <?php echo ($type['event_type_id'] == $eventTypeId) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['event_type_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Event Date</label>
        <input type="date" name="event_date" value="<?php echo htmlspecialchars($eventDate); ?>" required>
    </div>
    <div class="form-group">
        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
    </div>
    <div class="form-group">
        <label>Guest Count</label>
        <input type="number" name="guest_count" min="1" value="<?php echo htmlspecialchars($guestCount); ?>" required>
    </div>
    <div class="form-group">
        <label>Budget (LKR)</label>
        <input type="number" step="0.01" name="budget" min="0" value="<?php echo htmlspecialchars($budget); ?>" required>
    </div>
    <button type="submit" class="btn">Update Event</button>
    <a href="?url=event/view/<?php echo $event['event_id']; ?>" class="btn">Cancel</a>
</form>

<?php include '../app/views/partials/footer.php'; ?>