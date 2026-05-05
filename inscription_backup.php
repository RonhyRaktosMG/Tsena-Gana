<?php

require_once 'core/pdo.php';

$stmt = $pdo->prepare('SELECT id, nom FROM region');
$stmt->execute();
$regions = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];
    $id_region = $_POST['id_region'];

    try {

        $stmt = $pdo->prepare('INSERT INTO utilisateur (email, nom, motdepasse, adresse, telephone, id_region, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$email, $nom, $motdepasse, $adresse, $telephone, $id_region, $role]);
    } catch (Throwable $th) {
        die('Erreur lors de l\'inscription : ' . $th->getMessage());
    }

    echo 'Inscription réussie !';
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="nom" placeholder="Nom">
        <input type="password" name="motdepasse" placeholder="Mot de passe">
        <input type="text" name="adresse" placeholder="Adresse">
        <input type="text" name="telephone" placeholder="Téléphone">
        <select name="role" id="role">
            <option value="client">Client</option>
            <option value="vendeur">Vendeur</option>
            <option value="eleveur">Éleveur</option>
            <option value="livreur">Livreur</option>
        </select>
        <select name="id_region" id="">
            <?php foreach ($regions as $region): ?>
                <option value="<?= $region['id'] ?>"><?= $region['nom'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="S'inscrire">
        <a href="connexion.php">Se connecter</a>
    </form>
</body>
</html>