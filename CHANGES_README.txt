EventEase2026 - requested updates

1. Customer/vendor registration validation:
   - valid names, email, phone, address, strong password, confirm password
   - duplicate email still checked in database
2. Vendor registration now saves optional Facebook profile/page URL.
3. Customer event Edit button now works and only updates that customer's own event.
4. Add Services is now a visible green button on each event and a primary action on event details.
5. Forgot Password added to Login. For this simple university project it verifies email + registered phone, then lets the user choose a new strong password.
6. Removed duplicate Create Event dashboard card; the main Create Event button remains.
7. Customer dashboard welcome panel changed to a lighter color and description text changed to black.
8. No AI/QuillBot integration was present in the uploaded project files, so no core files needed removal for that item.
9. New events use Status 'Active' to match the Events ENUM in the SQL provided in chat.

Database note:
Your provided Vendor table already has FacebookProfile, so normally no database change is required. See database_update.sql if using an older database.

Forgot-password security note:
This email + phone verification flow is intentionally simple for the academic project. A production website should use a time-limited email/SMS reset token instead.

ADMIN LOGIN
-----------
If you need the default administrator account, open phpMyAdmin -> EventEase2026 -> SQL,
open admin_setup.sql from this project, paste it, and click Go.

Admin email: admin@eventease.com
Admin password: Admin@123

This resets the password for that specific admin email and creates the Administrator
record if it is missing.
