<?php

namespace App\Http\Response;

class JsonResponse extends Response
{
    public function __construct(array $data = [], int $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct(json_encode($data), $status, array_merge($headers, [
            'Content_Type' => 'application/json'
        ]));
    }

    public function setData(array $data): static
    {
        $this->content = json_encode($data);
        return $this;
    }
}
