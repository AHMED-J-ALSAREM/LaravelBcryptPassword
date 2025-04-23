# Laravel Bcrypt Password

A Laravel package for bcrypt password hashing functionality.

## Installation

You can install the package via composer:

```bash
composer require ahmad/laravel-bcrypt-password
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Ahmad\LaravelBcryptPassword\BcryptPasswordServiceProvider"
```

## Usage

```php
use Ahmad\LaravelBcryptPassword\PasswordHasher;

// Via dependency injection
public function __construct(PasswordHasher $hasher)
{
    $this->hasher = $hasher;
}

// Hash a password
$hash = $this->hasher->hash('password123');

// Verify a password
$isValid = $this->hasher->verify('password123', $hash);

// Check if password needs rehash
$needsRehash = $this->hasher->needsRehash($hash);
```

## Configuration

You can configure the bcrypt cost in your `.env` file:

```
BCRYPT_COST=12
```

Or in the configuration file `config/bcrypt-password.php`.

## Testing

```bash
composer test
```

## License

The MIT License (MIT)