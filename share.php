<?php

const ERR_REQUIRED = "Veuillez renseigner ce champ";
const ERR_TITLE_SHORT = "Le titre est trop court";
const ERR_CONTENT_SHORT = "L'article est trop court";
const ERR_URL = "L'image doit avoir une URL valide";

$pdo = require_once './database2.php';

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
        } else if (mb_strlen($title) < 3) {
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
        } else if (mb_strlen($content) < 50) {
            $errors['content'] = ERR_CONTENT_SHORT;
        }
        
        // echo"<pre>";

        if (empty(array_filter($errors, fn ($e) => $e !== ''))){
            // echo "ok";
            if ($id) {
                $moods['title'] = $title;
                $moods['sound'] = $sound;
                $moods['category'] = $category;
                $moods['content'] = $content;
                $stateUpdate->bindValue(':title',  $moods['title']);
                $stateUpdate->bindValue(':sound',  $moods['sound']);
                $stateUpdate->bindValue(':category',  $moods['category']);
                $stateUpdate->bindValue(':content',  $moods['content']);
                $stateUpdate->bindValue(':id',  $id);
                $stateUpdate->execute();

            } else {
                $stateCreate->bindValue(':title',  $title);
                $stateCreate->bindValue(':sound',  $sound);
                $stateCreate->bindValue(':category',  $category);
                $stateCreate->bindValue(':content',  $content);
                $stateCreate->execute();
            }
            
            
            header('Location: /');
        } 
        


    }






?>






<!DOCTYPE html>
<html lang="fr">

<head>

    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/share.css">

    <title>MoodSound</title>

</head>

<body>

    <div class="container">

        

        <div class="content">
            <div class="block p-20 form-container">
                <h1><?= $id ? "Modifier " : "Partager " ?>un MoodSound</h1>
                <form action="/share.php<?= $id ? "?id=$id" : '' ?>" method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                        <p class="text-error"><?= $errors['title']?></p>
                    </div>
                    <div class="form-control">
                        <label for="sound">Sound</label>
                        <input type="text" name="sound" id="sound" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound']?></p>
                    </div>
                    <div class="form-control">
                        <label for="category">Mood</label>
                        <select name="category" id="category">
                        <option <?= !$category || $category ==="joie" ? 'selected' : '' ?> value="joie">Joie</option>
                        <option <?= $category ==="amour" ? 'selected' : '' ?> value="amour">Amour</option>
                        <option  <?= $category ==="tristesse" ? 'selected' : '' ?> value="tristesse">Tristesse</option>
                        <option  <?= $category ==="colère" ? 'selected' : '' ?> value="colère">Colère</option>
                       </select>
                        <p class="text-error"><?= $errors['category']?></p>
                    </div>
                    <div class="form-control">
                        <label for="content">Content</label>
                        <textarea name="content" id="content"><?= $content ?? '' ?></textarea>
                        <p class="text-error"><?= $errors['content']?></p>
                    </div>
                    <div class="form-action">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary"><?= $id ? 'Modifier' : 'Publier' ?></button>
                    </div>
                </form>
            </div>
        </div>

       

    </div>

</body>

</html>