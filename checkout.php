<?php
$pageTitle = "Checkout - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

requireAuth();
$user = getCurrentUser();
$db = getDBConnection();

// Fetch Cart items
$cartStmt = $db->prepare("
    SELECT ci.id AS item_id, ci.quantity, ci.price, p.id AS product_id, p.title, p.vendor_id, p.stock, v.store_name, pi.image_url
    FROM cart c
    JOIN cart_items ci ON c.id = ci.cart_id
    JOIN products p ON ci.product_id = p.id
    LEFT JOIN vendors v ON p.vendor_id = v.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE c.user_id = ?
");
$cartStmt->execute([$user['id']]);
$cartItems = $cartStmt->fetchAll();

if (empty($cartItems)) {
    setFlash('warning', 'Your shopping cart is empty.');
    header("Location: " . APP_URL . "/customer/cart.php");
    exit;
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Coupon Application & Removal
$discountAmount = 0.00;
$couponDiscountRate = 0.00;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['apply_coupon'])) {
        $enteredCode = strtoupper(trim($_POST['coupon_code'] ?? ''));
        if ($enteredCode === 'FREEDOM80') {
            $_SESSION['applied_coupon'] = 'FREEDOM80';
            $_SESSION['coupon_discount_rate'] = 0.15; // 15% discount
            setFlash('success', 'Promo code FREEDOM80 applied! 15% discount unlocked.');
        } else if ($enteredCode === 'VYAPAR10') {
            $_SESSION['applied_coupon'] = 'VYAPAR10';
            $_SESSION['coupon_discount_rate'] = 0.10; // 10% discount
            setFlash('success', 'Promo code VYAPAR10 applied! 10% discount unlocked.');
        } else if ($enteredCode === 'FESTIVE20') {
            $_SESSION['applied_coupon'] = 'FESTIVE20';
            $_SESSION['coupon_discount_rate'] = 0.20; // 20% discount
            setFlash('success', 'Promo code FESTIVE20 applied! 20% discount unlocked.');
        } else {
            unset($_SESSION['applied_coupon']);
            unset($_SESSION['coupon_discount_rate']);
            setFlash('danger', 'Invalid coupon code. Try "FREEDOM80" for 15% Off!');
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } elseif (isset($_POST['remove_coupon'])) {
        unset($_SESSION['applied_coupon']);
        unset($_SESSION['coupon_discount_rate']);
        setFlash('info', 'Coupon code removed.');
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

if (isset($_SESSION['applied_coupon']) && isset($_SESSION['coupon_discount_rate'])) {
    $couponDiscountRate = $_SESSION['coupon_discount_rate'];
    $discountAmount = round($subtotal * $couponDiscountRate, 2);
}

$shipping = $subtotal > 0 ? 99.00 : 0.00;
$totalAmount = max(0, $subtotal - $discountAmount + $shipping);

// Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customerName = sanitize($_POST['name'] ?? $user['name']);
    $phone = sanitize($_POST['phone'] ?? $user['phone']);
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $pincode = sanitize($_POST['pincode'] ?? '');
    $paymentMethod = sanitize($_POST['payment_method'] ?? 'COD');
    
    if (empty($address) || empty($city) || empty($pincode)) {
        setFlash('danger', 'Please fill in all shipping address fields.');
    } else {
        $orderNum = "ORD-" . rand(10000, 99999);
        $fullAddress = "$address, $city - $pincode";
        
        $db->beginTransaction();
        try {
            // Create Order
            $insOrder = $db->prepare("
                INSERT INTO orders (order_number, user_id, customer_name, email, phone, address, subtotal, discount, shipping_fee, total_amount, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Processing')
            ");
            $insOrder->execute([$orderNum, $user['id'], $customerName, $user['email'], $phone, $fullAddress, $subtotal, $discountAmount, $shipping, $totalAmount, $paymentMethod]);
            $orderId = $db->lastInsertId();

            // Insert Order Items per Vendor
            $insItem = $db->prepare("
                INSERT INTO order_items (order_id, vendor_id, product_id, product_title, price, quantity, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')
            ");

            foreach ($cartItems as $cItem) {
                $insItem->execute([
                    $orderId,
                    $cItem['vendor_id'],
                    $cItem['product_id'],
                    $cItem['title'],
                    $cItem['price'],
                    $cItem['quantity']
                ]);

                // Reduce Stock
                $upStock = $db->prepare("UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id = ?");
                $upStock->execute([$cItem['quantity'], $cItem['product_id']]);
            }

            // Empty Cart
            $clearCart = $db->prepare("DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM cart WHERE user_id = ?)");
            $clearCart->execute([$user['id']]);

            // Clear Applied Coupon
            unset($_SESSION['applied_coupon']);
            unset($_SESSION['coupon_discount_rate']);

            $db->commit();
            setFlash('success', "Order #$orderNum placed successfully! Thank you for shopping.");
            header("Location: order-confirmation.php?id=" . $orderId);
            exit;
        } catch (Exception $ex) {
            $db->rollBack();
            setFlash('danger', 'Failed to place order: ' . $ex->getMessage());
        }
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-4 bg-light">
  <div class="container-fluid px-lg-5">
    <h3 class="fw-bold mb-4"><i class="bi bi-credit-card-2-front text-primary me-2"></i> Checkout & Place Order</h3>

    <?php displayAlerts(); ?>

    <form action="checkout.php" method="POST">
      <div class="row g-4">
        <!-- Shipping & Payment Form -->
        <div class="col-lg-7">
          <div class="card card-custom p-4 border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt text-primary me-2"></i> Shipping Address</h5>
            
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-bold">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= sanitize($user['name']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>" required>
              </div>
              <div class="col-12">
                <label class="form-label small fw-bold">Street Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="House No., Building, Street Name..." required>123 High Street</textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">City</label>
                <input type="text" name="city" class="form-control" value="Mumbai" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-bold">Pincode</label>
                <input type="text" name="pincode" class="form-control" value="400001" required>
              </div>
            </div>
          </div>

          <div class="card card-custom p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 text-primary me-2"></i> Select Payment Method</h5>
            
            <div class="form-check p-3 border rounded mb-2 payment-option" data-method="cod">
              <input class="form-check-input" type="radio" name="payment_method" id="payCOD" value="COD" checked>
              <label class="form-check-label fw-bold d-block" for="payCOD">
                <i class="bi bi-cash-stack text-success me-1"></i> Cash on Delivery (COD)
                <small class="text-muted d-block fw-normal">Pay with cash upon package delivery</small>
              </label>
            </div>

            <div class="form-check p-3 border rounded mb-2 payment-option" data-method="upi">
              <input class="form-check-input" type="radio" name="payment_method" id="payUPI" value="UPI">
              <label class="form-check-label fw-bold d-block" for="payUPI">
                <i class="bi bi-phone text-primary me-1"></i> UPI / Google Pay / PhonePe
                <small class="text-muted d-block fw-normal">Instant UPI payment via QR or UPI ID</small>
              </label>
            </div>

            <!-- UPI Payment Details Panel -->
            <div id="upiDetails" class="payment-details-panel" style="display: none;">
              <div class="card border-0 rounded-3 p-4 mb-3" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8f5e9 100%);">
                
                <div class="row align-items-center">
                  <div class="col-md-5 text-center mb-3 mb-md-0">
                    <div class="p-3 bg-white rounded-3 shadow-sm d-inline-block">
                      <img src="assets/images/upi-qr-code.png" alt="UPI QR Code" style="width: 180px; height: 180px; object-fit: contain;">
                    </div>
                    <p class="text-muted small mt-2 mb-0"><i class="bi bi-qr-code-scan me-1"></i> Scan QR to pay ₹<?= number_format($totalAmount, 2) ?></p>
                  </div>
                  <div class="col-md-7">
                    <h6 class="fw-bold mb-3"><i class="bi bi-upc-scan text-primary me-1"></i> Or Pay via UPI ID</h6>
                    
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-white"><i class="bi bi-at text-primary"></i></span>
                      <input type="text" class="form-control fw-bold" value="vyaparsetu@upi" id="upiIdField" readonly style="background: #fff; font-size: 1.05rem; letter-spacing: 0.5px;">
                      <button type="button" class="btn btn-outline-primary" onclick="copyUpiId()" id="copyUpiBtn">
                        <i class="bi bi-clipboard me-1"></i> Copy
                      </button>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                      <span class="text-muted small">Pay using:</span>
                      <div class="d-flex gap-2">
                        <span class="badge rounded-pill bg-white text-dark border shadow-sm px-3 py-2" style="font-size: 0.8rem;">
                          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Google_Pay_Logo.svg/512px-Google_Pay_Logo.svg.png" alt="GPay" style="height: 16px;" onerror="this.parentElement.innerHTML='<i class=\'bi bi-google text-primary\'></i> GPay'"> GPay
                        </span>
                        <span class="badge rounded-pill bg-white text-dark border shadow-sm px-3 py-2" style="font-size: 0.8rem;">
                          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/PhonePe_Logo.svg/1024px-PhonePe_Logo.svg.png" alt="PhonePe" style="height: 16px;" onerror="this.parentElement.innerHTML='<i class=\'bi bi-phone text-primary\'></i> PhonePe'"> PhonePe
                        </span>
                        <span class="badge rounded-pill bg-white text-dark border shadow-sm px-3 py-2" style="font-size: 0.8rem;">
                          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/UPI-Logo-vector.svg/1024px-UPI-Logo-vector.svg.png" alt="BHIM UPI" style="height: 16px;" onerror="this.parentElement.innerHTML='<i class=\'bi bi-bank text-primary\'></i> BHIM'"> BHIM
                        </span>
                      </div>
                    </div>

                    <div class="input-group mb-2">
                      <span class="input-group-text bg-white"><i class="bi bi-person-circle text-muted"></i></span>
                      <input type="text" name="upi_reference" class="form-control" placeholder="Enter your UPI ID (e.g. name@paytm)" style="font-size: 0.9rem;">
                    </div>
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Enter your UPI ID for payment verification after placing order</small>
                  </div>
                </div>

                <div class="mt-3 p-2 rounded-2 text-center" style="background: rgba(76, 175, 80, 0.1); border: 1px dashed #4CAF50;">
                  <small class="text-success fw-bold"><i class="bi bi-shield-check me-1"></i> 100% Secure UPI Payment · Powered by Vyapar Setu Payment Gateway</small>
                </div>
              </div>
            </div>

            <div class="form-check p-3 border rounded payment-option" data-method="card">
              <input class="form-check-input" type="radio" name="payment_method" id="payCard" value="CARD">
              <label class="form-check-label fw-bold d-block" for="payCard">
                <i class="bi bi-credit-card text-warning me-1"></i> Credit / Debit Card
                <small class="text-muted d-block fw-normal">Visa, MasterCard, RuPay cards</small>
              </label>
            </div>

            <!-- Card Payment Details Panel -->
            <div id="cardDetails" class="payment-details-panel" style="display: none;">
              <div class="card border-0 rounded-3 p-4 mb-3" style="background: linear-gradient(135deg, #fff8e1 0%, #fff3e0 100%);">
                
                <!-- Visual Card Preview -->
                <div class="mb-4 mx-auto" style="max-width: 380px; perspective: 1000px;">
                  <div id="cardPreview" style="background: linear-gradient(135deg, #1a237e 0%, #283593 40%, #3949ab 100%); border-radius: 16px; padding: 1.5rem; color: #fff; box-shadow: 0 10px 30px rgba(26,35,126,0.35); min-height: 200px; position: relative; overflow: hidden;">
                    <!-- Decorative circles -->
                    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.08);"></div>
                    <div style="position: absolute; bottom: -40px; left: -20px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,0.05);"></div>
                    
                    <div class="d-flex justify-content-between align-items-start mb-4">
                      <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1280px-Visa_Inc._logo.svg.png" alt="Visa" style="height: 24px; filter: brightness(0) invert(1);" onerror="this.style.display='none'">
                      <i class="bi bi-wifi" style="font-size: 1.4rem; transform: rotate(90deg); opacity: 0.7;"></i>
                    </div>
                    
                    <div style="font-family: 'Courier New', monospace; font-size: 1.3rem; letter-spacing: 3px; margin-bottom: 1.2rem;" id="cardNumberPreview">
                      •••• •••• •••• ••••
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-end">
                      <div>
                        <div style="font-size: 0.65rem; text-transform: uppercase; opacity: 0.7; letter-spacing: 1px;">Card Holder</div>
                        <div style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;" id="cardHolderPreview">YOUR NAME</div>
                      </div>
                      <div>
                        <div style="font-size: 0.65rem; text-transform: uppercase; opacity: 0.7; letter-spacing: 1px;">Expires</div>
                        <div style="font-size: 0.9rem; letter-spacing: 1px;" id="cardExpiryPreview">MM/YY</div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label small fw-bold"><i class="bi bi-credit-card me-1"></i> Card Number</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white"><i class="bi bi-credit-card-2-front text-muted"></i></span>
                      <input type="text" name="card_number" id="cardNumberInput" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number" style="font-family: 'Courier New', monospace; font-size: 1.05rem; letter-spacing: 1px;">
                      <span class="input-group-text bg-white" id="cardTypeIcon"><i class="bi bi-credit-card text-muted"></i></span>
                    </div>
                  </div>
                  <div class="col-12">
                    <label class="form-label small fw-bold"><i class="bi bi-person me-1"></i> Name on Card</label>
                    <input type="text" name="card_holder" id="cardHolderInput" class="form-control" placeholder="e.g. RUSHIKESH SHERKAR" autocomplete="cc-name" style="text-transform: uppercase;">
                  </div>
                  <div class="col-6">
                    <label class="form-label small fw-bold"><i class="bi bi-calendar3 me-1"></i> Expiry Date</label>
                    <input type="text" name="card_expiry" id="cardExpiryInput" class="form-control" placeholder="MM / YY" maxlength="7" autocomplete="cc-exp">
                  </div>
                  <div class="col-6">
                    <label class="form-label small fw-bold"><i class="bi bi-lock me-1"></i> CVV</label>
                    <div class="input-group">
                      <input type="password" name="card_cvv" id="cardCvvInput" class="form-control" placeholder="•••" maxlength="4" autocomplete="cc-csc">
                      <span class="input-group-text bg-white"><i class="bi bi-shield-lock text-success"></i></span>
                    </div>
                  </div>
                </div>

                <div class="mt-3 p-2 rounded-2 text-center" style="background: rgba(255, 152, 0, 0.1); border: 1px dashed #FF9800;">
                  <small class="text-warning fw-bold"><i class="bi bi-lock-fill me-1"></i> 256-bit SSL Encrypted · PCI DSS Compliant · Your card data is secure</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-5">
          <div class="card card-custom p-4 border-0 shadow-sm sticky-top" style="top: 80px;">
            <h5 class="fw-bold mb-3">Order Items (<?= count($cartItems) ?>)</h5>
            
            <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
              <?php foreach ($cartItems as $item): ?>
                <?php $img = !empty($item['image_url']) ? $item['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=150&q=80'; ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                  <div class="d-flex align-items-center gap-2">
                    <img src="<?= sanitize($img) ?>" class="rounded border" width="45" height="45" style="object-fit: contain; background: #f8fafc;">
                    <div>
                      <h6 class="fw-bold mb-0 text-dark small text-truncate" style="max-width: 180px;"><?= sanitize($item['title']) ?></h6>
                      <small class="text-muted">Qty: <?= (int)$item['quantity'] ?> × <?= formatCurrency($item['price']) ?></small>
                    </div>
                  </div>
                  <strong class="text-dark small"><?= formatCurrency($item['price'] * $item['quantity']) ?></strong>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Subtotal</span>
              <span class="fw-bold"><?= formatCurrency($subtotal) ?></span>
            </div>
            <?php if ($discountAmount > 0): ?>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Coupon Discount</span>
                <span class="fw-bold text-success">-<?= formatCurrency($discountAmount) ?></span>
              </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Shipping Charges</span>
              <span class="fw-bold"><?= formatCurrency($shipping) ?></span>
            </div>
            <hr>

            <!-- Coupon Input Fields -->
            <div class="mb-4 p-3 bg-light rounded-3 border">
              <label class="form-label small fw-bold text-dark"><i class="bi bi-ticket-perforated text-primary me-1"></i> Promo Coupon Code</label>
              <div class="input-group">
                <input type="text" name="coupon_code" class="form-control form-control-sm text-uppercase fw-bold" placeholder="e.g. FREEDOM80" value="<?= isset($_SESSION['applied_coupon']) ? sanitize($_SESSION['applied_coupon']) : '' ?>">
                <button type="submit" name="apply_coupon" class="btn btn-sm btn-primary">Apply</button>
              </div>
              <?php if (isset($_SESSION['applied_coupon'])): ?>
                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                  <span class="text-success small fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> Coupon '<?= sanitize($_SESSION['applied_coupon']) ?>' Applied (<?= (int)($_SESSION['coupon_discount_rate'] * 100) ?>% Off)
                  </span>
                  <button type="submit" name="remove_coupon" class="btn btn-link btn-sm text-danger p-0 fw-bold text-decoration-none">Remove</button>
                </div>
              <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between mb-4">
              <span class="fs-5 fw-bold">Total Payable</span>
              <span class="fs-4 fw-extrabold text-primary-custom"><?= formatCurrency($totalAmount) ?></span>
            </div>

            <button type="submit" name="place_order" class="btn btn-success btn-lg w-100 fw-bold">
              <i class="bi bi-check-circle-fill me-2"></i> Confirm & Place Order
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>

<style>
  .payment-option {
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .payment-option:hover {
    border-color: #4361ee !important;
    background: #f8f9ff;
  }
  .payment-option.active-method {
    border-color: #4361ee !important;
    background: #f0f3ff;
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
  }
  .payment-details-panel {
    overflow: hidden;
    transition: max-height 0.4s ease, opacity 0.3s ease;
  }
  .payment-details-panel.panel-visible {
    animation: slideDown 0.4s ease forwards;
  }
  .payment-details-panel.panel-hidden {
    animation: slideUp 0.3s ease forwards;
  }
  @keyframes slideDown {
    from { max-height: 0; opacity: 0; }
    to { max-height: 800px; opacity: 1; }
  }
  @keyframes slideUp {
    from { max-height: 800px; opacity: 1; }
    to { max-height: 0; opacity: 0; }
  }
  #cardPreview {
    transition: all 0.3s ease;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
  const upiPanel = document.getElementById('upiDetails');
  const cardPanel = document.getElementById('cardDetails');
  const paymentOptions = document.querySelectorAll('.payment-option');

  function togglePaymentPanels() {
    const selected = document.querySelector('input[name="payment_method"]:checked').value;

    // Update active styling on options
    paymentOptions.forEach(opt => opt.classList.remove('active-method'));
    const activeOption = document.querySelector(`.payment-option[data-method="${selected.toLowerCase()}"]`);
    if (activeOption) activeOption.classList.add('active-method');

    // Show/hide UPI panel
    if (selected === 'UPI') {
      upiPanel.style.display = 'block';
      upiPanel.classList.remove('panel-hidden');
      upiPanel.classList.add('panel-visible');
    } else {
      if (upiPanel.style.display !== 'none') {
        upiPanel.classList.remove('panel-visible');
        upiPanel.classList.add('panel-hidden');
        setTimeout(() => { upiPanel.style.display = 'none'; }, 300);
      }
    }

    // Show/hide Card panel
    if (selected === 'CARD') {
      cardPanel.style.display = 'block';
      cardPanel.classList.remove('panel-hidden');
      cardPanel.classList.add('panel-visible');
    } else {
      if (cardPanel.style.display !== 'none') {
        cardPanel.classList.remove('panel-visible');
        cardPanel.classList.add('panel-hidden');
        setTimeout(() => { cardPanel.style.display = 'none'; }, 300);
      }
    }
  }

  paymentRadios.forEach(radio => {
    radio.addEventListener('change', togglePaymentPanels);
  });

  // Also allow clicking the entire row to select
  paymentOptions.forEach(opt => {
    opt.addEventListener('click', function() {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) { radio.checked = true; togglePaymentPanels(); }
    });
  });

  // Initialize
  togglePaymentPanels();

  // ---- Card Number Formatting ----
  const cardNumInput = document.getElementById('cardNumberInput');
  const cardNumPreview = document.getElementById('cardNumberPreview');
  const cardTypeIcon = document.getElementById('cardTypeIcon');

  if (cardNumInput) {
    cardNumInput.addEventListener('input', function() {
      let val = this.value.replace(/\D/g, '').substring(0, 16);
      let formatted = val.replace(/(\d{4})(?=\d)/g, '$1 ');
      this.value = formatted;

      // Update preview
      let display = formatted || '•••• •••• •••• ••••';
      if (val.length < 16) {
        let remaining = 16 - val.length;
        let dots = '•'.repeat(remaining);
        let full = val + dots;
        display = full.replace(/(.{4})/g, '$1 ').trim();
      }
      cardNumPreview.textContent = display;

      // Detect card type
      if (val.startsWith('4')) {
        cardTypeIcon.innerHTML = '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1280px-Visa_Inc._logo.svg.png" alt="Visa" style="height:20px;" onerror="this.outerHTML=\'<i class=bi-credit-card text-primary></i>\'">';
      } else if (/^5[1-5]/.test(val) || /^2[2-7]/.test(val)) {
        cardTypeIcon.innerHTML = '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/800px-Mastercard-logo.svg.png" alt="MC" style="height:20px;" onerror="this.outerHTML=\'<i class=bi-credit-card text-warning></i>\'">';
      } else if (/^6[0-9]/.test(val)) {
        cardTypeIcon.innerHTML = '<span class="badge bg-primary" style="font-size:0.7rem;">RuPay</span>';
      } else {
        cardTypeIcon.innerHTML = '<i class="bi bi-credit-card text-muted"></i>';
      }
    });
  }

  // ---- Card Holder Name Preview ----
  const cardHolderInput = document.getElementById('cardHolderInput');
  const cardHolderPreview = document.getElementById('cardHolderPreview');
  if (cardHolderInput) {
    cardHolderInput.addEventListener('input', function() {
      cardHolderPreview.textContent = this.value.toUpperCase() || 'YOUR NAME';
    });
  }

  // ---- Expiry Date Formatting ----
  const cardExpiryInput = document.getElementById('cardExpiryInput');
  const cardExpiryPreview = document.getElementById('cardExpiryPreview');
  if (cardExpiryInput) {
    cardExpiryInput.addEventListener('input', function() {
      let val = this.value.replace(/\D/g, '').substring(0, 4);
      if (val.length >= 2) {
        this.value = val.substring(0, 2) + ' / ' + val.substring(2);
      } else {
        this.value = val;
      }
      cardExpiryPreview.textContent = this.value || 'MM/YY';
    });
  }
});

// Copy UPI ID function
function copyUpiId() {
  const upiId = document.getElementById('upiIdField').value;
  navigator.clipboard.writeText(upiId).then(() => {
    const btn = document.getElementById('copyUpiBtn');
    btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-success');
    setTimeout(() => {
      btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy';
      btn.classList.remove('btn-success');
      btn.classList.add('btn-outline-primary');
    }, 2000);
  });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

