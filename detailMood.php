<?php

$pdo = require_once './database2.php';

$statement = $pdo->prepare('SELECT * FROM mood WHERE idmood=:id');






$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: /');
} else {
    $statement->bindValue(':id', $id);
    $statement->execute();
    $mood = $statement->fetch();
}





?>







<!DOCTYPE html>
<html lang="fr">

<head>

    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/detailMood.css">

    <title>MoodSound</title>

</head>

<body>

    <div class="container">

        <div class="content">
            <a href="/">Page d'acceuil</a>
            <img class="article-cover-img" src="<?= $mood['sound'] ?>" alt="">
            <h1 class="article-title"><?= $mood['title'] ?></h1>
            <div class="article-content"><?= $mood['content'] ?></div>
            <div class="action">
                <a class="btn btn-secondary" href="/deleteMood.php?id=<?= $mood['idmood'] ?>">Delete</a>
                <!-- <a class="btn btn-primary" href="/share.php?id=<?= $mood['idmood'] ?>">Edit</a> -->
            </div>
        </div>

    </div>

</body>

</html>