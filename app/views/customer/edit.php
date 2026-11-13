<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <h2>EventEase</h2>
    <div class="navbar-links">
        <a href="index.php?page=customer_dashboard">Dashboard</a>
        <a href="index.php?page=my_events">My Events</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</nav>
<main class="container">
    <div class="edit-event-card">
        <div class="page-header">
            <h1>Edit Event</h1>
            <p>Update your event details below.</p>
        </div>
        <?php if (!empty($message)): ?>
            <p class="auth-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?page=edit_event&id=<?= (int)$event['EventID'] ?>">
            <div class="form-group">
                <label for="eventTypeID">Event Type</label>
                <select id="eventTypeID" name="eventTypeID" required>
                    <?php foreach ($eventTypes as $type): ?>
                        <option value="<?= (int)$type['EventTypeID'] ?>" <?= (int)$type['EventTypeID'] === (int)$event['EventTypeID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['EventTypeName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="eventName">Event Name</label><input type="text" id="eventName" name="eventName" maxlength="120" value="<?= htmlspecialchars($event['EventName']) ?>" required></div>
            <div class="form-group"><label for="eventDate">Event Date</label><input type="date" id="eventDate" name="eventDate" value="<?= htmlspecialchars($event['EventDate']) ?>" required></div>
            <div class="form-group"><label for="location">Location</label><input type="text" id="location" name="location" maxlength="150" value="<?= htmlspecialchars($event['Location']) ?>" required></div>
            <div class="form-group"><label for="guestCount">Guest Count</label><input type="number" id="guestCount" name="guestCount" min="1" value="<?= (int)$event['GuestCount'] ?>" required></div>
            <div class="form-group"><label for="budget">Budget (LKR)</label><input type="number" id="budget" name="budget" min="0" step="0.01" value="<?= htmlspecialchars($event['Budget']) ?>" required></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="index.php?page=view_event&id=<?= (int)$event['EventID'] ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
