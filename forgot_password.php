<?php
$pageTitle = "Forgot Password - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    redirectUserByRole($user['role'] ?? 'customer');
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-5 bg-light min-vh-75 d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        
        <div class="card card-custom p-4 p-md-5 shadow-lg border-0" data-aos="fade-up">
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle p-3 mb-3 animate__animated animate__pulse animate__infinite" style="width: 70px; height: 70px; background-color: var(--primary-50);">
              <i class="bi bi-shield-lock-fill fs-1 text-primary" style="color: var(--primary-500) !important;"></i>
            </div>
            <h3 class="fw-bold mb-1">Forgot Password</h3>
            <p class="text-muted small">Enter your registered email address or phone number below. We will verify your account and send a 6-digit verification OTP code.</p>
          </div>

          <?php displayAlerts(); ?>

          <form action="send_otp.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-4">
              <label for="identity" class="form-label fw-600 text-dark">Registered Email or Phone Number</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" id="identity-icon"><i class="bi bi-envelope-fill text-muted"></i></span>
                <input type="text" name="identity" id="identity" class="form-control border-start-0 ps-0" placeholder="e.g. name@domain.com or +91 XXXXX XXXXX" required autofocus>
              </div>
              <div class="form-text text-muted mt-2">
                <i class="bi bi-info-circle me-1"></i> Both Customer and Vendor records will be verified.
              </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background-color: var(--primary-500); border-color: var(--primary-500); color: white; border-radius: var(--radius-md);">
              <span>Send Verification OTP</span>
              <i class="bi bi-arrow-right-short ms-2 fs-5"></i>
            </button>
          </form>

          <hr class="my-4">

          <div class="d-flex justify-content-between align-items-center small">
            <a href="login.php" class="text-decoration-none text-primary fw-600"><i class="bi bi-arrow-left me-1"></i> Customer Login</a>
            <span class="text-muted">|</span>
            <a href="vendor_login.php" class="text-decoration-none text-primary fw-600"><i class="bi bi-store me-1"></i> Vendor Portal</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const identityInput = document.getElementById('identity');
  const identityIcon = document.getElementById('identity-icon');
  
  if (identityInput && identityIcon) {
    identityInput.addEventListener('input', function() {
      const val = this.value.trim();
      // If it contains only numbers, spaces, plus, dashes, it is likely a phone number
      if (/^[0-9\s\-\+]+$/.test(val) && val.length > 3) {
        identityIcon.innerHTML = '<i class="bi bi-telephone-fill text-muted"></i>';
      } else {
        identityIcon.innerHTML = '<i class="bi bi-envelope-fill text-muted"></i>';
      }
    });
  }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
