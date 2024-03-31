-- Create the travago database
CREATE DATABASE IF NOT EXISTS travago;

--------------------------------------------------------------------------------------------------------------------------------------
-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    isVerified BOOLEAN DEFAULT FALSE,
    address VARCHAR(255),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP user_type ENUM('admin', 'user', 'agency') DEFAULT 'user' NOT NULL,
    company_id INT,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    UNIQUE (email)
);

------------------------------------------------------
-- add relation company id to the users table 
ALTER TABLE
    users
ADD
    FOREIGN KEY (company_id) REFERENCES companies(id);

--------------------------------------------------------------------------------------------------------------------------------------
-- create the companies table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP UNIQUE (email)
);

--------------------------------------------------------
-- insert Travago into the companies table
insert into
    companies (name, email, phone, address)
values
    (
        'Travago',
        'travago@dev.com',
        '1234567890',
        '123, Travago Street, Sousse'
    );

--------------------------------------------------------------------------------------------------------------------------------------
-- create the trips table
CREATE TABLE IF NOT EXISTS trips (
    Id int(7) NOT NULL AUTO_INCREMENT,
    Destination varchar(255) NOT NULL,
    Flight_number int(10) NOT NULL,
    Number_of_seats int(7) NOT NULL,
    Plan varchar(255) NOT NULL,
    Departure_date date NOT NULL,
    Arrival_date date NOT NULL,
    Hotel varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    company_id int(7) NOT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    PRIMARY KEY (Id)
);

--------------------------------------------------------------------------------------------------------------------------------------
-- ALTER 
ALTER TABLE
    users
MODIFY
    COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

--------------------------------------------------------------------------------------------------------------------------------------
-- create the vehicles table
CREATE TABLE IF NOT EXISTS vehicles (
    id int(7) NOT NULL AUTO_INCREMENT,
    brand varchar(255) NOT NULL,
    model varchar(255) NOT NULL,
    number_of_seats int(7) NOT NULL,
    plate_number varchar(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    company_id int(7),
    availablabity BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    PRIMARY KEY (Id)
);

--------------------------------------------------------------------------------------------------------------------------------------
-- create the tours table
CREATE TABLE IF NOT EXISTS tours (
    id int(7) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    program varchar(255) NOT NULL,
    desciption varchar(255) NOT NULL,
    destination varchar(255) NOT NULL,
    number_of_seats int(7) NOT NULL,
    departure_date date NOT NULL,
    arrival_date date NOT NULL,
    accomodation varchar(255) NOT NULL,
    transport_type varchar(255) NOT NULL,
    price int(7) NOT NULL,
    company_id int(7) NOT NULL REFERENCES companies(id),
);