<?php

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header(
        "Location: index.php?page=login"
    );

    exit;
}

$availability =
    $availability ?? [];

$message =
    $message ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Availability - EventEase</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #333;
        }


        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }


        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        .top-bar h1 {
            margin: 0;
            color: #222;
        }


        .back-link {
            text-decoration: none;
            color: #333;
            font-size: 15px;
        }


        .back-link:hover {
            text-decoration: underline;
        }


        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow:
                0 3px 12px
                rgba(0, 0, 0, 0.08);
        }


        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #222;
        }


        .form-group {
            margin-bottom: 20px;
        }


        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }


        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }


        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #555;
        }


        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
        }


        .btn-add {
            background: #222;
            color: #ffffff;
        }


        .btn-add:hover {
            background: #444;
        }


        .btn-update {
            background: #222;
            color: #ffffff;
        }


        .btn-update:hover {
            background: #444;
        }


        .btn-cancel {
            background: #e5e5e5;
            color: #333;
        }


        .btn-cancel:hover {
            background: #d5d5d5;
        }


        .message {
            padding: 13px 15px;
            margin-bottom: 20px;
            background: #d1e7dd;
            border: 1px solid #a3cfbb;
            color: #0f5132;
            border-radius: 7px;
        }


        .table-wrapper {
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        th {
            background: #f0f2f5;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }


        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }


        tr:last-child td {
            border-bottom: none;
        }


        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }


        .status-available {
            background: #d1e7dd;
            color: #0f5132;
        }


        .status-unavailable {
            background: #f8d7da;
            color: #842029;
        }


        .status-booked {
            background: #fff3cd;
            color: #664d03;
        }


        .empty-message {
            padding: 20px;
            text-align: center;
            color: #777;
        }


        .action-link {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }


        .action-link:hover {
            text-decoration: underline;
        }


        .required {
            color: #d00;
        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<div class="container">


    <!-- Header -->

    <div class="top-bar">

        <h1>
            Manage Availability
        </h1>

        <a
            href="index.php?page=vendor_dashboard"
            class="back-link"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- Message -->

    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <!-- Add Availability -->

    <div class="card">

        <h2>
            Add Availability
        </h2>


        <form
            method="POST"
            action="index.php?page=add_availability"
        >


            <div class="form-group">

                <label for="availabilityDate">

                    Date
                    <span class="required">*</span>

                </label>


                <input
                    type="date"
                    id="availabilityDate"
                    name="availabilityDate"
                    min="<?= date('Y-m-d') ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="status">

                    Status
                    <span class="required">*</span>

                </label>


                <select
                    id="status"
                    name="status"
                    required
                >

                    <option value="">
                        Select Status
                    </option>

                    <option value="Available">
                        Available
                    </option>

                    <option value="Unavailable">
                        Unavailable
                    </option>

                    <option value="Booked">
                        Booked
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="btn btn-add"
            >
                Add Availability
            </button>


        </form>

    </div>


    <!-- Availability List -->

    <div class="card">

        <h2>
            My Availability
        </h2>


        <div class="table-wrapper">

            <?php if (!empty($availability)): ?>

                <table>

                    <thead>

                        <tr>

                            <th>
                                Date
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
                            $availability
                            as $item
                        ): ?>

                            <?php

                            $status =
                                $item['Status']
                                ?? '';

                            $statusClass =
                                '';

                            if (
                                $status ===
                                'Available'
                            ) {

                                $statusClass =
                                    'status-available';

                            } elseif (
                                $status ===
                                'Unavailable'
                            ) {

                                $statusClass =
                                    'status-unavailable';

                            } elseif (
                                $status ===
                                'Booked'
                            ) {

                                $statusClass =
                                    'status-booked';
                            }

                            ?>


                            <tr>

                                <td>

                                    <?= htmlspecialchars(
                                        $item[
                                            'AvailabilityDate'
                                        ]
                                    ) ?>

                                </td>


                                <td>

                                    <span
                                        class="status
                                        <?= $statusClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $status
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="index.php?page=edit_availability&id=<?= (int) $item['AvailabilityID'] ?>"
                                        class="action-link"
                                    >
                                        Edit
                                    </a>

                                </td>

                            </tr>


                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty-message">

                    No availability records found.

                </div>

            <?php endif; ?>

        </div>

    </div>


</div>


</body>

</html>