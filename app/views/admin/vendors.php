<?php

/*
|--------------------------------------------------------------------------
| Admin Vendor Verification
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Admin'
) {

    header(
        'Location: index.php?page=login'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Session Message
|--------------------------------------------------------------------------
*/

$message =
    $_SESSION['vendor_message']
    ?? '';

unset(
    $_SESSION['vendor_message']
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

    <title>Vendor Verification | EventEase</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #222;
        }


        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }


        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }


        .navbar a:hover {
            text-decoration: underline;
        }


        .container {
            width: 95%;
            max-width: 1400px;
            margin: 35px auto;
        }


        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        .page-header h1 {
            margin: 0;
            font-size: 28px;
        }


        .page-header p {
            margin-top: 8px;
            color: #666;
        }


        .back-button {
            display: inline-block;
            background: #374151;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
        }


        .back-button:hover {
            background: #111827;
        }


        .message {
            background: #dcfce7;
            color: #166534;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
        }


        .table-card {
            background: white;
            border-radius: 10px;
            padding: 20px;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.08);

            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }


        th,
        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }


        th {
            background: #f3f4f6;
            font-size: 14px;
            color: #374151;
        }


        td {
            font-size: 14px;
        }


        tr:hover {
            background: #fafafa;
        }


        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }


        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }


        .status-approved {
            background: #dcfce7;
            color: #166534;
        }


        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }


        .verification-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .verification-form select {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background: white;
        }


        .update-button {
            border: none;
            background: #2563eb;
            color: white;

            padding: 8px 13px;

            border-radius: 5px;

            cursor: pointer;
        }


        .update-button:hover {
            background: #1d4ed8;
        }


        .empty-message {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }


        @media (max-width: 768px) {

            .navbar {
                padding: 15px 20px;
            }


            .container {
                width: 92%;
            }


            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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


    <div>

        <a
            href="index.php?page=admin_dashboard"
        >
            Dashboard
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


    <div class="page-header">

        <div>

            <h1>
                Vendor Verification
            </h1>

            <p>
                Review registered vendors and manage
                their verification status.
            </p>

        </div>


        <a
            href="index.php?page=admin_dashboard"
            class="back-button"
        >
            Back to Dashboard
        </a>

    </div>



    <?php if (!empty($message)): ?>

        <div class="message">

            <?php
            echo htmlspecialchars(
                $message
            );
            ?>

        </div>

    <?php endif; ?>



    <div class="table-card">


        <?php if (!empty($vendors)): ?>


            <table>

                <thead>

                    <tr>

                        <th>
                            Vendor ID
                        </th>

                        <th>
                            Business Name
                        </th>

                        <th>
                            Owner
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Business Address
                        </th>

                        <th>
                            Registration Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php foreach (
                        $vendors
                        as $vendor
                    ): ?>


                        <tr>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['VendorID']
                                );
                                ?>

                            </td>


                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $vendor['BusinessName']
                                    );
                                    ?>

                                </strong>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['FirstName']
                                    . ' '
                                    . $vendor['LastName']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['Email']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['Phone']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['BusinessAddress']
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $vendor['RegistrationDate']
                                );
                                ?>

                            </td>


                            <td>


                                <?php

                                $status =
                                    $vendor[
                                        'VerificationStatus'
                                    ];

                                $statusClass =
                                    'status-pending';


                                if (
                                    $status === 'Approved'
                                ) {

                                    $statusClass =
                                        'status-approved';

                                } elseif (
                                    $status === 'Rejected'
                                ) {

                                    $statusClass =
                                        'status-rejected';
                                }

                                ?>


                                <span
                                    class="status
                                    <?php
                                    echo $statusClass;
                                    ?>"
                                >

                                    <?php
                                    echo htmlspecialchars(
                                        $status
                                    );
                                    ?>

                                </span>


                            </td>


                            <td>


                                <form
                                    method="POST"
                                    action="index.php?page=admin_update_vendor"
                                    class="verification-form"
                                >


                                    <input
                                        type="hidden"
                                        name="vendor_id"
                                        value="<?php
                                        echo htmlspecialchars(
                                            $vendor['VendorID']
                                        );
                                        ?>"
                                    >


                                    <select
                                        name="verification_status"
                                        required
                                    >

                                        <option
                                            value="Pending"
                                            <?php
                                            echo
                                            $status === 'Pending'
                                                ? 'selected'
                                                : '';
                                            ?>
                                        >
                                            Pending
                                        </option>


                                        <option
                                            value="Approved"
                                            <?php
                                            echo
                                            $status === 'Approved'
                                                ? 'selected'
                                                : '';
                                            ?>
                                        >
                                            Approved
                                        </option>


                                        <option
                                            value="Rejected"
                                            <?php
                                            echo
                                            $status === 'Rejected'
                                                ? 'selected'
                                                : '';
                                            ?>
                                        >
                                            Rejected
                                        </option>

                                    </select>


                                    <button
                                        type="submit"
                                        class="update-button"
                                    >
                                        Update
                                    </button>


                                </form>


                            </td>


                        </tr>


                    <?php endforeach; ?>


                </tbody>

            </table>


        <?php else: ?>


            <div class="empty-message">

                No vendors have been registered yet.

            </div>


        <?php endif; ?>


    </div>


</div>


</body>

</html>