<?php
// Version sans .htaccess pour tester
require_once __DIR__ . '/autoload.php';

use ContentFactory\Controllers\DashboardController;
use ContentFactory\Controllers\ArticleController;
use ContentFactory\Controllers\AuthController;

// Récupérer l'action depuis l'URL
$action = $_GET['action'] ?? 'dashboard';

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.nav { background: #f0f0f0; padding: 10px; margin-bottom: 20px; }
.nav a { margin-right: 15px; text-decoration: none; color: #333; }
</style>";

echo "<div class='nav'>";
echo "<a href='?action=dashboard'>Dashboard</a>";
echo "<a href='?action=articles'>Articles</a>";
echo "<a href='?action=login'>Connexion</a>";
echo "</div>";

try {
    switch ($action) {
        case 'dashboard':
            $controller = new DashboardController();
            $controller->index();
            break;
            
        case 'articles':
            $controller = new ArticleController();
            $controller->index();
            break;
            
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;
            
        default:
            echo "<h1>Action non trouvée</h1>";
            break;
    }
} catch (Exception $e) {
    echo "<h1>Erreur</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>