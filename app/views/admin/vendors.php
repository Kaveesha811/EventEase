<?php include '../app/views/partials/header.php'; ?>

<h2>Manage Vendors</h2>
<h3>Pending Approval</h3>
<?php if (empty($pendingVendors)): ?>
    <p>No pending vendors.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Business Name</th>
            <th>Owner</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($pendingVendors as $vendor): ?>
            <tr>
                <td><?php echo htmlspecialchars($vendor['business_name']); ?></td>
                <td><?php echo htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']); ?></td>
                <td><?php echo $vendor['email']; ?></td>
                <td><?php echo $vendor['phone']; ?></td>
                <td>
                    <a href="?url=admin/approveVendor/<?php echo $vendor['vendor_id']; ?>" class="btn btn-success">Approve</a>
                    <a href="?url=admin/rejectVendor/<?php echo $vendor['vendor_id']; ?>" class="btn btn-danger">Reject</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<h3>Approved Vendors</h3>
<table>
    <tr>
        <th>Business Name</th>
        <th>Owner</th>
        <th>Email</th>
    </tr>
    <?php foreach ($approvedVendors as $vendor): ?>
        <tr>
            <td><?php echo htmlspecialchars($vendor['business_name']); ?></td>
            <td><?php echo htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']); ?></td>
            <td><?php echo $vendor['email']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../app/views/partials/footer.php'; ?>