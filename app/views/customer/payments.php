<?php

/*
|--------------------------------------------------------------------------
| Payment Message
|--------------------------------------------------------------------------
*/

$message =
    $_SESSION['payment_message']
    ?? '';

unset(
    $_SESSION['payment_message']
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
        My Payments | EventEase
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body>


<div class="container">

    <h1>
        My Payments
    </h1>


    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <?php if (!empty($payments)): ?>


        <div class="card">

            <h2>
                Payment History
            </h2>


            <table>

                <thead>

                    <tr>

                        <th>
                            Payment ID
                        </th>

                        <th>
                            Booking ID
                        </th>

                        <th>
                            Event
                        </th>

                        <th>
                            Vendor
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Method
                        </th>

                        <th>
                            Reference No.
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Slip
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php foreach (
                    $payments
                    as $payment
                ): ?>

                    <tr>


                        <!-- Payment ID -->

                        <td>

                            #<?= htmlspecialchars(
                                $payment['PaymentID']
                            ) ?>

                        </td>


                        <!-- Booking ID -->

                        <td>

                            #<?= htmlspecialchars(
                                $payment['BookingID']
                            ) ?>

                        </td>


                        <!-- Event -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['EventName']
                            ) ?>

                            <br>

                            <small>

                                <?= htmlspecialchars(
                                    $payment['EventDate']
                                ) ?>

                            </small>

                        </td>


                        <!-- Vendor -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['BusinessName']
                            ) ?>

                        </td>


                        <!-- Amount -->

                        <td>

                            LKR
                            <?= number_format(
                                $payment['Amount'],
                                2
                            ) ?>

                        </td>


                        <!-- Payment Date -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['PaymentDate']
                            ) ?>

                        </td>


                        <!-- Payment Method -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['PaymentMethod']
                            ) ?>

                        </td>


                        <!-- Reference Number -->

                        <td>

                            <?= htmlspecialchars(
                                $payment['ReferenceNo']
                            ) ?>

                        </td>


                        <!-- Status -->

                        <td>

                            <?php
                            $status =
                                $payment['PaymentStatus'];
                            ?>


                            <?php if (
                                $status === 'Completed'
                            ): ?>

                                <span
                                    class="status completed"
                                >

                                    Completed

                                </span>

                            <?php else: ?>

                                <span
                                    class="status pending"
                                >

                                    Pending

                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- Payment Slip -->

                        <td>

                            <?php if (
                                !empty(
                                    $payment['PaymentSlip']
                                )
                            ): ?>

                                <a
                                    href="../uploads/payment_slips/<?= htmlspecialchars(
                                        $payment['PaymentSlip']
                                    ) ?>"
                                    target="_blank"
                                >

                                    View Slip

                                </a>

                            <?php else: ?>

                                <span>
                                    No Slip
                                </span>

                            <?php endif; ?>

                        </td>


                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>


    <?php else: ?>


        <div class="card">

            <h2>
                No Payments Found
            </h2>

            <p>

                You have not made any payments yet.

            </p>

        </div>


    <?php endif; ?>


    <br>


    <a
        href="index.php?page=my_bookings"
    >

        ← Back to My Bookings

    </a>


</div>


</body>

</html>