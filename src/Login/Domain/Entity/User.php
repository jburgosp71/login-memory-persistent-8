<?php

namespace LoginMemoryPersistent\Domain\Entity;

use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use PhpParser\Node\Scalar\String_;

class User
{
    protected Username $username;
    protected Password $password;

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
}