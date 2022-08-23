<?php

namespace Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories;

use Grimarina\Blog_Project\Blog\{Comment, UUID};
use Grimarina\Blog_Project\Exceptions\CommentNotFoundException;
use Psr\Log\LoggerInterface;

class CommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger
    )
    {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare('INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)');

        $statement->execute([
            ':uuid' => (string)$comment->getUuid(),
            ':post_uuid' => (string)$comment->getPost_uuid(),
            ':author_uuid' => (string)$comment->getAuthor_uuid(),
            ':text' => $comment->getText(), 
        ]);

        $this->logger->info('Comment ' . $comment->getUuid() . ' created');

    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare('SELECT * FROM comments WHERE uuid = :uuid');

        $statement->execute([
            'uuid' => (string)$uuid,
        ]);

        return $this->getComment($statement, $uuid);

    }

    public function getComment(\PDOStatement $statement, string $uuid): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        if ($result === false) {
            $message = "Cannot find comment: $uuid";
            $this->logger->warning($message);

            throw new CommentNotFoundException($message); 
        }

        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']), 
            new UUID($result['author_uuid']), 
            $result['text']
        );
    }
}