<?php

namespace LoginMemoryPersistent\Domain\Service\Verify2FA;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;

interface Verify2FAInterface
{
    /**
     * @throws Validation2FAException
     */
    public function verifyCode(User $user, string $code): bool;
}