<?php

namespace AhmedJAlsarem\LaravelBcryptPassword;

class PasswordHasher
{
    protected $config;
    protected $wp_hasher;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        // Initialize WordPress hasher with default iteration count
        $iteration_count_log2 = isset($config['iteration_count_log2']) ? $config['iteration_count_log2'] : 8;
        $portable_hashes = isset($config['portable_hashes']) ? $config['portable_hashes'] : true;
        $this->wp_hasher = new \PasswordHash($iteration_count_log2, $portable_hashes);
    }

    public function hash($password)
    {
        // If the password is already hashed, return it
        if ($this->isHashed($password)) {
            return $password;
        }

        // Use WordPress's hasher for compatibility
        if (class_exists('PasswordHash')) {
            return $this->wp_hasher->HashPassword($password);
        }

        // Fallback to bcrypt if WordPress hasher is not available
        $cost = $this->config['cost'] ?? 12;
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public function verify($password, $hash)
    {
        // Handle WordPress portable hashes
        if (substr($hash, 0, 3) === '$P$' || substr($hash, 0, 3) === '$H$') {
            return $this->wp_hasher->CheckPassword($password, $hash);
        }

        // Handle bcrypt hashes (both with and without WordPress prefix)
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }
        return password_verify($password, $hash);
    }

    public function needsRehash($hash)
    {
        // Always rehash WordPress portable hashes to bcrypt
        if (substr($hash, 0, 3) === '$P$' || substr($hash, 0, 3) === '$H$') {
            return true;
        }

        // Remove WordPress prefix if it exists
        if (strpos($hash, '$wp$') === 0) {
            $hash = '$' . substr($hash, 4);
        }

        $cost = $this->config['cost'] ?? 12;
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