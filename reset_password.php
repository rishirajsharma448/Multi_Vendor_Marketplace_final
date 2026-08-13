<?php
$pageTitle = "Reset Password - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['reset_email']) || empty($_SESSION['otp_verified'])) {
    header('Location: ' . APP_URL . '/forgot_password.php');
    exit;
}

$error = '';
$email = $_SESSION['reset_email'];
$role = $_SESSION['reset_role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Confirm password does not match.";
    } else {
        $db = getDBConnection();
        $hash = password_hash($new_password, PASSWORD_BCRYPT);
        
        $db->beginTransaction();
        try {
            if ($role === 'customer' || $role === 'both') {
                $stmt = $db->prepare("UPDATE users SET password_hash = ?, otp = NULL, otp_expiry = NULL WHERE LOWER(email) = LOWER(?) AND role = 'customer'");
                $stmt->execute([$hash, $email]);
            }
            
            if ($role === 'vendor' || $role === 'both') {
                // Clear OTP from vendors table
                $stmt = $db->prepare("UPDATE vendors SET otp = NULL, otp_expiry = NULL WHERE LOWER(email) = LOWER(?)");
                $stmt->execute([$email]);
                
                // Update credentials in users table for the vendor
                $stmt = $db->prepare("UPDATE users SET password_hash = ?, otp = NULL, otp_expiry = NULL WHERE id = (SELECT user_id FROM vendors WHERE LOWER(email) = LOWER(?))");
                $stmt->execute([$hash, $email]);
            }
            
            $db->commit();
            
            // Clean up session
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_role']);
            unset($_SESSION['otp_verified']);
            
            setFlash('success', 'Your password has been updated successfully. Please log in with your new credentials.');
            header('Location: ' . APP_URL . '/login.php');
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $error = "An error occurred while updating the password: " . $e->getMessage();
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
            <div class="progress-bar" style="width: 100%; background-color: var(--primary-500);"></div>
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
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 34px; height: 34px; background-color: var(--primary-500);">3</div>
            <div class="small fw-600 mt-1 text-muted" style="font-size: 0.75rem;">Reset</div>
          </div>
        </div>

        <div class="card card-custom p-4 p-md-5 shadow-lg border-0" data-aos="fade-up">
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle p-3 mb-3" style="width: 70px; height: 70px; background-color: var(--primary-50);">
              <i class="bi bi-key-fill fs-1 text-primary" style="color: var(--primary-500) !important;"></i>
            </div>
            <h3 class="fw-bold mb-1">Set New Password</h3>
            <p class="text-muted small">Choose a strong, secure password for your account:</p>
            <div class="badge px-3 py-1.5" style="background-color: var(--primary-50); color: var(--primary-700); font-family: monospace;">
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

          <form action="reset_password.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="new_password" class="form-label fw-600 text-dark">New Password</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Minimum 6 characters" minlength="6" required autofocus>
              </div>
            </div>

            <div class="mb-4">
              <label for="confirm_password" class="form-label fw-600 text-dark">Confirm New Password</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter password" minlength="6" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background-color: var(--primary-500); border-color: var(--primary-500); color: white; border-radius: var(--radius-md);">
              <span>Update Password</span>
              <i class="bi bi-check-circle-fill ms-2 fs-5"></i>
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
