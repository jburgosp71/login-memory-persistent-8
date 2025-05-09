<?php

namespace LoginMemoryPersistent\Domain\ValueObjects;

class TwoFactorType
{
    const EMAIL = 'email';
    const GOOGLE = 'google';

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [self::EMAIL, self::GOOGLE])) {
            throw new \InvalidArgumentException("Invalid 2FA type");
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEmail(): bool
    {
        return $this->value === self::EMAIL;
    }

    public function isGoogle(): bool
    {
        return $this->value === self::GOOGLE;
    }
}