# EventEase 2026

**EventEase** is a web-based event management and vendor marketplace platform connecting customers with event-service vendors. Built with PHP (MVC), MySQL, HTML, CSS, and basic JavaScript.

## Features
- Customer registration/login, event creation, service discovery, booking, bank transfer payment records, reviews.
- Vendor registration (pending admin approval), service management, availability, booking management.
- Admin vendor verification and payment confirmation.
- Role-based access, password hashing, PDO prepared statements.

## Technologies
- PHP 8+
- MySQL
- HTML5, CSS3, JavaScript
- PDO
- MVC architecture

## Setup Instructions

### 1. Database Setup
- Create a MySQL database named `eventease2026` (or import the SQL file which does it automatically).
- Import `database/eventease.sql` using phpMyAdmin or MySQL Workbench.
- The SQL file creates all tables and inserts sample data.

### 2. Configure Database Connection
Edit `config/database.php`:
- Set `$host`, `$dbname`, `$username`, `$password` to match your MySQL environment. Default is `localhost`, `eventease2026`, `root`, empty password.

### 3. Place Project Files
Copy the entire `EventEase2026` folder to your web server document root (e.g., `htdocs` for XAMPP, `www` for WAMP).

### 4. Run the Application
Start Apache and MySQL.
Open browser: `http://localhost/EventEase2026/public/`

### 5. Sample Login Accounts
All sample users have password: `password`

| Role     | Email                  | Password |
|----------|------------------------|----------|
| Admin    | admin@eventease.com    | password |
| Customer | john@customer.com      | password |
| Customer | jane@customer.com      | password |
| Vendor   | david@vendor.com       | password |
| Vendor   | sarah@vendor.com       | password |
| Vendor (pending) | mike@vendor.com | password |

**Note:** If the password hash does not work, you can register a new user via the registration form and use that account for testing. To create a new admin, insert a user with role 'admin' and a new password hash (generate using PHP's `password_hash`).

## Project Structure
- `app/controllers/` – application logic
- `app/models/` – database operations
- `app/views/` – user interface
- `config/` – database configuration
- `public/` – entry point, CSS, JS, uploads
- `database/` – SQL schema and sample data

## Main Workflow
1. Customer registers and creates an event.
2. Customer searches services by category, selects services from approved vendors.
3. Customer requests booking; system creates bookings per vendor.
4. Vendor accepts/rejects booking.
5. Customer makes bank transfer and submits payment slip.
6. Admin verifies payment.
7. Vendor marks booking completed.
8. Customer writes a review.

## MVC Routing
All requests go through `public/index.php?url=controller/action/param1/param2`. The front controller parses the URL and dispatches to the appropriate controller method.

## License
For educational purposes only.