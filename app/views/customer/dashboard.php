<?php include '../app/views/partials/header.php'; ?>

<div class="dashboard-welcome">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p>Manage your events, find services, and track bookings.</p>
</div>

<div style="margin-bottom:20px;">
    <a href="?url=event/create" class="btn">Create Event</a>
    <a href="?url=event/findServices" class="btn">Find Services</a>
</div>

<div class="grid">
    <div class="card">
        <h3>My Events</h3>
        <p>You have <?php echo count($events); ?> event(s).</p>
        <a href="?url=event/myEvents" class="btn">View Events</a>
    </div>
    <div class="card">
        <h3>My Bookings</h3>
        <p>You have <?php echo count($bookings); ?> booking(s).</p>
        <a href="?url=booking/myBookings" class="btn">View Bookings</a>
    </div>
    <div class="card">
        <h3>My Payments</h3>
        <a href="?url=payment/myPayments" class="btn">View Payments</a>
    </div>
</div>

<?php include '../app/views/partials/footer.php'; ?>