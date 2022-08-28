<?php

namespace Grimarina\Blog_Project\UnitTests\Commands;

use Grimarina\Blog_Project\Blog\Commands\Users\CreateUser;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Exceptions\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserCommandTest extends TestCase
{

    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User

            {
                throw new UserNotFoundException("Not found");
            }
        };
    }

    // public function testItRequiresLastName(): void
    // {
    //     $command = new CreateUser(
    //         $this->makeUsersRepository(),
    //     );

    //     $this->expectException(RuntimeException::class);
    //     $this->expectExceptionMessage('Not enough arguments (missing "lastname").');

    //     $command->run(
    //         new ArrayInput([
    //             'username' => 'Ivan',
    //             'password' => 'some_password',
    //             'firstname' => 'Ivan'
    //         ]),
    //         new NullOutput()
    //     );
    // }

    public function testItRequiresPassword(): void
    {
        $command = new CreateUser(
            $this->makeUsersRepository()
        );
        
        $this->expectException(RuntimeException::class); $this->expectExceptionMessage('Not enough arguments (missing: "firstname, lastname, password"');
        
        $command->run(
            new ArrayInput([
                'username' => 'Ivan', 
            ]),
            new NullOutput() 
        );
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUser( 
            $this->makeUsersRepository()
        );

        $this->expectException(RuntimeException::class); $this->expectExceptionMessage('Not enough arguments (missing: "firstname, lastname").');

        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password', 
            ]),
        new NullOutput() 
        );
    }

    public function testItSavesUserToRepository(): void 
    {
        $usersRepository = new class implements UsersRepositoryInterface { 
            private bool $called = false;

            public function save(User $user): void
            {
                $this->called = true;
            }

            public function get(UUID $uuid): User
            {

                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUser($usersRepository);

        $command->run( 
            new ArrayInput([
                'username' => 'Ivan', 
                'password' => 'some_password', 
                'firstname' => 'Ivan', 
                'lastname' => 'Nikitin',
            ]),
            new NullOutput() 
        );

        $this->assertTrue($usersRepository->wasCalled());
    }
}