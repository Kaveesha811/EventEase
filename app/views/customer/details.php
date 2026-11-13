<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Customer'
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

    <title>
        Event Details - EventEase
    </title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .navbar {
            background: #222;
            color: white;
            padding: 18px 40px;
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
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
            text-decoration: none;
        }

        .event-card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .event-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .event-header h1 {
            margin-bottom: 8px;
        }

        .event-type {
            color: #666;
            font-size: 17px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-box {
            background: #f8f8f8;
            padding: 18px;
            border-radius: 7px;
        }

        .detail-box label {
            display: block;
            color: #666;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .detail-box strong {
            font-size: 16px;
        }

        .status {
            display: inline-block;
            margin-top: 25px;
            padding: 8px 14px;
            background: #eee;
            border-radius: 5px;
            font-weight: bold;
        }

        .section {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .section h2 {
            margin-bottom: 8px;
        }

        .section-description {
            color: #666;
            margin-bottom: 20px;
        }

        .service-item {
            background: #f8f8f8;
            padding: 18px;
            border-radius: 7px;
            margin-bottom: 12px;
        }

        .service-item h3 {
            margin-bottom: 7px;
        }

        .service-item p {
            color: #666;
            margin-bottom: 6px;
        }

        .service-price {
            font-weight: bold;
            color: #222 !important;
        }

        .empty-services {
            background: #f8f8f8;
            padding: 25px;
            border-radius: 7px;
            color: #666;
        }

        .budget-box {
            margin-top: 25px;
            background: #f8f8f8;
            padding: 22px;
            border-radius: 8px;
        }

        .budget-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .budget-row.total {
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 15px;
            font-size: 18px;
        }

        .budget-row.remaining {
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 15px;
            font-size: 18px;
        }

        .over-budget {
            color: #c62828;
        }

        .within-budget {
            color: #2e7d32;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #444;
        }

        .remove-btn {
            display: inline-block;
            padding: 9px 15px;
            background: #c62828;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .remove-btn:hover {
            background: #a91f1f;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 6px;
        }

        .booking-form {
            background: #f8f8f8;
            padding: 22px;
            border-radius: 8px;
        }

        .booking-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .booking-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: vertical;
            font-size: 14px;
            font-family: Arial, sans-serif;
            margin-bottom: 15px;
        }

        .booking-form textarea:focus {
            outline: none;
            border-color: #777;
        }

        .booking-note {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        @media (max-width: 650px) {

            .details-grid {
                grid-template-columns: 1fr;
            }

            .budget-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<nav class="navbar">

    <h2>EventEase</h2>

    <a href="index.php?page=customer_dashboard">
        Dashboard
    </a>

</nav>


<div class="container">


    <a
        href="index.php?page=my_events"
        class="back-link"
    >
        ← Back to My Events
    </a>


    <?php if (!empty($event)): ?>


        <div class="event-card">


            <!--
            |--------------------------------------------------------------------------
            | Event Header
            |--------------------------------------------------------------------------
            -->

            <div class="event-header">

                <h1>

                    <?= htmlspecialchars(
                        $event['EventName']
                    ) ?>

                </h1>


                <p class="event-type">

                    <?= htmlspecialchars(
                        $event['EventTypeName']
                    ) ?>

                </p>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | Event Details
            |--------------------------------------------------------------------------
            -->

            <div class="details-grid">


                <div class="detail-box">

                    <label>
                        Event Date
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['EventDate']
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Location
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['Location']
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Guest Count
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['GuestCount']
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Budget
                    </label>

                    <strong>

                        LKR
                        <?= number_format(
                            $event['Budget'],
                            2
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Event Status
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['Status']
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Created Date
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['CreatedAt']
                        ) ?>

                    </strong>

                </div>


            </div>


            <span class="status">

                <?= htmlspecialchars(
                    $event['Status']
                ) ?>

            </span>


            <!--
            |--------------------------------------------------------------------------
            | Event Message
            |--------------------------------------------------------------------------
            -->

            <?php if (
                !empty($_SESSION['event_message'])
            ): ?>

                <div class="message">

                    <?= htmlspecialchars(
                        $_SESSION['event_message']
                    ) ?>

                </div>


                <?php

                unset(
                    $_SESSION['event_message']
                );

                ?>

            <?php endif; ?>


            <!--
            |--------------------------------------------------------------------------
            | Booking Message
            |--------------------------------------------------------------------------
            -->

            <?php if (
                !empty($_SESSION['booking_message'])
            ): ?>

                <div class="message">

                    <?= htmlspecialchars(
                        $_SESSION['booking_message']
                    ) ?>

                </div>


                <?php

                unset(
                    $_SESSION['booking_message']
                );

                ?>

            <?php endif; ?>


            <!--
            |--------------------------------------------------------------------------
            | Selected Services
            |--------------------------------------------------------------------------
            -->

            <div class="section">

                <h2>
                    Selected Services
                </h2>


                <p class="section-description">
                    Services currently added to your event.
                </p>


                <?php if (
                    empty($eventItems)
                ): ?>


                    <div class="empty-services">

                        <p>
                            No services have been added
                            to this event yet.
                        </p>

                        <p style="margin-top: 8px;">

                            Click
                            <strong>
                                Find Services
                            </strong>
                            below to start planning
                            your event.

                        </p>

                    </div>


                <?php else: ?>


                    <?php foreach (
                        $eventItems as $item
                    ): ?>


                        <div class="service-item">


                            <?php if (
                                !empty($item['ServiceID'])
                            ): ?>

                                <h3>

                                    <?= htmlspecialchars(
                                        $item['ServiceName']
                                    ) ?>

                                </h3>


                                <p>

                                    Vendor:
                                    <strong>

                                        <?= htmlspecialchars(
                                            $item['BusinessName']
                                        ) ?>

                                    </strong>

                                </p>


                                <?php if (
                                    !empty(
                                        $item['ServiceDescription']
                                    )
                                ): ?>

                                    <p>

                                        <?= htmlspecialchars(
                                            $item['ServiceDescription']
                                        ) ?>

                                    </p>

                                <?php endif; ?>


                                <p>

                                    Quantity:
                                    <?= htmlspecialchars(
                                        $item['Quantity']
                                    ) ?>

                                </p>


                                <p class="service-price">

                                    Price:

                                    LKR
                                    <?= number_format(
                                        $item['EstimatedPrice'],
                                        2
                                    ) ?>

                                </p>


                            <?php elseif (
                                !empty($item['PackageID'])
                            ): ?>


                                <h3>

                                    <?= htmlspecialchars(
                                        $item['PackageName']
                                    ) ?>

                                </h3>


                                <p>

                                    <strong>
                                        Package
                                    </strong>

                                </p>


                                <p>

                                    Vendor:
                                    <strong>

                                        <?= htmlspecialchars(
                                            $item['BusinessName']
                                        ) ?>

                                    </strong>

                                </p>


                                <?php if (
                                    !empty(
                                        $item['PackageDescription']
                                    )
                                ): ?>

                                    <p>

                                        <?= htmlspecialchars(
                                            $item['PackageDescription']
                                        ) ?>

                                    </p>

                                <?php endif; ?>


                                <p>

                                    Quantity:
                                    <?= htmlspecialchars(
                                        $item['Quantity']
                                    ) ?>

                                </p>


                                <p class="service-price">

                                    Package Price:

                                    LKR
                                    <?= number_format(
                                        $item['EstimatedPrice'],
                                        2
                                    ) ?>

                                </p>


                            <?php endif; ?>


                            <!-- Remove Button -->

                            <form
                                method="POST"
                                action="index.php?page=remove_event_item"
                                style="margin-top: 15px;"
                                onsubmit="return confirm('Are you sure you want to remove this item from your event?');"
                            >

                                <input
                                    type="hidden"
                                    name="eventID"
                                    value="<?= $event['EventID'] ?>"
                                >


                                <input
                                    type="hidden"
                                    name="eventItemID"
                                    value="<?= $item['EventItemID'] ?>"
                                >


                                <button
                                    type="submit"
                                    class="remove-btn"
                                >
                                    Remove
                                </button>

                            </form>


                        </div>


                    <?php endforeach; ?>


                <?php endif; ?>


            </div>


            <!--
            |--------------------------------------------------------------------------
            | Budget Summary
            |--------------------------------------------------------------------------
            -->

            <div class="budget-box">


                <div class="budget-row">

                    <span>
                        Event Budget
                    </span>

                    <strong>

                        LKR
                        <?= number_format(
                            $event['Budget'],
                            2
                        ) ?>

                    </strong>

                </div>


                <div class="budget-row total">

                    <span>
                        Estimated Total
                    </span>

                    <strong>

                        LKR
                        <?= number_format(
                            $estimatedTotal,
                            2
                        ) ?>

                    </strong>

                </div>


                <div class="budget-row remaining">

                    <span>
                        Remaining Budget
                    </span>


                    <strong
                        class="<?=
                            $remainingBudget < 0
                                ? 'over-budget'
                                : 'within-budget'
                        ?>"
                    >

                        LKR
                        <?= number_format(
                            $remainingBudget,
                            2
                        ) ?>

                    </strong>

                </div>


            </div>


            <!--
            |--------------------------------------------------------------------------
            | Booking Request
            |--------------------------------------------------------------------------
            -->

            <?php if (!empty($eventItems)): ?>

                <div class="section">

                    <h2>
                        Request Booking
                    </h2>


                    <p class="section-description">
                        Submit a booking request for the selected
                        services and packages.
                    </p>


                    <div class="booking-form">

                        <p class="booking-note">
                            Your selected services and packages will
                            be sent to the relevant vendors as booking
                            requests.
                        </p>


                        <form
                            method="POST"
                            action="index.php?page=request_booking"
                        >

                            <input
                                type="hidden"
                                name="eventID"
                                value="<?= htmlspecialchars(
                                    $event['EventID']
                                ) ?>"
                            >


                            <label for="notes">
                                Notes
                            </label>


                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                placeholder="Add any special requirements or notes for the vendor..."
                            ></textarea>


                            <button
                                type="submit"
                                class="btn"
                                onclick="return confirm('Are you sure you want to submit this booking request?');"
                            >
                                Request Booking
                            </button>

                        </form>

                    </div>

                </div>

            <?php endif; ?>


            <!--
            |--------------------------------------------------------------------------
            | Actions
            |--------------------------------------------------------------------------
            -->

            <div class="actions">


                <a
                    href="index.php?page=find_services&id=<?= $event['EventID'] ?>"
                    class="btn btn-primary service-main-action"
                >
                    + Add Services
                </a>


                <a
                    href="index.php?page=edit_event&id=<?= $event['EventID'] ?>"
                    class="btn btn-secondary"
                >
                    Edit Event
                </a>

                <a
                    href="index.php?page=packages&id=<?= $event['EventID'] ?>"
                    class="btn"
                >
                    View Packages
                </a>


            </div>


        </div>


    <?php else: ?>


        <div class="event-card">

            <h2>
                Event Not Found
            </h2>


            <p style="margin-top: 10px;">

                The event does not exist or you
                do not have permission to view it.

            </p>

        </div>


    <?php endif; ?>


</div>

</body>

</html>