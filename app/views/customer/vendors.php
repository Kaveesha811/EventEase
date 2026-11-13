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
        Vendors - EventEase
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
            margin-right: 8px;
        }

        .vendors-grid {
            display: grid;
            grid-template-columns:
                repeat(2, 1fr);
            gap: 20px;
        }

        .vendor-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .vendor-card h2 {
            margin-bottom: 8px;
        }

        .vendor-address {
            color: #666;
            margin-bottom: 20px;
        }

        .service-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .service-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .availability {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 5px;
            background: #eee;
            font-size: 14px;
            font-weight: bold;
        }

        .available {
            background: #e8f5e9;
        }

        .unavailable {
            background: #ffebee;
        }

        .booked {
            background: #fff3e0;
        }

        .not-specified {
            background: #eeeeee;
        }

        .vendor-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .vendor-btn:hover {
            background: #444;
        }

        .disabled-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background: #aaa;
            color: white;
            border-radius: 5px;
            cursor: not-allowed;
        }

        .empty-message {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #666;
        }

        @media (max-width: 750px) {

            .vendors-grid {
                grid-template-columns: 1fr;
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
        href="index.php?page=find_services&id=<?= $event['EventID'] ?>"
        class="back-link"
    >
        ← Back to Services
    </a>


    <div class="page-header">

        <h1>
            Available Vendors
        </h1>

        <p>
            Browse vendors offering
            <?= htmlspecialchars(
                $categoryName
            ) ?> services.
        </p>

    </div>


    <div class="event-summary">

        <strong>Event:</strong>

        <?= htmlspecialchars(
            $event['EventName']
        ) ?>

        &nbsp;&nbsp;

        <strong>Date:</strong>

        <?= htmlspecialchars(
            $event['EventDate']
        ) ?>

        &nbsp;&nbsp;

        <strong>Service:</strong>

        <?= htmlspecialchars(
            $categoryName
        ) ?>

    </div>


    <?php if (empty($vendors)): ?>

        <div class="empty-message">

            <h2>
                No Vendors Found
            </h2>

            <p style="margin-top: 10px;">
                There are currently no approved
                vendors offering this service.
            </p>

        </div>

    <?php else: ?>

        <div class="vendors-grid">

            <?php foreach (
                $vendors as $vendor
            ): ?>

                <div class="vendor-card">

                    <h2>

                        <?= htmlspecialchars(
                            $vendor['BusinessName']
                        ) ?>

                    </h2>


                    <p class="vendor-address">

                        <?= htmlspecialchars(
                            $vendor['BusinessAddress']
                        ) ?>

                    </p>

                    <?php if (!empty($vendor['FacebookProfile'])): ?>
                        <p>
                            <a href="<?= htmlspecialchars($vendor['FacebookProfile']) ?>" target="_blank" rel="noopener noreferrer" class="facebook-link">
                                Facebook Profile
                            </a>
                        </p>
                    <?php endif; ?>


                    <div class="service-name">

                        <?= htmlspecialchars(
                            $vendor['ServiceName']
                        ) ?>

                    </div>


                    <p class="service-description">

                        <?= htmlspecialchars(
                            $vendor['ServiceDescription']
                        ) ?>

                    </p>


                    <div class="price">

                        LKR
                        <?= number_format(
                            $vendor['Price'],
                            2
                        ) ?>

                    </div>


                    <?php

                    $availabilityClass =
                        'not-specified';

                    if (
                        $vendor['AvailabilityStatus']
                        === 'Available'
                    ) {
                        $availabilityClass =
                            'available';
                    }

                    elseif (
                        $vendor['AvailabilityStatus']
                        === 'Unavailable'
                    ) {
                        $availabilityClass =
                            'unavailable';
                    }

                    elseif (
                        $vendor['AvailabilityStatus']
                        === 'Booked'
                    ) {
                        $availabilityClass =
                            'booked';
                    }

                    ?>


                    <span
                        class="availability
                        <?= $availabilityClass ?>"
                    >

                        Availability:
                        <?= htmlspecialchars(
                            $vendor[
                                'AvailabilityStatus'
                            ]
                        ) ?>

                    </span>


                    <?php if (
                        $vendor['AvailabilityStatus']
                        === 'Available'
                    ): ?>

<a
    href="index.php?page=view_service&id=<?= $event['EventID'] ?>&service=<?= $vendor['ServiceID'] ?>"
    class="vendor-btn"
>
    View Service
</a>

                    <?php else: ?>

                        <span class="disabled-btn">
                            Not Available
                        </span>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>

</html>