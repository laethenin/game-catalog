<?php
$errors = $errors ?? [];
?>

<h1>Add a game</h1>

<form method="POST" action="/add" class="form">
    <div class="field">
        <label for="title">Title</label>
        <input type="text" name="title">
        <?php if (!empty($errors['title'])): ?><small><?= $errors['title'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="platform">Platform</label>
        <input type="text" name="platform">
        <?php if (!empty($errors['platform'])): ?><small><?= $errors['platform'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="genre">Genre</label>
        <input type="text" name="genre">
        <?php if (!empty($errors['genre'])): ?><small><?= $errors['genre'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="releaseYear">Release Year</label>
        <input type="number" name="releaseYear">
        <?php if (!empty($errors['releaseYear'])): ?><small><?= $errors['releaseYear'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="rating">Rating</label>
        <input type="number" name="rating">
        <?php if (!empty($errors['rating'])): ?><small><?= $errors['rating'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="description">Description</label>
        <input type="text" name="description">
        <?php if (!empty($errors['description'])): ?><small><?= $errors['description'] ?></small><?php endif; ?>
    </div>
    <div class="field">
        <label for="notes">Notes</label>
        <input type="text" name="notes">
        <?php if (!empty($errors['notes'])): ?><small><?= $errors['notes'] ?></small><?php endif; ?>
    </div>

    <button class="btn" type="submit">Add Game</button>

</form>
