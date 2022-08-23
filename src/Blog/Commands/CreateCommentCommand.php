<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Comment, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;
use Psr\Log\LoggerInterface;

class CreateCommentCommand
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
        private LoggerInterface $logger
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create comment command started");

        $this->commentsRepository->save(new Comment(
            UUID::random(),
            UUID::random(),
            UUID::random(),
            $arguments->get('text')
        ));

        $this->logger->info("Comment created");
    }

}