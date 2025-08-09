<?php
// Version simple de la liste des articles
require_once __DIR__ . '/autoload.php';

use ContentFactory\Models\Article;
use ContentFactory\Models\Project;

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Articles - Content Factory</title>
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
</head>
<body class='bg-gray-50 p-8'>";

echo "<div class='max-w-6xl mx-auto'>";
echo "<h1 class='text-3xl font-bold text-gray-900 mb-8'>";
echo "<i class='fas fa-file-alt text-blue-600 mr-3'></i>Gestion des Articles";
echo "</h1>";

// Navigation
echo "<div class='bg-white rounded-lg shadow p-4 mb-8'>";
echo "<nav class='flex space-x-6'>";
echo "<a href='simple-dashboard.php' class='text-gray-600 hover:text-blue-600'>Dashboard</a>";
echo "<a href='simple-articles.php' class='text-blue-600 font-medium'>Articles</a>";
echo "<a href='no-htaccess.php?action=login' class='text-gray-600 hover:text-blue-600'>Connexion</a>";
echo "</nav>";
echo "</div>";

try {
    $articleModel = new Article();
    $projectModel = new Project();
    
    // Filtres
    $projectFilter = $_GET['project'] ?? '';
    $statusFilter = $_GET['status'] ?? '';
    
    $filters = [];
    if (!empty($projectFilter)) $filters['Projets'] = $projectFilter;
    if (!empty($statusFilter)) $filters['published_status'] = $statusFilter;
    
    $articles = $articleModel->getAll($filters);
    $projects = $projectModel->getAll();
    
    // Formulaire de filtres
    echo "<div class='bg-white rounded-lg shadow mb-6 p-6'>";
    echo "<form method='GET' class='flex flex-wrap items-center gap-4'>";
    echo "<div class='flex-1 min-w-64'>";
    echo "<label class='block text-sm font-medium text-gray-700 mb-1'>Projet</label>";
    echo "<select name='project' class='w-full border border-gray-300 rounded-md px-3 py-2'>";
    echo "<option value=''>Tous les projets</option>";
    foreach ($projects as $project) {
        $selected = $projectFilter === $project['Projet'] ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($project['Projet']) . "' $selected>";
        echo htmlspecialchars($project['Projet']);
        echo "</option>";
    }
    echo "</select></div>";
    
    echo "<div class='flex-1 min-w-48'>";
    echo "<label class='block text-sm font-medium text-gray-700 mb-1'>Statut</label>";
    echo "<select name='status' class='w-full border border-gray-300 rounded-md px-3 py-2'>";
    echo "<option value=''>Tous les statuts</option>";
    $statuses = ['brouillon', 'en attente', 'publié'];
    foreach ($statuses as $status) {
        $selected = $statusFilter === $status ? 'selected' : '';
        echo "<option value='$status' $selected>$status</option>";
    }
    echo "</select></div>";
    
    echo "<div class='flex items-end'>";
    echo "<button type='submit' class='bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700'>";
    echo "<i class='fas fa-filter mr-2'></i>Filtrer";
    echo "</button></div>";
    echo "</form></div>";
    
    // Tableau des articles
    echo "<div class='bg-white rounded-lg shadow overflow-hidden'>";
    echo "<div class='overflow-x-auto'>";
    echo "<table class='min-w-full divide-y divide-gray-200'>";
    echo "<thead class='bg-gray-50'>";
    echo "<tr>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase'>Titre</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase'>Projet</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase'>Statut</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase'>Date</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody class='bg-white divide-y divide-gray-200'>";
    
    if (empty($articles)) {
        echo "<tr><td colspan='4' class='px-6 py-12 text-center text-gray-500'>";
        echo "<i class='fas fa-file-alt text-4xl mb-4 text-gray-300'></i>";
        echo "<p class='text-lg font-medium'>Aucun article trouvé</p>";
        echo "</td></tr>";
    } else {
        foreach ($articles as $article) {
            echo "<tr class='hover:bg-gray-50'>";
            
            // Titre
            echo "<td class='px-6 py-4'>";
            echo "<div class='text-sm font-medium text-gray-900'>";
            echo htmlspecialchars($article['Final_Title'] ?? 'Sans titre');
            echo "</div>";
            if (!empty($article['Meta_description'])) {
                echo "<div class='text-sm text-gray-500'>";
                echo htmlspecialchars(substr($article['Meta_description'], 0, 100)) . "...";
                echo "</div>";
            }
            echo "</td>";
            
            // Projet
            echo "<td class='px-6 py-4 whitespace-nowrap'>";
            echo "<span class='px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full'>";
            echo htmlspecialchars($article['Projets'] ?? 'Aucun');
            echo "</span></td>";
            
            // Statut
            echo "<td class='px-6 py-4 whitespace-nowrap'>";
            $status = $article['published_status'] ?? 'brouillon';
            $statusClasses = [
                'brouillon' => 'bg-gray-100 text-gray-800',
                'en attente' => 'bg-yellow-100 text-yellow-800',
                'publié' => 'bg-green-100 text-green-800'
            ];
            $statusClass = $statusClasses[$status] ?? $statusClasses['brouillon'];
            echo "<span class='px-2 py-1 text-xs font-medium rounded-full $statusClass'>";
            echo htmlspecialchars($status);
            echo "</span></td>";
            
            // Date
            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>";
            echo date('d/m/Y', strtotime($article['CreatedAt'] ?? 'now'));
            echo "</td>";
            
            echo "</tr>";
        }
    }
    
    echo "</tbody></table>";
    echo "</div></div>";
    
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>";
    echo "<strong>Erreur:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "</div>";
echo "</body></html>";
?>