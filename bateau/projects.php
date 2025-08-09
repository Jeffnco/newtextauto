<?php
session_start();
require_once __DIR__ . '/autoload.php';

use ContentFactory\Models\Project;
use ContentFactory\Models\Article;

// Vérifier la connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$projectModel = new Project();
$articleModel = new Article();
$message = '';
$error = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $data = [
                    'Projet' => $_POST['projet'] ?? '',
                    'site' => $_POST['site'] ?? '',
                    'mdp_app_wp' => $_POST['mdp_app_wp'] ?? '',
                    'publish_count' => intval($_POST['publish_count'] ?? 1),
                    'publish_period' => $_POST['publish_period'] ?? 'day',
                    'publish_time' => $_POST['publish_time'] ?? '09:00'
                ];
                
                if ($projectModel->create($data)) {
                    $message = 'Projet créé avec succès !';
                } else {
                    $error = 'Erreur lors de la création du projet';
                }
                break;
                
            case 'update':
                $id = $_POST['id'] ?? '';
                $data = [
                    'site' => $_POST['site'] ?? '',
                    'mdp_app_wp' => $_POST['mdp_app_wp'] ?? '',
                    'publish_count' => intval($_POST['publish_count'] ?? 1),
                    'publish_period' => $_POST['publish_period'] ?? 'day',
                    'publish_time' => $_POST['publish_time'] ?? '09:00'
                ];
                
                if ($projectModel->update($id, $data)) {
                    $message = 'Projet mis à jour avec succès !';
                } else {
                    $error = 'Erreur lors de la mise à jour du projet';
                }
                break;
        }
    }
}

try {
    $projects = $projectModel->getAll();
    
    // Compter les articles par projet
    $projectStats = [];
    foreach ($projects as $project) {
        $articles = $articleModel->getAll(['Projets' => $project['Projet']]);
        $projectStats[$project['Projet']] = [
            'total' => count($articles),
            'published' => count(array_filter($articles, fn($a) => ($a['published_status'] ?? '') === 'publié')),
            'pending' => count(array_filter($articles, fn($a) => ($a['published_status'] ?? '') === 'en attente'))
        ];
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Content Factory</title>
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
                        <h2 class="text-xl font-semibold text-gray-800">Gestion des Projets</h2>
                        <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Nouveau Projet
                        </button>
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

                    <!-- Projects Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if (empty($projects)): ?>
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-folder text-6xl text-gray-300 mb-4"></i>
                                <p class="text-xl font-medium text-gray-500">Aucun projet trouvé</p>
                                <p class="text-gray-400 mb-6">Créez votre premier projet pour commencer</p>
                                <button onclick="openCreateModal()" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Créer un projet
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($projects as $project): ?>
                                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                                <i class="fas fa-folder text-xl"></i>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button onclick="editProject(<?= htmlspecialchars(json_encode($project)) ?>)" 
                                                        class="text-blue-600 hover:text-blue-800" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 cursor-pointer hover:text-blue-600" 
                                            onclick="viewProject('<?= htmlspecialchars($project['Projet']) ?>')">
                                            <?= htmlspecialchars($project['Projet'] ?? 'Sans nom') ?>
                                        </h3>
                                        
                                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                                            <?php if (!empty($project['site'])): ?>
                                                <div class="flex items-center">
                                                    <i class="fas fa-globe w-4 h-4 mr-2"></i>
                                                    <span><?= htmlspecialchars($project['site']) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($project['publish_count']) && !empty($project['publish_period'])): ?>
                                                <div class="flex items-center">
                                                    <i class="fas fa-clock w-4 h-4 mr-2"></i>
                                                    <span><?= $project['publish_count'] ?>/<?= $project['publish_period'] ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar w-4 h-4 mr-2"></i>
                                                <span>Créé le <?= date('d/m/Y', strtotime($project['CreatedAt'] ?? 'now')) ?></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Statistiques des articles -->
                                        <?php $stats = $projectStats[$project['Projet']] ?? ['total' => 0, 'published' => 0, 'pending' => 0]; ?>
                                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                            <div class="bg-blue-50 p-2 rounded">
                                                <div class="font-semibold text-blue-600"><?= $stats['total'] ?></div>
                                                <div class="text-gray-600">Total</div>
                                            </div>
                                            <div class="bg-green-50 p-2 rounded">
                                                <div class="font-semibold text-green-600"><?= $stats['published'] ?></div>
                                                <div class="text-gray-600">Publiés</div>
                                            </div>
                                            <div class="bg-yellow-50 p-2 rounded">
                                                <div class="font-semibold text-yellow-600"><?= $stats['pending'] ?></div>
                                                <div class="text-gray-600">En attente</div>
                                            </div>
                                        </div>
                                        
                                        <button onclick="viewProject('<?= htmlspecialchars($project['Projet']) ?>')" 
                                                class="w-full mt-4 bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Voir les articles
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Créer/Modifier Projet -->
    <div id="projectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Nouveau Projet</h3>
                </div>
                
                <form id="projectForm" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="projectId">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du projet</label>
                        <input type="text" name="projet" id="projet" required 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site (sans https, que le domaine)</label>
                        <input type="text" name="site" id="site" required 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mot de passe application WordPress (base64)
                            <a href="https://www.base64encode.org/fr/" target="_blank" class="text-blue-600 text-xs">Base64 encoder</a>
                        </label>
                        <input type="password" name="mdp_app_wp" id="mdp_app_wp" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de publications</label>
                            <input type="number" name="publish_count" id="publish_count" min="1" value="1" required 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                            <select name="publish_period" id="publish_period" required 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="day">Jour</option>
                                <option value="week">Semaine</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Heure de publication</label>
                        <input type="time" name="publish_time" id="publish_time" value="09:00" required 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <span id="submitText">Créer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Nouveau Projet';
            document.getElementById('formAction').value = 'create';
            document.getElementById('submitText').textContent = 'Créer';
            document.getElementById('projectForm').reset();
            document.getElementById('projectId').value = '';
            document.getElementById('projet').disabled = false;
            document.getElementById('projectModal').classList.remove('hidden');
        }

        function editProject(project) {
            document.getElementById('modalTitle').textContent = 'Modifier le Projet';
            document.getElementById('formAction').value = 'update';
            document.getElementById('submitText').textContent = 'Modifier';
            document.getElementById('projectId').value = project.Id;
            document.getElementById('projet').value = project.Projet;
            document.getElementById('projet').disabled = true;
            document.getElementById('site').value = project.site || '';
            document.getElementById('mdp_app_wp').value = project.mdp_app_wp || '';
            document.getElementById('publish_count').value = project.publish_count || 1;
            document.getElementById('publish_period').value = project.publish_period || 'day';
            document.getElementById('publish_time').value = project.publish_time || '09:00';
            document.getElementById('projectModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('projectModal').classList.add('hidden');
        }

        function viewProject(projectName) {
            window.location.href = 'project-detail.php?project=' + encodeURIComponent(projectName);
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('projectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>