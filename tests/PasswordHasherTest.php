<?php

namespace Ahmad\LaravelBcryptPassword\Tests;

use Ahmad\LaravelBcryptPassword\PasswordHasher;
use Orchestra\Testbench\TestCase;

class PasswordHasherTest extends TestCase
{
    protected $hasher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hasher = new PasswordHasher(['cost' => 4]); // Lower cost for faster tests
    }

    public function test_it_can_hash_passwords()
    {
        $password = 'secret123';
        $hash = $this->hasher->hash($password);
        
        $this->assertNotEquals($password, $hash);
        $this->assertTrue(password_verify($password, $hash));
    }

    public function test_it_can_verify_passwords()
    {
        $password = 'secret123';
        $hash = $this->hasher->hash($password);
        
        $this->assertTrue($this->hasher->verify($password, $hash));
        $this->assertFalse($this->hasher->verify('wrong_password', $hash));
    }

    public function test_it_can_check_if_password_needs_rehash()
    {
        $password = 'secret123';
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $this->assertTrue($this->hasher->needsRehash($hash));
    }
}