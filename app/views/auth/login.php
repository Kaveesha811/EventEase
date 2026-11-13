<?php include '../app/views/partials/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <p style="text-align:center; color:#7f8c8d; margin-bottom:25px;">Login to EventEase</p>

        <form action="?url=auth/doLogin" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="auth-links">
            <p><a href="?url=auth/forgotPassword">Forgot Password?</a></p>
            <p>New customer? <a href="?url=auth/registerCustomer">Register here</a></p>
            <p>Vendor? <a href="?url=auth/registerVendor">Register your business</a></p>
        </div>
    </div>
</div>

<?php include '../app/views/partials/footer.php'; ?>