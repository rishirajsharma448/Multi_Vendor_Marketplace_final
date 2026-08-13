<?php
/**
 * Script to delete all products and dependent records from MySQL and SQLite databases.
 */

define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/includes/db.php';

echo "Purging all products from databases...\n";

function purgeProductsFromPdo($pdo, $dbName) {
    if ($dbName === 'MySQL') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $pdo->exec("TRUNCATE TABLE product_images;");
        $pdo->exec("TRUNCATE TABLE reviews;");
        $pdo->exec("TRUNCATE TABLE wishlist;");
        $pdo->exec("TRUNCATE TABLE cart_items;");
        $pdo->exec("TRUNCATE TABLE order_items;");
        $pdo->exec("TRUNCATE TABLE products;");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } else {
        $pdo->exec("PRAGMA foreign_keys = OFF;");
        $pdo->exec("DELETE FROM product_images;");
        $pdo->exec("DELETE FROM reviews;");
        $pdo->exec("DELETE FROM wishlist;");
        $pdo->exec("DELETE FROM cart_items;");
        $pdo->exec("DELETE FROM order_items;");
        $pdo->exec("DELETE FROM products;");
        $pdo->exec("PRAGMA foreign_keys = ON;");
    }

    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $count = $stmt->fetchColumn();
    echo "Product count in $dbName database after purge: $count\n";
}

// MySQL Purge
try {
    $mysqlPdo = new PDO("mysql:host=localhost;dbname=vyapar_setu;charset=utf8mb4", "root", "");
    purgeProductsFromPdo($mysqlPdo, 'MySQL');
} catch (Exception $e) {
    echo "MySQL Error: " . $e->getMessage() . "\n";
}

// SQLite Purge
try {
    $sqlitePdo = new PDO("sqlite:" . BASE_PATH . "/config/vyapar_setu.sqlite");
    purgeProductsFromPdo($sqlitePdo, 'SQLite');
} catch (Exception $e) {
    echo "SQLite Error: " . $e->getMessage() . "\n";
}

echo "All products removed successfully!\n";
