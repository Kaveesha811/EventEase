<?php include '../app/views/partials/header.php'; ?>

<h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
<p><strong>Type:</strong> <?php echo $event['event_type_name']; ?></p>
<p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
<p><strong>Guests:</strong> <?php echo $event['guest_count']; ?></p>
<p><strong>Budget:</strong> LKR <?php echo number_format($event['budget'], 2); ?></p>

<hr>
<h3>Selected Services</h3>
<?php if (empty($eventItems)): ?>
    <p>No services selected. <a href="?url=event/findServices">Find services</a>.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Service</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php 
        $totalEstimate = 0;
        foreach ($eventItems as $item): 
            $totalEstimate += $item['estimated_price'];
        ?>
            <tr>
                <td><?php echo htmlspecialchars($item['service_name']); ?></td>
                <td><?php echo htmlspecialchars($item['business_name']); ?></td>
                <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                <td>LKR <?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>LKR <?php echo number_format($item['estimated_price'], 2); ?></td>
                <td><a href="?url=event/removeService/<?php echo $item['event_item_id']; ?>" class="btn btn-danger" onclick="return confirm('Remove this service?')">Remove</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="5"><strong>Total Estimate</strong></td>
            <td><strong>LKR <?php echo number_format($totalEstimate, 2); ?></strong></td>
            <td></td>
        </tr>
    </table>
    <a href="?url=booking/requestBooking/<?php echo $event['event_id']; ?>" class="btn btn-success">Request Booking</a>
<?php endif; ?>

<?php include '../app/views/partials/footer.php'; ?>