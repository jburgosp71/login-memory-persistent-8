<?php

namespace TestsLoginMemoryPersistent\Infrastructure\Service;

use LoginMemoryPersistent\Infrastructure\Service\Verify2FAGoogle;
use PHPUnit\Framework\TestCase;
use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\TwoFactorType;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;
use PragmaRX\Google2FA\Google2FA;
use ReflectionClass;

class Verify2FAGoogleTest extends TestCase
{
    public function testValidCodeReturnsTrue()
    {
        $google2fa = $this->createMock(Google2FA::class);
        $google2fa->method('verifyKey')->willReturn(true);

        $service = new Verify2FAGoogle();
        $this->setPrivateProperty($service, 'google2fa', $google2fa);

        $user = new User(new Username("googleuser@example.com"), new Password("secret"));
        $user->enableTwoFactor(new TwoFactorType(TwoFactorType::GOOGLE), 'secretkey');

        $this->assertTrue($service->verifyCode($user, '123456'));
    }

    public function testInvalidCodeThrowsException()
    {
        $google2fa = $this->createMock(Google2FA::class);
        $google2fa->method('verifyKey')->willReturn(false);

        $service = new Verify2FAGoogle();
        $this->setPrivateProperty($service, 'google2fa', $google2fa);

        $user = new User(new Username("googleuser@example.com"), new Password("secret"));
        $user->enableTwoFactor(new TwoFactorType(TwoFactorType::GOOGLE), 'secretkey');

        $this->expectException(Validation2FAException::class);
        $this->expectExceptionMessage("Invalid Google Authenticator code");

        $service->verifyCode($user, '123456');
    }

    private function setPrivateProperty(object $obj, string $propName, $value): void
    {
        $reflection = new ReflectionClass($obj);
        $prop = $reflection->getProperty($propName);
        $prop->setValue($obj, $value);
    }
}
