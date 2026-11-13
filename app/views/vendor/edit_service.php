<?php
if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header('Location: index.php?page=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Service - EventEase</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<nav class="navbar">

    <h2>EventEase</h2>

    <div class="navbar-links">

        <a href="index.php?page=vendor_dashboard">Dashboard</a>
        <a href="index.php?page=vendor_bookings">Bookings</a>
        <a href="index.php?page=vendor_services">Services</a>
        <a href="index.php?page=vendor_packages">Packages</a>
        <a href="index.php?page=vendor_availability">Availability</a>
        <a href="index.php?page=vendor_transactions">Transactions</a>
        <a href="index.php?page=logout">Logout</a>

    </div>

</nav>

<div class="container">

    <div class="page-header">

        <span class="page-kicker">Vendor workspace</span>

        <h1>Edit Service</h1>

        <p>
            Update your service details and keep your listing accurate.
        </p>

    </div>

    <?php if (!empty($message)): ?>

        <div class="message message-error">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($service)): ?>

        <form
            method="POST"
            class="card edit-service-card"
            action="index.php?page=edit_service&id=<?= urlencode($service['ServiceID']) ?>"
        >

            <div class="form-group">

                <label for="serviceCategoryID">
                    Service Category
                </label>

                <select
                    name="serviceCategoryID"
                    id="serviceCategoryID"
                    required
                >

                    <option value="">
                        Select Category
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= htmlspecialchars($category['ServiceCategoryID']) ?>"
                            <?= (
                                $category['ServiceCategoryID']
                                == $service['ServiceCategoryID']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= htmlspecialchars(
                                $category['CategoryName']
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="form-group">

                <label for="serviceName">
                    Service Name
                </label>

                <input
                    type="text"
                    name="serviceName"
                    id="serviceName"
                    value="<?= htmlspecialchars($service['ServiceName']) ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    required
                ><?= htmlspecialchars($service['Description']) ?></textarea>

            </div>


            <div class="form-group">

                <label for="price">
                    Price (LKR)
                </label>

                <input
                    type="number"
                    name="price"
                    id="price"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($service['Price']) ?>"
                    required
                >

            </div>


            <div class="edit-service-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                Update Service
                </button>

                <a
                    href="index.php?page=vendor_services"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    <?php else: ?>

        <div class="card empty-message">
            <h2>Service not found</h2>
            <p>This service may have been removed or is no longer available.</p>

        <a
            href="index.php?page=vendor_services"
            class="back-button"
        >
            Back to Manage Services
        </a>
        </div>

    <?php endif; ?>

</div>

</body>

</html>