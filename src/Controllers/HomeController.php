<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ExampleService;

class HomeController extends Controller
{
    public function __construct(protected ExampleService $service)
    {
    }

    public function index(): void
    {
        $data = $this->service->getData();
        $this->render('home', ['title' => 'HomePage', 'content' => $data]);
    }

    public function about(): void
    {
        $this->render('about', ['title' => 'About us', 'content' => 'This is the about page']);
    }
}