<?php include '../app/views/partials/header.php'; ?>

<h2>My Services</h2>
<a href="?url=service/create" class="btn">Add New Service</a>
<?php if (empty($services)): ?>
    <p>No services added yet.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Service Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['service_name'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($service['category_name'] ?? ''); ?></td>
                <td>LKR <?php echo number_format($service['price'] ?? 0, 2); ?></td>
                <td><?php echo ucfirst($service['status'] ?? ''); ?></td>
                <td>
                    <a href="?url=service/edit/<?php echo $service['service_id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="?url=service/deactivate/<?php echo $service['service_id']; ?>" class="btn btn-danger" onclick="return confirm('Deactivate this service?')">Deactivate</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>