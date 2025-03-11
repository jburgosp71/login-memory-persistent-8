<?php

namespace LoginMemoryPersistent\Domain\Repository;

use LoginMemoryPersistent\Domain\Entity\User;

interface UserSaveRepositoryInterface
{
    function save(User $user);
    function update(User $user);
}