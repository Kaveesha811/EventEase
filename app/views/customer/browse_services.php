<?php include '../app/views/partials/header.php'; ?>

<h2>Available Services</h2>
<?php if (empty($services)): ?>
    <div class="alert alert-error">No services found for this category. Please try another category or check back later.</div>
<?php else: ?>
    <table>
        <tr>
            <th>Service</th>
            <th>Vendor</th>
            <th>Description</th>
            <th>Price</th>
            <th>Facebook Profile</th>
            <th>Action</th>
        </tr>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                <td><?php echo htmlspecialchars($service['business_name']); ?><br><small><?php echo htmlspecialchars($service['business_address']); ?></small></td>
                <td><?php echo htmlspecialchars($service['description']); ?></td>
                <td>LKR <?php echo number_format($service['price'], 2); ?></td>
                <td><?php if ($service['facebook_profile']): ?><a href="<?php echo $service['facebook_profile']; ?>" target="_blank">View</a><?php endif; ?></td>
                <td>
                    <form action="?url=event/addService" method="POST">
                        <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>">
                        <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                        <button type="submit" class="btn">Add</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="?url=event/view/<?php echo $_GET['event_id']; ?>">Back to Event</a></p>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>