<?php
$pageTitle = "Forgot Password & OTP Login - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/mail.php';

$message = '';
$error = '';
$demoOtpBanner = '';

// Always start fresh at Step 1 (Enter Details) whenever user navigates or taps "Forgot Password"
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['step'])) {
    unset($_SESSION['reset_otp']);
}

// Check current step in OTP flow
$otpData = $_SESSION['reset_otp'] ?? null;

// Determine active step
$step = 1;
if ($otpData && !empty($otpData['otp'])) {
    if (!empty($otpData['verified'])) {
        $step = 3; // Options screen (Reset Password or Login Anyway)
    } else {
        $step = 2; // Verify OTP step
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'request_otp') {
        $identifier = trim($_POST['identifier'] ?? '');
        if (empty($identifier)) {
            $error = "Please enter your registered Email ID or Phone Number.";
        } else {
            $db = getDBConnection();
            $cleanId = preg_replace('/[^0-9a-zA-Z@.]/', '', $identifier);
            $digitsOnly = preg_replace('/[^0-9]/', '', $identifier);
            
            // Search in users table first (handles customers & registered vendors)
            $stmt = $db->prepare("
                SELECT * FROM users 
                WHERE LOWER(email) = LOWER(?) 
                   OR phone = ? 
                   OR REPLACE(phone, ' ', '') = ? 
                   OR REPLACE(phone, '-', '') = ?
                   OR REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+91', '') = ?
                   OR REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+91', '') = ?
            ");
            $stmt->execute([$identifier, $identifier, $cleanId, $cleanId, $cleanId, $digitsOnly]);
            $user = $stmt->fetch();

            if (!$user) {
                // Search in vendors table
                $vStmt = $db->prepare("
                    SELECT v.*, u.id as u_id, u.name as u_name, u.role as u_role 
                    FROM vendors v 
                    LEFT JOIN users u ON v.user_id = u.id 
                    WHERE LOWER(v.email) = LOWER(?) 
                       OR v.phone = ? 
                       OR REPLACE(v.phone, ' ', '') = ?
                       OR REPLACE(REPLACE(v.phone, ' ', ''), '+91', '') = ?
                ");
                $vStmt->execute([$identifier, $identifier, $cleanId, $digitsOnly]);
                $vendor = $vStmt->fetch();

                if ($vendor) {
                    if (!empty($vendor['u_id'])) {
                        $uStmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                        $uStmt->execute([$vendor['u_id']]);
                        $user = $uStmt->fetch();
                    } else {
                        // Create user structure for vendor
                        $user = [
                            'id' => $vendor['id'],
                            'name' => $vendor['owner_name'] ?? $vendor['store_name'],
                            'email' => $vendor['email'],
                            'phone' => $vendor['phone'],
                            'role' => 'vendor',
                            'status' => 'ACTIVE'
                        ];
                    }
                }
            } else {
                if ($user['role'] === 'vendor') {
                    $vStmt = $db->prepare("SELECT * FROM vendors WHERE user_id = ? OR LOWER(email) = LOWER(?)");
                    $vStmt->execute([$user['id'], $user['email']]);
                    $vendor = $vStmt->fetch();
                }
            }

            // If user not found in database, automatically create a customer account so any user can receive OTP
            if (!$user) {
                $isEmailInput = (strpos($identifier, '@') !== false);
                $userName = $isEmailInput ? ucfirst(explode('@', $identifier)[0]) : 'User ' . substr($digitsOnly, -4);
                $userEmail = $isEmailInput ? strtolower($identifier) : $digitsOnly . '@vyaparsetu.com';
                $userPhone = $isEmailInput ? '' : $identifier;
                $defaultPass = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);

                $insStmt = $db->prepare("INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES (?, ?, ?, ?, 'customer', 'ACTIVE', 1)");
                $insStmt->execute([$userName, $userEmail, $userPhone, $defaultPass]);
                $newId = $db->lastInsertId();

                $user = [
                    'id' => $newId,
                    'name' => $userName,
                    'email' => $userEmail,
                    'phone' => $userPhone,
                    'role' => 'customer',
                    'status' => 'ACTIVE'
                ];
            }

            if ($user) {
                if (($user['status'] ?? 'ACTIVE') !== 'ACTIVE') {
                    $error = "Your account is currently " . strtolower($user['status']) . ". Please contact support.";
                } else {
                    // Generate cryptographically random 6-digit OTP
                    $otp = sprintf("%06d", random_int(100000, 999999));
                    
                    $_SESSION['reset_otp'] = [
                        'otp' => $otp,
                        'user_id' => $user['id'],
                        'user_name' => $user['name'],
                        'user_email' => $user['email'],
                        'user_phone' => $user['phone'] ?? '',
                        'role' => $user['role'] ?? ($vendor ? 'vendor' : 'customer'),
                        'vendor_id' => $vendor['id'] ?? null,
                        'target_input' => $identifier,
                        'target_type' => (strpos($identifier, '@') !== false) ? 'email' : 'phone',
                        'expires_at' => time() + 600, // 10 minutes validity
                        'verified' => false
                    ];

                    // Dispatch actual OTP directly to the exact user input (Email or Phone)
                    if (strpos($identifier, '@') !== false) {
                        // User entered an Email ID -> Dispatch directly to entered Email
                        sendEmail(
                            $identifier,
                            "Your OTP Verification Code - Vyapar Setu",
                            "Hello " . $user['name'] . ",\n\nYour 6-digit random OTP for verification is: " . $otp . "\nThis code is valid for 10 minutes."
                        );
                        if (!empty($user['phone'])) {
                            sendSMS($user['phone'], "Vyapar Setu Verification OTP: " . $otp . " (Valid for 10 min)");
                        }
                    } else {
                        // User entered a Phone Number -> Dispatch directly to entered Phone Number
                        sendSMS(
                            $identifier,
                            "Vyapar Setu Verification OTP: " . $otp . " (Valid for 10 min)"
                        );
                        if (!empty($user['email'])) {
                            sendEmail(
                                $user['email'],
                                "Your OTP Verification Code - Vyapar Setu",
                                "Hello " . $user['name'] . ",\n\nYour 6-digit random OTP for verification is: " . $otp . "\nThis code is valid for 10 minutes."
                            );
                        }
                    }

                    $message = "A 6-digit random OTP code has been dispatched to " . htmlspecialchars($identifier) . ". Please check your inbox/SMS. (Local XAMPP logs are saved in logs/mail.log & logs/sms.log).";
                    $step = 2;
                    $otpData = $_SESSION['reset_otp'];
                }
            } else {
                $error = "No Customer or Vendor account found matching '" . htmlspecialchars($identifier) . "'.";
            }
        }
    } elseif ($action === 'verify_otp') {
        $enteredOtp = trim($_POST['otp_code'] ?? '');
        
        if (!$otpData) {
            $error = "Session expired. Please request a new OTP.";
            $step = 1;
        } elseif (time() > $otpData['expires_at']) {
            $error = "The OTP code has expired. Please request a new code.";
            unset($_SESSION['reset_otp']);
            $otpData = null;
            $step = 1;
        } elseif ($enteredOtp !== $otpData['otp']) {
            $error = "Invalid OTP code entered. Please check and try again.";
            $step = 2;
        } else {
            // OTP Verified!
            $_SESSION['reset_otp']['verified'] = true;
            $otpData['verified'] = true;
            $message = "OTP verified successfully! Please select an option below.";
            $step = 3;
        }
    } elseif ($action === 'reset_password') {
        if (!$otpData || empty($otpData['verified'])) {
            $error = "Unauthorized action. Please verify your OTP first.";
            $step = 1;
        } else {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($newPassword) || strlen($newPassword) < 6) {
                $error = "Password must be at least 6 characters long.";
                $step = 3;
            } elseif ($newPassword !== $confirmPassword) {
                $error = "New password and confirm password do not match.";
                $step = 3;
            } else {
                $db = getDBConnection();
                $passHash = password_hash($newPassword, PASSWORD_BCRYPT);
                
                $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$passHash, $otpData['user_id']]);

                // Authenticate user session
                $_SESSION['user_id'] = $otpData['user_id'];
                $_SESSION['user_name'] = $otpData['user_name'];
                $_SESSION['user_email'] = $otpData['user_email'];
                $_SESSION['user_role'] = $otpData['role'];
                $_SESSION['role'] = $otpData['role'];

                if ($otpData['role'] === 'vendor' && !empty($otpData['vendor_id'])) {
                    $_SESSION['vendor_id'] = $otpData['vendor_id'];
                }

                // Log activity
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $lStmt = $db->prepare("INSERT INTO activity_logs (user_id, action_description, ip_address) VALUES (?, ?, ?)");
                $lStmt->execute([$otpData['user_id'], 'Password reset via OTP verification.', $ip]);

                unset($_SESSION['reset_otp']);
                setFlash('success', 'Your password has been updated successfully! Welcome back, ' . htmlspecialchars($otpData['user_name']) . '.');

                if ($otpData['role'] === 'vendor') {
                    header('Location: vendor_dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            }
        }
    } elseif ($action === 'login_anyway') {
        if (!$otpData || empty($otpData['verified'])) {
            $error = "Unauthorized action. Please verify your OTP first.";
            $step = 1;
        } else {
            // Log in user directly without modifying password
            $_SESSION['user_id'] = $otpData['user_id'];
            $_SESSION['user_name'] = $otpData['user_name'];
            $_SESSION['user_email'] = $otpData['user_email'];
            $_SESSION['user_role'] = $otpData['role'];
            $_SESSION['role'] = $otpData['role'];

            if ($otpData['role'] === 'vendor' && !empty($otpData['vendor_id'])) {
                $_SESSION['vendor_id'] = $otpData['vendor_id'];
            }

            $db = getDBConnection();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $lStmt = $db->prepare("INSERT INTO activity_logs (user_id, action_description, ip_address) VALUES (?, ?, ?)");
            $lStmt->execute([$otpData['user_id'], 'Direct login via OTP authentication.', $ip]);

            unset($_SESSION['reset_otp']);
            setFlash('success', 'OTP Login successful! Welcome back, ' . htmlspecialchars($otpData['user_name']) . '.');

            if ($otpData['role'] === 'vendor') {
                header('Location: vendor_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    } elseif ($action === 'reset_flow') {
        unset($_SESSION['reset_otp']);
        header('Location: forgot-password.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-5 bg-light min-vh-75">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        
        <!-- Step Indicators -->
        <div class="d-flex justify-content-between mb-4 position-relative">
          <div class="progress position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 3px; z-index: 1;">
            <div class="progress-bar bg-primary" style="width: <?= $step === 1 ? '0%' : ($step === 2 ? '50%' : '100%') ?>;"></div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold <?= $step >= 1 ? 'bg-primary' : 'bg-secondary' ?>" style="width: 38px; height: 38px;">1</div>
            <div class="small fw-600 mt-1">Enter Details</div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold <?= $step >= 2 ? 'bg-primary' : 'bg-secondary' ?>" style="width: 38px; height: 38px;">2</div>
            <div class="small fw-600 mt-1">Verify OTP</div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold <?= $step === 3 ? 'bg-primary' : 'bg-secondary' ?>" style="width: 38px; height: 38px;">3</div>
            <div class="small fw-600 mt-1">Reset / Login</div>
          </div>
        </div>

        <div class="card card-custom p-4 p-md-5 shadow-sm border-0">
          
          <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i><?= sanitize($message) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>



          <!-- STEP 1: REQUEST OTP -->
          <?php if ($step === 1): ?>
            <div class="text-center mb-4">
              <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle p-3 mb-2" style="width: 65px; height: 65px;">
                <i class="bi bi-shield-lock fs-2"></i>
              </div>
              <h4 class="fw-bold mt-2">Forgot Password & OTP Verification</h4>
              <p class="text-muted small">Enter your <strong>Phone Number</strong> or <strong>Email Address</strong> below. A new random 6-digit OTP code will be sent via SMS/Email to verify your identity.</p>
            </div>

            <form action="forgot-password.php" method="POST">
              <input type="hidden" name="action" value="request_otp">
              
              <div class="mb-4">
                <label for="identifier" class="form-label fw-600">Phone Number or Email Address</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-white"><i class="bi bi-person-vcard text-primary"></i></span>
                  <input type="text" name="identifier" id="identifier" class="form-control fs-6" placeholder="Enter Phone Number (e.g. 9876543210) or Email Address" required value="<?= sanitize($_POST['identifier'] ?? '') ?>">
                </div>
                <div class="form-text text-muted mt-2">
                  <i class="bi bi-info-circle me-1"></i> A random 6-digit OTP will be dispatched immediately to your Email and Mobile SMS.
                </div>
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-600 fs-6 shadow-sm">
                <i class="bi bi-send-fill me-2"></i> Get Verification OTP
              </button>
            </form>

            <div class="text-center small mt-4 pt-2 border-top">
              <a href="login.php" class="text-decoration-none text-muted me-3"><i class="bi bi-arrow-left me-1"></i> Customer Login</a>
              <span class="text-muted">•</span>
              <a href="vendor_login.php" class="text-decoration-none text-muted ms-3"><i class="bi bi-store me-1"></i> Vendor Login</a>
            </div>

          <!-- STEP 2: VERIFY OTP -->
          <?php elseif ($step === 2): ?>
            <div class="text-center mb-4">
              <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle p-3 mb-2" style="width: 65px; height: 65px;">
                <i class="bi bi-phone-vibrate fs-2"></i>
              </div>
              <h4 class="fw-bold mt-2">Verify OTP Code</h4>
              <p class="text-muted small mb-0">Please check your Email inbox or Mobile SMS messages and enter the 6-digit OTP code sent to:</p>
              <span class="badge bg-primary fs-6 mt-2 px-3 py-2"><i class="bi bi-envelope-at me-1"></i> <?= htmlspecialchars($otpData['target_input'] ?? '') ?></span>
            </div>

            <form action="forgot-password.php" method="POST">
              <input type="hidden" name="action" value="verify_otp">

              <div class="mb-4 text-center">
                <label for="otp_code" class="form-label fw-500 mb-2">6-Digit OTP Code</label>
                <input type="text" name="otp_code" id="otp_code" class="form-control text-center font-monospace fs-3 tracking-widest" placeholder="123456" maxlength="6" pattern="[0-9]{6}" required autofocus style="letter-spacing: 0.5rem; max-width: 240px; margin: 0 auto;">
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-600 fs-6 shadow-sm mb-3">
                <i class="bi bi-check-circle-fill me-2"></i> Verify OTP Code
              </button>
            </form>

            <form action="forgot-password.php" method="POST" class="text-center">
              <input type="hidden" name="action" value="reset_flow">
              <button type="submit" class="btn btn-link text-decoration-none text-muted small">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Try standard or different Email / Phone
              </button>
            </form>

          <!-- STEP 3: OPTIONS SCREEN (RESET PASSWORD OR LOGIN ANYWAY) -->
          <?php elseif ($step === 3): ?>
            <div class="text-center mb-4">
              <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle p-3 mb-2" style="width: 65px; height: 65px;">
                <i class="bi bi-shield-check fs-1 text-success"></i>
              </div>
              <h4 class="fw-bold mt-2">OTP Verified Successfully!</h4>
              <p class="text-muted small">Welcome, <strong><?= htmlspecialchars($otpData['user_name'] ?? 'User') ?></strong> (<?= ucfirst($otpData['role'] ?? 'user') ?> account). Choose how you want to proceed:</p>
            </div>

            <div class="accordion accordion-flush mb-4" id="otpOptionsAccordion">
              
              <!-- OPTION 1: RESET PASSWORD -->
              <div class="card border rounded-3 mb-3 shadow-2xs overflow-hidden">
                <div class="card-header bg-white p-3 border-0">
                  <h5 class="mb-0">
                    <button class="btn btn-link text-decoration-none text-dark fw-bold w-100 text-start d-flex justify-content-between align-items-center p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReset">
                      <span><i class="bi bi-key-fill text-primary me-2"></i> Option 1: Reset Your Password</span>
                      <i class="bi bi-chevron-down text-muted"></i>
                    </button>
                  </h5>
                </div>
                <div id="collapseReset" class="collapse show" data-bs-parent="#otpOptionsAccordion">
                  <div class="card-body bg-light border-top">
                    <form action="forgot-password.php" method="POST">
                      <input type="hidden" name="action" value="reset_password">

                      <div class="mb-3">
                        <label for="new_password" class="form-label fw-500">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Minimum 6 characters" minlength="6" required>
                      </div>

                      <div class="mb-3">
                        <label for="confirm_password" class="form-label fw-500">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter new password" minlength="6" required>
                      </div>

                      <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-600">
                        <i class="bi bi-check-lg me-1"></i> Save New Password & Login
                      </button>
                    </form>
                  </div>
                </div>
              </div>

              <!-- OPTION 2: LOGIN ANYWAY WITHOUT RESET -->
              <div class="card border border-success-subtle bg-success-subtle rounded-3 shadow-2xs overflow-hidden">
                <div class="card-body p-4 text-center">
                  <div class="fw-bold fs-5 text-success mb-1">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Option 2: Login Anyway
                  </div>
                  <p class="text-muted small mb-3">Skip password reset and sign directly into your account dashboard right now.</p>
                  
                  <form action="forgot-password.php" method="POST">
                    <input type="hidden" name="action" value="login_anyway">
                    <button type="submit" class="btn btn-success w-100 py-2.5 fw-600 shadow-sm">
                      <i class="bi bi-arrow-right-circle-fill me-2"></i> Login Immediately (Skip Password Reset)
                    </button>
                  </form>
                </div>
              </div>

            </div>

            <form action="forgot-password.php" method="POST" class="text-center border-top pt-3">
              <input type="hidden" name="action" value="reset_flow">
              <button type="submit" class="btn btn-link text-muted small text-decoration-none">
                <i class="bi bi-x-circle me-1"></i> Cancel & Return to Login
              </button>
            </form>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

