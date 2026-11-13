<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header(
        'Location: index.php?page=login'
    );
    exit;
}

$transactions = $transactions ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Transaction History - EventEase</title>

    <style>

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
            font-size: 22px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .nav-links a:hover {
            background: #374151;
        }

        .container {
            max-width: 1400px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            background: #e5e7eb;
            color: #111827;
            padding: 9px 15px;
            border-radius: 6px;
        }

        .back-button:hover {
            background: #d1d5db;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1150px;
        }

        th {
            background: #f3f4f6;
            color: #374151;
            text-align: left;
            padding: 13px;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        tr:hover {
            background: #f9fafb;
        }

        .amount {
            font-weight: 600;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .booking-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .booking-accepted {
            background: #dbeafe;
            color: #1e40af;
        }

        .booking-completed {
            background: #dcfce7;
            color: #166534;
        }

        .booking-rejected,
        .booking-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .payment-completed {
            background: #dcfce7;
            color: #166534;
        }

        .payment-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-pending {
            background: #fee2e2;
            color: #991b1b;
        }

        .customer-name {
            font-weight: 600;
        }

        .customer-contact {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #6b7280;
        }

        .empty h3 {
            margin-bottom: 8px;
            color: #374151;
        }

        @media (max-width: 768px) {

            .navbar {
                padding: 15px;
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .container {
                margin: 25px auto;
                padding: 0 12px;
            }

            .page-header h1 {
                font-size: 25px;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <nav class="navbar">

        <h2>EventEase</h2>

        <div class="nav-links">

            <a href="index.php?page=vendor_dashboard">
                Dashboard
            </a>

            <a href="index.php?page=logout">
                Logout
            </a>

        </div>

    </nav>


    <div class="container">

        <a
            href="index.php?page=vendor_dashboard"
            class="back-button"
        >
            ← Back to Dashboard
        </a>


        <div class="page-header">

            <h1>Transaction History</h1>

            <p>
                View your booking and payment transaction history.
            </p>

        </div>


        <div class="table-card">

            <?php if (empty($transactions)): ?>

                <div class="empty">

                    <h3>No Transactions Found</h3>

                    <p>
                        You do not have any booking transactions yet.
                    </p>

                </div>

            <?php else: ?>

                <table>

                    <thead>

                        <tr>

                            <th>Booking</th>

                            <th>Customer</th>

                            <th>Event</th>

                            <th>Event Date</th>

                            <th>Booking Amount</th>

                            <th>Paid Amount</th>

                            <th>Payment Method</th>

                            <th>Payment Status</th>

                            <th>Booking Status</th>

                            <th>Last Payment</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($transactions as $transaction): ?>

                            <?php

                            $bookingStatus =
                                $transaction['BookingStatus']
                                ?? 'Pending';

                            $paymentStatus =
                                $transaction['PaymentStatus']
                                ?? 'Pending';

                            $bookingStatusClass =
                                'booking-' .
                                strtolower(
                                    $bookingStatus
                                );

                            $paymentStatusClass =
                                match ($paymentStatus) {
                                    'Completed' =>
                                        'payment-completed',

                                    'Partially Paid' =>
                                        'payment-partial',

                                    default =>
                                        'payment-pending'
                                };

                            ?>

                            <tr>

                                <td>
                                    <strong>
                                        #<?= htmlspecialchars(
                                            $transaction['BookingID']
                                        ) ?>
                                    </strong>

                                    <div class="customer-contact">
                                        <?= htmlspecialchars(
                                            $transaction['BookingDate']
                                        ) ?>
                                    </div>
                                </td>


                                <td>

                                    <div class="customer-name">

                                        <?= htmlspecialchars(
                                            $transaction['FirstName']
                                        ) ?>

                                        <?= htmlspecialchars(
                                            $transaction['LastName']
                                        ) ?>

                                    </div>

                                    <div class="customer-contact">

                                        <?= htmlspecialchars(
                                            $transaction['Email']
                                        ) ?>

                                    </div>

                                    <div class="customer-contact">

                                        <?= htmlspecialchars(
                                            $transaction['Phone']
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $transaction['EventName']
                                        ) ?>
                                    </strong>

                                    <div class="customer-contact">

                                        <?= htmlspecialchars(
                                            $transaction['Location']
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $transaction['EventDate']
                                    ) ?>

                                </td>


                                <td class="amount">

                                    LKR
                                    <?= number_format(
                                        (float)
                                        $transaction['TotalAmount'],
                                        2
                                    ) ?>

                                </td>


                                <td class="amount">

                                    LKR
                                    <?= number_format(
                                        (float)
                                        $transaction['PaidAmount'],
                                        2
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $transaction['PaymentMethods']
                                        ?? 'Not Paid'
                                    ) ?>

                                </td>


                                <td>

                                    <span
                                        class="status
                                        <?= $paymentStatusClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $paymentStatus
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <span
                                        class="status
                                        <?= $bookingStatusClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $bookingStatus
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?php if (
                                        !empty(
                                            $transaction['LastPaymentDate']
                                        )
                                    ): ?>

                                        <?= htmlspecialchars(
                                            $transaction[
                                                'LastPaymentDate'
                                            ]
                                        ) ?>

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>