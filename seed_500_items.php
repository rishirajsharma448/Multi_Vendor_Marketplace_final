<?php
/* Vyapar Setu - 550+ Product Catalog Data Seeder
   Executes seeding of 550+ realistic products across 8 categories & 8 SME vendors.
*/
require_once __DIR__ . '/config/db.php';

$db = getDB();
echo "Starting database seeding for 550+ catalog items...\n";

// Clear existing items
$db->exec("DELETE FROM products");

// Categories & Unsplash Image Libraries
$categoryData = [
  'Electronics' => [
    'titles' => [
      'SonicPods ANC Wireless Earbuds', 'SmartFit OLED Smartwatch', 'Ultra HD 4K Streaming Webcam', 
      'Ergonomic Mechanical RGB Keyboard', 'ProGamer Precision Wireless Mouse', 'ThunderBass Portable Bluetooth Speaker',
      'Dual-Band Wi-Fi 6 Router', 'FastCharge 20000mAh Power Bank', 'Noise-Cancelling Studio Headphones',
      'Smart LED Desk Lamp with Wireless Charging', 'MagSafe Magnetic Wireless Charger', '4K USB-C Multi-Port Hub'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 1,
    'vendor_name' => 'TechCraft Electronics',
    'base_price' => 1299
  ],
  'Fashion' => [
    'titles' => [
      'Handcrafted Pure Mulberry Silk Banarasi Saree', 'Breathable Organic Cotton Chanderi Kurta Set',
      'Handblocked Indigo Dabu Cotton Dupatta', 'Pure Pashmina Kashmiri Embroidered Shawl',
      'Handwoven Chanderi Silk Cotton Saree', 'Men Ethnic Khadi Cotton Nehru Jacket',
      'Designer Bandhani Silk Dupatta', 'Traditional Chikankari Hand Embroidered Kurti',
      'Pure Georgette Anarkali Suit Set', 'Handloom Cotton Casual Shirt'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 2,
    'vendor_name' => 'Bharat Handloom Textiles',
    'base_price' => 950
  ],
  'Spices & Organics' => [
    'titles' => [
      'Organic Malabar Black Pepper Whole (500g)', 'Kashmiri Grade-1 Mongra Saffron Kesar (2g)',
      'Pure Organic Lakadong Turmeric Powder (250g)', 'Single Origin Organic Wayanad Cardamom (100g)',
      'Hand-Pounded Organic Red Chili Powder (500g)', 'Pure Himalayan Raw Forest Honey (500g)',
      'Organic Cold-Pressed Mustard Oil (1L)', 'Traditional Organic A2 Desi Cow Ghee (500ml)',
      'Raw Organic Wild Cumin Seeds (250g)', 'Pure Organic Cloves Whole (100g)'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 3,
    'vendor_name' => 'Swadeshi Organic Spices',
    'base_price' => 350
  ],
  'Handicrafts' => [
    'titles' => [
      'Hand-Carved Solid Brass Peacock Diya Idol', 'Handpainted Terracotta Chai Kulhad Set (Set of 6)',
      'Rajasthani Royal Blue Pottery Flower Vase', 'Brass Antique Ganesha Oil Lamp Statuette',
      'Traditional Handpainted Marble Elephant Figurine', 'Handcrafted Dhokra Art Brass Wall Hanging',
      'Wooden Carved Jharokha Mirror Frame', 'Hand-woven Bamboo Table Lamp'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1605648916361-9bc12ad6a569?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 4,
    'vendor_name' => 'Jaipur Royal Crafts',
    'base_price' => 590
  ],
  'Beauty & Herbal Wellness' => [
    'titles' => [
      'Herbal Neem & Kumkumadi Saffron Face Cleanser (200ml)', 'Organic Cold-Pressed Extra Virgin Coconut Oil (500ml)',
      'Pure Ayurvedic Bhringraj Hair Growth Oil (200ml)', 'Natural Rose Water & Sandalwood Toner Spray',
      'Organic Aloe Vera & Tea Tree Skin Gel', 'Ayurvedic Ubtan Radiance Body Scrub (200g)',
      'Herbal Vetiver & Saffron Bath Soap Set', 'Organic Almond Moisturizing Face Lotion'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1608248597261-26c7e3fef57c?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 5,
    'vendor_name' => 'GreenTerra Organics',
    'base_price' => 280
  ],
  'Home & Kitchen' => [
    'titles' => [
      'Hand-Carved Kashmir Walnut Wood Dry Fruit Bowl', 'Pure Ayurvedic Copper Water Jug Set (1.5L + 2 Tumblers)',
      'Traditional Cast Iron Pre-Seasoned Kadhai (10 Inch)', 'Handwoven Natural Jute Floor Runner Rug',
      'Brass Handcrafted Spice Box Masala Dabba', 'Artisan Ceramic Handpainted Dinnerware Bowls (Set of 4)',
      'Terracotta Earthen Water Pot Storage Dispenser', 'Hand-stitched Cotton Table Mat Set'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 6,
    'vendor_name' => 'Kashmir Valley Crafts',
    'base_price' => 890
  ],
  'Footwear & Leather Goods' => [
    'titles' => [
      'Handcrafted Genuine Leather Kolhapuri Chappals', 'Vintage Handcrafted Leather Messenger Laptop Bag (15.6")',
      'Genuine Full Grain Leather Bifold Men Wallet', 'Handmade Ethnic Mojari Jutti Shoes',
      'Genuine Leather Unisex Travel Duffle Bag', 'Hand-stitched Leather Casual Loafers',
      'Vintage Leather Passport Cover & Card Holder', 'Women Handcrafted Embroidered Leather Mojaris'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=600&q=80',
      'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 7,
    'vendor_name' => 'DesiLeather Crafters',
    'base_price' => 1199
  ],
  'Books & Stationery' => [
    'titles' => [
      'Handmade Recycled Cotton Paper Journal & Quill Pen Set', 'Vintage Leather-bound Antique Recipe Diary',
      'Eco-Friendly Plantable Seed Paper Notebook Set', 'Artisan Brass Calligraphy Pen Set with Ink',
      'Handmade Botanical Pressed Flower Bookmark Set', 'Handwoven Fabric Hardcover Daily Planner 2026'
    ],
    'images' => [
      'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80'
    ],
    'vendor_id' => 8,
    'vendor_name' => 'Nalanda Academic Press',
    'base_price' => 499
  ]
];

$stmt = $db->prepare("INSERT INTO products (sku, vendor_id, category_name, title, description, price, original_price, stock, image_url, rating, rating_count, spotlight, status) 
                      VALUES (:sku, :vendor_id, :cat, :title, :desc, :price, :orig_price, :stock, :image, :rating, :rcount, :spot, 'ACTIVE')");

$count = 0;
$targetTotal = 560;

while ($count < $targetTotal) {
    foreach ($categoryData as $catName => $catInfo) {
        if ($count >= $targetTotal) break;
        $count++;

        $titleBase = $catInfo['titles'][$count % count($catInfo['titles'])];
        $title = $titleBase . " - Edition #" . (floor($count / count($catInfo['titles'])) + 1);
        $sku = strtoupper(substr($catName, 0, 2)) . "-ITEM-" . str_pad($count, 4, '0', STR_PAD_LEFT);
        $price = $catInfo['base_price'] + (($count * 45) % 8500);
        $origPrice = floor($price * (1.2 + (rand(1, 4) * 0.1)));
        $stock = rand(5, 120);
        $rating = 4.0 + (rand(0, 10) / 10.0);
        $rcount = rand(15, 650);
        $img = $catInfo['images'][$count % count($catInfo['images'])];
        $spot = ($count % 5 === 0) ? 'BESTSELLER' : (($count % 3 === 0) ? 'FEATURED' : 'REGULAR');
        $desc = "Certified high quality $titleBase manufactured by master SME artisans under Vyapar Setu platform. Features genuine material, top craftsmanship, and 100% quality guarantee.";

        $stmt->execute([
            ':sku' => $sku,
            ':vendor_id' => $catInfo['vendor_id'],
            ':cat' => $catName,
            ':title' => $title,
            ':desc' => $desc,
            ':price' => $price,
            ':orig_price' => $origPrice,
            ':stock' => $stock,
            ':image' => $img,
            ':rating' => $rating,
            ':rcount' => $rcount,
            ':spot' => $spot
        ]);
    }
}

// Log Seeding
$log = $db->prepare("INSERT INTO audit_logs (action_description) VALUES (:act)");
$log->execute([':act' => "Successfully seeded $count products across 8 categories & 8 SME stores into database."]);

echo "Successfully seeded $count catalog items into database!\n";
