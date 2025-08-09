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

$articleId = $_GET['id'] ?? '';
if (empty($articleId)) {
    header('Location: articles.php');
    exit;
}

$message = '';
$error = '';

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $articleModel = new Article();
        $data = [
            'Final_Title' => $_POST['title'] ?? '',
            'Meta_description' => $_POST['meta_description'] ?? '',
            'final_article' => $_POST['content'] ?? '',
            'Projets' => $_POST['project'] ?? '',
            'key_takeaways' => $_POST['key_takeaways'] ?? '',
            'image_prompt' => $_POST['image_prompt'] ?? ''
        ];
        
        if ($articleModel->update($articleId, $data)) {
            $message = 'Article mis à jour avec succès !';
        } else {
            $error = 'Erreur lors de la mise à jour de l\'article';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

try {
    $articleModel = new Article();
    $projectModel = new Project();
    
    $article = $articleModel->getById($articleId);
    if (!$article) {
        header('Location: articles.php');
        exit;
    }
    
    $projects = $projectModel->getAll();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['Final_Title'] ?? 'Article') ?> - Content Factory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.7.0/tinymce.min.js"></script>
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
                
                <a href="articles.php" class="flex items-center px-6 py-3 text-blue-600 bg-blue-50 border-r-2 border-blue-600">
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="javascript:history.back()" class="text-gray-400 hover:text-gray-600 mr-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h2 class="text-xl font-semibold text-gray-800">Modifier l'article</h2>
                        </div>
                        <div class="flex items-center space-x-3">
                            <?php 
                            $status = $article['published_status'] ?? 'brouillon';
                            $statusClasses = [
                                'brouillon' => 'bg-gray-100 text-gray-800',
                                'en attente' => 'bg-yellow-100 text-yellow-800',
                                'publié' => 'bg-green-100 text-green-800'
                            ];
                            $statusClass = $statusClasses[$status] ?? $statusClasses['brouillon'];
                            ?>
                            <span class="px-3 py-1 text-sm font-medium rounded-full <?= $statusClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <?php if ($message): ?>
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                    <input type="text" name="title" required 
                                           value="<?= htmlspecialchars($article['Final_Title'] ?? '') ?>"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Projet</label>
                                    <select name="project" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionnez un projet</option>
                                        <?php foreach ($projects as $project): ?>
                                            <option value="<?= htmlspecialchars($project['Projet']) ?>" 
                                                    <?= ($article['Projets'] ?? '') === $project['Projet'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($project['Projet']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta description</label>
                                    <textarea name="meta_description" rows="3" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($article['Meta_description'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Key Takeaways</label>
                                    <textarea name="key_takeaways" rows="4" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($article['key_takeaways'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Prompt</label>
                                    <textarea name="image_prompt" rows="3" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($article['image_prompt'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contenu de l'article</h3>
                            <textarea name="content" id="content" rows="20" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($article['final_article'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="javascript:history.back()" 
                               class="px-6 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialiser TinyMCE
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: 'link image lists code table',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | table | code',
            branding: false,
            forced_root_block: false,
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }'
        });
    </script>
</body>
</html>