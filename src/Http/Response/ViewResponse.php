<?php

namespace App\Http\Response;

class ViewResponse extends Response
{
    public function __construct(string $view, array $data = [], int $status = self::HTTP_OK, array $headers = [])
    {
        extract($data);
        ob_start();
        require __DIR__ . "/../../../views/{$view}.php";
        $content = ob_get_clean();

        parent::__construct($content, $status, array_merge($headers, [
            'Content-Type' => 'text/html'
        ]));
    }
}
