<?php

$pdo = require_once './database2.php';

$statement = $pdo->prepare('DELETE FROM mood WHERE idmood=:id');


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id){
    $statement->bindValue(':id', $id);
    $statement->execute();
}
            header('Location: /');


