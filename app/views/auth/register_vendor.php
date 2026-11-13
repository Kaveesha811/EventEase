<?php
/*
|--------------------------------------------------------------------------
| Vendor Registration View
|--------------------------------------------------------------------------
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Vendor Registration - EventEase</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body class="auth-page">

<div class="auth-container">

    <div class="auth-card">

        <h1>Vendor Registration</h1>

        <p class="auth-description">
            Choose the account type you want to create.
        </p>

        <div class="role-choice" aria-label="Choose account type">

            <a
                href="index.php?page=register"
                class="role-choice-link"
            >
                Customer
            </a>

            <a
                href="index.php?page=vendor_register"
                class="role-choice-link active"
            >
                Vendor
            </a>

        </div>

        <p class="auth-form-title">
            Create your vendor account and offer your event services.
        </p>


        <?php if (!empty($message)): ?>
            <p class="auth-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form
            method="POST"
            action="index.php?page=vendor_register"
        >

            <!-- First Name -->

            <div class="form-group">

                <label for="firstName">
                    First Name
                </label>

                <input
                    type="text"
                    id="firstName"
                    name="firstName"
                    pattern="[A-Za-z][A-Za-z .'-]{1,49}"
                    value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>"
                    required
                >

            </div>


            <!-- Last Name -->

            <div class="form-group">

                <label for="lastName">
                    Last Name
                </label>

                <input
                    type="text"
                    id="lastName"
                    name="lastName"
                    pattern="[A-Za-z][A-Za-z .'-]{1,49}"
                    value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>"
                    required
                >

            </div>


            <!-- Email -->

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required
                >

            </div>


            <!-- Password -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}"
                    title="Use at least 8 characters with uppercase, lowercase, and a number."
                    required
                >

            </div>


            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" minlength="8" required>
            </div>

            <!-- Phone -->

            <div class="form-group">

                <label for="phone">
                    Phone Number
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    pattern="[0-9+() -]{9,20}"
                    title="Enter a valid phone number."
                    value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                    required
                >

            </div>


            <!-- Business Name -->

            <div class="form-group">

                <label for="businessName">
                    Business Name
                </label>

                <input
                    type="text"
                    id="businessName"
                    name="businessName"
                    value="<?= htmlspecialchars($_POST['businessName'] ?? '') ?>"
                    required
                >

            </div>


            <!-- Business Description -->

            <div class="form-group">

                <label for="businessDescription">
                    Business Description
                </label>

                <textarea
                    id="businessDescription"
                    name="businessDescription"
                    rows="4"
                    placeholder="Describe your business and the event services you provide..."
                    required
                ></textarea>

            </div>


            <!-- Business Address -->

            <div class="form-group">

                <label for="businessAddress">
                    Business Address
                </label>

                <textarea
                    id="businessAddress"
                    name="businessAddress"
                    rows="3"
                    required
                ></textarea>

            </div>

            <div class="form-group">
                <label for="facebookProfile">Facebook Profile / Page URL (Optional)</label>
                <input
                    type="url"
                    id="facebookProfile"
                    name="facebookProfile"
                    placeholder="https://www.facebook.com/yourbusiness"
                    value="<?= htmlspecialchars($_POST['facebookProfile'] ?? '') ?>"
                >
                <small class="form-help">Customers can use this link to view your Facebook business profile.</small>
            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn"
            >
                Register as Vendor
            </button>

        </form>


        <div class="auth-footer">

            <p>
                Already have an account?
                <a href="index.php?page=login">
                    Login
                </a>
            </p>

            <p>
                Vendor accounts require
                <strong>admin verification</strong>
                before they can access the vendor dashboard.
            </p>

        </div>

    </div>

</div>

</body>

</html>