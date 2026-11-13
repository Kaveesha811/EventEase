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

    <title>My Events - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        /* My Events Header */

        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 20px;

            margin-bottom: 25px;
        }

        .events-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .events-header p {
            margin: 0;
            color: #6b7280;
        }


        /* Event Cards */

        .event-grid {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(300px, 1fr)
                );

            gap: 20px;
        }

        .event-card {
            position: relative;

            display: flex;
            flex-direction: column;

            min-height: 300px;

            overflow: hidden;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .event-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 4px;

            background: #2563eb;
        }

        .event-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 9px 22px
                rgba(0, 0, 0, 0.10);
        }

        .event-card h2 {
            margin: 0 0 6px;
            font-size: 21px;
        }

        .event-type {
            display: inline-block;

            margin-bottom: 20px;

            color: #2563eb;

            font-size: 13px;
            font-weight: bold;
        }


        /* Event Details */

        .event-details {
            display: grid;
            gap: 10px;

            margin-bottom: 20px;
        }

        .event-detail {
            display: flex;
            justify-content: space-between;

            gap: 15px;

            padding-bottom: 9px;

            border-bottom: 1px solid #f0f0f0;

            font-size: 14px;
        }

        .event-detail:last-child {
            border-bottom: none;
        }

        .event-detail strong {
            color: #374151;
        }

        .event-detail span {
            text-align: right;
        }


        /* Event Status */

        .event-status {
            margin-top: auto;
            margin-bottom: 18px;
        }


        /* Event Actions */

        .event-actions {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
        }

        .event-actions .btn {
            transition:
                transform 0.15s ease;
        }

        .event-actions .btn:hover {
            transform: translateY(-1px);
        }


        /* Empty State */

        .empty-state {
            background: white;

            padding: 50px 25px;

            border: 1px solid #e5e7eb;
            border-radius: 10px;

            text-align: center;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.05);
        }

        .empty-state h2 {
            margin: 0 0 8px;
        }

        .empty-state p {
            margin: 0 0 20px;

            color: #6b7280;
        }


        @media (max-width: 650px) {

            .events-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .events-header h1 {
                font-size: 27px;
            }

            .event-detail {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .event-detail span {
                text-align: left;
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

    <div class="events-header">

        <div>

            <h1>
                My Events
            </h1>

            <p>
                View and manage your planned events.
            </p>

        </div>

        <a
            href="index.php?page=create_event"
            class="btn btn-primary"
        >
            + Create Event
        </a>

    </div>


    <?php if (empty($events)): ?>

        <div class="empty-state">

            <h2>
                No Events Yet
            </h2>

            <p>
                Create your first event to start planning.
            </p>

            <a
                href="index.php?page=create_event"
                class="btn btn-primary"
            >
                Create Event
            </a>

        </div>

    <?php else: ?>

        <div class="event-grid">

            <?php foreach ($events as $event): ?>

                <div class="card event-card">

                    <h2>
                        <?= htmlspecialchars(
                            $event['EventName']
                        ) ?>
                    </h2>

                    <span class="event-type">

                        <?= htmlspecialchars(
                            $event['EventTypeName']
                        ) ?>

                    </span>


                    <div class="event-details">

                        <div class="event-detail">

                            <strong>
                                Date
                            </strong>

                            <span>
                                <?= htmlspecialchars(
                                    $event['EventDate']
                                ) ?>
                            </span>

                        </div>


                        <div class="event-detail">

                            <strong>
                                Location
                            </strong>

                            <span>
                                <?= htmlspecialchars(
                                    $event['Location']
                                ) ?>
                            </span>

                        </div>


                        <div class="event-detail">

                            <strong>
                                Guests
                            </strong>

                            <span>
                                <?= htmlspecialchars(
                                    $event['GuestCount']
                                ) ?>
                            </span>

                        </div>


                        <div class="event-detail">

                            <strong>
                                Budget
                            </strong>

                            <span>
                                LKR
                                <?= number_format(
                                    (float) $event['Budget'],
                                    2
                                ) ?>
                            </span>

                        </div>

                    </div>


                    <div class="event-status">

                        <span class="status">

                            <?= htmlspecialchars(
                                $event['Status']
                            ) ?>

                        </span>

                    </div>


                    <div class="event-actions">

                        <a
                            href="index.php?page=view_event&id=<?= urlencode(
                                $event['EventID']
                            ) ?>"
                            class="btn btn-primary"
                        >
                            View Event
                        </a>

                        <a
                            href="index.php?page=edit_event&id=<?= urlencode(
                                $event['EventID']
                            ) ?>"
                            class="btn btn-secondary"
                        >
                            Edit
                        </a>

                        <a
                            href="index.php?page=find_services&id=<?= (int)$event['EventID'] ?>"
                            class="btn btn-success"
                        >
                            + Add Services
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

</body>

</html>
