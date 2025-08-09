<?php
// Test ultra simple pour vérifier PHP
echo "<h1>Test PHP Simple</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";

// Test des fichiers de config
if (file_exists('config/database.php')) {
    echo "<p>✅ config/database.php existe</p>";
    $config = require 'config/database.php';
    echo "<p>Config chargée: " . print_r($config['nocodb'], true) . "</p>";
} else {
    echo "<p>❌ config/database.php manquant</p>";
}

// Test Composer
if (file_exists('vendor/autoload.php')) {
    echo "<p>✅ Composer installé</p>";
} else {
    echo "<p>❌ Composer manquant - Exécutez: composer install</p>";
}

echo "<hr>";
echo "<p><a href='debug.php'>🔍 Debug complet</a></p>";
echo "<p><a href='index.php'>🏠 Retour à l'accueil</a></p>";
?>