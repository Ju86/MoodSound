<?php

$pdo = require_once './database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_input = filter_input_array(INPUT_POST, [

        'email' => FILTER_SANITIZE_EMAIL
    ]);

    $email = $_input['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$password || !$email) {
        $error = "LES CHAMPS DOIVENT ETRE REMPLIS";
    } else {
        $statementUser = $pdo->prepare('SELECT * FROM users WHERE email=:email');
        $statementUser->bindValue(':email', $email);
        $statementUser->execute();
        $user = $statementUser->fetch();
        //         echo "<pre>";
        //         var_dump($user);
        // echo "</pre>";

        if ($user && password_verify($password, $user['password'])) {
            $statementSession = $pdo->prepare('INSERT INTO session VALUES (default, :userid)');
            $statementSession->bindValue(':userid', $user['id']);
            $statementSession->execute();
            $sessionId = $pdo->lastInsertId();
            setcookie('session', $sessionId, time() + 60 * 3, '', '', false, true);
            header('Location: /profile.php');
        } else {
            echo "WRONG MAIL AND/OR PASSWORD";
        }
    }
}




?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/login.css">
    <title>MoodSound</title>
</head>

<body>

    <div class="container">

        <nav class="menu">

            <a class="popUp" href="/"><img src="/img/outline_home_work_white_24dp.png" alt="Accueil"><span>Accueil</span></a>
            <a class="popUp" href="/login.php"><img src="/img/outline_login_white_24dp.png" alt="Se connecter"><span>Se connecter</span></a>
            <a class="popUp" href="/register.php/"><img src="/img/outline_assignment_white_24dp.png" alt="S'inscrire"><span>S'inscrire</span></a>

        </nav>

        <div class="content">

            <form class="login" action="/login.php" method="POST">

                <h1>Se connecter,
                    <br>
                    <br>
                </h1>

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

                <button>Se connecter</button>

            </form>
        </div>
    </div>

</body>

</html>