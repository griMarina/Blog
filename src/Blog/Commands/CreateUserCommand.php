<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Exceptions\{CommandException, UserNotFoundException};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger,
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');
       
        if ($this->userExists($username)) {
            $this->logger->warning("User: $username already exists");
            return;                
        }

        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            $arguments->get('firstname'),
            $arguments->get('lastname'),
        );
       
        $this->usersRepository->save($user);

        $this->logger->info("User created: $username");
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