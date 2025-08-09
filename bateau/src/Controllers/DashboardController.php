<?php
namespace ContentFactory\Controllers;

use ContentFactory\Core\Auth;
use ContentFactory\Models\Article;
use ContentFactory\Models\Project;

class DashboardController
{
    public function index()
    {
        Auth::requireAuth();
        
        $articleModel = new Article();
        $projectModel = new Project();
        
        $stats = [
            'total_articles' => count($articleModel->getAll()),
            'published_articles' => count($articleModel->getAll(['published_status' => 'publié'])),
            'total_projects' => count($projectModel->getAll()),
            'pending_articles' => count($articleModel->getAll(['published_status' => 'en attente']))
        ];
        
        $recent_articles = array_slice($articleModel->getAll(), 0, 5);
        
        require __DIR__ . '/../../views/dashboard/index.php';
    }
}