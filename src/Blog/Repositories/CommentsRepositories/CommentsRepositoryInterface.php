<?php

namespace Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories;

use Grimarina\Blog_Project\Blog\{Comment, UUID};

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;

    public function get(UUID $uuid): Comment;
}