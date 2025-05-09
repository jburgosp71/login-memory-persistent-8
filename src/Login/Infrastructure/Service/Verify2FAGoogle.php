<?php

namespace LoginMemoryPersistent\Infrastructure\Service;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;
use LoginMemoryPersistent\Domain\Service\Verify2FA\Verify2FAInterface;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;

class Verify2FAGoogle implements Verify2FAInterface
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws Validation2FAException
     * @throws SecretKeyTooShortException
     */
    public function verifyCode(User $user, string $code): bool
    {
        $secret = $user->getGoogleSecret();

        if (!$secret || !$this->google2fa->verifyKey($secret, $code)) {
            throw new Validation2FAException("Invalid Google Authenticator code");
        }

        return true;
    }
}