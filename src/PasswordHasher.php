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
        // Debug incoming values
        \Log::debug('PasswordHasher verify attempt', [
            'password_length' => strlen($password),
            'original_hash' => $hash
        ]);

        // Handle WordPress bcrypt format
        if (str_starts_with($hash, '$wp$')) {
            $hash = '$' . substr($hash, 4);
            \Log::debug('Cleaned WordPress hash', [
                'cleaned_hash' => $hash
            ]);
            return $this->verifyBcrypt($password, $hash);
        }

        // Handle standard bcrypt
        if (str_starts_with($hash, '$2y$')) {
            return $this->verifyBcrypt($password, $hash);
        }

        // Fallback to WordPress portable hasher
        return $this->wp_hasher->CheckPassword($password, $hash);
    }

    protected function verifyBcrypt($password, $hash)
    {
        $result = password_verify($password, $hash);
        \Log::debug('Bcrypt verification result', [
            'result' => $result
        ]);
        return $result;
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