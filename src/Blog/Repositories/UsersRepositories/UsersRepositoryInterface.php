<?php

namespace Grimarina\Blog_Project\Blog\Repositories\UsersRepositories;

use Grimarina\Blog_Project\Blog\{User, UUID};

interface UsersRepositoryInterface
{
    public function save(User $user): void;

    public function get(UUID $uuid): User;

    public function getByUsername(string $username): User;
}