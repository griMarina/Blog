<?php

namespace Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories;

use Grimarina\Blog_Project\Blog\{Comment, UUID};
use Grimarina\Blog_Project\Blog\Exceptions\CommentNotFoundException;

class CommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare('INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)');

        $statement->execute([
            ':uuid' => (string)$comment->getUuid(),
            ':post_uuid' => $comment->getPost_uuid(),
            ':author_uuid' => $comment->getAuthor_uuid(),
            ':text' => $comment->getText(), 
        ]);
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
            throw new CommentNotFoundException(
                "Cannot find comment: $uuid"
            ); 
        }

        return new Comment(
            new UUID($result['uuid']),
            $result['post_uuid'], 
            $result['author_uuid'], 
            $result['text']
        );
    }
}