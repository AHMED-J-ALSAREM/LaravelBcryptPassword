<?php

namespace AhmedJAlsarem\LaravelBcryptPassword;

class PasswordHasher
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function hash($password)
    {
        $cost = $this->config['cost'] ?? 12;
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
        return '$wp$' . substr($hash, 1); // Add WordPress prefix
    }

    public function verify($password, $hash)
    {
        // Remove WordPress prefix if it exists
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }
        return password_verify($password, $hash);
    }

    public function needsRehash($hash)
    {
        $cost = $this->config['cost'] ?? 12;
        // Remove WordPress prefix if it exists
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}