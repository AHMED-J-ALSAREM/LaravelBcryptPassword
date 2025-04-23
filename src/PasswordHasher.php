<?php

namespace Ahmad\LaravelBcryptPassword;

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
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function needsRehash($hash)
    {
        $cost = $this->config['cost'] ?? 12;
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}