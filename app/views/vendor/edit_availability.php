<?php

/*
|--------------------------------------------------------------------------
| Vendor Access Check
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'Vendor'
) {
    header(
        'Location: index.php?page=login'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Check Availability Record
|--------------------------------------------------------------------------
*/

if (!$availabilityRecord) {

    header(
        'Location: index.php?page=vendor_availability'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Get Message
|--------------------------------------------------------------------------
*/

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

    <title>
        Edit Availability - EventEase
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

            background: #f4f6f9;

            color: #222;
        }


        .container {

            width: 90%;

            max-width: 900px;

            margin: 50px auto;
        }


        .page-header {

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            margin-bottom: 25px;
        }


        .page-header h1 {

            margin: 0;

            font-size: 30px;
        }


        .back-link {

            text-decoration: none;

            color: #222;

            font-size: 15px;
        }


        .back-link:hover {

            text-decoration: underline;
        }


        .card {

            background: #ffffff;

            padding: 30px;

            border-radius: 14px;

            box-shadow:
                0 5px 18px
                rgba(0, 0, 0, 0.08);
        }


        .card h2 {

            margin-top: 0;

            margin-bottom: 25px;

            font-size: 24px;
        }


        .message {

            margin-bottom: 20px;

            padding: 12px 15px;

            border-radius: 8px;

            background: #fff3cd;

            color: #664d03;

            border: 1px solid #ffecb5;
        }


        .form-group {

            margin-bottom: 20px;
        }


        label {

            display: block;

            margin-bottom: 8px;

            font-weight: 600;
        }


        .required {

            color: red;
        }


        input,
        select {

            width: 100%;

            padding: 12px 14px;

            border: 1px solid #ccc;

            border-radius: 8px;

            font-size: 15px;

            background: #fff;
        }


        input:focus,
        select:focus {

            outline: none;

            border-color: #555;

            box-shadow:
                0 0 0 2px
                rgba(0, 0, 0, 0.08);
        }


        .current-status {

            margin-top: 8px;

            font-size: 14px;

            color: #666;
        }


        .actions {

            display: flex;

            gap: 12px;

            margin-top: 25px;
        }


        .btn {

            display: inline-block;

            padding: 12px 20px;

            border-radius: 8px;

            border: none;

            font-size: 15px;

            cursor: pointer;

            text-decoration: none;
        }


        .btn-primary {

            background: #222;

            color: #fff;
        }


        .btn-primary:hover {

            background: #000;
        }


        .btn-secondary {

            background: #e9ecef;

            color: #222;
        }


        .btn-secondary:hover {

            background: #dfe3e6;
        }


        @media (max-width: 600px) {

            .container {

                width: 94%;

                margin: 30px auto;
            }


            .page-header {

                flex-direction: column;

                align-items: flex-start;

                gap: 12px;
            }


            .card {

                padding: 22px;
            }


            .actions {

                flex-direction: column;
            }


            .btn {

                width: 100%;

                text-align: center;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<div class="container">


    <!-- Page Header -->

    <div class="page-header">

        <h1>
            Edit Availability
        </h1>


        <a
            href="index.php?page=vendor_availability"
            class="back-link"
        >
            ← Back to Availability
        </a>

    </div>


    <!-- Edit Form -->

    <div class="card">

        <h2>
            Update Availability
        </h2>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            action="index.php?page=edit_availability&id=<?= (int)$availabilityRecord['AvailabilityID'] ?>"
        >


            <!-- Date -->

            <div class="form-group">

                <label for="availabilityDate">

                    Date
                    <span class="required">*</span>

                </label>


                <input
                    type="date"
                    id="availabilityDate"
                    name="availabilityDate"
                    value="<?= htmlspecialchars($availabilityRecord['AvailabilityDate']) ?>"
                    min="<?= date('Y-m-d') ?>"
                    required
                >

            </div>


            <!-- Status -->

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


                    <option
                        value="Available"
                        <?= $availabilityRecord['Status'] === 'Available'
                            ? 'selected'
                            : '' ?>
                    >
                        Available
                    </option>


                    <option
                        value="Unavailable"
                        <?= $availabilityRecord['Status'] === 'Unavailable'
                            ? 'selected'
                            : '' ?>
                    >
                        Unavailable
                    </option>


                    <option
                        value="Booked"
                        <?= $availabilityRecord['Status'] === 'Booked'
                            ? 'selected'
                            : '' ?>
                    >
                        Booked
                    </option>

                </select>


                <div class="current-status">

                    Current status:
                    <strong>
                        <?= htmlspecialchars($availabilityRecord['Status']) ?>
                    </strong>

                </div>

            </div>


            <!-- Buttons -->

            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Availability
                </button>


                <a
                    href="index.php?page=vendor_availability"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>


        </form>

    </div>


</div>


</body>

</html>