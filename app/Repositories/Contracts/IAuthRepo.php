<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface IAuthRepo
{
    public function login(string $email);
    public function redirectToGoogle();
    public function handleGoogleCallback();
    public function checkRefreshToken(string $refreshToken);
    public function logout(User $user);
}