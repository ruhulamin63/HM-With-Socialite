<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'access_token',
        'refresh_token',
        'expires_in'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token',
        'refresh_token',
        'expires_in'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($user) {
            $user->updateTokenExpiration();
        });
    }

    public function updateTokenExpiration()
    {
        if ($this->isDirty('password')) {
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $this->id)
                ->where('tokenable_type', 'App\Models\User')
                ->whereNull('expires_at')
                ->update(['expires_at' => now()]);
        }
    }

    public $appends = ["photo_url", 'display_name'];

    public function getPhotoUrlAttribute()
    {
        $image = $this->attributes['photo'] ?? '/avatar5.png';
        return asset($image);
    }

    public function getDisplayNameAttribute()
    {
        if(isset($this->attributes['name']) && isset($this->attributes['email'])){
            return $this->attributes['name']. ' - '. $this->attributes['email'];
        }else{
            return '';
        }
    }
}
