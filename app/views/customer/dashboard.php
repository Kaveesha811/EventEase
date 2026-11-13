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

    <title>Customer Dashboard - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        /* Dashboard Header */

        .dashboard-header {
            background: linear-gradient(
                135deg,
                #dbeafe,
                #eff6ff
            );

            color: #172033;

            padding: 35px;

            border-radius: 14px;

            margin-bottom: 22px;

            box-shadow:
                0 8px 22px
                rgba(37, 99, 235, 0.18);
        }

        .dashboard-header h1 {
            margin: 0 0 10px;
            font-size: 32px;
        }

        .dashboard-header p {
            margin: 0;
            max-width: 650px;
            color: #000000;
            line-height: 1.6;
        }


        /* Quick Action */

        .quick-action {
            background: white;

            border: 1px solid #e5e7eb;
            border-left: 5px solid #2563eb;

            padding: 20px 22px;

            margin-bottom: 22px;

            border-radius: 10px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 20px;

            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.05);
        }

        .quick-action h2 {
            margin: 0 0 5px;
            font-size: 19px;
        }

        .quick-action p {
            margin: 0;
            color: #6b7280;
            line-height: 1.5;
        }


        /* Dashboard Cards */

        .dashboard-card {
            min-height: 225px;

            display: flex;
            flex-direction: column;

            position: relative;

            overflow: hidden;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .dashboard-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 4px;

            background: #2563eb;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 9px 22px
                rgba(0, 0, 0, 0.10);
        }

        .dashboard-card h2 {
            margin: 0 0 10px;
            font-size: 20px;
        }

        .dashboard-card p {
            margin: 0 0 20px;

            color: #6b7280;

            line-height: 1.6;
        }

        .dashboard-card .btn {
            margin-top: auto;
            align-self: flex-start;
        }


        /* Card Labels */

        .card-label {
            display: inline-block;

            margin-bottom: 14px;

            color: #2563eb;

            font-size: 12px;

            font-weight: bold;

            letter-spacing: 0.5px;
        }


        /* Different Card Accent */

        .dashboard-card:nth-child(2)::before {
            background: #7c3aed;
        }

        .dashboard-card:nth-child(2) .card-label {
            color: #7c3aed;
        }

        .dashboard-card:nth-child(3)::before {
            background: #059669;
        }

        .dashboard-card:nth-child(3) .card-label {
            color: #059669;
        }

        .dashboard-card:nth-child(4)::before {
            background: #f59e0b;
        }

        .dashboard-card:nth-child(4) .card-label {
            color: #d97706;
        }


        @media (max-width: 650px) {

            .dashboard-header {
                padding: 28px 22px;
            }

            .dashboard-header h1 {
                font-size: 27px;
            }

            .quick-action {
                align-items: flex-start;
                flex-direction: column;
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

    <!-- Welcome -->

    <section class="dashboard-header">

        <h1>
            Welcome,
            <?= htmlspecialchars(
                $_SESSION['first_name'] ?? 'Customer'
            ) ?>
        </h1>

        <p>
            Plan your event, manage bookings
            and keep track of your payments
            from one place.
        </p>

    </section>


    <!-- Quick Action -->

    <section class="quick-action">

        <div>

            <h2>
                Ready to plan your next event?
            </h2>

            <p>
                Start by creating an event
                and add the services you need.
            </p>

        </div>

        <a
            href="index.php?page=create_event"
            class="btn btn-primary"
        >
            + Create Event
        </a>

    </section>


    <!-- Main Dashboard Cards -->

    <section class="cards">

        <!-- My Events -->

        <div class="card dashboard-card">

            <span class="card-label">
                EVENTS
            </span>

            <h2>
                My Events
            </h2>

            <p>
                View and manage your planned
                weddings, birthdays and other
                events.
            </p>

            <a
                href="index.php?page=my_events"
                class="btn btn-primary"
            >
                View Events
            </a>

        </div>


        <!-- My Bookings -->

        <div class="card dashboard-card">

            <span class="card-label">
                BOOKINGS
            </span>

            <h2>
                My Bookings
            </h2>

            <p>
                Check your vendor booking
                requests and booking status.
            </p>

            <a
                href="index.php?page=my_bookings"
                class="btn btn-primary"
            >
                View Bookings
            </a>

        </div>


        <!-- My Payments -->

        <div class="card dashboard-card">

            <span class="card-label">
                PAYMENTS
            </span>

            <h2>
                My Payments
            </h2>

            <p>
                View payment records, payment
                status and payment details.
            </p>

            <a
                href="index.php?page=my_payments"
                class="btn btn-success"
            >
                View Payments
            </a>

        </div>


    </section>

</main>


</body>

</html>
