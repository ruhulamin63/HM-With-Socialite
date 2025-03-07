<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IAuthRepo;
use App\Traits\ApiResponser;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class AuthRepository implements IAuthRepo
{
    use ApiResponser;
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = $this->model->where('email', $email)->first();
        }

        if (!$user) {
            throw new \Exception('Invalid login credentials');
        }

        // if ($user->is_verified != 1) {
        //     throw new \Exception('User not verified');
        // }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user = UserResource::make($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function redirectToGoogle()  
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
                
            $user = User::updateOrCreate(['email' => $googleUser->email],[
                'name' => $googleUser->name,
                'google_id'=> $googleUser->id,
                'avatar' => $googleUser->avatar,
                'email_verified_at' => now(),
                'refresh_token' => Str::random(60)
            ]);

            Auth::login($user);

            $token = $user->createToken('authToken')->plainTextToken;

            return [
                'token' => $token,
                'refresh_token' => $user->refresh_token
            ];

        } catch (\Exception $e) {
            throw new Exception('Failed to authenticate with Google');
        }
    }

    public function checkRefreshToken($refreshToken)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $user = User::where('refresh_token', $request->refresh_token)->first();

        if (!$user) {
            throw new Exception('Invalid refresh token');
        }

        $accessToken = $user->createToken('authToken')->plainTextToken;

        $newRefreshToken = Str::random(60);
        $user->refresh_token = $newRefreshToken;
        $user->save();

        return [
            'token' => $accessToken,
            'refresh_token' => $newRefreshToken,
        ];
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();

        return true;
    }
}