<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo "Welcome to the Home Page";
    }

    public function about(): void
    {
        echo "About us page";
    }
}
