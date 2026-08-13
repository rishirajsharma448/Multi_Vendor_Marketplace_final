<?php
$pageTitle = "Verify Email - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<main class="py-5 bg-light">
  <div class="container text-center">
    <div class="card card-custom p-5 mx-auto max-w-500px">
      <i class="bi bi-patch-check-fill text-success display-3 mb-3"></i>
      <h4 class="fw-bold">Email Verification</h4>
      <p class="text-muted">Your email address has been verified successfully!</p>
      <a href="login.php" class="btn btn-primary-custom px-4">Continue to Login</a>
    </div>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
