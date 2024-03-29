-- Create the travago database
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
    user_type ENUM('admin', 'user', 'agency') DEFAULT 'user' NOT NULL,
    company_id INT
);
-- add relation company id to the users table 
ALTER TABLE users ADD FOREIGN KEY (company_id) REFERENCES companies(id);
-- remove constraint not null from company_id
--------------------------------------------------------------------------------------------------------------------------------------
-- create the companies table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

insert into companies (name, email, phone, address) values ('Travago',  
'travago@dev.com', '1234567890', '123, Travago Street, Sousse');