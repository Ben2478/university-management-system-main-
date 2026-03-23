# Laravel Cloud DB Migration (MySQL)

This guide covers two ways to get your database onto Laravel Cloud (or any MySQL-based host):

- **Option A (recommended for go-live):** Create an **empty** cloud DB and run **migrations + seeders**.
- **Option B (when you must keep existing data):** **Export** your local MySQL database and **import** it into the cloud.

## Prerequisites

- You have Laravel Cloud **DB credentials** (host, port, database, username, password).
- Locally (WAMP), you can access your MySQL database (example: `benson`).
- Your deployed app has the correct `.env` values on Laravel Cloud.

---

## Option A — Fresh cloud DB (migrations + seeders)

1) **Create a new MySQL database** in Laravel Cloud.

2) Set these environment variables on Laravel Cloud (values from your DB panel):

```env
DB_CONNECTION=mysql
DB_HOST=YOUR_CLOUD_DB_HOST
DB_PORT=3306
DB_DATABASE=YOUR_CLOUD_DB_NAME
DB_USERNAME=YOUR_CLOUD_DB_USER
DB_PASSWORD=YOUR_CLOUD_DB_PASSWORD
```

3) Deploy, then run migrations on the cloud:

```bash
php artisan migrate --force
```

4) Seed **only the users table** (optional, recommended so you can log in):

Before seeding, you can set these env vars on Laravel Cloud to use your real domain emails/names:

```env
INITIAL_SUPERADMIN_EMAIL=superadmin@yourdomain.com
INITIAL_SUPERADMIN_NAME=Super Admin
INITIAL_ADMIN_EMAIL=admin@yourdomain.com
INITIAL_ADMIN_NAME=System Admin
INITIAL_EXTRA_ADMIN_EMAILS=staff1@yourdomain.com,staff2@yourdomain.com
INITIAL_DISABLE_LEGACY_SEED_USERS=true
```

```bash
php artisan db:seed --class=Database\\Seeders\\InitialUsersSeeder --force
```

Important:
- If you run `php artisan db:seed --force` without `--class`, it will execute your default `DatabaseSeeder` which may seed **other tables**.

Notes:
- This is the cleanest approach if you **don’t need local data**.
- If you already imported a DB dump that includes the `migrations` table, `migrate` may do nothing (that can be correct).

---

## Option B — Move local data to cloud (export + import)

### Step 1: Export local DB (WAMP)

You can export using either:
- **phpMyAdmin** (Export → Quick/Custom → SQL), or
- **mysqldump** (recommended for reliability)

#### Using `mysqldump` (Windows)

1) Find your MySQL bin folder (typical WAMP path looks like):

- `C:\wamp64\bin\mysql\mysql8.0.xx\bin`

2) Run:

```powershell
# Example only — adjust paths/user/db
$mysqlBin = 'C:\wamp64\bin\mysql\mysql8.0.xx\bin'
& "$mysqlBin\mysqldump.exe" --no-tablespaces --single-transaction --quick --routines --triggers --events --default-character-set=utf8mb4 -u root benson > .\benson_dump.sql
```

If your MySQL client errors with `column-statistics`, add:

```text
--column-statistics=0
```

### Step 2: Import into Laravel Cloud MySQL

From your machine (or CI), import the dump into the cloud database:

```powershell
$mysqlBin = 'C:\wamp64\bin\mysql\mysql8.0.xx\bin'
$env:MYSQL_PWD = 'YOUR_CLOUD_DB_PASSWORD'
& "$mysqlBin\mysql.exe" -h YOUR_CLOUD_DB_HOST -P 3306 -u YOUR_CLOUD_DB_USER YOUR_CLOUD_DB_NAME < .\benson_dump.sql
Remove-Item Env:MYSQL_PWD
```

### Step 3: Set cloud `.env` and run migrations

After importing, ensure your cloud environment has the correct DB env vars, then run:

```bash
php artisan migrate --force
```

---

## Common gotchas

- **ANSI_QUOTES / strict SQL mode:** Avoid double-quoted string literals in raw SQL. (Recent fix: use `status = 'completed'`.)
- **Idempotent migrations:** If a table already exists in the imported dump, migrations that create it can fail unless guarded.
- **Large DB dumps:** Prefer `--single-transaction --quick` to reduce locks/memory.
- **Passwords in commands:** Avoid putting passwords directly in the command line; prefer `MYSQL_PWD` temporarily or interactive `-p`.

---

## Quick choice

- If you only need schema + default accounts → **Option A**
- If you must keep real student/payment/enrollment data → **Option B**
