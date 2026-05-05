<?php

    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: features/auth/connexion.php');
        exit();
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Tongasoa eto amin'ny TsenaGana ry <?= $_SESSION['user']['nom'] ?></h1>
    <a href="features/auth/deconnexion.php">Se déconnecter</a>
</body>
</html>