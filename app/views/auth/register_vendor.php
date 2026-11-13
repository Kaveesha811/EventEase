<?php include '../app/views/partials/header.php'; ?>

<h2>Vendor Registration</h2>
<p>Register your business to offer services on EventEase. Your account will be reviewed by admin before you can log in.</p>

<?php
// Pre-fill with old input if validation failed
$old = isset($_SESSION['old']) ? $_SESSION['old'] : [];
$firstName = isset($old['first_name']) ? $old['first_name'] : '';
$lastName = isset($old['last_name']) ? $old['last_name'] : '';
$email = isset($old['email']) ? $old['email'] : '';
$phone = isset($old['phone']) ? $old['phone'] : '';
$businessName = isset($old['business_name']) ? $old['business_name'] : '';
$businessDescription = isset($old['business_description']) ? $old['business_description'] : '';
$businessAddress = isset($old['business_address']) ? $old['business_address'] : '';
$facebookProfile = isset($old['facebook_profile']) ? $old['facebook_profile'] : '';
unset($_SESSION['old']);
?>

<form action="?url=auth/doRegisterVendor" method="POST">
    <h3>Personal Details</h3>
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
    </div>

    <h3>Business Details</h3>
    <div class="form-group">
        <label>Business Name</label>
        <input type="text" name="business_name" value="<?php echo htmlspecialchars($businessName); ?>" required>
    </div>
    <div class="form-group">
        <label>Business Description</label>
        <textarea name="business_description" rows="4"><?php echo htmlspecialchars($businessDescription); ?></textarea>
    </div>
    <div class="form-group">
        <label>Business Address</label>
        <input type="text" name="business_address" value="<?php echo htmlspecialchars($businessAddress); ?>" required>
    </div>
    <div class="form-group">
        <label>Facebook Profile URL (optional)</label>
        <input type="url" name="facebook_profile" value="<?php echo htmlspecialchars($facebookProfile); ?>">
    </div>

    <button type="submit" class="btn">Register as Vendor</button>
    <p>Already have an account? <a href="?url=auth/login">Login here</a></p>
</form>

<?php include '../app/views/partials/footer.php'; ?>