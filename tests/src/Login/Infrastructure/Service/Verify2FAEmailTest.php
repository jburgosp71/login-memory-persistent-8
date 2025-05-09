<?php

namespace TestsLoginMemoryPersistent\Infrastructure\Service;

use LoginMemoryPersistent\Infrastructure\Service\Verify2FAEmail;
use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\TwoFactorType;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class Verify2FAEmailTest extends TestCase
{
    public function testCodeIsGeneratedAndSentWhenMissing()
    {
        $service = new Verify2FAEmail();
        $user = new User(new Username("emailuser@example.com"), new Password("secret"));
        $user->enableTwoFactor(new TwoFactorType(TwoFactorType::EMAIL));

        $this->expectException(Validation2FAException::class);
        $this->expectExceptionMessage("2FA code sent via email");

        $service->verifyCode($user, "000000");
    }

    public function testCorrectCodePassesVerification()
    {
        $service = new Verify2FAEmail();
        $user = new User(new Username("emailuser@example.com"), new Password("secret"));
        $user->enableTwoFactor(new TwoFactorType(TwoFactorType::EMAIL));

        // Simula que el código fue generado antes
        $reflection = new ReflectionClass($service);
        $prop = $reflection->getProperty("codeStorage");
        $prop->setValue($service, [
            $user->getUsername() => 123456
        ]);

        $this->assertTrue($service->verifyCode($user, "123456"));
    }

    public function testInvalidCodeThrowsException()
    {
        $service = new Verify2FAEmail();
        $user = new User(new Username("emailuser@example.com"), new Password("secret"));
        $user->enableTwoFactor(new TwoFactorType(TwoFactorType::EMAIL));

        $reflection = new ReflectionClass($service);
        $prop = $reflection->getProperty("codeStorage");
        $prop->setValue($service, [
            $user->getUsername() => 123456
        ]);

        $this->expectException(Validation2FAException::class);
        $this->expectExceptionMessage("Invalid 2FA code");

        $service->verifyCode($user, "654321");
    }
}
