<?php

namespace Grimarina\Blog_Project\Blog\Repositories\LikesRepositories;

use Grimarina\Blog_Project\Blog\{Like, UUID};
use Grimarina\Blog_Project\Exceptions\PostNotFoundException;

class LikesRepository implements LikesRepositoryInterface
{
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare('INSERT INTO likes (uuid, post_uuid, author_uuid) VALUES (:uuid, :post_uuid, :author_uuid)');

        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':post_uuid' => (string)$like->getPost_uuid(),
            ':author_uuid' => (string)$like->getAuthor_uuid(),
        ]);
    }

    public function getByPostUuid(UUID $post_uuid): Like
    {
        $statement = $this->connection->prepare('SELECT * FROM likes WHERE post_uuid = :post_uuid');

        $statement->execute([
            'post_uuid' => (string)$post_uuid,
        ]);
        

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $post_uuid"
            ); 
        }

        return new Like(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']), 
            new UUID($result['author_uuid']), 
        );
    }

       // return $this->getLikes($statement, $post_uuid);

}

    // public function getLikes(\PDOStatement $statement, string $post_uuid): Like
    // {
    //     $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
    //     if ($result === false) {
    //         throw new LikeNotFoundException(
    //             "Cannot find like: $uuid"
    //         ); 
    //     }

    //     return new Comment(
    //         new UUID($result['uuid']),
    //         new UUID($result['post_uuid']), 
    //         new UUID($result['author_uuid']), 
    //         $result['text']
    //     );
    // }
