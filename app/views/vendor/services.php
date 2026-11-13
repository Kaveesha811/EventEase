<?php
/*
|--------------------------------------------------------------------------
| Vendor Manage Services
|--------------------------------------------------------------------------
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Services - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<nav class="navbar">

    <h2>EventEase</h2>

    <div class="navbar-links">

        <a href="index.php?page=vendor_dashboard">
            Dashboard
        </a>

        <a href="index.php?page=vendor_bookings">
            Bookings
        </a>

        <a href="index.php?page=vendor_services">
            Services
        </a>

        <a href="index.php?page=vendor_packages">
            Packages
        </a>

        <a href="index.php?page=vendor_availability">
            Availability
        </a>

        <a href="index.php?page=vendor_transactions">
            Transactions
        </a>

        <a href="index.php?page=logout">
            Logout
        </a>

    </div>

</nav>

<div class="dashboard-container">


    <!-- Header -->

    <div class="dashboard-header">

        <span class="page-kicker">
            Vendor workspace
        </span>

        <h1>
            Manage Services
        </h1>

        <p>
            Add and manage the event services offered
            to EventEase customers.
        </p>

    </div>

    <div class="vendor-steps" aria-label="Vendor setup steps">

        <div class="vendor-step active">
            <span class="vendor-step-number">1</span>
            <div>
                <strong>Add services</strong>
                <small>What your business offers</small>
            </div>
        </div>

        <div class="vendor-step">
            <span class="vendor-step-number">2</span>
            <div>
                <strong>Create packages</strong>
                <small>Combine your services</small>
            </div>
        </div>

        <div class="vendor-step">
            <span class="vendor-step-number">3</span>
            <div>
                <strong>Set availability</strong>
                <small>Choose available dates</small>
            </div>
        </div>

    </div>



    <!-- Messages -->

    <?php

    if (
        isset($_SESSION['service_message']) &&
        !empty($_SESSION['service_message'])
    ) {

        $message =
            $_SESSION['service_message'];

        unset($_SESSION['service_message']);
    }

    ?>

    <?php if (isset($message) && !empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>



    <!-- Add Service -->

    <div class="section service-create-section">

        <h2>
            Add New Service
        </h2>

        <p class="section-description">

            Add each service separately so customers can find exactly what
            your business provides.

        </p>

        <p class="form-hint">
            Use a clear service name and enter the starting price in LKR.
        </p>


        <form
            method="POST"
            action="index.php?page=vendor_services"
        >


            <!-- Service Category -->

            <div class="form-group">

                <label for="serviceCategoryID">

                    Service Category

                </label>


                <select
                    id="serviceCategoryID"
                    name="serviceCategoryID"
                    required
                >

                    <option value="">

                        Select a service category

                    </option>


                    <?php foreach (
                        $categories as $category
                    ): ?>

                        <option
                            value="<?= htmlspecialchars(
                                $category['ServiceCategoryID']
                            ) ?>"
                        >

                            <?= htmlspecialchars(
                                $category['CategoryName']
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>



            <!-- Service Name -->

            <div class="form-group">

                <label for="serviceName">

                    Service Name

                </label>


                <input
                    type="text"
                    id="serviceName"
                    name="serviceName"
                    placeholder="Example: Wedding Decoration"
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
                    rows="4"
                    placeholder="Describe the service you provide..."
                    required
                ></textarea>

            </div>



            <!-- Price -->

            <div class="form-group">

                <label for="price">

                    Price (LKR)

                </label>


                <input
                    type="number"
                    id="price"
                    name="price"
                    min="0"
                    step="0.01"
                    placeholder="Example: 75000"
                    required
                >

            </div>



            <!-- Submit -->

            <button
                type="submit"
                class="btn btn-primary"
            >

                Add Service

            </button>

            <a
                href="index.php?page=vendor_packages"
                class="btn btn-secondary service-package-link"
            >
                Create Package from Services
            </a>

        </form>

    </div>



    <!-- Existing Services -->

    <div class="section service-list-section">

        <h2>
            My Services
        </h2>

        <p class="section-description">

            Services currently offered by your business.

        </p>



        <?php if (empty($services)): ?>

            <p>

                You have not added any services yet.

            </p>


        <?php else: ?>


            <div class="table-container">

                <table>


                    <!-- Table Header -->

                    <thead>

                        <tr>

                            <th>
                                Service
                            </th>

                            <th>
                                Category
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



                    <!-- Table Body -->

                    <tbody>


                        <?php foreach (
                            $services as $service
                        ): ?>

                            <tr>


                                <!-- Service -->

                                <td>

                                    <?= htmlspecialchars(
                                        $service['ServiceName']
                                    ) ?>

                                </td>



                                <!-- Category -->

                                <td>

                                    <?= htmlspecialchars(
                                        $service['CategoryName']
                                    ) ?>

                                </td>



                                <!-- Description -->

                                <td>

                                    <?= htmlspecialchars(
                                        $service['Description']
                                    ) ?>

                                </td>



                                <!-- Price -->

                                <td>

                                    LKR
                                    <?= number_format(
                                        $service['Price'],
                                        2
                                    ) ?>

                                </td>



                                <!-- Status -->

                                <td>

                                    <?= htmlspecialchars(
                                        $service['Status']
                                    ) ?>

                                </td>



                                <!-- Actions -->

                                <td>


                                    <!-- Edit -->

                                    <a
                                        href="index.php?page=edit_service&id=<?= urlencode(
                                            $service['ServiceID']
                                        ) ?>"
                                    >

                                        Edit

                                    </a>



                                    <?php if (
                                        $service['Status']
                                        === 'Active'
                                    ): ?>


                                        <!-- Deactivate -->

                                        <form
                                            method="POST"
                                            action="index.php?page=deactivate_service"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to deactivate this service?');"
                                        >


                                            <input
                                                type="hidden"
                                                name="serviceID"
                                                value="<?= htmlspecialchars(
                                                    $service['ServiceID']
                                                ) ?>"
                                            >


                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                            >

                                                Deactivate

                                            </button>


                                        </form>


                                    <?php endif; ?>


                                </td>


                            </tr>

                        <?php endforeach; ?>


                    </tbody>

                </table>

            </div>


        <?php endif; ?>

    </div>



    <!-- Back to Dashboard -->

    <div class="dashboard-links">

        <a
            href="index.php?page=vendor_dashboard"
            class="back-button"
        >

            Back to Vendor Dashboard

        </a>

    </div>


</div>

</body>

</html>