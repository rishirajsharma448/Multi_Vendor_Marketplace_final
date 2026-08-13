<?php
$pageTitle = "Create Account - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

$roleParam = sanitize($_GET['role'] ?? 'customer');

if (isLoggedIn()) {
    $user = getCurrentUser();
    if ($user && $user['role'] === 'vendor') {
        header('Location: ' . APP_URL . '/vendor_dashboard.php');
        exit;
    } elseif ($roleParam !== 'vendor') {
        redirectUserByRole($user['role'] ?? 'customer');
    }
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'customer');
    
    // Vendor specific fields
    $store_name = sanitize($_POST['store_name'] ?? '');
    $gstin = sanitize($_POST['gstin'] ?? '');
    $category = sanitize($_POST['category'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $error = "Please fill in all required fields.";
    } elseif (!validateEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif (!validatePassword($password)) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif ($role === 'vendor' && empty($store_name)) {
        $error = "Store name is required for vendor registration.";
    } else {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "An account with this email address already exists.";
        } else {
            $db->beginTransaction();
            try {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $uStmt = $db->prepare("INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES (?, ?, ?, ?, ?, 'ACTIVE', 1)");
                $uStmt->execute([$name, $email, $phone, $password_hash, $role]);
                $userId = $db->lastInsertId();

                if ($role === 'vendor') {
                    $vendorCode = 'VND-' . rand(10000, 99999);
                    $vStmt = $db->prepare("INSERT INTO vendors (user_id, vendor_code, store_name, owner_name, email, phone, gstin, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'APPROVED')");
                    $vStmt->execute([$userId, $vendorCode, $store_name, $name, $email, $phone, $gstin, $category ?: 'General']);
                }

                $db->commit();

                // Auto login after registration
                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
                loginUser($user);

                setFlash('success', 'Registration successful! Welcome to Vyapar Setu.');
                redirectUserByRole($role);
            } catch (Exception $e) {
                $db->rollBack();
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4 shadow-sm border-0">
          <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Create Vyapar Setu Account</h4>
            <p class="text-muted small">Join thousands of shoppers and trusted vendors</p>
            
            <div class="btn-group w-100 my-2" role="group">
              <a href="register.php?role=customer" class="btn btn-outline-primary <?= $roleParam !== 'vendor' ? 'active' : '' ?>">Customer Registration</a>
              <a href="register.php?role=vendor" class="btn btn-outline-primary <?= $roleParam === 'vendor' ? 'active' : '' ?>">Vendor Registration</a>
            </div>
          </div>

          <?php displayAlerts(); ?>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i><?= sanitize($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="register.php?role=<?= $roleParam ?>" method="POST" autocomplete="off">
            <input type="hidden" name="role" value="<?= $roleParam === 'vendor' ? 'vendor' : 'customer' ?>">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-500">Full Name *</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="<?= sanitize($_POST['name'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-500">Phone Number *</label>
                <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" value="<?= sanitize($_POST['phone'] ?? '') ?>" required>
              </div>

              <div class="col-12">
                <label class="form-label fw-500">Email Address *</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?= sanitize($_POST['email'] ?? '') ?>" autocomplete="off" required>
              </div>

              <?php if ($roleParam === 'vendor'): ?>
                <div class="col-12 border-top pt-3 mt-3">
                  <h6 class="fw-bold text-primary mb-3"><i class="bi bi-shop me-1"></i> Store & Business Details</h6>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-500">Store Name *</label>
                  <input type="text" name="store_name" class="form-control" placeholder="e.g. Acme Superstore" value="<?= sanitize($_POST['store_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-500">Primary Category</label>
                  <select name="category" class="form-select">
                    <option value="Electronics">Electronics</option>
                    <option value="Fashion">Fashion & Apparel</option>
                    <option value="Spices & Organics">Spices & Organics</option>
                    <option value="Home & Living">Home & Living</option>
                    <option value="Beauty & Wellness">Beauty & Wellness</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label fw-500">GSTIN / Business Registration (Optional)</label>
                  <input type="text" name="gstin" class="form-control" placeholder="27AAAAA0000A1Z5" value="<?= sanitize($_POST['gstin'] ?? '') ?>">
                </div>
              <?php endif; ?>

              <div class="col-md-6">
                <label class="form-label fw-500">Password *</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" autocomplete="new-password" required>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-500">Confirm Password *</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" autocomplete="new-password" required>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-600">
                  <i class="bi bi-person-plus-fill me-2"></i> Register Account
                </button>
              </div>
            </div>
          </form>

          <div class="text-center small mt-4">
            Already have an account? <a href="login.php" class="fw-600 text-primary">Sign In</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
