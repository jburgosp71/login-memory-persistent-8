<?php

namespace TestsLoginMemoryPersistent\Domain\Service;

use LoginMemoryPersistent\Application\CheckLogin;
use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSearchRepositoryInterface;
use LoginMemoryPersistent\Domain\Service\LoginService;
use PHPUnit\Framework\TestCase;
use TestsLoginMemoryPersistent\Shared\GenerateUser;

class LoginServiceTest extends TestCase
{
    protected $userSearchRepository;
    protected $loginService;

    public function setUp(): void
    {
        $this->userSearchRepository = $this
            ->getMockBuilder(UserSearchRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @throws UnavailableUserException
     * @throws ErrorLoginException
     */
    public function testCorrectLoginService()
    {
        $loginService = new LoginService($this->userSearchRepository);
        $returnUser = GenerateUser::getUser('user','password');

        $this->userSearchRepository
            ->expects($this->once())
            ->method('findByUsername')
            ->will($this->returnValue($returnUser));

        $this->assertTrue($loginService->tryLogin('user','password'));
    }

    /**
     * @throws ErrorLoginException
     */
    public function testUnavailableUserOnLoginService()
    {
        $loginService = new LoginService($this->userSearchRepository);

        $this->userSearchRepository
            ->expects($this->once())
            ->method('findByUsername')
            ->will($this->returnValue(null));

        $this->expectException(UnavailableUserException::class);
        $loginService->tryLogin('incorrectUser', 'password');
    }

    /**
     * @throws UnavailableUserException
     */
    public function testErrorLoginOnLoginService()
    {
        $loginService = new LoginService($this->userSearchRepository);
        $returnUser = GenerateUser::getUser('user','password');

        $this->userSearchRepository
            ->expects($this->once())
            ->method('findByUsername')
            ->will($this->returnValue($returnUser));

        $this->expectException(ErrorLoginException::class);
        $this->assertFalse($loginService->tryLogin('user','incorrectPassword'));
    }
}
