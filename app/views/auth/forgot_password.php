<?php include '../app/views/partials/header.php'; ?>
<h2>Forgot Password</h2>
<form action="?url=auth/verifyEmail" method="POST">
    <div class="form-group">
        <label>Enter your registered email</label>
        <input type="email" name="email" required>
    </div>
    <button type="submit" class="btn">Next</button>
</form>
<?php include '../app/views/partials/footer.php'; ?>