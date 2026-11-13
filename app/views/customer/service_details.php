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
        Service Details - EventEase
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

        .service-card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .service-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .service-header h1 {
            margin-bottom: 8px;
        }

        .category {
            color: #666;
            font-size: 17px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h2 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .section p {
            color: #555;
            line-height: 1.6;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 25px;
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

        .price {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }

        .availability {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .available {
            background: #e8f5e9;
        }

        .booked {
            background: #fff3e0;
        }

        .unavailable {
            background: #ffebee;
        }

        .not-specified {
            background: #eeeeee;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 5px;
            text-decoration: none;
            background: #222;
            color: white;
        }

        .btn:hover {
            background: #444;
        }

        .disabled-btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 5px;
            background: #aaa;
            color: white;
            cursor: not-allowed;
        }

        .not-found {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
        }

        @media (max-width: 650px) {

            .details-grid {
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

    <?php if (!empty($service)): ?>

        <a
            href="index.php?page=view_vendors&id=<?= $event['EventID'] ?>&category=<?= $service['ServiceCategoryID'] ?>"
            class="back-link"
        >
            ← Back to Vendors
        </a>


        <div class="service-card">

            <div class="service-header">

                <h1>

                    <?= htmlspecialchars(
                        $service['ServiceName']
                    ) ?>

                </h1>

                <p class="category">

                    <?= htmlspecialchars(
                        $service['CategoryName']
                    ) ?>

                </p>

            </div>


            <div class="section">

                <h2>
                    Vendor
                </h2>

                <p>

                    <strong>
                        <?= htmlspecialchars(
                            $service['BusinessName']
                        ) ?>
                    </strong>

                </p>

                <p>

                    <?= htmlspecialchars(
                        $service['BusinessAddress']
                    ) ?>

                </p>

                <?php if (!empty($service['FacebookProfile'])): ?>
                    <p>
                        <a href="<?= htmlspecialchars($service['FacebookProfile']) ?>" target="_blank" rel="noopener noreferrer" class="facebook-link">
                            View Facebook Profile
                        </a>
                    </p>
                <?php endif; ?>

            </div>


            <div class="section">

                <h2>
                    Service Description
                </h2>

                <p>

                    <?= htmlspecialchars(
                        $service['ServiceDescription']
                    ) ?>

                </p>

            </div>


            <div class="details-grid">

                <div class="detail-box">

                    <label>
                        Event
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $event['EventName']
                        ) ?>

                    </strong>

                </div>


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
                        Service Category
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $service['CategoryName']
                        ) ?>

                    </strong>

                </div>


                <div class="detail-box">

                    <label>
                        Vendor
                    </label>

                    <strong>

                        <?= htmlspecialchars(
                            $service['BusinessName']
                        ) ?>

                    </strong>

                </div>

            </div>


            <div class="price">

                LKR
                <?= number_format(
                    $service['Price'],
                    2
                ) ?>

            </div>


            <?php

            $availability =
                $service['AvailabilityStatus'];

            $availabilityClass =
                'not-specified';

            if (
                $availability === 'Available'
            ) {

                $availabilityClass =
                    'available';

            } elseif (
                $availability === 'Booked'
            ) {

                $availabilityClass =
                    'booked';

            } elseif (
                $availability === 'Unavailable'
            ) {

                $availabilityClass =
                    'unavailable';
            }

            ?>


            <span
                class="availability
                <?= $availabilityClass ?>"
            >

                Availability:
                <?= htmlspecialchars(
                    $availability
                ) ?>

            </span>


            <div class="actions">

                <?php if (
                    $availability === 'Available'
                ): ?>

                    <?php if (
    $availability === 'Available'
): ?>

    <form
        method="POST"
        action="index.php?page=add_to_event"
        style="display: inline;"
    >

        <input
            type="hidden"
            name="eventID"
            value="<?= $event['EventID'] ?>"
        >

        <input
            type="hidden"
            name="serviceID"
            value="<?= $service['ServiceID'] ?>"
        >

        <button
            type="submit"
            class="btn"
            style="border: none; cursor: pointer;"
        >
            Add to Event
        </button>

    </form>

<?php else: ?>

    <span class="disabled-btn">
        Not Available
    </span>

<?php endif; ?>

                <?php else: ?>

                    <span class="disabled-btn">
                        Not Available
                    </span>

                <?php endif; ?>


                <a
                    href="index.php?page=find_services&id=<?= $event['EventID'] ?>"
                    class="btn"
                >
                    Back to Services
                </a>

            </div>

        </div>

    <?php else: ?>

        <div class="not-found">

            <h2>
                Service Not Found
            </h2>

            <p style="margin-top: 10px;">
                The service does not exist or is
                currently unavailable.
            </p>

        </div>

    <?php endif; ?>

</div>

</body>

</html>