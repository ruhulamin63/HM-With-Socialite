<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IAuthRepo;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthRepository implements IAuthRepo
{
    use ApiResponser;
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login(string $email, string $password)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = $this->model->where('email', $email)->first();
        }

        if (!$user) {
            throw new \Exception('Invalid login credentials');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('Invalid password');
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

    public function register(array $data)
    {
        try {
            $user = $this->model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_verified' => 1
            ]);

            return $this->success(UserResource::make($user), 'User registered successfully');
            
        } catch (\Throwable $th) {
            throw new \Exception('User registration failed');
        }
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
                // 'password' => Hash::make('password'),
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