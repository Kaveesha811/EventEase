<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../../config/database.php';

class AuthController
{
    private $userModel;
    private $customerModel;
    private $conn;


    /*
    | Constructor
    */

    public function __construct()
    {
        $database =
            new Database();

        $this->conn =
            $database->connect();

        $this->userModel =
            new User(
                $this->conn
            );

        $this->customerModel =
            new Customer(
                $this->conn
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Register Customer
    |--------------------------------------------------------------------------
    */

    public function register(
        $firstName,
        $lastName,
        $email,
        $password,
        $phone,
        $address
    ) {

        /*
         * Check whether email already exists.
         */

        if (
            $this->userModel
                ->emailExists($email)
        ) {

            return [
                'success' => false,
                'message' =>
                    'Email address already exists.'
            ];
        }


        try {

            /*
             * Start transaction.
             */

            $this->conn
                ->beginTransaction();


            /*
             * Create User account.
             */

            $userID =
                $this->userModel
                    ->registerUser(
                        $firstName,
                        $lastName,
                        $email,
                        $password,
                        $phone,
                        'Customer'
                    );


            if (!$userID) {

                throw new Exception(
                    'User registration failed.'
                );
            }


            /*
             * Create Customer profile.
             */

            $customerCreated =
                $this->customerModel
                    ->createCustomer(
                        $userID,
                        $address
                    );


            if (!$customerCreated) {

                throw new Exception(
                    'Customer profile creation failed.'
                );
            }


            /*
             * Confirm transaction.
             */

            $this->conn->commit();


            return [
                'success' => true,
                'message' =>
                    'Registration successful.'
            ];


        } catch (Exception $exception) {

            /*
             * Roll back if something fails.
             */

            if (
                $this->conn
                    ->inTransaction()
            ) {

                $this->conn
                    ->rollBack();
            }


            return [
                'success' => false,
                'message' =>
                    'Registration failed. Please try again.'
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Register Vendor
    |--------------------------------------------------------------------------
    */

    public function registerVendor(
        $firstName,
        $lastName,
        $email,
        $password,
        $phone,
        $businessName,
        $businessDescription,
        $businessAddress,
        $facebookProfile = null
    ) {

        /*
         * Check whether email already exists.
         */

        if (
            $this->userModel
                ->emailExists($email)
        ) {

            return [
                'success' => false,
                'message' =>
                    'Email address already exists.'
            ];
        }


        try {

            /*
             * Start transaction.
             */

            $this->conn
                ->beginTransaction();


            /*
             * Create Vendor User account.
             */

            $userID =
                $this->userModel
                    ->registerUser(
                        $firstName,
                        $lastName,
                        $email,
                        $password,
                        $phone,
                        'Vendor'
                    );


            if (!$userID) {

                throw new Exception(
                    'Vendor user registration failed.'
                );
            }


            /*
             * Create Vendor profile.
             */

            require_once __DIR__ .
                '/../models/Vendor.php';

            $vendorModel =
                new Vendor(
                    $this->conn
                );

            $vendorCreated =
                $vendorModel
                    ->createVendor(
                        $userID,
                        $businessName,
                        $businessDescription,
                        $businessAddress,
                        $facebookProfile
                    );


            if (!$vendorCreated) {

                throw new Exception(
                    'Vendor profile creation failed.'
                );
            }


            /*
             * Confirm transaction.
             */

            $this->conn->commit();


            return [
                'success' => true,
                'message' =>
                    'Vendor registration successful. Your account is pending admin verification.'
            ];


        } catch (Exception $exception) {

            /*
             * Roll back if something fails.
             */

            if (
                $this->conn
                    ->inTransaction()
            ) {

                $this->conn
                    ->rollBack();
            }


            return [
                'success' => false,
                'message' =>
                    'Vendor registration failed. Please try again.'
            ];
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Reset Password (simple academic-project flow)
    |--------------------------------------------------------------------------
    */

    public function resetPassword($email, $phone, $newPassword)
    {
        if (!$this->userModel->emailAndPhoneMatch($email, $phone)) {
            return [
                'success' => false,
                'message' => 'Email and phone number do not match our records.'
            ];
        }

        $updated = $this->userModel->updatePasswordByEmail($email, $newPassword);

        return [
            'success' => (bool) $updated,
            'message' => $updated
                ? 'Password reset successfully. You can now log in.'
                : 'Password reset failed. Please try again.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(
        $email,
        $password
    ) {

        $user =
            $this->userModel
                ->loginUser(
                    $email,
                    $password
                );

        if (
            is_array($user) &&
            isset($user['loginError'])
        ) {
            return [
                'success' => false,
                'message' => $user['loginError']
            ];
        }


        /*
         * Invalid login.
         */

        if (!$user) {

            return [
                'success' => false,
                'message' =>
                    'Invalid email or password.'
            ];
        }


        /*
         * Successful login.
         */

        return [
            'success' => true,
            'message' =>
                'Login successful.',
            'user' => $user
        ];
    }
}