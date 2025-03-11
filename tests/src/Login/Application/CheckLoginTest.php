<?php

namespace TestsLoginMemoryPersistent\Application;

use LoginMemoryPersistent\Application\CheckLogin;
use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSearchRepositoryInterface;
use LoginMemoryPersistent\Domain\Service\LoginService;
use PHPUnit\Framework\TestCase;

class CheckLoginTest extends TestCase
{
    protected $userSearchRepository;
    protected $loginService;

    public function setUp(): void
    {
        $this->userSearchRepository = $this
            ->getMockBuilder(UserSearchRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginService = $this
            ->getMockBuilder(LoginService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCheckCorrectLogin()
    {
        $checkLoginUseCase = new CheckLogin($this->loginService);

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->returnValue(true));

        $this->assertTrue($checkLoginUseCase->run('user','password'));
    }

    public function testUnavailableUserOnCheckLogin()
    {
        $checkLoginUseCase = new CheckLogin($this->loginService);

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->throwException(new UnavailableUserException()));

        $this->expectException(UnavailableUserException::class);
        $checkLoginUseCase->run('incorrectUser', 'password');
    }

    public function testErrorLoginOnCheckLogin()
    {
        $checkLoginUseCase = new CheckLogin($this->loginService);

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->throwException(new ErrorLoginException()));

        $this->expectException(ErrorLoginException::class);
        $this->assertFalse($checkLoginUseCase->run('user','incorrectPassword'));
    }
}
