<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}


?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/profile.css">
    <title>MoodSound</title>
</head>

<body>

    <div class="container">

        <nav class="menu">

            <a class="popUp" href="/"><img src="/img/outline_home_work_white_24dp.png" alt="Accueil"><span>Accueil</span></a>

            <a class="popUp" href="/profile.php"><img src="/img/outline_account_box_white_24dp.png" alt="Profil"><span>Profil</span></a>

            <a class="popUp" href="/logout.php"><img src="/img/outline_logout_white_24dp.png" alt="Se déconnecter"><span>Se déconnecter</span></a>

        </nav>

        <div class="content">
            <h1>PROFIL</h1>

            <h2>Hello <?= $user['username'] ?></h2>

        </div>


    </div>

</body>

</html>