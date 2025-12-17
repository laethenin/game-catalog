<?php
$id ??= 0;
$game ??= [];
?>

<?php if (!$game): ?>
    <h1>Le jeu demandé n'est pas trouvé.</h1>
<?php else: ?>
    <h1><?= $game['title'] ?></h1>
    <h1><?= $game['platform'] ?></h1>
<?php endif; ?>
    