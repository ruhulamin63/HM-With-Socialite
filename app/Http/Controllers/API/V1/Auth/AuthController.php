<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIController;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IAuthRepo;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends APIController
{
    private IAuthRepo $authRepository;

    public function __construct(IAuthRepo $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authRepository->register($request->all());
           
            return $this->success([
                'user' => $user['name'],
            ], 'User registered successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;

            $data = $this->authRepository->login($email, $password);

            return $this->success([
                'user' => $data['user'],
                'token' => $data['token']
            ], 'User logged in successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function redirect()
    {
        try {
            return $this->authRepository->redirectToGoogle();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function callback()
    {
        try {
            $data = $this->authRepository->handleGoogleCallback();

            return $this->success($data, 'User authenticated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = $request->refresh_token;

            $data = $this->authRepository->checkRefreshToken($refreshToken);

            return $this->success($data, 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $this->authRepository->logout(auth()->user());
            // $token = auth()->user()->currentAccessToken();
            // $token->delete();
            
            // return response()->json(true);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}