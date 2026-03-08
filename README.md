# StockEase - Inventory Management System

StockEase is a lightweight DBMS mini-project built with:
- HTML
- CSS
- JavaScript
- Procedural PHP
- MySQL

It supports user authentication, dashboard analytics, product/category/supplier management, purchase and sales entry, inventory view, and low stock alerts.

## 1. Project Structure

```text
stockease/
  index.php
  stockease.sql
  README.md
  test.php

  auth/
    login.php
    register.php
    logout.php

  pages/
    dashboard.php
    products.php
    categories.php
    suppliers.php
    purchases.php
    sales.php
    inventory.php

  backend/
    db.php
    session.php
    auth_check.php
    helpers.php
    login_user.php
    register_user.php
    add_product.php
    update_product.php
    delete_product.php
    add_category.php
    update_category.php
    delete_category.php
    add_supplier.php
    update_supplier.php
    delete_supplier.php
    add_purchase.php
    add_sale.php

  config/
    config.php

  css/
    style.css

  js/
    script.js

  includes/
    header.php
    footer.php
```

## 2. Local Setup (XAMPP)

1. Place the `stockease` folder inside `xampp/htdocs/`.
2. Start `Apache` and `MySQL` from XAMPP Control Panel.
3. Open `phpMyAdmin`.
4. Import `stockease.sql`.
5. Open in browser:
   - `http://localhost/stockease`
6. Register a new user from the registration page.
7. Login and use the dashboard.

## 3. Database Configuration

Edit `config/config.php` if needed:

- `db_host`
- `db_name`
- `db_user`
- `db_pass`

Default local values:
- host: `localhost`
- db: `stockease`
- user: `root`
- pass: empty

You can also use environment variables (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `BASE_URL`) for deployment.

## 4. Deployment on Shared Hosting

1. Upload all files/folders to your hosting public directory (for example `public_html/stockease`).
2. Create a MySQL database from hosting control panel.
3. Import `stockease.sql` into that database.
4. Update credentials in `config/config.php` (or set env variables if hosting supports it).
5. Ensure PHP sessions are enabled (normally enabled by default).
6. Open your hosted URL and register/login.

## 5. Security Notes

- Passwords are hashed using `password_hash()`.
- Login verification uses `password_verify()`.
- Auth-protected pages use session checks.
- SQL operations use prepared statements.
- Delete actions include confirmation prompts.

## 6. Core Features Delivered

- User registration/login/logout
- Dashboard with summary metrics
- Product CRUD
- Category CRUD
- Supplier CRUD
- Purchase entry (auto stock increase via DB trigger)
- Sales entry (auto stock decrease via DB trigger)
- Inventory view with low stock highlighting
- Responsive dashboard UI (coffee-brown + black + light neutral theme)
