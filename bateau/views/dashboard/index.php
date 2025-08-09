<?php 
$title = 'Dashboard - Content Factory';
$page_title = 'Dashboard';
ob_start(); 
?>

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

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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
                                    <a href="/articles/<?= $article['Id'] ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                <a href="/articles/create" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvel Article
                </a>
                <a href="/keyword-research" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Recherche Mots-clés
                </a>
                <a href="/projects/create" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-folder-plus mr-2"></i>
                    Nouveau Projet
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Activité Récente</h3>
            <div class="space-y-3">
                <div class="flex items-center text-sm">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-3"></div>
                    <span class="text-gray-600">Article publié il y a 2h</span>
                </div>
                <div class="flex items-center text-sm">
                    <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                    <span class="text-gray-600">Nouveau projet créé</span>
                </div>
                <div class="flex items-center text-sm">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                    <span class="text-gray-600">5 idées générées</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>