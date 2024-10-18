CREATE DATABASE IF NOT EXISTS bdd_landing_page;

USE bdd_landing_page;

CREATE TABLE IF NOT EXISTS registrations (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    type VARCHAR(50),
    email VARCHAR(100),
    birth DATE,
    phone VARCHAR(20),
    country VARCHAR(50),
    questions TEXT,
    IP VARCHAR(45),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    counter INT
);
