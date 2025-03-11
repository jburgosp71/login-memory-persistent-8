<?php

namespace LoginMemoryPersistent\Domain\Repository;

interface UserSearchRepositoryInterface
{
    function findByUsername(String $username);
}