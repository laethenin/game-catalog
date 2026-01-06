<?php

namespace Controller;

use Repository\GamesRepository;

final readonly class GameApiController
{
    public function __construct(
        private GamesRepository $gamesRepository
    ) {}

    public function top(): void
    {
        $games = $this->gamesRepository->findTopRated(5);
        http_response_code(200);
        echo json_encode($games);
    }

    public function recent(): void
    {
        $games = $this->gamesRepository->findMostRecent(5);
        http_response_code(200);
        echo json_encode($games);
    }

    public function ratings(): void
    {
        $stats = $this->gamesRepository->countByRating();
        http_response_code(200);
        echo json_encode($stats);
    }
}
