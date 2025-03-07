<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIController;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IAuthRepo;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends APIController
{
    private IAuthRepo $authRepository;

    public function __construct(IAuthRepo $authRepository)
    {
        $this->authRepository = $authRepository;
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
       
            return redirect()->away('http://localhost:3000/callback?token=' . $data['token'] . '&refresh_token=' . $data['refresh_token']);

            // return response()->json([
            //     'redirect_url' => 'http://localhost:3000/callback?token=' . $data['token'] . '&refresh_token=' . $data['refresh_token']
            // ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authRepository->logout(auth()->user());
            
            return response()->json(true);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $data = $this->authRepository->refreshToken($request->refresh_token);
            dd($data);
            return $this->success([
                'token' => $data['token'],
                'refresh_token' => $data['refresh_token']
            ], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}