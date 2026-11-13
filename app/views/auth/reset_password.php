<?php include '../app/views/partials/header.php'; ?>
<h2>Reset Password</h2>
<form action="?url=auth/doResetPassword" method="POST">
    <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" required>
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn">Update Password</button>
</form>
<?php include '../app/views/partials/footer.php'; ?>