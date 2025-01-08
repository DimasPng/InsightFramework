<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home', ['title' => 'HomePage', 'content' => 'Welcome to the home page!']);
    }

    public function about(): void
    {
        $this->render('about', ['title' => 'About us', 'content' => 'This is the about page']);
    }
}