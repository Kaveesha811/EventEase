USE EventEase2026;

-- Run this only if your Vendor table does NOT already contain FacebookProfile.
-- If you created the database using the SQL in your message, you can skip this ALTER.
-- ALTER TABLE Vendor ADD COLUMN FacebookProfile VARCHAR(255) NULL AFTER BusinessAddress;

-- EventEase code now saves newly created events with Status = 'Active',
-- matching ENUM('Active','Cancelled','Completed') in your provided Events table.
