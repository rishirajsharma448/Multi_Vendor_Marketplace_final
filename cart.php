<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if (!isLoggedIn()) {
    setFlash('info', 'Please sign in to view your shopping cart.');
    header("Location: " . APP_URL . "/login.php");
    exit;
}

header("Location: " . APP_URL . "/customer/cart.php");
exit;
