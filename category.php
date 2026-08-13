<?php
$pageTitle = "Categories - Vyapar Setu";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDBConnection();
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (!empty($slug)) {
    $cat = $db->prepare("SELECT * FROM categories WHERE slug = ?");
    $cat->execute([$slug]);
    $category = $cat->fetch();
    if ($category) {
        header("Location: " . APP_URL . "/shop.php?category=" . $category['id']);
        exit;
    }
}

$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
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

<main class="py-5 bg-light">
  <div class="container-fluid px-lg-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Explore All Product Categories</h2>
      <p class="text-muted">Find exactly what you need from authentic marketplace vendors</p>
    </div>

    <div class="row g-4">
      <?php foreach ($categories as $cat): ?>
        <div class="col-md-4 col-lg-3">
          <div class="card card-custom p-4 text-center h-100 border-0 shadow-sm hover-top">
            <div class="bg-white rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border: 2px solid #f1f5f9;">
              <?= getCategoryIconHTML($cat['name']) ?>
            </div>
            <h5 class="fw-bold text-dark mb-2"><?= sanitize($cat['name']) ?></h5>
            <p class="text-muted small mb-3"><?= sanitize($cat['description'] ?: 'Explore top products in this category.') ?></p>
            <a href="shop.php?category=<?= $cat['id'] ?>" class="btn btn-outline-primary btn-sm mt-auto fw-600">Browse Category</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
