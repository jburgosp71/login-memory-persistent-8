<?php

namespace TestsLoginMemoryPersistent\Domain\ValueObjects;

use LoginMemoryPersistent\Domain\ValueObjects\Username;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    public function testUsername()
    {
        $usernameTestA = new Username('username');
        $usernameTestB = new Username('username');

        $this->assertTrue($usernameTestA->equals($usernameTestA));
        $this->assertFalse($usernameTestA->equals($usernameTestB));
    }
}
