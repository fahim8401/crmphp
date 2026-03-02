# HPLink CRM - Feature Overview

## Core Features

### Dashboard
- **Statistics Overview**: View key metrics at a glance
  - Total employees count
  - Total clients count
  - Monthly received transactions amount
  - Monthly pending transactions amount
- **Quick Actions**: Fast access to common tasks
  - Add Employee
  - Add Client
  - Add Transaction
  - View Reports
- **Role-based Display**: Different views for admin, HR, and employee roles
- **System Information**: Current month, user role, timezone, and currency

### Employee Management
- **CRUD Operations**: Create, Read, Update, Delete employees
- **Employee Fields**:
  - Name, Phone, Email
  - Base Salary
  - Join Date
  - Notes
- **Access Control**: Admin and HR can manage employees
- **Validation**: Input validation with error messages
- **List View**: Sortable employee listing with search capability

### Client Management
- **CRUD Operations**: Create, Read, Update, Delete clients
- **Client Fields**:
  - Name, Phone
  - Notes
- **Access Control**: Admin and HR can manage clients
- **List View**: Clean table layout with action buttons

### Transaction Management
- **Transaction Types**:
  - Received: Money received from clients
  - Pending: Outstanding payments
  - Expense: Business expenses
- **Transaction Fields**:
  - Employee assignment
  - Client assignment
  - Amount
  - Description
  - Month/Year
  - Settle Date (for pending transactions)
  - Status (pending/completed)
- **Month-based Filtering**: View transactions by specific month
- **Status Tracking**: Visual badges for transaction types and statuses
- **Access Control**: Admin and HR can manage transactions

### User Management (Admin Only)
- **CRUD Operations**: Create, Read, Update, Delete users
- **User Roles**:
  - Admin: Full system access
  - HR: Manage employees, clients, transactions
  - Employee: Limited view access
- **User Fields**:
  - Name, Email
  - Password (encrypted)
  - Role
  - Link to Employee record
- **Security**: Password hashing, role-based restrictions
- **Self-protection**: Users cannot delete their own account

### Reports & Analytics
- **Month-based Reports**: Financial summary by month
- **Key Metrics**:
  - Total Received
  - Total Pending
  - Total Expenses
  - Net Income
- **Transaction Breakdown**: Detailed count and amounts by type
- **Export Ready**: Data prepared for month closing exports

### Month Closing
- **Web Interface**: Close months directly from browser
- **CLI Support**: Command-line option for automated workflows
- **Automated Tasks**:
  - Create salary trackers for all employees
  - Move pending transactions to next month
  - Generate CSV reports
  - Record month closing in database
- **History View**: List of previously closed months
- **Safety Checks**: Prevent duplicate closings

### Activity Logs
- **Audit Trail**: Track all user actions
- **Filterable**: By user or model type
- **Detailed Information**:
  - User who performed action
  - Action type (create, update, delete)
  - Model name and ID
  - Timestamp
- **Pagination**: Navigate through large log sets

### User Profile
- **Personal Information**: View user details
- **Role Display**: Current role and permissions
- **Account Information**: User ID, email, creation date
- **Employee Link**: View linked employee record if applicable

## Security Features

### Authentication
- Session-based authentication
- Password hashing with bcrypt
- Session regeneration on login
- Secure logout with session destruction

### Authorization
- Role-based access control (RBAC)
- Page-level permission checks
- Function-level role requirements
- Automatic permission denial for unauthorized access

### Input Security
- Output escaping with `e()` function
- Prepared SQL statements (no SQL injection)
- Input validation on all forms
- Error handling with try-catch blocks

### Session Security
- Custom session naming
- HTTP-only cookies
- Session timeout handling
- Session fixation prevention

## UI/UX Features

### Responsive Design
- Mobile-friendly navigation
- Responsive tables and forms
- Touch-friendly buttons
- Collapsible sidebar on mobile

### User Feedback
- Flash messages (success, error, warning, info)
- Auto-dismissing notifications
- Inline validation messages
- Confirmation dialogs for destructive actions

### Accessibility
- Semantic HTML
- ARIA labels where needed
- Keyboard navigation support
- Clear visual hierarchy

### Design System
- Tailwind CSS framework
- Consistent color scheme
- Professional styling
- Clean and modern interface

## Technical Features

### Database
- MySQL/MariaDB support
- PDO with prepared statements
- Foreign key relationships
- Indexed columns for performance

### Code Quality
- PHPDoc comments
- Consistent naming conventions
- Modular architecture
- Separation of concerns (MVC-inspired)

### Compatibility
- PHP 8.0+ support
- cPanel/shared hosting compatible
- No external dependencies
- Standalone operation

### Developer Tools
- Comprehensive documentation
- Development guidelines
- Code examples
- Clear project structure

## Supported Workflows

### Employee Onboarding
1. Create employee record
2. Set base salary
3. Link user account (optional)
4. Track from join date

### Client Management
1. Add client details
2. Link transactions to client
3. Track pending payments
4. Monitor client revenue

### Transaction Processing
1. Record received payment
2. Mark pending invoices
3. Track expenses
4. Update transaction status

### Month-End Closing
1. Review month transactions
2. Generate reports
3. Close month via web or CLI
4. Export CSV for records

### User Administration
1. Create user accounts
2. Assign appropriate roles
3. Link to employee records
4. Manage permissions

## Future Enhancement Ideas

- Email notifications
- PDF report generation
- Advanced search filters
- Data visualization (charts)
- Backup/restore functionality
- Multi-language support
- API for integrations
- Custom report builder
- Employee attendance tracking
- Leave management system
