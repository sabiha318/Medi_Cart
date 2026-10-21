# MediCart — Online Pharmacy Management System

A full-stack pharmacy e-commerce platform built with **Laravel** + **Blade** + **Bootstrap 5** + **MySQL**.
Customers can browse medicines, manage a cart, check out, and track orders. Admins manage medicines,
categories, orders, and customers from a dedicated dashboard.

---

## ⚠️ Important — how this ZIP is organized

This ZIP contains the **application code only** (`app/`, `database/`, `resources/views/`, `routes/`,
`bootstrap/app.php`, `composer.json`, `.env.example`), not a full vendor-installed Laravel skeleton
(no `vendor/`, no `config/*.php`, no `public/index.php`, no `artisan` binary). Those files are
identical boilerplate in every Laravel 11 install and are best generated fresh by Composer so you get
files matched to the exact framework version on your machine. Follow the setup steps below — it takes
about 5 minutes.

## 1) Setup Instructions

```bash
# 1. Create a fresh Laravel 11 skeleton (generates config/, public/index.php, artisan, vendor/, etc.)
composer create-project laravel/laravel medicart-app
cd medicart-app

# 2. Copy the contents of this ZIP into the new project, overwriting where prompted:
#    app/, database/, resources/views/, routes/web.php, bootstrap/app.php,
#    public/css/, public/images/, composer.json (merge if you customized the fresh one), .env.example

# 3. Install dependencies
composer install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Set your MySQL credentials in .env, then create the database:
mysql -u root -p -e "CREATE DATABASE medicart"

# 6. Run migrations + seeders (creates admin/customer demo accounts, categories, sample medicines)
php artisan migrate --seed

# 7. Link storage so uploaded medicine images are publicly viewable
php artisan storage:link

# 8. Serve the app
php artisan serve
```

Visit `http://localhost:8000`.

**Demo accounts (from `AdminSeeder`):**
| Role     | Email                  | Password  |
|----------|-------------------------|-----------|
| Admin    | admin@medicart.test     | password  |
| Customer | customer@medicart.test  | password  |

---

## 2) Project Overview

| Layer          | Technology                                   |
|----------------|-----------------------------------------------|
| Backend        | PHP 8.2+, Laravel 11                          |
| Frontend       | Blade templates, Bootstrap 5, Font Awesome    |
| Database       | MySQL                                          |
| Auth           | Laravel session-based auth (custom, no Breeze) |
| File storage   | Laravel `Storage` (public disk) for images    |
| Access control | Custom `is.admin` / `is.customer` middleware  |

---

## 3) Folder Structure (what's in this ZIP)

```
medicart/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MedicineController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── CustomerController.php
│   │   │   ├── Customer/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── AddressController.php
│   │   │   └── Controller.php
│   │   └── Middleware/
│   │       ├── IsAdmin.php
│   │       └── IsCustomer.php
│   └── Models/
│       ├── User.php, Category.php, Medicine.php
│       ├── Cart.php, Address.php, Order.php, OrderItem.php
│
├── database/
│   ├── migrations/          (7 migrations — users role/phone, categories, medicines,
│   │                          addresses, carts, orders, order_items)
│   ├── seeders/              (AdminSeeder, CategorySeeder, MedicineSeeder)
│   └── factories/MedicineFactory.php
│
├── resources/views/
│   ├── layouts/ (app.blade.php — storefront nav, admin.blade.php — sidebar)
│   ├── auth/ (login, register)
│   ├── admin/ (dashboard, medicines/, categories/, orders/, customers/)
│   └── customer/ (home, medicines/, cart/, checkout/, orders/, address/)
│
├── routes/web.php
├── bootstrap/app.php          (registers is.admin / is.customer middleware aliases)
├── public/css/app.css, public/images/medicine-placeholder.svg
├── composer.json
└── .env.example
```

---

## 4) Database Design

| Table          | Key Columns                                                                                     | Relationships |
|----------------|---------------------------------------------------------------------------------------------------|---------------|
| `users`        | id, name, email, password, **role** (customer/admin), phone                                       | hasMany orders, addresses, cartItems |
| `categories`   | id, name, slug, description                                                                        | hasMany medicines |
| `medicines`    | id, category_id (FK), name, brand, slug, price, stock, unit (strip/bottle/box), image, description | belongsTo category |
| `addresses`    | id, user_id (FK), name, phone, address, city, is_default                                           | belongsTo user, hasMany orders |
| `carts`        | id, user_id (FK), medicine_id (FK), quantity — unique(user_id, medicine_id)                        | belongsTo user, medicine |
| `orders`       | id, user_id (FK), address_id (FK, nullable), recipient/shipping snapshot fields, delivery_option, delivery_fee, payment_method, subtotal, total_amount, status | belongsTo user, address; hasMany items |
| `order_items`  | id, order_id (FK), medicine_id (FK, nullable), medicine_name, price, quantity, subtotal             | belongsTo order, medicine |

**Design notes:**
- `orders` snapshots the recipient name/phone/address and each `order_item`'s medicine name/price at the
  time of purchase, so later edits to a customer's address or a medicine's price never distort historical
  orders.
- `carts` has a unique constraint on `(user_id, medicine_id)` so adding the same medicine twice just
  increments quantity instead of creating duplicate rows.

---

## 5) Route Map (`routes/web.php`)

| Group | Middleware | Example Routes |
|-------|-----------|----------------|
| Public | — | `GET /`, `GET /medicines`, `GET /medicines/{medicine}` |
| Guest  | `guest` | `GET/POST /register`, `GET/POST /login` |
| Customer | `auth`, `is.customer` | `/cart`, `/checkout`, `/orders`, `/address` (full CRUD where relevant) |
| Admin | `auth`, `is.admin`, prefix `/admin` | `/admin/dashboard`, resourceful `/admin/medicines`, `/admin/categories`, `/admin/orders`, `/admin/customers` |

`is.admin` blocks non-admins with a 403; `is.customer` redirects an admin who wanders into the storefront
checkout flow back to their dashboard, keeping the two experiences separate.

---

## 6) Controller Responsibilities

**Admin**
- `DashboardController@index` — totals (medicines/orders/customers/pending), recent orders, low-stock list.
- `MedicineController` — full CRUD + search/category filter + image upload via `Storage::disk('public')`.
- `CategoryController` — full CRUD; blocks deleting a category that still has medicines.
- `OrderController` — list/filter by status, show detail, `updateStatus` (pending → processing → delivered).
- `CustomerController` — list/search customers, view a customer's order + address history.

**Customer**
- `HomeController` — landing page + catalogue with search/category/sort, medicine detail page.
- `CartController` — add/update/remove; quantity is clamped to available stock.
- `CheckoutController` — shows address/delivery/payment selection; `store()` re-validates stock, wraps
  order + order_item creation and stock decrement in a DB transaction, then clears the cart.
- `OrderController` — order history + detail/tracking page with a visual status stepper.
- `AddressController` — full CRUD, with a single "default address" enforced per customer.

---

## 7) Core Business Logic Enforced

- Stock is decremented **only after** a successful transaction-wrapped order placement (see
  `CheckoutController@store`), and stock is re-checked immediately before committing, so two customers
  can't both check out the last unit.
- Cart quantities can never exceed a medicine's current stock (`min($quantity, $medicine->stock)`).
- Customers only ever query their **own** `user_id` for orders/addresses/cart — enforced with
  `abort_if($resource->user_id !== Auth::id(), 403)` in every relevant controller method.
- Only `is.admin`-protected routes can create/edit/delete medicines, categories, or change order status.
- Medicines with `stock = 0` show "Out of Stock" and disable the add-to-cart button/form.

---

## 8) Build Order (suggested)

1. `composer create-project laravel/laravel` → drop in this ZIP's files → `composer install`.
2. Run migrations, confirm schema in a MySQL client.
3. Run seeders, log in as `admin@medicart.test` and `customer@medicart.test` to sanity-check both roles.
4. Walk the customer flow end-to-end: register → browse → add to cart → add address → checkout → track order.
5. Walk the admin flow: add a category → add a medicine with an image → watch it appear in the storefront →
   place a test order as the customer → update its status as the admin.
6. Polish: add real product photography, tune the Bootstrap theme colors, add pagination styling, and
   consider the optional enhancements below.

---

## 9) Optional Enhancements Not Yet Wired Up

- AJAX cart add/update (currently full-page form posts — swap for `fetch()` + Blade `@stack('scripts')`).
- Live search-as-you-type on the medicine catalogue.
- A printable invoice/PDF view for `orders.show`.
- Image preview before upload on the admin medicine form (small vanilla-JS `FileReader` snippet).
- Rate-limiting login attempts (Laravel's `throttle` middleware) if this goes anywhere near production.

---

## 10) A Note on Production Readiness

This is built to be a clean, realistic **final-year-project-grade** codebase: correct relationships,
transaction-safe stock handling, role separation, and CSRF-protected forms throughout. Before treating it
as production-ready you'd still want: automated tests (`tests/Feature`), rate limiting, email verification,
a real payment gateway integration in place of the bKash UI stub, and moving demo credentials out of the
seeder for anything public-facing.
