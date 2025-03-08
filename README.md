# HM Socialite Login
This includes authentication, CRUD operations for hotels, pagination, social login.

### Proficiency in PHP and Laravel
- Features to Implement in Laravel:
- User Authentication (with Access & Refresh Token)
- Social Login (Google OAuth)
- Social Media Sharing
- CRUD Operations

## For Laravel 12, the minimum PHP version required is PHP 8.2.

```bash
git clone https://github.com/ruhulamin63/HM-With-Socialite.git
```

```bash
cp .env.example .env
```

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=HM-System
DB_USERNAME=root
DB_PASSWORD=
```

```bash
composer install
Or
composer update
```

### Need to add google client id and client secret in .env file:
```bash
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

```bash
php artisan migrate:fresh --seed
```

```bash
php artisan serve
```

### API Access Route
```bash
http://127.0.0.1:8000/api/v1
```