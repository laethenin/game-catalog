<?php

function getAllGames (): array {
    // Path jusqu'aux jeux
    $path = __DIR__ . '/../../data/games.json';

    // Lire le contenu du fichier JSON
    $json = file_get_contents($path);

    // Gérer le cas où le fichier ne peut pas être lu
    if ($json === false) {
        return [];
    } 
    $data = json_decode($json, true);
    
    // Retourne les jeux
    return is_array($data) ? $data : [];
} 

function getGameById(int $id): ?array {

    foreach (getAllGames() as $gameById) {
        if ((int)($gameById['id'] ?? 0) === $id) {
            return $gameById;
        }
    }

    return null;
}