<?php
$pageTitle = "Verify OTP - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['reset_email'])) {
    header('Location: ' . APP_URL . '/forgot_password.php');
    exit;
}

$error = '';
$email = $_SESSION['reset_email'];
$role = $_SESSION['reset_role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = trim($_POST['otp_code'] ?? '');

    if (empty($enteredOtp) || strlen($enteredOtp) !== 6 || !ctype_digit($enteredOtp)) {
        $error = "Please enter a valid 6-digit OTP code.";
    } else {
        $db = getDBConnection();
        $dbOtp = null;
        $dbExpiry = null;

        // Try checking users table first
        if ($role === 'customer' || $role === 'both') {
            $stmt = $db->prepare("SELECT otp, otp_expiry FROM users WHERE LOWER(email) = LOWER(?) AND role = 'customer'");
            $stmt->execute([$email]);
            $res = $stmt->fetch();
            if ($res && $res['otp'] !== null) {
                $dbOtp = $res['otp'];
                $dbExpiry = $res['otp_expiry'];
            }
        }

        // Check vendors table if no match or if vendor
        if (($role === 'vendor' || $role === 'both') && ($dbOtp === null || $dbOtp !== $enteredOtp)) {
            $stmt = $db->prepare("SELECT otp, otp_expiry FROM vendors WHERE LOWER(email) = LOWER(?)");
            $stmt->execute([$email]);
            $res = $stmt->fetch();
            if ($res && $res['otp'] !== null) {
                $dbOtp = $res['otp'];
                $dbExpiry = $res['otp_expiry'];
            }
        }

        if ($dbOtp !== null && $dbOtp === $enteredOtp) {
            $expiryTime = strtotime($dbExpiry);
            if ($expiryTime >= time()) {
                $_SESSION['otp_verified'] = true;
                setFlash('success', 'OTP verified successfully! Please enter your new password.');
                header('Location: ' . APP_URL . '/reset_password.php');
                exit;
            } else {
                $error = "The OTP code has expired. Please request a new code.";
            }
        } else {
            $error = "Invalid OTP code. Please check and try again.";
        }
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-5 bg-light min-vh-75 d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        
        <!-- Step Indicators -->
        <div class="d-flex justify-content-between mb-4 position-relative" style="max-width: 320px; margin: 0 auto;">
          <div class="progress position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 3px; z-index: 1;">
            <div class="progress-bar" style="width: 50%; background-color: var(--primary-500);"></div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 34px; height: 34px; background-color: var(--primary-500);">1</div>
            <div class="small fw-600 mt-1" style="font-size: 0.75rem;">Request</div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 34px; height: 34px; background-color: var(--primary-500);">2</div>
            <div class="small fw-600 mt-1" style="font-size: 0.75rem;">Verify</div>
          </div>
          
          <div class="position-relative text-center" style="z-index: 2;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold bg-secondary" style="width: 34px; height: 34px;">3</div>
            <div class="small fw-600 mt-1 text-muted" style="font-size: 0.75rem;">Reset</div>
          </div>
        </div>

        <div class="card card-custom p-4 p-md-5 shadow-lg border-0" data-aos="fade-up">
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle p-3 mb-3" style="width: 70px; height: 70px; background-color: var(--primary-50);">
              <i class="bi bi-shield-check-fill fs-1 text-primary" style="color: var(--primary-500) !important;"></i>
            </div>
            <h3 class="fw-bold mb-1">Verify OTP</h3>
            <p class="text-muted small">Enter the 6-digit OTP code sent to your registered email address:</p>
            <div class="badge px-3 py-2 fs-6 mb-2" style="background-color: var(--primary-50); color: var(--primary-700); font-family: monospace; border: 1px solid var(--primary-100);">
              <?= htmlspecialchars($email) ?>
            </div>
          </div>

          <?php displayAlerts(); ?>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
              <div><?= htmlspecialchars($error) ?></div>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="verify_otp.php" method="POST">
            <div class="mb-4 text-center">
              <label for="otp_code" class="form-label fw-600 mb-2">6-Digit OTP Code</label>
              <input type="text" name="otp_code" id="otp_code" class="form-control text-center font-monospace fs-3 tracking-widest" placeholder="••••••" maxlength="6" pattern="[0-9]{6}" required autofocus style="letter-spacing: 0.6rem; max-width: 240px; margin: 0 auto; border-radius: var(--radius-md);">
              <div class="form-text text-muted mt-2">
                <i class="bi bi-clock me-1"></i> Valid for 10 minutes. Check your mail inbox and spam folder.
              </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background-color: var(--primary-500); border-color: var(--primary-500); color: white; border-radius: var(--radius-md);">
              <span>Verify Code</span>
              <i class="bi bi-shield-check ms-2 fs-5"></i>
            </button>
          </form>

          <hr class="my-4">

          <div class="text-center">
            <a href="forgot_password.php" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Request a new OTP</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
