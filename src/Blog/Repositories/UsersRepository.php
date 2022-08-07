<?php

namespace Grimarina\Blog_Project\Blog\Repositories;

use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Blog\Exceptions\UserNotFoundException;

class UsersRepository implements UsersRepositoryInterface
{
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare('INSERT INTO users (uuid, username, firstname, lastname) VALUES (:uuid, :username, :firstname, :lastname)');

        $statement->execute([
            ':uuid' => (string)$user->getUuid(),
            ':username' => $user->getUsername(),
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname(), 
        ]);
    }

    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE uuid = :uuid');

        $statement->execute([
            'uuid' => (string)$uuid,
        ]);

        return $this->getUser($statement, $uuid);

    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE username = :username');

        $statement->execute([
            ':username' => $username,
        ]);

        return $this->getUser($statement, $username);
    }

    public function getUser(\PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            ); 
        }

        return new User(
            new UUID($result['uuid']),
            $result['username'], 
            $result['firstname'], 
            $result['lastname']
        );
    }
}