# Lakeview Skills Institute — SIMIS

**Student Information and Management Information System**
Year Two Diploma in Information Technology — Academic Project

---

## Overview

SIMIS is a web-based prototype that replaces paper files and spreadsheets at Lakeview Skills Institute (a fictional Ugandan vocational training institute). It provides one integrated system for student registration, enrolment, fees, attendance, results, and reporting — with role-based access for five user types.

---

## Technology Stack

| Layer | Choice |
|---|---|
| Backend | PHP 8 (PDO, sessions, password_hash) |
| Database | MySQL / MariaDB (XAMPP) |
| Frontend | Bootstrap 5.3 + Bootstrap Icons |
| Architecture | Lightweight MVC — front controller, models, controllers, views |
| Local server | XAMPP Apache or PHP built-in development server |

---

## System Modules

| Module | Description |
|---|---|
| Login & Auth | Session-based login, bcrypt passwords, CSRF protection |
| Dashboard | Role-specific summary cards and widgets |
| Students | Register, edit, search, delete, full profile view |
| Programmes | Manage training programmes |
| Courses | Manage courses (each belongs to one programme) |
| Enrolments | Enrol students into programmes per academic year |
| Fee Charges | Record tuition, registration, library charges |
| Payments | Record payments, auto-update charge status, printable receipt |
| Attendance | Bulk mark attendance per course/date, view rates |
| Assessments | Define CA, exam, assignment assessments per course |
| Results | Bulk score entry, auto-grading, student transcript |
| Users | Admin-only: create/edit/delete system users |
| Reports | Enrolment, fees, attendance, pass-rate reports with print view |

---

## User Roles

| Role | Typical Functions |
|---|---|
| **Administrator** | Full access to all modules |
| **Admissions Officer** | Students, enrolments, programmes, courses |
| **Finance Officer** | Fee charges, payments, finance reports |
| **Academic Officer** | Attendance, assessments, results |
| **Management** | View all; full access to reports |

Permissions are enforced **server-side** in every controller. Hiding buttons is a convenience only.

---

## Database

Ten entities matching the academic ERD:

`user` · `programme` · `course` · `student` · `enrolment` · `fee_charge` · `payment` · `attendance` · `assessment` · `result`

See `database/README.md` for detailed setup instructions.

---

## Quick Start (XAMPP)

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) with **Apache** and **MySQL** started
- PHP 8.0+

### 1 — Copy the project

Place the project folder inside your XAMPP web root:

```
C:\xampp\htdocs\simis\
```

Or run directly from any folder using PHP's built-in server (see below).

### 2 — Create the database

Open **phpMyAdmin** (`http://localhost/phpmyadmin`):

1. Create a new database named **`simis_lakeview`** (collation: `utf8mb4_unicode_ci`).
2. Import `database/schema.sql`.
3. Import `database/seed.sql`.

**Or** use the command line (adjust path if needed):

```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS simis_lakeview CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
& "C:\xampp\mysql\bin\mysql.exe" -u root simis_lakeview < database\schema.sql
& "C:\xampp\mysql\bin\mysql.exe" -u root simis_lakeview < database\seed.sql
```

### 3 — Configure the database connection

Open `config/config.php` and confirm:

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);        // change if MySQL runs on a different port
define('DB_NAME', 'simis_lakeview');
define('DB_USER', 'root');
define('DB_PASS', '');          // empty = default XAMPP password
```

### 4 — Run the application

**Option A — XAMPP Apache**

Access at: `http://localhost/simis/`

**Option B — PHP built-in server** (no Apache needed)

```powershell
# From inside the project folder:
& "C:\xampp\php\php.exe" -S localhost:8000
```

Then open: `http://localhost:8000/`

---

## Demo Login Credentials

All accounts use the password **`password`** (for demonstration only).

| Username | Role |
|---|---|
| `admin` | Administrator |
| `admissions` | Admissions Officer |
| `finance` | Finance Officer |
| `academic` | Academic Officer |
| `management` | Management |

---

## Project Structure

```
simis/
├── config/
│   ├── config.php          # App constants, DB credentials, roles
│   ├── database.php        # PDO connection (db() function)
│   └── grades.php          # Configurable grading bands
├── controllers/            # One file per module (request → response)
├── models/                 # Data access via PDO (one file per entity)
├── views/
│   ├── layouts/            # Shared header, sidebar, footer
│   ├── auth/               # Login page
│   ├── dashboard/
│   ├── students/
│   ├── programmes/
│   ├── courses/
│   ├── enrolments/
│   ├── fee_charges/
│   ├── payments/
│   ├── attendance/
│   ├── assessments/
│   ├── results/
│   ├── users/
│   ├── reports/
│   └── errors/             # 403 and 404 pages
├── includes/
│   ├── functions.php       # Helpers: e(), flash, CSRF, view(), money()
│   ├── auth.php            # login(), logout(), current_user()
│   └── access.php          # can(), require_permission() — RBAC
├── public/
│   ├── css/style.css       # Custom teal theme
│   └── js/app.js           # Auto-dismiss alerts, confirm dialogs
├── database/
│   ├── schema.sql          # Ten-table schema with foreign keys
│   ├── seed.sql            # Demo users, programmes, students, records
│   └── README.md           # Database setup instructions
├── index.php               # Front controller / router
├── .gitignore
└── README.md               # This file
```

---

## Grading Bands

Grades are **configurable** in `config/grades.php`. The current demo bands are:

| Grade | Min % | Remark |
|---|---|---|
| A | 80% | Distinction |
| B | 70% | Credit |
| C | 60% | Pass |
| D | 50% | Marginal Pass |
| F | 0% | Fail |

> **Note:** These are sample bands for demonstration only and do not represent any official institutional grading policy.

---

## Security Features

- Passwords stored with `password_hash()` (bcrypt, PHP default cost)
- CSRF token on every POST form
- Server-side permission guard on every controller action (`require_permission()`)
- Session security: `httponly`, `use_only_cookies`, `use_strict_mode`
- PDO prepared statements throughout — no raw SQL interpolation
- Access denied returns HTTP 403 and renders an error view

---

## Known Limitations (Prototype Scope)

- No email sending (password resets are done by admin in the Users module)
- No file uploads (e.g. student photos)
- No pagination on large tables
- Single-campus, single-currency (UGX)
- Runs on HTTP (HTTPS should be enabled before any real deployment)

---

## Academic Context

This prototype was developed as a Year Two Diploma in Information Technology academic project for Lakeview Skills Institute. The implementation is consistent with the accompanying academic report, including the system requirements, DFD, Use Case Diagram, Activity Diagram, and ten-entity ERD.

---

*Lakeview Skills Institute is a fictional institution created for this academic project.*
