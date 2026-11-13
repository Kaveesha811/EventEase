<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Booking Details - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        /* Page Header */

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 20px;

            margin-bottom: 25px;
        }

        .booking-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .booking-header p {
            margin: 0;
            color: #6b7280;
        }


        /* Section Cards */

        .detail-section {
            margin-bottom: 20px;
        }

        .detail-section h2 {
            margin: 0 0 20px;
            font-size: 20px;
        }


        /* Booking Top */

        .booking-top {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            padding-bottom: 18px;
            margin-bottom: 20px;

            border-bottom: 1px solid #e5e7eb;
        }

        .booking-id {
            font-size: 18px;
            font-weight: bold;
        }


        /* Detail Grid */

        .details-grid {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 12px;
        }

        .detail-box {
            padding: 15px;

            background: #f8fafc;

            border: 1px solid #eef2f7;
            border-radius: 8px;
        }

        .detail-label {
            display: block;

            margin-bottom: 6px;

            color: #64748b;

            font-size: 12px;
            font-weight: bold;
        }

        .detail-value {
            display: block;

            color: #111827;

            font-size: 14px;
            line-height: 1.5;
        }


        /* Items Table */

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            padding: 13px;

            border-bottom: 1px solid #e5e7eb;

            text-align: left;
        }

        .items-table th {
            background: #f3f4f6;

            font-size: 13px;
        }

        .items-table td {
            font-size: 14px;
        }

        .items-table .amount {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #d1d5db;
        }


        /* Notes */

        .notes {
            padding: 16px;

            background: #f8fafc;

            border: 1px solid #eef2f7;
            border-radius: 8px;

            color: #374151;

            line-height: 1.6;
        }


        /* Payment Information */

        .payment-box {
            padding: 18px;

            background: #eff6ff;

            border: 1px solid #bfdbfe;
            border-radius: 8px;
        }

        .payment-box p {
            margin: 0 0 12px;

            line-height: 1.6;
        }

        .payment-box p:last-child {
            margin-bottom: 0;
        }

        .payment-status {
            display: inline-block;

            padding: 5px 10px;

            background: #fef3c7;
            color: #92400e;

            border-radius: 15px;

            font-size: 12px;
            font-weight: bold;
        }


        /* Empty Items */

        .no-items {
            margin: 0;
            color: #6b7280;
        }


        @media (max-width: 700px) {

            .booking-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .booking-header h1 {
                font-size: 27px;
            }

            .booking-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            .items-table {
                min-width: 650px;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <h2>
        EventEase
    </h2>

    <div class="navbar-links">

        <a href="index.php?page=customer_dashboard">
            Dashboard
        </a>

        <a href="index.php?page=my_events">
            My Events
        </a>

        <a href="index.php?page=my_bookings">
            Bookings
        </a>

        <a href="index.php?page=my_payments">
            Payments
        </a>

        <a href="index.php?page=logout">
            Logout
        </a>

    </div>

</nav>


<main class="container">

    <div class="booking-header">

        <div>

            <h1>
                Booking Details
            </h1>

            <p>
                Review your booking, selected items
                and payment information.
            </p>

        </div>

        <a
            href="index.php?page=my_bookings"
            class="back-button"
        >
            ← My Bookings
        </a>

    </div>


    <!-- Booking Information -->

    <section class="card detail-section">

        <div class="booking-top">

            <div class="booking-id">

                Booking #<?= htmlspecialchars(
                    $booking['BookingID']
                ) ?>

            </div>


            <?php

                $status =
                    $booking['Status'] ?? 'Pending';

                $statusClass =
                    'status-' .
                    strtolower($status);

            ?>

            <span
                class="status
                <?= htmlspecialchars(
                    $statusClass
                ) ?>"
            >
                <?= htmlspecialchars(
                    $status
                ) ?>
            </span>

        </div>


        <h2>
            Booking Information
        </h2>


        <div class="details-grid">

            <div class="detail-box">

                <span class="detail-label">
                    Booking Date
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['BookingDate']
                    ) ?>
                </span>

            </div>


            <div class="detail-box">

                <span class="detail-label">
                    Total Amount
                </span>

                <span class="detail-value">
                    LKR
                    <?= number_format(
                        (float)$booking['TotalAmount'],
                        2
                    ) ?>
                </span>

            </div>


            <div class="detail-box">

                <span class="detail-label">
                    Vendor
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['BusinessName']
                    ) ?>
                </span>

            </div>


            <div class="detail-box">

                <span class="detail-label">
                    Vendor Address
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['BusinessAddress']
                    ) ?>
                </span>

            </div>

        </div>

    </section>


    <!-- Event Information -->

    <section class="card detail-section">

        <h2>
            Event Information
        </h2>


        <div class="details-grid">

            <div class="detail-box">

                <span class="detail-label">
                    Event Name
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['EventName']
                    ) ?>
                </span>

            </div>


            <div class="detail-box">

                <span class="detail-label">
                    Event Date
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['EventDate']
                    ) ?>
                </span>

            </div>


            <div class="detail-box">

                <span class="detail-label">
                    Location
                </span>

                <span class="detail-value">
                    <?= htmlspecialchars(
                        $booking['Location']
                    ) ?>
                </span>

            </div>

        </div>

    </section>


    <!-- Selected Services and Packages -->

    <section class="card detail-section">

        <h2>
            Selected Services & Packages
        </h2>


        <?php if (!empty($bookingItems)): ?>

            <div class="table-wrapper">

                <table class="items-table">

                    <thead>

                        <tr>

                            <th>
                                Item
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th class="amount">
                                Unit Price
                            </th>

                            <th class="amount">
                                Sub Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $bookingItems
                            as $item
                        ): ?>

                            <?php

                                if (
                                    !empty(
                                        $item['ServiceID']
                                    )
                                ) {

                                    $itemName =
                                        $item['ServiceName'];

                                    $itemType =
                                        'Service';

                                } else {

                                    $itemName =
                                        $item['PackageName'];

                                    $itemType =
                                        'Package';

                                }

                            ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        $itemName
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $itemType
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['Quantity']
                                    ) ?>
                                </td>

                                <td class="amount">
                                    LKR
                                    <?= number_format(
                                        (float)$item['UnitPrice'],
                                        2
                                    ) ?>
                                </td>

                                <td class="amount">
                                    LKR
                                    <?= number_format(
                                        (float)$item['SubTotal'],
                                        2
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>


                        <tr class="total-row">

                            <td colspan="4">
                                Total Amount
                            </td>

                            <td class="amount">
                                LKR
                                <?= number_format(
                                    (float)$booking['TotalAmount'],
                                    2
                                ) ?>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <p class="no-items">
                No booking items found.
            </p>

        <?php endif; ?>

    </section>


    <!-- Customer Notes -->

    <section class="card detail-section">

        <h2>
            Customer Notes
        </h2>


        <div class="notes">

            <?php

                if (
                    !empty(
                        $booking['Notes']
                    )
                ) {

                    echo nl2br(
                        htmlspecialchars(
                            $booking['Notes']
                        )
                    );

                } else {

                    echo 'No notes were added to this booking.';

                }

            ?>

        </div>

    </section>


    <!-- Payment Information -->

    <section class="card detail-section">

        <h2>
            Payment Information
        </h2>


        <div class="payment-box">

            <p>

                <strong>
                    Payment Method:
                </strong>

                Bank Transfer

            </p>


            <p>

                <strong>
                    Payment Status:
                </strong>

                <span class="payment-status">
                    Pending
                </span>

            </p>


            <p>
                Payment records can be viewed here
                once a payment has been recorded.
            </p>

        </div>

    </section>

</main>

</body>

</html>
