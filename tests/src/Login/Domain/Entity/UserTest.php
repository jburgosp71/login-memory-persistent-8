<?php

namespace TestsLoginMemoryPersistent\Domain\Entity;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $username = new Username('username');
        $password = new Password('password');

        $user = new User($username, $password);

        $this->assertEquals($username->__toString(), $user->getUsername());
        $this->assertEquals($password->__toString(), $user->getPassword());
    }
}
