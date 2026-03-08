-- ===========================================================
-- StockEase Inventory Management System - Complete Schema
-- ===========================================================
-- This schema supports all features in the PHP application:
-- - User authentication (registration, login)
-- - Product management (CRUD)
-- - Category management (CRUD)
-- - Supplier management (CRUD)
-- - Purchase transactions (auto stock increase)
-- - Sales transactions (auto stock decrease)
-- - Dashboard analytics
-- - Inventory view with low stock alerts
-- ===========================================================

-- Create database
CREATE DATABASE IF NOT EXISTS stockease CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stockease;

-- Drop existing objects in correct order
SET FOREIGN_KEY_CHECKS = 0;
DROP VIEW IF EXISTS low_stock_view;
DROP VIEW IF EXISTS inventory_view;
DROP TRIGGER IF EXISTS increase_stock_after_purchase;
DROP TRIGGER IF EXISTS decrease_stock_after_sale;
DROP TRIGGER IF EXISTS prevent_negative_stock_before_sale;
DROP TABLE IF EXISTS sales_items;
DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS purchase_items;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ===========================================================
-- TABLE: users
-- Stores user authentication data
-- Required by: backend/register_user.php, backend/login_user.php
-- ===========================================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL COMMENT 'Password hashed using password_hash()',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_email (email),
    INDEX idx_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: categories
-- Stores product categories
-- Required by: backend/add_category.php, backend/update_category.php
-- ===========================================================
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categories_name (category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: suppliers
-- Stores supplier information
-- Required by: backend/add_supplier.php, backend/update_supplier.php
-- ===========================================================
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_suppliers_name (supplier_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: products
-- Stores product details with stock management
-- Required by: backend/add_product.php, backend/update_product.php
-- ===========================================================
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(120) NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    low_stock_level INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_products_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_products_price CHECK (price >= 0),
    CONSTRAINT chk_products_stock CHECK (stock_quantity >= 0),
    CONSTRAINT chk_products_low_stock CHECK (low_stock_level >= 0),
    INDEX idx_products_category (category_id),
    INDEX idx_products_supplier (supplier_id),
    INDEX idx_products_name (product_name),
    INDEX idx_products_stock (stock_quantity, low_stock_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: purchases
-- Stores purchase order headers
-- Required by: backend/add_purchase.php
-- ===========================================================
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    purchase_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchases_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_purchases_date (purchase_date),
    INDEX idx_purchases_supplier (supplier_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: purchase_items
-- Stores individual items in each purchase
-- Required by: backend/add_purchase.php, pages/purchases.php
-- ===========================================================
CREATE TABLE purchase_items (
    purchase_item_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL COMMENT 'Purchase price per item',
    CONSTRAINT fk_purchase_items_purchase
        FOREIGN KEY (purchase_id) REFERENCES purchases(purchase_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_purchase_items_product
        FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_purchase_items_qty CHECK (quantity > 0),
    CONSTRAINT chk_purchase_items_price CHECK (price > 0),
    INDEX idx_purchase_items_purchase (purchase_id),
    INDEX idx_purchase_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: sales
-- Stores sales transaction headers
-- Required by: backend/add_sale.php
-- ===========================================================
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sales_date (sale_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TABLE: sales_items
-- Stores individual items in each sale
-- Required by: backend/add_sale.php, pages/sales.php
-- ===========================================================
CREATE TABLE sales_items (
    sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL COMMENT 'Sale price per item',
    CONSTRAINT fk_sales_items_sale
        FOREIGN KEY (sale_id) REFERENCES sales(sale_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_sales_items_product
        FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_sales_items_qty CHECK (quantity > 0),
    CONSTRAINT chk_sales_items_price CHECK (price > 0),
    INDEX idx_sales_items_sale (sale_id),
    INDEX idx_sales_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================
-- TRIGGERS: Automatic Stock Management
-- ===========================================================

DELIMITER //

-- Trigger: Increase stock quantity after purchase
CREATE TRIGGER increase_stock_after_purchase
AFTER INSERT ON purchase_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock_quantity = stock_quantity + NEW.quantity,
        updated_at = CURRENT_TIMESTAMP
    WHERE product_id = NEW.product_id;
END//

-- Trigger: Validate stock before sale (prevents negative stock)
CREATE TRIGGER prevent_negative_stock_before_sale
BEFORE INSERT ON sales_items
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;
    
    SELECT stock_quantity INTO current_stock
    FROM products
    WHERE product_id = NEW.product_id;
    
    IF current_stock IS NULL THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Invalid product for sale.';
    END IF;
    
    IF NEW.quantity > current_stock THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Insufficient stock for sale.';
    END IF;
END//

-- Trigger: Decrease stock quantity after sale
CREATE TRIGGER decrease_stock_after_sale
AFTER INSERT ON sales_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock_quantity = stock_quantity - NEW.quantity,
        updated_at = CURRENT_TIMESTAMP
    WHERE product_id = NEW.product_id;
END//

DELIMITER ;

-- ===========================================================
-- VIEWS: Reporting and Analytics
-- ===========================================================

-- View: Complete inventory with category and supplier details
-- Used by: pages/inventory.php
CREATE VIEW inventory_view AS
SELECT
    p.product_id,
    p.product_name,
    c.category_name,
    s.supplier_name,
    p.price,
    p.stock_quantity,
    p.low_stock_level,
    (p.stock_quantity * p.price) AS total_value,
    CASE 
        WHEN p.stock_quantity <= p.low_stock_level THEN 'Low Stock'
        ELSE 'In Stock'
    END AS stock_status
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
INNER JOIN suppliers s ON p.supplier_id = s.supplier_id;

-- View: Products with low stock levels
-- Used by: pages/dashboard.php
CREATE VIEW low_stock_view AS
SELECT
    product_id,
    product_name,
    stock_quantity,
    low_stock_level,
    (low_stock_level - stock_quantity) AS deficit
FROM products
WHERE stock_quantity <= low_stock_level
ORDER BY stock_quantity ASC;

-- ===========================================================
-- SAMPLE DATA: Initial seed data for testing
-- ===========================================================

INSERT INTO categories (category_name, description) VALUES
('Electronics', 'Electronic items like laptops, phones, and accessories'),
('Furniture', 'Office and home furniture including desks and chairs'),
('Stationery', 'Office supplies and stationery items');

INSERT INTO suppliers (supplier_name, phone, email, address) VALUES
('ABC Traders', '9876543210', 'abc@traders.com', 'Kochi, Kerala, India'),
('XYZ Supplies', '9123456780', 'xyz@supplies.com', 'Ernakulam, Kerala, India'),
('Tech Distributors', '9988776655', 'info@techdist.com', 'Bangalore, Karnataka, India');

INSERT INTO products (product_name, category_id, supplier_id, price, stock_quantity, low_stock_level) VALUES
('Laptop', 1, 1, 50000.00, 10, 5),
('Office Chair', 2, 2, 1500.00, 20, 5),
('Mobile Phone', 1, 1, 20000.00, 15, 5),
('Wireless Mouse', 1, 3, 500.00, 30, 10),
('Notebook A4', 3, 2, 50.00, 100, 20);

-- ===========================================================
-- VERIFICATION QUERIES (Optional - for testing)
-- ===========================================================

-- Uncomment below to verify setup:
-- SELECT * FROM categories;
-- SELECT * FROM suppliers;
-- SELECT * FROM products;
-- SELECT * FROM inventory_view;
-- SELECT * FROM low_stock_view;

-- ===========================================================
-- Schema creation complete!
-- Import this file in phpMyAdmin to setup the database.
-- ===========================================================
