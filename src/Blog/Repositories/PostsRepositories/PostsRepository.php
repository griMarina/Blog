<?php

namespace Grimarina\Blog_Project\Blog\Repositories\PostsRepositories;

use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Exceptions\{PostNotFoundException, PostsRepositoryException};
use Psr\Log\LoggerInterface;

class PostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger,
        )
    {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare('INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)');

        $statement->execute([
            ':uuid' => (string)$post->getUuid(),
            ':author_uuid' => (string)$post->getAuthor_uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(), 
        ]);

        $this->logger->info('Post ' . $post->getUuid() . ' created');
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare('SELECT * FROM posts WHERE uuid = :uuid');

        $statement->execute([
            'uuid' => (string)$uuid,
        ]);

        return $this->getPost($statement, $uuid);

    }

    public function getPost(\PDOStatement $statement, string $uuid): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            $message = "Cannot find post: $uuid";
            $this->logger->warning($message);
            
            throw new PostNotFoundException($message); 
        }

        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']), 
            $result['title'], 
            $result['text']
        );
    }

    public function delete(UUID $uuid): void
    {
        try {
            $statement = $this->connection->prepare('DELETE FROM posts WHERE uuid = :uuid');

            $statement->execute([
                'uuid' => (string)$uuid,
            ]);
        } catch (\PDOException $error) {
            throw new PostsRepositoryException($error->getMessage());
        }
        
    }
}