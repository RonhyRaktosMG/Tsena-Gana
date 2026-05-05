<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=tsena_gana_db', 'tsena_gana_user', 'tsena_gana_password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (\Throwable $th) {
    die('Erreur de connexion à la base de données : ' . $th->getMessage());
}

?>