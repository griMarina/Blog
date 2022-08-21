<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;
use Psr\Log\LoggerInterface;

class CreatePostCommand
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private LoggerInterface $logger
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create post command started");

        $this->postsRepository->save(new Post(
            UUID::random(),
            UUID::random(),
            $arguments->get('title'),
            $arguments->get('text')
        ));

        $this->logger->info("Post created");
    }

}