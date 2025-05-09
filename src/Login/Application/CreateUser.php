<?php

namespace LoginMemoryPersistent\Application;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Repository\UserSaveRepositoryInterface;
use LoginMemoryPersistent\Domain\ValueObjects\Password;
use LoginMemoryPersistent\Domain\ValueObjects\TwoFactorType;
use LoginMemoryPersistent\Domain\ValueObjects\Username;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;

class CreateUser
{
    private UserSaveRepositoryInterface $userSaveRepository;

    public function __construct(UserSaveRepositoryInterface $userSaveRepository)
    {
        $this->userSaveRepository = $userSaveRepository;
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function addUser(String $user, String $password, TwoFactorType $twoFactorType = null) : bool
    {
        $userEntity = new User(new Username($user), new Password($password));
        if ($twoFactorType !== null) {
            $this->activate2FA($userEntity, $twoFactorType);
        }
        $this->userSaveRepository->save($userEntity);

        return true;
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws SecretKeyTooShortException
     * @throws InvalidCharactersException
     */
    private function activate2FA(User $user, TwoFactorType $twoFactorType): void
    {
        if ($twoFactorType->isGoogle()) {
            $secret = (new Google2FA())->generateSecretKey();
            $user->enableTwoFactor(new TwoFactorType(TwoFactorType::GOOGLE), $secret);
        }
        if ($twoFactorType->isEmail()) {
            $user->enableTwoFactor(new TwoFactorType(TwoFactorType::EMAIL));
        }
    }
}