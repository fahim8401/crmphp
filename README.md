# HPLink CRM (Raw PHP, cPanel-ready)

## Requirements

- PHP 8.0+ or 8.1+
- MySQL (5.7+ recommended)
- Extensions: `pdo_mysql`, `mbstring`, `json`, `fileinfo`
- Shared hosting/cPanel compatible (no Composer or external PHP packages)

## Setup & Installation (Local or cPanel)

**Recommended: Use the Install Wizard**

1. Upload the entire `hplinkcrm` folder to your desired location (e.g., `public_html/hplinkcrm`).
2. Set permissions for `storage/exports` and `storage/logs` to writable (`chmod 755` or `chmod 775` as needed).
3. Visit `install/index.php` in your browser.
   - The wizard will check PHP extensions and folder permissions.
   - Enter your database credentials, timezone, and currency.
   - The wizard will write `config.php` and import the database schema and seed data.
   - On completion, you will see the default admin login and security reminders.
4. **Important:** Delete the `install/` folder after installation for security.

**Manual Setup (Advanced):**

- Copy `config.php.example` to `config.php` and update DB credentials and app settings.
- Import `database/schema.sql` and `database/seed.sql` using phpMyAdmin or MySQL CLI.
- Set permissions for `storage/exports` and `storage/logs` to writable.
- Open `index.php` in your browser.

**Pending Transaction Reminder:**
- Each pending transaction now has a `settle_date` field (date to remind for collection).
- Use this field to track when to follow up for payment.

**Cron Example (Month Close):**
- `php /home/username/hplinkcrm/close_month.php 2025-09`

## Security

- Change the default admin password after first login (`admin@example.test` / `Admin@123`).
- Remove seed users after deployment.
- All passwords are hashed with `password_hash()`.
- CSRF tokens on all forms.
- Output escaped with `htmlspecialchars()`.
- Delete the `install/` folder after installation for security.

## Troubleshooting

- **DB Connection:** Check credentials in `config.php`.
- **Permission Denied:** Ensure `storage/` subfolders are writable.
- **Session Path:** Ensure PHP session path is writable.
- **exec() Disabled:** Some features (month close via web) require `exec()`.

## Manual Test Checklist

- Login as admin, HR, employee; verify role restrictions.
- Create transaction as HR with salary deduction; check salary tracker.
- Mark pending transaction as received; check changed_by/changed_at.
- Run close_month script; verify CSV and pending moved to next month.

## Database Schema Notes

- The `transactions` table includes a `settle_date` column for pending transactions.
- Use this date to trigger reminders or reports for money collection.

## File Structure

```
hplinkcrm/
├─ index.php
├─ .htaccess
├─ assets/
├─ controllers/
├─ services/
├─ lib/
├─ views/
│  └─ layout/
├─ storage/
│  ├─ exports/
│  └─ logs/
├─ database/
│  ├─ schema.sql
│  └─ seed.sql
├─ install/
│  └─ index.php
├─ config.php.example
├─ config.php
├─ close_month.php
└─ README.md
```

## Deployment (cPanel)

- Upload the entire `hplinkcrm` folder to your desired location (e.g., `public_html/hplinkcrm`).
- Visit `install/index.php` in your browser to run the installer, or copy `config.php.example` to `config.php` and edit manually.
- Create MySQL DB & user, set credentials in `config.php`.
- Import `database/schema.sql` and `database/seed.sql` via phpMyAdmin or MySQL CLI, or use the installer.
- Set permissions for `storage/` to 755/775 as required.
- Cron example: `php /home/username/hplinkcrm/close_month.php 2025-09` run monthly.

## Troubleshooting

- **DB Connection:** Check credentials in `config.php`.
- **Permission Denied:** Ensure `storage/` subfolders are writable.
- **Session Path:** Ensure PHP session path is writable.
- **exec() Disabled:** Some features (month close via web) require `exec()`.

## Manual Test Checklist

- Run the install wizard and verify all requirements and permissions are checked.
- Login as admin, HR, employee; verify role restrictions.
- Create transaction as HR with salary deduction; check salary tracker.
- Mark pending transaction as received; check changed_by/changed_at.
- Run close_month script; verify CSV and pending moved to next month.
