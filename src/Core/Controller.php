<?php

namespace App\Core;

class Controller
{
    public function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../../views/{$view}.php";
    }
}
