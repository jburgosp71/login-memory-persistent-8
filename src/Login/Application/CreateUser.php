<?php

namespace LoginMemoryPersistent\Application;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;

class CreateUser
{
    private UserSaveRepositoryInterface $userSaveRepository;

    public function __construct(UserSaveRepositoryInterface $userSaveRepository)
    {
        $this->userSaveRepository = $userSaveRepository;
    }

    public function addUser(String $user, String $password) : bool
    {
        $userEntity = new User(new Username($user), new Password($password));
        $this->userSaveRepository->save($userEntity);

        return true;
    }
}