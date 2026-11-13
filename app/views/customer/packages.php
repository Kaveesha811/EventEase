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
        Packages - EventEase
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
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
            text-decoration: none;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin-bottom: 8px;
        }

        .page-header p {
            color: #666;
        }

        .event-summary {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.06);
        }

        .event-summary strong {
            margin-right: 6px;
        }

        .packages-grid {
            display: grid;
            grid-template-columns:
                repeat(2, 1fr);
            gap: 20px;
        }

        .package-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .package-card h2 {
            margin-bottom: 8px;
        }

        .vendor {
            color: #666;
            margin-bottom: 15px;
        }

        .description {
            color: #555;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .price {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .package-btn {
            display: inline-block;
            padding: 10px 16px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .package-btn:hover {
            background: #444;
        }

        .empty-message {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #666;
        }

        @media (max-width: 700px) {

            .packages-grid {
                grid-template-columns: 1fr;
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


    <a href="index.php?page=customer_dashboard">
        Dashboard
    </a>

</nav>


<div class="container">


    <a
        href="index.php?page=view_event&id=<?= $event['EventID'] ?>"
        class="back-link"
    >
        ← Back to Event
    </a>


    <div class="page-header">

        <h1>
            Packages
        </h1>

        <p>
            Choose a package for your event.
        </p>

    </div>


    <div class="event-summary">

        <strong>
            Event:
        </strong>

        <?= htmlspecialchars(
            $event['EventName']
        ) ?>


        &nbsp;&nbsp;


        <strong>
            Type:
        </strong>

        <?= htmlspecialchars(
            $event['EventTypeName']
        ) ?>


        &nbsp;&nbsp;


        <strong>
            Date:
        </strong>

        <?= htmlspecialchars(
            $event['EventDate']
        ) ?>

    </div>


    <?php if (
        empty($packages)
    ): ?>


        <div class="empty-message">

            <h2>
                No Packages Available
            </h2>

            <p style="margin-top: 10px;">

                There are currently no active
                packages available.

            </p>

        </div>


    <?php else: ?>


        <div class="packages-grid">


            <?php foreach (
                $packages as $package
            ): ?>


                <div class="package-card">


                    <h2>

                        <?= htmlspecialchars(
                            $package['PackageName']
                        ) ?>

                    </h2>


                    <p class="vendor">

                        Vendor:

                        <strong>

                            <?= htmlspecialchars(
                                $package['BusinessName']
                            ) ?>

                        </strong>

                    </p>


                    <p class="description">

                        <?= htmlspecialchars(
                            $package['Description']
                        ) ?>

                    </p>


                    <div class="price">

                        <?php
                        $originalPrice =
                            (float) ($package['OriginalPrice'] ?? 0);

                        $packagePrice =
                            (float) $package['Price'];

                        $saving =
                            max(0, $originalPrice - $packagePrice);
                        ?>

                        <?php if ($saving > 0): ?>

                            <span class="package-original-price">
                                LKR <?= number_format(
                                    $originalPrice,
                                    2
                                ) ?>
                            </span>

                            <span class="package-saving">
                                Save LKR <?= number_format(
                                    $saving,
                                    2
                                ) ?>
                            </span>

                        <?php endif; ?>

                        <strong>
                            LKR <?= number_format(
                                $packagePrice,
                                2
                            ) ?>
                        </strong>

                    </div>


                    <a
                        href="index.php?page=view_package&id=<?= $event['EventID'] ?>&package=<?= $package['PackageID'] ?>"
                        class="package-btn"
                    >
                        View Package
                    </a>


                </div>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</div>

</body>

</html>