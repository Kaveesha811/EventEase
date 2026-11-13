<?php include '../app/views/partials/header.php'; ?>

<h2>Edit Service</h2>

<?php
// Pre-fill with existing service data or old input from validation error
$old = isset($_SESSION['old']) ? $_SESSION['old'] : [];
$serviceName = isset($old['service_name']) ? $old['service_name'] : $service['service_name'];
$categoryId = isset($old['service_category_id']) ? $old['service_category_id'] : $service['service_category_id'];
$description = isset($old['description']) ? $old['description'] : $service['description'];
$price = isset($old['price']) ? $old['price'] : $service['price'];
unset($_SESSION['old']);
?>

<form action="?url=service/update/<?php echo $service['service_id']; ?>" method="POST">
    <div class="form-group">
        <label>Service Name</label>
        <input type="text" name="service_name" value="<?php echo htmlspecialchars($serviceName); ?>" required>
    </div>
    <div class="form-group">
        <label>Service Category</label>
        <select name="service_category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['service_category_id']; ?>" <?php echo ($categoryId == $cat['service_category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    <div class="form-group">
        <label>Price (LKR)</label>
        <input type="number" step="0.01" name="price" min="0" value="<?php echo htmlspecialchars($price); ?>" required>
    </div>
    <button type="submit" class="btn">Update Service</button>
    <a href="?url=service/myServices" class="btn">Cancel</a>
</form>

<?php include '../app/views/partials/footer.php'; ?>