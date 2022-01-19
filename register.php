<?php


require_once './isLoggedIn.php';
$user = isLoggedIn();

$pdo = require './database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [
        'username' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL
    ]);

    $email = $_input['email'] ?? '';
    $username = $_input['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password || !$email) {
        $error = "LES CHAMPS DOIVENT ETRE REMPLIS";
    } else {
        // echo $password;
        $hashPassword = password_hash($password, PASSWORD_ARGON2I);
        $statement = $pdo->prepare('INSERT INTO users VALUES (
            DEFAULT,
            :email,
            :username,
            :password
        )');
        $statement->bindvalue(':email', $email);
        $statement->bindvalue(':username', $username);
        $statement->bindvalue(':password', $hashPassword);
        $statement->execute();

        header('Location: /login.php');
    }
}




?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/register.css">
    <title>MoodSound</title>
</head>


<body>

    <div class="container">

        <?php require_once 'includes/header.php' ?>

        <div class="content">

            <div class="sous-content1">

                <p class="text">
                    L’être humain peut ressentir une multitude d’émotions, la musique peut
                    refléter toutes les émotions possibles.
                    <br>
                    <br>
                    Crée ton MoodSound en associant ton humeur à une musique et partage-le sur le tchat de la communauté.
                    <br>
                    <br>
                    N'attend plus, inscris-toi !!!
                </p>

            </div>

            <div class="sous-content2">

                <form class="register" action="/register.php" method="POST">

                    <h1>S'inscrire</h1>

                    <div>
                        <label for="pseudo">Pseudo : </label><br>
                        <input type="text" placeholder="username" name="username"><br>
                    </div>

                    <div>
                        <label for="email">Email : </label><br>
                        <input type="text" placeholder="email" name="email"><br>
                    </div>

                    <div>
                        <label for="password">Mot de passe : </label><br>
                        <input type="password" placeholder="password" name="password"><br>
                    </div>

                    <?php if ($error) : ?>
                        <h1 style="color:red"><?= $error ?></h1>
                    <?php endif; ?>

                    <button>S'inscrire</button>

                </form>

            </div>

        </div>

    </div>

    </div>

</body>

</html>