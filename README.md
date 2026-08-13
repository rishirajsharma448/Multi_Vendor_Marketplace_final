# Vyapar Setu – Multi Vendor Marketplace

Vyapar Setu is a production-quality Multi-Vendor Marketplace web application built with **PHP, MySQL (with seamless PDO SQLite fallback), HTML5, CSS3, Bootstrap 5, JavaScript, AJAX, and Chart.js**.

---

## 🌟 Key Features

- **Multi-Role Authentication**:
  - Roles: `Customer`, `Vendor`, `Admin`
  - Secure passwords hashed with `password_hash()` and verified with `password_verify()`
  - Prepared statements preventing SQL injection
  - Flash notification messages & role-based routing middleware

- **Role-Based Dashboards**:
  - **Customer Dashboard**: Orders tracking, wishlist, shopping cart, checkout, saved address & account settings.
  - **Vendor Dashboard**: Store profile, add/edit/delete product inventory, store orders fulfillment status updates, customer review replies, sales analytics charts.
  - **Admin Dashboard**: Master governance dashboard, vendor approval workflow, customer management, product moderation, category management, GMV revenue charts.

- **E-Commerce Storefront**:
  - Homepage hero section, category grid, trending items, seller spotlights
  - Dynamic product filter by category, price, and spotlight
  - AJAX cart badge & wishlist toggling
  - Express multi-step checkout with simulated Razorpay/UPI & COD support

---

## 📁 Directory Structure

```
d:\Multi_Vendor_Marketplace\
├── index.php
├── login.php
├── register.php
├── forgot-password.php
├── reset-password.php
├── verify-email.php
├── logout.php
├── search.php
├── product.php
├── category.php
├── shop.php
├── about.php
├── contact.php
├── faq.php
├── terms.php
├── privacy.php
│
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   ├── vendors.php
│   ├── customers.php
│   ├── products.php
│   ├── categories.php
│   ├── subcategories.php
│   ├── brands.php
│   ├── orders.php
│   ├── payments.php
│   ├── coupons.php
│   ├── banners.php
│   ├── reports.php
│   ├── analytics.php
│   ├── reviews.php
│   ├── support.php
│   ├── notifications.php
│   ├── settings.php
│   └── profile.php
│
├── vendor/
│   ├── dashboard.php
│   ├── profile.php
│   ├── shop-settings.php
│   ├── add-product.php
│   ├── edit-product.php
│   ├── products.php
│   ├── inventory.php
│   ├── orders.php
│   ├── returns.php
│   ├── customers.php
│   ├── reviews.php
│   ├── coupons.php
│   ├── analytics.php
│   ├── earnings.php
│   ├── withdrawal.php
│   ├── messages.php
│   └── notifications.php
│
├── customer/
│   ├── dashboard.php
│   ├── profile.php
│   ├── addresses.php
│   ├── wishlist.php
│   ├── cart.php
│   ├── checkout.php
│   ├── payment.php
│   ├── orders.php
│   ├── order-details.php
│   ├── returns.php
│   ├── reviews.php
│   ├── notifications.php
│   ├── messages.php
│   └── support.php
│
├── api/
│   ├── login.php
│   ├── register.php
│   ├── products.php
│   ├── cart.php
│   ├── wishlist.php
│   ├── orders.php
│   └── search.php
│
├── ajax/
│   ├── add-cart.php
│   ├── remove-cart.php
│   ├── wishlist.php
│   ├── search.php
│   ├── notification.php
│   └── checkout.php
│
├── includes/
│   ├── db.php
│   ├── config.php
│   ├── auth.php
│   ├── session.php
│   ├── functions.php
│   ├── validation.php
│   ├── mail.php
│   ├── header.php
│   ├── navbar.php
│   ├── sidebar.php
│   ├── footer.php
│   └── alerts.php
│
├── uploads/
├── assets/
│   ├── css/style.css
│   ├── js/app.js
│   ├── images/
│   ├── icons/
│   └── fonts/
│
├── database/
│   ├── schema.sql
│   ├── sample-data.sql
│   └── backup.sql
│
└── README.md
```




## ⚙️ How to Run via XAMPP

1. Copy or clone this folder into `C:\xampp\htdocs\Multi_Vendor_Marketplace`.
2. Start **Apache** and **MySQL** modules in XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` in your browser.
4. Import `database/schema.sql` and `database/sample-data.sql` into MySQL.
5. Open `http://localhost/Multi_Vendor_Marketplace` in your browser.
