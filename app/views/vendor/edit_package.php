<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header("Location: index.php?page=login");
    exit;
}

$message = $message ?? '';

$packageID = $package['PackageID'] ?? 0;

$selectedServiceIDs = $selectedServiceIDs ?? [];

$selectedServiceIDs = array_map(
    'intval',
    $selectedServiceIDs
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Package - EventEase</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
        }

        .header {
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .header h1 {
            margin: 0 0 8px;
            color: #222;
        }

        .header p {
            margin: 0;
            color: #666;
        }

        .form-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #555;
        }

        .services-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #fafafa;
        }

        .service-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px;
            margin-bottom: 8px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 7px;
        }

        .service-item:last-child {
            margin-bottom: 0;
        }

        .service-item input[type="checkbox"] {
            margin-top: 3px;
            width: 17px;
            height: 17px;
        }

        .service-info {
            flex: 1;
        }

        .service-name {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .service-details {
            font-size: 14px;
            color: #666;
        }

        .service-price {
            font-weight: bold;
            color: #333;
        }

        .status-box {
            margin-bottom: 22px;
            padding: 12px 15px;
            background: #f1f1f1;
            border-radius: 7px;
        }

        .status-label {
            font-weight: bold;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #fff3cd;
            border: 1px solid #ffe69c;
            color: #664d03;
            border-radius: 7px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-update {
            background: #222;
            color: #ffffff;
        }

        .btn-update:hover {
            background: #444;
        }

        .btn-cancel {
            background: #e5e5e5;
            color: #333;
        }

        .btn-cancel:hover {
            background: #d5d5d5;
        }

        .empty-message {
            color: #777;
            padding: 10px 0;
        }

        .required {
            color: #d00;
        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>Edit Package</h1>

        <p>
            Update your package details and included services.
        </p>

    </div>


    <div class="form-card">

        <?php if (!empty($message)): ?>

            <div class="message">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <div class="status-box">

            <span class="status-label">
                Current Status:
            </span>

            <?= htmlspecialchars(
                $package['Status'] ?? 'Unknown'
            ) ?>

        </div>


        <form
            method="POST"
            action="index.php?page=edit_package&id=<?= $packageID ?>"
            id="editPackageForm"
        >


            <!-- Package Name -->

            <div class="form-group">

                <label for="package_name">
                    Package Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="package_name"
                    name="package_name"
                    value="<?= htmlspecialchars(
                        $package['PackageName'] ?? ''
                    ) ?>"
                    required
                >

            </div>


            <!-- Description -->

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                ><?= htmlspecialchars(
                    $package['Description'] ?? ''
                ) ?></textarea>

            </div>


            <!-- Price -->

            <div class="form-group">

                <label for="price">
                    Package Price (10% Discount)
                    <span class="required">*</span>
                </label>

                <p class="form-hint">
                    The final price is calculated automatically from the selected services.
                </p>

                <input
                    type="number"
                    id="price"
                    name="price"
                    value="<?= htmlspecialchars(
                        $package['Price'] ?? ''
                    ) ?>"
                    min="0"
                    step="0.01"
                    readonly
                    required
                >

                <p class="package-total-preview" id="editPackagePricePreview">
                    Select services to see the original total and discounted price.
                </p>

            </div>


            <!-- Services -->

            <div class="form-group">

                <label>
                    Included Services
                    <span class="required">*</span>
                </label>

                <div class="services-box">

                    <?php if (!empty($vendorServices)): ?>

                        <?php foreach (
                            $vendorServices as $service
                        ): ?>

                            <?php
                            $serviceID =
                                (int) $service['ServiceID'];

                            $isSelected =
                                in_array(
                                    $serviceID,
                                    $selectedServiceIDs,
                                    true
                                );
                            ?>

                            <label class="service-item">

                                <input
                                    type="checkbox"
                                    name="service_ids[]"
                                    value="<?= $serviceID ?>"
                                    data-price="<?= htmlspecialchars(
                                        $service['Price']
                                    ) ?>"
                                    <?= $isSelected
                                        ? 'checked'
                                        : '' ?>
                                >

                                <div class="service-info">

                                    <div class="service-name">

                                        <?= htmlspecialchars(
                                            $service['ServiceName']
                                        ) ?>

                                    </div>

                                    <div class="service-details">

                                        <?= htmlspecialchars(
                                            $service['CategoryName']
                                        ) ?>

                                        <?php if (
                                            !empty(
                                                $service['Description']
                                            )
                                        ): ?>

                                            -
                                            <?= htmlspecialchars(
                                                $service['Description']
                                            ) ?>

                                        <?php endif; ?>

                                        <br>

                                        <span class="service-price">

                                            LKR
                                            <?= number_format(
                                                (float)
                                                $service['Price'],
                                                2
                                            ) ?>

                                        </span>

                                    </div>

                                </div>

                            </label>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-message">

                            You do not have any active services
                            available for this package.

                            <br><br>

                            Please add an active service first.

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <div class="form-group">

                <label>
                    Suitable Event Types
                </label>

                <div class="event-type-options">

                    <?php foreach ($eventTypes as $eventType): ?>

                        <?php
                        $eventTypeID =
                            (int) $eventType['EventTypeID'];

                        $isSelected =
                            in_array(
                                $eventTypeID,
                                $selectedEventTypeIDs,
                                true
                            );
                        ?>

                        <label class="event-type-option">
                            <input
                                type="checkbox"
                                name="event_type_ids[]"
                                value="<?= $eventTypeID ?>"
                                <?= $isSelected ? 'checked' : '' ?>
                            >
                            <?= htmlspecialchars(
                                $eventType['EventTypeName']
                            ) ?>
                        </label>

                    <?php endforeach; ?>

                </div>

            </div>

            <!-- Buttons -->

            <div class="buttons">

                <button
                    type="submit"
                    class="btn btn-update"
                >
                    Update Package
                </button>


                <a
                    href="index.php?page=vendor_packages"
                    class="btn btn-cancel"
                >
                    Cancel
                </a>

            </div>


        </form>

    </div>

</div>


<script>

document
    .getElementById('editPackageForm')
    .addEventListener('submit', function(event) {

        const checkboxes =
            document.querySelectorAll(
                'input[name="service_ids[]"]:checked'
            );

        if (checkboxes.length === 0) {

            alert(
                'Please select at least one service for the package.'
            );

            event.preventDefault();

        }

    });

const editServiceChecks = document.querySelectorAll(
    'input[name="service_ids[]"]'
);
const editPriceInput = document.getElementById('price');
const editPricePreview = document.getElementById(
    'editPackagePricePreview'
);

function updateEditPackagePrice() {
    let originalTotal = 0;

    editServiceChecks.forEach((checkbox) => {
        if (checkbox.checked) {
            originalTotal += Number(checkbox.dataset.price || 0);
        }
    });

    const packagePrice = originalTotal * 0.9;
    const saving = originalTotal * 0.1;

    editPriceInput.value = packagePrice.toFixed(2);

    if (originalTotal > 0) {
        editPricePreview.textContent =
            `Original: LKR ${originalTotal.toFixed(2)} | ` +
            `Saving: LKR ${saving.toFixed(2)}`;
    }
}

editServiceChecks.forEach((checkbox) => {
    checkbox.addEventListener('change', updateEditPackagePrice);
});

updateEditPackagePrice();

</script>


</body>

</html>