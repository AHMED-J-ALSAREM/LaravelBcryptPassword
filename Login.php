protected function checkPassword(string $inputPassword, string $storedHash, User $user): bool
{
    try {
        // Remove WordPress prefix if it exists
        if (str_starts_with($storedHash, '$wp$')) {
            $storedHash = '$' . substr($storedHash, 4);
        }

        // Debug the exact values being used
        \Log::debug('Password verification attempt', [
            'input_password' => $inputPassword,
            'cleaned_hash' => $storedHash,
            'is_valid_hash' => str_starts_with($storedHash, '$2y$')
        ]);

        // Direct bcrypt verification
        if (password_verify($inputPassword, $storedHash)) {
            return true;
        }

        // Fallback to WordPress hasher if direct verification fails
        $wp_hasher = new \AhmedJAlsarem\LaravelBcryptPassword\PasswordHash(8, true);
        $verified = $wp_hasher->CheckPassword($inputPassword, $storedHash);

        \Log::debug('WordPress hasher verification result', [
            'verified' => $verified
        ]);

        return $verified;

    } catch (\Exception $e) {
        \Log::error('Password verification error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}