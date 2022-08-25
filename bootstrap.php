<?php

use Grimarina\Blog_Project\Blog\Container\DIContainer;
use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\{CommentsRepositoryInterface, CommentsRepository};
use Grimarina\Blog_Project\Blog\Repositories\LikesRepositories\{LikesRepository, LikesRepositoryInterface};
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\{PostsRepository, PostsRepositoryInterface};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\{UsersRepository, UsersRepositoryInterface};
use Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories\{AuthTokensRepositoryInterface, AuthTokensRepository};
use Grimarina\Blog_Project\http\Auth\{AuthenticationInterface, BearerTokenAuthentication, IdentificationInterface, JsonBodyUuidIdentification, PasswordAuthentication, PasswordAuthenticationInterface, TokenAuthenticationInterface};
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;

require_once __DIR__ . "/vendor/autoload.php";

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);

$logger = (new Logger('blog'));

if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(
            new StreamHandler(__DIR__ . '/logs/blog.log'))
        ->pushHandler(
            new StreamHandler(__DIR__ . '/logs/blog.error.log', 
            level: Logger::ERROR,
            bubble: false,
        )
    );
}

if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler('php://stdout')
        );
}

$container->bind(
    LoggerInterface::class,
    $logger
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

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    AuthTokensRepository::class
);

$container->bind( 
    TokenAuthenticationInterface::class, 
    BearerTokenAuthentication::class
);

return $container;