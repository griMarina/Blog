<?php

namespace Grimarina\Blog_Project\Blog\Commands\Users;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\{User, UUID};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
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
            ->setName('users:update') 
            ->setDescription('Updates a user') 
            ->addArgument(
                'uuid', 
                InputArgument::REQUIRED, 
                'UUID of a user to update',
        ) 
        ->addOption(
            'firstname',
            'f',
            InputOption::VALUE_OPTIONAL,
            'First name',
        ) 
        ->addOption(
            'lastname',
            'l', 
            InputOption::VALUE_OPTIONAL, 
            'Last name',
        );
    }

    protected function execute( 
        InputInterface $input, 
        OutputInterface $output,
    ): int 
    {
        $firstName = $input->getOption('firstname'); 
        $lastName = $input->getOption('lastname');

        if (empty($firstName) && empty($lastName)) { 
            $output->writeln('Nothing to update'); 
            return Command::SUCCESS;
        }
    
        $uuid = new UUID($input->getArgument('uuid'));  

        $user = $this->usersRepository->get($uuid);

        $updatedUser = new User(
            uuid: $uuid,
            username: $user->getUsername(),
            hashedPassword: $user->getHashedPassword(),
            firstname: empty($firstName)
                ? $user->getFirstname() : $firstName,
            lastname: empty($lastName)
            ? $user->getLastname() : $lastName,
        );
        
        $this->usersRepository->save($updatedUser); 
        
        $output->writeln('User updated: ' . $user->getUsername());

        return Command::SUCCESS; 
    }
}