<?php
$pageTitle = "Contact Us - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
}
?>
<main class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card card-custom p-5 border-0 shadow-sm">
          <h3 class="fw-bold mb-3">Get in Touch with Vyapar Setu</h3>
          <p class="text-muted mb-4">Have a question about seller onboarding, orders, or support? Send us a message.</p>

          <?php if ($success): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i> Thank you! Your message has been sent. Our support team will get back to you shortly.</div>
          <?php endif; ?>

          <form action="contact.php" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-500">Your Name</label>
                <input type="text" class="form-control" placeholder="John Doe" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-500">Email Address</label>
                <input type="email" class="form-control" placeholder="name@example.com" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-500">Subject</label>
                <input type="text" class="form-control" placeholder="Vendor Application Inquiry" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-500">Message</label>
                <textarea class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary-custom px-4 py-2">Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
