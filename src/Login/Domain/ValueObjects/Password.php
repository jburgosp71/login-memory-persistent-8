<?php

namespace LoginMemoryPersistent\Domain\ValueObjects;

class Password
{
    private String $hashedPassword;
    private String $password;

    public function __construct(String $password)
    {
        $this->password = $password;
        $this->hashedPassword = $this->hashPassword($password);
    }

    public function equals(Password $password) : bool
    {
        return $this === $password;
    }

    public function __toString() : String
    {
        return $this->password;
    }

    public function verifyPassword(string $givenPassword) : bool
    {
        return password_verify($givenPassword, $this->hashedPassword);
    }

    private static function hashPassword(string $password) : String
    {
        return password_hash($password,  PASSWORD_DEFAULT);
    }
}