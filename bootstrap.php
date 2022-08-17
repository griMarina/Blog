<?php

use Grimarina\Blog_Project\Blog\Container\DIContainer;
use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\{CommentsRepositoryInterface, CommentsRepository};
use Grimarina\Blog_Project\Blog\Repositories\LikesRepositories\{LikesRepository, LikesRepositoryInterface};
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\{PostsRepository, PostsRepositoryInterface};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\{UsersRepository, UsersRepositoryInterface};

require_once __DIR__ . "/vendor/autoload.php";

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostsRepositoryInterface::class,
    PostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    UsersRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    CommentsRepository::class
);

$container->bind(
    LikesRepositoryInterface::class,
    LikesRepository::class
);

return $container;