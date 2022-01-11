<?php

const ERR_REQUIRED = "Veuillez renseigner ce champ";
const ERR_TITLE_SHORT = "Le titre est trop court";
const ERR_CONTENT_SHORT = "L'article est trop court";
const ERR_URL = "L'image doit avoir une URL valide";

$pdo = require_once './database.php';

$stateCreate = $pdo->prepare('
INSERT INTO mood (
    title,
    sound, 
    category,
    content
    ) VALUES (
        :title,
        :sound,
        :category,
        :content
        )
');

$stateUpdate = $pdo->prepare('
UPDATE mood
SET
title=:title,
sound=:sound,
category=:category,
content=:content
WHERE idmood=:id
');

$stateRead = $pdo->prepare('SELECT * FROM mood WHERE idmood=:id');

$category = '';


$errors = [
    'title' => '',
    'sound' => '',
    'category' => '',
    'content' => ''
];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id) {
    $stateRead->bindValue(':id', $id);
    $stateRead->execute();
    $mood = $stateRead->fetch();
    $title = $mood['title'];
    $sound = $mood['sound'];
    $category = $mood['category'];
    $content = $mood['content'];
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {



    $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_STRING,
        'sound' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_STRING,
        'content' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $title = $_POST['title'] ?? '';
    $sound = $_POST['sound'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!$title) {
        $errors['title'] = ERR_REQUIRED;
    } else if (mb_strlen($title) < 1) {
        $errors['title'] = ERR_TITLE_SHORT;
    }

    if (!$sound) {
        $errors['sound'] = ERR_REQUIRED;
    } else if (!filter_var($sound, FILTER_VALIDATE_URL)) {
        $errors['sound'] = ERR_URL;
    }

    if (!$category) {
        $errors['category'] = ERR_REQUIRED;
    }

    if (!$content) {
        $errors['content'] = ERR_REQUIRED;
    } else if (mb_strlen($content) < 1) {
        $errors['content'] = ERR_CONTENT_SHORT;
    }

    // echo"<pre>";

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        // echo "ok";
        if ($id) {
            $moods['title'] = $title;
            $moods['sound'] = $sound;
            $moods['category'] = $category;
            $moods['content'] = $content;
            $stateUpdate->bindValue(':title',  $moods['title']);
            $stateUpdate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/", $sound));
            $stateUpdate->bindValue(':category',  $moods['category']);
            $stateUpdate->bindValue(':content',  $moods['content']);
            $stateUpdate->bindValue(':id',  $id);
            $stateUpdate->execute();
        } else {
            $stateCreate->bindValue(':title',  $title);
            $stateCreate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/", $sound));
            $stateCreate->bindValue(':category',  $category);
            $stateCreate->bindValue(':content',  $content);
            $stateCreate->execute();
        }


        header('Location: /wall.php');
    }
}

$statement = $pdo->prepare('SELECT * FROM mood');
$statement->execute();

$moods = $statement->fetchAll();
$categories = [];

$selectedCat = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';


if (count($moods)) {

    $categories = array_map(fn ($a) => $a['category'], $moods);


    $cat = array_reduce($categories, function ($acc, $c) {
        if (isset($acc[$c])) {
            $acc[$c]++;
        } else {
            $acc[$c] = 1;
        }
        return $acc;
    }, []);


    $artPerCat = array_reduce($moods, function ($acc, $art) {
        if (isset($acc[$art['category']])) {
            $acc[$art['category']] = [...$acc[$art['category']], $art];
        } else {
            $acc[$art['category']] = [$art];
        }
        return $acc;
    }, []);
}


require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}



?>





<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="data:;base64,iVBORw0KGgo=">

    <link rel="stylesheet" href="public/css/wall.css">

    <title>MoodSound</title>

</head>

<body>

    <div class="container">
   <!-- <a href="/profile.php"><h1><?= $user['username'] ?></h1></a>   
        <!-- CATEGORIE : -->

        <div class="category">

            <ul class="category-container">
                <li><a href="/">All MoodSound<span class="small">(<?= count($moods) ?>)</span></a></li>
                <?php foreach ($cat as $cKey => $cNum) : ?>
                    <li><a href="/?cat=<?= $cKey ?>"><?= $cKey ?><span class="small">(<?= $cNum ?>)</span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>





        <div class="tchat">

            <div class="category-content">

                <?php if (!$selectedCat) : ?>
                    <?php foreach ($cat as $c => $num) : ?>
                        <h2><?= $c ?></h2>
                        <div class="articles-container">
                            <?php foreach ($artPerCat[$c] as $a) : ?>
                                <a href="/detailMood.php?id=<?= $a['idmood'] ?>" class="article block">
                                    <iframe class="img-container" width="458" height="257" src="<?= $a['sound'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    <h2><?= $a['title'] ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>

                    <h2><?= $selectedCat ?></h2>

                    <div class="articles-container">
                        <?php foreach ($artPerCat[$selectedCat] as $a) : ?>
                            <a href="/detailMood.php?id=<?= $a['idmood'] ?>" class="article block">
                                <iframe class="img-container" width="458" height="257" src="<?= $a['sound'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                <h2><?= $a['title'] ?></h2>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-container">

                <div class="block p-20 form-container">

                    <h1><?= $id ? "Modifier " : "Partager " ?>un MoodSound</h1>

                    <form action="/share.php<?= $id ? "?id=$id" : '' ?>" method="POST">

                        <div class="form-control">
                            <label for="title">Titre</label>
                            <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                            <p class="text-error"><?= $errors['title'] ?></p>
                        </div>

                        <div class="form-control">
                            <label for="category">Mood</label>
                            <select name="category" id="category">
                                <option <?= !$category || $category === "joie" ? 'selected' : '' ?> value="joie">Joie</option>
                                <option <?= $category === "amour" ? 'selected' : '' ?> value="amour">Amour</option>
                                <option <?= $category === "tristesse" ? 'selected' : '' ?> value="tristesse">Tristesse</option>
                                <option <?= $category === "colère" ? 'selected' : '' ?> value="colère">Colère</option>
                            </select>
                            <p class="text-error"><?= $errors['category'] ?></p>
                        </div>
                        <div class="form-control">
                            <label for="sound">Sound</label>
                            <input type="text" placeholder="URL Youtube" name="sound" id="sound" value="<?= $sound ?? '' ?>">
                            <p class="text-error"><?= $errors['sound'] ?></p>
                        </div>
                        <div class="form-control">
                            <label for="content">Content</label>
                            <textarea name="content" id="content"><?= $content ?? '' ?></textarea>
                            <p class="text-error"><?= $errors['content'] ?></p>
                        </div>
                        <div class="form-action">
                            <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                            <button class="btn btn-primary"><?= $id ? 'Modifier' : 'Publier' ?></button>
                        </div>
                    </form>
                </div>
            </div>

        <div class="input-tchat">
            
            <form action="/wall.php<?= $id ? "?id=$id" : '' ?>" method="POST">

                <div class="form-control">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                    <p class="text-error"><?= $errors['title'] ?></p>
                </div>

                <button class="btn btn-primary"><?= $id ? 'Modifier' : 'Publier' ?></button>

            </form>

        </div>

           

        </div> -->

















    </div>

</body>

</html>