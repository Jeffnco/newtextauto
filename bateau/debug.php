<?php
// Script de debug pour identifier les problèmes

echo "<h1>🔍 Debug Content Factory</h1>";

// 1. Version PHP
echo "<h2>Version PHP</h2>";
echo "Version: " . PHP_VERSION . "<br>";
if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
    echo "✅ PHP OK<br>";
} else {
    echo "❌ PHP trop ancien (requis: 8.0+)<br>";
}

// 2. Extensions PHP
echo "<h2>Extensions PHP</h2>";
$required_extensions = ['curl', 'json', 'session'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext<br>";
    } else {
        echo "❌ $ext manquant<br>";
    }
}

// 3. Fichiers et dossiers
echo "<h2>Structure des fichiers</h2>";
$required_files = [
    'config/database.php',
    'config/webhooks.php',
    'src/Core/Database.php',
    'src/Core/Auth.php',
    'views/layouts/app.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ $file manquant<br>";
    }
}

// 4. Permissions
echo "<h2>Permissions</h2>";
$writable_dirs = ['uploads'];
foreach ($writable_dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "✅ $dir (écriture OK)<br>";
    } else {
        echo "❌ $dir (problème d'écriture)<br>";
    }
}

// 5. Composer
echo "<h2>Composer</h2>";
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer installé<br>";
    require_once 'vendor/autoload.php';
    echo "✅ Autoload fonctionne<br>";
} else {
    echo "❌ Composer non installé - Exécutez: composer install<br>";
}

// 6. Configuration
echo "<h2>Configuration</h2>";
if (file_exists('config/database.php')) {
    $config = require 'config/database.php';
    echo "✅ Config database chargée<br>";
    echo "- Base URL: " . $config['nocodb']['base_url'] . "<br>";
    echo "- Projet: " . $config['nocodb']['project'] . "<br>";
} else {
    echo "❌ config/database.php manquant<br>";
}

// 7. Test de classe
echo "<h2>Test des classes</h2>";
if (file_exists('vendor/autoload.php')) {
    try {
        require_once 'vendor/autoload.php';
        $db = ContentFactory\Core\Database::getInstance();
        echo "✅ Classe Database OK<br>";
    } catch (Exception $e) {
        echo "❌ Erreur Database: " . $e->getMessage() . "<br>";
    }
}

// 8. Test connexion NocoDB
echo "<h2>Test connexion NocoDB</h2>";
if (file_exists('config/database.php')) {
    $config = require 'config/database.php';
    $testUrl = $config['nocodb']['base_url'] . $config['nocodb']['project'] . '/Projets';
    
    $ch = curl_init($testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'xc-token: ' . $config['nocodb']['token']
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ Connexion NocoDB OK (Code: $httpCode)<br>";
    } else {
        echo "❌ Erreur NocoDB (Code: $httpCode)<br>";
        echo "URL testée: $testUrl<br>";
    }
}

echo "<hr>";
echo "<h2>Actions recommandées</h2>";
echo "<ol>";
echo "<li>Si Composer manque: <code>composer install</code></li>";
echo "<li>Vérifier les permissions des dossiers</li>";
echo "<li>Vérifier la configuration NocoDB</li>";
echo "<li>Consulter les logs d'erreur du serveur</li>";
echo "</ol>";
?>