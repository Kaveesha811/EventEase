<?php

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
|
| This page is accessible only through the Admin dashboard route.
|
*/

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
        Admin Dashboard - EventEase
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background-color: #f5f7fb;

            color: #1f2937;
        }


        /* ------------------------------------------------------------------
           Navigation Bar
           ------------------------------------------------------------------ */

        .navbar {

            background-color: #111827;

            color: white;

            padding: 18px 7%;

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

            align-items: center;
        }


        .navbar-links a {

            color: white;

            text-decoration: none;

            font-size: 14px;
        }


        .navbar-links a:hover {

            text-decoration: underline;
        }


        /* ------------------------------------------------------------------
           Main Container
           ------------------------------------------------------------------ */

        .container {

            width: 86%;

            max-width: 1200px;

            margin: 45px auto;
        }


        .welcome-section {

            margin-bottom: 30px;
        }


        .welcome-section h1 {

            margin: 0 0 8px 0;

            font-size: 32px;

            color: #111827;
        }


        .welcome-section p {

            margin: 0;

            color: #6b7280;

            font-size: 15px;
        }


        /* ------------------------------------------------------------------
           Dashboard Cards
           ------------------------------------------------------------------ */

        .dashboard-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 24px;
        }


        .card {

            background-color: white;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            padding: 28px;

            box-shadow:
                0 4px 12px
                rgba(0, 0, 0, 0.05);
        }


        .card h2 {

            margin-top: 0;

            margin-bottom: 10px;

            font-size: 21px;

            color: #111827;
        }


        .card p {

            color: #6b7280;

            line-height: 1.6;

            font-size: 14px;

            min-height: 45px;
        }


        .card a {

            display: inline-block;

            margin-top: 15px;

            padding: 10px 18px;

            background-color: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            font-size: 14px;
        }


        .card a:hover {

            background-color: #1d4ed8;
        }


        /* ------------------------------------------------------------------
           Vendor Verification Card
           ------------------------------------------------------------------ */

        .vendor-card {

            border-top: 4px solid #2563eb;
        }


        /* ------------------------------------------------------------------
           Payment Management Card
           ------------------------------------------------------------------ */

        .payment-card {

            border-top: 4px solid #059669;
        }


        /* ------------------------------------------------------------------
           Admin Information
           ------------------------------------------------------------------ */

        .info-card {

            margin-top: 25px;

            background-color: white;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            padding: 25px;

            box-shadow:
                0 4px 12px
                rgba(0, 0, 0, 0.04);
        }


        .info-card h3 {

            margin-top: 0;

            color: #111827;
        }


        .info-card p {

            margin-bottom: 0;

            color: #6b7280;

            line-height: 1.6;

            font-size: 14px;
        }


        /* ------------------------------------------------------------------
           Responsive Design
           ------------------------------------------------------------------ */

        @media (max-width: 768px) {

            .navbar {

                padding:
                    16px 5%;

                flex-direction: column;

                align-items: flex-start;

                gap: 12px;
            }


            .navbar-links {

                gap: 15px;
            }


            .container {

                width: 90%;

                margin:
                    30px auto;
            }


            .dashboard-grid {

                grid-template-columns: 1fr;
            }


            .welcome-section h1 {

                font-size: 27px;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


    <!--
    |--------------------------------------------------------------------------
    | Navigation Bar
    |--------------------------------------------------------------------------
    -->

    <div class="navbar">

        <h2>
            EventEase
        </h2>


        <div class="navbar-links">

            <a
                href="index.php?page=admin_dashboard"
            >
                Dashboard
            </a>


            <a
                href="index.php?page=admin_payments"
            >
                Payments
            </a>


            <a
                href="index.php?page=admin_vendors"
            >
                Vendors
            </a>


            <a
                href="index.php?page=logout"
            >
                Logout
            </a>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | Main Content
    |--------------------------------------------------------------------------
    -->

    <div class="container">


        <div class="welcome-section">

            <h1>
                Admin Dashboard
            </h1>

            <p>
                Manage EventEase operations,
                vendors and payment records.
            </p>

        </div>


        <!--
        |--------------------------------------------------------------------------
        | Dashboard Management Cards
        |--------------------------------------------------------------------------
        -->

        <div class="dashboard-grid">


            <!-- Vendor Verification -->

            <div class="card vendor-card">

                <h2>
                    Vendor Verification
                </h2>

                <p>
                    Review registered vendors
                    and manage their verification
                    status before they access
                    the vendor system.
                </p>


                <a
                    href="index.php?page=admin_vendors"
                >
                    Manage Vendors
                </a>

            </div>


            <!-- Payment Management -->

            <div class="card payment-card">

                <h2>
                    Payment Management
                </h2>

                <p>
                    View customer payment records
                    and verify bank transfer
                    payments submitted through
                    EventEase.
                </p>


                <a
                    href="index.php?page=admin_payments"
                >
                    Manage Payments
                </a>

            </div>


        </div>


        <!--
        |--------------------------------------------------------------------------
        | Admin Information
        |--------------------------------------------------------------------------
        -->

        <div class="info-card">

            <h3>
                Administration
            </h3>

            <p>
                Use the management options above
                to verify vendors and review
                customer payment records.
            </p>

        </div>


    </div>


</body>

</html>