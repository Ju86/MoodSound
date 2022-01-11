<?php



$pdo = require_once './database.php';

$statement = $pdo->prepare('SELECT * FROM tchat');
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

$stateRead = $pdo->prepare('SELECT * FROM tchat WHERE idtchat=:id');

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




const ERR_REQUIRED = "Veuillez renseigner ce champ";
const ERR_URL = "L'URL n'est pas valide";



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

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {

        if ($id) {

            $moods['sound'] = $sound;
            $moods['category'] = $category;

            $stateUpdate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/", $sound));
            $stateUpdate->bindValue(':category',  $moods['category']);
            $stateUpdate->bindValue(':id',  $id);
            $stateUpdate->execute();
        } else {
            $stateCreate->bindValue(':sound',  str_replace("youtu.be/", "youtube.com/embed/", $sound));
            $stateCreate->bindValue(':category',  $category);
            $stateCreate->execute();
        }

        header('Location: /shareWall.php');
    }
}

// $statement = $pdo->prepare('SELECT * FROM mood');
// $statement->execute();
// $moods = $statement->fetchAll();

$statement = $pdo->prepare('SELECT * FROM users LEFT JOIN mood ON users.iduser = mood.iduser');
$statement->execute();
$moods = $statement->fetchAll();


$statement = $pdo->prepare('SELECT * FROM users LEFT JOIN tchat ON users.iduser = tchat.iduser');
$statement->execute();
$users = $statement->fetchAll();

// $statement = $pdo->prepare('SELECT * FROM users');
// $statement->execute();
// $users = $statement->fetchAll();

// $statementtchat = $pdo->prepare('INSERT INTO tchat VALUES (default, :iduser)');
// $statementtchat->bindValue(':iduser', $user['iduser']);
// $statementtchat->execute();



// require_once './isLoggedIn.php';

// $user = isLoggedIn();

// if (!$user) {
//     header('Location: /login.php');
// }

?>







<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="public/css/shareWall.css">
    <title>MoodSound</title>
</head>

<body>

    <div class="container">


        <div class="moodsound">
            <h4>MoodSound</h4>
        </div>

        <div class="online-container">
            <h1 class="online">En Ligne</h1>
            <!-- <?php foreach ($users as $a) : ?>
                <h2><?= $a['username'] ?></h2>
            <?php endforeach; ?> -->
        </div>



        <div class="tchat-container">
            <ul class="all-category">

            </ul>
            <div class="text-container">
                <div class="tchat">
               
                        <?php foreach ($users as $a) : ?>
   
                            <span class="username_date"><h2><?= $a['username'] ?></h2><h3><?= $a['date'] ?></h3></span>
                            <span><?= $a['message'] ?></span>   
                        <?php endforeach; ?>
                        <?php foreach ($moods as $a) : ?>
                            <h2><?= $a['username'] ?></h2>
                            <span class="date"><?= $a['date'] ?></span>
                            <span class="mood">Dans un mood : <?= $a['category'] ?></span>
                            <iframe class="sound" width="458" height="257" src="<?= $a['sound'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php endforeach; ?>
                </div>

                
            </div>

            <div class="input-container">
                <form action="/shareWall.php<?= $id ? "?id=$id" : '' ?>" method="POST">

                    <label for="message"></label>
                    <textarea name="message" id="message"><?= $message ?? '' ?></textarea>

                    <div class="form-action">
                        <button class="btn btn-primary"><?= $id ?: 'Publier' ?></button>
                    </div>
                </form>
            </div>

            <div class="input-mood-container">
                <form action="/shareWall.php<?= $id ? "?id=$id" : '' ?>" method="POST">

                    <div class="form-control">
                        <label for="category">Mood</label>
                        <select name="category" id="category">
                            <option <?= !$category || $category === "joie" ? 'selected' : '' ?> value="joie">Joie</option>
                            <option <?= $category === "amour" ? 'selected' : '' ?> value="amour">Amour</option>
                            <option <?= $category === "tristesse" ? 'selected' : '' ?> value="tristesse">Tristesse</option>
                            <option <?= $category === "colère" ? 'selected' : '' ?> value="colère">Colère</option>
                        </select>
                        <p class="text-error"><?= $errors['category'] ?></p>
                        <label for="sound">Sound</label>
                        <input type="text" placeholder="URL Youtube" name="sound" id="sound" value="<?= $sound ?? '' ?>">
                        <p class="text-error"><?= $errors['sound'] ?></p>
                    </div>
                    <div class="form-action">
                        <button class="btn btn-primary"><?= $id ?: 'Publier' ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>




</body>

</html>