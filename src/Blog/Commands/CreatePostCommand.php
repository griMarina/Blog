<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;

class CreatePostCommand
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $this->postsRepository->save(new Post(
            UUID::random(),
            $arguments->get('author_uuid'),
            $arguments->get('title'),
            $arguments->get('text')
        ));
    }

}