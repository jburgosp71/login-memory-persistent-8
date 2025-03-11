<?php

namespace LoginMemoryPersistent\Application;

use LoginMemoryPersistent\Domain\Exceptions\ErrorLoginException;
use LoginMemoryPersistent\Domain\Exceptions\UnavailableUserException;
use LoginMemoryPersistent\Domain\Service\LoginService;

class CheckLogin
{
    protected LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @throws ErrorLoginException
     * @throws UnavailableUserException
     */
    public function run(String $username, String $password) : bool
    {
        return $this->loginService->tryLogin($username, $password);
    }
}