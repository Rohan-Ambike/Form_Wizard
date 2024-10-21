-- Create the database
CREATE DATABASE
IF NOT EXISTS formwizard;

-- Use the created database
USE formwizard;

-- Create the users table
CREATE TABLE
IF NOT EXISTS users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR
(50) NOT NULL,
    last_name VARCHAR
(50) NOT NULL,
    employee_email VARCHAR
(100) UNIQUE NOT NULL,
    password VARCHAR
(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the user_access table
CREATE TABLE
IF NOT EXISTS user_access
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email_id VARCHAR
(100) UNIQUE NOT NULL,
    admin_access ENUM
('yes', 'no') DEFAULT 'no',
    test_list JSON DEFAULT '[]',
    FOREIGN KEY
(email_id) REFERENCES users
(employee_email) ON
DELETE CASCADE
);


