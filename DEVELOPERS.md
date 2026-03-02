# Developer Documentation - HPLink CRM

## Project Structure

```
crmphp/
├── assets/               # CSS, JS, images
│   ├── app.js           # Main JavaScript file
│   └── logo.png         # Application logo
├── config.php           # Configuration file (gitignored)
├── config.php.example   # Configuration template
├── controllers/         # Business logic controllers
│   ├── AuthController.php
│   ├── ClientsController.php
│   ├── EmployeesController.php
│   ├── TransactionsController.php
│   └── UsersController.php
├── database/            # Database schema and seeds
│   ├── schema.sql
│   └── seed.sql
├── index.php            # Main router and entry point
├── lib/                 # Core library functions
│   ├── auth.php         # Authentication functions
│   ├── database.php     # Database connection
│   ├── helpers.php      # Utility functions
│   └── role_checks.php  # Authorization functions
├── services/            # Business logic services
│   ├── ActivityLogService.php
│   ├── CsvService.php
│   └── SalaryService.php
├── storage/             # Writable storage (gitignored)
│   ├── exports/         # CSV exports
│   └── logs/            # Application logs
└── views/               # View templates
    ├── layout/          # Layout components
    ├── dashboard.php
    ├── profile.php
    ├── reports.php
    ├── employees/
    ├── clients/
    ├── transactions/
    └── users/
```

## Development Setup

### Requirements
- PHP 8.0+ or 8.1+
- MySQL 5.7+ or MariaDB
- Extensions: pdo_mysql, mbstring, json, fileinfo

### Installation
1. Clone the repository
2. Copy `config.php.example` to `config.php`
3. Update database credentials in `config.php`
4. Import `database/schema.sql` and `database/seed.sql`
5. Set permissions: `chmod 775 storage/exports storage/logs`
6. Access via web server or `php -S localhost:8000`

## Architecture

### MVC Pattern (Simplified)
- **Models**: Direct database access via PDO in controllers
- **Views**: PHP templates in `views/` directory
- **Controllers**: Business logic in `controllers/` directory

### Routing
All requests go through `index.php` which routes based on `?page=` parameter.

Example: `?page=employees&action=create`

### Authentication & Authorization
- Session-based authentication
- Role-based access control (admin, hr, employee)
- Functions: `is_logged_in()`, `current_user()`, `require_role()`

## Coding Standards

### Security Best Practices
1. **Output Escaping**: Always use `e()` function for HTML output
2. **Prepared Statements**: All database queries use PDO prepared statements
3. **Input Validation**: Validate and sanitize all user inputs
4. **Password Hashing**: Use `password_hash_safe()` and `password_verify_safe()`
5. **Role Checks**: Use `require_role()` for authorization

### Code Style
- Use 4 spaces for indentation
- Follow PHP-FIG PSR-12 style guide where applicable
- Add PHPDoc comments for functions
- Keep functions small and focused

### Naming Conventions
- Controllers: `{Feature}Controller.php`
- Functions: `{feature}_{action}_{type}` (e.g., `employees_create_page()`)
- Views: Match controller actions (e.g., `employees/create.php`)

## Adding New Features

### Step 1: Create Controller
```php
// controllers/NewFeatureController.php
require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/role_checks.php';

function newfeature_index_page() {
    require_role(['admin', 'hr']);
    // Your logic here
    include __DIR__ . '/../views/newfeature/index.php';
}
```

### Step 2: Create Views
Create view files in `views/newfeature/` directory.

### Step 3: Add Routing
Add route case in `index.php`:
```php
case 'newfeature':
    require_once __DIR__ . '/controllers/NewFeatureController.php';
    // Handle actions
    break;
```

### Step 4: Add Navigation
Update `views/layout/sidebar.php` to add menu link.

## Database Access

### Getting Database Connection
```php
$db = get_db();
```

### Running Queries
```php
// SELECT
$stmt = $db->prepare("SELECT * FROM table WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

// INSERT
$stmt = $db->prepare("INSERT INTO table (col1, col2) VALUES (?, ?)");
$stmt->execute([$val1, $val2]);

// UPDATE
$stmt = $db->prepare("UPDATE table SET col1 = ? WHERE id = ?");
$stmt->execute([$val1, $id]);
```

## Helper Functions

### Output & Security
- `e($str)` - Escape HTML output
- `set_flash($type, $msg)` - Set flash message
- `get_flash($type)` - Get and clear flash messages

### Authentication
- `is_logged_in()` - Check if user is logged in
- `current_user()` - Get current user data
- `require_role($roles)` - Require specific role(s)

### Formatting
- `format_date($date, $format)` - Format date
- `format_currency($amount)` - Format currency

## Testing Checklist

Before deploying:
- [ ] Test all CRUD operations
- [ ] Verify role-based access control
- [ ] Check input validation
- [ ] Test error handling
- [ ] Verify flash messages
- [ ] Test on different screen sizes
- [ ] Check database queries for SQL injection
- [ ] Verify XSS protection

## Common Issues

### Database Connection Failed
- Check credentials in `config.php`
- Verify MySQL service is running
- Check user permissions

### Session Issues
- Ensure session path is writable
- Check `session.save_path` in `php.ini`
- Use `install/session_test.php` to diagnose

### Permission Denied
- Set storage folders to 775: `chmod 775 storage/exports storage/logs`
- Check web server user permissions

## Maintenance

### Backup
- Regular MySQL backups
- Backup `config.php` and `storage/` directory
- Version control for code changes

### Logs
- Application logs in `storage/logs/`
- Web server error logs
- MySQL slow query log

## Support

For issues or questions:
- Check README.md for user documentation
- Review code comments and PHPDoc
- Check database schema in `database/schema.sql`
