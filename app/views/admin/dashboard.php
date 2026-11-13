<?php include '../app/views/partials/header.php'; ?>

<h2>Admin Dashboard</h2>
<div class="grid">
    <div class="card">
        <h3>Total Customers</h3>
        <p><?php echo $totalCustomers; ?></p>
    </div>
    <div class="card">
        <h3>Total Vendors</h3>
        <p><?php echo $totalVendors; ?></p>
    </div>
    <div class="card">
        <h3>Pending Vendors</h3>
        <p><?php echo $pendingVendors; ?></p>
        <a href="?url=admin/vendors" class="btn">Review</a>
    </div>
    <div class="card">
        <h3>Pending Payments</h3>
        <p><?php echo $pendingPayments; ?></p>
        <a href="?url=payment/adminPayments" class="btn">Review</a>
    </div>
    <div class="card">
        <h3>Total Bookings</h3>
        <p><?php echo $totalBookings; ?></p>
    </div>
</div>

<?php include '../app/views/partials/footer.php'; ?>