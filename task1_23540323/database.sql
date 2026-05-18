-- ===========================================================================
-- Online Medicine Shop  --  Database schema (shared by all 3 tasks)
--
-- HOW TO IMPORT (XAMPP):
--   1. Start Apache + MySQL in XAMPP Control Panel
--   2. Open http://localhost/phpmyadmin
--   3. Click "Import" and choose this file
--   4. Admin/customer accounts are auto-created on first page load.
-- ===========================================================================

CREATE DATABASE IF NOT EXISTS online_medicine_shop
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE online_medicine_shop;

CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255)  NOT NULL,
    role            ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    profile_picture VARCHAR(255)  DEFAULT NULL,
    address         VARCHAR(255)  DEFAULT NULL,
    phone           VARCHAR(30)   DEFAULT NULL,
    remember_token  VARCHAR(64)   DEFAULT NULL,
    created_at      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    category_type ENUM('liquid','solid') NOT NULL DEFAULT 'solid',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicines (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150)   NOT NULL,
    category_id  INT            NOT NULL,
    vendor_name  VARCHAR(150)   NOT NULL,
    price        DECIMAL(10,2)  NOT NULL,
    availability INT            NOT NULL DEFAULT 0,
    description  TEXT           DEFAULT NULL,
    image_path   VARCHAR(255)   DEFAULT NULL,
    created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_med_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity    INT NOT NULL DEFAULT 1,
    added_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_user FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
    CONSTRAINT fk_cart_med  FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    total_amount     DECIMAL(10,2) NOT NULL,
    shipping_address VARCHAR(255)  NOT NULL,
    status           ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
    payment_method   VARCHAR(50)   DEFAULT NULL,
    order_date       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity    INT NOT NULL,
    unit_price  DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_oi_order FOREIGN KEY (order_id)    REFERENCES orders(id)    ON DELETE CASCADE,
    CONSTRAINT fk_oi_med   FOREIGN KEY (medicine_id) REFERENCES medicines(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    order_id       INT NOT NULL,
    amount         DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50)   NOT NULL,
    transaction_id VARCHAR(80)   DEFAULT NULL,
    payment_date   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pay_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (name, category_type) VALUES
    ('Aspirin genre','solid'), ('Paracetamol genre','solid'),
    ('Cough Syrup genre','liquid'), ('Antibiotic genre','solid'), ('Saline genre','liquid');

INSERT INTO medicines (name, category_id, vendor_name, price, availability, description) VALUES
    ('Aspirin 75mg',     1,'Square Pharma',  2.50,120,'Low-dose aspirin tablet.'),
    ('Aspirin 300mg',    1,'Beximco Pharma', 3.00, 80,'Pain relief aspirin tablet.'),
    ('Napa 500mg',       2,'Beximco Pharma', 1.20,200,'Paracetamol tablet for fever.'),
    ('Ace 500mg',        2,'Square Pharma',  1.50,150,'Paracetamol tablet.'),
    ('Adryl Syrup',      3,'ACI Limited',    4.75, 60,'Cough relief syrup 100ml.'),
    ('Tusca Plus Syrup', 3,'Square Pharma',  5.25, 45,'Expectorant cough syrup.'),
    ('Azithromycin 500', 4,'Incepta Pharma', 8.00, 40,'Antibiotic capsule.'),
    ('Normal Saline',    5,'Libra Pharma',   3.50, 90,'IV saline 500ml.');
