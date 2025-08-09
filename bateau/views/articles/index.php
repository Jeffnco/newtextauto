<?php 
$title = 'Articles - Content Factory';
$page_title = 'Gestion des Articles';
ob_start(); 
?>

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
        <h1 class="text-2xl font-bold text-gray-900">Articles</h1>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
            <?= count($articles) ?> articles
        </span>
    </div>
    
    <div class="flex items-center space-x-3">
        <a href="/articles/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nouvel Article
        </a>
        <a href="/keyword-research" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center">
            <i class="fas fa-search mr-2"></i>
            Générer des Idées
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6 p-6">
    <form method="GET" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-gray-700 mb-1">Projet</label>
            <select name="project" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les projets</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?= htmlspecialchars($project['Projet']) ?>" <?= ($_GET['project'] ?? '') === $project['Projet'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($project['Projet']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="brouillon" <?= ($_GET['status'] ?? '') === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                <option value="en attente" <?= ($_GET['status'] ?? '') === 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="publié" <?= ($_GET['status'] ?? '') === 'publié' ? 'selected' : '' ?>>Publié</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($articles)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                            <p class="text-lg font-medium">Aucun article trouvé</p>
                            <p class="text-sm">Commencez par créer votre premier article</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="<?= $article['Id'] ?>">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="/articles/<?= $article['Id'] ?>" class="hover:text-blue-600">
                                                <?= htmlspecialchars($article['Final_Title'] ?? 'Sans titre') ?>
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars(substr($article['Meta_description'] ?? '', 0, 100)) ?>...
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    <?= htmlspecialchars($article['Projets'] ?? 'Aucun') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $status = $article['published_status'] ?? 'brouillon';
                                $statusClasses = [
                                    'brouillon' => 'bg-gray-100 text-gray-800',
                                    'en attente' => 'bg-yellow-100 text-yellow-800',
                                    'publié' => 'bg-green-100 text-green-800'
                                ];
                                ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusClasses[$status] ?? $statusClasses['brouillon'] ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($article['CreatedAt'] ?? 'now')) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="/articles/<?= $article['Id'] ?>" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="publishArticle('<?= $article['Id'] ?>')" class="text-green-600 hover:text-green-900" title="Publier">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                    <button onclick="deleteArticle('<?= $article['Id'] ?>')" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function publishArticle(id) {
    if (confirm('Êtes-vous sûr de vouloir publier cet article ?')) {
        // Logique de publication
        console.log('Publishing article:', id);
    }
}

function deleteArticle(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        window.location.href = `/articles/${id}/delete`;
    }
}
</script>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>