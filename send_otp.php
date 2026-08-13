<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mail.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . APP_URL . '/forgot_password.php');
    exit;
}

$identity = trim($_POST['identity'] ?? '');

if (empty($identity)) {
    setFlash('danger', 'Please enter your registered email address or phone number.');
    header('Location: ' . APP_URL . '/forgot_password.php');
    exit;
}

$db = getDBConnection();
$email = '';
$phone = '';
$is_customer = false;
$is_vendor = false;

if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
    $email = strtolower($identity);
    
    // Check if customer exists in users table (role = 'customer')
    $custStmt = $db->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?) AND role = 'customer'");
    $custStmt->execute([$email]);
    if ($custStmt->fetch()) {
        $is_customer = true;
    }
    
    // Check if vendor exists in vendors table
    $vendStmt = $db->prepare("SELECT id FROM vendors WHERE LOWER(email) = LOWER(?)");
    $vendStmt->execute([$email]);
    if ($vendStmt->fetch()) {
        $is_vendor = true;
    }
} else {
    // Clean phone input (remove spaces, plus, dashes)
    $cleanPhone = preg_replace('/\s+|-|\+91|\+/', '', $identity);
    if (strlen($cleanPhone) >= 10) {
        // Query users by phone
        $custStmt = $db->prepare("SELECT id, email, phone FROM users WHERE (phone LIKE ? OR REPLACE(phone, ' ', '') LIKE ?) AND role = 'customer' LIMIT 1");
        $custStmt->execute(["%$cleanPhone%", "%$cleanPhone%"]);
        $cRow = $custStmt->fetch();
        if ($cRow) {
            $is_customer = true;
            $email = $cRow['email'];
            $phone = $cRow['phone'];
        }
        
        // Query vendors by phone
        $vendStmt = $db->prepare("SELECT id, email, phone FROM vendors WHERE (phone LIKE ? OR REPLACE(phone, ' ', '') LIKE ?) LIMIT 1");
        $vendStmt->execute(["%$cleanPhone%", "%$cleanPhone%"]);
        $vRow = $vendStmt->fetch();
        if ($vRow) {
            $is_vendor = true;
            if (empty($email)) {
                $email = $vRow['email'];
                $phone = $vRow['phone'];
            }
        }
    }
}

if (!$is_customer && !$is_vendor) {
    setFlash('danger', 'The email address or phone number ' . htmlspecialchars($identity) . ' is not registered in our system.');
    header('Location: ' . APP_URL . '/forgot_password.php');
    exit;
}

// Generate 6-digit OTP
$otp = random_int(100000, 999999);
$expiry = date('Y-m-d H:i:s', time() + 600); // 10 minutes from now

// Store OTP in Database
if ($is_customer) {
    $stmt = $db->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE LOWER(email) = LOWER(?) AND role = 'customer'");
    $stmt->execute([$otp, $expiry, $email]);
}

if ($is_vendor) {
    $stmt = $db->prepare("UPDATE vendors SET otp = ?, otp_expiry = ? WHERE LOWER(email) = LOWER(?)");
    $stmt->execute([$otp, $expiry, $email]);
}

// Store reset details in session for verify and reset phases
$_SESSION['reset_email'] = $email;
$_SESSION['reset_role'] = ($is_customer && $is_vendor) ? 'both' : ($is_customer ? 'customer' : 'vendor');
$_SESSION['otp_verified'] = false;

if (!empty($phone)) {
    // Send SMS OTP
    $message = "Your Vyapar Setu verification OTP code is: {$otp}. Valid for 10 minutes.";
    sendSMS($phone, $message);
    setFlash('success', 'A 6-digit OTP code has been sent via SMS to your phone number: ' . htmlspecialchars($phone) . '. Please verify it below.');
} else {
    // Compose and Send Email
    $subject = "Password Reset OTP Code - Vyapar Setu";
    $body = "Hello,\n\n";
    $body .= "We received a request to reset the password for your Vyapar Setu account.\n\n";
    $body .= "Your 6-digit OTP (One Time Password) is: {$otp}\n\n";
    $body .= "This code is valid for the next 10 minutes. If you did not make this request, please ignore this email.\n\n";
    $body .= "Best regards,\n";
    $body .= "The Vyapar Setu Team";
    
    sendEmail($email, $subject, $body);
    setFlash('success', 'A 6-digit OTP code has been sent to ' . htmlspecialchars($email) . '. Please verify it below.');
}

header('Location: ' . APP_URL . '/verify_otp.php');
exit;
