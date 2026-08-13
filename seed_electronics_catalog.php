<?php
/**
 * Vyapar Setu - Electronics Product Catalog Seeder
 * Populates 360+ real products across 8 brands with Amazon product images.
 */

// Disable time limit for large seeding execution
set_time_limit(0);

// Set base path and include db configuration
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/includes/db.php';

echo "Starting Electronics Product Catalog Seeder (Amazon Images Edition)...\n";

// 1. Define Vendors to ensure they exist
$targetVendors = [
    ['name' => 'TechWorld Electronics', 'email' => 'techworld@vyaparsetu.com', 'code' => 'VND-TW001'],
    ['name' => 'Digital Hub', 'email' => 'digitalhub@vyaparsetu.com', 'code' => 'VND-DH002'],
    ['name' => 'Mobile Planet', 'email' => 'mobileplanet@vyaparsetu.com', 'code' => 'VND-MP003'],
    ['name' => 'SmartBuy Store', 'email' => 'smartbuy@vyaparsetu.com', 'code' => 'VND-SB004'],
    ['name' => 'Gadget Zone', 'email' => 'gadgetzone@vyaparsetu.com', 'code' => 'VND-GZ005'],
    ['name' => 'Prime Electronics', 'email' => 'prime@vyaparsetu.com', 'code' => 'VND-PE006'],
    ['name' => 'NextGen Gadgets', 'email' => 'nextgen@vyaparsetu.com', 'code' => 'VND-NG007'],
    ['name' => 'ElectroMart', 'email' => 'electromart@vyaparsetu.com', 'code' => 'VND-EM008']
];

// 2. Define Subcategories under Electronics (parent_id = 1)
$subcategories = [
    'Smartphones' => ['icon' => 'bi-phone', 'desc' => 'Latest flagship and budget smartphones'],
    'Tablets' => ['icon' => 'bi-tablet', 'desc' => 'High-performance tablets for work and play'],
    'Laptops' => ['icon' => 'bi-laptop', 'desc' => 'Ultrabooks, gaming, and business laptops'],
    'Desktop Computers' => ['icon' => 'bi-pc-display-horizontal', 'desc' => 'All-in-one desktops and gaming rigs'],
    'Smart TVs' => ['icon' => 'bi-tv', 'desc' => '4K, QLED, and OLED smart television screens'],
    'Monitors' => ['icon' => 'bi-display', 'desc' => 'Professional, gaming, and smart screen displays'],
    'Headphones' => ['icon' => 'bi-headphones', 'desc' => 'Over-ear and wireless noise-cancelling headphones'],
    'Earbuds' => ['icon' => 'bi-earbuds', 'desc' => 'True Wireless Stereo (TWS) bluetooth earbuds'],
    'Speakers' => ['icon' => 'bi-speaker', 'desc' => 'Bluetooth portable speakers and home soundbars'],
    'Smart Watches' => ['icon' => 'bi-watch', 'desc' => 'Fitness trackers and cellular smart watches'],
    'Projectors' => ['icon' => 'bi-projector', 'desc' => 'Smart portable and home theatre projectors'],
    'Keyboards' => ['icon' => 'bi-keyboard', 'desc' => 'Wired, wireless, and mechanical keyboards'],
    'Mice' => ['icon' => 'bi-mouse', 'desc' => 'Ergonomic, optical, and gaming mice controllers'],
    'Gaming' => ['icon' => 'bi-controller', 'desc' => 'Console accessories, gamepads, and VR headsets'],
    'Chargers' => ['icon' => 'bi-lightning', 'desc' => 'Fast wall chargers and wireless charging stands'],
    'Power Banks' => ['icon' => 'bi-battery-charging', 'desc' => 'High capacity portable battery power banks'],
    'Cables' => ['icon' => 'bi-usb', 'desc' => 'Type-C, Lightning, HDMI, and aux connector cables'],
    'Computer Accessories' => ['icon' => 'bi-cpu', 'desc' => 'USB hubs, laptop stands, and docking stations'],
    'Mobile Accessories' => ['icon' => 'bi-phone-vibrate', 'desc' => 'Phone cases, mounts, and selfie sticks'],
    'Networking' => ['icon' => 'bi-router', 'desc' => 'High speed Wi-Fi routers, mesh, and adapters'],
    'PC Components' => ['icon' => 'bi-cpu', 'desc' => 'Internal motherboards and high end graphics cards'],
    'Other Electronics' => ['icon' => 'bi-device-ssd', 'desc' => 'Smart plugs, trackers, and miscellaneous tech gear']
];

// 3. Define Brands
$brandsList = ['Apple', 'Samsung', 'OPPO', 'vivo', 'ASUS', 'Zebronics', 'Portronics', 'Lenovo'];

// 4. Seeding Execution Core Logic
function runSeedingForPdo($pdo, $dbName) {
    global $targetVendors, $subcategories, $brandsList;
    echo "\n=== Running Seeding on $dbName Database ===\n";

    // Disable foreign key checks for seeding safety
    if ($dbName === 'MySQL') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    } else {
        $pdo->exec("PRAGMA foreign_keys = OFF;");
    }

    // Ensure Electronics parent category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = 1 OR name = 'Electronics'");
    $stmt->execute();
    $electronicsParent = $stmt->fetch();
    if (!$electronicsParent) {
        $pdo->exec("INSERT INTO categories (id, name, slug, icon, description) VALUES (1, 'Electronics', 'electronics', 'bi-laptop', 'Gadgets, Smart Devices & Accessories')");
    }

    // A. Seed Vendors
    $vendorIds = [];
    $passHash = password_hash('password123', PASSWORD_BCRYPT);
    foreach ($targetVendors as $v) {
        // Check user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$v['email']]);
        $user = $stmt->fetch();
        if (!$user) {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES (?, ?, ?, ?, 'vendor', 'ACTIVE', 1)");
            $stmt->execute([$v['name'], $v['email'], '+91 99999 88888', $passHash]);
            $userId = $pdo->lastInsertId();
        } else {
            $userId = $user['id'];
        }

        // Check vendor
        $stmt = $pdo->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $stmt->execute([$userId]);
        $vendor = $stmt->fetch();
        if (!$vendor) {
            $stmt = $pdo->prepare("INSERT INTO vendors (user_id, vendor_code, store_name, owner_name, email, phone, gstin, category, status) VALUES (?, ?, ?, ?, ?, ?, '27AAAAA1111A1Z1', 'Electronics', 'APPROVED')");
            $stmt->execute([$userId, $v['code'], $v['name'], $v['name'] . ' Owner', $v['email'], '+91 99999 88888']);
            $vendorIds[] = $pdo->lastInsertId();
        } else {
            $vendorIds[] = $vendor['id'];
        }
    }

    // B. Seed Brands
    $brandIds = [];
    foreach ($brandsList as $b) {
        $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = ?");
        $stmt->execute([$b]);
        $brand = $stmt->fetch();
        if (!$brand) {
            $stmt = $pdo->prepare("INSERT INTO brands (name, slug) VALUES (?, ?)");
            $stmt->execute([$b, strtolower($b)]);
            $brandIds[$b] = $pdo->lastInsertId();
        } else {
            $brandIds[$b] = $brand['id'];
        }
    }

    // C. Seed Subcategories
    $categoryIds = [];
    foreach ($subcategories as $name => $meta) {
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        $cat = $stmt->fetch();
        if (!$cat) {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, icon, description, parent_id) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$name, strtolower(str_replace(' ', '-', $name)), $meta['icon'], $meta['desc']]);
            $categoryIds[$name] = $pdo->lastInsertId();
        } else {
            $categoryIds[$name] = $cat['id'];
        }
    }

    // D. Generate and Seed Products Programmatically
    $productsData = generateProductsCatalog();
    $totalProductCount = count($productsData);
    echo "Generated $totalProductCount product definitions for insertion.\n";

    $prodCheck = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
    $prodInsert = $pdo->prepare("INSERT INTO products (sku, vendor_id, category_id, brand_id, title, description, price, original_price, discount, stock, spotlight, status, rating, rating_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVE', ?, ?)");
    $prodUpdateImg = $pdo->prepare("UPDATE product_images SET image_url = ? WHERE product_id = ?");
    $imgInsert = $pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, 1)");

    $seeded = 0;
    $updated = 0;

    foreach ($productsData as $p) {
        // Validate lookup IDs
        $brandId = $brandIds[$p['brand']] ?? null;
        $catId = $categoryIds[$p['category']] ?? null;
        $vendorId = $vendorIds[$seeded % count($vendorIds)]; // Distribute across vendors

        if (!$brandId || !$catId) {
            continue;
        }

        // Check if SKU already exists
        $prodCheck->execute([$p['sku']]);
        $existing = $prodCheck->fetch();

        if ($existing) {
            // Update existing product primary image to Amazon URL
            $prodId = $existing['id'];
            $prodUpdateImg->execute([$p['image_url'], $prodId]);
            $updated++;
            continue;
        }

        // Insert Product
        try {
            $prodInsert->execute([
                $p['sku'],
                $vendorId,
                $catId,
                $brandId,
                $p['title'],
                $p['description'],
                $p['price'],
                $p['original_price'],
                $p['discount'],
                $p['stock'],
                $p['spotlight'],
                $p['rating'],
                $p['rating_count']
            ]);

            $prodId = $pdo->lastInsertId();

            // Insert Primary Image
            $imgInsert->execute([$prodId, $p['image_url']]);
            $seeded++;
        } catch (Exception $ex) {
            echo "Error seeding SKU {$p['sku']}: " . $ex->getMessage() . "\n";
        }
    }

    echo "Seeded: $seeded new products. Updated: $updated product Amazon images.\n";

    // Enable foreign keys
    if ($dbName === 'MySQL') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } else {
        $pdo->exec("PRAGMA foreign_keys = ON;");
    }
}

/**
 * Programmatic catalog builder using Amazon product images
 */
function generateProductsCatalog() {
    $catalog = [];
    $globalIndex = 1;

    // Amazon image assets catalog
    $amzImages = [
        'iphone' => 'https://m.media-amazon.com/images/I/71d7rfSl0wL._SL1500_.jpg',
        'iphone_pro' => 'https://m.media-amazon.com/images/I/81CgtwSII3L._SL1500_.jpg',
        'ipad' => 'https://m.media-amazon.com/images/I/61uA2UVnYWL._SL1500_.jpg',
        'macbook' => 'https://m.media-amazon.com/images/I/71f5Eu5lJSL._SL1500_.jpg',
        'apple_watch' => 'https://m.media-amazon.com/images/I/71e-0k6rKkL._SL1500_.jpg',
        'airpods' => 'https://m.media-amazon.com/images/I/61SUj2aKoEL._SL1500_.jpg',
        'samsung_s24' => 'https://m.media-amazon.com/images/I/71vFKBpKakL._SL1500_.jpg',
        'samsung_tab' => 'https://m.media-amazon.com/images/I/71c6NspqI-L._SL1500_.jpg',
        'samsung_tv' => 'https://m.media-amazon.com/images/I/71MlcO4mEaL._SL1500_.jpg',
        'samsung_watch' => 'https://m.media-amazon.com/images/I/61SLdn86sSL._SL1500_.jpg',
        'samsung_buds' => 'https://m.media-amazon.com/images/I/61Qqg+T8nsL._SL1500_.jpg',
        'oppo_reno' => 'https://m.media-amazon.com/images/I/71v2jVh0C3L._SL1500_.jpg',
        'oppo_pad' => 'https://m.media-amazon.com/images/I/61N+V8d7iKL._SL1500_.jpg',
        'vivo_x100' => 'https://m.media-amazon.com/images/I/71XNeka-BRL._SL1500_.jpg',
        'vivo_tws' => 'https://m.media-amazon.com/images/I/51eB6m4pUCL._SL1500_.jpg',
        'asus_rog' => 'https://m.media-amazon.com/images/I/71+20D2b3KL._SL1500_.jpg',
        'asus_tuf' => 'https://m.media-amazon.com/images/I/81t56LkWQSL._SL1500_.jpg',
        'asus_monitor' => 'https://m.media-amazon.com/images/I/81o+C8R2xmL._SL1500_.jpg',
        'zeb_soundbar' => 'https://m.media-amazon.com/images/I/71L+x5+L4xL._SL1500_.jpg',
        'zeb_speaker' => 'https://m.media-amazon.com/images/I/81eT5y8-q2L._SL1500_.jpg',
        'zeb_keyboard' => 'https://m.media-amazon.com/images/I/61o2y3-7r6L._SL1500_.jpg',
        'portronics_sounddrum' => 'https://m.media-amazon.com/images/I/71lK-r4u3mL._SL1500_.jpg',
        'lenovo_ideapad' => 'https://m.media-amazon.com/images/I/61+r3+p7-7L._SL1500_.jpg'
    ];

    // 1. APPLE (Target: 55)
    $appleData = [
        'Smartphones' => [
            ['iPhone 15', 79900, 'Apple iPhone 15 features Dynamic Island, 48MP main camera, USB-C, and robust color-infused glass design as listed on Amazon.', $amzImages['iphone']],
            ['iPhone 15 Plus', 89900, 'Apple iPhone 15 Plus has a larger 6.7-inch display, dual camera system, Dynamic Island, and exceptional battery life.', $amzImages['iphone']],
            ['iPhone 15 Pro', 134900, 'Apple iPhone 15 Pro with aerospace-grade titanium design, A17 Pro chip, customizable Action button, and advanced 48MP camera.', $amzImages['iphone_pro']],
            ['iPhone 15 Pro Max', 159900, 'Apple flagship iPhone 15 Pro Max, titanium design, 5x Telephoto zoom camera, A17 Pro chip.', $amzImages['iphone_pro']],
            ['iPhone 14', 69900, 'Popular iPhone 14 features a dual-camera system, Action mode, Crash Detection.', $amzImages['iphone']],
            ['iPhone 13', 59900, 'iPhone 13 with Super Retina XDR display, advanced dual-camera system, and A15 Bionic chip.', $amzImages['iphone']]
        ],
        'Tablets' => [
            ['iPad (10th Gen)', 39900, 'All-screen iPad in four vibrant colors. Features 10.9-inch Liquid Retina display and A14 Bionic chip.', $amzImages['ipad']],
            ['iPad Air (M2)', 59900, 'Redesigned iPad Air powered by Apple M2 chip. Gorgeous display, landscape camera, and superfast Wi-Fi.', $amzImages['ipad']],
            ['iPad Pro 11-inch (M4)', 99900, 'Thinnest Apple product ever. Features Ultra Retina XDR OLED display, Apple M4 chip.', $amzImages['ipad']],
            ['iPad Pro 13-inch (M4)', 129900, 'Breakthrough Tandem OLED display, M4 performance.', $amzImages['ipad']],
            ['iPad mini', 49900, 'Mega power in mini size. Fits in one hand, supports Apple Pencil.', $amzImages['ipad']]
        ],
        'Laptops' => [
            ['MacBook Air 13-inch (M2)', 99900, 'Superlight MacBook Air with M2 chip, fanless silent design, and up to 18 hours of battery performance.', $amzImages['macbook']],
            ['MacBook Air 13-inch (M3)', 114900, 'Thin and powerful MacBook Air with M3 chip, support for up to two external displays.', $amzImages['macbook']],
            ['MacBook Air 15-inch (M3)', 134900, 'Spacious 15.3-inch Liquid Retina display in a thin fanless laptop.', $amzImages['macbook']],
            ['MacBook Pro 14-inch (M3)', 169900, 'Pro laptop with Liquid Retina XDR screen, M3 chip.', $amzImages['macbook']],
            ['MacBook Pro 14-inch (M3 Pro)', 199900, 'MacBook Pro with M3 Pro chip. High bandwidth unified memory.', $amzImages['macbook']],
            ['MacBook Pro 16-inch (M3 Max)', 349900, 'Ultimate portable workstation with M3 Max chip.', $amzImages['macbook']]
        ],
        'Desktop Computers' => [
            ['Mac mini (M2)', 59900, 'Compact desktop PC with M2 chip, HDMI output, dual USB-A, Ethernet.', $amzImages['macbook']],
            ['iMac 24-inch (M3)', 134900, 'All-in-one desktop computer with stunning 4.5K Retina display.', $amzImages['macbook']],
            ['Mac Studio (M2 Max)', 209900, 'Compact powerhouse for creators.', $amzImages['macbook']]
        ],
        'Smart Watches' => [
            ['Apple Watch Series 9', 41900, 'Advanced fitness and safety smartwatch featuring Double Tap gesture, brighter screen.', $amzImages['apple_watch']],
            ['Apple Watch SE (2nd Gen)', 29900, 'Essential watch features at a friendly price. Heart rate monitoring.', $amzImages['apple_watch']],
            ['Apple Watch Ultra 2', 89900, 'Rugged titanium watch designed for outdoor athletes.', $amzImages['apple_watch']]
        ],
        'Earbuds' => [
            ['AirPods (2nd Gen)', 12900, 'Iconic wireless earbuds with universal fit, optical sensors.', $amzImages['airpods']],
            ['AirPods (3rd Gen)', 19900, 'AirPods featuring personalized Spatial Audio with dynamic head tracking.', $amzImages['airpods']],
            ['AirPods Pro (2nd Gen)', 24900, 'Active Noise Cancellation, Adaptive Audio mode, Conversation Awareness.', $amzImages['airpods']]
        ],
        'Headphones' => [
            ['AirPods Max', 59900, 'Premium over-ear headphones with custom acoustic design, high-fidelity audio.', $amzImages['airpods']]
        ],
        'Computer Accessories' => [
            ['Apple Pencil (2nd Gen)', 11900, 'Wireless charging pencil with double-tap feature, pressure sensitivity.', $amzImages['ipad']],
            ['Apple Pencil Pro', 11900, 'Pencil Pro with squeeze detection, barrel roll, haptic feedback.', $amzImages['ipad']],
            ['Magic Keyboard for iPad', 29900, 'Floating cantilever design, backlit keys, and built-in trackpad.', $amzImages['macbook']],
            ['Magic Mouse', 7500, 'Rechargeable wireless mouse with multi-touch surface.', $amzImages['macbook']],
            ['Magic Keyboard', 9500, 'Slim wireless desktop keyboard with scissor-switch keys.', $amzImages['macbook']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'Apple', $appleData, 55);

    // 2. SAMSUNG (Target: 55)
    $samsungData = [
        'Smartphones' => [
            ['Galaxy S24 Ultra', 129999, 'Galaxy AI flagship, titanium build, 200MP camera, 100x digital zoom.', $amzImages['samsung_s24']],
            ['Galaxy S24 Plus', 99999, 'Vibrant 6.7-inch QHD+ screen, Exynos 2400 processor, dual camera.', $amzImages['samsung_s24']],
            ['Galaxy S24', 79999, 'Compact flagship design, Galaxy AI translation, smart editing.', $amzImages['samsung_s24']],
            ['Galaxy Z Fold 5', 154999, 'Revolutionary folding 7.6-inch main screen, dual app taskbar.', $amzImages['samsung_s24']],
            ['Galaxy Z Flip 5', 99999, 'Pocketable compact folding phone with large cover screen.', $amzImages['samsung_s24']],
            ['Galaxy A55 5G', 39999, 'Midrange device with metallic camera layout, IP67 water rating.', $amzImages['samsung_s24']]
        ],
        'Tablets' => [
            ['Galaxy Tab S9 Ultra', 108999, 'Colossal 14.6-inch Dynamic AMOLED 2X display, IP68 water resistant tablet.', $amzImages['samsung_tab']],
            ['Galaxy Tab S9 FE', 36999, 'Fan Edition tablet, 10.9-inch display, dual camera, S Pen.', $amzImages['samsung_tab']]
        ],
        'Smart Watches' => [
            ['Galaxy Watch 6 Classic', 36999, 'Sleek fitness watch with legendary rotating bezel control, ECG monitoring.', $amzImages['samsung_watch']],
            ['Galaxy Watch 6', 29999, 'Slim bezel fitness watch, high resolution AMOLED screen.', $amzImages['samsung_watch']]
        ],
        'Earbuds' => [
            ['Galaxy Buds 2 Pro', 15999, '24-bit high-fidelity audio, advanced Intelligent ANC.', $amzImages['samsung_buds']],
            ['Galaxy Buds FE', 7999, 'Ergonomic wings ear tips design, deep bass output.', $amzImages['samsung_buds']]
        ],
        'Smart TVs' => [
            ['Crystal 4K UHD TV', 32990, 'PurColor crystal display, smart 4K upscaling, built-in voice assistants.', $amzImages['samsung_tv']],
            ['QLED 4K Smart TV', 59990, '100% Color Volume with Quantum Dot, dual LED backlighting.', $amzImages['samsung_tv']]
        ],
        'Monitors' => [
            ['Smart Monitor M8', 54990, '32-inch 4K screen with Smart TV streaming applications.', $amzImages['samsung_tv']],
            ['Odyssey G9 Gaming Monitor', 129990, '49-inch super ultra-wide 1000R curved gaming monitor.', $amzImages['samsung_tv']]
        ],
        'Mobile Accessories' => [
            ['25W Super Fast Charger', 1299, 'USB-C fast charging adapter block, compact design.', $amzImages['samsung_buds']],
            ['45W Power Adapter T4510', 2999, 'Super Fast Charging 2.0 adapter with cable.', $amzImages['samsung_buds']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'Samsung', $samsungData, 55);

    // 3. OPPO (Target: 38)
    $oppoData = [
        'Smartphones' => [
            ['Oppo Find X7 Ultra', 84999, 'Premium flagship with dual periscope telephoto cameras, Hasselblad colors.', $amzImages['oppo_reno']],
            ['Oppo Reno 11 Pro 5G', 39999, 'Ultra-clear portrait camera system, 32MP telephoto lens.', $amzImages['oppo_reno']],
            ['Oppo Reno 11 5G', 29999, 'Slim design smartphone with portrait expert engine.', $amzImages['oppo_reno']],
            ['Oppo F25 Pro 5G', 23999, 'Borderless 120Hz AMOLED display, IP65 dust and water resistance.', $amzImages['oppo_reno']],
            ['Oppo A79 5G', 17499, 'Vibrant color smartphone with dual stereo speakers, 33W SUPERVOOC.', $amzImages['oppo_reno']]
        ],
        'Tablets' => [
            ['Oppo Pad 2', 35999, 'High-end 11.61-inch tablet with unique 7:5 screen ratio.', $amzImages['oppo_pad']],
            ['Oppo Pad Air', 15999, 'Lightweight budget tablet, 10.36-inch 2K display.', $amzImages['oppo_pad']]
        ],
        'Earbuds' => [
            ['Oppo Enco X2', 10999, 'Dual coaxial drivers TWS earbuds, LHDC 4.0 lossless audio.', $amzImages['oppo_reno']],
            ['Oppo Enco Air 3 Pro', 4999, 'Industry-first bamboo fiber diaphragm drivers, active ANC.', $amzImages['oppo_reno']]
        ],
        'Mobile Accessories' => [
            ['Oppo 80W SuperVOOC Charger', 2499, 'Official high speed fast wall charger adapter.', $amzImages['oppo_reno']],
            ['Oppo SuperVOOC Type-C Cable', 799, 'Heavy duty fast charging type-C cable.', $amzImages['oppo_reno']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'OPPO', $oppoData, 38);

    // 4. VIVO (Target: 38)
    $vivoData = [
        'Smartphones' => [
            ['Vivo X100 Pro', 89999, 'Zeiss APO floating telephoto camera, flagship Dimensity 9300 SoC.', $amzImages['vivo_x100']],
            ['Vivo X100 5G', 63999, 'Premium optics phone, 120W dual cell charging.', $amzImages['vivo_x100']],
            ['Vivo V30 Pro 5G', 41999, 'Zeiss professional portrait camera, Aura light ring studio flash.', $amzImages['vivo_x100']],
            ['Vivo V30 5G', 33999, 'Sleek design device, Aura portrait studio module.', $amzImages['vivo_x100']],
            ['Vivo T2 Pro 5G', 23999, 'Dimensity 7200 processor, 64MP OIS camera.', $amzImages['vivo_x100']]
        ],
        'Earbuds' => [
            ['Vivo TWS 3e', 2999, 'True Wireless earbuds with active noise cancellation ANC.', $amzImages['vivo_tws']],
            ['Vivo Wireless Sport Lite', 1999, 'Neckband style wireless earphones, magnetic design.', $amzImages['vivo_tws']]
        ],
        'Mobile Accessories' => [
            ['Vivo 120W FlashCharge Adapter', 2999, 'High speed flash wall charger, dual cell protection.', $amzImages['vivo_tws']],
            ['Vivo Type-C FlashCharge Cable', 699, 'Official Type-C fast charging cable.', $amzImages['vivo_tws']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'vivo', $vivoData, 38);

    // 5. ASUS (Target: 45)
    $asusData = [
        'Laptops' => [
            ['ROG Zephyrus G14', 149990, 'AMD Ryzen 9, RTX 4060 graphics, premium OLED display.', $amzImages['asus_rog']],
            ['ROG Strix SCAR 16', 289990, 'Intel Core i9 14th Gen, RTX 4080, QHD+ 240Hz screen.', $amzImages['asus_rog']],
            ['TUF Gaming A15', 79990, 'Affordable heavy gaming laptop, Ryzen 7 processor, RTX 4050.', $amzImages['asus_tuf']],
            ['Zenbook 14 OLED', 94990, 'Intel Evo Core Ultra 5, thin sleek design.', $amzImages['asus_rog']],
            ['Vivobook 16', 49990, 'AMD Ryzen 5, 16-inch workspace screen, thin design.', $amzImages['asus_rog']]
        ],
        'Monitors' => [
            ['ROG Swift PG27AQDM OLED', 89990, '27-inch QHD gaming monitor, ultra fast 240Hz refresh rate OLED.', $amzImages['asus_monitor']],
            ['TUF Gaming VG27AQ', 26990, '27-inch IPS gaming monitor, 165Hz refresh rate.', $amzImages['asus_monitor']]
        ],
        'PC Components' => [
            ['ROG Strix Z790-E Motherboard', 45990, 'High-end Intel motherboard with PCIe 5.0, Wi-Fi 7.', $amzImages['asus_tuf']],
            ['TUF Gaming RTX 4070 Ti Super', 89990, 'Elite graphics card, triple fan axial tech cooling.', $amzImages['asus_tuf']]
        ],
        'Gaming' => [
            ['ROG Falchion RX Low Profile', 129900, 'Ultra compact wireless gaming keyboard, mechanical switches.', $amzImages['asus_rog']],
            ['ROG Harpe Ace Aim Lab Edition', 9990, 'Ultra lightweight 54g wireless gaming mouse.', $amzImages['asus_rog']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'ASUS', $asusData, 45);

    // 6. ZEBRONICS (Target: 45)
    $zebronicsData = [
        'Speakers' => [
            ['Zebronics Zeb-Juke Bar 9700 Pro', 12999, 'Dolby Atmos 450W soundbar system, dual wireless satellite speakers.', $amzImages['zeb_soundbar']],
            ['Zebronics Zeb-Music Bomb', 1499, 'Portable outdoor rugged Bluetooth speaker, powerful bass.', $amzImages['zeb_speaker']],
            ['Zebronics Zeb-Space Deck', 5999, 'Heavy party speaker with dual wireless mic inputs.', $amzImages['zeb_speaker']]
        ],
        'Earbuds' => [
            ['Zebronics Zeb-Sound Bomb N1', 1199, 'TWS bluetooth earbuds, active noise cancellation.', $amzImages['zeb_speaker']],
            ['Zebronics Zeb-Sound Bomb 9', 999, 'Budget wireless earbuds, compact case, long backup battery.', $amzImages['zeb_speaker']]
        ],
        'Smart TVs' => [
            ['Zebronics Zeb-Smart TV 43', 19999, '43-inch FHD Smart LED TV, Android platform.', $amzImages['samsung_tv']]
        ],
        'Keyboards' => [
            ['Zebronics Zeb-Transformer-K', 1299, 'Semi-mechanical gaming keyboard, multicolor LED backlights.', $amzImages['zeb_keyboard']],
            ['Zebronics Zeb-Companion 107', 799, 'Wireless keyboard and mouse combo.', $amzImages['zeb_keyboard']]
        ],
        'Mice' => [
            ['Zebronics Zeb-Fighter', 399, 'Wired USB gaming mouse, customizable breathing RGB lights.', $amzImages['zeb_keyboard']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'Zebronics', $zebronicsData, 45);

    // 7. PORTRONICS (Target: 45)
    $portronicsData = [
        'Speakers' => [
            ['Portronics SoundDrum', 1699, '10W portable Bluetooth speaker, built-in FM radio, USB, TF card reader inputs.', $amzImages['portronics_sounddrum']],
            ['Portronics Bounce', 999, 'Super compact 5W portable speaker with silicon mesh strap handle.', $amzImages['portronics_sounddrum']],
            ['Portronics Pure Sound Pro IV', 2499, 'Wireless soundbar speaker, dual bass radiators, 16W output.', $amzImages['portronics_sounddrum']]
        ],
        'Power Banks' => [
            ['Portronics Power Brick 20K', 1499, '20000mAh heavy duty power bank, 22.5W fast charge output.', $amzImages['portronics_sounddrum']],
            ['Portronics Power Plate 7', 999, 'Multiplug smart power board with USB ports.', $amzImages['portronics_sounddrum']]
        ],
        'Chargers' => [
            ['Portronics Adapto 65W', 1999, 'Gan technology multi-port fast charger, outputs Type-C and USB-A.', $amzImages['portronics_sounddrum']],
            ['Portronics Freedom Fold', 1199, 'Foldable wireless charging stand for desktop phone display.', $amzImages['portronics_sounddrum']]
        ],
        'Computer Accessories' => [
            ['Portronics Ruffpad 15', 799, '15-inch LCD writing pad slate drawing board with stylus.', $amzImages['portronics_sounddrum']],
            ['Portronics Toad 13 Wireless Mouse', 349, 'Silent click wireless office mouse, ergonomic shape.', $amzImages['portronics_sounddrum']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'Portronics', $portronicsData, 45);

    // 8. LENOVO (Target: 55)
    $lenovoData = [
        'Laptops' => [
            ['Lenovo IdeaPad Slim 3', 42990, 'Intel Core i3, thin lightweight body design, ideal workspace laptop.', $amzImages['lenovo_ideapad']],
            ['Lenovo ThinkPad E14', 74990, 'Legendary business class laptop with reliable security, trackpoint mouse key.', $amzImages['lenovo_ideapad']],
            ['Lenovo Yoga Slim 7i Pro', 109990, 'Premium thin design, Intel Evo Core i7 processor, 2.8K OLED screen.', $amzImages['lenovo_ideapad']],
            ['Lenovo Legion Pro 5', 139990, 'Intel Core i7, RTX 4060, high performance gaming cooling vents.', $amzImages['lenovo_ideapad']],
            ['Lenovo LOQ 15', 68990, 'Affordable gaming series LOQ, RTX 3050, high cooling tech vents.', $amzImages['lenovo_ideapad']]
        ],
        'Tablets' => [
            ['Lenovo Tab M11', 17999, '11-inch screen tablet, perfect for study reading and video lectures.', $amzImages['ipad']],
            ['Lenovo Tab P12', 29999, 'Vibrant 12.7-inch 3K display, quad JBL speakers.', $amzImages['ipad']]
        ],
        'Monitors' => [
            ['Lenovo Q24i Monitor', 11999, '23.8-inch ultra slim aesthetic monitor, IPS panel.', $amzImages['asus_monitor']],
            ['ThinkVision P27h-30', 36999, 'Professional monitor, QHD IPS panel, USB-C daisy chain.', $amzImages['asus_monitor']]
        ],
        'Computer Accessories' => [
            ['Lenovo Wireless Compact Mouse', 599, 'Ergonomic ambidextrous office mouse, portable travel design.', $amzImages['zeb_keyboard']],
            ['Lenovo Legion Gaming Bag', 1999, 'Heavy duty water-resistant protective laptop backpack.', $amzImages['lenovo_ideapad']]
        ]
    ];

    buildCatalogBrandItems($catalog, $globalIndex, 'Lenovo', $lenovoData, 55);

    return $catalog;
}

/**
 * Programmatic expansion helper using Amazon product images
 */
function buildCatalogBrandItems(&$catalog, &$globalIndex, $brand, $categoryDefinitions, $targetCount) {
    $brandSeeded = 0;
    $variantsList = [
        'Color' => ['Midnight Black', 'Platinum Silver', 'Deep Navy Blue', 'Titanium Gray', 'Forest Green', 'Sunset Gold', 'Sakura Pink', 'Frost White'],
        'Storage' => ['128GB', '256GB', '512GB', '1TB'],
        'RAM' => ['8GB RAM', '12GB RAM', '16GB RAM', '32GB RAM'],
        'Size' => ['Standard Edition', 'Pro Edition', 'Ultra Edition', 'Max Edition']
    ];

    // Keep generating variants from base products until targetCount is met
    while ($brandSeeded < $targetCount) {
        foreach ($categoryDefinitions as $category => $items) {
            foreach ($items as $item) {
                if ($brandSeeded >= $targetCount) {
                    break 3;
                }

                $baseName = $item[0];
                $basePrice = $item[1];
                $baseDesc = $item[2];
                $imageUrl = $item[3];

                // Generate unique variant values based on index
                $color = $variantsList['Color'][$brandSeeded % count($variantsList['Color'])];
                
                // For smartphones/tablets, use storage. For laptops/desktops, use RAM. For others, use Size.
                $spec = '';
                if (in_array($category, ['Smartphones', 'Tablets'])) {
                    $spec = $variantsList['Storage'][floor($brandSeeded / 2) % count($variantsList['Storage'])];
                } elseif (in_array($category, ['Laptops', 'Desktop Computers'])) {
                    $spec = $variantsList['RAM'][floor($brandSeeded / 2) % count($variantsList['RAM'])];
                } else {
                    $spec = $variantsList['Size'][floor($brandSeeded / 2) % count($variantsList['Size'])];
                }

                $vName = "{$color}, {$spec}";

                // Determine prices for variants realistically
                $priceMultiplier = 1.0 + (($brandSeeded % 4) * 0.15);
                $origPrice = floor($basePrice * $priceMultiplier);
                $discount = 5 + (($globalIndex * 7) % 25);
                $finalPrice = floor($origPrice * (1 - ($discount / 100)));

                $skuPrefix = strtoupper(substr($brand, 0, 3));
                $sku = "{$skuPrefix}-" . str_pad($globalIndex, 6, '0', STR_PAD_LEFT);

                $title = "{$baseName} ({$vName})";
                $desc = "{$baseDesc} (Variant: {$vName}). Genuine {$brand} product sourced directly as featured on Amazon store listings with original brand warranty and platform support.";
                
                $rating = number_format(3.8 + (($globalIndex * 13) % 13) / 10.0, 2);
                if ($rating > 5.0) $rating = 5.00;
                $rcount = 10 + (($globalIndex * 37) % 900);

                $spotlight = ($globalIndex % 8 === 0) ? 'BESTSELLER' : (($globalIndex % 5 === 0) ? 'FEATURED' : 'REGULAR');

                $catalog[] = [
                    'sku' => $sku,
                    'brand' => $brand,
                    'category' => $category,
                    'title' => $title,
                    'description' => $desc,
                    'price' => $finalPrice,
                    'original_price' => $origPrice,
                    'discount' => $discount,
                    'stock' => rand(5, 120),
                    'spotlight' => $spotlight,
                    'rating' => $rating,
                    'rating_count' => $rcount,
                    'image_url' => $imageUrl
                ];

                $globalIndex++;
                $brandSeeded++;
            }
        }
    }
}

// 5. Connect and Execute on MySQL
try {
    $mysqlPdo = new PDO("mysql:host=localhost;dbname=vyapar_setu;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    runSeedingForPdo($mysqlPdo, 'MySQL');
} catch (Exception $e) {
    echo "MySQL Database Connection Skipped or Failed: " . $e->getMessage() . "\n";
}

// 6. Connect and Execute on SQLite
try {
    $sqlitePath = BASE_PATH . '/config/vyapar_setu.sqlite';
    if (file_exists($sqlitePath)) {
        $sqlitePdo = new PDO("sqlite:$sqlitePath");
        $sqlitePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        runSeedingForPdo($sqlitePdo, 'SQLite');
    } else {
        echo "SQLite Database File not found at $sqlitePath\n";
    }
} catch (Exception $e) {
    echo "SQLite Database Seeding Failed: " . $e->getMessage() . "\n";
}

echo "\nAmazon Products & Images Seeder execution completed successfully!\n";
