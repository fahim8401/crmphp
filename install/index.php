<?php
// install/index.php
// HPLink CRM Installer for cPanel/shared hosting

function check_extension($ext) {
    return extension_loaded($ext);
}

function check_writable($path) {
    return is_writable($path);
}

$step = $_POST['step'] ?? 1;
$error = '';
$success = '';
$requirements = [
    'pdo_mysql' => check_extension('pdo_mysql'),
    'mbstring' => check_extension('mbstring'),
    'json' => check_extension('json'),
    'fileinfo' => check_extension('fileinfo'),
    'storage/exports' => check_writable(__DIR__ . '/../storage/exports'),
    'storage/logs' => check_writable(__DIR__ . '/../storage/logs'),
];

if ($step == 2) {
    // Validate input
    $db_host = trim($_POST['db_host'] ?? '');
    $db_name = trim($_POST['db_name'] ?? '');
    $db_user = trim($_POST['db_user'] ?? '');
    $db_pass = $_POST['db_pass'] ?? '';
    $timezone = trim($_POST['timezone'] ?? 'Asia/Dhaka');
    $currency = trim($_POST['currency'] ?? '৳');
    if (!$db_host || !$db_name || !$db_user) {
        $error = "All fields are required.";
        $step = 1;
    } else {
        // Try DB connection
        try {
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (Exception $e) {
            $error = "DB connection failed: " . htmlspecialchars($e->getMessage());
            $step = 1;
        }
    }
    // Write config.php
    if (!$error) {
        $config = "<?php\nreturn [\n"
            . "    'db' => [\n"
            . "        'host' => '$db_host',\n"
            . "        'database' => '$db_name',\n"
            . "        'username' => '$db_user',\n"
            . "        'password' => '$db_pass',\n"
            . "        'charset' => 'utf8mb4',\n"
            . "    ],\n"
            . "    'timezone' => '$timezone',\n"
            . "    'currency_symbol' => '$currency',\n"
            . "    'default_month_format' => 'Y-m',\n"
            . "    'session_name' => 'crm_session',\n"
            . "    'csrf_token_name' => 'csrf_token',\n"
            . "    'export_path' => __DIR__ . '/../storage/exports/',\n"
            . "    'log_path' => __DIR__ . '/../storage/logs/',\n"
            . "];\n";
        if (@file_put_contents(__DIR__ . '/../config.php', $config)) {
            $success = "config.php created successfully.";
            $step = 3;
        } else {
            $error = "Failed to write config.php. Check permissions.";
            $step = 1;
        }
    }
}

if ($step == 3 && isset($_POST['import_sql'])) {
    // Import schema.sql and seed.sql
    $db_host = trim($_POST['db_host']);
    $db_name = trim($_POST['db_name']);
    $db_user = trim($_POST['db_user']);
    $db_pass = $_POST['db_pass'];
    try {
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        $pdo = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $seed = file_get_contents(__DIR__ . '/../database/seed.sql');
        $pdo->exec($schema);
        $pdo->exec($seed);
        $success = "Database imported successfully.";
        $step = 4;
    } catch (Exception $e) {
        $error = "SQL import failed: " . htmlspecialchars($e->getMessage());
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HPLink CRM Installer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow w-full max-w-lg">
    <h1 class="text-2xl font-bold mb-4">HPLink CRM Installer</h1>
    <?php if ($error): ?>
      <div class="mb-4 text-red-600"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="mb-4 text-green-700"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($step == 1): ?>
      <h2 class="text-lg font-semibold mb-2">Requirements Check</h2>
      <ul class="mb-4">
        <li>pdo_mysql: <span class="<?= $requirements['pdo_mysql'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['pdo_mysql'] ? 'OK' : 'Missing' ?></span></li>
        <li>mbstring: <span class="<?= $requirements['mbstring'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['mbstring'] ? 'OK' : 'Missing' ?></span></li>
        <li>json: <span class="<?= $requirements['json'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['json'] ? 'OK' : 'Missing' ?></span></li>
        <li>fileinfo: <span class="<?= $requirements['fileinfo'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['fileinfo'] ? 'OK' : 'Missing' ?></span></li>
        <li>storage/exports writable: <span class="<?= $requirements['storage/exports'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['storage/exports'] ? 'OK' : 'Not writable' ?></span></li>
        <li>storage/logs writable: <span class="<?= $requirements['storage/logs'] ? 'text-green-700' : 'text-red-600' ?>"><?= $requirements['storage/logs'] ? 'OK' : 'Not writable' ?></span></li>
      </ul>
      <?php if (in_array(false, $requirements, true)): ?>
        <div class="mb-4 text-red-600">Please fix the above issues before continuing.</div>
      <?php else: ?>
        <form method="post">
          <input type="hidden" name="step" value="2">
          <div class="mb-2">
            <label class="block font-semibold">DB Host</label>
            <input type="text" name="db_host" value="localhost" class="w-full border px-3 py-2 rounded" required>
          </div>
          <div class="mb-2">
            <label class="block font-semibold">DB Name</label>
            <input type="text" name="db_name" class="w-full border px-3 py-2 rounded" required>
          </div>
          <div class="mb-2">
            <label class="block font-semibold">DB User</label>
            <input type="text" name="db_user" class="w-full border px-3 py-2 rounded" required>
          </div>
          <div class="mb-2">
            <label class="block font-semibold">DB Password</label>
            <input type="password" name="db_pass" class="w-full border px-3 py-2 rounded">
          </div>
          <div class="mb-2">
            <label class="block font-semibold">Timezone</label>
            <input type="text" name="timezone" value="Asia/Dhaka" class="w-full border px-3 py-2 rounded">
          </div>
          <div class="mb-4">
            <label class="block font-semibold">Currency Symbol</label>
            <input type="text" name="currency" value="৳" class="w-full border px-3 py-2 rounded">
          </div>
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Save & Next</button>
        </form>
      <?php endif; ?>
    <?php elseif ($step == 3): ?>
      <form method="post">
        <input type="hidden" name="step" value="3">
        <input type="hidden" name="db_host" value="<?=e($_POST['db_host'])?>">
        <input type="hidden" name="db_name" value="<?=e($_POST['db_name'])?>">
        <input type="hidden" name="db_user" value="<?=e($_POST['db_user'])?>">
        <input type="hidden" name="db_pass" value="<?=e($_POST['db_pass'])?>">
        <button type="submit" name="import_sql" value="1" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Import Database (schema.sql & seed.sql)</button>
      </form>
      <div class="mt-4 text-yellow-700">After successful import, <b>delete the install folder</b> for security.</div>
    <?php elseif ($step == 4): ?>
      <div class="mb-4 text-green-700">
        <b>Installation complete!</b><br>
        You can now <a href="../index.php" class="text-blue-600 underline">login to HPLink CRM</a>.<br>
        <b>Default admin login:</b> admin@example.test / Admin@123<br>
        <b>Important:</b> Change the default admin password and delete the <code>install/</code> folder.
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
