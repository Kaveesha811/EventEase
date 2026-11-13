<?php

/*
|--------------------------------------------------------------------------
| Review Message
|--------------------------------------------------------------------------
*/

$message =
    $_SESSION['review_message']
    ?? '';

unset(
    $_SESSION['review_message']
);

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
        Review Booking | EventEase
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

        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 6%;
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
            padding: 8px 14px;
            border-radius: 5px;
            background: #2563eb;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow:
                0 4px 12px
                rgba(0, 0, 0, 0.05);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .details p {
            margin: 8px 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .rating-options {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .rating-options label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .rating-options input {
            margin-right: 5px;
        }

        textarea {
            width: 100%;
            min-height: 130px;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }

        .submit-button {
            margin-top: 20px;
            border: none;
            background: #2563eb;
            color: white;
            padding: 11px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .submit-button:hover {
            background: #1d4ed8;
        }

        .message {
            margin-bottom: 20px;
            padding: 14px;
            background: #dcfce7;
            color: #166534;
            border-radius: 6px;
        }

        .existing-review {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 20px;
            border-radius: 8px;
        }

        .stars {
            font-size: 24px;
            letter-spacing: 3px;
        }

        .comment {
            margin-top: 15px;
            line-height: 1.6;
        }

        .review-date {
            margin-top: 15px;
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 600px) {

            .container {
                width: 92%;
                margin: 25px auto;
            }

            .card {
                padding: 20px;
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

    <a
        href="index.php?page=customer_dashboard"
    >
        Dashboard
    </a>

</nav>


<div class="container">


    <a
        href="index.php?page=my_bookings"
        class="back-link"
    >
        ← Back to My Bookings
    </a>


    <?php if (!empty($message)): ?>

        <div class="message">

            <?php
            echo htmlspecialchars(
                $message
            );
            ?>

        </div>

    <?php endif; ?>


    <?php if (!empty($booking)): ?>


        <div class="card">

            <h1>
                Review Your Booking
            </h1>

            <p class="subtitle">
                Share your experience with the vendor.
            </p>


            <div class="details">

                <p>
                    <strong>
                        Booking ID:
                    </strong>

                    #
                    <?php
                    echo htmlspecialchars(
                        $booking['BookingID']
                    );
                    ?>
                </p>


                <p>
                    <strong>
                        Event:
                    </strong>

                    <?php
                    echo htmlspecialchars(
                        $booking['EventName']
                    );
                    ?>
                </p>


                <p>
                    <strong>
                        Vendor:
                    </strong>

                    <?php
                    echo htmlspecialchars(
                        $booking['BusinessName']
                    );
                    ?>
                </p>


                <p>
                    <strong>
                        Event Date:
                    </strong>

                    <?php
                    echo htmlspecialchars(
                        $booking['EventDate']
                    );
                    ?>
                </p>


                <p>
                    <strong>
                        Booking Status:
                    </strong>

                    <?php
                    echo htmlspecialchars(
                        $booking['Status']
                    );
                    ?>
                </p>

            </div>


            <?php if (!empty($review)): ?>


                <div class="existing-review">

                    <h2>
                        Your Review
                    </h2>


                    <div class="stars">

                        <?php

                        $rating =
                            (int) $review['Rating'];

                        for (
                            $i = 1;
                            $i <= 5;
                            $i++
                        ) {

                            echo $i <= $rating
                                ? '★'
                                : '☆';
                        }

                        ?>

                    </div>


                    <?php if (
                        !empty(
                            $review['Comment']
                        )
                    ): ?>

                        <div class="comment">

                            <strong>
                                Comment:
                            </strong>

                            <p>
                                <?php
                                echo nl2br(
                                    htmlspecialchars(
                                        $review['Comment']
                                    )
                                );
                                ?>
                            </p>

                        </div>

                    <?php endif; ?>


                    <div class="review-date">

                        Reviewed on:

                        <?php
                        echo htmlspecialchars(
                            $review['ReviewDate']
                        );
                        ?>

                    </div>

                </div>


            <?php else: ?>


                <form
                    method="POST"
                    action="index.php?page=submit_review"
                >

                    <input
                        type="hidden"
                        name="booking_id"
                        value="<?php
                        echo htmlspecialchars(
                            $booking['BookingID']
                        );
                        ?>"
                    >


                    <label>
                        Rating
                    </label>


                    <div class="rating-options">

                        <?php for (
                            $i = 1;
                            $i <= 5;
                            $i++
                        ): ?>

                            <label>

                                <input
                                    type="radio"
                                    name="rating"
                                    value="<?php echo $i; ?>"
                                    required
                                >

                                <?php
                                echo $i;
                                ?>
                                Star

                            </label>

                        <?php endfor; ?>

                    </div>


                    <label for="comment">
                        Comment
                    </label>


                    <textarea
                        id="comment"
                        name="comment"
                        maxlength="1000"
                        placeholder="Write your feedback about the vendor..."
                    ></textarea>


                    <button
                        type="submit"
                        class="submit-button"
                    >
                        Submit Review
                    </button>

                </form>


            <?php endif; ?>


        </div>


    <?php else: ?>


        <div class="card">

            <h2>
                Booking Not Found
            </h2>

            <p>
                The requested booking could not be found.
            </p>

        </div>


    <?php endif; ?>


</div>

</body>

</html>