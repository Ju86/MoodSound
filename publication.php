<?php

$pdo = require_once './database2.php';

$statement = $pdo->prepare('SELECT * FROM mood');
$statement->execute();

$moods = $statement->fetchAll();
$categories = [];

$selectedCat = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';


if (count($moods)) {
   
    $categories = array_map(fn ($a) => $a['category'], $moods);
 

    $cat = array_reduce($categories, function ($acc, $c) {
        if (isset($acc[$c])) {
            $acc[$c]++;
        } else {
            $acc[$c] = 1;
        }
        return $acc;
    }, []);


    $artPerCat = array_reduce($moods, function ($acc, $art) {
        if (isset($acc[$art['category']])) {
            $acc[$art['category']] = [...$acc[$art['category']], $art];
        } else {
            $acc[$art['category']] = [$art];
        }
        return $acc;
    }, []);
 
}










?>







<!DOCTYPE html>
<html lang="fr">

<head>

    
    <link rel="stylesheet" href="/public/css/publication.css">

    <title>MoodSound</title>

</head>

<body>

    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="main-category-container">
                <ul class="category-container">
                <li><a href="/">All MoodSound<span class="small">(<?= count($moods) ?>)</span></a></li>
                    <?php foreach ($cat as $cKey => $cNum) : ?>
                        <li><a href="/?cat=<?= $cKey ?>"><?= $cKey ?><span class="small">(<?= $cNum ?>)</span></a></li>

                    <?php endforeach; ?>
                </ul>
                <div class="category-content">
                    <?php if(!$selectedCat) : ?>
                    <?php foreach ($cat as $c => $num) : ?>
                        <h2><?= $c ?></h2>
                        <div class="articles-container">
                            <?php foreach ($artPerCat[$c] as $a) : ?>
                                <a href="/detailMood.php?id=<?= $a['idmood'] ?>" class="article block">
                                    <!-- <div class="img-container" style="background-image:url(<?= $a['image'] ?>)"></div> -->
                                    <img src="<?= $a['sound'] ?>" alt="" class="img-container">
                                    <!-- <iframe  class="img-container" width="916" height="515" src="<?= $a['image'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                    <h2><?= $a['title'] ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php else : ?>
                        
                        <h2><?= $selectedCat ?></h2>
                        <div class="articles-container">
                            <?php foreach ($artPerCat[$selectedCat] as $a) : ?>
                                <a href="/detailMood.php?id=<?= $a['idmood'] ?>" class="article block">
                                    <!-- <div class="img-container" style="background-image:url(<?= $a['image'] ?>)"></div> -->
                                    <img src="<?= $a['sound'] ?>" alt="" class="img-container">
                                    <!-- <iframe  class="img-container" width="916" height="515" src="<?= $a['image'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                    <h2><?= $a['title'] ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                </div>
            </div>

        </div>
        
    </div>

</body>

</html>