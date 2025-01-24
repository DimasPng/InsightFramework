<?php

namespace App\Http\Controllers;

use App\Core\Auth\AuthManager;
use App\Core\Controller;

class AuthController extends Controller
{
    public function __construct(
        protected AuthManager $auth
    )
    {
    }

    public function index(): void
    {
        $this->render('login');
    }

    public function login(): void
    {
        $credential = [
            'email' => $_POST['email'] ?? null,
            'password' => $_POST['password'] ?? null,
        ];

        if ($this->auth->attempt($credential)) {
            echo 'You are logged in!';
            return;
        }

        echo 'Invalid credentials.';
    }

    public function logout(): void
    {
        $this->auth->logout();

        echo 'You have been logged out';
    }
}
