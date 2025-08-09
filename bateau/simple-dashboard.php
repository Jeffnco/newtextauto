<?php
// Version ultra simple du dashboard
require_once __DIR__ . '/autoload.php';

use ContentFactory\Models\Article;
use ContentFactory\Models\Project;

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Dashboard - Content Factory</title>
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
</head>
<body class='bg-gray-50 p-8'>";

echo "<div class='max-w-6xl mx-auto'>";
echo "<h1 class='text-3xl font-bold text-gray-900 mb-8'>";
echo "<i class='fas fa-rocket text-blue-600 mr-3'></i>Content Factory Dashboard";
echo "</h1>";

// Navigation
echo "<div class='bg-white rounded-lg shadow p-4 mb-8'>";
echo "<nav class='flex space-x-6'>";
echo "<a href='simple-dashboard.php' class='text-blue-600 font-medium'>Dashboard</a>";
echo "<a href='simple-articles.php' class='text-gray-600 hover:text-blue-600'>Articles</a>";
echo "<a href='no-htaccess.php?action=login' class='text-gray-600 hover:text-blue-600'>Connexion</a>";
echo "</nav>";
echo "</div>";

try {
    // Récupérer les données
    $articleModel = new Article();
    $projectModel = new Project();
    
    $allArticles = $articleModel->getAll();
    $publishedArticles = $articleModel->getAll(['published_status' => 'publié']);
    $projects = $projectModel->getAll();
    
    // Statistiques
    echo "<div class='grid grid-cols-1 md:grid-cols-4 gap-6 mb-8'>";
    
    // Total articles
    echo "<div class='bg-white rounded-lg shadow p-6'>";
    echo "<div class='flex items-center'>";
    echo "<div class='p-3 rounded-full bg-blue-100 text-blue-600'>";
    echo "<i class='fas fa-file-alt text-xl'></i>";
    echo "</div>";
    echo "<div class='ml-4'>";
    echo "<p class='text-sm font-medium text-gray-600'>Total Articles</p>";
    echo "<p class='text-2xl font-semibold text-gray-900'>" . count($allArticles) . "</p>";
    echo "</div></div></div>";
    
    // Articles publiés
    echo "<div class='bg-white rounded-lg shadow p-6'>";
    echo "<div class='flex items-center'>";
    echo "<div class='p-3 rounded-full bg-green-100 text-green-600'>";
    echo "<i class='fas fa-check-circle text-xl'></i>";
    echo "</div>";
    echo "<div class='ml-4'>";
    echo "<p class='text-sm font-medium text-gray-600'>Articles Publiés</p>";
    echo "<p class='text-2xl font-semibold text-gray-900'>" . count($publishedArticles) . "</p>";
    echo "</div></div></div>";
    
    // Projets
    echo "<div class='bg-white rounded-lg shadow p-6'>";
    echo "<div class='flex items-center'>";
    echo "<div class='p-3 rounded-full bg-purple-100 text-purple-600'>";
    echo "<i class='fas fa-folder text-xl'></i>";
    echo "</div>";
    echo "<div class='ml-4'>";
    echo "<p class='text-sm font-medium text-gray-600'>Projets</p>";
    echo "<p class='text-2xl font-semibold text-gray-900'>" . count($projects) . "</p>";
    echo "</div></div></div>";
    
    // En attente
    $pendingArticles = $articleModel->getAll(['published_status' => 'en attente']);
    echo "<div class='bg-white rounded-lg shadow p-6'>";
    echo "<div class='flex items-center'>";
    echo "<div class='p-3 rounded-full bg-yellow-100 text-yellow-600'>";
    echo "<i class='fas fa-clock text-xl'></i>";
    echo "</div>";
    echo "<div class='ml-4'>";
    echo "<p class='text-sm font-medium text-gray-600'>En Attente</p>";
    echo "<p class='text-2xl font-semibold text-gray-900'>" . count($pendingArticles) . "</p>";
    echo "</div></div></div>";
    
    echo "</div>"; // Fin grid
    
    // Articles récents
    echo "<div class='bg-white rounded-lg shadow'>";
    echo "<div class='px-6 py-4 border-b border-gray-200'>";
    echo "<h3 class='text-lg font-medium text-gray-900'>Articles Récents</h3>";
    echo "</div>";
    echo "<div class='p-6'>";
    
    $recentArticles = array_slice($allArticles, 0, 5);
    if (empty($recentArticles)) {
        echo "<p class='text-gray-500 text-center py-8'>Aucun article trouvé</p>";
    } else {
        echo "<div class='space-y-4'>";
        foreach ($recentArticles as $article) {
            echo "<div class='flex items-center justify-between p-4 bg-gray-50 rounded-lg'>";
            echo "<div class='flex-1'>";
            echo "<h4 class='font-medium text-gray-900'>" . htmlspecialchars($article['Final_Title'] ?? 'Sans titre') . "</h4>";
            echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($article['Projets'] ?? 'Aucun projet') . "</p>";
            echo "</div>";
            $status = $article['published_status'] ?? 'brouillon';
            $statusClass = $status === 'publié' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            echo "<span class='px-2 py-1 text-xs font-medium rounded-full $statusClass'>";
            echo htmlspecialchars($status);
            echo "</span>";
            echo "</div>";
        }
        echo "</div>";
    }
    
    echo "</div></div>"; // Fin articles récents
    
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>";
    echo "<strong>Erreur:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "</div>"; // Fin container
echo "</body></html>";
?>