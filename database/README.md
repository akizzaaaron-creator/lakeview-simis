# SIMIS Database

SQL files for the **Lakeview Skills Institute SIMIS** database (ten entities,
matching the academic ERD).

## Files

| File | Purpose |
|------|---------|
| `schema.sql` | Creates the ten tables with primary/foreign keys: `user`, `programme`, `course`, `student`, `enrolment`, `fee_charge`, `payment`, `attendance`, `assessment`, `result` |
| `seed.sql` | Inserts fictional sample data, including five demo user accounts |

## Setup with XAMPP / MySQL

The database name used by the application is **`simis_lakeview`** (see `config/config.php`).

1. Start **MySQL** from the XAMPP control panel (or ensure the MySQL service is running).
2. Create the database, then import the schema followed by the seed data.

### Option A — Command line (XAMPP mysql client)

```powershell
# run from the project root
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS simis_lakeview CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
& "C:\xampp\mysql\bin\mysql.exe" -u root simis_lakeview < database\schema.sql
& "C:\xampp\mysql\bin\mysql.exe" -u root simis_lakeview < database\seed.sql
```

### Option B — phpMyAdmin

1. Open `http://localhost/phpmyadmin`.
2. Create a new database named `simis_lakeview` (collation `utf8mb4_unicode_ci`).
3. Import `database/schema.sql`, then import `database/seed.sql`.

## Demo login credentials

All five demo accounts use the password **`password`** (demo only).

| Username     | Role              |
|--------------|-------------------|
| `admin`      | Administrator     |
| `admissions` | Admissions Officer|
| `finance`    | Finance Officer   |
| `academic`   | Academic Officer  |
| `management` | Management        |

## Notes

- Default XAMPP credentials are `root` with no password. Change `config/config.php` if your MySQL setup differs.
- All seed data is fictional and provided for demonstration only.
