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

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    
    <title>MoodSound</title>

</head>

<body>

    <div class="container">

        <div class="content">
            <iframe class="img-container" width="458" height="257" src="<?= $mood['sound'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <h1 class="article-title"><?= $mood['title'] ?></h1>
            <div class="article-content"><?= $mood['content'] ?></div>
            <div class="action">
                <a class="btn btn-secondary" href="/deleteMood.php?id=<?= $mood['idmood'] ?>">Delete</a>
                <a class="btn btn-primary" href="/share.php?id=<?= $mood['idmood'] ?>">Edit</a>
            </div>
        </div>

    </div>

</body>

</html>