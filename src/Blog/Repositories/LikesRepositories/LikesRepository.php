<?php

namespace Grimarina\Blog_Project\Blog\Repositories\LikesRepositories;

use Grimarina\Blog_Project\Blog\{Like, UUID};
use Grimarina\Blog_Project\Exceptions\LikeAlreadyExistException;
use Grimarina\Blog_Project\Exceptions\PostNotFoundException;

class LikesRepository implements LikesRepositoryInterface
{
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
    {

        $statement = $this->connection->prepare('SELECT author_uuid FROM likes WHERE post_uuid = :post_uuid');
        $statement->execute([
            ':post_uuid' => (string)$like->getPost_uuid(),
        ]);

        $arr = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $arr[] = $row;
        }

        foreach ($arr as $row) {

            if ($row['author_uuid'] == (string)$like->getAuthor_uuid()) {
                throw new LikeAlreadyExistException(
                    "Like has already added"
                );
            }
        }

        $statement = $this->connection->prepare('INSERT INTO likes (uuid, post_uuid, author_uuid) VALUES (:uuid, :post_uuid, :author_uuid)');

        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':post_uuid' => (string)$like->getPost_uuid(),
            ':author_uuid' => (string)$like->getAuthor_uuid(),
        ]);    
    }

    public function getByPostUuid(UUID $post_uuid): array 
    {
        $statement = $this->connection->prepare('SELECT uuid FROM likes WHERE post_uuid = :post_uuid');

        $statement->execute([
            'post_uuid' => (string)$post_uuid,
        ]);
 
        $likes = [];
        while ($like = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $likes[] = $like;
        }
        
        if (empty($likes)) {
            throw new PostNotFoundException(
                "Cannot find post: $post_uuid"
            ); 
        }

       return $likes;  
    }
}