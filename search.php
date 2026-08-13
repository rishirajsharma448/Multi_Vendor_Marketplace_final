<?php
$pageTitle = "Search Results - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$query = sanitize($_GET['q'] ?? '');
$db = getDBConnection();

$products = [];
if (!empty($query)) {
    $stmt = $db->prepare("
        SELECT p.*, c.name AS category_name, v.store_name, pi.image_url 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        LEFT JOIN vendors v ON p.vendor_id = v.id 
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
        WHERE (p.title LIKE ? OR p.description LIKE ? OR c.name LIKE ? OR v.store_name LIKE ?)
        AND p.status = 'ACTIVE'
        ORDER BY p.id DESC
    ");
    $searchTerm = "%$query%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $products = $stmt->fetchAll();
}
?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="py-4 bg-light">
  <div class="container-fluid px-lg-5">
    <h4 class="fw-bold mb-3">Search Results for "<?= sanitize($query) ?>" (<?= count($products) ?> items)</h4>

    <?php if (empty($products)): ?>
      <div class="card card-custom p-5 text-center my-4">
        <i class="bi bi-search text-muted display-3 mb-3"></i>
        <h5>No products found matching "<?= sanitize($query) ?>"</h5>
        <p class="text-muted">Try checking your spelling or searching for generic terms like "earbuds", "saree", or "spices".</p>
        <a href="shop.php" class="btn btn-primary-custom mx-auto max-w-200px">Browse Storefront</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($products as $prod): ?>
          <?php $img = !empty($prod['image_url']) ? $prod['image_url'] : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80'; ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="product-card">
              <div class="product-card-img-wrapper">
                <img src="<?= sanitize($img) ?>" alt="<?= sanitize($prod['title']) ?>" class="product-card-img">
              </div>
              <div class="p-3 d-flex flex-column flex-grow-1">
                <small class="text-muted fw-500"><?= sanitize($prod['category_name'] ?? 'General') ?></small>
                <h6 class="fw-bold mb-2 text-truncate">
                  <a href="product.php?id=<?= $prod['id'] ?>" class="text-dark text-decoration-none"><?= sanitize($prod['title']) ?></a>
                </h6>
                <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                  <span class="fs-5 fw-bold text-primary-custom"><?= formatCurrency($prod['price']) ?></span>
                  <button class="btn btn-sm btn-primary-custom btn-add-cart" data-product-id="<?= $prod['id'] ?>">Add</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
