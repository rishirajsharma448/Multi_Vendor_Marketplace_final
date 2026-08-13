<?php
$pageTitle = "Frequently Asked Questions - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<main class="py-5 bg-light">
  <div class="container">
    <div class="max-w-800px mx-auto">
      <h2 class="fw-bold mb-4 text-center">Frequently Asked Questions</h2>
      <div class="accordion shadow-sm rounded-3 overflow-hidden" id="faqAccordion">
        <div class="accordion-item border-0 border-bottom">
          <h2 class="accordion-header">
            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              How do I become a vendor on Vyapar Setu?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
            <div class="accordion-body text-muted">
              Click on the "Become a Vendor" button, fill in your store details, GSTIN, and business information. Once submitted, your store will be instantly activated.
            </div>
          </div>
        </div>
        <div class="accordion-item border-0 border-bottom">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              What payment methods are supported?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body text-muted">
              Vyapar Setu supports Credit/Debit cards, UPI payments, Razorpay, Net Banking, and Cash on Delivery (COD).
            </div>
          </div>
        </div>
        <div class="accordion-item border-0">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              How does order tracking work?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body text-muted">
              Once an order is placed, vendors update the status in real time (e.g. Order Placed, Shipped, Delivered), and customers can view status in their Customer Dashboard under "My Orders".
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
