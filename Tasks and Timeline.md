Tasks and Timeline:

1.Database and Configuration (done)

    Create db.php for database connection. (done)
    Create users, tests, and test_results tables programmatically. (done)

2.Authentication System

    Registration system in register.php.
    Login system in index.php with role-based redirection.

3.Admin Dashboard

    Create Test (create_test.php).
    Assign Test to Users (admin_dashboard.php).
    View Test Results (view_results.php).

4.User Dashboard

    View assigned tests (user_dashboard.php).
    Attempt Test (attempt_test.php) and submit answers.

5.Session Management

    Implement session handling for role-based access.
    Logout functionality (logout.php).

6.Final Touches

    Error handling and validation.
    Shared header/footer navigation.

---


Folder Structure (Updated)

Form_Wizard/
│
├── index.php                # Main entry point (Login Page)
├── register.php             # Registration Page
├── admin_dashboard.php      # Admin Dashboard
├── user_dashboard.php       # User Dashboard
├── create_test.php          # Form to create and assign tests (for admin)
├── attempt_test.php         # Page for users to attempt a test
├── view_results.php         # Page for admin to view user test results
├── setup.php                # Script to set up the database and tables
├── logout.php               # Logout script
│
├── assets/                  # Static assets (CSS, JS, Images)
│   ├── css/
│   │   └── styles.css       # CSS for styling the application
│   ├── js/
│   │   └── scripts.js       # JavaScript for dynamic behavior
│   └── images/              # Images folder
│
├── includes/                # Reusable PHP components
│   ├── header.php           # Header for all pages
│   ├── footer.php           # Footer for all pages
│   └── db.php               # Database connection script
│
├── sql/                     # SQL files for database setup
│   └── form_wizard.sql      # SQL script to set up the database and tables
│
├── tests/                   # Folder to store JSON-encoded test data
│   └── test_1.json          # Example test file
│
├── uploads/                 # Directory for user-uploaded files (if needed)
│
└── config/
    └── config.php           # Configuration file for site-wide settings (e.g., DB credentials)