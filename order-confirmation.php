<?php
$pageTitle = "Order Confirmed - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

requireAuth();
$user = getCurrentUser();
$db = getDBConnection();

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Order
$stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: " . APP_URL . "/shop.php");
    exit;
}

// Fetch Order Items
$itemStmt = $db->prepare("
    SELECT oi.*, v.store_name, pi.image_url 
    FROM order_items oi
    LEFT JOIN vendors v ON oi.vendor_id = v.id
    LEFT JOIN products p ON oi.product_id = p.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE oi.order_id = ?
");
$itemStmt->execute([$orderId]);
$orderItems = $itemStmt->fetchAll();

$deliveryDate = date('D, M d, Y', strtotime('+4 days'));
?>

<!-- Include Canvas Confetti JS Library -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
  @keyframes popScale {
    0% { transform: scale(0.5); opacity: 0; }
    70% { transform: scale(1.08); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
  }

  @keyframes floatBounce {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(5deg); }
  }

  @keyframes pulseGlow {
    0%, 100% { box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2); }
    50% { box-shadow: 0 15px 45px rgba(16, 185, 129, 0.45); }
  }

  @keyframes checkmarkRipple {
    0% { transform: scale(0.8); opacity: 0.5; }
    100% { transform: scale(1.4); opacity: 0; }
  }

  .celebration-card {
    animation: popScale 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    background: #ffffff;
    border-radius: 24px !important;
    position: relative;
    overflow: hidden;
  }

  .celebration-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 6px;
    background: linear-gradient(90deg, #ff4757, #ffa502, #2ed573, #1e90ff, #3742fa);
  }

  .party-popper-left, .party-popper-right {
    position: absolute;
    top: 20px;
    font-size: 2.5rem;
    animation: floatBounce 2.5s ease-in-out infinite;
    user-select: none;
  }

  .party-popper-left { left: 25px; animation-delay: 0s; }
  .party-popper-right { right: 25px; animation-delay: 0.3s; }

  .success-icon-wrapper {
    position: relative;
    display: inline-block;
  }

  .success-icon-wrapper::after {
    content: '';
    position: absolute;
    top: -10px; left: -10px; right: -10px; bottom: -10px;
    border-radius: 50%;
    background: rgba(46, 213, 115, 0.3);
    animation: checkmarkRipple 1.8s infinite;
    z-index: 0;
  }

  .success-icon-main {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #2ed573 0%, #10ac84 100%);
    color: #fff;
    border-radius: 50%;
    font-size: 3rem;
    box-shadow: 0 10px 25px rgba(46, 213, 115, 0.4);
    animation: popScale 0.8s ease-out 0.2s both;
  }

  .order-number-badge {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 0.5rem 1rem;
  }

  .slide-up-delay-1 {
    animation: popScale 0.6s ease-out 0.3s both;
  }

  .slide-up-delay-2 {
    animation: popScale 0.6s ease-out 0.5s both;
  }

  .celebration-btn {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .celebration-btn:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
  }
</style>

<main class="py-5 style-bg-celebration" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); min-height: 85vh;">
  <div class="container-fluid px-lg-5">
    <div class="max-w-800px mx-auto" style="max-width: 800px;">
      
      <!-- Order Confirmed Success Header Card -->
      <div class="card celebration-card border-0 shadow-lg p-4 p-md-5 text-center mb-4">
        <!-- Floating Party Poppers -->
        <span class="party-popper-left">🎉</span>
        <span class="party-popper-right">🥳</span>

        <div class="mb-4">
          <div class="success-icon-wrapper">
            <div class="success-icon-main">
              <i class="bi bi-check-lg"></i>
            </div>
          </div>
        </div>

        <span class="badge bg-success-subtle text-success border border-success-subtle text-uppercase fs-6 px-3 py-2 mx-auto mb-3 rounded-pill fw-bold" style="letter-spacing: 1px;">
          <i class="bi bi-stars me-1"></i> Order Confirmed!
        </span>

        <h2 class="fw-extrabold text-dark mb-2 display-6" style="font-weight: 800;">Woohoo! Your Order is Placed! 🎊</h2>
        <p class="text-muted fs-5 mb-4">Thank you for shopping with us! Your order reference number is: <br>
          <span class="order-number-badge d-inline-block mt-2 font-mono text-primary fw-bold fs-4">
            <i class="bi bi-hash"></i><?= sanitize($order['order_number']) ?>
          </span>
        </p>

        <div class="p-3 rounded-4 d-inline-flex align-items-center gap-3 max-w-400px mx-auto text-start border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
          <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-truck fs-4"></i>
          </div>
          <div>
            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Estimated Delivery</small>
            <strong class="text-dark fs-6"><?= $deliveryDate ?></strong>
          </div>
        </div>
      </div>

      <!-- Order Details Card -->
      <div class="card celebration-card slide-up-delay-1 border-0 shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-4 pb-3 border-bottom d-flex align-items-center gap-2">
          <i class="bi bi-bag-check-fill text-primary fs-4"></i> Order Summary & Items
        </h5>
        
        <!-- Purchased Items List -->
        <div class="mb-4">
          <?php foreach ($orderItems as $item): ?>
            <?php $img = !empty($item['image_url']) ? $item['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=150&q=80'; ?>
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
              <div class="d-flex align-items-center gap-3">
                <img src="<?= sanitize($img) ?>" class="rounded-3 border" width="64" height="64" style="object-fit: contain; background: #f8fafc;">
                <div>
                  <h6 class="fw-bold mb-1 text-dark"><?= sanitize($item['product_title']) ?></h6>
                  <small class="text-success fw-bold"><i class="bi bi-shop me-1"></i><?= sanitize($item['store_name'] ?? 'Vendor Merchant') ?></small>
                  <div class="small text-muted mt-1">Qty: <strong><?= (int)$item['quantity'] ?></strong> × <?= formatCurrency($item['price']) ?></div>
                </div>
              </div>
              <strong class="text-dark fs-6"><?= formatCurrency($item['price'] * $item['quantity']) ?></strong>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="row g-4">
          <!-- Shipping Address -->
          <div class="col-md-6">
            <div class="p-3 bg-light rounded-3 h-100 border">
              <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Shipping Address</h6>
              <p class="small text-dark fw-bold mb-1"><?= sanitize($order['customer_name']) ?></p>
              <p class="small text-muted mb-1"><?= sanitize($order['address']) ?></p>
              <p class="small text-muted mb-0"><i class="bi bi-telephone me-1"></i> <?= sanitize($order['phone']) ?></p>
            </div>
          </div>

          <!-- Financial Breakdown -->
          <div class="col-md-6">
            <div class="p-3 bg-light rounded-3 h-100 border">
              <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-credit-card-fill text-primary me-1"></i> Payment Info</h6>
              <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Payment Method:</span>
                <strong class="text-dark"><?= sanitize($order['payment_method']) ?></strong>
              </div>
              <div class="d-flex justify-content-between small text-muted mb-2">
                <span>Payment Status:</span>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning">Processing</span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Subtotal:</span>
                <span class="text-dark fw-bold"><?= formatCurrency($order['subtotal']) ?></span>
              </div>
              <?php if ($order['discount'] > 0): ?>
                <div class="d-flex justify-content-between small text-muted mb-1">
                  <span>Discount:</span>
                  <span class="text-success fw-bold">-<?= formatCurrency($order['discount']) ?></span>
                </div>
              <?php endif; ?>
              <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Shipping Fee:</span>
                <span class="text-dark fw-bold"><?= formatCurrency($order['shipping_fee']) ?></span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between fw-bold text-dark">
                <span>Total Amount Paid:</span>
                <span class="text-primary fs-5"><?= formatCurrency($order['total_amount']) ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-3 justify-content-center slide-up-delay-2 flex-wrap">
        <a href="<?= APP_URL ?>/customer/orders.php" class="btn btn-primary btn-lg px-4 py-2 fw-bold celebration-btn rounded-3 shadow-sm">
          <i class="bi bi-box-seam-fill me-2"></i> Track My Orders
        </a>
        <a href="<?= APP_URL ?>/shop.php" class="btn btn-outline-dark btn-lg px-4 py-2 fw-bold celebration-btn rounded-3">
          <i class="bi bi-cart-plus-fill me-2"></i> Continue Shopping
        </a>
      </div>

    </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Multi-stage confetti popper celebration launch!
    if (typeof confetti === 'function') {
      
      // Cannon burst 1: Center fireworks burst
      confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 },
        colors: ['#2ed573', '#ff4757', '#ffa502', '#1e90ff', '#e84393']
      });

      // Cannon burst 2: Side poppers (left & right)
      setTimeout(function() {
        confetti({
          particleCount: 50,
          angle: 60,
          spread: 55,
          origin: { x: 0, y: 0.7 }
        });
        confetti({
          particleCount: 50,
          angle: 120,
          spread: 55,
          origin: { x: 1, y: 0.7 }
        });
      }, 350);

      // Cannon burst 3: Stars and festive sparkles
      setTimeout(function() {
        confetti({
          particleCount: 80,
          spread: 100,
          origin: { y: 0.4 },
          shapes: ['star', 'circle'],
          colors: ['#FFD700', '#FF69B4', '#00FFFF', '#FF4500']
        });
      }, 750);
    }
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

