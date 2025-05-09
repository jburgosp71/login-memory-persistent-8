<?php

namespace LoginMemoryPersistent\Infrastructure\Service;

use LoginMemoryPersistent\Domain\Service\Verify2FA\Verify2FAInterface;
use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\ValueObjects\TwoFactorType;

class TwoFactorFactory
{
    public function make(User $user): Verify2FAInterface
    {
        $verifiers2FA[TwoFactorType::EMAIL] = new Verify2FAEmail();
        $verifiers2FA[TwoFactorType::GOOGLE] = new Verify2FAGoogle();

        $type = $user->getTwoFactorType();

        if ($type === null) {
            throw new \InvalidArgumentException("User has no 2FA enabled");
        }

        if (!key_exists($type, $verifiers2FA)) {
            throw new \LogicException("Unsupported 2FA type");
        }

        return $verifiers2FA[$type->__toString()];
    }
}