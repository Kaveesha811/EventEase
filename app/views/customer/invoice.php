<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Customer'
) {
    header('Location: index.php?page=login');
    exit;
}

$bookings = $bookings ?? [];
$event = $event ?? null;
$invoiceItems = $invoiceItems ?? [];
$totalAmount = 0;
$paidAmount = $totalPaidAmount ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Event Summary - EventEase</title>

    </style>

    <link rel="stylesheet" href="css/style.css">
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 9px 15px;
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
            border-radius: 6px;
        }

        .header,
        .card,
        .summary {
            background: white;
            border-radius: 10px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        h1 {
            margin: 0 0 8px;
        }

        .muted {
            color: #6b7280;
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 18px;
        }

        .event-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 7px;
        }

        .event-box strong {
            display: block;
            margin-bottom: 6px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .amount {
            white-space: nowrap;
            font-weight: 600;
        }

        .vendor {
            font-weight: 600;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .grand-total {
            font-size: 21px;
            font-weight: bold;
            padding-top: 15px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        @media print {
            .navbar,
            .back {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <h2>EventEase</h2>

    <div>
        <a href="index.php?page=customer_dashboard">
            Dashboard
        </a>

        <a href="index.php?page=my_events">
            My Events
        </a>

        <a href="index.php?page=my_bookings">
            My Bookings
        </a>

        <a href="index.php?page=logout">
            Logout
        </a>
    </div>
</nav>

<div class="container">

    <a
        href="index.php?page=my_bookings"
        class="back"
    >
        ← Back to My Bookings
    </a>

    <?php if (!$event || empty($bookings)): ?>

        <div class="card empty">
            <h2>Event Summary Not Found</h2>
            <p>
                No booking information was found for this event.
            </p>
        </div>

    <?php else: ?>

        <div class="header">

            <h1>
                Consolidated Event Summary
            </h1>

            <p class="muted">
                Booking summary for your complete event package.
            </p>

            <div class="event-grid">

                <div class="event-box">
                    <strong>Event</strong>
                    <?= htmlspecialchars($event['EventName']) ?>
                </div>

                <div class="event-box">
                    <strong>Event Date</strong>
                    <?= htmlspecialchars($event['EventDate']) ?>
                </div>

                <div class="event-box">
                    <strong>Location</strong>
                    <?= htmlspecialchars($event['Location']) ?>
                </div>

                <div class="event-box">
                    <strong>Vendors</strong>
                    <?= count($bookings) ?>
                </div>

            </div>

        </div>

        <?php foreach ($bookings as $booking): ?>

            <div class="card">

                <h2>
                    Booking #<?= htmlspecialchars($booking['BookingID']) ?>
                </h2>

                <p class="muted">
                    Vendor:
                    <span class="vendor">
                        <?= htmlspecialchars($booking['BusinessName']) ?>
                    </span>
                    &nbsp; | &nbsp;
                    Status:
                    <span class="status">
                        <?= htmlspecialchars($booking['Status']) ?>
                    </span>
                </p>

                <div class="table-wrap">

                    <table>

                        <thead>
                            <tr>
                                <th>Service / Package</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach (
                            ($booking['Items'] ?? [])
                            as $item
                        ): ?>

                            <?php
                            $itemName =
                                !empty($item['ServiceName'])
                                ? $item['ServiceName']
                                : ($item['PackageName'] ?? 'Package');

                            $totalAmount +=
                                (float) $item['SubTotal'];
                            ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($itemName) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['Quantity']) ?>
                                </td>

                                <td class="amount">
                                    LKR
                                    <?= number_format(
                                        (float) $item['UnitPrice'],
                                        2
                                    ) ?>
                                </td>

                                <td class="amount">
                                    LKR
                                    <?= number_format(
                                        (float) $item['SubTotal'],
                                        2
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <p class="amount">
                    Booking Total:
                    LKR
                    <?= number_format(
                        (float) $booking['TotalAmount'],
                        2
                    ) ?>
                </p>

                <p class="muted">
                    Payment Status:
                    <?= htmlspecialchars(
                        $booking['PaymentStatus'] ?? 'Pending'
                    ) ?>
                    <?php if (
                        isset($booking['PaidAmount'])
                    ): ?>
                        | Paid:
                        LKR
                        <?= number_format(
                            (float) $booking['PaidAmount'],
                            2
                        ) ?>
                    <?php endif; ?>
                </p>

            </div>

        <?php endforeach; ?>

        <div class="summary">

            <div class="total-row">
                <span>Total Event Amount</span>

                <strong>
                    LKR
                    <?= number_format($totalAmount, 2) ?>
                </strong>
            </div>

            <div class="total-row">
                <span>Total Paid</span>

                <strong>
                    LKR
                    <?= number_format($paidAmount, 2) ?>
                </strong>
            </div>

            <div class="total-row grand-total">
                <span>Balance Due</span>

                <strong>
                    LKR
                    <?= number_format(
                        max(0, $totalAmount - $paidAmount),
                        2
                    ) ?>
                </strong>
            </div>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
