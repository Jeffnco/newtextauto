<?php
session_start();
require_once __DIR__ . '/autoload.php';

use ContentFactory\Core\Database;

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $db = Database::getInstance();
            $result = $db->makeRequest('users?filter=(email,eq,' . urlencode($email) . ')');
            
            if ($result['success'] && !empty($result['data']['list'])) {
                $user = $result['data']['list'][0];
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['Id'];
                    $_SESSION['user_email'] = $user['email'];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Mot de passe incorrect';
                }
            } else {
                $error = 'Utilisateur non trouvé';
            }
        } catch (Exception $e) {
            $error = 'Erreur de connexion : ' . $e->getMessage();
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Content Factory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-rocket text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Content Factory</h2>
            <p class="mt-2 text-sm text-gray-600">Connectez-vous à votre compte</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <?php if ($error): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Se connecter
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Utilisez vos identifiants de la base NocoDB
                </p>
            </div>
        </div>
    </div>
</body>
</html>