<?php

namespace Grimarina\Blog_Project\Blog\Repositories\UsersRepositories;

use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Exceptions\UserNotFoundException;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function get(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            if ((string)$user->getUuid() === (string)$uuid) {
                return $user;
            }
        }

        throw new UserNotFoundException("User not found: $uuid");   
    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $username");
    }
}