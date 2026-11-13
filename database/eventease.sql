CREATE DATABASE IF NOT EXISTS eventease2026;
USE eventease2026;

CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('customer','vendor','admin') NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE Customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    address VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Vendor (
    vendor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    business_name VARCHAR(100) NOT NULL,
    business_description TEXT,
    business_address VARCHAR(255),
    facebook_profile VARCHAR(255),
    verification_status ENUM('pending','approved','rejected') DEFAULT 'pending',
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Administrator (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    admin_level ENUM('super','moderator') DEFAULT 'moderator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE EventType (
    event_type_id INT AUTO_INCREMENT PRIMARY KEY,
    event_type_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    status ENUM('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE ServiceCategory (
    service_category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    status ENUM('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    event_type_id INT NOT NULL,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    guest_count INT NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    status ENUM('active','cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (event_type_id) REFERENCES EventType(event_type_id)
) ENGINE=InnoDB;

CREATE TABLE Service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    service_category_id INT NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES Vendor(vendor_id) ON DELETE CASCADE,
    FOREIGN KEY (service_category_id) REFERENCES ServiceCategory(service_category_id)
) ENGINE=InnoDB;

CREATE TABLE Availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    availability_date DATE NOT NULL,
    status ENUM('available','unavailable','booked') DEFAULT 'available',
    FOREIGN KEY (vendor_id) REFERENCES Vendor(vendor_id) ON DELETE CASCADE,
    UNIQUE KEY (vendor_id, availability_date)
) ENGINE=InnoDB;

CREATE TABLE EventItem (
    event_item_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT DEFAULT 1,
    estimated_price DECIMAL(10,2) DEFAULT 0.00,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES Events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Service(service_id) ON DELETE CASCADE,
    UNIQUE KEY (event_id, service_id)
) ENGINE=InnoDB;

CREATE TABLE Booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    vendor_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','accepted','rejected','cancelled','completed') DEFAULT 'pending',
    notes TEXT,
    FOREIGN KEY (event_id) REFERENCES Events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (vendor_id) REFERENCES Vendor(vendor_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE BookingItem (
    booking_item_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES Booking(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Service(service_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('bank_transfer') DEFAULT 'bank_transfer',
    payment_status ENUM('pending','completed') DEFAULT 'pending',
    reference_no VARCHAR(100),
    payment_slip VARCHAR(255),
    FOREIGN KEY (booking_id) REFERENCES Booking(booking_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNIQUE NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES Booking(booking_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample Data
INSERT INTO EventType (event_type_name, description) VALUES
('Wedding', 'Wedding ceremony and reception'),
('Birthday', 'Birthday party'),
('Corporate Event', 'Corporate functions and seminars'),
('Anniversary', 'Anniversary celebration'),
('Engagement', 'Engagement ceremony');

INSERT INTO ServiceCategory (category_name, description) VALUES
('Venue', 'Event venues and halls'),
('Catering', 'Food and beverage services'),
('Decoration', 'Event decoration and floral arrangements'),
('Photography & Videography', 'Professional photo and video coverage'),
('Cake', 'Custom cakes and desserts'),
('DJ & Sound', 'Music and sound systems'),
('Makeup', 'Bridal and event makeup'),
('Entertainment', 'Live bands, dancers, etc.');

-- Password for all sample users: 'password' (hashed)
INSERT INTO user (first_name, last_name, email, password, phone, role, status) VALUES
('Admin', 'User', 'admin@eventease.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0712345678', 'admin', 'active'),
('John', 'Doe', 'john@customer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0712345678', 'customer', 'active'),
('Jane', 'Smith', 'jane@customer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0723456789', 'customer', 'active'),
('David', 'Vendor', 'david@vendor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0734567890', 'vendor', 'active'),
('Sarah', 'Vendor', 'sarah@vendor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0745678901', 'vendor', 'active'),
('Mike', 'PendingVendor', 'mike@vendor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0756789012', 'vendor', 'active');

INSERT INTO Administrator (user_id, admin_level) VALUES (1, 'super');

INSERT INTO Customer (user_id, address) VALUES
(2, '123 Main St, Colombo'),
(3, '456 Galle Rd, Kandy');

INSERT INTO Vendor (user_id, business_name, business_description, business_address, facebook_profile, verification_status) VALUES
(4, 'David Catering', 'Premium catering services for all events', 'Colombo 07', 'https://facebook.com/davidcatering', 'approved'),
(5, 'Sarah Decoration', 'Elegant decorations for weddings and parties', 'Nugegoda', 'https://facebook.com/sarahdecor', 'approved'),
(6, 'Mike Photography', 'Professional photography and videography', 'Kandy', NULL, 'pending');

INSERT INTO Service (vendor_id, service_category_id, service_name, description, price, status) VALUES
(1, 2, 'Full Catering Package', 'Full meal package for 100 guests', 250000.00, 'active'),
(1, 2, 'Snack Bar', 'Snacks and beverages for 50 guests', 50000.00, 'active'),
(2, 3, 'Wedding Stage Decoration', 'Complete stage decoration with flowers', 150000.00, 'active'),
(2, 3, 'Table Centerpieces', 'Elegant centerpieces per table', 5000.00, 'active'),
(3, 4, 'Wedding Photography', 'Full day photography coverage', 80000.00, 'active'),
(3, 4, 'Video Coverage', '4K video coverage with drone', 100000.00, 'active');

INSERT INTO Availability (vendor_id, availability_date, status) VALUES
(1, '2026-12-20', 'available'),
(1, '2026-12-21', 'booked'),
(2, '2026-12-20', 'available'),
(2, '2026-12-22', 'unavailable'),
(3, '2026-12-20', 'available');

INSERT INTO Events (customer_id, event_type_id, event_name, event_date, location, guest_count, budget) VALUES
(1, 1, 'John & Jane Wedding', '2026-12-20', 'Colombo', 200, 500000.00);

INSERT INTO EventItem (event_id, service_id, quantity, estimated_price) VALUES
(1, 1, 1, 250000.00),
(1, 3, 1, 150000.00);

INSERT INTO Booking (event_id, vendor_id, total_amount, status, notes) VALUES
(1, 1, 250000.00, 'accepted', 'Please arrange 200 guest meals');

INSERT INTO BookingItem (booking_id, service_id, quantity, unit_price, subtotal) VALUES
(1, 1, 1, 250000.00, 250000.00);

INSERT INTO Payment (booking_id, amount, payment_status, reference_no, payment_slip) VALUES
(1, 250000.00, 'pending', 'BANK123456', NULL);