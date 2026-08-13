<?php
$pageTitle = "Shop Marketplace - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDBConnection();

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$spotlight = isset($_GET['spotlight']) ? sanitize($_GET['spotlight']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

$where = ["p.status = 'ACTIVE'"];
$params = [];

if ($category_id > 0) {
    $where[] = "p.category_id = ?";
    $params[] = $category_id;
}
if (!empty($spotlight)) {
    $where[] = "p.spotlight = ?";
    $params[] = $spotlight;
}

$orderBy = "p.id DESC";
if ($sort === 'price_asc') $orderBy = "p.price ASC";
if ($sort === 'price_desc') $orderBy = "p.price DESC";
if ($sort === 'rating') $orderBy = "p.rating DESC";

$sql = "
    SELECT
        p.*,
        c.name AS category_name,
        v.store_name,
        pi.image_url
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN vendors v ON p.vendor_id = v.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE " . implode(' AND ', $where) . "
    ORDER BY " . $orderBy;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-4 bg-light">
  <div class="container-fluid px-lg-5">
    <div class="row g-4">
      <!-- Filter Sidebar -->
      <div class="col-lg-3">
        <div class="card card-custom p-4 border-0 shadow-sm sticky-top" style="top: 80px;">
          <h5 class="fw-bold mb-3"><i class="bi bi-funnel text-primary me-2"></i> Filter Products</h5>
          
          <form action="shop.php" method="GET">
            <div class="mb-4">
              <label class="form-label fw-600 small text-uppercase">Categories</label>
              <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="0">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                    <?= sanitize($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-600 small text-uppercase">Spotlight Badges</label>
              <select name="spotlight" class="form-select" onchange="this.form.submit()">
                <option value="">All Items</option>
                <option value="BESTSELLER" <?= $spotlight === 'BESTSELLER' ? 'selected' : '' ?>>Bestsellers</option>
                <option value="FEATURED" <?= $spotlight === 'FEATURED' ? 'selected' : '' ?>>Featured</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label fw-600 small text-uppercase">Sort By</label>
              <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Top Customer Rating</option>
              </select>
            </div>

            <a href="shop.php" class="btn btn-outline-secondary w-100 btn-sm">Reset Filters</a>
          </form>
        </div>
      </div>

      <!-- Main Product Display -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-bold mb-0">Marketplace Catalog (<?= count($products) ?> items)</h4>
        </div>

        <?php if (empty($products)): ?>
          <div class="card card-custom p-5 text-center my-4">
            <i class="bi bi-box-seam text-muted display-3 mb-3"></i>
            <h5>No products found matching your filters</h5>
            <p class="text-muted">Try selecting a different category or resetting filters.</p>
            <a href="shop.php" class="btn btn-primary-custom mx-auto max-w-200px">View All Products</a>
          </div>
        <?php else: ?>
          <div class="row g-4">
            <?php foreach ($products as $prod): ?>
              <?php $img = !empty($prod['image_url']) ? $prod['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80'; ?>
              <div class="col-sm-6 col-md-4">
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
                      <small class="text-success fw-bold"><i class="bi bi-shop me-1"></i><?= sanitize($prod['store_name'] ?? 'Vendor') ?></small>
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
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
