<?php

namespace LoginMemoryPersistent\Infrastructure\Persistence\Memory;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\DuplicatedUserException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\Repository\UserSearchRepositoryInterface;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\Username;

class UserRepository implements UserSaveRepositoryInterface, UserSearchRepositoryInterface
{
    protected array $userArray;

    public function __construct()
    {
        $this->userArray = [];
    }
    
    /**
     * @throws DuplicatedUserException
     */
    public function save(User $user) : void
    {
        if ($this->findByUsername($user->getUsername()) instanceof User) {
            throw new DuplicatedUserException();
        }

        $this->userArray[$user->getUsername()] = $this->encodePassword($user->getPassword());
    }

    /**
     * @throws UnavailableUserException
     */
    public function update(User $user) : void
    {
        if (!$this->findByUsername($user->getUsername()) instanceof User) {
            throw new UnavailableUserException();
        }

        $this->userArray[$user->getUsername()] = $this->encodePassword($user->getPassword());
    }

    public function findByUsername(String $username): ?User
    {
        if(isset($this->userArray[$username])) {
            $usernameValue = new Username($username);
            $passwordValue = new Password($this->decodePassword($this->userArray[$username]));
            return new User($usernameValue, $passwordValue);
        }

        return null;
    }

    private function encodePassword(string $password): String
    {
        return base64_encode($password);
    }

    private function decodePassword(string $encodedPassword): String
    {
        return base64_decode($encodedPassword);
    }

}