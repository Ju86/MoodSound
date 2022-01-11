<?php

$pdo = require_once './database.php';

$sessionId = $_COOKIE['session'] ?? '';

if ($sessionId) {
    $statement = $pdo->prepare('DELETE FROM session WHERE iduser=:id');
    $statement->bindValue(':id', $sessionId);
    $statement->execute();
    setcookie('session', '', time() - 1);
    header('Location: /login.php');
} else {
    header('Location: /login.php');
}