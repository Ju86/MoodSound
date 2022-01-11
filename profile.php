<?php

require_once './isLoggedIn.php';

$user = isLoggedIn();

if (!$user) {
    header('Location: /login.php');
}

?>

<?php

$pdo = require_once './database.php';

$statement = $pdo->prepare('SELECT * FROM profil');
$statement->execute();

$tchats = $statement->fetchAll();

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$stateCreate = $pdo->prepare('
INSERT INTO tchat (
    message
    ) VALUES (
        :message
        )
');

$stateUpdate = $pdo->prepare('
UPDATE tchat
SET
message=:message
WHERE idtchat=:id
');

$stateRead = $pdo->prepare('SELECT * FROM tchat WHERE idtchat=:id' );

 
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $_POST = filter_input_array(INPUT_POST, [
        'message' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $message = $_POST['message'] ?? '';

    if ($id) {
        $tchats['message'] = $message;
        $stateUpdate->bindValue(':message',  $tchats['message']);
        $stateUpdate->bindValue(':id',  $id);
        $stateUpdate->execute();
    } else {
        $stateCreate->bindValue(':message',  $message);
        $stateCreate->execute();
    }
    header('Location: /shareWall.php');

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

            <h2>Hello <?= $user['username'] ?></h2>

            <form action="/profile.php<?= $id ? "?id=$id" : '' ?>" method="POST">
                   
                    <div class="form-control">
                        <label for="image">Avatar</label>
                        <input type="text" name="image" id="image" value="<?= $image ?? '' ?>">
                        <p class="text-error"><?= $errors['image']?></p>
                    </div>
                    <div class="form-control">
                        <label for="sound1">La plus belle chanson de tous les temps ?</label>
                        <input type="text" placeholder="URL Youtube" name="sound1" id="sound1" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound1']?></p>
                    </div>
                    <div class="form-control">
                        <label for="sound2">La meilleur chanson pour danser ivre mort-e ?</label>
                        <input type="text" placeholder="URL Youtube" name="sound2" id="sound2" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound2']?></p>
                    </div>
                    <div class="form-control">
                        <label for="sound3">La Chanson pour une nuit d'amour ?</label>
                        <input type="text" placeholder="URL Youtube" name="sound3" id="sound3" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound3']?></p>
                    </div>
                    <div class="form-control">
                        <label for="sound4">La chanson plaisir coupable ?</label>
                        <input type="text" placeholder="URL Youtube" name="sound4" id="sound4" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound4']?></p>
                    </div>
                    <div class="form-control">
                        <label for="content">Content</label>
                        <textarea name="content" id="content"><?= $content ?? '' ?></textarea>
                        <p class="text-error"><?= $errors['content']?></p>
                    </div>

        </div>

        

    </div>

</body>

</html>