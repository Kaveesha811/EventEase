<?php

session_start();

require_once __DIR__ .
    '/../app/controllers/AuthController.php';

$page = $_GET['page'] ?? 'register';

$authController = new AuthController();


function isStrongPassword($password)
{
    return strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}




function isValidName($name)
{
    return preg_match("/^[A-Za-z][A-Za-z .'-]{1,49}$/", $name);
}

function isValidPhone($phone)
{
    return preg_match('/^[0-9+() -]{9,20}$/', $phone);
}

function renderSuccessPage(
    $title,
    $message,
    $description,
    $actionUrl = 'index.php?page=login',
    $actionText = 'Go to Login',
    $secondaryUrl = '',
    $secondaryText = ''
) {
    $safeTitle = htmlspecialchars(
        $title,
        ENT_QUOTES,
        'UTF-8'
    );

    $safeMessage = htmlspecialchars(
        $message,
        ENT_QUOTES,
        'UTF-8'
    );

    $safeDescription = htmlspecialchars(
        $description,
        ENT_QUOTES,
        'UTF-8'
    );

    $safeActionUrl = htmlspecialchars(
        $actionUrl,
        ENT_QUOTES,
        'UTF-8'
    );

    $safeActionText = htmlspecialchars(
        $actionText,
        ENT_QUOTES,
        'UTF-8'
    );

    $secondaryAction = '';

    if ($secondaryUrl !== '' && $secondaryText !== '') {
        $safeSecondaryUrl = htmlspecialchars(
            $secondaryUrl,
            ENT_QUOTES,
            'UTF-8'
        );

        $safeSecondaryText = htmlspecialchars(
            $secondaryText,
            ENT_QUOTES,
            'UTF-8'
        );

        $secondaryAction = <<<HTML
        <a href="{$safeSecondaryUrl}" class="success-secondary-link">
            {$safeSecondaryText}
        </a>
HTML;
    }

    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$safeTitle} - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <main class="success-card">
        <div class="success-icon">&#10003;</div>
        <h1>{$safeMessage}</h1>
        <p>{$safeDescription}</p>
        <a href="{$safeActionUrl}" class="btn btn-primary">
            {$safeActionText}
        </a>
        {$secondaryAction}
    </main>
</body>
</html>
HTML;
}


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

if ($page === 'register') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $firstName =
            trim($_POST['firstName'] ?? '');

        $lastName =
            trim($_POST['lastName'] ?? '');

        $email =
            trim($_POST['email'] ?? '');

        $password =
            $_POST['password'] ?? '';

        $confirmPassword =
            $_POST['confirmPassword'] ?? '';

        $phone =
            trim($_POST['phone'] ?? '');

        $address =
            trim($_POST['address'] ?? '');


        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($password) ||
            empty($confirmPassword) ||
            empty($phone) ||
            empty($address)
        ) {

            $message =
                'Please fill in all fields.';

            require_once __DIR__ .
                '/../app/views/auth/register.php';

        } elseif (!isValidName($firstName) || !isValidName($lastName)) {

            $message = 'Please enter valid first and last names.';
            require_once __DIR__ . '/../app/views/auth/register.php';

        } elseif (!isValidPhone($phone)) {

            $message = 'Please enter a valid phone number.';
            require_once __DIR__ . '/../app/views/auth/register.php';

        } elseif ($password !== $confirmPassword) {

            $message = 'Passwords do not match.';
            require_once __DIR__ . '/../app/views/auth/register.php';

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $message =
                'Please enter a valid email address.';

            require_once __DIR__ .
                '/../app/views/auth/register.php';

        } elseif (!isStrongPassword($password)) {

            $message =
                'Password must be at least 8 characters and include uppercase, lowercase, and a number.';

            require_once __DIR__ .
                '/../app/views/auth/register.php';

        } else {

            $result =
                $authController->register(
                    $firstName,
                    $lastName,
                    $email,
                    $password,
                    $phone,
                    $address
                );

            if ($result['success']) {
                renderSuccessPage(
                    'Registration Successful',
                    $result['message'],
                    'Your EventEase account is ready. You can now log in and start planning your event.'
                );

            } else {

                $message =
                    $result['message'];

                require_once __DIR__ .
                    '/../app/views/auth/register.php';
            }
        }

    } else {

        require_once __DIR__ .
            '/../app/views/auth/register.php';
    }
}


/*
|--------------------------------------------------------------------------
| VENDOR REGISTRATION
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_register') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /*
         * Get personal details
         */

        $firstName =
            trim($_POST['firstName'] ?? '');

        $lastName =
            trim($_POST['lastName'] ?? '');

        $email =
            trim($_POST['email'] ?? '');

        $password =
            $_POST['password'] ?? '';

        $confirmPassword =
            $_POST['confirmPassword'] ?? '';

        $phone =
            trim($_POST['phone'] ?? '');


        /*
         * Get business details
         */

        $businessName =
            trim($_POST['businessName'] ?? '');

        $businessDescription =
            trim($_POST['businessDescription'] ?? '');

        $businessAddress =
            trim($_POST['businessAddress'] ?? '');

        $facebookProfile =
            trim($_POST['facebookProfile'] ?? '');


        /*
         * Basic validation
         */

        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($password) ||
            empty($confirmPassword) ||
            empty($phone) ||
            empty($businessName) ||
            empty($businessDescription) ||
            empty($businessAddress)
        ) {

            $message =
                'Please fill in all fields.';

            require_once __DIR__ .
                '/../app/views/auth/register_vendor.php';

        } elseif (!isValidName($firstName) || !isValidName($lastName)) {

            $message = 'Please enter valid first and last names.';
            require_once __DIR__ . '/../app/views/auth/register_vendor.php';

        } elseif (!isValidPhone($phone)) {

            $message = 'Please enter a valid phone number.';
            require_once __DIR__ . '/../app/views/auth/register_vendor.php';

        } elseif ($password !== $confirmPassword) {

            $message = 'Passwords do not match.';
            require_once __DIR__ . '/../app/views/auth/register_vendor.php';

        } elseif ($facebookProfile !== '' && !filter_var($facebookProfile, FILTER_VALIDATE_URL)) {

            $message = 'Please enter a valid Facebook profile URL.';
            require_once __DIR__ . '/../app/views/auth/register_vendor.php';

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $message =
                'Please enter a valid email address.';

            require_once __DIR__ .
                '/../app/views/auth/register_vendor.php';

        } elseif (strlen($password) < 6) {

            $message =
                'Password must be at least 8 characters and include uppercase, lowercase, and a number.';

            require_once __DIR__ .
                '/../app/views/auth/register_vendor.php';

        } elseif (!isStrongPassword($password)) {

            $message =
                'Password must be at least 8 characters and include uppercase, lowercase, and a number.';

            require_once __DIR__ .
                '/../app/views/auth/register_vendor.php';

        } else {

            /*
             * Register Vendor
             */

            $result =
                $authController
                    ->registerVendor(
                        $firstName,
                        $lastName,
                        $email,
                        $password,
                        $phone,
                        $businessName,
                        $businessDescription,
                        $businessAddress,
                        $facebookProfile
                    );


            /*
             * Registration successful
             */

            if ($result['success']) {
                renderSuccessPage(
                    'Vendor Registration Successful',
                    $result['message'],
                    'Your vendor account is waiting for admin verification. You can log in after it is approved.'
                    , 'index.php?page=login'
                    , 'Go to Login'
                    , 'index.php?page=vendor_register'
                    , 'Back to Registration'
                );

            } else {

                /*
                 * Registration failed
                 */

                $message =
                    $result['message'];

                require_once __DIR__ .
                    '/../app/views/auth/register_vendor.php';
            }
        }

    } else {

        /*
         * Display Vendor Registration Form
         */

        require_once __DIR__ .
            '/../app/views/auth/register_vendor.php';
    }


}


/*
|--------------------------------------------------------------------------
| VENDOR SERVICES
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_services') {

    require_once __DIR__ .
        '/../app/controllers/ServiceController.php';


    /*
     * Only logged-in Vendors
     * can manage services.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    /*
     * Create Service Controller
     */

    $serviceController =
        new ServiceController();


    /*
     * Handle Add Service
     */

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
    ) {

        $serviceCategoryID =
            $_POST['serviceCategoryID'] ?? 0;

        $serviceName =
            trim(
                $_POST['serviceName'] ?? ''
            );

        $description =
            trim(
                $_POST['description'] ?? ''
            );

        $price =
            $_POST['price'] ?? '';


        /*
         * Validate form data
         */

        if (
            !filter_var(
                $serviceCategoryID,
                FILTER_VALIDATE_INT
            ) ||
            empty($serviceName) ||
            empty($description) ||
            $price === ''
        ) {

            $message =
                'Please fill in all fields.';

        } elseif ($price < 0) {

            $message =
                'Price cannot be negative.';

        } else {

            /*
             * Create service
             */

            $result =
                $serviceController
                    ->createService(
                        $vendorID,
                        $serviceCategoryID,
                        $serviceName,
                        $description,
                        $price
                    );


            if ($result) {

                $message =
                    'Service added successfully.';

            } else {

                $message =
                    'Failed to add service.';
            }
        }
    }


    /*
     * Get Vendor Services
     */

    $services =
        $serviceController
            ->getVendorServices(
                $vendorID
            );


    /*
     * Get Active Categories
     */

    $categories =
        $serviceController
            ->getActiveServiceCategories();


    /*
     * Display Services Page
     */

    require_once __DIR__ .
        '/../app/views/vendor/services.php';
}

elseif ($page === 'edit_service') {

    require_once __DIR__ . '/../app/controllers/ServiceController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    $vendorID = $_SESSION['vendor_id'] ?? 0;

    $serviceID = $_GET['id'] ?? 0;

    if (
        !filter_var(
            $serviceID,
            FILTER_VALIDATE_INT
        )
    ) {
        header(
            'Location: index.php?page=vendor_services'
        );
        exit;
    }

    $serviceController =
        new ServiceController();


    /*
     * Update Service
     */

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $serviceCategoryID =
            $_POST['serviceCategoryID'] ?? 0;

        $serviceName =
            trim(
                $_POST['serviceName'] ?? ''
            );

        $description =
            trim(
                $_POST['description'] ?? ''
            );

        $price =
            $_POST['price'] ?? '';


        if (
            !filter_var(
                $serviceCategoryID,
                FILTER_VALIDATE_INT
            ) ||
            empty($serviceName) ||
            empty($description) ||
            $price === ''
        ) {

            $message =
                'Please fill in all fields.';

        } elseif ($price < 0) {

            $message =
                'Price cannot be negative.';

        } else {

            $result =
                $serviceController->updateService(
                    $serviceID,
                    $vendorID,
                    $serviceCategoryID,
                    $serviceName,
                    $description,
                    $price
                );


            if ($result) {

                header(
                    'Location: index.php?page=vendor_services'
                );

                exit;

            } else {

                $message =
                    'Failed to update service.';
            }
        }
    }


    /*
     * Get Existing Service
     */

    $service =
        $serviceController->getVendorService(
            $serviceID,
            $vendorID
        );


    /*
     * Get Service Categories
     */

    $categories =
        $serviceController
            ->getActiveServiceCategories();


    /*
     * Service Not Found
     */

    if (!$service) {

        header(
            'Location: index.php?page=vendor_services'
        );

        exit;
    }


    require_once
        __DIR__ .
        '/../app/views/vendor/edit_service.php';
}

elseif ($page === 'deactivate_service') {

    require_once __DIR__ . '/../app/controllers/ServiceController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header('Location: index.php?page=login');
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: index.php?page=vendor_services'
        );
        exit;
    }


    $vendorID =
        $_SESSION['vendor_id'] ?? 0;

    $serviceID =
        $_POST['serviceID'] ?? 0;


    if (
        !filter_var(
            $serviceID,
            FILTER_VALIDATE_INT
        )
    ) {
        header(
            'Location: index.php?page=vendor_services'
        );
        exit;
    }


    $serviceController =
        new ServiceController();


    $result =
        $serviceController->deactivateService(
            $serviceID,
            $vendorID
        );


    if ($result) {

        $_SESSION['service_message'] =
            'Service deactivated successfully.';

    } else {

        $_SESSION['service_message'] =
            'Failed to deactivate service.';
    }


    header(
        'Location: index.php?page=vendor_services'
    );

    exit;
}



/*
|--------------------------------------------------------------------------
| VENDOR PACKAGES
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_packages') {

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';


    /*
     * Only logged-in Vendors
     * can manage packages.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    /*
     * Create Package Controller
     */

    $packageController =
        new PackageController();


    /*
     * Get Vendor Packages
     */

    $vendorPackages =
        $packageController
            ->getVendorPackages(
                $vendorID
            );


    /*
     * Get Vendor's Active Services
     */

    $vendorServices =
        $packageController
            ->getVendorActiveServices(
                $vendorID
            );

    $eventTypes =
        $packageController
            ->getActiveEventTypes();


    /*
     * Display Vendor Packages Page
     */

    require_once __DIR__ .
        '/../app/views/vendor/packages.php';
}


/*
|--------------------------------------------------------------------------
| ADD PACKAGE
|--------------------------------------------------------------------------
*/

elseif ($page === 'add_package') {

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    $packageController =
        new PackageController();


    /*
     * Get Form Data
     */

    $packageName =
        trim(
            $_POST['package_name'] ?? ''
        );

    $description =
        trim(
            $_POST['description'] ?? ''
        );

    $price =
        $_POST['price'] ?? '';

    $serviceIDs =
        $_POST['service_ids'] ?? [];

    $eventTypeIDs =
        $_POST['event_type_ids'] ?? [];


    /*
     * Validate Package Name
     */

    if (
        empty($packageName)
    ) {

        $_SESSION['package_message'] =
            'Package name is required.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Validate Description
     */

    if (
        empty($description)
    ) {

        $_SESSION['package_message'] =
            'Package description is required.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Validate Price
     */

    if (
        $price === '' ||
        !is_numeric($price)
    ) {

        $_SESSION['package_message'] =
            'Please enter a valid package price.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    if ($price < 0) {

        $_SESSION['package_message'] =
            'Package price cannot be negative.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Validate Services
     */

    if (
        !is_array($serviceIDs) ||
        empty($serviceIDs)
    ) {

        $_SESSION['package_message'] =
            'Please select at least one service.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }

    if (
        !is_array($eventTypeIDs) ||
        empty($eventTypeIDs)
    ) {
        $_SESSION['package_message'] =
            'Please select at least one suitable event type.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Clean Service IDs
     */

    $serviceIDs =
        array_unique(
            array_map(
                'intval',
                $serviceIDs
            )
        );

    $eventTypeIDs = array_unique(
        array_map('intval', $eventTypeIDs)
    );

    $servicesTotal =
        $packageController
            ->getServicesTotal(
                $vendorID,
                $serviceIDs
            );

    if ($servicesTotal <= 0) {
        $_SESSION['package_message'] =
            'The selected services must have a valid total price.';

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }

    $price = round($servicesTotal * 0.90, 2);


    /*
     * Create Package
     */

    $result =
        $packageController
            ->createPackage(
                $vendorID,
                $packageName,
                $description,
                $price,
                $serviceIDs,
                $eventTypeIDs
            );


    /*
     * Store Result Message
     */

    if ($result) {

        $_SESSION['package_message'] =
            'Package created successfully.';

    } else {

        $_SESSION['package_message'] =
            'Failed to create package.';
    }


    /*
     * Return to Package Page
     */

    header(
        'Location: index.php?page=vendor_packages'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| EDIT PACKAGE
|--------------------------------------------------------------------------
*/

elseif ($page === 'edit_package') {

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    /*
     * Get Package ID
     */

    $packageID =
        $_GET['id'] ?? 0;


    /*
     * Validate Package ID
     */

    if (
        !filter_var(
            $packageID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Create Controller
     */

    $packageController =
        new PackageController();


    /*
     * Update Package
     */

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
    ) {

        $packageName =
            trim(
                $_POST['package_name'] ?? ''
            );

        $description =
            trim(
                $_POST['description'] ?? ''
            );

        $price =
            $_POST['price'] ?? '';

        $serviceIDs =
            $_POST['service_ids'] ?? [];

        $eventTypeIDs =
            $_POST['event_type_ids'] ?? [];


        /*
         * Validate Package Name
         */

        if (
            empty($packageName)
        ) {

            $message =
                'Package name is required.';

        }


        /*
         * Validate Description
         */

        elseif (
            empty($description)
        ) {

            $message =
                'Package description is required.';

        }


        /*
         * Validate Price
         */

        elseif (
            $price === '' ||
            !is_numeric($price)
        ) {

            $message =
                'Please enter a valid package price.';

        }


        elseif ($price < 0) {

            $message =
                'Package price cannot be negative.';

        }


        /*
         * Validate Services
         */

        elseif (
            !is_array($serviceIDs) ||
            empty($serviceIDs)
        ) {

            $message =
                'Please select at least one service.';

        }

        elseif (
            !is_array($eventTypeIDs) ||
            empty($eventTypeIDs)
        ) {
            $message =
                'Please select at least one suitable event type.';

        }


        else {

            /*
             * Clean Service IDs
             */

            $serviceIDs =
                array_unique(
                    array_map(
                        'intval',
                        $serviceIDs
                    )
                );

            $eventTypeIDs = array_unique(
                array_map('intval', $eventTypeIDs)
            );

            $servicesTotal =
                $packageController
                    ->getServicesTotal(
                        $vendorID,
                        $serviceIDs
                    );

            if ($servicesTotal <= 0) {
                $message =
                    'The selected services must have a valid total price.';
            }

            $price = round($servicesTotal * 0.90, 2);


            /*
             * Update Package
             */

            $result =
                $packageController
                    ->updatePackage(
                        $packageID,
                        $vendorID,
                        $packageName,
                        $description,
                        $price,
                        $serviceIDs,
                        $eventTypeIDs
                    );


            if ($result) {

                $_SESSION['package_message'] =
                    'Package updated successfully.';

                header(
                    'Location: index.php?page=vendor_packages'
                );

                exit;

            } else {

                $message =
                    'Failed to update package.';
            }
        }
    }


    /*
     * Get Existing Package
     */

    $package =
        $packageController
            ->getVendorPackage(
                $packageID,
                $vendorID
            );


    /*
     * Package Not Found
     */

    if (!$package) {

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Get Vendor Services
     */

    $vendorServices =
        $packageController
            ->getVendorActiveServices(
                $vendorID
            );

    $eventTypes =
        $packageController
            ->getActiveEventTypes();


    /*
     * Get Selected Services
     */

    $selectedServiceIDs =
        $packageController
            ->getPackageServiceIDs(
                $packageID,
                $vendorID
            );

    $selectedEventTypeIDs =
        $packageController
            ->getPackageEventTypeIDs(
                $packageID,
                $vendorID
            );


    /*
     * Display Edit Page
     */

    require_once __DIR__ .
        '/../app/views/vendor/edit_package.php';
}


/*
|--------------------------------------------------------------------------
| DEACTIVATE PACKAGE
|--------------------------------------------------------------------------
*/

elseif ($page === 'deactivate_package') {

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    /*
     * Get Package ID
     */

    $packageID =
        $_POST['package_id'] ?? 0;


    /*
     * Validate Package ID
     */

    if (
        !filter_var(
            $packageID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=vendor_packages'
        );

        exit;
    }


    /*
     * Deactivate Package
     */

    $packageController =
        new PackageController();


    $result =
        $packageController
            ->deactivatePackage(
                $packageID,
                $vendorID
            );


    /*
     * Store Message
     */

    if ($result) {

        $_SESSION['package_message'] =
            'Package deactivated successfully.';

    } else {

        $_SESSION['package_message'] =
            'Failed to deactivate package.';
    }


    /*
     * Return to Package Page
     */

    header(
        'Location: index.php?page=vendor_packages'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| VENDOR AVAILABILITY
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_availability') {

    require_once __DIR__ .
        '/../app/controllers/AvailabilityController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    $availabilityController =
        new AvailabilityController();


    /*
     * Get Vendor Availability
     */

    $availability =
        $availabilityController
            ->getVendorAvailability(
                $vendorID
            );


    /*
     * Get Message
     */

    $message =
        $_SESSION['availability_message']
        ?? '';

    unset(
        $_SESSION['availability_message']
    );


    /*
     * Display Availability Page
     */

    require_once __DIR__ .
        '/../app/views/vendor/availability.php';
}


/*
|--------------------------------------------------------------------------
| ADD AVAILABILITY
|--------------------------------------------------------------------------
*/

elseif ($page === 'add_availability') {

    require_once __DIR__ .
        '/../app/controllers/AvailabilityController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {
        header(
            'Location: index.php?page=vendor_availability'
        );

        exit;
    }


    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    $availabilityDate =
        $_POST['availabilityDate'] ?? '';


    $status =
        $_POST['status'] ?? '';


    /*
     * Validate Date
     */

    if (
        empty($availabilityDate)
    ) {

        $_SESSION['availability_message'] =
            'Please select a date.';

        header(
            'Location: index.php?page=vendor_availability'
        );

        exit;
    }


    /*
     * Validate Status
     */

    $allowedStatuses = [
        'Available',
        'Unavailable',
        'Booked'
    ];


    if (
        !in_array(
            $status,
            $allowedStatuses,
            true
        )
    ) {

        $_SESSION['availability_message'] =
            'Please select a valid availability status.';

        header(
            'Location: index.php?page=vendor_availability'
        );

        exit;
    }


    /*
     * Add Availability
     */

    $availabilityController =
        new AvailabilityController();


    $result =
        $availabilityController
            ->addAvailability(
                $vendorID,
                $availabilityDate,
                $status
            );


    if ($result) {

        $_SESSION['availability_message'] =
            'Availability added successfully.';

    } else {

        $_SESSION['availability_message'] =
            'Failed to add availability. The date may already exist.';
    }


    header(
        'Location: index.php?page=vendor_availability'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| EDIT AVAILABILITY
|--------------------------------------------------------------------------
*/

elseif ($page === 'edit_availability') {

    require_once __DIR__ .
        '/../app/controllers/AvailabilityController.php';


    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    $vendorID =
        $_SESSION['vendor_id'] ?? 0;


    $availabilityID =
        $_GET['id'] ?? 0;


    /*
     * Validate ID
     */

    if (
        !filter_var(
            $availabilityID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=vendor_availability'
        );

        exit;
    }


    $availabilityController =
        new AvailabilityController();


    $message = '';


    /*
     * Handle Update
     */

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
    ) {

        $availabilityDate =
            $_POST['availabilityDate']
            ?? '';


        $status =
            $_POST['status']
            ?? '';


        $allowedStatuses = [
            'Available',
            'Unavailable',
            'Booked'
        ];


        if (
            empty($availabilityDate)
        ) {

            $message =
                'Please select a date.';

        } elseif (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {

            $message =
                'Please select a valid availability status.';

        } else {

            $result =
                $availabilityController
                    ->updateAvailability(
                        $availabilityID,
                        $vendorID,
                        $availabilityDate,
                        $status
                    );


            if ($result) {

                $_SESSION['availability_message'] =
                    'Availability updated successfully.';

                header(
                    'Location: index.php?page=vendor_availability'
                );

                exit;

            } else {

                $message =
                    'Failed to update availability.';
            }
        }
    }


    /*
     * Get Existing Availability
     */

    $availabilityRecord =
        $availabilityController
            ->getVendorAvailabilityByID(
                $availabilityID,
                $vendorID
            );


    /*
     * Record Not Found
     */

    if (!$availabilityRecord) {

        header(
            'Location: index.php?page=vendor_availability'
        );

        exit;
    }


    /*
     * Display Edit Page
     */

    require_once __DIR__ .
        '/../app/views/vendor/edit_availability.php';
}


/*
|--------------------------------------------------------------------------
| VENDOR BOOKINGS
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_bookings') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'];

    /*
     * Get Vendor Bookings
     */

    $bookingController =
        new BookingController();

    $bookings =
        $bookingController
            ->getVendorBookings(
                $vendorID
            );

    /*
     * Get Session Message
     */

    $message =
        $_SESSION['booking_message']
        ?? '';

    unset(
        $_SESSION['booking_message']
    );

    /*
     * Display Vendor Bookings
     */

    require_once __DIR__ .
        '/../app/views/vendor/bookings.php';
}


/*
|--------------------------------------------------------------------------
| VENDOR TRANSACTION HISTORY
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_transactions') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    /*
     * Get Vendor ID
     */

    $vendorID =
        $_SESSION['vendor_id'];

    /*
     * Get Transaction History
     */

    $bookingController =
        new BookingController();

    $transactions =
        $bookingController
            ->getVendorTransactionHistory(
                $vendorID
            );

    /*
     * Display Transaction History
     */

    require_once __DIR__ .
        '/../app/views/vendor/transactions.php';
}



/*
|--------------------------------------------------------------------------
| UPDATE BOOKING STATUS
|--------------------------------------------------------------------------
*/

elseif ($page === 'update_booking_status') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    /*
     * Only logged-in Vendors
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=vendor_bookings'
        );

        exit;
    }

    /*
     * Get Booking ID and New Status
     */

    $bookingID =
        $_POST['booking_id'] ?? 0;

    $newStatus =
        $_POST['status'] ?? '';

    $vendorID =
        $_SESSION['vendor_id'];

    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {

        $_SESSION['booking_message'] =
            'Invalid booking ID.';

        header(
            'Location: index.php?page=vendor_bookings'
        );

        exit;
    }

    /*
     * Allowed Statuses
     */

    $allowedStatuses = [
        'Accepted',
        'Rejected',
        'Cancelled',
        'Completed'
    ];

    if (
        !in_array(
            $newStatus,
            $allowedStatuses,
            true
        )
    ) {

        $_SESSION['booking_message'] =
            'Invalid booking status.';

        header(
            'Location: index.php?page=vendor_bookings'
        );

        exit;
    }

    /*
     * Update Booking Status
     */

    $bookingController =
        new BookingController();

    $result =
        $bookingController
            ->updateBookingStatus(
                $bookingID,
                $vendorID,
                $newStatus
            );

    /*
     * Store Message
     */

    $_SESSION['booking_message'] =
        $result['message'];

    /*
     * Return to Vendor Bookings
     */

    header(
        'Location: index.php?page=vendor_bookings'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD
|--------------------------------------------------------------------------
*/

elseif ($page === 'forgot_password') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        if ($email === '' || $phone === '' || $newPassword === '' || $confirmPassword === '') {
            $message = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } elseif (!isValidPhone($phone)) {
            $message = 'Please enter a valid phone number.';
        } elseif (!isStrongPassword($newPassword)) {
            $message = 'Password must be at least 8 characters and include uppercase, lowercase, and a number.';
        } elseif ($newPassword !== $confirmPassword) {
            $message = 'Passwords do not match.';
        } else {
            $result = $authController->resetPassword($email, $phone, $newPassword);
            if ($result['success']) {
                renderSuccessPage(
                    'Password Reset',
                    'Password reset successfully.',
                    'You can now log in using your new password.',
                    'index.php?page=login',
                    'Go to Login'
                );
                exit;
            }
            $message = $result['message'];
        }
    }

    require_once __DIR__ . '/../app/views/auth/forgot_password.php';
}


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

elseif ($page === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email =
            trim($_POST['email'] ?? '');

        $password =
            $_POST['password'] ?? '';


        if (
            empty($email) ||
            empty($password)
        ) {

            $message =
                'Please enter your email and password.';

            require_once __DIR__ .
                '/../app/views/auth/login.php';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $message = 'Please enter a valid email address.';
            require_once __DIR__ . '/../app/views/auth/login.php';

        } else {

            $result =
                $authController->login(
                    $email,
                    $password
                );


            if ($result['success']) {

                $user = $result['user'];


                /*
                 * Store logged-in user details
                 * in the session.
                 */

                $_SESSION['user_id'] =
                    $user['UserID'];

                $_SESSION['first_name'] =
                    $user['FirstName'];

                $_SESSION['last_name'] =
                    $user['LastName'];

                $_SESSION['email'] =
                    $user['Email'];

                $_SESSION['role'] =
                    $user['Role'];


                /*
                 * Store Customer ID
                 * for Customer accounts.
                 */

if ($user['Role'] === 'Customer') {

    $_SESSION['customer_id'] =
        $user['CustomerID'];

} elseif ($user['Role'] === 'Vendor') {

    $_SESSION['vendor_id'] =
        $user['VendorID'];
}


                /*
                 * Redirect based on role
                 */

                if ($user['Role'] === 'Customer') {

                    header(
                        'Location: index.php?page=customer_dashboard'
                    );

                    exit;

                } elseif ($user['Role'] === 'Vendor') {

                    header(
                        'Location: index.php?page=vendor_dashboard'
                    );

                    exit;

                } elseif ($user['Role'] === 'Admin') {

                    header(
                        'Location: index.php?page=admin_dashboard'
                    );

                    exit;
                }

            } else {

                $message =
                    $result['message'];

                require_once __DIR__ .
                    '/../app/views/auth/login.php';
            }
        }

    } else {

        require_once __DIR__ .
            '/../app/views/auth/login.php';
    }
}


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

elseif ($page === 'admin_dashboard') {

    /*
     * Only logged-in Admins
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Admin'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Display Admin Dashboard
     */

    require_once __DIR__ .
        '/../app/views/admin/dashboard.php';
}



/*
|--------------------------------------------------------------------------
| CUSTOMER DASHBOARD
|--------------------------------------------------------------------------
*/

elseif ($page === 'customer_dashboard') {

    if (
        !isset($_SESSION['user_id']) ||
        ($_SESSION['role'] ?? '') !== 'Customer'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    require_once __DIR__ .
        '/../app/views/customer/dashboard.php';
}

/*
|--------------------------------------------------------------------------
| VENDOR DASHBOARD
|--------------------------------------------------------------------------
*/

elseif ($page === 'vendor_dashboard') {

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Vendor'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    require_once __DIR__ .
        '/../app/views/vendor/dashboard.php';
}

/*
|--------------------------------------------------------------------------
| CREATE EVENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'create_event') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';


    /*
     * Only logged-in Customers
     * can create events.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    $eventController =
        new EventController();


    /*
     * Display Create Event Form
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        $eventTypes =
            $eventController->showCreateForm();

        require_once __DIR__ .
            '/../app/views/customer/create.php';

    } else {

        /*
         * Get Customer ID from session
         */

        $customerID =
            $_SESSION['customer_id'];


        $eventTypeID =
            $_POST['eventTypeID'] ?? '';

        $eventName =
            trim($_POST['eventName'] ?? '');

        $eventDate =
            $_POST['eventDate'] ?? '';

        $location =
            trim($_POST['location'] ?? '');

        $guestCount =
            $_POST['guestCount'] ?? '';

        $budget =
            $_POST['budget'] ?? '';


        /*
         * Basic Validation
         */

        if (
            empty($eventTypeID) ||
            empty($eventName) ||
            empty($eventDate) ||
            empty($location) ||
            empty($guestCount) ||
            $budget === ''
        ) {

            $message =
                'Please fill in all fields.';

            $eventTypes =
                $eventController->showCreateForm();

            require_once __DIR__ .
                '/../app/views/customer/create.php';

        } elseif ($guestCount < 1) {

            $message =
                'Guest count must be at least 1.';

            $eventTypes =
                $eventController->showCreateForm();

            require_once __DIR__ .
                '/../app/views/customer/create.php';

        } elseif ($budget < 0) {

            $message =
                'Budget cannot be negative.';

            $eventTypes =
                $eventController->showCreateForm();

            require_once __DIR__ .
                '/../app/views/customer/create.php';

        } else {

            /*
             * Create Event
             */

            $result =
                $eventController->create(
                    $customerID,
                    $eventTypeID,
                    $eventName,
                    $eventDate,
                    $location,
                    $guestCount,
                    $budget
                );


            if ($result) {
                renderSuccessPage(
                    'Event Created',
                    'Event created successfully.',
                    'Your event has been added to EventEase. You can now add services and packages to it.',
                    'index.php?page=find_services&id=' . (int) $result,
                    'Add Services',
                    'index.php?page=customer_dashboard',
                    'Go to Dashboard'
                );

            } else {

                $message =
                    'Failed to create event.';

                $eventTypes =
                    $eventController->showCreateForm();

                require_once __DIR__ .
                    '/../app/views/customer/create.php';
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| MY EVENTS
|--------------------------------------------------------------------------
*/

elseif ($page === 'my_events') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';


    /*
     * Only logged-in Customers
     * can view their events.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Customer ID from session
     */

    $customerID =
        $_SESSION['customer_id'];


    $eventController =
        new EventController();


    /*
     * Get customer's events
     */

    $events =
        $eventController->getCustomerEvents(
            $customerID
        );


    /*
     * Display events
     */

    require_once __DIR__ .
        '/../app/views/customer/events.php';
}

/*
|--------------------------------------------------------------------------
| EDIT EVENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'edit_event') {
    require_once __DIR__ . '/../app/controllers/EventController.php';

    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Customer') {
        header('Location: index.php?page=login');
        exit;
    }

    $eventID = $_GET['id'] ?? 0;
    $customerID = $_SESSION['customer_id'] ?? 0;

    if (!filter_var($eventID, FILTER_VALIDATE_INT)) {
        header('Location: index.php?page=my_events');
        exit;
    }

    $eventController = new EventController();
    $event = $eventController->getEvent($eventID, $customerID);

    if (!$event) {
        header('Location: index.php?page=my_events');
        exit;
    }

    $eventTypes = $eventController->showCreateForm();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $eventTypeID = $_POST['eventTypeID'] ?? '';
        $eventName = trim($_POST['eventName'] ?? '');
        $eventDate = $_POST['eventDate'] ?? '';
        $location = trim($_POST['location'] ?? '');
        $guestCount = $_POST['guestCount'] ?? '';
        $budget = $_POST['budget'] ?? '';

        if ($eventTypeID === '' || $eventName === '' || $eventDate === '' || $location === '' || $guestCount === '' || $budget === '') {
            $message = 'Please fill in all fields.';
        } elseif (!filter_var($eventTypeID, FILTER_VALIDATE_INT) || !filter_var($guestCount, FILTER_VALIDATE_INT) || (int)$guestCount < 1) {
            $message = 'Please enter valid event details.';
        } elseif (!is_numeric($budget) || (float)$budget < 0) {
            $message = 'Budget cannot be negative.';
        } else {
            $updated = $eventController->update(
                $eventID, $customerID, $eventTypeID, $eventName,
                $eventDate, $location, $guestCount, $budget
            );

            if ($updated) {
                $_SESSION['event_message'] = 'Event updated successfully.';
                header('Location: index.php?page=view_event&id=' . (int)$eventID);
                exit;
            }
            $message = 'Failed to update event.';
        }

        $event = array_merge($event, [
            'EventTypeID' => $eventTypeID, 'EventName' => $eventName,
            'EventDate' => $eventDate, 'Location' => $location,
            'GuestCount' => $guestCount, 'Budget' => $budget
        ]);
    }

    require_once __DIR__ . '/../app/views/customer/edit.php';
}


/*
|--------------------------------------------------------------------------
| VIEW EVENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'view_event') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/EventItemController.php';


    /*
     * Only logged-in Customers
     * can view events.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Event ID
     */

    $eventID =
        $_GET['id'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate Event ID
     */

    if (!filter_var(
        $eventID,
        FILTER_VALIDATE_INT
    )) {

        $event = null;
        $eventItems = [];
        $estimatedTotal = 0;

        require_once __DIR__ .
            '/../app/views/customer/details.php';

        exit;
    }


    /*
     * Get event
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    /*
     * Stop if event does not
     * belong to customer.
     */

    if (!$event) {

        $eventItems = [];
        $estimatedTotal = 0;

        require_once __DIR__ .
            '/../app/views/customer/details.php';

        exit;
    }


    /*
     * Get selected event items
     */

    $eventItemController =
        new EventItemController();


    $eventItems =
        $eventItemController
            ->getEventItems(
                $eventID
            );


    /*
     * Calculate estimated total
     */

    $estimatedTotal =
        $eventItemController
            ->getEventTotal(
                $eventID
            );


    /*
     * Calculate remaining budget
     */

    $remainingBudget =
        $event['Budget'] -
        $estimatedTotal;


    /*
     * Display Event Details
     */

    require_once __DIR__ .
        '/../app/views/customer/details.php';
}


/*
|--------------------------------------------------------------------------
| VIEW PACKAGES
|--------------------------------------------------------------------------
*/

elseif ($page === 'packages') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    $eventID =
        $_GET['id'] ?? 0;

    $customerID =
        $_SESSION['customer_id'];

    if (!filter_var(
        $eventID,
        FILTER_VALIDATE_INT
    )) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );

    if (!$event) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }

    $packageController =
        new PackageController();

    $packages =
        $packageController
            ->getActivePackages(
                $event['EventTypeID']
            );

    require_once __DIR__ .
        '/../app/views/customer/packages.php';
}

/*
|--------------------------------------------------------------------------
| VIEW PACKAGE DETAILS
|--------------------------------------------------------------------------
*/

elseif ($page === 'view_package') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/PackageController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    $eventID =
        $_GET['id'] ?? 0;

    $packageID =
        $_GET['package'] ?? 0;

    $customerID =
        $_SESSION['customer_id'];

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $packageID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );

    if (!$event) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }

    $packageController =
        new PackageController();

    $package =
        $packageController
            ->getPackageDetails(
                $packageID
            );

    if (!$package) {

        header(
            'Location: index.php?page=packages&id='
            . $eventID
        );

        exit;
    }

    $packageServices =
        $packageController
            ->getPackageServices(
                $packageID
            );

    require_once __DIR__ .
        '/../app/views/customer/package_details.php';
}

/*
|--------------------------------------------------------------------------
| FIND SERVICES
|--------------------------------------------------------------------------
*/

elseif ($page === 'find_services') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/ServiceController.php';


    /*
     * Only logged-in Customers
     * can find services.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Event ID
     */

    $eventID =
        $_GET['id'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate Event ID
     */

    if (!filter_var(
        $eventID,
        FILTER_VALIDATE_INT
    )) {

        $event = null;
        $categories = [];

        require_once __DIR__ .
            '/../app/views/customer/services.php';

        exit;
    }


    /*
     * Get selected event
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    /*
     * If event does not belong
     * to customer, stop here.
     */

    if (!$event) {

        $categories = [];

        require_once __DIR__ .
            '/../app/views/customer/services.php';

        exit;
    }


    /*
     * Get service categories
     */

    $serviceController =
        new ServiceController();

    $categories =
        $serviceController
            ->getServiceCategories(
                $event['EventTypeID']
            );


    /*
     * Display services page
     */

    require_once __DIR__ .
        '/../app/views/customer/services.php';
}



/*
|--------------------------------------------------------------------------
| VIEW VENDORS
|--------------------------------------------------------------------------
*/

elseif ($page === 'view_vendors') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/ServiceController.php';


    /*
     * Only logged-in Customers
     * can view vendors.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Event ID and
     * Service Category ID
     */

    $eventID =
        $_GET['id'] ?? 0;

    $categoryID =
        $_GET['category'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate IDs
     */

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $categoryID,
            FILTER_VALIDATE_INT
        )
    ) {

        $event = null;
        $vendors = [];
        $categoryName = 'Service';

        require_once __DIR__ .
            '/../app/views/customer/vendors.php';

        exit;
    }


    /*
     * Get selected event
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    /*
     * Make sure the event
     * belongs to logged-in customer.
     */

    if (!$event) {

        $vendors = [];
        $categoryName = 'Service';

        require_once __DIR__ .
            '/../app/views/customer/vendors.php';

        exit;
    }


    /*
     * Get vendors
     */

    $serviceController =
        new ServiceController();

    $vendors =
        $serviceController->getVendorsByCategory(
            $categoryID,
            $event['EventDate']
        );


    /*
     * Get category name
     */

    $categoryName = 'Service';

    foreach ($vendors as $vendor) {

        /*
         * Category name is supplied
         * separately below when available.
         */

        break;
    }


    /*
     * Get category name directly
     * from database.
     */

    $database =
        new Database();

    $db =
        $database->connect();

    $sql = "SELECT CategoryName
            FROM ServiceCategory
            WHERE ServiceCategoryID =
                  :categoryID
            LIMIT 1";

    $stmt =
        $db->prepare($sql);

    $stmt->bindParam(
        ':categoryID',
        $categoryID,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $category =
        $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {

        $categoryName =
            $category['CategoryName'];
    }


    /*
     * Display vendors
     */

    require_once __DIR__ .
        '/../app/views/customer/vendors.php';
}


/*
|--------------------------------------------------------------------------
| VIEW SERVICE
|--------------------------------------------------------------------------
*/

elseif ($page === 'view_service') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/ServiceController.php';


    /*
     * Only logged-in Customers
     * can view services.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Event ID and Service ID
     */

    $eventID =
        $_GET['id'] ?? 0;

    $serviceID =
        $_GET['service'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate IDs
     */

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $serviceID,
            FILTER_VALIDATE_INT
        )
    ) {

        $event = null;
        $service = null;

        require_once __DIR__ .
            '/../app/views/customer/service_details.php';

        exit;
    }


    /*
     * Get customer's event
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    /*
     * Stop if event does not
     * belong to customer.
     */

    if (!$event) {

        $service = null;

        require_once __DIR__ .
            '/../app/views/customer/service_details.php';

        exit;
    }


    /*
     * Get service details
     */

    $serviceController =
        new ServiceController();

    $service =
        $serviceController->getServiceDetails(
            $serviceID,
            $event['EventDate']
        );


    /*
     * Display service details
     */

    require_once __DIR__ .
        '/../app/views/customer/service_details.php';
}

/*
|--------------------------------------------------------------------------
| ADD SERVICE TO EVENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'add_to_event') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/EventItemController.php';


    /*
     * Only logged-in Customers
     * can add services.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Get data
     */

    $eventID =
        $_POST['eventID'] ?? 0;

    $serviceID =
        $_POST['serviceID'] ?? 0;


    /*
     * Get logged-in customer
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate IDs
     */

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $serviceID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Verify event belongs
     * to logged-in customer.
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    if (!$event) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Add service
     */

    $eventItemController =
        new EventItemController();


    $result =
        $eventItemController
            ->addServiceToEvent(
                $eventID,
                $serviceID,
                $event['EventDate']
            );


    /*
     * Store message in session
     */

    $_SESSION['event_message'] =
        $result['message'];


    /*
     * Return to Event Details
     */

    header(
        'Location: index.php?page=view_event&id='
        . $eventID
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| ADD PACKAGE TO EVENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'add_package_to_event') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/EventItemController.php';


    /*
     * Only logged-in Customers
     * can add packages.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Get data
     */

    $eventID =
        $_POST['eventID'] ?? 0;

    $packageID =
        $_POST['packageID'] ?? 0;

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate IDs
     */

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $packageID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Verify Event Ownership
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    if (!$event) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Add Package
     */

    $eventItemController =
        new EventItemController();

    $result =
        $eventItemController
            ->addPackageToEvent(
                $eventID,
                $packageID,
                $event['EventDate']
            );


    /*
     * Store Message
     */

    $_SESSION['event_message'] =
        $result['message'];


    /*
     * Return to Event Details
     */

    header(
        'Location: index.php?page=view_event&id='
        . $eventID
    );

    exit;
}

/* --------------------------------------------------------------------------
   REQUEST BOOKING
   -------------------------------------------------------------------------- */

elseif ($page === 'request_booking') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {
        header(
            'Location: index.php?page=login'
        );
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
            'Location: index.php?page=my_events'
        );
        exit;
    }

    $eventID = $_POST['eventID'] ?? 0;

    $notes = trim(
        $_POST['notes'] ?? ''
    );

    $customerID =
        $_SESSION['customer_id'];

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        )
    ) {
        header(
            'Location: index.php?page=my_events'
        );
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | Verify that this event belongs to the logged-in customer
    |----------------------------------------------------------------------
    */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );

    if (!$event) {
        header(
            'Location: index.php?page=my_events'
        );
        exit;
    }

    /*
    |----------------------------------------------------------------------
    | Create Booking Requests
    |----------------------------------------------------------------------
    */

    $bookingController =
        new BookingController();

    $result =
        $bookingController
            ->createBookingsForEvent(
                $eventID,
                $customerID,
                $notes
            );

    $_SESSION['booking_message'] =
        $result['message'];

    header(
        'Location: index.php?page=view_event&id='
        . $eventID
    );

    exit;
}


/* --------------------------------------------------------------------------
   MY BOOKINGS
   -------------------------------------------------------------------------- */

elseif ($page === 'my_bookings') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    /*
    |--------------------------------------------------------------------------
    | Only logged-in Customers
    |--------------------------------------------------------------------------
    */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Customer ID
    |--------------------------------------------------------------------------
    */

    if (
        !isset($_SESSION['customer_id']) ||
        !filter_var(
            $_SESSION['customer_id'],
            FILTER_VALIDATE_INT
        )
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    $customerID =
        (int) $_SESSION['customer_id'];


    /*
    |--------------------------------------------------------------------------
    | Get Customer Bookings
    |--------------------------------------------------------------------------
    */

    $bookingController =
        new BookingController();

    $bookings =
        $bookingController
            ->getCustomerBookings(
                $customerID
            );


    /*
    |--------------------------------------------------------------------------
    | Display My Bookings
    |--------------------------------------------------------------------------
    */

    require_once __DIR__ .
        '/../app/views/customer/my_bookings.php';
}

/*
|--------------------------------------------------------------------------
| REMOVE EVENT ITEM
|--------------------------------------------------------------------------
*/

elseif ($page === 'remove_event_item') {

    require_once __DIR__ .
        '/../app/controllers/EventController.php';

    require_once __DIR__ .
        '/../app/controllers/EventItemController.php';


    /*
     * Only logged-in Customers
     * can remove event items.
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Get data
     */

    $eventID =
        $_POST['eventID'] ?? 0;

    $eventItemID =
        $_POST['eventItemID'] ?? 0;

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate IDs
     */

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        ) ||
        !filter_var(
            $eventItemID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Verify Event Ownership
     */

    $eventController =
        new EventController();

    $event =
        $eventController->getEvent(
            $eventID,
            $customerID
        );


    if (!$event) {

        header(
            'Location: index.php?page=my_events'
        );

        exit;
    }


    /*
     * Remove Event Item
     */

    $eventItemController =
        new EventItemController();

    $result =
        $eventItemController
            ->removeEventItem(
                $eventItemID,
                $eventID
            );


    /*
     * Store Message
     */

    $_SESSION['event_message'] =
        $result['message'];


    /*
     * Return to Event Details
     */

    header(
        'Location: index.php?page=view_event&id='
        . $eventID
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| VIEW BOOKING
|--------------------------------------------------------------------------
*/

elseif ($page === 'view_booking') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {
        header(
            'Location: index.php?page=login'
        );

        exit;
    }

    /*
     * Get Booking ID
     */

    $bookingID =
        $_GET['id'] ?? 0;

    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];

    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {
        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }

    /*
     * Create Booking Controller
     */

    $bookingController =
        new BookingController();

    /*
     * Get Booking Details
     */

    $booking =
        $bookingController
            ->getBookingDetails(
                $bookingID,
                $customerID
            );

    /*
     * Booking not found
     */

    if (!$booking) {
        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }

    /*
     * Get Booking Items
     */

    $bookingItems =
        $bookingController
            ->getBookingItems(
                $bookingID
            );

    /*
     * Display Booking Details
     */

    require_once __DIR__ .
        '/../app/views/customer/view_booking.php';
}


/*
|--------------------------------------------------------------------------
| REVIEW BOOKING
|--------------------------------------------------------------------------
*/

elseif ($page === 'review_booking') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    require_once __DIR__ .
        '/../app/controllers/ReviewController.php';


    /*
     * Only logged-in Customers
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Booking ID
     */

    $bookingID =
        $_GET['id'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get Booking Details
     */

    $bookingController =
        new BookingController();

    $booking =
        $bookingController
            ->getBookingDetails(
                $bookingID,
                $customerID
            );


    /*
     * Booking not found
     */

    if (!$booking) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Only Completed bookings
     * can be reviewed.
     */

    if (
        $booking['Status'] !== 'Completed'
    ) {

        $_SESSION['review_message'] =
            'Only completed bookings can be reviewed.';

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get Existing Review
     */

    $reviewController =
        new ReviewController();

    $review =
        $reviewController
            ->getReviewByBooking(
                $bookingID,
                $customerID
            );


    /*
     * Display Review Page
     */

    require_once __DIR__ .
        '/../app/views/customer/review.php';
}


/*
|--------------------------------------------------------------------------
| SUBMIT REVIEW
|--------------------------------------------------------------------------
*/

elseif ($page === 'submit_review') {

    require_once __DIR__ .
        '/../app/controllers/ReviewController.php';


    /*
     * Only logged-in Customers
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get submitted data
     */

    $bookingID =
        $_POST['booking_id'] ?? 0;

    $rating =
        $_POST['rating'] ?? 0;

    $comment =
        trim(
            $_POST['comment'] ?? ''
        );


    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {

        $_SESSION['review_message'] =
            'Invalid booking ID.';

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Validate Rating
     */

    if (
        !filter_var(
            $rating,
            FILTER_VALIDATE_INT
        ) ||
        $rating < 1 ||
        $rating > 5
    ) {

        $_SESSION['review_message'] =
            'Please select a rating between 1 and 5.';

        header(
            'Location: index.php?page=review_booking&id='
            . $bookingID
        );

        exit;
    }


    /*
     * Validate Comment Length
     */

    if (
        strlen($comment) > 1000
    ) {

        $_SESSION['review_message'] =
            'Comment cannot exceed 1000 characters.';

        header(
            'Location: index.php?page=review_booking&id='
            . $bookingID
        );

        exit;
    }


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Create Review
     */

    $reviewController =
        new ReviewController();

    $result =
        $reviewController
            ->createReview(
                $bookingID,
                $customerID,
                $rating,
                $comment
            );


    /*
     * Store Message
     */

    $_SESSION['review_message'] =
        $result['message'];


    /*
     * Return to Review Page
     */

    header(
        'Location: index.php?page=review_booking&id='
        . $bookingID
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| MAKE PAYMENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'make_payment') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';


    /*
     * Only logged-in Customers
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Booking ID
     */

    $bookingID =
        $_GET['id'] ?? 0;


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get Booking Details
     */

    $bookingController =
        new BookingController();

    $booking =
        $bookingController
            ->getBookingDetails(
                $bookingID,
                $customerID
            );


    /*
     * Booking not found
     */

    if (!$booking) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get existing payments
     */

    $paymentController =
        new PaymentController();

    $payments =
        $paymentController
            ->getBookingPayments(
                $bookingID
            );


    /*
     * Display Payment Page
     */

    require_once __DIR__ .
        '/../app/views/customer/make_payment.php';
}


/*
|--------------------------------------------------------------------------
| SUBMIT PAYMENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'submit_payment') {

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';


    /*
     * Only logged-in Customers
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Get submitted data
     */

    $bookingID =
        $_POST['booking_id'] ?? 0;

    $amount =
        $_POST['amount'] ?? 0;

    $referenceNo =
        trim(
            $_POST['reference_no'] ?? ''
        );


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Validate Booking ID
     */

    if (
        !filter_var(
            $bookingID,
            FILTER_VALIDATE_INT
        )
    ) {

        $_SESSION['payment_message'] =
            'Invalid booking ID.';

        header(
            'Location: index.php?page=my_bookings'
        );

        exit;
    }


    /*
     * Validate Amount
     */

    if (
        !is_numeric($amount) ||
        $amount <= 0
    ) {

        $_SESSION['payment_message'] =
            'Please enter a valid payment amount.';

        header(
            'Location: index.php?page=make_payment&id='
            . $bookingID
        );

        exit;
    }


    /*
     * Payment Slip
     */

    $paymentSlip = '';


    if (
        isset($_FILES['payment_slip']) &&
        $_FILES['payment_slip']['error']
        === UPLOAD_ERR_OK
    ) {

        $uploadDirectory =
            __DIR__ .
            '/../uploads/payment_slips/';


        /*
         * Create folder if it does not exist
         */

        if (
            !is_dir(
                $uploadDirectory
            )
        ) {

            mkdir(
                $uploadDirectory,
                0777,
                true
            );
        }


        /*
         * Get file extension
         */

        $extension =
            strtolower(
                pathinfo(
                    $_FILES['payment_slip']['name'],
                    PATHINFO_EXTENSION
                )
            );


        /*
         * Allowed file types
         */

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'pdf'
        ];


        if (
            !in_array(
                $extension,
                $allowedExtensions,
                true
            )
        ) {

            $_SESSION['payment_message'] =
                'Invalid payment slip file type.';

            header(
                'Location: index.php?page=make_payment&id='
                . $bookingID
            );

            exit;
        }


        /*
         * Generate unique filename
         */

        $fileName =
            'payment_' .
            $bookingID .
            '_' .
            time() .
            '.' .
            $extension;


        /*
         * Upload file
         */

        $uploadPath =
            $uploadDirectory .
            $fileName;


        if (
            move_uploaded_file(
                $_FILES['payment_slip']['tmp_name'],
                $uploadPath
            )
        ) {

            $paymentSlip =
                $fileName;

        } else {

            $_SESSION['payment_message'] =
                'Failed to upload payment slip.';

            header(
                'Location: index.php?page=make_payment&id='
                . $bookingID
            );

            exit;
        }
    }


    /*
     * Create Payment
     */

    $paymentController =
        new PaymentController();

    $result =
        $paymentController
            ->createPayment(
                $bookingID,
                $customerID,
                $amount,
                $referenceNo,
                $paymentSlip
            );


    /*
     * Store message
     */

    $_SESSION['payment_message'] =
        $result['message'];


    /*
     * Return to Payment Page
     */

    header(
        'Location: index.php?page=make_payment&id='
        . $bookingID
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| MY PAYMENTS
|--------------------------------------------------------------------------
*/

elseif ($page === 'my_payments') {

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';


    /*
     * Only logged-in Customers
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get Customer ID
     */

    $customerID =
        $_SESSION['customer_id'];


    /*
     * Get Customer Payments
     */

    $paymentController =
        new PaymentController();

    $payments =
        $paymentController
            ->getCustomerPayments(
                $customerID
            );


    /*
     * Get Session Message
     */

    $message =
        $_SESSION['payment_message']
        ?? '';

    unset(
        $_SESSION['payment_message']
    );


    /*
     * Display Payments
     */

    require_once __DIR__ .
        '/../app/views/customer/payments.php';
}


/*
|--------------------------------------------------------------------------
| ADMIN PAYMENT MANAGEMENT
|--------------------------------------------------------------------------
*/

elseif ($page === 'admin_payments') {

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Admin'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    $paymentController = new PaymentController();
    $payments = $paymentController->getAllPayments();

    $message = $_SESSION['payment_message'] ?? '';
    unset($_SESSION['payment_message']);

    require_once __DIR__ .
        '/../app/views/admin/payments.php';
}

/*
|--------------------------------------------------------------------------
| ADMIN UPDATE PAYMENT STATUS
|--------------------------------------------------------------------------
*/

elseif ($page === 'admin_update_payment') {

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Admin'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=admin_payments');
        exit;
    }

    $paymentID = $_POST['payment_id'] ?? 0;
    $newStatus = $_POST['payment_status'] ?? '';

    if (!filter_var($paymentID, FILTER_VALIDATE_INT)) {
        $_SESSION['payment_message'] = 'Invalid payment ID.';
        header('Location: index.php?page=admin_payments');
        exit;
    }

    $paymentController = new PaymentController();
    $result = $paymentController->updatePaymentStatus($paymentID, $newStatus);

    $_SESSION['payment_message'] = $result['message'];

    header('Location: index.php?page=admin_payments');
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN VENDOR VERIFICATION
|--------------------------------------------------------------------------
*/

elseif ($page === 'admin_vendors') {

    require_once __DIR__ .
        '/../app/controllers/VendorController.php';


    /*
     * Only logged-in Admins
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Admin'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Get all vendors
     */

    $vendorController =
        new VendorController();

    $vendors =
        $vendorController
            ->getAllVendors();


    /*
     * Display Admin Vendor Page
     */

    require_once __DIR__ .
        '/../app/views/admin/vendors.php';
}


/*
|--------------------------------------------------------------------------
| ADMIN UPDATE VENDOR VERIFICATION
|--------------------------------------------------------------------------
*/

elseif ($page === 'admin_update_vendor') {

    require_once __DIR__ .
        '/../app/controllers/VendorController.php';


    /*
     * Only logged-in Admins
     */

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Admin'
    ) {

        header(
            'Location: index.php?page=login'
        );

        exit;
    }


    /*
     * Only POST requests
     */

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {

        header(
            'Location: index.php?page=admin_vendors'
        );

        exit;
    }


    /*
     * Get submitted data
     */

    $vendorID =
        $_POST['vendor_id']
        ?? 0;

    $newStatus =
        $_POST['verification_status']
        ?? '';


    /*
     * Validate Vendor ID
     */

    if (
        !filter_var(
            $vendorID,
            FILTER_VALIDATE_INT
        )
    ) {

        $_SESSION['vendor_message'] =
            'Invalid vendor ID.';

        header(
            'Location: index.php?page=admin_vendors'
        );

        exit;
    }


    /*
     * Update Verification Status
     */

    $vendorController =
        new VendorController();

    $result =
        $vendorController
            ->updateVerificationStatus(
                $vendorID,
                $newStatus
            );


    /*
     * Store Message
     */

    $_SESSION['vendor_message'] =
        $result['message'];


    /*
     * Return to Vendor Verification
     */

    header(
        'Location: index.php?page=admin_vendors'
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| CUSTOMER CONSOLIDATED EVENT SUMMARY
|--------------------------------------------------------------------------
*/

elseif ($page === 'event_summary') {

    require_once __DIR__ .
        '/../app/controllers/BookingController.php';

    require_once __DIR__ .
        '/../app/controllers/PaymentController.php';

    if (
        !isset($_SESSION['user_id']) ||
        $_SESSION['role'] !== 'Customer'
    ) {
        header('Location: index.php?page=login');
        exit;
    }

    $eventID = $_GET['id'] ?? 0;

    if (
        !filter_var(
            $eventID,
            FILTER_VALIDATE_INT
        )
    ) {
        header('Location: index.php?page=my_bookings');
        exit;
    }

    $customerID =
        $_SESSION['customer_id'];

    $bookingController =
        new BookingController();

    $allBookings =
        $bookingController
            ->getCustomerBookings(
                $customerID
            );

    $bookings = [];
    $totalPaidAmount = 0;

    foreach ($allBookings as $booking) {

        if (
            (int) $booking['EventID'] ===
            (int) $eventID
        ) {

            $booking['Items'] =
                $bookingController
                    ->getBookingItems(
                        $booking['BookingID']
                    );

            $paymentController =
                new PaymentController();

            $payments =
                $paymentController
                    ->getBookingPayments(
                        $booking['BookingID']
                    );

            $paidAmount = 0;

            foreach ($payments as $payment) {

                if (
                    $payment['PaymentStatus'] ===
                    'Completed'
                ) {
                    $paidAmount +=
                        (float) $payment['Amount'];
                }
            }

            $booking['PaidAmount'] =
                $paidAmount;

            $totalPaidAmount +=
                $paidAmount;

            $booking['PaymentStatus'] =
                $paidAmount >=
                (float) $booking['TotalAmount']
                    ? 'Completed'
                    : (
                        $paidAmount > 0
                            ? 'Partially Paid'
                            : 'Pending'
                    );

            $bookings[] =
                $booking;
        }
    }

    $event = null;

    if (!empty($bookings)) {

        $event = [
            'EventName' =>
                $bookings[0]['EventName'],

            'EventDate' =>
                $bookings[0]['EventDate'],

            'Location' =>
                $bookings[0]['Location'] ?? 'Not specified'
        ];
    }

    require_once __DIR__ .
        '/../app/views/customer/invoice.php';
}



/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

elseif ($page === 'logout') {

    session_unset();

    session_destroy();

    header(
        'Location: index.php?page=login'
    );

    exit;
}