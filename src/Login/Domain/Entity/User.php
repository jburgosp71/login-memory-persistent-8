<?php

namespace LoginMemoryPersistent\Domain\Entity;

use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use LoginMemoryPersistent\Domain\ValueObjects\TwoFactorType;

class User
{
    protected Username $username;
    protected Password $password;
    protected ?TwoFactorType $twoFactorType = null;
    protected ?string $googleSecret = null;

    public function __construct(Username $username, Password $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): String
    {
        return $this->username->__toString();
    }

    /**
     * @param Username $username
     */
    public function setUsername(Username $username) : void
    {
        $this->username = $username;
    }

    public function getPassword(): String
    {
        return $this->password->__toString();
    }

    public function checkPassword(String $password): bool
    {
        return $this->password->verifyPassword($password);
    }

    public function setPassword(Password $password) : void
    {
        $this->password = $password;
    }

    public function enableTwoFactor(TwoFactorType $type, ?string $googleSecret = null): void
    {
        $this->twoFactorType = $type;
        $this->googleSecret = $googleSecret;
    }

    public function getTwoFactorType(): ?TwoFactorType
    {
        return $this->twoFactorType;
    }

    public function getGoogleSecret(): ?string
    {
        return $this->googleSecret;
    }

    public function setTwoFactorType(?string $type): void
    {
        $this->twoFactorType = new TwoFactorType($type);
    }

    public function setGoogleSecret(?string $secret): void
    {
        $this->googleSecret = $secret;
    }
}