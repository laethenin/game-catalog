<?php

require_once __DIR__ . '/../services/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController
{
    public function handleRequest(): void {
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

    private function home(): void {
        // Récupérer tous les jeux
        $games = getLimitedGames(3);

        http_response_code(200);

        // rendre la vue avec les jeux
        $this->render('home', [
            'featuredGames' => $games,
            'total' => countAll()
        ]);
    }
    
    private function games(): void {
        // Récupérer tous les jeux
        $games = getAllGamesSortedByRating();

        http_response_code(200);

        // rendre la vue avec les jeux
        $this->render('games', [
            'games' => $games
        ]);
    }

    private function gameById(): void {
         $id = (int)($_GET['id'] ?? 0);
         $game = getGameById($id);

         http_response_code(200);

         $this->render('detail', [
            'id' => $id,
            'game' => $game
         ]);
    }

    private function notFound(): void {
        http_response_code(404);
        $this->render('not-found', []);
    }
    
}