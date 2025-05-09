<?php

namespace LoginMemoryPersistent\Application;

use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;
use LoginMemoryPersistent\Domain\Service\LoginService;
use LoginMemoryPersistent\Infrastructure\Service\TwoFactorFactory;

class CheckLoginWith2FA
{
    protected LoginService $loginService;
    protected TwoFactorFactory $twoFactorFactory;

    public function __construct(LoginService $loginService, TwoFactorFactory $twoFactorFactory)
    {
        $this->loginService = $loginService;
        $this->twoFactorFactory = $twoFactorFactory;
    }

    /**
     * @throws ErrorLoginException
     * @throws UnavailableUserException
     * @throws Validation2FAException
     */
    public function run(string $username, string $password, ?string $code = null): bool
    {
        $user = $this->loginService->getUserIfValid($username, $password); // separa esta lógica del tryLogin

        if ($user->getTwoFactorType() !== null) {
            $verifier = $this->twoFactorFactory->make($user);
            $verifier->verifyCode($user, $code);
        }

        return true;
    }
}