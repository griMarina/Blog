<?php

namespace Grimarina\Blog_Project\Blog\Repositories\UsersRepositories;

use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Exceptions\UserNotFoundException;
use Psr\Log\LoggerInterface;

class UsersRepository implements UsersRepositoryInterface
{
    public function __construct(
       private \PDO $connection,
       private LoggerInterface $logger,
    )
    {
    }

    public function save(User $user): void
    {

        $statement = $this->connection->prepare('INSERT INTO users (uuid, username, password, firstname, lastname) VALUES (:uuid, :username, :password, :firstname, :lastname)');

        $statement->execute([
            ':uuid' => (string)$user->getUuid(),
            ':username' => $user->getUsername(),
            ':password' => $user->getHashedPassword(),
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname(), 
        ]);

        $this->logger->info('User ' . $user->getUsername() . ' created');
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
            $message = "Cannot find user: $username";
            $this->logger->warning($message);
            
            throw new UserNotFoundException($message); 
        }

        return new User(
            new UUID($result['uuid']),
            $result['username'], 
            $result['password'], 
            $result['firstname'], 
            $result['lastname']
        );
    }
}