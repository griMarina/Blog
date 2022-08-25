<?php

namespace Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Grimarina\Blog_Project\Blog\{AuthToken, UUID};
use Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories\AuthTokensRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{AuthTokenNotFoundException, AuthTokensRepositoryException};
use PDO;

class AuthTokensRepository implements AuthTokensRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
    )
    {
    }

    public function save(AuthToken $authToken): void
    {
        $query = <<<SQL
            INSERT INTO tokens (
                token,
                user_uuid,
                expires_on
            ) VALUES (
            :token,
            :user_uuid,
            :expires_on
            )
            ON CONFLICT (token) DO UPDATE SET expires_on = :expires_on
        SQL;

        try {
            $statement = $this->connection->prepare($query); 
            $statement->execute([
                ':token' => (string)$authToken->token(),
                ':user_uuid' => (string)$authToken->userUuid(), 
                ':expires_on' => $authToken->expiresOn()->format(DateTimeInterface::ATOM),
            ]);
        } catch (\PDOException $error) {
            throw new AuthTokensRepositoryException(
                $error->getMessage(), (int)$error->getCode(), $error
            ); 
        }
    }

    public function get(string $token): AuthToken
    {
        try {
       
            $statement = $this->connection->prepare('SELECT * FROM tokens WHERE token = :token');
            $statement->execute([
                ':token' => $token,
            ]);

            $result = $statement->fetch(PDO::FETCH_ASSOC);

        } catch (\PDOException $error) {
            throw new AuthTokensRepositoryException(
                $error->getMessage(), (int)$error->getCode(), $error 
            );
        }
        
        if ($result === false) {
            throw new AuthTokenNotFoundException("Cannot find token: $token");
        }
        
        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $error) {
            throw new AuthTokensRepositoryException( 
                $error->getMessage(), $error->getCode(), $error
            ); 
        }
    }
}