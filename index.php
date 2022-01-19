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

        <?php require_once 'includes/header.php' ?>

       

            <div class="content">

                <h1>Partagez votre humeur en musique</h1>
                <h1>avec MoodSound</h1>

                <button><a href="/register.php/">S'inscrire</a></button>

            </div>

   

</body>

</html>