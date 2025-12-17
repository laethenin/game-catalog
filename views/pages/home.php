<?php

$games = $featuredGames ?? [];
$totalGames = $total ?? 0; 

?>

<h1>Top <?= count($games) ?> featured Games</h1>

<strong>Sur un total de : <?= $totalGames ?> jeux</strong>

<?php foreach ($games as $game) : ?>
<p><?= $game['title'] ?></p>
<p><?= $game['platform'] ?></p>
<p><?= $game['genre'] ?></p>
<p><?= $game['releaseYear'] ?></p>
<?php endforeach; ?>
    