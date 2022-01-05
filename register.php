<?php

$pdo = require_once './database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [
        'username' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL
    ]);

    $username = $_input['username'] ?? '';
    $email = $_input['email'] ?? '';
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

        <nav class="menu">
            <a class="popUp" href="/"><img src="/img/outline_home_work_white_24dp.png" alt="Accueil"><span>Accueil</span></a>
            <a class="popUp" href="/register.php/"><img src="/img/outline_assignment_white_24dp.png" alt="S'inscrire"><span>S'inscrire</span></a>
            <a class="popUp" href="/login.php"><img src="/img/outline_login_white_24dp.png" alt="Se connecter"><span>Se connecter</span></a>

        </nav>

        <div class="content">

            <form class="register" action="/register.php" method="POST">

                <h1>Bienvenue sur MoodSound,
                    <br>
                    <br>
                </h1>


                <div>
                    <label for="pseudo">Pseudo : </label><br>
                    <input type="text" placeholder="username" name="username"><br><br>
                </div>

                <div>
                    <label for="email">Email : </label><br>
                    <input type="text" placeholder="email" name="email"><br><br>
                </div>

                <div>
                    <label for="password">Mot de passe : </label><br>
                    <input type="password" placeholder="password" name="password"><br><br>
                </div>

                <?php if ($error) : ?>
                    <h1 style="color:red"><?= $error ?></h1>
                <?php endif; ?>

                <button>S'inscrire</button>

            </form>
        </div>
    </div>



</body>

</html>