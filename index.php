<?php

require_once './isLoggedIn.php';
$user = isLoggedIn();

?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <title>MoodSound</title>
</head>

<body>

    <div class="container">

        <!-- <div class="content"> -->

            <nav class="menu">

                <?php if ($user) : ?>
                    <a class="popUp" href="/logout.php/"><img src="img/outline_logout_white_24dp.png" alt="Se déconnecter"><span>Se déconnecter</span></a>
                    <a class="popUp" href="/profile.php/"><img src="img/outline_account_box_white_24dp.png" alt="Profil"><span>Profil</span></a>
                <?php else : ?>
                     <a class="popUp" href="/register.php/"><img src="img/outline_assignment_white_24dp.png" alt="S'inscrire"><span>S'inscrire</span></a>
                     <a class="popUp" href="/login.php"><img src="img/outline_login_white_24dp.png" alt="Se connecter"><span>Se connecter</span></a>
                <?php endif; ?>

            </nav>

        <!-- </div> -->

    </div>

</body>

</html>