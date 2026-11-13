<?php
class ServiceController {
    private function checkVendorAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
            header('Location: ?url=auth/login');
            exit;
        }
        $vendor = Vendor::findByUserId($_SESSION['user_id']);
        if (!$vendor || $vendor['verification_status'] != 'approved') {
            header('Location: ?url=auth/login');
            exit;
        }
        return $vendor;
    }

    public function myServices() {
        $vendor = $this->checkVendorAuth();
        $services = Service::getByVendor($vendor['vendor_id']);
        require_once '../app/views/vendor/my_services.php';
    }

    public function create() {
        $vendor = $this->checkVendorAuth();
        $categories = Service::getCategories();
        require_once '../app/views/vendor/create_service.php';
    }

    public function store() {
        $vendor = $this->checkVendorAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validateService($_POST);
            if (empty($errors)) {
                $data = [
                    'vendor_id' => $vendor['vendor_id'],
                    'service_category_id' => $_POST['service_category_id'],
                    'service_name' => trim($_POST['service_name']),
                    'description' => trim($_POST['description']),
                    'price' => $_POST['price']
                ];
                Service::create($data);
                $_SESSION['success'] = "Service added successfully.";
                header('Location: ?url=service/myServices');
                exit;
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ?url=service/create');
                exit;
            }
        }
    }

    public function edit($serviceId = null) {
        $vendor = $this->checkVendorAuth();
        if (!$serviceId) {
            header('Location: ?url=service/myServices');
            exit;
        }
        $service = Service::getById($serviceId);
        if (!$service || $service['vendor_id'] != $vendor['vendor_id']) {
            $_SESSION['error'] = "You are not authorised to edit this service.";
            header('Location: ?url=service/myServices');
            exit;
        }
        $categories = Service::getCategories();
        require_once '../app/views/vendor/edit_service.php';
    }

    public function update($serviceId = null) {
        $vendor = $this->checkVendorAuth();
        if (!$serviceId || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ?url=service/myServices');
            exit;
        }
        $service = Service::getById($serviceId);
        if (!$service || $service['vendor_id'] != $vendor['vendor_id']) {
            $_SESSION['error'] = "You are not authorised to edit this service.";
            header('Location: ?url=service/myServices');
            exit;
        }
        $errors = $this->validateService($_POST);
        if (empty($errors)) {
            $data = [
                'service_category_id' => $_POST['service_category_id'],
                'service_name' => trim($_POST['service_name']),
                'description' => trim($_POST['description']),
                'price' => $_POST['price']
            ];
            Service::update($serviceId, $data);
            $_SESSION['success'] = "Service updated successfully.";
            header('Location: ?url=service/myServices');
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: ?url=service/edit/' . $serviceId);
            exit;
        }
    }

    public function deactivate($serviceId = null) {
        $vendor = $this->checkVendorAuth();
        if (!$serviceId) {
            header('Location: ?url=service/myServices');
            exit;
        }
        $service = Service::getById($serviceId);
        if (!$service || $service['vendor_id'] != $vendor['vendor_id']) {
            $_SESSION['error'] = "You are not authorised to deactivate this service.";
            header('Location: ?url=service/myServices');
            exit;
        }
        Service::deactivate($serviceId);
        $_SESSION['success'] = "Service deactivated.";
        header('Location: ?url=service/myServices');
        exit;
    }

    private function validateService($data) {
        $errors = [];
        if (empty($data['service_name'])) $errors[] = "Please enter a service name.";
        if (empty($data['service_category_id'])) $errors[] = "Please select a category.";
        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) $errors[] = "Please enter a valid price.";
        return $errors;
    }
}
?>