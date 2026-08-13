<?php
require_once __DIR__ . '/includes/db.php';

$db = getDBConnection();
echo "Setting up category-specific vendor accounts and passwords...\n";

$passHash = password_hash('vendor123', PASSWORD_BCRYPT);

$vendorAccounts = [
    [
        'id' => 1,
        'name' => 'TechCraft Electronics Owner',
        'email' => 'contact@techcraft.in',
        'phone' => '9820011223',
        'store_name' => 'TechCraft Electronics',
        'category' => 'Electronics'
    ],
    [
        'id' => 2,
        'name' => 'Bharat Handloom Textiles Owner',
        'email' => 'sales@bharattextiles.in',
        'phone' => '9830022334',
        'store_name' => 'Bharat Handloom Textiles',
        'category' => 'Fashion'
    ],
    [
        'id' => 3,
        'name' => 'Swadeshi Organic Spices Owner',
        'email' => 'info@swadeshispices.org',
        'phone' => '9840033445',
        'store_name' => 'Swadeshi Organic Spices',
        'category' => 'Spices & Organics'
    ],
    [
        'id' => 4,
        'name' => 'Jaipur Royal Crafts Owner',
        'email' => 'raj@jaipurcrafts.com',
        'phone' => '9850044556',
        'store_name' => 'Jaipur Royal Crafts',
        'category' => 'Handicrafts'
    ],
    [
        'id' => 5,
        'name' => 'GreenTerra Organics Owner',
        'email' => 'care@greenterra.in',
        'phone' => '9860055667',
        'store_name' => 'GreenTerra Organics',
        'category' => 'Beauty & Herbal Wellness'
    ],
    [
        'id' => 6,
        'name' => 'Kashmir Valley Crafts Owner',
        'email' => 'tariq@kashmircrafts.org',
        'phone' => '9870066778',
        'store_name' => 'Kashmir Valley Crafts',
        'category' => 'Home & Kitchen'
    ],
    [
        'id' => 7,
        'name' => 'DesiLeather Crafters Owner',
        'email' => 'suresh@desileather.in',
        'phone' => '9880077889',
        'store_name' => 'DesiLeather Crafters',
        'category' => 'Footwear & Leather Goods'
    ],
    [
        'id' => 8,
        'name' => 'Nalanda Academic Press Owner',
        'email' => 'editor@nalandapress.org',
        'phone' => '9890088990',
        'store_name' => 'Nalanda Academic Press',
        'category' => 'Books & Stationery'
    ]
];

foreach ($vendorAccounts as $acc) {
    // Check if user exists in users table
    $stmt = $db->prepare("SELECT id FROM users WHERE LOWER(email) = ?");
    $stmt->execute([strtolower($acc['email'])]);
    $existingUser = $stmt->fetch();
    
    if (!$existingUser) {
        $uIns = $db->prepare("INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES (?, ?, ?, ?, 'vendor', 'ACTIVE', 1)");
        $uIns->execute([$acc['name'], strtolower($acc['email']), $acc['phone'], $passHash]);
        $userId = $db->lastInsertId();
    } else {
        $userId = $existingUser['id'];
        $uUp = $db->prepare("UPDATE users SET password_hash = ?, role = 'vendor', status = 'ACTIVE' WHERE id = ?");
        $uUp->execute([$passHash, $userId]);
    }
    
    // Update or insert into vendors table
    $vCheck = $db->prepare("SELECT id FROM vendors WHERE id = ? OR LOWER(email) = ?");
    $vCheck->execute([$acc['id'], strtolower($acc['email'])]);
    $existingVendor = $vCheck->fetch();
    
    if ($existingVendor) {
        $vUp = $db->prepare("UPDATE vendors SET user_id = ?, store_name = ?, owner_name = ?, email = ?, phone = ?, category = ?, status = 'APPROVED' WHERE id = ?");
        $vUp->execute([$userId, $acc['store_name'], $acc['name'], strtolower($acc['email']), $acc['phone'], $acc['category'], $existingVendor['id']]);
    } else {
        $vIns = $db->prepare("INSERT INTO vendors (id, user_id, vendor_code, store_name, owner_name, email, phone, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'APPROVED')");
        $code = "VND-" . str_pad($acc['id'], 3, '0', STR_PAD_LEFT);
        $vIns->execute([$acc['id'], $userId, $code, $acc['store_name'], $acc['name'], strtolower($acc['email']), $acc['phone'], $acc['category']]);
    }
    
    // Ensure products assigned to category match vendor_id
    $catStmt = $db->prepare("SELECT id FROM categories WHERE name LIKE ?");
    $catStmt->execute(['%' . explode('&', $acc['category'])[0] . '%']);
    $cat = $catStmt->fetch();
    if ($cat) {
        $pUp = $db->prepare("UPDATE products SET vendor_id = ? WHERE category_id = ?");
        $pUp->execute([$acc['id'], $cat['id']]);
    }
    
    echo " -> Configured Vendor ID {$acc['id']}: {$acc['store_name']} ({$acc['category']}) -> Email: {$acc['email']}\n";
}

echo "All 8 category-specific vendor credentials setup complete!\n";
