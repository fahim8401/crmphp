-- CRM Database Schema (MySQL 5.7+)
-- Default charset: utf8mb4
-- Foreign keys are optional for cPanel compatibility

SET NAMES utf8mb4;
SET time_zone = '+06:00';

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','hr','employee') NOT NULL,
  employee_id INT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_role (role),
  INDEX idx_employee_id (employee_id)
  -- FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
);

CREATE TABLE employees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(150) NULL,
  base_salary DECIMAL(12,2) NOT NULL,
  joined_at DATE NOT NULL,
  notes TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  notes TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE expense_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NULL,
  client_id INT UNSIGNED NULL,
  type ENUM('received','pending','expense') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  description TEXT,
  status ENUM('pending','completed') NOT NULL DEFAULT 'pending',
  month_year VARCHAR(7) NOT NULL,
  settle_date DATE NULL, -- Date to remind for pending collection
  created_by_user_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  changed_by_user_id INT UNSIGNED NULL,
  changed_at DATETIME NULL,
  INDEX idx_month_year (month_year),
  INDEX idx_employee_id (employee_id),
  INDEX idx_client_id (client_id)
  -- FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL,
  -- FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
  -- FOREIGN KEY (created_by_user_id) REFERENCES users(id),
  -- FOREIGN KEY (changed_by_user_id) REFERENCES users(id)
);

CREATE TABLE expenses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  expense_item_id INT UNSIGNED NOT NULL,
  employee_id INT UNSIGNED NULL,
  amount DECIMAL(12,2) NOT NULL,
  is_salary_deduction TINYINT(1) NOT NULL DEFAULT 0,
  transaction_id INT UNSIGNED NULL,
  description TEXT,
  created_by INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_expense_item_id (expense_item_id),
  INDEX idx_employee_id (employee_id)
  -- FOREIGN KEY (expense_item_id) REFERENCES expense_items(id),
  -- FOREIGN KEY (employee_id) REFERENCES employees(id),
  -- FOREIGN KEY (transaction_id) REFERENCES transactions(id),
  -- FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE salary_tracker (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  month_year VARCHAR(7) NOT NULL,
  base_salary DECIMAL(12,2) NOT NULL,
  deducted DECIMAL(12,2) NOT NULL DEFAULT 0,
  final_salary DECIMAL(12,2) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_employee_month (employee_id, month_year),
  INDEX idx_employee_id (employee_id),
  INDEX idx_month_year (month_year)
  -- FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE month_closings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  month_year VARCHAR(7) NOT NULL,
  closed_by_user_id INT UNSIGNED NOT NULL,
  closed_at DATETIME NOT NULL,
  notes TEXT,
  UNIQUE KEY uniq_month_year (month_year)
  -- FOREIGN KEY (closed_by_user_id) REFERENCES users(id)
);

CREATE TABLE activity_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  model_name VARCHAR(50) NOT NULL,
  model_id INT UNSIGNED NOT NULL,
  action VARCHAR(20) NOT NULL,
  changes JSON NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_id (user_id),
  INDEX idx_model_name (model_name),
  INDEX idx_model_id (model_id)
  -- FOREIGN KEY (user_id) REFERENCES users(id)
);

-- End of schema.sql
