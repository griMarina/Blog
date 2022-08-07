<?php

namespace Grimarina\Blog_Project\Blog\Repositories;

use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;

    public function get(UUID $uuid): User;

    public function getByUsername(string $username): User;
}