<?php

namespace LoginMemoryPersistent\Domain\ValueObjects;

class Username
{
    private string $username;

    public function __construct(String $username)
    {
        $this->username = $username;
    }

    public function equals(Username $username) : bool
    {
        return $this === $username;
    }

    public function __toString() : String
    {
        return $this->username;
    }
}