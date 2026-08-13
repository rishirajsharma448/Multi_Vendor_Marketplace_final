<?php
$pageTitle = "Vyapar Setu - Premier Multi-Vendor Marketplace";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDBConnection();

// Fetch Categories
$catStmt = $db->query("SELECT * FROM categories ORDER BY id ASC LIMIT 8");
$categories = $catStmt->fetchAll();

// Fetch Featured Products
$featStmt = $db->query("
    SELECT
        p.*,
        p.price AS current_price,
        c.name AS category_name,
        v.store_name,
        pi.image_url
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN vendors v ON p.vendor_id = v.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE p.status = 'ACTIVE'
    ORDER BY p.id DESC
    LIMIT 8
");
$featuredProducts = $featStmt->fetchAll();
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<?php
function getCategoryIconHTML($catName) {
    $name = strtolower($catName);
    if (strpos($name, 'electron') !== false) {
        return '<i class="fa-solid fa-laptop text-primary fs-3"></i>';
    } elseif (strpos($name, 'fashion') !== false) {
        return '<i class="fa-solid fa-shirt text-danger fs-3"></i>';
    } elseif (strpos($name, 'handicraft') !== false) {
        return '<i class="fa-solid fa-palette text-warning fs-3"></i>';
    } elseif (strpos($name, 'spice') !== false || strpos($name, 'organic') !== false) {
        return '<i class="fa-solid fa-pepper-hot text-success fs-3"></i>';
    } elseif (strpos($name, 'kitchen') !== false || strpos($name, 'home') !== false) {
        return '<i class="fa-solid fa-utensils text-info fs-3"></i>';
    } elseif (strpos($name, 'beauty') !== false || strpos($name, 'herbal') !== false) {
        return '<i class="fa-solid fa-spa fs-3" style="color: #ec4899;"></i>';
    } elseif (strpos($name, 'footwear') !== false || strpos($name, 'leather') !== false) {
        return '<i class="fa-solid fa-shoe-prints text-dark fs-3"></i>';
    } elseif (strpos($name, 'book') !== false || strpos($name, 'stationery') !== false) {
        return '<i class="fa-solid fa-book fs-3" style="color: #6366f1;"></i>';
    } else {
        return '<i class="fa-solid fa-layer-group text-primary fs-3"></i>';
    }
}
?>

<main>
  <!-- Hero Carousel Slider -->
  <section class="py-4 py-lg-5 bg-white border-bottom">
    <div class="container-fluid px-lg-5">
      <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators mb-3">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>

        <div class="carousel-inner">
          <!-- Slide 1: India Independence Day Sale -->
          <div class="carousel-item active">
            <div class="p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #ea580c 0%, #1e1b4b 50%, #16a34a 100%); min-height: 420px; display: flex; align-items: center;">
              <div class="row align-items-center w-100 g-4">
                <div class="col-lg-7">
                  <span class="badge bg-danger text-white font-mono px-3 py-2 rounded-pill fw-bold mb-3 d-inline-block border border-light">
                    🇮🇳 Independence Day Special
                  </span>
                  <h1 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                    Freedom Sale: Flat 15% OFF + Free Shipping!
                  </h1>
                  <p class="lead text-light mb-4 opacity-90 fs-6">
                    Celebrate the spirit of freedom with Vyapar Setu. Enjoy exclusive discount offers on handloom textiles, organic spices, local handicrafts, and homegrown electronics. Use Code: <strong class="text-warning">FREEDOM80</strong>
                  </p>
                  <div class="d-flex flex-wrap gap-3">
                    <a href="shop.php" class="btn btn-warning btn-lg fw-bold px-4 shadow">
                      <i class="bi bi-bag-check-fill me-2"></i> Shop Freedom Deals
                    </a>
                    <a href="register.php?role=vendor" class="btn btn-outline-light btn-lg fw-bold px-4">
                      <i class="bi bi-shop me-2"></i> Sell with Us
                    </a>
                  </div>
                </div>
                <div class="col-lg-5 text-center">
                  <div class="p-2 bg-white rounded-4 shadow-lg d-inline-block border border-3 border-white">
                    <img src="india.png" alt="Independence Day Special Showcase" class="img-fluid rounded-3" style="max-height: 280px; width: 100%; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2: Artisan & SME Brands (Matching Screenshot 1) -->
          <div class="carousel-item">
            <div class="p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 50%, #c2410c 100%); min-height: 420px; display: flex; align-items: center;">
              <div class="row align-items-center w-100 g-4">
                <div class="col-lg-7">
                  <span class="badge bg-warning text-dark font-mono px-3 py-2 rounded-pill fw-bold mb-3 d-inline-block">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Next-Gen Multi-Vendor Shopping
                  </span>
                  <h1 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                    Discover Quality Products from Independent Artisans & Trusted Brands
                  </h1>
                  <p class="lead text-light mb-4 opacity-90 fs-6">
                    Shop thousands of verified products in Electronics, Handloom Fashion, Organic Spices, and Home Décor with nationwide delivery.
                  </p>
                  <div class="d-flex flex-wrap gap-3">
                    <a href="shop.php" class="btn btn-warning btn-lg fw-bold px-4 shadow">
                      <i class="bi bi-bag-check-fill me-2"></i> Explore Marketplace
                    </a>
                    <a href="register.php?role=vendor" class="btn btn-outline-light btn-lg fw-bold px-4">
                      <i class="bi bi-shop me-2"></i> Become a Seller
                    </a>
                  </div>
                </div>
                <div class="col-lg-5 text-center">
                  <div class="p-2 bg-white rounded-4 shadow-lg d-inline-block border border-3 border-white">
                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=700&q=80" alt="Marketplace Showcase" class="img-fluid rounded-3" style="max-height: 280px; width: 100%; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2: Tech & Electronics Sale -->
          <div class="carousel-item">
            <div class="p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2563eb 100%); min-height: 420px; display: flex; align-items: center;">
              <div class="row align-items-center w-100 g-4">
                <div class="col-lg-7">
                  <span class="badge bg-info text-dark font-mono px-3 py-2 rounded-pill fw-bold mb-3 d-inline-block">
                    <i class="bi bi-fire me-1"></i> Direct Vendor Factory Deals
                  </span>
                  <h1 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                    Upgrade Your Lifestyle with Certified SME Electronics & Gadgets
                  </h1>
                  <p class="lead text-light mb-4 opacity-90 fs-6">
                    Get up to 40% OFF on noise-cancelling earbuds, smartwatches, keyboards, 4K webcams, and accessories directly from manufacturers.
                  </p>
                  <div class="d-flex flex-wrap gap-3">
                    <a href="category.php?slug=electronics" class="btn btn-info text-dark btn-lg fw-bold px-4 shadow">
                      <i class="bi bi-laptop me-2"></i> Shop Electronics Deals
                    </a>
                    <a href="register.php?role=vendor" class="btn btn-outline-light btn-lg fw-bold px-4">
                      <i class="bi bi-shop me-2"></i> Become a Seller
                    </a>
                  </div>
                </div>
                <div class="col-lg-5 text-center">
                  <div class="p-2 bg-white rounded-4 shadow-lg d-inline-block border border-3 border-white">
                    <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=700&q=80" alt="Electronics Showcase" class="img-fluid rounded-3" style="max-height: 280px; width: 100%; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 3: Handloom Textiles & Organic Spices -->
          <div class="carousel-item">
            <div class="p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%); min-height: 420px; display: flex; align-items: center;">
              <div class="row align-items-center w-100 g-4">
                <div class="col-lg-7">
                  <span class="badge bg-success text-white font-mono px-3 py-2 rounded-pill fw-bold mb-3 d-inline-block border border-light">
                    <i class="bi bi-patch-check-fill me-1"></i> 100% Authentic Indian Heritage
                  </span>
                  <h1 class="display-5 fw-extrabold text-white mb-3" style="letter-spacing: -1px; font-weight: 800;">
                    Pure Handloom Silk Sarees & Single-Origin Organic Spices
                  </h1>
                  <p class="lead text-light mb-4 opacity-90 fs-6">
                    Support local Indian weavers and farmers. Sourced directly from Varanasi, Kashmir, Wayanad, and Jaipur artisans.
                  </p>
                  <div class="d-flex flex-wrap gap-3">
                    <a href="category.php?slug=fashion" class="btn btn-light btn-lg text-success fw-bold px-4 shadow">
                      <i class="bi bi-basket-fill me-2"></i> Browse Heritage Collections
                    </a>
                    <a href="register.php?role=vendor" class="btn btn-outline-light btn-lg fw-bold px-4">
                      <i class="bi bi-shop me-2"></i> Become a Seller
                    </a>
                  </div>
                </div>
                <div class="col-lg-5 text-center">
                  <div class="p-2 bg-white rounded-4 shadow-lg d-inline-block border border-3 border-white">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=80" alt="Fashion Showcase" class="img-fluid rounded-3" style="max-height: 280px; width: 100%; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Carousel Prev/Next Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <?php displayAlerts(); ?>

  <!-- Features Badges -->
  <section class="py-4 bg-light">
    <div class="container-fluid px-lg-5">
      <div class="row g-3">
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 shadow-sm">
            <div class="bg-primary-light text-primary rounded-circle p-3 fs-4"><i class="bi bi-truck"></i></div>
            <div>
              <h6 class="fw-bold mb-0">Fast Delivery</h6>
              <small class="text-muted">Direct from certified vendors</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 shadow-sm">
            <div class="bg-primary-light text-primary rounded-circle p-3 fs-4"><i class="bi bi-shield-check"></i></div>
            <div>
              <h6 class="fw-bold mb-0">Buyer Protection</h6>
              <small class="text-muted">100% verified payments</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 shadow-sm">
            <div class="bg-primary-light text-primary rounded-circle p-3 fs-4"><i class="bi bi-award"></i></div>
            <div>
              <h6 class="fw-bold mb-0">Authentic Sellers</h6>
              <small class="text-muted">Handpicked local merchants</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 shadow-sm">
            <div class="bg-primary-light text-primary rounded-circle p-3 fs-4"><i class="bi bi-headset"></i></div>
            <div>
              <h6 class="fw-bold mb-0">24/7 Support</h6>
              <small class="text-muted">Dedicated resolution team</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="py-5 bg-white">
    <div class="container-fluid px-lg-5">
      <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
          <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Browse Top Categories</span>
          <h2 class="fw-bold text-dark mb-0">Shop by Category</h2>
        </div>
        <a href="category.php" class="btn btn-outline-primary btn-sm fw-600">View All Categories <i class="bi bi-arrow-right"></i></a>
      </div>

      <div class="row g-3">
        <?php foreach ($categories as $cat): ?>
          <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="category.php?slug=<?= sanitize($cat['slug']) ?>" class="card text-decoration-none text-center p-3 h-100 card-custom border-0 bg-light hover-top shadow-sm">
              <div class="bg-white rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 68px; height: 68px; border: 2px solid #f1f5f9;">
                <?= getCategoryIconHTML($cat['name']) ?>
              </div>
              <h6 class="fw-bold text-dark mb-0 small"><?= sanitize($cat['name']) ?></h6>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <section class="py-5 bg-light">
    <div class="container-fluid px-lg-5">
      <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
          <span class="text-primary fw-bold text-uppercase small">Handpicked Items</span>
          <h2 class="fw-bold text-dark mb-0">Trending Products</h2>
        </div>
        <a href="shop.php" class="btn btn-outline-primary btn-sm fw-600">View Marketplace <i class="bi bi-arrow-right"></i></a>
      </div>

      <div class="row g-4">
        <?php foreach ($featuredProducts as $prod): ?>
          <?php $img = !empty($prod['image_url']) ? $prod['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80'; ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card">
              <div class="product-card-img-wrapper" onclick="window.location.href='product.php?id=<?= $prod['id'] ?>'" style="cursor: pointer;">
                <?php if ($prod['spotlight'] === 'BESTSELLER'): ?>
                  <span class="badge badge-spotlight bg-danger">Bestseller</span>
                <?php elseif ($prod['spotlight'] === 'FEATURED'): ?>
                  <span class="badge badge-spotlight bg-primary">Featured</span>
                <?php endif; ?>
                <img src="<?= sanitize($img) ?>" alt="<?= sanitize($prod['title']) ?>" class="product-card-img" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'">
              </div>
              <div class="p-3 d-flex flex-column flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted fw-500"><?= sanitize($prod['category_name'] ?? 'General') ?></small>
                  <small class="text-success fw-bold"><i class="bi bi-shop me-1"></i><?= sanitize($prod['store_name'] ?? 'Vyapar Setu Vendor') ?></small>
                </div>
                <h6 class="fw-bold mb-2 text-truncate" title="<?= sanitize($prod['title']) ?>">
                  <a href="product.php?id=<?= $prod['id'] ?>" class="text-dark text-decoration-none"><?= sanitize($prod['title']) ?></a>
                </h6>
                <div class="mb-2">
                  <?= renderStars($prod['rating']) ?>
                  <small class="text-muted ms-1">(<?= (int)$prod['rating_count'] ?>)</small>
                </div>
                <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                  <div>
                    <span class="fs-5 fw-bold text-primary-custom"><?= formatCurrency($prod['price']) ?></span>
                    <?php if ($prod['original_price'] > $prod['price']): ?>
                      <small class="text-muted text-decoration-line-through ms-1"><?= formatCurrency($prod['original_price']) ?></small>
                    <?php endif; ?>
                  </div>
                  <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-danger btn-toggle-wishlist" data-product-id="<?= $prod['id'] ?>" title="Save to Wishlist">
                      <i class="<?= isProductInWishlist($prod['id']) ? 'bi bi-heart-fill text-danger' : 'bi bi-heart' ?>"></i>
                    </button>
                    <button class="btn btn-sm btn-primary-custom btn-add-cart" data-product-id="<?= $prod['id'] ?>">
                      <i class="bi bi-cart-plus me-1"></i> Add
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
