<?php
class AuthController {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }
        require_once '../app/views/auth/login.php';
    }

    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password']) && $user['status'] == 'active') {
                if ($user['role'] == 'vendor') {
                    $vendor = Vendor::findByUserId($user['user_id']);
                    if ($vendor && $vendor['verification_status'] != 'approved') {
                        $_SESSION['error'] = "Your vendor account is not approved yet. Please wait for admin approval.";
                        header('Location: ?url=auth/login');
                        exit;
                    }
                }
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
                $this->redirectBasedOnRole();
            } else {
                $_SESSION['error'] = "Invalid login details.";
                header('Location: ?url=auth/login');
                exit;
            }
        } else {
            header('Location: ?url=auth/login');
            exit;
        }
    }

    private function redirectBasedOnRole() {
        switch ($_SESSION['role']) {
            case 'customer':
                header('Location: ?url=event/dashboard');
                break;
            case 'vendor':
                header('Location: ?url=vendor/dashboard');
                break;
            case 'admin':
                header('Location: ?url=admin/dashboard');
                break;
            default:
                header('Location: ?url=auth/login');
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: ?url=auth/login');
        exit;
    }

    public function registerCustomer() {
        require_once '../app/views/auth/register_customer.php';
    }

    public function doRegisterCustomer() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validateCustomerRegistration($_POST);
            if (empty($errors)) {
                $userData = [
                    'first_name' => trim($_POST['first_name']),
                    'last_name' => trim($_POST['last_name']),
                    'email' => trim($_POST['email']),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone' => trim($_POST['phone']),
                    'role' => 'customer'
                ];
                $userId = User::create($userData);
                Customer::create($userId, trim($_POST['address']));
                $_SESSION['success'] = "Registration successful! Please login.";
                header('Location: ?url=auth/login');
                exit;
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ?url=auth/registerCustomer');
                exit;
            }
        }
    }

    public function registerVendor() {
        require_once '../app/views/auth/register_vendor.php';
    }

    public function doRegisterVendor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validateRegistration($_POST);
            if (empty($_POST['business_name'])) {
                $errors[] = "Please enter your business name.";
            }
            if (!empty($_POST['facebook_profile']) && !filter_var($_POST['facebook_profile'], FILTER_VALIDATE_URL)) {
                $errors[] = "Please enter a valid Facebook URL.";
            }
            if (empty($errors)) {
                $userData = [
                    'first_name' => trim($_POST['first_name']),
                    'last_name' => trim($_POST['last_name']),
                    'email' => trim($_POST['email']),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone' => trim($_POST['phone']),
                    'role' => 'vendor'
                ];
                $userId = User::create($userData);
                $vendorData = [
                    'user_id' => $userId,
                    'business_name' => trim($_POST['business_name']),
                    'business_description' => trim($_POST['business_description']),
                    'business_address' => trim($_POST['business_address']),
                    'facebook_profile' => trim($_POST['facebook_profile'])
                ];
                Vendor::create($vendorData);
                $_SESSION['success'] = "Vendor registration submitted! Please wait for admin approval before logging in.";
                header('Location: ?url=auth/login');
                exit;
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ?url=auth/registerVendor');
                exit;
            }
        }
    }

    private function validateCustomerRegistration($data) {
    $errors = [];

    if (empty($data['first_name']) || strlen($data['first_name']) > 50) {
        $errors[] = "Please enter a valid first name.";
    }

    if (empty($data['last_name']) || strlen($data['last_name']) > 50) {
        $errors[] = "Please enter a valid last name.";
    }

    // Email validation
    $email = trim($data['email'] ?? '');

    $allowedDomains = [
        '@gmail.com',
        '@yahoo.com',
        '@hotmail.com',
        '@outlook.com'
    ];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } else {

        $emailLower = strtolower($email);
        $validDomain = false;

        foreach ($allowedDomains as $domain) {
            if (substr($emailLower, -strlen($domain)) === $domain) {
                $validDomain = true;
                break;
            }
        }

        if (!$validDomain) {
            $errors[] = "Customer email must end with @gmail.com, @yahoo.com, @hotmail.com, or @outlook.com.";
        } else {

            $existing = User::findByEmail($email);

            if ($existing) {
                $errors[] = "This email is already registered.";
            }
        }
    }

    // Password validation
    $password = $data['password'] ?? '';

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    } else {

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one capital letter.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one symbol.";
        }
    }

    // Password confirmation
    if (empty($data['confirm_password'])) {
        $errors[] = "Please retype your password to confirm it.";
    } elseif ($password !== $data['confirm_password']) {
        $errors[] = "Passwords do not match.";
    }

    // Phone validation
    if (empty($data['phone'])) {
        $errors[] = "Please enter a valid phone number.";
    } elseif (!preg_match('/^0\d{9}$/', $data['phone'])) {
        $errors[] = "Please enter a valid Sri Lankan phone number.";
    }

    // Address validation
    if (empty(trim($data['address'] ?? ''))) {
        $errors[] = "Please enter your address.";
    }

    return $errors;
    }

    private function validateRegistration($data) {
        $errors = [];
        if (empty($data['first_name']) || strlen($data['first_name']) > 50) {
            $errors[] = "Please enter a valid first name.";
        }
        if (empty($data['last_name']) || strlen($data['last_name']) > 50) {
            $errors[] = "Please enter a valid last name.";
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        } else {
            $existing = User::findByEmail($data['email']);
            if ($existing) {
                $errors[] = "This email is already registered.";
            }
        }
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = "Password must contain at least 6 characters.";
        }
        if (empty($data['phone'])) {
            $errors[] = "Please enter a valid phone number.";
        } else {
            if (!preg_match('/^0\d{9}$/', $data['phone'])) {
                $errors[] = "Please enter a valid Sri Lankan phone number.";
            }
        }
        return $errors;
    }

    public function forgotPassword() {
        require_once '../app/views/auth/forgot_password.php';
    }

    public function verifyEmail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $user = User::findByEmail($email);
            if ($user) {
                $_SESSION['reset_user_id'] = $user['user_id'];
                $_SESSION['reset_email'] = $email;
                header('Location: ?url=auth/resetPassword');
                exit;
            } else {
                $_SESSION['error'] = "Email not found.";
                header('Location: ?url=auth/forgotPassword');
                exit;
            }
        }
    }

    public function resetPassword() {
        if (!isset($_SESSION['reset_user_id'])) {
            header('Location: ?url=auth/forgotPassword');
            exit;
        }
        require_once '../app/views/auth/reset_password.php';
    }

    public function doResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['reset_user_id'])) {
                header('Location: ?url=auth/forgotPassword');
                exit;
            }
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters.";
                header('Location: ?url=auth/resetPassword');
                exit;
            }
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match.";
                header('Location: ?url=auth/resetPassword');
                exit;
            }
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            User::updatePassword($_SESSION['reset_user_id'], $hashed);
            unset($_SESSION['reset_user_id'], $_SESSION['reset_email']);
            $_SESSION['success'] = "Password updated successfully. Please login.";
            header('Location: ?url=auth/login');
            exit;
        }
    }
}
?>