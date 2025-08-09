<?php
// Point d'entrée principal avec redirection automatique
session_start();

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']);

// Si pas connecté, rediriger vers la page de connexion
if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}

// Si connecté, rediriger vers le dashboard
header('Location: dashboard.php');
exit;
?>