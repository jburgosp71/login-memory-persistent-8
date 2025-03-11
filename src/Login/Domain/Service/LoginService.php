<?php

namespace LoginMemoryPersistent\Domain\Service;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSearchRepositoryInterface;

class LoginService
{
    protected UserSearchRepositoryInterface $userSearchRepository;

    public function __construct(UserSearchRepositoryInterface $userSearchRepository)
    {
        $this->userSearchRepository = $userSearchRepository;
    }

    /**
     * @throws ErrorLoginException
     * @throws UnavailableUserException
     */
    public function tryLogin(String $username, String $password, $callback = null) : bool
    {
        $user = $this->userSearchRepository->findByUsername($username);
        if (!$user instanceof User) {
            throw new UnavailableUserException();
        }

        if (!$user->checkPassword($password))
        {
            throw new ErrorLoginException();
        }

        return true;
    }
}