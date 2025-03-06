<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface IAuthRepo
{
    public function redirectToGoogle();
    public function handleGoogleCallback();
    public function checkRefreshToken(string $refreshToken);
    public function register(array $data);
    public function login(string $email, string $password);
    public function logout(User $user);
}