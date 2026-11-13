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

    <title>My Bookings - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

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

    <div class="bookings-header">

        <div>

            <h1>
                My Bookings
            </h1>

            <p>
                View and manage your vendor booking requests.
            </p>

        </div>

        <a
            href="index.php?page=my_events"
            class="back-button"
        >
            ← My Events
        </a>

    </div>


    <?php if (empty($bookings)): ?>

        <div class="card empty-message">

            <h2>
                No Bookings Yet
            </h2>

            <p>
                Your vendor booking requests will appear here.
            </p>

            <a
                href="index.php?page=my_events"
                class="btn btn-primary"
            >
                View My Events
            </a>

        </div>

    <?php else: ?>

        <div class="booking-list">

            <?php foreach ($bookings as $booking): ?>

                <?php

                    $status =
                        $booking['Status']
                        ?? 'Pending';

                    $statusClass =
                        'status-' .
                        strtolower(
                            $status
                        );

                ?>

                <div class="card booking-card">

                    <div class="booking-title-row">

                        <h2>
                            <?= htmlspecialchars(
                                $booking['EventName']
                            ) ?>
                        </h2>

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


                    <div class="booking-info">

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
                                Vendor
                            </span>

                            <span class="info-value">
                                <?= htmlspecialchars(
                                    $booking['BusinessName']
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
                                Booking Date
                            </span>

                            <span class="info-value">
                                <?= htmlspecialchars(
                                    $booking['BookingDate']
                                ) ?>
                            </span>

                        </div>


                        <div class="info-box">

                            <span class="info-label">
                                Total Amount
                            </span>

                            <span class="info-value">
                                LKR
                                <?= number_format(
                                    (float)
                                    $booking['TotalAmount'],
                                    2
                                ) ?>
                            </span>

                        </div>


                        <div class="info-box">

                            <span class="info-label">
                                Notes
                            </span>

                            <span class="info-value">

                                <?php if (
                                    !empty(
                                        $booking['Notes']
                                    )
                                ): ?>

                                    <?= htmlspecialchars(
                                        $booking['Notes']
                                    ) ?>

                                <?php else: ?>

                                    No notes

                                <?php endif; ?>

                            </span>

                        </div>

                    </div>


                    <div class="button-group">

                        <a
                            href="index.php?page=view_booking&id=<?= urlencode(
                                $booking['BookingID']
                            ) ?>"
                            class="view-button"
                        >
                            View Booking
                        </a>


                        <?php if (
                            $status === 'Accepted' ||
                            $status === 'Completed'
                        ): ?>

                            <a
                                href="index.php?page=make_payment&id=<?= urlencode(
                                    $booking['BookingID']
                                ) ?>"
                                class="payment-button"
                            >
                                Make Payment
                            </a>

                        <?php endif; ?>


                        <?php if (
                            $status === 'Completed'
                        ): ?>

                            <a
                                href="index.php?page=review_booking&id=<?= urlencode(
                                    $booking['BookingID']
                                ) ?>"
                                class="review-button"
                            >
                                Write Review
                            </a>

                            <a
                                href="index.php?page=event_summary&id=<?= urlencode(
                                    $booking['EventID']
                                ) ?>"
                                class="summary-button"
                            >
                                Event Summary
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

</body>

</html>
