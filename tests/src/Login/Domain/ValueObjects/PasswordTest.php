<?php

namespace TestsLoginMemoryPersistent\Domain\ValueObjects;

use LoginMemoryPersistent\Domain\ValueObjects\Password;
use PHPUnit\Framework\TestCase;
use function ECSPrefix202306\Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

class PasswordTest extends TestCase
{
    public function testEqualsPassword()
    {
        $passwordTestA = new Password('password');
        $passwordTestB = new Password('password');

        $this->assertTrue($passwordTestA->equals($passwordTestA));
        $this->assertFalse($passwordTestA->equals($passwordTestB));
    }

    public function testCheckVerifyPassword()
    {
        $givenPassword = 'password';
        $errorPassword = 'badPassword';
        $storedPassword = new Password($givenPassword);
        $storedPassword2 = new Password($givenPassword);

        $this->assertTrue($storedPassword->verifyPassword($givenPassword));
        $this->assertTrue($storedPassword2->verifyPassword($givenPassword));
        $this->assertFalse($storedPassword->verifyPassword($errorPassword));
    }
}