<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase 2026</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="navbar">
        <div class="brand">
            <a href="?url=home/index">Event<span>Ease</span></a>
        </div>

        <div class="nav-links">
            <a href="?url=home/index">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="margin-right:10px;">
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                    (<?php echo ucfirst($_SESSION['role']); ?>)
                </span>

            <?php if ($_SESSION['role'] === 'customer'): ?>
                <a href="?url=event/dashboard">Dashboard</a>

            <?php elseif ($_SESSION['role'] === 'vendor'): ?>
                <a href="?url=vendor/dashboard">Dashboard</a>

            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                <a href="?url=admin/dashboard">Dashboard</a>

            <?php endif; ?>
                <a href="?url=auth/logout">Logout</a>

            <?php else: ?>
                <a href="?url=auth/login">Login</a>
                <a href="?url=auth/registerCustomer">Register as Customer</a>
                <a href="?url=auth/registerVendor">Register as Vendor</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $err): ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; unset($_SESSION['errors']); ?>
                </ul>
            </div>

        <?php endif; ?>