<?php

const ERR_REQUIRED = "Veuillez renseigner ce champ";
const ERR_URL = "L'URL n'est pas valide";

$pdo = require_once './database.php';

$stateCreate = $pdo->prepare('
INSERT INTO mood (
    sound, 
    category
    ) VALUES (
        :sound,
        :category
        )
');

$stateUpdate = $pdo->prepare('
UPDATE mood
SET
sound=:sound,
category=:category,
WHERE idmood=:id
');

$stateRead = $pdo->prepare('SELECT * FROM mood WHERE idmood=:id');

$category = '';


$errors = [
    'sound' => '',
    'category' => ''
];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id) {
    $stateRead->bindValue(':id', $id);
    $stateRead->execute();
    $mood = $stateRead->fetch();
    $sound = $mood['sound'];
    $category = $mood['category'];
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $_POST = filter_input_array(INPUT_POST, [
        'sound' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_STRING,
        ]);

        $sound = $_POST['sound'] ?? '';
        $category = $_POST['category'] ?? '';
    
        if (!$sound) {
            $errors['sound'] = ERR_REQUIRED;
        } else if (!filter_var($sound, FILTER_VALIDATE_URL)) {
            $errors['sound'] = ERR_URL;
        }

        if (!$category) {
            $errors['category'] = ERR_REQUIRED;
        }

        if (empty(array_filter($errors, fn ($e) => $e !== ''))){
      
            if ($id) {
            
                $moods['sound'] = $sound;
                $moods['category'] = $category;
               
                $stateUpdate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/",$sound));
                $stateUpdate->bindValue(':category',  $moods['category']);
                $stateUpdate->bindValue(':id',  $id);
                $stateUpdate->execute();

            } else {
                $stateCreate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/",$sound));
                $stateCreate->bindValue(':category',  $category);
                $stateCreate->execute();
            }
           
            header('Location: /share.php');
        } 
    }

    $statement = $pdo->prepare('SELECT * FROM mood');
    $statement->execute();
    $moods = $statement->fetchAll();




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
            
                <form action="/share.php<?= $id ? "?id=$id" : '' ?>" method="POST">
                   
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
                        <label for="sound">Sound</label>
                        <input type="text" placeholder="URL Youtube" name="sound" id="sound" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound']?></p>
                    </div>
                    <div class="form-action">
                        <button class="btn btn-primary"><?= $id ? : 'Publier' ?></button>
                    </div>
                </form>
            </div>
        </div>


        <div >
        <?php foreach ($moods as $a) : ?>
                          <span><?= $a['category'] ?></span>
                          <iframe class="img-container" width="458" height="257" src="<?= $a['sound'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php endforeach; ?>
       
        </div>

       

    </div>

</body>

</html>