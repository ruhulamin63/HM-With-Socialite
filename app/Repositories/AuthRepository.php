<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IAuthRepo;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements IAuthRepo
{
    use ApiResponser;
    private User $model;

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
                'password' => Hash::make('password'),
                'access_token' => $googleUser->token,
                'refresh_token' => Str::random(60),
                'expires_in' => now()->addMinutes(1)->toDateTimeString()
            ]);

            return [
                'access_token' => $googleUser->token,
                'refresh_token' => $refreshToken
            ];

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate with Google'], 500);
        }
    }

    public function checkRefreshToken($refreshToken)
    {
        $user = User::where('refresh_token', $refreshToken)->first();

        if (!$user) {
            throw new \Exception('Invalid refresh token');
        }

        $newAccessToken = $user->createToken('access-token', ['*'], now()->addMinutes(15))->plainTextToken;
        $newRefreshToken = Str::random(60);

        $user->update(['refresh_token' => $newRefreshToken]);

        return [
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken
        ];
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
        $user->update(['refresh_token' => null]);

        return $this->success([], 'User logged out successfully');
    }
}