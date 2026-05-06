<?php
require_once '../../core/utils.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: '.url('features/auth/connexion.php'));
    exit;
}



// Redirection en fonction du rôle
$role = $_SESSION['user']['role'] ?? null;

echo "Role: $role"; // Debug: afficher le rôle de l'utilisateur

switch ($role) {
    case 'vendeur':
        header('Location: '.url('pages/espace-client/vendeur.php'));
        exit;
    case 'client':
        header('Location: '.url('pages/espace-client/client.php'));
        exit;
    case 'livreur':
        header('Location: '.url('pages/espace-client/livreur.php'));
        exit;
    case 'eleveur':
        header('Location: '.url('pages/espace-client/eleveur.php'));
        exit;
    default:
        header('Location: '.url('features/auth/connexion.php'));
        exit;
}
?>