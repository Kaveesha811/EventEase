<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header(
        "Location: index.php?page=login"
    );
    exit;
}

$vendorPackages =
    $vendorPackages ?? [];

$vendorServices =
    $vendorServices ?? [];

$packageMessage =
    $_SESSION['package_message']
    ?? '';

unset(
    $_SESSION['package_message']
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

    <title>My Packages - EventEase</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            color: #222;
        }

        .back-link {
            text-decoration: none;
            color: #555;
        }

        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            color: #333;
        }

        .message {
            padding: 12px 15px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .services-box {
            border: 1px solid #ddd;
            border-radius: 7px;
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .service-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .service-option:last-child {
            border-bottom: none;
        }

        .service-option input {
            width: auto;
            margin-top: 4px;
        }

        .service-info {
            flex: 1;
        }

        .service-name {
            font-weight: bold;
            color: #222;
        }

        .service-category {
            font-size: 13px;
            color: #777;
            margin-top: 3px;
        }

        .service-price {
            font-size: 13px;
            color: #444;
            margin-top: 3px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #333;
            color: white;
        }

        .btn-primary:hover {
            background: #111;
        }

        .btn-edit {
            background: #eeeeee;
            color: #333;
        }

        .btn-danger {
            background: #d9534f;
            color: white;
        }

        .btn-danger:hover {
            background: #c9302c;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f3f6;
            color: #333;
        }

        .status-active {
            color: #2e7d32;
            font-weight: bold;
        }

        .status-inactive {
            color: #c62828;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        .empty-message {
            color: #777;
            padding: 15px 0;
        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="container">


    <!-- PAGE HEADER -->

    <div class="page-header">

        <h1>
            My Packages
        </h1>

        <a
            href="index.php?page=vendor_dashboard"
            class="back-link"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- MESSAGE -->

    <?php if (!empty($packageMessage)): ?>

        <div class="message">

            <?php
            echo htmlspecialchars(
                $packageMessage
            );
            ?>

        </div>

    <?php endif; ?>


    <!-- ADD PACKAGE -->

    <div class="card">

        <h2>
            Add New Package
        </h2>


        <form
            method="POST"
            action="index.php?page=add_package"
        >


            <!-- PACKAGE NAME -->

            <div class="form-group">

                <label for="packageName">
                    Package Name
                </label>

                <input
                    type="text"
                    id="packageName"
                    name="package_name"
                    placeholder="Enter package name"
                    required
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Describe what this package includes"
                    required
                ></textarea>

            </div>


            <!-- PRICE -->

            <div class="form-group">

                <label for="price">
                    Package Price (10% Discount)
                </label>

                <p class="form-hint">
                    The final price is calculated automatically from the selected services.
                </p>

                <input
                    type="number"
                    id="price"
                    name="price"
                    min="0"
                    step="0.01"
                    placeholder="Select services to calculate"
                    readonly
                    required
                >

                <p class="package-total-preview" id="packagePricePreview">
                    Select services to see the original total and discounted price.
                </p>

            </div>


            <!-- SERVICES -->

            <div class="form-group">

                <label>
                    Select Services Included in Package
                </label>


                <?php if (!empty($vendorServices)): ?>

                    <div class="services-box">

                        <?php foreach (
                            $vendorServices
                            as $service
                        ): ?>

                            <div
                                class="service-option"
                            >

                                <input
                                    type="checkbox"
                                    name="service_ids[]"
                                    value="<?php
                                    echo (int)
                                        $service['ServiceID'];
                                    ?>"
                                    data-price="<?= htmlspecialchars(
                                        $service['Price']
                                    ) ?>"
                                    id="service_<?php
                                    echo (int)
                                        $service['ServiceID'];
                                    ?>"
                                >


                                <label
                                    for="service_<?php
                                    echo (int)
                                        $service['ServiceID'];
                                    ?>"
                                    class="service-info"
                                >

                                    <div
                                        class="service-name"
                                    >
                                        <?php
                                        echo htmlspecialchars(
                                            $service['ServiceName']
                                        );
                                        ?>
                                    </div>

                                    <div
                                        class="service-category"
                                    >
                                        Category:
                                        <?php
                                        echo htmlspecialchars(
                                            $service['CategoryName']
                                        );
                                        ?>
                                    </div>

                                    <div
                                        class="service-price"
                                    >
                                        Service Price:
                                        LKR
                                        <?php
                                        echo number_format(
                                            $service['Price'],
                                            2
                                        );
                                        ?>
                                    </div>

                                </label>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <p class="empty-message">

                        You do not have any active
                        services yet.

                        Please add an active service
                        before creating a package.

                    </p>

                <?php endif; ?>

            </div>


            <div class="form-group">

                <label>
                    Suitable Event Types
                </label>

                <div class="event-type-options">

                    <?php foreach ($eventTypes as $eventType): ?>

                        <label class="event-type-option">
                            <input
                                type="checkbox"
                                name="event_type_ids[]"
                                value="<?= (int) $eventType['EventTypeID'] ?>"
                            >
                            <?= htmlspecialchars(
                                $eventType['EventTypeName']
                            ) ?>
                        </label>

                    <?php endforeach; ?>

                </div>

            </div>

            <!-- SUBMIT -->

            <?php if (!empty($vendorServices)): ?>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Package
                </button>

            <?php endif; ?>


        </form>

    </div>


    <!-- MY PACKAGES -->

    <div class="card">

        <h2>
            My Packages
        </h2>


        <?php if (!empty($vendorPackages)): ?>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Package Name
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach (
                        $vendorPackages
                        as $package
                    ): ?>

                        <tr>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $package['PackageName']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $package['Description']
                                );
                                ?>

                            </td>


                            <td>

                                LKR
                                <?php
                                echo number_format(
                                    $package['Price'],
                                    2
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                if (
                                    $package['Status']
                                    === 'Active'
                                ):

                                    echo
                                        '<span class="status-active">
                                            Active
                                        </span>';

                                else:

                                    echo
                                        '<span class="status-inactive">
                                            Inactive
                                        </span>';

                                endif;
                                ?>

                            </td>


                            <td>

                                <div class="actions">


                                    <!-- EDIT -->

                                    <a
                                        href="index.php?page=edit_package&id=<?php
                                        echo (int)
                                            $package['PackageID'];
                                        ?>"
                                        class="btn btn-edit"
                                    >
                                        Edit
                                    </a>


                                    <!-- DEACTIVATE -->

                                    <?php if (
                                        $package['Status']
                                        === 'Active'
                                    ): ?>

                                        <form
                                            method="POST"
                                            action="index.php?page=deactivate_package"
                                            onsubmit="return confirm(
                                                'Are you sure you want to deactivate this package?'
                                            );"
                                        >

                                            <input
                                                type="hidden"
                                                name="package_id"
                                                value="<?php
                                                echo (int)
                                                    $package['PackageID'];
                                                ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                            >
                                                Deactivate
                                            </button>

                                        </form>

                                    <?php endif; ?>


                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <p class="empty-message">

                You have not created any packages yet.

            </p>

        <?php endif; ?>

    </div>


</div>

<script>
    const serviceChecks = document.querySelectorAll(
        'input[name="service_ids[]"]'
    );
    const priceInput = document.getElementById('price');
    const pricePreview = document.getElementById('packagePricePreview');

    function updatePackagePrice() {
        let originalTotal = 0;

        serviceChecks.forEach((checkbox) => {
            if (checkbox.checked) {
                originalTotal += Number(checkbox.dataset.price || 0);
            }
        });

        const packagePrice = originalTotal * 0.9;
        const saving = originalTotal * 0.1;

        priceInput.value = packagePrice.toFixed(2);

        if (originalTotal > 0) {
            pricePreview.textContent =
                `Original: LKR ${originalTotal.toFixed(2)} | ` +
                `Saving: LKR ${saving.toFixed(2)}`;
        } else {
            pricePreview.textContent =
                'Select services to see the original total and discounted price.';
        }
    }

    serviceChecks.forEach((checkbox) => {
        checkbox.addEventListener('change', updatePackagePrice);
    });
</script>

</body>

</html>