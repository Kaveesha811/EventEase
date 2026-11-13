<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - EventEase</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="auth-page">

<div class="login-container">

    <h1>EventEase</h1>

    <p class="subtitle">
        Login to your account
    </p>

    <?php if (!empty($message)): ?>

        <p class="error-message">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>

    <form
        method="POST"
        action="index.php?page=login"
    >

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
            >

        </div>

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>

        <div class="forgot-link-row">
            <a href="index.php?page=forgot_password">Forgot password?</a>
        </div>

        <button
            type="submit"
            class="login-btn"
        >
            Login
        </button>

    </form>

    <div class="register-link">

        Don't have an account?

        <a href="index.php?page=register">
            Create Account
        </a>

    </div>

</div>

</body>

</html>