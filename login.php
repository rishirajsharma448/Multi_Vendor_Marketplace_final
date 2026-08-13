<?php
$pageTitle = "Login - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    redirectUserByRole($user['role'] ?? 'customer');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Please provide both username/email address and password.";
    } else {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ? OR email LIKE ?");
        $stmt->execute([$email, $email, $email . '%']);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password_hash']) || $password === $user['password_hash'])) {
            if ($user['status'] !== 'ACTIVE') {
                $error = "Your account is currently " . strtolower($user['status']) . ". Please contact support.";
            } else {
                loginUser($user);
                setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
                redirectUserByRole($user['role']);
            }
        } else {
            $error = "Invalid email address or password.";
        }
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4 shadow-sm border-0">
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle p-3 mb-2" style="width: 60px; height: 60px;">
              <i class="bi bi-person-lock fs-2"></i>
            </div>
            <h4 class="fw-bold mb-1">Sign In to Vyapar Setu</h4>
            <p class="text-muted small">Access your Customer, Vendor, or Admin account</p>
          </div>

          <?php displayAlerts(); ?>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="login.php" method="POST" class="needs-validation" autocomplete="off" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label fw-500">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" value="<?= sanitize($_POST['email'] ?? '') ?>" autocomplete="off" required>
              </div>
            </div>

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-500 mb-0">Password</label>
                <a href="forgot_password.php" class="small text-primary text-decoration-none">Forgot password?</a>
              </div>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" autocomplete="new-password" required>
              </div>
            </div>

            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember">
              <label class="form-check-label small text-muted" for="remember">Remember me on this device</label>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-600">
              <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
            </button>
          </form>

          <hr class="my-4">

       

          <div class="text-center small">
            Don't have an account? <a href="register.php" class="fw-600 text-primary">Create account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
