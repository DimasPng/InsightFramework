<?php

namespace App\Http\Controllers;

use App\Http\Response\JsonResponse;
use App\Http\Response\ViewResponse;
use App\Services\DatabaseConnection;
use App\Services\ExampleService;

class HomeController
{
    public function __construct(
        protected ExampleService     $service,
        protected DatabaseConnection $db
    )
    {
    }

    public function index(): ViewResponse
    {
        $data = $this->service->getData();
        return view('home', ['title' => 'HomePage', 'content' => $data]);
    }

    public function dashboard(): ViewResponse
    {
        return view('dashboard');
    }

    public function about(): JsonResponse
    {
        return response()->json(['status' => true, 'message' => 'Success']);
    }
}