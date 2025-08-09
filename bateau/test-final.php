<?php
// Test final pour vérifier que tout fonctionne
echo "<h1>🚀 Test Final - Content Factory</h1>";

// Test autoloader
require_once __DIR__ . '/autoload.php';

echo "<h2>Test Autoloader</h2>";
try {
    $db = ContentFactory\Core\Database::getInstance();
    echo "✅ Autoloader fonctionne<br>";
    echo "✅ Classe Database chargée<br>";
} catch (Exception $e) {
    echo "❌ Erreur autoloader: " . $e->getMessage() . "<br>";
}

// Test des contrôleurs
echo "<h2>Test Contrôleurs</h2>";
try {
    $controller = new ContentFactory\Controllers\DashboardController();
    echo "✅ DashboardController OK<br>";
} catch (Exception $e) {
    echo "❌ Erreur DashboardController: " . $e->getMessage() . "<br>";
}

try {
    $controller = new ContentFactory\Controllers\ArticleController();
    echo "✅ ArticleController OK<br>";
} catch (Exception $e) {
    echo "❌ Erreur ArticleController: " . $e->getMessage() . "<br>";
}

// Test connexion NocoDB
echo "<h2>Test NocoDB</h2>";
try {
    $db = ContentFactory\Core\Database::getInstance();
    $result = $db->makeRequest('Projets');
    if ($result['success']) {
        echo "✅ Connexion NocoDB OK<br>";
        echo "✅ Nombre de projets: " . count($result['data']['list'] ?? []) . "<br>";
    } else {
        echo "❌ Erreur NocoDB: " . $result['error'] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur test NocoDB: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>🎯 Liens de test</h2>";
echo "<a href='/' style='display: inline-block; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>🏠 Dashboard</a><br>";
echo "<a href='/articles' style='display: inline-block; padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>📝 Articles</a><br>";
echo "<a href='/auth/login' style='display: inline-block; padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>🔐 Connexion</a><br>";

echo "<hr>";
echo "<p><strong>Si tout est ✅, votre application est prête !</strong></p>";
?>