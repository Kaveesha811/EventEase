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
        Vendor Dashboard - EventEase
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

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .header {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .header h1 {
            margin: 0 0 10px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 16px;
        }

        .cards {
            display: grid;
            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 20px;
        }

        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .card p {
            color: #6b7280;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .logout {
            margin-top: 25px;
        }

        .logout a {
            color: #dc2626;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <h2>EventEase</h2>

    <div class="navbar-links">

        <a href="index.php?page=vendor_dashboard">
            Dashboard
        </a>

        <a href="index.php?page=vendor_bookings">
            Bookings
        </a>

        <a href="index.php?page=vendor_services">
            Services
        </a>

        <a href="index.php?page=vendor_packages">
            Packages
        </a>

        <a href="index.php?page=vendor_availability">
            Availability
        </a>

        <a href="index.php?page=vendor_transactions">
            Transactions
        </a>

        <a href="index.php?page=logout">
            Logout
        </a>

    </div>

</nav>

<div class="container">

    <div class="header">

        <h1>
            Welcome,
            <?= htmlspecialchars(
                $_SESSION['first_name'] ?? 'Vendor'
            ) ?>
        </h1>

        <p>
            Manage your EventEase services,
            bookings and availability from your
            vendor dashboard.
        </p>

    </div>


    <div class="cards">


        <!-- Booking Requests -->

        <div class="card">

            <h2>
                Booking Requests
            </h2>

            <p>
                View booking requests submitted
                by customers for your services
                and packages.
            </p>

            <a
                href="index.php?page=vendor_bookings"
                class="btn"
            >
                View Bookings
            </a>

        </div>


        <!-- Services -->

        <div class="card">

            <h2>
                My Services
            </h2>

            <p>
                Manage the services you offer
                to EventEase customers.
            </p>

            <a
                href="index.php?page=vendor_services"
                class="btn"
            >
                Manage Services
            </a>

        </div>


        <!-- Packages -->

        <div class="card">

            <h2>
                My Packages
            </h2>

            <p>
                Create and manage service
                packages and their prices.
            </p>

            <a
                href="index.php?page=vendor_packages"
                class="btn"
            >
                Manage Packages
            </a>

        </div>


        <!-- Availability -->

        <div class="card">

            <h2>
                Availability
            </h2>

            <p>
                Manage your availability for
                different event dates.
            </p>

            <a
                href="index.php?page=vendor_availability"
                class="btn"
            >
                Manage Availability
            </a>

        </div>


        <!-- Transaction History -->

        <div class="card">

            <h2>
                Transaction History
            </h2>

            <p>
                View your booking amounts,
                payment records and transaction
                status.
            </p>

            <a
                href="index.php?page=vendor_transactions"
                class="btn"
            >
                View Transactions
            </a>

        </div>


    </div>


</div>

</body>

</html>
