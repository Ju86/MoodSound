<?php

require './isLoggedIn.php';
$user = isLoggedIn();

// echo "<pre>";
// var_dump($user);
// echo "</pre>";
// die();

$pdo = require './database.php';

// $profilStatement = $pdo->prepare('SELECT * FROM profil ');
// $profilStatement->execute();
// $profils = $profilStatement->fetchAll();

// $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$stateCreate = $pdo->prepare('
INSERT INTO profil (
    avatar,
    sound1,
    sound2,
    sound3,
    sound4,
    presentation,
    iduser
    ) VALUES (
        :avatar,
        :sound1,
        :sound2,
        :sound3,
        :sound4,
        :presentation,
        :iduser
        )
');

$stateUpdate = $pdo->prepare('
UPDATE profil
SET
avatar=:avatar,
sound1=:sound1,
sound2=:sound2,
sound3=:sound3,
sound4=:sound4,
presentation=:presentation
WHERE idprofil=:id
');

$stateRead = $pdo->prepare('SELECT * FROM profil WHERE iduser=:id');

if ($user) {
    $stateRead->bindValue(':id', $user['iduser']);
    $stateRead->execute();
    $profil = $stateRead->fetch();
    // echo "<pre>";
    // var_dump($profil);
    // echo "</pre>";
    // die();
    $avatar = $profil['avatar'];
    $sound1 = $profil['sound1'];
    $sound2 = $profil['sound2'];
    $sound3 = $profil['sound3'];
    $sound4 = $profil['sound4'];
    $presentation = $profil['presentation'];
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $_POST = filter_input_array(INPUT_POST, [

        'avatar' => FILTER_SANITIZE_URL,
        'sound1' => FILTER_SANITIZE_URL,
        'sound2' => FILTER_SANITIZE_URL,
        'sound3' => FILTER_SANITIZE_URL,
        'sound4' => FILTER_SANITIZE_URL,
        'presentation' => FILTER_SANITIZE_FULL_SPECIAL_CHARS

    ]);

    $avatar = $_POST['avatar'] ?? '';
    $sound1 = $_POST['sound1'] ?? '';
    $sound2 = $_POST['sound2'] ?? '';
    $sound3 = $_POST['sound3'] ?? '';
    $sound4 = $_POST['sound4'] ?? '';
    $presentation = $_POST['presentation'] ?? '';

    if ($user) {
        // header('Location: /login.php');
        // $profil['avatar'] = $avatar;
        // $profil['sound1'] = $sound1;
        // $profil['sound2'] = $sound2;
        // $profil['sound3'] = $sound3;
        // $profil['sound4'] = $sound4;
        // $profil['presentation'] = $presentation;
        $stateUpdate->bindValue(':avatar',  $avatar);
        $stateUpdate->bindValue(':sound1',  str_replace("youtu.be/", "youtube.com/embed/", $sound1));
        $stateUpdate->bindValue(':sound2',  str_replace("youtu.be/", "youtube.com/embed/", $sound2));
        $stateUpdate->bindValue(':sound3',  str_replace("youtu.be/", "youtube.com/embed/", $sound3));
        $stateUpdate->bindValue(':sound4',  str_replace("youtu.be/", "youtube.com/embed/", $sound4));
        $stateUpdate->bindValue(':presentation', $presentation);
        $stateUpdate->bindValue(':id', $profil['idprofil']);
        $stateUpdate->execute();
    } else {
        // header('Location: /index.php');
        $stateCreate->bindValue(':avatar',  $avatar);
        $stateCreate->bindValue(':sound1',  str_replace("youtu.be/", "youtube.com/embed/", $sound1));
        $stateCreate->bindValue(':sound2',  str_replace("youtu.be/", "youtube.com/embed/", $sound2));
        $stateCreate->bindValue(':sound3',  str_replace("youtu.be/", "youtube.com/embed/", $sound3));
        $stateCreate->bindValue(':sound4',  str_replace("youtu.be/", "youtube.com/embed/", $sound4));
        $stateCreate->bindValue(':presentation', $presentation);
        $stateCreate->bindValue(':iduser',  $user['iduser']);
        $stateCreate->execute();
    }
    header('Location: /profile.php');
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

        <?php require_once 'includes/header.php' ?>

        <div class="content">

            <div class="sous-content1">

                <h1><?= $user['iduser'] ? "Modifier " : "Compléter " ?>mon profil</h1>

                <form class="profile" action="/profile.php" method="POST">

                    <div class="form-control">
                        <label for="avatar">Choisis ton avatar : </label>
                        <br>
                        <input type="text" name="avatar" id="avatar" value="<?= $avatar ?? '' ?>">

                    </div>
                    <div class="form-control">
                        <label for="sound1">Ta plus belle chanson de tous les temps ?</label>
                        <br>
                        <input type="text" name="sound1" id="sound1" value="<?= $sound1 ?? '' ?>">

                    </div>
                    <div class="form-control">
                        <label for="sound2">Ta meilleur chanson pour danser ivre (mort-e) ?</label>
                        <br>
                        <input type="text" name="sound2" id="sound2" value="<?= $sound2 ?? '' ?>">

                    </div>
                    <div class="form-control">
                        <label for="sound3">Ta chanson pour une nuit d'amour ?</label>
                        <br>
                        <input type="text" name="sound3" id="sound3" value="<?= $sound3 ?? '' ?>">

                    </div>
                    <div class="form-control">
                        <label for="sound4">Ta chanson plaisir coupable ?</label>
                        <br>
                        <input type="text" name="sound4" id="sound4" value="<?= $sound4 ?? '' ?>">
                    </div>

                    <div class="form-control">
                        <label for="presentation">Présente- toi en quelques lignes : </label>
                        <br>
                        <textarea name="presentation" id="presentation"><?= $presentation ?? '' ?></textarea>
                    </div>

                    <br>

                    <button class="btn btn-primary"><?= $user['iduser'] ? 'Modifier' : 'Sauvegarder' ?></button>

                </form>

            </div>

            <div class="sous-content2">

                <span><img class="profil-cover-img" src="<?= $avatar ?? '' ?>" alt=""><?= $user['username'] ?></span>
                <iframe class=" sound" width="229" height="128" src="<?= $sound1 ?? '' ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <iframe class="sound" width="229" height="128" src="<?= $sound2 ?? '' ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <iframe class="sound" width="229" height="128" src="<?= $sound3 ?? '' ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <iframe class="sound" width="229" height="128" src="<?= $sound4 ?? '' ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="presentation"><?= $presentation ?? '' ?></div>

            </div>


        </div>

    </div>

</body>

</html>