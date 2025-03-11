<?php

namespace TestsLoginMemoryPersistent\Application;

use LoginMemoryPersistent\Application\CreateUser;
use LoginMemoryPersistent\Domain\Exceptions\DuplicatedUserException;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\Repository\UserSearchRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    protected $userSearchRepository;
    protected $userSaveRepository;

    public function setUp(): void
    {
        $this->userSearchRepository = $this
            ->getMockBuilder(UserSearchRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userSaveRepository = $this
            ->getMockBuilder(UserSaveRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCorrectCreateUser() {
        $createUserUseCase = new CreateUser($this->userSaveRepository);

        $this->userSaveRepository
            ->expects($this->once())
            ->method('save');

        $this->assertTrue($createUserUseCase->addUser('user','password'));
    }

    public function testDuplicatedUserOnCreateUser() {
        $createUserUseCase = new CreateUser($this->userSaveRepository);

        $this->userSaveRepository
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new DuplicatedUserException()));

        $this->expectException(DuplicatedUserException::class);
        $createUserUseCase->addUser('user','password');
    }
}
