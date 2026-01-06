<?php

namespace Core;

use JetBrains\PhpStorm\NoReturn;

final class Response {
    public function render(string $view, array $data = [], int $status = 200): void {
        http_response_code($status);
        extract($data);
        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        require __DIR__ . '/../../views/partials/footer.php';
    }

    #[NoReturn]
    public function redirect(string $to, int $status = 302): void {
        header('Location: ' . $to, true, $status);
        exit;
    }

    public function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
