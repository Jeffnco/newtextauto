<?php
// Test direct sans .htaccess
echo "<h1>🚀 Test Direct</h1>";

// Test autoloader
require_once __DIR__ . '/autoload.php';

echo "<h2>Test des contrôleurs directement</h2>";

try {
    echo "<h3>Test Dashboard</h3>";
    $controller = new ContentFactory\Controllers\DashboardController();
    $controller->index();
} catch (Exception $e) {
    echo "❌ Erreur Dashboard: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}
?>