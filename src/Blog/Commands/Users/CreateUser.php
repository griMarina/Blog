<?php

namespace Grimarina\Blog_Project\Blog\Commands\Users;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Exceptions\UserNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('users:create')
            ->setDescription('Create new user')
            ->addArgument('firstname', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastname', InputArgument::REQUIRED, 'Last name')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');

    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $output->writeln('Create user command started');

        $username = $input->getArgument('username');

        if ($this->userExists($username)) {
            $output->writeln("User already exists: $username");

            return Command::FAILURE;
        }

        $user = User::createFrom(
            $username,
            $input->getArgument('password'),
            $input->getArgument('firstname'),
            $input->getArgument('lastname')
        );

        $this->usersRepository->save($user);

        $output->writeln("User created: $username");

        return Command::SUCCESS;
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