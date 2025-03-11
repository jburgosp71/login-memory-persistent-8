<?php

namespace LoginMemoryPersistent\Application;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\Service\LoginService;
use LoginMemoryPersistent\Domain\ValueObjects\Password;

class ChangePassword
{
    protected $userSaveRepository;
    protected $loginService;

    /**
     * ChangePassword constructor.
     * @param UserSaveRepositoryInterface $userSaveRepository
     * @param LoginService $loginService
     */
    public function __construct(
        UserSaveRepositoryInterface $userSaveRepository,
        LoginService $loginService
    )
    {
        $this->userSaveRepository = $userSaveRepository;
        $this->loginService = $loginService;
    }

    /**
     * @throws UnavailableUserException
     * @throws ErrorLoginException
     */
    public function run(User $user, String $newPassword) : bool
    {
        if ($this->loginService->tryLogin($user->getUsername(), $user->getPassword()))
        {
           $changePassword = new Password($newPassword);
           $user->setPassword($changePassword);
           $this->userSaveRepository->update($user);
        }

        return true;
    }
}