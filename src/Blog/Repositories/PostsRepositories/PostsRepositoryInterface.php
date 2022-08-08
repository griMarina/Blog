<?php

namespace Grimarina\Blog_Project\Blog\Repositories\PostsRepositories;

use Grimarina\Blog_Project\Blog\{Post, UUID};

interface PostsRepositoryInterface
{
    public function save(Post $post): void;

    public function get(UUID $uuid): Post;
}