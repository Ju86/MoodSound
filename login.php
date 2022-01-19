<?php

require_once './isLoggedIn.php';
$user = isLoggedIn();


$pdo = require './database.php';
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
            $statementSession = $pdo->prepare('INSERT INTO session VALUES (default, :iduser)');
            $statementSession->bindValue(':iduser', $user['iduser']);
            $statementSession->execute();
            $sessionId = $pdo->lastInsertId();
            setcookie('session', $sessionId, time() + 60 * 60, '', '', false, true);
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

        <?php require_once 'includes/header.php' ?>

        <div class="content">
    
<div class="sous-content1"></div>
<div class="sous-content2">
            <form class="login" action="/login.php" method="POST">

                <h1>Se connecter</h1>

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

        </div>

    </div>

</body>

</html>