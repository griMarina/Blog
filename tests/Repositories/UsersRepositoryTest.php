<?php

namespace Repositories;

use Grimarina\Blog_Project\Blog\Exceptions\UserNotFoundException;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepository;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Blog\User;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UsersRepositoryTest extends TestCase 
{

    public function testItSavesUserToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000', 
            ':username' => 'ivan123',
            ':firstname' => 'Ivan',
            ':lastname' => 'Nikitin',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new UsersRepository($connectionStub);

        $repository->save(new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'ivan123',
            'Ivan',
            'Nikitin'
        ));
    }


    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionMock = $this->createMock(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

    
        $repository = new UsersRepository($connectionMock);
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user: ivan123');

        $repository->getByUsername('ivan123');
    }
}
