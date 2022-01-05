<?php 

$moods = json_decode(file_get_contents('./moods.json'), true);

$dns = "mysql:host=localhost;dbname=moodsound";
$user = "root";
$password = "@Fistoflegend62680";

$pdo = new PDO($dns, $user, $password);

$statement = $pdo->prepare('
INSERT INTO mood (
    title,
    sound,
    category,
    content
    ) VALUES (
        :title, 
        :sound,
        :category,
        :content
        )
');

foreach ($moods as $moo) {
    $statement->bindValue(':title', $moo['title']);
    $statement->bindValue(':sound', $moo['sound']);
    $statement->bindValue(':category', $moo['category']);
    $statement->bindValue(':content', $moo['content']);
    $statement->execute();
};