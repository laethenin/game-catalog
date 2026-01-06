<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\GamesRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController
{
    public function __construct(
        private Response $response,
        private GamesRepository $gamesRepository,
        private Session $session,
        private Request $request) {}

    /*public function handleRequest(string $path): void {
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
    }*/

    public function home(): void {
        // Récupérer tous les jeux
        $games = $this->gamesRepository->findTop(3);
        //Debug::dump_block('Method', $this->request->method());

        // rendre la vue avec les jeux
        $this->response->render('home', [
            'featuredGames' => $games,
            'total' => $this->gamesRepository->countAll()
        ]);
    }
    
    public function games(): void {
        // Récupérer tous les jeux
        $games = $this->gamesRepository->findAllSortedByRating();

        // rendre la vue avec les jeux
        $this->response->render('games', [
            'games' => $games
        ]);
    }

    public function gameById(int $id): void {
         $game = $this->gamesRepository->findById($id);
         //Debug::dump_block('$_SESSION', $_SESSION);

         $success = $this->session->pullFlash('success');

         $this->response->render('detail', [
            'id' => $id,
            'game' => $game,
             'success' => $success
         ]);
    }

    #[NoReturn]
    public function random(): void {
        $lastId = $this->session->get('last_random_id') ?? null;
        $game = null;

        for($i = 0; $i < 5; $i++) {
            $candidate = $this->gamesRepository->findRandom();

            if($candidate['id'] !== $lastId) {
                $game = $candidate;
            }
        }

        $id = $game['id'];
        $this->session->set('last_random_id', $id);

        $this->response->redirect('/games/' . $id);
    }

    public function add(): void {
        if ($this->request->isPost()) {
            $this->handleAddGame();
            return;
        }
        $this->response->render('add');
    }

    public function handleAddGame(): void {
        $title = trim($this->request->post('title'));
        $platform = trim($this->request->post('platform'));
        $genre = trim($this->request->post('genre'));
        $releaseYear = (int)($this->request->post('releaseYear'));
        $rating = (int)($this->request->post('rating'));
        $description = trim($this->request->post('description'));
        $notes = trim($this->request->post('notes'));

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
            $this->response->render('add', ['old' => $old, 'errors' => $errors], 422);
            return;
        }

        $newGameId = $this->gamesRepository->createGame($old);
        $this->session->flash('success', 'Game added successfully');

        $this->response->redirect('/games/' . $newGameId);
    }

    public function notFound(): void {
        $this->response->render('not-found', [], 404);
    }
    
}