<?php

namespace TestsLoginMemoryPersistent\Shared;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;

class GenerateUser
{
    /**
     * @param String $username
     * @param String $password
     * @return User
     */
    public static function getUser(String $username, String $password): User
    {

        $username = new Username($username);
        $password = new Password($password);
        return new User($username, $password);
    }
}