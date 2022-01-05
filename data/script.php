<?php 

$members = json_decode(file_get_contents('./inscriptions.json'), true);


$dns = "mysql:host=localhost;dbname=moodsound";
$user = "root";
$password = "@Fistoflegend62680";

$pdo = new PDO($dns, $user, $password);

$statement = $pdo->prepare('
INSERT INTO member (
    email,
    password,
    dateNaissance,
    pseudo
    ) VALUES (
        :email, 
        :password,
        :dateNaissance,
        :pseudo
        )
');

foreach ($members as $mem) {
    $statement->bindValue(':email', $mem['email']);
    $statement->bindValue(':password', $mem['password']);
    $statement->bindValue(':dateNaissance', $mem['dateNaissance']);
    $statement->bindValue(':pseudo', $mem['pseudo']);
    $statement->execute();
};






