<?php
class EventController {
    private function checkCustomerAuth() {
        $role = isset($_SESSION['role']) ? trim(strtolower($_SESSION['role'])) : '';
        if (!isset($_SESSION['user_id']) || $role !== 'customer') {
            header('Location: ?url=auth/login');
            exit;
        }
    }

    // Helper to get customer record or redirect if not found
    private function getCustomerOrFail() {
        $customer = Customer::findByUserId($_SESSION['user_id']);
        if (!$customer) {
            $_SESSION['error'] = "Customer record not found. Please contact admin.";
            header('Location: ?url=auth/logout');
            exit;
        }
        return $customer;
    }

    public function dashboard() {
        $this->checkCustomerAuth();
        $customer = $this->getCustomerOrFail();
        $events = Event::findByCustomer($customer['customer_id']);
        $bookings = Booking::getByCustomer($customer['customer_id']);
        require_once '../app/views/customer/dashboard.php';
    }

    public function create() {
        $this->checkCustomerAuth();
        $eventTypes = Event::getAllEventTypes();
        require_once '../app/views/customer/create_event.php';
    }

    public function store() {
        $this->checkCustomerAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validateEvent($_POST);
            if (empty($errors)) {
                $customer = $this->getCustomerOrFail();
                $data = [
                    'customer_id' => $customer['customer_id'],
                    'event_type_id' => $_POST['event_type_id'],
                    'event_name' => trim($_POST['event_name']),
                    'event_date' => $_POST['event_date'],
                    'location' => trim($_POST['location']),
                    'guest_count' => $_POST['guest_count'],
                    'budget' => $_POST['budget']
                ];
                Event::create($data);
                $_SESSION['success'] = "Event created successfully.";
                header('Location: ?url=event/dashboard');
                exit;
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ?url=event/create');
                exit;
            }
        }
    }

    public function myEvents() {
        $this->checkCustomerAuth();
        $customer = $this->getCustomerOrFail();
        $events = Event::findByCustomer($customer['customer_id']);
        require_once '../app/views/customer/my_events.php';
    }

    public function view($eventId = null) {
        $this->checkCustomerAuth();
        if (!$eventId) {
            header('Location: ?url=event/dashboard');
            exit;
        }
        $customer = $this->getCustomerOrFail();
        $event = Event::findById($eventId);
        if (!$event || $event['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "You are not authorised to view this event.";
            header('Location: ?url=event/dashboard');
            exit;
        }
        $eventItems = EventItem::getByEvent($eventId);
        require_once '../app/views/customer/view_event.php';
    }

    public function edit($eventId = null) {
        $this->checkCustomerAuth();
        if (!$eventId) {
            header('Location: ?url=event/dashboard');
            exit;
        }
        $customer = $this->getCustomerOrFail();
        $event = Event::findById($eventId);
        if (!$event || $event['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "You are not authorised to edit this event.";
            header('Location: ?url=event/dashboard');
            exit;
        }
        $eventTypes = Event::getAllEventTypes();
        require_once '../app/views/customer/edit_event.php';
    }

    public function update($eventId = null) {
        $this->checkCustomerAuth();
        if (!$eventId || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ?url=event/dashboard');
            exit;
        }
        $customer = $this->getCustomerOrFail();
        $event = Event::findById($eventId);
        if (!$event || $event['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "You are not authorised to edit this event.";
            header('Location: ?url=event/dashboard');
            exit;
        }
        $errors = $this->validateEvent($_POST);
        if (empty($errors)) {
            $data = [
                'event_name' => trim($_POST['event_name']),
                'event_type_id' => $_POST['event_type_id'],
                'event_date' => $_POST['event_date'],
                'location' => trim($_POST['location']),
                'guest_count' => $_POST['guest_count'],
                'budget' => $_POST['budget']
            ];
            Event::update($eventId, $data);
            $_SESSION['success'] = "Event updated successfully.";
            header('Location: ?url=event/view/' . $eventId);
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: ?url=event/edit/' . $eventId);
            exit;
        }
    }

    // =============== SERVICE DISCOVERY ===============
    public function findServices() {
        $this->checkCustomerAuth();
        $customer = $this->getCustomerOrFail();
        $events = Event::findByCustomer($customer['customer_id']);
        $categories = Service::getCategories();
        require_once '../app/views/customer/find_services.php';
    }

    public function browseServices() {
    $this->checkCustomerAuth();

    // Get the selected event and service category
    $eventId = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
    $categoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;

    // Make sure both selections were made
    if ($eventId <= 0 || $categoryId <= 0) {
        $_SESSION['error'] = "Please select an event and a service category.";
        header('Location: ?url=event/findServices');
        exit;
    }

    $customer = $this->getCustomerOrFail();

    // Get the selected event
    $event = Event::findById($eventId);

    // Make sure this event belongs to the logged-in customer
    if (!$event || (int) $event['customer_id'] !== (int) $customer['customer_id']) {
        $_SESSION['error'] = "Invalid event.";
        header('Location: ?url=event/findServices');
        exit;
    }

    // Get all active services in the selected category
    // from approved vendors
    $services = Service::searchByCategory($categoryId);

    // Display the available services
    require_once '../app/views/customer/browse_services.php';
    }

    public function addService() {
    $this->checkCustomerAuth();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $eventId = isset($_POST['event_id'])
            ? (int) $_POST['event_id']
            : 0;

        $serviceId = isset($_POST['service_id'])
            ? (int) $_POST['service_id']
            : 0;

        $quantity = isset($_POST['quantity'])
            ? (int) $_POST['quantity']
            : 1;

        // Validate submitted values
        if ($eventId <= 0 || $serviceId <= 0 || $quantity < 1) {
            $_SESSION['error'] = "Invalid service selection.";
            header('Location: ?url=event/findServices');
            exit;
        }

        $customer = $this->getCustomerOrFail();

        // Get event
        $event = Event::findById($eventId);

        // Check event ownership
        if (
            !$event ||
            (int) $event['customer_id'] !== (int) $customer['customer_id']
        ) {
            $_SESSION['error'] = "Invalid event.";
            header('Location: ?url=event/findServices');
            exit;
        }

        // Check that the service is still available
        $service = Service::getAvailableById($serviceId);

        if (!$service) {
            $_SESSION['error'] = "This service is no longer available.";
            header('Location: ?url=event/view/' . $eventId);
            exit;
        }

        // Add service to event
        EventItem::add(
            $eventId,
            $serviceId,
            $quantity
        );

        $_SESSION['success'] = "Service added to event.";

        // Return to event details
        header('Location: ?url=event/view/' . $eventId);
        exit;
    }

    // If accessed without POST
    header('Location: ?url=event/findServices');
    exit;
}

    public function removeService($eventItemId = null) {
        $this->checkCustomerAuth();
        if (!$eventItemId) {
            header('Location: ?url=event/dashboard');
            exit;
        }
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT ei.*, e.customer_id FROM EventItem ei JOIN Events e ON ei.event_id = e.event_id WHERE ei.event_item_id = ?");
        $stmt->execute([$eventItemId]);
        $item = $stmt->fetch();
        $customer = $this->getCustomerOrFail();
        if (!$item || $item['customer_id'] != $customer['customer_id']) {
            $_SESSION['error'] = "You are not authorised to remove this service.";
            header('Location: ?url=event/dashboard');
            exit;
        }
        EventItem::remove($eventItemId);
        $_SESSION['success'] = "Service removed from event.";
        header('Location: ?url=event/view/' . $item['event_id']);
        exit;
    }

    private function validateEvent($data) {
        $errors = [];
        if (empty($data['event_name'])) $errors[] = "Please enter an event name.";
        if (empty($data['event_type_id'])) $errors[] = "Please select an event type.";
        if (empty($data['event_date'])) $errors[] = "Please enter an event date.";
        if (empty($data['location'])) $errors[] = "Please enter a location.";
        if (empty($data['guest_count']) || !is_numeric($data['guest_count']) || $data['guest_count'] <= 0) $errors[] = "Please enter a valid guest count.";
        if (empty($data['budget']) || !is_numeric($data['budget']) || $data['budget'] <= 0) $errors[] = "Please enter a valid budget.";
        return $errors;
    }
}
?>