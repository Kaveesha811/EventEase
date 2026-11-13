<?php

/*
|--------------------------------------------------------------------------
| ADMIN PAYMENT MANAGEMENT
|--------------------------------------------------------------------------
|
| Variables received from public/index.php:
|
| $payments
| $message
|
*/

$payments = $payments ?? [];
$message = $message ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Payment Management - EventEase</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 35px;
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
            text-decoration: none;
            color: white;
            background: #374151;
            padding: 9px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: #4b5563;
        }

        .container {
            width: 95%;
            max-width: 1500px;
            margin: 30px auto;
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #666;
        }

        .message {
            background: #e8f5e9;
            color: #256029;
            border: 1px solid #b7dfb9;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .summary-card h3 {
            margin: 0 0 8px;
            font-size: 15px;
            color: #666;
        }

        .summary-card strong {
            font-size: 25px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1250px;
        }

        th {
            background: #1f2937;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            white-space: nowrap;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 13px;
        }

        tr:hover {
            background: #f9fafb;
        }

        .customer-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .small-text {
            color: #666;
            line-height: 1.5;
        }

        .amount {
            font-weight: bold;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .booking-status {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 5px;
            background: #e5e7eb;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
        }

        .slip-link {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 5px;
            font-size: 12px;
        }

        .slip-link:hover {
            background: #1d4ed8;
        }

        .no-slip {
            color: #888;
            font-style: italic;
        }

        .action-form {
            margin: 0;
        }

        .complete-button {
            border: none;
            background: #198754;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .complete-button:hover {
            background: #157347;
        }

        .completed-text {
            color: #198754;
            font-weight: bold;
            font-size: 12px;
        }

        .empty-state {
            background: white;
            padding: 50px 25px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .empty-state h2 {
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #666;
        }

        .footer-note {
            margin-top: 20px;
            color: #777;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 800px) {

            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .container {
                width: 94%;
            }

            .summary {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 23px;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<!--
|--------------------------------------------------------------------------
| NAVIGATION BAR
|--------------------------------------------------------------------------
-->

<div class="navbar">

    <h2>EventEase - Admin</h2>

    <div class="nav-links">

        <a href="index.php?page=admin_dashboard">
            Dashboard
        </a>

        <a href="index.php?page=logout">
            Logout
        </a>

    </div>

</div>


<!--
|--------------------------------------------------------------------------
| MAIN CONTENT
|--------------------------------------------------------------------------
-->

<div class="container">


    <div class="page-header">

        <h1>Payment Management</h1>

        <p>
            Review customer bank transfer payments
            and verify pending payments.
        </p>

    </div>


    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <?php

    /*
     * Calculate summary values
     */

    $totalPayments = count($payments);

    $pendingPayments = 0;

    $completedPayments = 0;

    $totalCompletedAmount = 0;

    foreach ($payments as $payment) {

        if ($payment['PaymentStatus'] === 'Pending') {

            $pendingPayments++;

        }

        if ($payment['PaymentStatus'] === 'Completed') {

            $completedPayments++;

            $totalCompletedAmount +=
                (float) $payment['Amount'];
        }
    }

    ?>


    <!--
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    -->

    <div class="summary">

        <div class="summary-card">

            <h3>Total Payments</h3>

            <strong>
                <?= $totalPayments ?>
            </strong>

        </div>


        <div class="summary-card">

            <h3>Pending Payments</h3>

            <strong>
                <?= $pendingPayments ?>
            </strong>

        </div>


        <div class="summary-card">

            <h3>Completed Payment Amount</h3>

            <strong>
                LKR
                <?= number_format($totalCompletedAmount, 2) ?>
            </strong>

        </div>

    </div>


    <?php if (empty($payments)): ?>


        <div class="empty-state">

            <h2>No Payments Found</h2>

            <p>
                There are currently no payment records
                in the system.
            </p>

        </div>


    <?php else: ?>


        <!--
        |--------------------------------------------------------------------------
        | PAYMENT TABLE
        |--------------------------------------------------------------------------
        -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            Payment
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Event
                        </th>

                        <th>
                            Vendor
                        </th>

                        <th>
                            Booking
                        </th>

                        <th>
                            Payment Amount
                        </th>

                        <th>
                            Method
                        </th>

                        <th>
                            Reference
                        </th>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Slip
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php foreach ($payments as $payment): ?>


                    <tr>


                        <!-- Payment ID -->

                        <td>

                            <strong>
                                #<?= (int) $payment['PaymentID'] ?>
                            </strong>

                            <div class="small-text">

                                Booking
                                #<?= (int) $payment['BookingID'] ?>

                            </div>

                        </td>


                        <!-- Customer -->

                        <td>

                            <div class="customer-name">

                                <?= htmlspecialchars(
                                    $payment['FirstName']
                                    . ' '
                                    . $payment['LastName']
                                ) ?>

                            </div>

                            <div class="small-text">

                                <?= htmlspecialchars(
                                    $payment['Email']
                                ) ?>

                                <br>

                                <?= htmlspecialchars(
                                    $payment['Phone']
                                ) ?>

                            </div>

                        </td>


                        <!-- Event -->

                        <td>

                            <strong>

                                <?= htmlspecialchars(
                                    $payment['EventName']
                                ) ?>

                            </strong>

                            <div class="small-text">

                                Date:
                                <?= htmlspecialchars(
                                    $payment['EventDate']
                                ) ?>

                                <br>

                                Location:
                                <?= htmlspecialchars(
                                    $payment['Location']
                                ) ?>

                            </div>

                        </td>


                        <!-- Vendor -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['BusinessName']
                            ) ?>

                        </td>


                        <!-- Booking -->

                        <td>

                            <span class="booking-status">

                                <?= htmlspecialchars(
                                    $payment['BookingStatus']
                                ) ?>

                            </span>

                            <div class="small-text">

                                Total:
                                LKR
                                <?= number_format(
                                    (float) $payment['TotalAmount'],
                                    2
                                ) ?>

                            </div>

                        </td>


                        <!-- Payment Amount -->

                        <td>

                            <span class="amount">

                                LKR
                                <?= number_format(
                                    (float) $payment['Amount'],
                                    2
                                ) ?>

                            </span>

                        </td>


                        <!-- Payment Method -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['PaymentMethod']
                            ) ?>

                        </td>


                        <!-- Reference Number -->

                        <td>

                            <strong>

                                <?= htmlspecialchars(
                                    $payment['ReferenceNo']
                                ) ?>

                            </strong>

                        </td>


                        <!-- Payment Date -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['PaymentDate']
                            ) ?>

                        </td>


                        <!-- Payment Slip -->

                        <td>

                            <?php if (
                                !empty(
                                    $payment['PaymentSlip']
                                )
                            ): ?>


                                <?php

                                /*
                                 * Use basename() to prevent
                                 * directory traversal.
                                 */

                                $slipFile =
                                    basename(
                                        $payment['PaymentSlip']
                                    );

                                $slipPath =
                                    '../uploads/payment_slips/'
                                    . $slipFile;

                                ?>


                                <a
                                    href="<?= htmlspecialchars($slipPath) ?>"
                                    target="_blank"
                                    class="slip-link"
                                >
                                    View Slip
                                </a>


                            <?php else: ?>


                                <span class="no-slip">

                                    No slip uploaded

                                </span>


                            <?php endif; ?>

                        </td>


                        <!-- Payment Status -->

                        <td>

                            <?php if (
                                $payment['PaymentStatus']
                                === 'Pending'
                            ): ?>


                                <span class="status status-pending">

                                    Pending

                                </span>


                            <?php else: ?>


                                <span class="status status-completed">

                                    Completed

                                </span>


                            <?php endif; ?>

                        </td>


                        <!-- Action -->

                        <td>

                            <?php if (
                                $payment['PaymentStatus']
                                === 'Pending'
                            ): ?>


                                <form
                                    method="POST"
                                    action="index.php?page=admin_update_payment"
                                    class="action-form"
                                    onsubmit="
                                        return confirm(
                                            'Are you sure you want to mark this payment as Completed?'
                                        );
                                    "
                                >

                                    <input
                                        type="hidden"
                                        name="payment_id"
                                        value="<?= (int) $payment['PaymentID'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="payment_status"
                                        value="Completed"
                                    >

                                    <button
                                        type="submit"
                                        class="complete-button"
                                    >
                                        Mark Completed
                                    </button>

                                </form>


                            <?php else: ?>


                                <span class="completed-text">

                                    Verified

                                </span>


                            <?php endif; ?>

                        </td>


                    </tr>


                <?php endforeach; ?>


                </tbody>

            </table>

        </div>


    <?php endif; ?>


    <div class="footer-note">

        EventEase Payment Management System

    </div>


</div>


</body>

</html>