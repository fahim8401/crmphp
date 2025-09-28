<?php
session_start();
if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = bin2hex(random_bytes(8));
    echo "Session started. Refresh to test persistence.";
} else {
    echo "Session persists! Value: " . htmlspecialchars($_SESSION['test']);
}
?>
