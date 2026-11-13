<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
<div class="login-container">
    <h1>Reset Password</h1>
    <p class="subtitle">Enter the email and phone number used when registering.</p>

    <?php if (!empty($message)): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=forgot_password">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9+() -]{9,20}" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword" name="newPassword" minlength="8" pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm New Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" minlength="8" required>
        </div>
        <button type="submit" class="login-btn">Reset Password</button>
    </form>
    <div class="register-link"><a href="index.php?page=login">Back to Login</a></div>
</div>
</body>
</html>
