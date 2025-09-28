-- CRM Seed Data-- Seed users for all roles
INSERT INTO users (id, name, email, password_hash, role, employee_id, created_at)
VALUES
  (1, 'Admin User', 'admin@example.test', '$2y$10$8dMmK4gmqvrJVGcUnqlZE.LpKd8W1IpfG/zQ/yPDyGgIBsMyvhPHq', 'admin', NULL, NOW()),
  (2, 'HR User', 'hr@example.test', '$2y$10$aZ77yRRmDM2JHCpIxSvAC.1AwO8AxSeD8xg5EFnGcfgmucANQacI6', 'hr', NULL, NOW()),
  (3, 'Employee User', 'employee@example.test', '$2y$10$4q3iCaxTB65nw/ixCZI2ouYTHYazxZ5BzCkzRC0m07TTfDMQPRtsG', 'employee', 1, NOW());

-- Passwords (all): Admin@123, Hr@123, Emp@123 (hashes must be generated with password_hash)

-- Insert employees
INSERT INTO employees (id, name, phone, email, base_salary, joined_at, notes, created_at) VALUES
  (1, 'Alice Rahman', '01711111111', 'alice@company.com', 50000.00, '2023-01-10', 'Senior Sales', NOW()),
  (2, 'Bob Karim', '01722222222', 'bob@company.com', 40000.00, '2023-03-15', 'Support', NOW());

-- Insert users (passwords: Admin@123, Hr@123, Emp@123)
INSERT INTO users (id, name, email, password_hash, role, employee_id, created_at) VALUES
  (1, 'Admin User', 'admin@example.test', '$2y$10$8dMmK4gmqvrJVGcUnqlZE.LpKd8W1IpfG/zQ/yPDyGgIBsMyvhPHq', 'admin', NULL, NOW()),
  (2, 'HR User', 'hr@example.test', '$2y$10$aZ77yRRmDM2JHCpIxSvAC.1AwO8AxSeD8xg5EFnGcfgmucANQacI6', 'hr', NULL, NOW()),
  (3, 'Employee User', 'employee@example.test', '$2y$10$4q3iCaxTB65nw/ixCZI2ouYTHYazxZ5BzCkzRC0m07TTfDMQPRtsG', 'employee', 1, NOW());

-- Insert clients
INSERT INTO clients (id, name, phone, notes, created_at) VALUES
  (1, 'Acme Corp', '01888888888', 'VIP client', NOW()),
  (2, 'Beta Ltd', '01999999999', '', NOW());

-- Insert expense items
INSERT INTO expense_items (id, name, created_at) VALUES
  (1, 'Travel', NOW()),
  (2, 'Stationery', NOW());

-- Insert transactions (current month: adjust YYYY-MM as needed)
INSERT INTO transactions (id, employee_id, client_id, type, amount, description, status, month_year, settle_date, created_by_user_id, created_at, updated_at, changed_by_user_id, changed_at) VALUES
  (1, 1, 1, 'received', 100000.00, 'Project payment', 'completed', '2025-09', NULL, 1, NOW(), NOW(), NULL, NULL),
  (2, 1, 2, 'pending', 50000.00, 'Consulting fee', 'pending', '2025-09', '2025-09-30', 2, NOW(), NOW(), NULL, NULL),
  (3, 1, NULL, 'expense', 2000.00, 'Travel to client', 'completed', '2025-09', NULL, 2, NOW(), NOW(), NULL, NULL);

-- Insert expenses (linked to transaction 3)
INSERT INTO expenses (id, expense_item_id, employee_id, amount, is_salary_deduction, transaction_id, description, created_by, created_at) VALUES
  (1, 1, 1, 2000.00, 1, 3, 'Travel to client', 2, NOW());

-- Insert salary tracker for employee 1
INSERT INTO salary_tracker (id, employee_id, month_year, base_salary, deducted, final_salary, created_at) VALUES
  (1, 1, '2025-09', 50000.00, 2000.00, 48000.00, NOW());

-- Insert month closing (example)
INSERT INTO month_closings (id, month_year, closed_by_user_id, closed_at, notes) VALUES
  (1, '2025-08', 1, '2025-08-31 23:59:59', 'Closed previous month');

-- Insert activity log (example)
INSERT INTO activity_logs (id, user_id, model_name, model_id, action, changes, created_at) VALUES
  (1, 1, 'transaction', 1, 'create', NULL, NOW());

-- Notes:
-- Password hashes above are placeholders. After first login, change all default passwords.
