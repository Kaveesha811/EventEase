<?php

/*
|--------------------------------------------------------------------------
| Vendor Booking Management
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header(
        'Location: index.php?page=login'
    );

    exit;
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

    <title>Booking Management | EventEase</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
            color: #666;
        }

        .message {
            background: #e8f5e9;
            color: #256029;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .booking-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .booking-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 15px;
        }

        .booking-id {
            font-size: 18px;
            font-weight: bold;
        }

        .status {
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-cancelled {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-completed {
            background: #cce5ff;
            color: #004085;
        }

        .details {
            display: grid;
            grid-template-columns:
                repeat(2, 1fr);
            gap: 15px;
        }

        .detail {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
        }

        .detail strong {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .amount {
            font-size: 20px;
            font-weight: bold;
        }

        .notes {
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .actions form {
            display: inline;
        }

        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-accept {
            background: #198754;
            color: white;
        }

        .btn-reject {
            background: #dc3545;
            color: white;
        }

        .btn-complete {
            background: #0d6efd;
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .empty {
            background: #ffffff;
            padding: 40px;
            text-align: center;
            border-radius: 12px;
            color: #666;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #0d6efd;
            font-weight: bold;
        }

        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .details {
                grid-template-columns: 1fr;
            }

            .booking-top {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>Booking Management</h1>

        <p>
            View and manage booking requests from customers.
        </p>

    </div>


    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <?php if (empty($bookings)): ?>

        <div class="empty">

            <h3>No Booking Requests</h3>

            <p>
                You currently have no booking requests.
            </p>

        </div>

    <?php else: ?>


        <?php foreach ($bookings as $booking): ?>

            <?php

            $status =
                $booking['Status'];

            $statusClass =
                'status-' .
                strtolower($status);

            ?>

            <div class="booking-card">

                <div class="booking-top">

                    <div class="booking-id">

                        Booking #<?= htmlspecialchars(
                            $booking['BookingID']
                        ) ?>

                    </div>

                    <span
                        class="status <?= htmlspecialchars(
                            $statusClass
                        ) ?>"
                    >

                        <?= htmlspecialchars(
                            $status
                        ) ?>

                    </span>

                </div>


                <div class="details">

                    <div class="detail">

                        <strong>Customer</strong>

                        <?= htmlspecialchars(
                            $booking['FirstName']
                            . ' '
                            . $booking['LastName']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Email</strong>

                        <?= htmlspecialchars(
                            $booking['Email']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Phone</strong>

                        <?= htmlspecialchars(
                            $booking['Phone']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Event</strong>

                        <?= htmlspecialchars(
                            $booking['EventName']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Event Date</strong>

                        <?= htmlspecialchars(
                            $booking['EventDate']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Location</strong>

                        <?= htmlspecialchars(
                            $booking['Location']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Guest Count</strong>

                        <?= htmlspecialchars(
                            $booking['GuestCount']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Booking Request Date</strong>

                        <?= htmlspecialchars(
                            $booking['BookingDate']
                        ) ?>

                    </div>


                    <div class="detail">

                        <strong>Total Amount</strong>

                        <span class="amount">

                            LKR
                            <?= number_format(
                                $booking['TotalAmount'],
                                2
                            ) ?>

                        </span>

                    </div>

                </div>


                <?php if (
                    !empty($booking['Notes'])
                ): ?>

                    <div class="notes">

                        <strong>Customer Notes:</strong>

                        <?= htmlspecialchars(
                            $booking['Notes']
                        ) ?>

                    </div>

                <?php endif; ?>


                <?php if (
                    $status === 'Pending'
                ): ?>

                    <div class="actions">

                        <form
                            method="POST"
                            action="index.php?page=update_booking_status"
                        >

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= htmlspecialchars(
                                    $booking['BookingID']
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Accepted"
                            >

                            <button
                                type="submit"
                                class="btn btn-accept"
                            >
                                Accept
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="index.php?page=update_booking_status"
                        >

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= htmlspecialchars(
                                    $booking['BookingID']
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Rejected"
                            >

                            <button
                                type="submit"
                                class="btn btn-reject"
                            >
                                Reject
                            </button>

                        </form>

                    </div>

                <?php elseif (
                    $status === 'Accepted'
                ): ?>

                    <div class="actions">

                        <form
                            method="POST"
                            action="index.php?page=update_booking_status"
                        >

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= htmlspecialchars(
                                    $booking['BookingID']
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Completed"
                            >

                            <button
                                type="submit"
                                class="btn btn-complete"
                            >
                                Mark as Completed
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="index.php?page=update_booking_status"
                        >

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= htmlspecialchars(
                                    $booking['BookingID']
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="status"
                                value="Cancelled"
                            >

                            <button
                                type="submit"
                                class="btn btn-cancel"
                            >
                                Cancel Booking
                            </button>

                        </form>

                    </div>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>


    <a
        href="index.php?page=vendor_dashboard"
        class="back"
    >
        ← Back to Vendor Dashboard
    </a>

</div>

</body>

</html>