<?php

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Services\DatabaseConnection;
use App\Services\ExampleService;

class HomeController extends Controller
{
    public function __construct(
        protected ExampleService     $service,
        protected DatabaseConnection $db
    )
    {
    }

    public function index(): void
    {
        $data = $this->service->getData();
        $this->render('home', ['title' => 'HomePage', 'content' => $data]);
    }

    public function dashboard(): void
    {
        $this->render('dashboard');
    }

    public function about(): void
    {
        $this->render('about', ['title' => 'About us', 'content' => 'This is the about page']);
    }
}