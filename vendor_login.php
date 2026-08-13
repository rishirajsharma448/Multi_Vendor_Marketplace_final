<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'vendor') {
    header('Location: vendor_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SME Vendor Login Portal - Vyapar Setu</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      background: linear-gradient(135deg, var(--navy-900) 0%, #1E1B4B 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .login-card {
      background: #FFFFFF;
      border-radius: var(--radius-xl);
      padding: 2.5rem;
      max-width: 440px;
      width: 100%;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }
  </style>
</head>
<body>

  <div class="login-card">
    <div style="text-align: center; margin-bottom: 2rem;">
      <div class="logo-icon" style="margin: 0 auto 1rem; width: 52px; height: 52px; font-size: 1.8rem;">VS</div>
      <h2 style="font-size: 1.75rem; color: var(--navy-900);">SME Vendor Console</h2>
      <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.35rem;">Sign in with your registered vendor email to manage products, sales analytics & fulfillment.</p>
    </div>

    <form id="vendor-login-form" onsubmit="handleVendorLogin(event)">
      <div style="margin-bottom: 1.25rem;">
        <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.35rem;">Vendor Gmail / Business Email</label>
        <div class="search-input-group" style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 0.4rem 0.85rem;">
          <i class="fa-solid fa-envelope" style="color: #94A3B8;"></i>
          <input type="email" id="vendor-email" required placeholder="vendor@store.com" style="width: 100%; border: none; outline: none; background: none; padding: 0.3rem 0.5rem; font-size: 0.9rem;">
        </div>
      </div>

      <div style="margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
          <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); margin: 0;">Password</label>
          <a href="forgot_password.php?role=vendor" style="font-size: 0.8rem; color: var(--primary-600); text-decoration: none; font-weight: 600;"><i class="fa-solid fa-key"></i> Forgot Password / OTP Login?</a>
        </div>
        <div class="search-input-group" style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 0.4rem 0.85rem;">
          <i class="fa-solid fa-lock" style="color: #94A3B8;"></i>
          <input type="password" id="vendor-password" required placeholder="••••••••" style="width: 100%; border: none; outline: none; background: none; padding: 0.3rem 0.5rem; font-size: 0.9rem;">
        </div>
      </div>

      <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 0.95rem;">
        Login to Store Console <i class="fa-solid fa-arrow-right"></i>
      </button>
    </form>

    <div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem;">
      <a href="index.php" style="color: var(--text-muted); text-decoration: underline;"><i class="fa-solid fa-arrow-left"></i> Back to Customer Storefront</a>
    </div>
  </div>

  <script>
    async function handleVendorLogin(e) {
      e.preventDefault();
      const email = document.getElementById('vendor-email').value;
      const password = document.getElementById('vendor-password').value;

      const res = await fetch('api/auth.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ role: 'vendor', email, password })
      });
      const data = await res.json();

      if (data.status === 'success') {
        window.location.href = data.redirect;
      } else {
        alert(data.message);
      }
    }
  </script>
</body>
</html>
