USE EventEase2026;

-- EventEase default administrator
-- Email: admin@eventease.com
-- Password: Admin@123

INSERT INTO Users
    (FirstName, LastName, Email, Password, Phone, Role, Status)
VALUES
    (
        'System',
        'Admin',
        'admin@eventease.com',
        '$2y$12$aFP55PtZ8wwf6UY4pufGWelmPwzZ7.RLnxZvADw5A1pc6woL7GeP2',
        '0771234567',
        'Admin',
        'Active'
    )
ON DUPLICATE KEY UPDATE
    Password = VALUES(Password),
    Phone = VALUES(Phone),
    Role = 'Admin',
    Status = 'Active';

INSERT INTO Administrator (UserID, AdminLevel)
SELECT UserID, 'Standard'
FROM Users
WHERE Email = 'admin@eventease.com'
ON DUPLICATE KEY UPDATE
    AdminLevel = 'Standard';
