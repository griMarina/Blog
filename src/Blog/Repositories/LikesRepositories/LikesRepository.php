<?php

namespace Grimarina\Blog_Project\Blog\Repositories\LikesRepositories;

use Grimarina\Blog_Project\Blog\{Like, UUID};
use Grimarina\Blog_Project\Exceptions\{LikeAlreadyExistsException, PostNotFoundException};

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

    public function getByPostUuid(UUID $post_uuid): array 
    {
        $statement = $this->connection->prepare('SELECT * FROM likes WHERE post_uuid = :post_uuid');

        $statement->execute([
            'post_uuid' => (string)$post_uuid,
        ]);
 
        $result = $statement->fetchAll();
        
        if (!$result) {
            throw new PostNotFoundException(
                "Cannot find post: $post_uuid"
            ); 
        }

       return $result;  
    }

    public function isLikeAlreadyExists(string $post_uuid, string $author_uuid): void
    {
        $statement = $this->connection->prepare('SELECT * FROM likes WHERE post_uuid = :post_uuid AND author_uuid = :author_uuid');
        $statement->execute([
            ':post_uuid' => $post_uuid,
            ':author_uuid' => $author_uuid
        ]);
        
        $result = $statement->fetchAll();
        
        if ($result) {
            throw new LikeAlreadyExistsException(
                "Like has already added"
            );
        }        
    }
}