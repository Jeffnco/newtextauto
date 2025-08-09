<?php
session_start();
require_once __DIR__ . '/autoload.php';

use ContentFactory\Models\Article;
use ContentFactory\Models\Project;

// Vérifier la connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $articleModel = new Article();
    $projectModel = new Project();
    
    $allArticles = $articleModel->getAll();
    $publishedArticles = $articleModel->getAll(['published_status' => 'publié']);
    $projects = $projectModel->getAll();
    $pendingArticles = $articleModel->getAll(['published_status' => 'en attente']);
    
    $stats = [
        'total_articles' => count($allArticles),
        'published_articles' => count($publishedArticles),
        'total_projects' => count($projects),
        'pending_articles' => count($pendingArticles)
    ];
    
    $recent_articles = array_slice($allArticles, 0, 5);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Content Factory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-white w-64 shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-rocket text-blue-600"></i>
                    Content Factory
                </h1>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Principal</p>
                </div>
                
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 border-r-2 border-blue-600">
                    <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <a href="articles.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                    Articles
                </a>
                
                <a href="projects.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-folder w-5 h-5 mr-3"></i>
                    Projets
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['user_email'] ?? 'Utilisateur') ?></p>
                        <a href="logout.php" class="text-xs text-gray-500 hover:text-red-600">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <?php if (isset($error)): ?>
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Erreur: <?= htmlspecialchars($error) ?>
                        </div>
                    <?php else: ?>
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-file-alt text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Total Articles</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_articles'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Articles Publiés</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['published_articles'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                        <i class="fas fa-folder text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Projets Actifs</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_projects'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                        <i class="fas fa-clock text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">En Attente</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['pending_articles'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions & Recent Articles -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2">
                                <div class="bg-white rounded-lg shadow">
                                    <div class="px-6 py-4 border-b border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900">Articles Récents</h3>
                                    </div>
                                    <div class="p-6">
                                        <?php if (empty($recent_articles)): ?>
                                            <p class="text-gray-500 text-center py-8">Aucun article trouvé</p>
                                        <?php else: ?>
                                            <div class="space-y-4">
                                                <?php foreach ($recent_articles as $article): ?>
                                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                        <div class="flex-1">
                                                            <h4 class="font-medium text-gray-900"><?= htmlspecialchars($article['Final_Title'] ?? 'Sans titre') ?></h4>
                                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($article['Projets'] ?? 'Aucun projet') ?></p>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full <?= 
                                                                ($article['published_status'] ?? '') === 'publié' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' 
                                                            ?>">
                                                                <?= htmlspecialchars($article['published_status'] ?? 'Brouillon') ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="bg-white rounded-lg shadow p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
                                    <div class="space-y-3">
                                        <a href="articles.php" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            Voir les Articles
                                        </a>
                                        <a href="projects.php" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors flex items-center justify-center">
                                            <i class="fas fa-folder mr-2"></i>
                                            Gérer les Projets
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>