<?php

/*
|--------------------------------------------------------------------------
| Payment Message
|--------------------------------------------------------------------------
*/

$message =
    $_SESSION['payment_message']
    ?? '';

unset(
    $_SESSION['payment_message']
);


/*
|--------------------------------------------------------------------------
| Calculate Completed Payment Amount
|--------------------------------------------------------------------------
*/

$paidAmount = 0;

if (!empty($payments)) {

    foreach ($payments as $payment) {

        if (
            $payment['PaymentStatus']
            === 'Completed'
        ) {

            $paidAmount +=
                (float) $payment['Amount'];
        }
    }
}


/*
|--------------------------------------------------------------------------
| Booking Amounts
|--------------------------------------------------------------------------
*/

$bookingTotal =
    (float) $booking['TotalAmount'];

$remainingAmount =
    $bookingTotal -
    $paidAmount;

if ($remainingAmount < 0) {

    $remainingAmount = 0;

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Make Payment - EventEase
    </title>

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

        /* Navigation */

        .navbar {
            background: #111827;
            color: white;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Main content */

        .container {
            width: 92%;
            max-width: 1000px;
            margin: 35px auto;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .page-header h1 {
            margin: 0 0 10px;
            font-size: 30px;
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            background: #e5e7eb;
            color: #111827;
            padding: 9px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-button:hover {
            background: #d1d5db;
        }

        /* Cards */

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.05);
        }

        .card h2 {
            margin: 0 0 18px;
            font-size: 20px;
        }

        .card p {
            line-height: 1.6;
            margin-bottom: 12px;
        }

        /* Booking details */

        .booking-details {
            display: grid;
            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(210px, 1fr)
                );
            gap: 12px;
        }

        .info-box {
            background: #f9fafb;
            padding: 14px;
            border-radius: 7px;
        }

        .info-label {
            display: block;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            display: block;
            color: #111827;
            font-weight: bold;
        }

        /* Amount summary */

        .amount-box {
            display: grid;
            grid-template-columns:
                repeat(3, 1fr);
            gap: 12px;
            margin-top: 18px;
        }

        .amount-item {
            background: #f9fafb;
            padding: 16px;
            border-radius: 7px;
            text-align: center;
        }

        .amount-item span {
            display: block;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .amount-item strong {
            font-size: 18px;
        }

        /* Bank details */

        .bank-details {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 18px;
            border-radius: 7px;
        }

        .bank-details p {
            margin: 0 0 10px;
        }

        .bank-details p:last-child {
            margin-bottom: 0;
        }

        .bank-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        /* Message */

        .message {
            background: #dcfce7;
            color: #166534;
            padding: 13px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        /* Form */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .form-group input[readonly] {
            background: #f3f4f6;
        }

        .form-help {
            display: block;
            margin-top: 6px;
            color: #6b7280;
            font-size: 13px;
        }

        .submit-button {
            border: none;
            background: #059669;
            color: white;
            padding: 11px 20px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #047857;
        }

        /* Payment history */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        table th {
            background: #f3f4f6;
            font-size: 14px;
        }

        table td {
            font-size: 14px;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 700px) {

            .container {
                width: 92%;
            }

            .amount-box {
                grid-template-columns: 1fr;
            }

            .bank-label {
                display: block;
                width: auto;
                margin-bottom: 3px;
            }

            .navbar {
                padding: 16px 5%;
            }

            .navbar-links {
                gap: 12px;
            }

            .page-header h1 {
                font-size: 26px;
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

        <a href="index.php?page=logout">
            Logout
        </a>

    </div>

</nav>


<main class="container">

<!--
    |--------------------------------------------------------------------------
    | Page Header
    |--------------------------------------------------------------------------
    -->

    <div class="page-header">

        <h1>
            Make Payment
        </h1>


        <a
            href="index.php?page=my_bookings"
            class="back-button"
        >
            Back to My Bookings
        </a>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Payment Message
    |--------------------------------------------------------------------------
    -->

    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <!--
    |--------------------------------------------------------------------------
    | Booking Details
    |--------------------------------------------------------------------------
    -->

    <div class="card">

        <h2>
            Booking Details
        </h2>


        <div class="booking-details">


            <div class="info-box">

                <span class="info-label">
                    Booking ID
                </span>

                <span class="info-value">

                    #<?= htmlspecialchars(
                        $booking['BookingID']
                    ) ?>

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Event
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $booking['EventName']
                    ) ?>

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Event Date
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $booking['EventDate']
                    ) ?>

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Vendor
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $booking['BusinessName']
                        ?? 'N/A'
                    ) ?>

                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Booking Status
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $booking['Status']
                    ) ?>

                </span>

            </div>


        </div>


        <!-- Payment Summary -->

        <div class="amount-box">


            <div class="amount-item">

                <span>
                    Booking Total
                </span>

                <strong>

                    LKR
                    <?= number_format(
                        $bookingTotal,
                        2
                    ) ?>

                </strong>

            </div>


            <div class="amount-item">

                <span>
                    Completed Payments
                </span>

                <strong>

                    LKR
                    <?= number_format(
                        $paidAmount,
                        2
                    ) ?>

                </strong>

            </div>


            <div class="amount-item">

                <span>
                    Remaining Amount
                </span>

                <strong>

                    LKR
                    <?= number_format(
                        $remainingAmount,
                        2
                    ) ?>

                </strong>

            </div>


        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Bank Account Details
    |--------------------------------------------------------------------------
    -->

    <div class="card">

        <h2>
            Bank Transfer Details
        </h2>


        <p>
            Please transfer the payment amount
            to the following bank account.
        </p>


        <div class="bank-details">


            <p>

                <span class="bank-label">
                    Account Number:
                </span>

                278200100099534

            </p>


            <p>

                <span class="bank-label">
                    Account Name:
                </span>

                P.R.K.Sewmini

            </p>


            <p>

                <span class="bank-label">
                    Bank:
                </span>

                People's Bank

            </p>


            <p>

                <span class="bank-label">
                    Branch:
                </span>

                Nittabuwa

            </p>


        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Payment Form
    |--------------------------------------------------------------------------
    -->

    <?php if ($remainingAmount > 0): ?>


        <div class="card">

            <h2>
                Submit Bank Transfer Payment
            </h2>


            <form
                action="index.php?page=submit_payment"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- Booking ID -->

                <input
                    type="hidden"
                    name="booking_id"
                    value="<?= htmlspecialchars(
                        $booking['BookingID']
                    ) ?>"
                >


                <!-- Payment Amount -->

                <div class="form-group">

                    <label for="amount">

                        Payment Amount (LKR)

                    </label>


                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        min="1"
                        max="<?= htmlspecialchars(
                            $remainingAmount
                        ) ?>"
                        step="0.01"
                        value="<?= htmlspecialchars(
                            $remainingAmount
                        ) ?>"
                        required
                    >


                    <span class="form-help">

                        Maximum payment amount:
                        LKR
                        <?= number_format(
                            $remainingAmount,
                            2
                        ) ?>

                    </span>

                </div>


                <!-- Payment Method -->

                <div class="form-group">

                    <label for="payment_method">

                        Payment Method

                    </label>


                    <input
                        type="text"
                        id="payment_method"
                        value="Bank Transfer"
                        readonly
                    >

                </div>


                <!-- Reference Number -->

                <div class="form-group">

                    <label for="reference_no">

                        Bank Transfer Reference No.

                    </label>


                    <input
                        type="text"
                        id="reference_no"
                        name="reference_no"
                        placeholder="Enter bank transfer reference number"
                        maxlength="100"
                        required
                    >

                </div>


                <!-- Payment Slip -->

                <div class="form-group">

                    <label for="payment_slip">

                        Payment Slip
                        <span>
                            (Optional)
                        </span>

                    </label>


                    <input
                        type="file"
                        id="payment_slip"
                        name="payment_slip"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >


                    <span class="form-help">

                        Allowed formats:
                        JPG, JPEG, PNG, PDF

                    </span>

                </div>


                <!-- Submit Button -->

                <button
                    type="submit"
                    class="submit-button"
                >

                    Submit Payment

                </button>


            </form>

        </div>


    <?php else: ?>


        <div class="message">

            This booking has been fully paid.

        </div>


    <?php endif; ?>


    <!--
    |--------------------------------------------------------------------------
    | Payment History
    |--------------------------------------------------------------------------
    -->

    <?php if (!empty($payments)): ?>


        <div class="card">

            <h2>
                Payment History
            </h2>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Payment ID
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                Reference No.
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach (
                        $payments
                        as $payment
                    ): ?>


                        <tr>


                            <td>

                                #<?= htmlspecialchars(
                                    $payment['PaymentID']
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $payment['PaymentDate']
                                ) ?>

                            </td>


                            <td>

                                LKR
                                <?= number_format(
                                    $payment['Amount'],
                                    2
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $payment['PaymentMethod']
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $payment['ReferenceNo']
                                ) ?>

                            </td>


                            <td>


                                <?php if (
                                    $payment['PaymentStatus']
                                    === 'Completed'
                                ): ?>


                                    <span
                                        class="status
                                        status-completed"
                                    >

                                        Completed

                                    </span>


                                <?php else: ?>


                                    <span
                                        class="status
                                        status-pending"
                                    >

                                        Pending

                                    </span>


                                <?php endif; ?>


                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>

                </table>

            </div>

        </div>


    <?php endif; ?>

</main>

</body>

</html>
