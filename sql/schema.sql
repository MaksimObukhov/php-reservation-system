SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Databáze: `xname`


-- Struktura tabulky `users`
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Struktura tabulky `admins`
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


-- Struktura tabulky `barbers`
CREATE TABLE barbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Struktura tabulky `schedules`
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barber_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    is_available BOOL NOT NULL DEFAULT TRUE,
    FOREIGN KEY (barber_id) REFERENCES barbers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Struktura tabulky `bookings`
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    schedule_id INT NOT NULL,
    user_id INT,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
