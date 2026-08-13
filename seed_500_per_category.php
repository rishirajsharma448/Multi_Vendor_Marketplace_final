<?php
/* Vyapar Setu - Bulk Catalog Data Seeder
   Generates 500+ realistic products per category across 8 categories (Total: 4,000+ items)
   Assigned across SME vendors with SKUs, realistic prices, stock, ratings, and image URLs.
*/

set_time_limit(600); // 10 minutes max execution
ini_set('memory_limit', '512M');

require_once __DIR__ . '/includes/db.php';

$db = getDBConnection();
echo "===========================================\n";
echo "Starting Bulk Seeder: 500+ items per category...\n";
echo "===========================================\n";

// Disable foreign key checks for speed
$db->exec("SET FOREIGN_KEY_CHECKS=0");
$db->exec("TRUNCATE TABLE product_images");
$db->exec("TRUNCATE TABLE order_items");
$db->exec("TRUNCATE TABLE cart_items");
$db->exec("TRUNCATE TABLE wishlist");
$db->exec("TRUNCATE TABLE reviews");
$db->exec("TRUNCATE TABLE products");
$db->exec("SET FOREIGN_KEY_CHECKS=1");

// Fetch active categories
$categories = $db->query("SELECT id, name FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
// Fetch active vendors
$vendors = $db->query("SELECT id, store_name FROM vendors ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

if (empty($categories)) {
    die("Error: No categories found in database.\n");
}

if (empty($vendors)) {
    // Insert default vendor if missing
    $db->exec("INSERT INTO vendors (vendor_code, store_name, owner_name, email, phone, status) VALUES ('VND-001', 'Vyapar Setu Official Store', 'Store Manager', 'admin@vyaparsetu.in', '9876543210', 'APPROVED')");
    $vendors = $db->query("SELECT id, store_name FROM vendors ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
}

$vendorCount = count($vendors);

// Product Catalog Generator Templates per Category
$categoryTemplates = [
    'Electronics' => [
        'prefix' => 'ELE',
        'adjectives' => ['Ultra HD', 'Pro ANC', 'Smart OLED', 'Wireless', 'Ergonomic', 'Noise-Cancelling', 'Fast-Charge', 'Portable', 'Dual-Band', 'MagSafe', 'Bluetooth 5.3', 'Premium', 'Compact'],
        'items' => ['Earbuds', 'Smartwatch', '4K Streaming Webcam', 'Mechanical RGB Keyboard', 'Precision Wireless Mouse', 'Portable Speaker', 'Wi-Fi 6 Router', '20000mAh Power Bank', 'Studio Headphones', 'LED Desk Lamp', 'USB-C Docking Station', 'Drone Camera', 'Smart Ring Fitness Tracker', 'Soundbar with Subwoofer'],
        'base_price' => 1499,
        'price_range' => 12000,
        'images' => [
            'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Fashion' => [
        'prefix' => 'FSH',
        'adjectives' => ['Handcrafted Pure Silk', 'Organic Cotton', 'Traditional Handblocked', 'Pure Pashmina', 'Chanderi Silk', 'Ethnic Designer', 'Georgette Anarkali', 'Classic Slim Fit', 'Breathable Linen', 'Hand-Embroidered', 'Vintage Denim', 'Jacquard Weave'],
        'items' => ['Banarasi Saree', 'Chanderi Kurta Set', 'Indigo Dabu Dupatta', 'Kashmiri Embroidered Shawl', 'Nehru Jacket', 'Bandhani Silk Saree', 'Chikankari Kurti', 'Anarkali Suit', 'Cotton Casual Shirt', 'Denim Jacket', 'Palazzo Pants', 'Lehenga Choli Set'],
        'base_price' => 899,
        'price_range' => 8500,
        'images' => [
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Spices & Organics' => [
        'prefix' => 'SPC',
        'adjectives' => ['Organic Malabar', 'Grade-1 Kashmiri Mongra', 'Pure Lakadong', 'Single Origin Wayanad', 'Hand-Pounded', 'Raw Forest', 'Cold-Pressed', 'Traditional A2 Desi', 'Himalayan Pink', 'Wild Farm Fresh', 'Sun-Dried Organic'],
        'items' => ['Black Pepper Whole (500g)', 'Saffron Kesar (2g)', 'Turmeric Powder (250g)', 'Green Cardamom (100g)', 'Red Chili Powder (500g)', 'Raw Forest Honey (500g)', 'Mustard Oil (1L)', 'Cow Ghee (500ml)', 'Cumin Seeds (250g)', 'Cloves Whole (100g)', 'Rock Salt (1kg)', 'Cold-Pressed Coconut Oil (500ml)'],
        'base_price' => 299,
        'price_range' => 1800,
        'images' => [
            'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Handicrafts' => [
        'prefix' => 'HDC',
        'adjectives' => ['Hand-Carved Solid Brass', 'Terracotta Handpainted', 'Rosewood Vintage', 'Jaipur Blue Pottery', 'Kashmiri Walnut Wood', 'Handcrafted Copper', 'Traditional Marbled', 'Terracotta Clay', 'Dhokra Tribal Art', 'Inlaid Wooden'],
        'items' => ['Peacock Diya Idol', 'Chai Kulhad Set (Set of 6)', 'Meenakari Jewellery Box', 'Serving Tray', 'Dry Fruit Bowl', 'Water Jug Set (1.5L)', 'Mandir Puja Bell', 'Decorative Wall Hanging', 'Flower Vase', 'Coaster Set (Set of 4)', 'Wind Chime'],
        'base_price' => 450,
        'price_range' => 4500,
        'images' => [
            'https://images.unsplash.com/photo-1615529182904-14819c35db37?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Home & Kitchen' => [
        'prefix' => 'HMK',
        'adjectives' => ['Cast Iron Heavy-Duty', 'Non-Stick Ceramic', 'Pure Copper', 'Stainless Steel 304', 'Ergonomic Wooden', 'Bamboo Fiber Eco-Friendly', 'Thermal Insulated', 'Smart Glass Top', 'Traditional Clay'],
        'items' => ['Kadhai with Lid', 'Cookware Set (3 Pieces)', 'Water Bottle (1L)', 'Storage Container Jar Set', 'Spatula Set', 'Dinner Set (24 Pieces)', 'Vacuum Flask (750ml)', 'Induction Cooktop', 'Pressure Cooker (3L)', 'Spice Box Organiser'],
        'base_price' => 599,
        'price_range' => 5500,
        'images' => [
            'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1584992236310-6edddc08acff?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Beauty & Herbal Wellness' => [
        'prefix' => 'BHW',
        'adjectives' => ['Herbal Neem & Saffron', 'Ayurvedic Kumkumadi', 'Organic Cold-Pressed', 'Pure Aloe Vera', 'Botanical Essential', 'Natural Sandalwood', 'Tea Tree Purifying', 'Anti-Hairfall Herbal', 'Hydrating Rose'],
        'items' => ['Face Cleanser (200ml)', 'Night Glow Serum (30ml)', 'Virgin Coconut Oil (500ml)', 'Gel Moisture (150g)', 'Oil Spray (100ml)', 'Face Pack Ubtan (100g)', 'Shampoo Bar (100g)', 'Body Butter Lotion (200ml)', 'Lip Balm Tinted (15g)'],
        'base_price' => 250,
        'price_range' => 1950,
        'images' => [
            'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1608248597261-e4d9904944d1?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Footwear & Leather Goods' => [
        'prefix' => 'FLG',
        'adjectives' => ['Handcrafted Genuine Leather', 'Vintage Handmade', 'Ethnic Kolhapuri', 'Genuine Milled Leather', 'Breathable Comfort', 'Durable Stitch', 'Classic Formal', 'Water-Resistant Canvas'],
        'items' => ['Kolhapuri Chappals', 'Messenger Laptop Bag (15.6")', 'Bifold Wallet with RFID', 'Jutti Shoes for Men', 'Leather Travel Duffel Bag', 'Mojari Sandals', 'Slip-on Loafers', 'Belts with Brass Buckle', 'Backpack Daypack'],
        'base_price' => 799,
        'price_range' => 6800,
        'images' => [
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?auto=format&fit=crop&w=600&q=80'
        ]
    ],
    'Books & Stationery' => [
        'prefix' => 'BKS',
        'adjectives' => ['Recycled Cotton Paper', 'Hardcover Premium', 'Handmade Leather-Bound', 'Minimalist Academic', 'Gold-Foil Embossed', 'Eco-Friendly Bamboo', 'Calligraphy Artisan'],
        'items' => ['Journal & Quill Pen Set', 'Notebook Planner 2026', 'Fountain Pen Converter', 'Sketchbook 200 GSM', 'Desk Organiser Tray', 'Handmade Parchment Scroll', 'Bookmark Set (Set of 5)', 'Wooden Gel Pen Set'],
        'base_price' => 199,
        'price_range' => 1450,
        'images' => [
            'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1585776245991-cf89dd7fc73a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80'
        ]
    ]
];

// Statements for fast batch inserts
$prodSql = "INSERT INTO products (sku, vendor_id, category_id, category_name, title, description, price, original_price, discount, stock, image_url, rating, rating_count, spotlight, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVE', NOW())";
$pStmt = $db->prepare($prodSql);

$imgSql = "INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, 1)";
$iStmt = $db->prepare($imgSql);

$spotlights = ['REGULAR', 'REGULAR', 'FEATURED', 'BESTSELLER', 'REGULAR'];
$totalInserted = 0;
$targetPerCategory = 500;

foreach ($categories as $cat) {
    $catId = $cat['id'];
    $catName = trim($cat['name']);
    
    // Find matching template or fallback
    $tmplKey = 'Electronics';
    foreach ($categoryTemplates as $k => $tmpl) {
        if (strpos(strtolower($catName), strtolower(explode('&', $k)[0])) !== false) {
            $tmplKey = $k;
            break;
        }
    }
    $tmpl = $categoryTemplates[$tmplKey];
    
    echo "Seeding category: {$catName} (Target: {$targetPerCategory} items)...\n";
    
    $db->beginTransaction();
    
    for ($n = 1; $n <= $targetPerCategory; $n++) {
        $vendor = $vendors[($n + $catId) % $vendorCount];
        $vendorId = $vendor['id'];
        
        $adj = $tmpl['adjectives'][$n % count($tmpl['adjectives'])];
        $itm = $tmpl['items'][$n % count($tmpl['items'])];
        $variant = "Edition #" . ($n + 100);
        
        $title = "{$adj} {$itm} ({$variant})";
        $sku = "SKU-{$tmpl['prefix']}-" . str_pad($n, 4, '0', STR_PAD_LEFT);
        
        $desc = "Authentic {$title} supplied directly by verified SME vendor '{$vendor['store_name']}'. Crafted using premium materials, high durability quality checks, and covered under 100% Vyapar Setu buyer protection warranty.";
        
        $price = $tmpl['base_price'] + rand(50, $tmpl['price_range']);
        $originalPrice = $price + rand(200, 1500);
        $discount = (int)round((($originalPrice - $price) / $originalPrice) * 100);
        $stock = rand(5, 120);
        $rating = number_format(3.8 + (rand(0, 12) / 10), 1);
        $ratingCount = rand(5, 340);
        $imgUrl = $tmpl['images'][$n % count($tmpl['images'])];
        $spotlight = $spotlights[$n % count($spotlights)];
        
        // Execute Product Insert
        $pStmt->execute([
            $sku, $vendorId, $catId, $catName, $title, $desc, $price, $originalPrice, $discount, $stock, $imgUrl, $rating, $ratingCount, $spotlight
        ]);
        $productId = $db->lastInsertId();
        
        // Execute Product Image Insert
        $iStmt->execute([$productId, $imgUrl]);
        
        $totalInserted++;
    }
    
    $db->commit();
    echo " -> Created {$targetPerCategory} items for {$catName} successfully!\n";
}

echo "===========================================\n";
echo "SUCCESS! Total items seeded: {$totalInserted} products!\n";
echo "===========================================\n";
