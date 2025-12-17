<?php

require_once __DIR__ . '/../helpers/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController
{
    public function handleRequest() {
        $page = $_GET['page'] ?? 'home';

        switch ($page) {
            case 'home':
                $this->home();
                break;
            default:
                echo "404 Page Not Found.";
        }
    }

    private function render(string $view, array $data): void {
         extract($data);
         require __DIR__ . '/../../views/pages/' . $view . '.php'; 
    }

    private function home() {
        // Récupérer tous les jeux
        $games = getAllGames();
        $featuredGames = array_slice($games, 0, 3);

        // rendre la vue avec les jeux
        $this->render('home', [
            'featuredGames' => $featuredGames,
            'total' => count($games)
        ]);
    } 
    
}