<?php include '../app/views/partials/header.php'; ?>

<div class="dashboard-welcome">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p style="font-size:1.1rem; margin-top:5px;">
        <strong><?php echo htmlspecialchars($vendor['business_name']); ?></strong>
    </p>
    <p>Manage your services, availability, and bookings.</p>
</div>

<div class="grid">
    <div class="card">
        <h3>My Services (<?php echo $servicesCount; ?>)</h3>
        <a href="?url=service/myServices" class="btn">Manage Services</a>
    </div>
    <div class="card">
        <h3>Booking Requests (<?php echo $bookingsCount; ?>)</h3>
        <a href="?url=booking/vendorBookings" class="btn">View Bookings</a>
    </div>
    <div class="card">
        <h3>Availability (<?php echo $availabilityCount; ?> dates)</h3>
        <a href="?url=vendor/availability" class="btn">Manage Availability</a>
    </div>
    <div class="card">
        <h3>Customer Reviews (<?php echo $reviewsCount; ?>)</h3>
        <a href="?url=review/vendorReviews" class="btn">View Reviews</a>
    </div>
</div>

<?php include '../app/views/partials/footer.php'; ?>