<?php

namespace Grimarina\Blog_Project\Blog\Commands;

use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Comment, UUID};
use Grimarina\Blog_Project\Blog\Commands\Arguments;

class CreateCommentCommand
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    )
    {     
    }

    public function handle(Arguments $arguments): void
    {
        $this->commentsRepository->save(new Comment(
            UUID::random(),
            $arguments->get('post_uuid'),
            $arguments->get('author_uuid'),
            $arguments->get('text')
        ));
    }

}