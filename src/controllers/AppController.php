<?php

require_once __DIR__ . '/../services/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController
{
    public function handleRequest(string $path): void {
        if (preg_match('#^/games/(\d+)$#', $path, $m)) {
            $this->gameById((int)$m[1]);
            return;
        }

        switch ($path) {
            case '/':
                $this->home();
                break;
            case '/games':
                $this->games();
                break;
            case '/add':
                $this->add();
                break;
            case '/random':
                $this->random();
                break;
            default:
                $this->notFound();
                break;
        }
    }

    private function render(string $view, array $data = [], int $status = 200): void {
         http_response_code($status);
         extract($data);
         require __DIR__ . '/../../views/partials/header.php';
         require __DIR__ . '/../../views/pages/' . $view . '.php'; 
         require __DIR__ . '/../../views/partials/footer.php';
    }

    private function home(): void {
        // Récupérer tous les jeux
        $games = getLimitedGames(3);

        // rendre la vue avec les jeux
        $this->render('home', [
            'featuredGames' => $games,
            'total' => countAll()
        ]);
    }
    
    private function games(): void {
        // Récupérer tous les jeux
        $games = getAllGamesSortedByRating();

        // rendre la vue avec les jeux
        $this->render('games', [
            'games' => $games
        ]);
    }

    private function gameById(int $id): void {
         $game = getGameById($id);

         $success = $_SESSION['flash_success'] ?? null;
         unset($_SESSION['flash_success']);

         $this->render('detail', [
            'id' => $id,
            'game' => $game,
             'success' => $success
         ]);
    }

    private function random(): void {
        $lastId = $_SESSION['last_random_id'] ?? 0;
        $game = null;

        for($i = 0; $i < 5; $i++) {
            $candidate = getRandom();

            if($candidate['id'] !== $lastId) {
                $game = $candidate;
            }
        }

        $id = $game['id'];
        $_SESSION['last_random_id'] = $id;

        header('Location: /games/' . $id, true, 302);
        exit;
    }

    private function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddGame();
            return;
        }
        $this->render('add');
    }

    private function handleAddGame(): void {
        $title = trim($_POST['title']);
        $platform = trim($_POST['platform']);
        $genre = trim($_POST['genre']);
        $releaseYear = (int)$_POST['releaseYear'];
        $rating = (int)$_POST['rating'];
        $description = trim($_POST['description']);
        $notes = trim($_POST['notes']);

        $errors = [];

        if ($title === '') $errors['title'] = 'Le titre est obligatoire';
        if ($platform === '') $errors['platform'] = 'La platform est obligatoire';
        if ($genre === '') $errors['genre'] = 'Le genre est obligatoire';
        if ($releaseYear < 1800 || $releaseYear > (int)date('Y')) $errors['releaseYear'] = 'La release Year doit être entre 1800 et 2025';
        if ($rating < 0 || $rating > 10) $errors['rating'] = 'Rating doit être entre 0 et 10';
        if ($description === '') $errors['description'] = 'La description est obligatoire';
        if ($notes === '') $errors['notes'] = 'Une note est obligatoire';

        $old = [
            'title' => $title,
            'platform' => $platform,
            'genre' => $genre,
            'releaseYear' => $releaseYear,
            'rating' => $rating,
            'description' => $description,
            'notes' => $notes
            ];

        if (!empty($errors)) {
            $this->render('add', ['old' => $old, 'errors' => $errors], 422);
            return;
        }

        $newGameId = createGame($old);
        $_SESSION['flash_success'] = 'Game added successfully';

        header('Location: /games/' . $newGameId, true, 302);
        exit;
    }

    private function notFound(): void {
        $this->render('not-found', [], 404);
    }
    
}