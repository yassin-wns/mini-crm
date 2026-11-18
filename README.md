# MiniCRM — Modern Enterprise Customer Operations Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13" />
  <img src="https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.5" />
  <img src="https://img.shields.io/badge/MySQL-10.11-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Tests-19%20Passed-10B981?style=for-the-badge&logo=checkmarx&logoColor=white" alt="Tests" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License MIT" />
</p>

**MiniCRM** is a high-performance, lightweight Customer Relationship Management (CRM) platform built for small-to-medium businesses and sales organizations. Engineered with **PHP 8.5**, **Laravel 13**, and **MySQL**, it provides lead tracking, account management, multi-role authorization, server-side filtering, and a headless REST API.

---

## 🌟 Key Capabilities

### 🏢 Customer Lifecycle Management
- **Lead & Account Pipeline**: Classify contacts into `Lead`, `Active`, and `Inactive` lifecycle stages.
- **Full Contact Profiles**: Track phone numbers, corporate email addresses, company affiliations, and internal account notes.
- **Server-Side Multi-Query Search**: Instant search matching names, corporate domains, and email addresses with server-side status filtering.
- **Automatic Sequence Reset**: Database engine automatically cleans and resets sequence counters upon table purge for pristine record numbering.

### 📊 Real-Time Operations Dashboard
- **Executive Metric Cards**: Real-time KPI summaries (Total Accounts, Active Engagements, Sales Leads, Inactive Accounts).
- **Recent Activity Feed**: Immediate overview of newly onboarded accounts with one-click profile routing.

### 🛡️ Enterprise Security & Access Control
- **Dual-Tier Role-Based Access Control (RBAC)**:
  - **Administrator**: Full platform authority, organization-wide customer management, and unconditional deletion rights.
  - **Employee**: Day-to-day customer management, lead updates, and ownership-restricted record deletion.
- **State-of-the-Art Protection**: Session regeneration on authentication state changes, Bcrypt password hashing, and CSRF token verification across all state-altering endpoints.

### 🌐 Headless RESTful API
- Dedicated JSON API for third-party integrations, mobile clients, and webhooks.
- Standard HTTP status code semantics (`200 OK`, `201 Created`, `404 Not Found`, `422 Unprocessable Content`).

---

## 🏗️ Technical Architecture

| Layer | Implementation | Description |
|---|---|---|
| **Backend Framework** | Laravel 13.x on PHP 8.5 | High-speed MVC architecture using Eloquent ORM, Service Providers, and Middleware |
| **Database** | MySQL / MariaDB (InnoDB) | Normalized relational schema with foreign key constraints, indexes, and cascading deletions |
| **Presentation Layer** | Native PHP Templates (`.php`) | Rendered via Laravel's native `PhpEngine` without runtime template compilation overhead |
| **Design System** | Custom Pure CSS3 | Zero-dependency responsive design system inspired by Linear and Stripe Dashboard |
| **Client Scripts** | Vanilla JavaScript (ES6) | Lightweight DOM micro-interactions for password reveal and mobile drawer navigation |

---

## 📂 Repository Structure

```text
mini-crm/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   └── CustomerController.php   # Headless REST API Controller
│   │       ├── AuthController.php           # Session Authentication Controller
│   │       ├── CustomerController.php       # Web Resource CRUD & Search Controller
│   │       └── DashboardController.php      # Metrics & KPI Aggregation Controller
│   └── Models/
│       ├── Customer.php                     # Customer Eloquent Model with Creator Relations
│       └── User.php                         # User Model with Role Helpers (Admin/Employee)
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   └── 2026_11_18_000001_create_customers_table.php
│   └── seeders/
│       └── DatabaseSeeder.php               # Idempotent Database Seeder
├── public/
│   ├── css/
│   │   └── style.css                        # Linear/Stripe Inspired Custom CSS
│   └── js/
│       └── app.js                           # Vanilla JS Drawer & UI Interactivity
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.php                    # Enterprise Sign In Interface
│       ├── customers/
│       │   ├── create.php                   # Customer Onboarding Form
│       │   ├── edit.php                     # Customer Profile Editor
│       │   ├── index.php                    # Master Customer Directory & Search
│       │   └── show.php                     # Customer Profile & Audit Log
│       ├── layouts/
│       │   ├── footer.php                   # Corporate Global Footer
│       │   ├── header.php                   # Navigation Header & Health Monitor
│       │   └── sidebar.php                  # Primary Workspaces Sidebar
│       └── dashboard.php                    # Executive Dashboard View
├── routes/
│   ├── api.php                              # REST API Route Declarations
│   └── web.php                              # Web Application Routes
├── tests/
│   └── Feature/
│       └── CrmTest.php                      # Comprehensive Feature & API Test Suite
└── README.md
```

---

## ⚡ Quickstart & Installation

### Prerequisites
- PHP 8.3 or higher (PHP 8.5 recommended)
- MySQL 8.0+ or MariaDB 10.4+
- Composer 2.x

### 1. Clone the repository
```bash
git clone https://github.com/your-username/mini-crm.git
cd mini-crm
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment configuration
Copy the sample environment file and configure your MySQL database credentials:
```bash
cp .env.example .env
```

Verify the database connection variables in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_crm
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database initialization
Run schema migrations and initialize default accounts:
```bash
php artisan migrate
php artisan db:seed
```

### 5. Launch the application
```bash
php artisan serve
```

The application is now accessible at **`http://127.0.0.1:8000`**.

---

## 🔑 Default Credentials

| User Type | Email | Password | Access Level |
|---|---|---|---|
| **Administrator** | `admin@crm.local` | `password123` | Full control across all accounts & operations |
| **Team Member** | `employee@crm.local` | `password123` | Operational access & self-owned record control |

---

## 📡 REST API Reference

All requests and responses use standard JSON (`Content-Type: application/json`, `Accept: application/json`).

### Endpoints

| Method | Endpoint | Description | Status Codes |
|---|---|---|---|
| `GET` | `/api/customers` | Query customer directory (supports `?search=` and `?status=`) | `200 OK` |
| `GET` | `/api/customers/{id}` | Retrieve individual customer record | `200 OK`, `404 Not Found` |
| `POST` | `/api/customers` | Create new customer account | `201 Created`, `422 Unprocessable` |
| `PUT` | `/api/customers/{id}` | Update customer information | `200 OK`, `404 Not Found`, `422 Unprocessable` |
| `DELETE` | `/api/customers/{id}` | Permanently remove customer account | `200 OK`, `404 Not Found` |

### Sample JSON Request (`POST /api/customers`)
```json
{
  "name": "Acme Innovations Ltd",
  "email": "contact@acme.com",
  "phone": "+1 (555) 0199",
  "company": "Acme Innovations",
  "status": "Active",
  "notes": "Enterprise SLA with 100+ seats."
}
```

---

## 🧪 Automated Testing

MiniCRM includes a complete automated test suite covering authentication, RBAC boundaries, CRUD operations, database constraints, and API responses.

Execute the test suite:
```bash
php vendor/phpunit/phpunit/phpunit
```

**Results:**
```text
Tests: 19, Assertions: 65, Failures: 0, Errors: 0
Status: 100% Passed
```

---

## 📄 License

This software is open-sourced under the [MIT License](LICENSE).
MiniCRM Technologies &copy; 2026. All rights reserved.
