<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Event - EventEase</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f5f5f5;
            padding: 40px 20px;
        }

        .event-container {
            width: 650px;
            max-width: 100%;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #555;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .create-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 5px;
            background: #222;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .create-btn:hover {
            background: #444;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #222;
            text-decoration: none;
        }

        @media (max-width: 600px) {

            .form-row {
                grid-template-columns: 1fr;
            }

        }

    </style>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="event-container">

    <h1>Create Your Event</h1>

    <p class="subtitle">
        Add your event details to start planning
    </p>

    <?php if (!empty($message)): ?>

        <p style="
            color: #d32f2f;
            text-align: center;
            margin-bottom: 20px;
        ">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>


    <form
        method="POST"
        action="index.php?page=create_event"
    >

        <div class="form-group">

            <label for="eventName">
                Event Name
            </label>

            <input
                type="text"
                id="eventName"
                name="eventName"
                placeholder="e.g. My Wedding"
                required
            >

        </div>


        <div class="form-group">

            <label for="eventTypeID">
                Event Type
            </label>

            <select
                id="eventTypeID"
                name="eventTypeID"
                required
            >

                <option value="">
                    Select Event Type
                </option>

                <?php foreach ($eventTypes as $type): ?>

                    <option
                        value="<?= $type['EventTypeID'] ?>"
                    >
                        <?= htmlspecialchars(
                            $type['EventTypeName']
                        ) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label for="eventDate">
                    Event Date
                </label>

                <input
                    type="date"
                    id="eventDate"
                    name="eventDate"
                    min="<?= date('Y-m-d') ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="guestCount">
                    Guest Count
                </label>

                <input
                    type="number"
                    id="guestCount"
                    name="guestCount"
                    min="1"
                    placeholder="e.g. 150"
                    required
                >

            </div>

        </div>


        <div class="form-group">

            <label for="location">
                Location
            </label>

            <input
                type="text"
                id="location"
                name="location"
                placeholder="e.g. Colombo"
                required
            >

        </div>


        <div class="form-group">

            <label for="budget">
                Budget (LKR)
            </label>

            <input
                type="number"
                id="budget"
                name="budget"
                min="0"
                step="0.01"
                placeholder="e.g. 300000"
                required
            >

        </div>


        <button
            type="submit"
            class="create-btn"
        >
            Create Event
        </button>

    </form>


    <a
        href="index.php?page=customer_dashboard"
        class="back-link"
    >
        Back to Dashboard
    </a>

</div>

</body>

</html>