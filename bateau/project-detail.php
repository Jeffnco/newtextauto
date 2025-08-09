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

$projectName = $_GET['project'] ?? '';
if (empty($projectName)) {
    header('Location: projects.php');
    exit;
}

try {
    $articleModel = new Article();
    $projectModel = new Project();
    
    // Récupérer les informations du projet
    $projects = $projectModel->getAll();
    $currentProject = null;
    foreach ($projects as $project) {
        if ($project['Projet'] === $projectName) {
            $currentProject = $project;
            break;
        }
    }
    
    if (!$currentProject) {
        header('Location: projects.php');
        exit;
    }
    
    // Filtres
    $statusFilter = $_GET['status'] ?? '';
    
    $filters = ['Projets' => $projectName];
    if (!empty($statusFilter)) {
        $filters['published_status'] = $statusFilter;
    }
    
    $articles = $articleModel->getAll($filters);
    
    // Statistiques
    $stats = [
        'total' => count($articleModel->getAll(['Projets' => $projectName])),
        'published' => count($articleModel->getAll(['Projets' => $projectName, 'published_status' => 'publié'])),
        'pending' => count($articleModel->getAll(['Projets' => $projectName, 'published_status' => 'en attente'])),
        'draft' => count($articleModel->getAll(['Projets' => $projectName, 'published_status' => 'brouillon']))
    ];
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($projectName) ?> - Content Factory</title>
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
                
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <a href="articles.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                    Articles
                </a>
                
                <a href="projects.php" class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 border-r-2 border-blue-600">
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="projects.php" class="text-gray-400 hover:text-gray-600 mr-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($projectName) ?></h2>
                                <p class="text-sm text-gray-600">
                                    <?= !empty($currentProject['site']) ? htmlspecialchars($currentProject['site']) : 'Aucun site configuré' ?>
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            <?= $stats['total'] ?> articles
                        </span>
                    </div>
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
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-file-alt text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Total</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['total'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Publiés</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['published'] ?></p>
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
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['pending'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                                        <i class="fas fa-edit text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Brouillons</p>
                                        <p class="text-2xl font-semibold text-gray-900"><?= $stats['draft'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="bg-white rounded-lg shadow mb-6 p-6">
                            <form method="GET" class="flex items-center gap-4">
                                <input type="hidden" name="project" value="<?= htmlspecialchars($projectName) ?>">
                                
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Tous les statuts</option>
                                        <option value="brouillon" <?= $statusFilter === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                                        <option value="en attente" <?= $statusFilter === 'en attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="publié" <?= $statusFilter === 'publié' ? 'selected' : '' ?>>Publié</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-end">
                                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-filter mr-2"></i>
                                        Filtrer
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Articles Table -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($articles)): ?>
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                                                    <p class="text-lg font-medium">Aucun article trouvé</p>
                                                    <p class="text-sm">Les articles de ce projet apparaîtront ici</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($articles as $article): ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($article['Final_Title'] ?? 'Sans titre') ?>
                                                        </div>
                                                        <?php if (!empty($article['Meta_description'])): ?>
                                                            <div class="text-sm text-gray-500">
                                                                <?= htmlspecialchars(substr($article['Meta_description'], 0, 100)) ?>...
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <?php 
                                                        $status = $article['published_status'] ?? 'brouillon';
                                                        $statusClasses = [
                                                            'brouillon' => 'bg-gray-100 text-gray-800',
                                                            'en attente' => 'bg-yellow-100 text-yellow-800',
                                                            'publié' => 'bg-green-100 text-green-800'
                                                        ];
                                                        $statusClass = $statusClasses[$status] ?? $statusClasses['brouillon'];
                                                        ?>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                                                            <?= htmlspecialchars($status) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= date('d/m/Y', strtotime($article['CreatedAt'] ?? 'now')) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="article-detail.php?id=<?= $article['Id'] ?>" 
                                                           class="text-blue-600 hover:text-blue-900" title="Voir/Modifier">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>