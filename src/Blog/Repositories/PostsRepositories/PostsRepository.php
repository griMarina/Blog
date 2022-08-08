<?php

namespace Grimarina\Blog_Project\Blog\Repositories\PostsRepositories;

use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Blog\Exceptions\PostNotFoundException;

class PostsRepository implements PostsRepositoryInterface
{
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare('INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)');

        $statement->execute([
            ':uuid' => (string)$post->getUuid(),
            ':author_uuid' => $post->getAuthor_uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(), 
        ]);
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
            throw new PostNotFoundException(
                "Cannot find post: $uuid"
            ); 
        }

        return new Post(
            new UUID($result['uuid']),
            $result['author_uuid'], 
            $result['title'], 
            $result['text']
        );
    }
}