<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDBConnection();

$stmt = $db->prepare("
    SELECT p.*, c.name AS category_name, v.store_name, v.rating AS vendor_rating, v.reviews_count AS vendor_reviews, v.email AS vendor_email
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN vendors v ON p.vendor_id = v.id 
    WHERE p.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . APP_URL . '/shop.php');
    exit;
}

$pageTitle = sanitize($product['title']) . " - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';

// Product images
$imgStmt = $db->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY is_primary DESC");
$imgStmt->execute([$productId]);
$images = $imgStmt->fetchAll();

// Product reviews
$revStmt = $db->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$revStmt->execute([$productId]);
$reviews = $revStmt->fetchAll();

// Process review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $currentUser = getCurrentUser();
    if (!$currentUser) {
        setFlash('warning', 'Please login to leave a review.');
        header("Location: " . APP_URL . "/login.php");
        exit;
    }
    
    $rating = (int)($_POST['rating'] ?? 5);
    $comment = sanitize($_POST['comment'] ?? '');
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $ins = $db->prepare("INSERT INTO reviews (product_id, user_id, customer_name, rating, comment) VALUES (?, ?, ?, ?, ?)");
        $ins->execute([$productId, $currentUser['id'], $currentUser['name'], $rating, $comment]);
        setFlash('success', 'Thank you! Your review has been published.');
        header("Location: " . APP_URL . "/product.php?id=" . $productId);
        exit;
    }
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-4 bg-light">
  <div class="container-fluid px-lg-5">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
        <li class="breadcrumb-item"><a href="shop.php?category=<?= $product['category_id'] ?>"><?= sanitize($product['category_name']) ?></a></li>
        <li class="breadcrumb-item active"><?= sanitize($product['title']) ?></li>
      </ol>
    </nav>

    <?php displayAlerts(); ?>

    <div class="row g-4 mb-5">
      <!-- Image Gallery -->
      <div class="col-lg-6">
        <div class="card card-custom p-3 border-0 shadow-sm text-center">
          <?php $primaryImg = !empty($images) ? $images[0]['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=700&q=80'; ?>
          <div class="product-main-img-frame">
            <img src="<?= sanitize($primaryImg) ?>" id="mainProductImg" class="product-main-img" alt="<?= sanitize($product['title']) ?>">
          </div>
          
          <?php if (count($images) > 1): ?>
            <div class="d-flex gap-2 mt-3 justify-content-center flex-wrap">
              <?php foreach ($images as $img): ?>
                <div class="rounded border p-1" style="width: 70px; height: 70px; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="document.getElementById('mainProductImg').src=this.querySelector('img').src">
                  <img src="<?= sanitize($img['image_url']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Details -->
      <div class="col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="badge bg-secondary text-uppercase"><?= sanitize($product['category_name']) ?></span>
            <span class="badge bg-light text-dark border">SKU: <?= sanitize($product['sku']) ?></span>
          </div>

          <h2 class="fw-bold text-dark mb-2"><?= sanitize($product['title']) ?></h2>

          <div class="d-flex align-items-center gap-2 mb-3">
            <?= renderStars($product['rating']) ?>
            <span class="fw-bold text-dark"><?= number_format($product['rating'], 1) ?></span>
            <span class="text-muted">| <?= count($reviews) ?> customer reviews</span>
          </div>

          <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-baseline gap-3">
            <span class="display-6 fw-extrabold text-primary-custom"><?= formatCurrency($product['price']) ?></span>
            <?php if ($product['original_price'] > $product['price']): ?>
              <span class="text-muted text-decoration-line-through fs-5"><?= formatCurrency($product['original_price']) ?></span>
              <span class="badge bg-danger fs-6"><?= round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) ?>% OFF</span>
            <?php endif; ?>
          </div>

          <p class="text-muted mb-4"><?= nl2br(sanitize($product['description'])) ?></p>

          <div class="mb-4">
            <?php if ($product['stock'] > 0): ?>
              <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                <i class="bi bi-check-circle-fill me-1"></i> In Stock
              </span>
            <?php else: ?>
              <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2">
                <i class="bi bi-x-circle-fill me-1"></i> Out of Stock
              </span>
            <?php endif; ?>
          </div>

          <!-- Quantity Selector & Actions -->
          <div class="mb-4">
            <label class="form-label small fw-bold">Quantity</label>
            <div class="d-flex align-items-center gap-2 max-w-200px">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="let q=document.getElementById('productQuantity'); if(q.value>1) q.value--;">-</button>
              <input type="number" id="productQuantity" class="form-control form-control-sm text-center fw-bold" value="1" min="1" max="<?= max(1, (int)$product['stock']) ?>">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="let q=document.getElementById('productQuantity'); q.value++;">+</button>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-primary-custom btn-lg flex-grow-1 btn-add-cart" data-product-id="<?= $product['id'] ?>" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
              <i class="bi bi-cart-plus-fill me-2"></i> Add to Cart
            </button>
            <button class="btn btn-success btn-lg flex-grow-1 btn-buy-now" data-product-id="<?= $product['id'] ?>" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
              <i class="bi bi-lightning-charge-fill me-1"></i> Buy Now
            </button>
            <button class="btn btn-outline-danger btn-lg btn-toggle-wishlist" data-product-id="<?= $product['id'] ?>" title="Save to Wishlist">
              <i class="<?= isProductInWishlist($product['id']) ? 'bi bi-heart-fill text-danger' : 'bi bi-heart' ?>"></i>
            </button>
          </div>

          <!-- Vendor Box -->
          <div class="p-3 border rounded-3 bg-white d-flex align-items-center gap-3">
            <div class="bg-primary-light text-primary rounded-circle p-3 fs-3"><i class="bi bi-shop"></i></div>
            <div>
              <small class="text-muted d-block text-uppercase fw-bold">Sold & Shipped By</small>
              <h6 class="fw-bold mb-0 text-dark"><?= sanitize($product['store_name'] ?? 'Vyapar Setu Merchant') ?></h6>
              <small class="text-success"><i class="bi bi-star-fill text-warning"></i> <?= number_format($product['vendor_rating'], 1) ?> Vendor Rating</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="card card-custom p-4 border-0 shadow-sm mb-5">
      <h4 class="fw-bold mb-4"><i class="bi bi-chat-left-text text-primary me-2"></i> Customer Reviews & Feedback</h4>
      
      <div class="row g-4">
        <div class="col-lg-5">
          <div class="bg-light p-4 rounded-3 mb-3">
            <h6 class="fw-bold mb-2">Leave a Verified Review</h6>
            <form action="product.php?id=<?= $productId ?>" method="POST">
              <div class="mb-3">
                <label class="form-label small fw-bold">Rating</label>
                <select name="rating" class="form-select">
                  <option value="5">5 Stars - Outstanding</option>
                  <option value="4">4 Stars - Very Good</option>
                  <option value="3">3 Stars - Average</option>
                  <option value="2">2 Stars - Poor</option>
                  <option value="1">1 Star - Terrible</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">Your Review</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience with this product..." required></textarea>
              </div>
              <button type="submit" name="submit_review" class="btn btn-primary-custom btn-sm w-100">Submit Review</button>
            </form>
          </div>
        </div>

        <div class="col-lg-7">
          <?php if (empty($reviews)): ?>
            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
          <?php else: ?>
            <?php foreach ($reviews as $rev): ?>
              <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <h6 class="fw-bold mb-0 text-dark"><?= sanitize($rev['customer_name']) ?></h6>
                  <small class="text-muted"><?= date('M d, Y', strtotime($rev['created_at'])) ?></small>
                </div>
                <div class="mb-1"><?= renderStars($rev['rating']) ?></div>
                <p class="mb-1 text-muted small"><?= sanitize($rev['comment']) ?></p>
                <?php if (!empty($rev['vendor_response'])): ?>
                  <div class="bg-light p-2 rounded ms-3 border-start border-primary border-3 mt-2">
                    <small class="fw-bold text-primary"><i class="bi bi-reply-fill"></i> Seller Response:</small>
                    <p class="small text-muted mb-0"><?= sanitize($rev['vendor_response']) ?></p>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
