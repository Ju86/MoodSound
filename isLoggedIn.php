<?php 

function isLoggedIn()
{
    $pdo = require_once './database.php';
    $sessionId = $_COOKIE['session'] ?? '';

if ($sessionId) {
    $statementSession = $pdo->prepare('SELECT * FROM session WHERE id=:id');
    $statementSession->bindValue(':id', $sessionId);
    $statementSession->execute();
    $session = $statementSession->fetch();
    // echo "<pre>";
    // var_dump(($session));
    // echo "</pre>";
    $userStatement = $pdo->prepare('SELECT * FROM users WHERE id=:id');
    $userStatement->bindValue(':id', $session['user_id']);
    $userStatement->execute();
    $user = $userStatement->fetch();
}
return $user ?? false;
}