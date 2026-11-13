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
        Package Details - EventEase
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
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
            text-decoration: none;
        }

        .package-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .package-box h1 {
            margin-bottom: 10px;
        }

        .vendor {
            color: #666;
            margin-bottom: 20px;
        }

        .description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .event-info {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .services-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .services-box h2 {
            margin-bottom: 20px;
        }

        .service-item {
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .service-item h3 {
            margin-bottom: 8px;
        }

        .service-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .service-price {
            font-weight: bold;
        }

        .included {
            margin-top: 10px;
            color: #555;
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
        }

        .btn:hover {
            background: #444;
        }

        .empty-message {
            color: #666;
            padding: 20px 0;
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
        href="index.php?page=packages&id=<?= $event['EventID'] ?>"
        class="back-link"
    >
        ← Back to Packages
    </a>


    <div class="package-box">

        <h1>

            <?= htmlspecialchars(
                $package['PackageName']
            ) ?>

        </h1>


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


        <div class="event-info">

            <strong>
                Selected Event:
            </strong>

            <?= htmlspecialchars(
                $event['EventName']
            ) ?>

            <br><br>

            <strong>
                Event Type:
            </strong>

            <?= htmlspecialchars(
                $event['EventTypeName']
            ) ?>

            <br><br>

            <strong>
                Event Date:
            </strong>

            <?= htmlspecialchars(
                $event['EventDate']
            ) ?>

        </div>


<form
    method="POST"
    action="index.php?page=add_package_to_event"
>
    <input
        type="hidden"
        name="eventID"
        value="<?= $event['EventID'] ?>"
    >

    <input
        type="hidden"
        name="packageID"
        value="<?= $package['PackageID'] ?>"
    >

    <button
        type="submit"
        class="btn"
    >
        Add Package to Event
    </button>
</form>

    </div>


    <div class="services-box">

        <h2>
            Included Services
        </h2>


        <?php if (
            empty($packageServices)
        ): ?>

            <p class="empty-message">

                No services are currently listed
                for this package.

            </p>

        <?php else: ?>


            <?php foreach (
                $packageServices as $service
            ): ?>


                <div class="service-item">

                    <h3>

                        <?= htmlspecialchars(
                            $service['ServiceName']
                        ) ?>

                    </h3>


                    <p class="service-description">

                        <?= htmlspecialchars(
                            $service['Description']
                        ) ?>

                    </p>


                    <p class="service-price">

                        Service Price:

                        LKR
                        <?= number_format(
                            $service['Price'],
                            2
                        ) ?>

                    </p>


                    <?php if (
                        !empty(
                            $service['IncludedDescription']
                        )
                    ): ?>

                        <p class="included">

                            <strong>
                                Included:
                            </strong>

                            <?= htmlspecialchars(
                                $service[
                                    'IncludedDescription'
                                ]
                            ) ?>

                        </p>

                    <?php endif; ?>


                    <p class="included">

                        <strong>
                            Quantity:
                        </strong>

                        <?= htmlspecialchars(
                            $service['Quantity']
                        ) ?>

                    </p>

                </div>


            <?php endforeach; ?>


        <?php endif; ?>


    </div>


</div>

</body>

</html>