<?php

session_start();

require_once __DIR__ . '/../../core/pdo.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$typeArticle = $_POST['type_article'] ?? null;
$articleId = $_POST['article_id'] ?? null;
$quantite = (int)($_POST['quantite'] ?? 1);

if (!in_array($typeArticle, ['canard', 'produit'], true) || !$articleId || $quantite <= 0) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

$table = $typeArticle === 'canard' ? 'lot_canard' : 'produit';
$idColumn = 'id';

$stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$idColumn} = ?");
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo json_encode([
        'success' => false,
        'message' => $typeArticle === 'canard' ? 'Canard non trouvé' : 'Produit non trouvé'
    ]);
    exit;
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$panierKey = $typeArticle . '_' . $articleId;
$nom = $typeArticle === 'canard' ? 'Canard ' . $article['race'] : $article['nom'];
$unite = $typeArticle === 'canard' ? 'unité' : ($article['unite'] ?? 'unité');

if (isset($_SESSION['panier'][$panierKey])) {
    $_SESSION['panier'][$panierKey]['quantite'] += $quantite;
} else {
    $_SESSION['panier'][$panierKey] = [
        'type_article' => $typeArticle,
        'article_id' => (int)$articleId,
        'nom' => $nom,
        'prix_unitaire' => (float)$article['prix_unitaire'],
        'quantite' => $quantite,
        'image' => $article['image'] ?? '',
        'unite' => $unite,
        'type_label' => $typeArticle === 'canard' ? 'Canard' : 'Produit',
    ];
}

$totalItems = array_sum(array_map(static fn ($item) => (int)$item['quantite'], $_SESSION['panier']));

echo json_encode([
    'success' => true,
    'message' => 'Ajouté au panier',
    'total_items' => $totalItems,
]);
exit;

?>
