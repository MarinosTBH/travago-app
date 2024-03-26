-- Create the bookdb database
CREATE DATABASE IF NOT EXISTS travago;
--------------------------------------------------------------------------------------------------------------------------------------
-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    isVerified BOOLEAN DEFAULT FALSE,
    address VARCHAR(255),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    isVerified BOOLEAN DEFAULT FALSE,,
    phone VARCHAR(20);
);
--------------------------------------------------------------------------------------------------------------------------------------
-- Create the sessions table
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    expiry_timestamp TIMESTAMP NOT NULL
);

