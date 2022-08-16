<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Exceptions\{CommandException, UserNotFoundException};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            throw new CommandException(
                "User: $username already exists"
            );
        }

        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            $arguments->get('firstname'),
            $arguments->get('lastname'),
        ));
    }

    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }
}