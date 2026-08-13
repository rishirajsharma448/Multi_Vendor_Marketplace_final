<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';

logoutUser();
setFlash('info', 'You have been successfully logged out.');
header('Location: ' . APP_URL . '/login.php');
exit;
