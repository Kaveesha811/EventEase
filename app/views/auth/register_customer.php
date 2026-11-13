<?php include '../app/views/partials/header.php'; ?>
<h2>Customer Registration</h2>

<form action="?url=auth/doRegisterCustomer" method="POST">
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo isset($_SESSION['old']['first_name']) ? htmlspecialchars($_SESSION['old']['first_name']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo isset($_SESSION['old']['last_name']) ? htmlspecialchars($_SESSION['old']['last_name']) : ''; ?>" required>
    </div>

    <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo isset($_SESSION['old']['email']) ? htmlspecialchars($_SESSION['old']['email']) : ''; ?>" required>
</div>

    <div class="form-group">
    <label>Password</label>
    <input type="password"
           name="password"
           id="password"
           minlength="8"
           required>

    <small style="display:block; margin-top:5px; color:#7f8c8d;">
        At least 8 characters, including a capital letter,
        lowercase letter, number, and symbol.
    </small>
</div>

<div class="form-group">
    <label>Retype Password</label>

    <input type="password"
           name="confirm_password"
           id="confirm_password"
           minlength="8"
           required>
</div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo isset($_SESSION['old']['phone']) ? htmlspecialchars($_SESSION['old']['phone']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" value="<?php echo isset($_SESSION['old']['address']) ? htmlspecialchars($_SESSION['old']['address']) : ''; ?>" required>
    </div>

    <button type="submit" class="btn">Register</button>

</form>

<script>
    const customerRegisterForm =
        document.querySelector('form[action*="doRegisterCustomer"]');

    customerRegisterForm.addEventListener('submit', function (event) {

        const password =
            document.getElementById('password').value;

        const confirmPassword =
            document.getElementById('confirm_password').value;

        const email =
            document.querySelector('input[name="email"]')
            .value.trim()
            .toLowerCase();

        const allowedDomains = [
            '@gmail.com',
            '@yahoo.com',
            '@hotmail.com',
            '@outlook.com'
        ];

        const validEmailDomain =
            allowedDomains.some(domain =>
                email.endsWith(domain)
            );

        const passwordValid =
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[^A-Za-z0-9]/.test(password);

        if (!validEmailDomain) {
            alert(
                'Please use a Gmail, Yahoo, Hotmail, or Outlook email address.'
            );

            event.preventDefault();
            return;
        }

        if (!passwordValid) {
            alert(
                'Password must be at least 8 characters and contain a capital letter, lowercase letter, number, and symbol.'
            );

            event.preventDefault();
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match.');

            event.preventDefault();
        }
    });
</script>

<?php include '../app/views/partials/footer.php'; ?>