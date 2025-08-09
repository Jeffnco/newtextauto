<?php
namespace ContentFactory\Controllers;

use ContentFactory\Core\Auth;

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (Auth::login($email, $password)) {
                header('Location: /dashboard');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }
        
        require __DIR__ . '/../../views/auth/login.php';
    }
    
    public function logout()
    {
        Auth::logout();
        header('Location: /auth/login');
        exit;
    }
}