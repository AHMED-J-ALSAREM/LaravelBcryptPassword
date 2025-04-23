<?php

namespace AhmedJAlsarem\LaravelBcryptPassword;

class PasswordHasher
{
    protected $config;
    protected $wp_hasher;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $iteration_count_log2 = $config['iteration_count_log2'] ?? 8;
        $portable_passwords = $config['portable_passwords'] ?? true;
        $this->wp_hasher = new PasswordHash($iteration_count_log2, $portable_passwords);
    }

    public function hash($password)
    {
        if ($this->isHashed($password)) {
            return $password;
        }

        return $this->wp_hasher->HashPassword($password);
    }

    public function verify($password, $hash)
    {
        $max_length = config('bcrypt-password.validation.max_length', 4096);
        if (strlen($password) > $max_length) {
            return false;
        }

        // Handle WordPress phpass format ($P$ or $H$)
        if (substr($hash, 0, 3) === '$P$' || substr($hash, 0, 3) === '$H$') {
            return $this->wp_hasher->CheckPassword($password, $hash);
        }

        // Handle bcrypt format (with or without WordPress prefix)
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }
        return password_verify($password, $hash);
    }

    public function needsRehash($hash)
    {
        $cost = $this->config['cost'] ?? 12;

        // Always rehash WordPress portable hashes
        if (substr($hash, 0, 3) === '$P$' || substr($hash, 0, 3) === '$H$') {
            return true;
        }

        // Remove WordPress prefix if it exists
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }

        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    protected function isHashed($password)
    {
        return (
            strpos($password, '$P$') === 0 ||
            strpos($password, '$H$') === 0 ||
            strpos($password, '$2y$') === 0 ||
            strpos($password, '$wp$') === 0
        );
    }
}