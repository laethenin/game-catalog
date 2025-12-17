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
            case 'games':
                $this->games();
                break;
            case 'detail':
                $this->gameById();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function render(string $view, array $data): void {
         extract($data);
         require __DIR__ . '/../../views/partials/header.php';
         require __DIR__ . '/../../views/pages/' . $view . '.php'; 
         require __DIR__ . '/../../views/partials/footer.php';
    }

    private function home() {
        // Récupérer tous les jeux
        $games = getAllGames();
        $featuredGames = array_slice($games, 0, 3);

        http_response_code(200);

        // rendre la vue avec les jeux
        $this->render('home', [
            'featuredGames' => $featuredGames,
            'total' => count($games)
        ]);
    }
    
    private function games() {
        // Récupérer tous les jeux
        $games = getAllGames();

        usort($games, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        http_response_code(200);

        // rendre la vue avec les jeux
        $this->render('games', [
            'games' => $games
        ]);
    }

    private function gameById() {
         $id = (int)($_GET['id'] ?? 0); 
         $game = getGameById($id);

         http_response_code(200);

         $this->render('detail', [
            'id' => $id,
            'game' => $game
         ]);
    }

    private function notFound() {
        http_response_code(404);
        $this->render('not-found', []);
    }
    
}