<?php

namespace App\Http\Controllers;

use App\Core\Auth\AuthManager;
use App\Http\Response\JsonResponse;
use App\Http\Response\Response;
use App\Http\Response\ViewResponse;

class AuthController
{
    public function __construct(
        protected AuthManager $auth
    )
    {
    }

    public function index(): ViewResponse
    {
        return view('login');
    }

    public function login(): Response|JsonResponse
    {
        $credential = [
            'email' => $_POST['email'] ?? null,
            'password' => $_POST['password'] ?? null,
        ];

        if ($this->auth->attempt($credential)) {
            return response()->json(['message' => 'You are logged in!']);
        }

        return response()->redirect('/login');
    }

    public function logout(): Response
    {
        $this->auth->logout();
        return response()->redirect('/');
    }
}
