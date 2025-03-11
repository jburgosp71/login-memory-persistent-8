<?php

namespace TestsLoginMemoryPersistent\Application;

use LoginMemoryPersistent\Application\ChangePassword;
use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\Service\LoginService;
use PHPUnit\Framework\TestCase;
use TestsLoginMemoryPersistent\Shared\GenerateUser;

class ChangePasswordTest extends TestCase
{
    protected $userSaveRepository;
    protected $loginService;

    public function setUp(): void
    {
        $this->userSaveRepository = $this
            ->getMockBuilder(UserSaveRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginService = $this
            ->getMockBuilder(LoginService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCorrectChangePassword()
    {
        $changePasswordUseCase = new ChangePassword($this->userSaveRepository, $this->loginService);
        $returnUser = GenerateUser::getUser('user','password');

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->returnValue(true));

        $this->assertTrue($changePasswordUseCase->run($returnUser,'newPassword'));
    }

    public function testUnavailableUserOnChangePassword()
    {
        $changePasswordUseCase = new ChangePassword($this->userSaveRepository, $this->loginService);
        $returnUser = GenerateUser::getUser('user','password');

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->throwException(new UnavailableUserException()));

        $this->expectException(UnavailableUserException::class);
        $changePasswordUseCase->run($returnUser, 'newPassword');
    }

    public function testErrorLoginOnChangePassword()
    {
        $changePasswordUseCase = new ChangePassword($this->userSaveRepository, $this->loginService);
        $returnUser = GenerateUser::getUser('user','password');

        $this->loginService
            ->expects($this->once())
            ->method('tryLogin')
            ->will($this->throwException(new ErrorLoginException()));

        $this->expectException(ErrorLoginException::class);
        $changePasswordUseCase->run($returnUser, 'newPassword');
    }
}
