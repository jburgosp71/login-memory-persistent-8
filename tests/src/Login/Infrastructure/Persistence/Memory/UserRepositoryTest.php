<?php

namespace TestsLoginMemoryPersistent\Infrastructure\Persistence\Memory;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\DuplicatedUserException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Infrastructure\Persistence\Memory\UserRepository;
use TestsLoginMemoryPersistent\Shared\GenerateUser;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{

    /**
     * @throws DuplicatedUserException
     */
    public function testSave()
    {
        $userRepository = new UserRepository();
        $testUser = GenerateUser::getUser('user','password');

        $this->assertEquals(null, $userRepository->findByUsername('user'));

        $userRepository->save($testUser);
        $this->assertTrue($userRepository->findByUsername('user') instanceof User);
    }

    /**
     * @throws UnavailableUserException
     * @throws DuplicatedUserException
     */
    public function testUpdate()
    {
        $userRepository = new UserRepository();
        $testUser = GenerateUser::getUser('user','password');
        $userRepository->save($testUser);
        $this->assertTrue($userRepository->findByUsername('user')->checkPassword('password'));

        $newPassword = new Password('newPassword');
        $testUser->setPassword($newPassword);
        $userRepository->update($testUser);
        $this->assertTrue($userRepository->findByUsername('user')->checkPassword('newPassword'));
    }

    /**
     * @throws DuplicatedUserException
     */
    public function testCorrectOnFindByUsername()
    {
        $userRepository = new UserRepository();
        $returnUser = GenerateUser::getUser('user','password');

        $userRepository->save($returnUser);

        $this->assertTrue($userRepository->findByUsername('user') instanceof User);
    }

    /**
     * @throws DuplicatedUserException
     */
    public function testNotCorrectFindByUsername()
    {
        $userRepository = new UserRepository();
        $returnUser = GenerateUser::getUser('user','password');

        $userRepository->save($returnUser);

        $this->assertFalse($userRepository->findByUsername('userNotCorrect') instanceof User);
    }
}
