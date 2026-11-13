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
        Find Services - EventEase
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
            margin-bottom: 30px;
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
            margin-right: 15px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns:
                repeat(3, 1fr);
            gap: 20px;
        }

        .category-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.08);
        }

        .category-card h2 {
            margin-bottom: 10px;
        }

        .category-card p {
            color: #666;
            line-height: 1.5;
            min-height: 45px;
        }

        .category-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .category-btn:hover {
            background: #444;
        }

        .empty-message {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #666;
        }

        @media (max-width: 800px) {

            .categories-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }

        @media (max-width: 550px) {

            .categories-grid {
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
        href="index.php?page=view_event&id=<?= $event['EventID'] ?>"
        class="back-link"
    >
        ← Back to Event
    </a>


    <div class="page-header">

        <h1>Find Services</h1>

        <p>
            Choose services and vendors for your event.
        </p>

    </div>


    <div class="event-summary">

        <strong>Event:</strong>

        <?= htmlspecialchars(
            $event['EventName']
        ) ?>

        &nbsp;&nbsp;

        <strong>Type:</strong>

        <?= htmlspecialchars(
            $event['EventTypeName']
        ) ?>

    </div>


    <?php if (empty($categories)): ?>

        <div class="empty-message">

            <h2>No Services Available</h2>

            <p>
                There are currently no active services
                available.
            </p>

        </div>

    <?php else: ?>

        <div class="categories-grid">

            <?php foreach (
                $categories as $category
            ): ?>

                <div class="category-card">

                    <h2>
                        <?= htmlspecialchars(
                            $category['CategoryName']
                        ) ?>
                    </h2>

                    <p>

                        <?php if (
                            !empty(
                                $category['Description']
                            )
                        ): ?>

                            <?= htmlspecialchars(
                                $category['Description']
                            ) ?>

                        <?php else: ?>

                            Find available vendors
                            and services in this category.

                        <?php endif; ?>

                    </p>


<a
    href="index.php?page=view_vendors&id=<?= $event['EventID'] ?>&category=<?= $category['ServiceCategoryID'] ?>"
    class="category-btn"
>
    View Vendors
</a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>

</html>