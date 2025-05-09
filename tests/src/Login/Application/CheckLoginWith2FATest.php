<?php

namespace TestsLoginMemoryPersistent\Application;

use LoginMemoryPersistent\Application\CheckLoginWith2FA;
use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Service\LoginService;
use LoginMemoryPersistent\Domain\Service\Verify2FA\Verify2FAInterface;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use LoginMemoryPersistent\Infrastructure\Service\TwoFactorFactory;
use PHPUnit\Framework\TestCase;

class CheckLoginWith2FATest extends TestCase
{
    public function testLoginWithEmail2FASucceeds()
    {
        $username = 'user@example.com';
        $password = 'password123';
        $code     = '123456';

        // Mock User with 2FA type 'email'
        $user = new User(new Username($username), new Password($password));
        $user->setTwoFactorType('email');

        // Mock LoginService
        $loginService = $this->createMock(LoginService::class);
        $loginService->method('getUserIfValid')
            ->with($username, $password)
            ->willReturn($user);

        // Mock Email 2FA Verifier
        $emailVerifier = $this->createMock(Verify2FAInterface::class);
        $emailVerifier->expects($this->once())
            ->method('verifyCode')
            ->with($user, $code)
            ->willReturn(true);

        // Mock Factory
        $factory = $this->createMock(TwoFactorFactory::class);
        $factory->method('make')
            ->with($user)
            ->willReturn($emailVerifier);

        $checkLogin = new CheckLoginWith2FA($loginService, $factory);

        $this->assertTrue($checkLogin->run($username, $password, $code));
    }

    public function testLoginWithGoogle2FASucceeds()
    {
        $username = 'googleuser@example.com';
        $password = 'securePass!';
        $code     = '654321';

        // Mock User with 2FA type 'google'
        $user = new User(new Username($username), new Password($password));
        $user->setTwoFactorType('google');
        $user->setGoogleSecret('SECRET123');

        // Mock LoginService
        $loginService = $this->createMock(LoginService::class);
        $loginService->method('getUserIfValid')
            ->with($username, $password)
            ->willReturn($user);

        // Mock Google 2FA Verifier
        $googleVerifier = $this->createMock(Verify2FAInterface::class);
        $googleVerifier->expects($this->once())
            ->method('verifyCode')
            ->with($user, $code)
            ->willReturn(true);

        // Mock Factory
        $factory = $this->createMock(TwoFactorFactory::class);
        $factory->method('make')
            ->with($user)
            ->willReturn($googleVerifier);

        $checkLogin = new CheckLoginWith2FA($loginService, $factory);

        $this->assertTrue($checkLogin->run($username, $password, $code));
    }
}
