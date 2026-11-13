<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - EventEase</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

<div class="register-container">

    <h1>EventEase</h1>

    <p>Choose the account type you want to create</p>

    <div class="role-choice" aria-label="Choose account type">

        <a
            href="index.php?page=register"
            class="role-choice-link active"
        >
            Customer
        </a>

        <a
            href="index.php?page=vendor_register"
            class="role-choice-link"
        >
            Vendor
        </a>

    </div>

    <p class="auth-form-title">
        Create your customer account
    </p>

    <?php if (!empty($message)): ?>

    <p class="auth-error">
        <?= htmlspecialchars($message) ?>
    </p>
    
    <?php endif; ?>

    <form method="POST" action="index.php?page=register">

        <div class="form-group">
            <label for="firstName">First Name</label>
            <input
                type="text"
                id="firstName"
                name="firstName"
                value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>"
                pattern="[A-Za-z][A-Za-z .'-]{1,49}"
                required
            >
        </div>

        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input
                type="text"
                id="lastName"
                name="lastName"
                value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>"
                pattern="[A-Za-z][A-Za-z .'-]{1,49}"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                pattern="[0-9+() -]{9,20}"
                title="Enter a valid phone number."
                required
            >
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
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
            <input
                type="password"
                id="confirmPassword"
                name="confirmPassword"
                minlength="8"
                required
            >
        </div>

        <button type="submit" class="register-btn">
            Create Account
        </button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </div>

</div>

</body>
</html>