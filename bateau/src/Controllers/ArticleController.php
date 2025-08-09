<?php
namespace ContentFactory\Controllers;

use ContentFactory\Core\Auth;
use ContentFactory\Models\Article;
use ContentFactory\Models\Project;

class ArticleController
{
    private $articleModel;
    private $projectModel;

    public function __construct()
    {
        $this->articleModel = new Article();
        $this->projectModel = new Project();
    }

    public function index()
    {
        Auth::requireAuth();
        
        $filters = [
            'Projets' => $_GET['project'] ?? '',
            'published_status' => $_GET['status'] ?? ''
        ];
        
        $articles = $this->articleModel->getAll($filters);
        $projects = $this->projectModel->getAll();
        
        require __DIR__ . '/../../views/articles/index.php';
    }

    public function show(string $id)
    {
        Auth::requireAuth();
        
        $article = $this->articleModel->getById($id);
        if (!$article) {
            header('HTTP/1.0 404 Not Found');
            require __DIR__ . '/../../views/errors/404.php';
            return;
        }
        
        $projects = $this->projectModel->getAll();
        require __DIR__ . '/../../views/articles/show.php';
    }

    public function create()
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Final_Title' => $_POST['title'] ?? '',
                'Meta_description' => $_POST['meta_description'] ?? '',
                'final_article' => $_POST['content'] ?? '',
                'Projets' => $_POST['project'] ?? '',
                'published_status' => 'brouillon'
            ];
            
            if ($this->articleModel->create($data)) {
                header('Location: /articles?success=created');
                return;
            }
        }
        
        $projects = $this->projectModel->getAll();
        require __DIR__ . '/../../views/articles/create.php';
    }

    public function update(string $id)
    {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Final_Title' => $_POST['title'] ?? '',
                'Meta_description' => $_POST['meta_description'] ?? '',
                'final_article' => $_POST['content'] ?? '',
                'Projets' => $_POST['project'] ?? ''
            ];
            
            if ($this->articleModel->update($id, $data)) {
                header("Location: /articles/$id?success=updated");
                return;
            }
        }
        
        $this->show($id);
    }

    public function delete(string $id)
    {
        Auth::requireAuth();
        
        if ($this->articleModel->delete($id)) {
            header('Location: /articles?success=deleted');
        } else {
            header('Location: /articles?error=delete_failed');
        }
    }
}