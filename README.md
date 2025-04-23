# Laravel Bcrypt Password

A Laravel package for bcrypt password hashing functionality with WordPress password compatibility.

## Installation

You can install the package via composer:

```bash
composer require ahmed-j-alsarem/laravel-bcrypt-password
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="AhmedJAlsarem\LaravelBcryptPassword\BcryptPasswordServiceProvider"
```

## Usage

```php
use AhmedJAlsarem\LaravelBcryptPassword\PasswordHasher;

// Via dependency injection
public function __construct(PasswordHasher $hasher)
{
    $this->hasher = $hasher;
}

// Hash a password
$hash = $this->hasher->hash('password123');

// Verify a password (supports both Laravel and WordPress hashes)
$isValid = $this->hasher->verify('password123', $hash);

// WordPress Password Compatibility
// Example with WordPress password hash
$wp_hash = '$wp$2y$10$y6/UfA/WhvVLZK6RxBSJE./L6YpJN8ChGg15a0Pqry/bTGsDuMR1q';
$isValid = $this->hasher->verify('Na101918!', $wp_hash);

// Supports multiple WordPress hash formats:
// 1. WordPress bcrypt with $wp$ prefix
// 2. WordPress phpass with $P$ prefix
// 3. phpBB3 with $H$ prefix
// 4. Standard bcrypt with $2y$ prefix

// Check if password needs rehash
$needsRehash = $this->hasher->needsRehash($hash);
```

## Configuration

You can configure the bcrypt cost and WordPress compatibility options in your `.env` file:

```
BCRYPT_COST=12
WP_ITERATION_COUNT_LOG2=8
WP_PORTABLE_PASSWORDS=true
```

Or in the configuration file `config/bcrypt-password.php`.

## Testing

```bash
composer test
```

## License

The MIT License (MIT)